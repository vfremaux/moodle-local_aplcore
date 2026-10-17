<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Patch manager for local_aplcore.
 * Applies the normalized patches stored in the __patch / __reference
 * directories of plugins onto the Moodle core code.
 *
 * @package     local_aplcore
 * @author      Valery Fremaux (valery.fremaux@gmail.com)
 * @copyright   2026 Valery Fremaux (www.activeprolearn.com)
 * @license     https://www.gnu.org/copyleft/gpl.html GNU Public License
 */
namespace local_aplcore;

// phpcs:disable moodle.Commenting.ValidTags.Invalid
// Abusive PSR12 rule : adds useless spaces in string concatenation.
// phpcs:disable PSR12.Operators.OperatorSpacing.NoSpaceBefore
// phpcs:disable PSR12.Operators.OperatorSpacing.NoSpaceAfter

use core_component;
use core_text;

/**
 * Scans, validates and applies the core patches stored in the
 * __patch / __reference directories of a plugin.
 *
 * Settings used (local_aplcore admin_setting):
 *  - local_aplcore/sudoer              : system sudoer user allowed to write
 *                                         into the core codebase (NOPASSWD required).
 *                                         If empty, writing is attempted directly
 *                                         (case where the web server already owns the sources).
 *  - local_aplcore/backuppathedfiles   : 1/0, creates a backup of the core file before writing.
 *  - local_aplcore/divergencethreshold : divergence threshold (%, 0-100) above which
 *                                         a patch is skipped. Default: 30.
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class patch_manager {
    /** @var int initial number of context lines for pre/post-patterns. */
    const MIN_CONTEXT_LINES = 1;

    /** @var int maximum number of context lines before giving up (ambiguity). */
    const MAX_CONTEXT_LINES = 12;

    /** @var float default divergence threshold, in percent. */
    const DEFAULT_DIVERGENCE_THRESHOLD = 30.0;

    /** @var string|null $sudoeruser declared in the settings. */
    protected $sudoeruser;

    /** @var bool $dobackup create a backup before any write. */
    protected $dobackup;

    /** @var float divergence threshold (%) above which a patch is skipped. */
    protected $divergencethreshold;

    /** @var array $report cumulative report of the operations performed. */
    protected $report = [];

    /**
     * Constructor.
     */
    public function __construct() {
        $this->sudoeruser = trim((string) get_config('local_aplcore', 'sudoer'));
        $this->dobackup = (bool) get_config('local_aplcore', 'backuppatchedfiles');

        $threshold = get_config('local_aplcore', 'divergencethreshold');
        $this->divergencethreshold = ($threshold !== false && $threshold !== '')
            ? (float) $threshold
            : self::DEFAULT_DIVERGENCE_THRESHOLD;
    }

    // Entry point: scan.

    /**
     * Recursively scans a plugin's __patch directory and processes every
     * patch file found through the worker function.
     *
     * @param string|null $patchroot  absolute path of the __patch directory to scan.
     *                                Defaults to local_aplcore's own __patch directory.
     * @param string|null $pluginroot absolute path of the plugin root
     *                                (containing __patch and __reference).
     *                                Defaults to local_aplcore's own plugin root.
     * @return array the execution report (see get_report()).
     */
    public function scan_patches(?string $patchroot = null, ?string $pluginroot = null): array {
        if ($pluginroot === null) {
            $pluginroot = core_component::get_plugin_directory('local', 'aplcore');
        }
        if ($patchroot === null) {
            $patchroot = $pluginroot . '/__patch';
        }
        $referenceroot = $pluginroot . '/__reference';

        if (!is_dir($patchroot)) {
            $this->add_report_entry(null, 'error', "Patch directory not found: {$patchroot}");
            return $this->report;
        }

        $realpatchroot = realpath($patchroot);
        if ($realpatchroot === false) {
            $this->add_report_entry(null, 'error', "Unable to resolve the real path of: {$patchroot}");
            return $this->report;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($realpatchroot, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $fileinfo) {
            if (!$fileinfo->isFile()) {
                continue;
            }
            $realfile = realpath($fileinfo->getPathname());
            if ($realfile === false) {
                continue;
            }
            $relpath = ltrim(str_replace('\\', '/', core_text::substr($realfile, core_text::strlen($realpatchroot))), '/');
            if ($relpath === '') {
                continue;
            }
            // Worker function.
            $this->process_patch_file($relpath, $realpatchroot, $referenceroot);
        }

        return $this->report;
    }

    // Worker function: processes one patch file.

    /**
     * Processes a single patch file: identifies its PATCH+/PATCH- blocks,
     * locates the real core file, checks the divergence against
     * __reference, applies the relevant patches and rewrites the core file.
     * @param string $relpath
     * @param string $patchroot
     * @param string $referenceroot
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function process_patch_file(string $relpath, string $patchroot, string $referenceroot): void {
        global $CFG;

        $patchfile = $patchroot.'/'.$relpath;
        $referencefile = $referenceroot.'/'.$relpath;
        $corefile = $CFG->dirroot.'/'.$relpath;

        $patchlines = $this->read_lines($patchfile);
        if ($patchlines === null) {
            $this->add_report_entry($relpath, 'error', "Unable to read the patch file.");
            return;
        }

        if (!file_exists($corefile)) {
            $this->add_report_entry($relpath, 'error', "Core file not found: {$corefile}");
            return;
        }
        $corelines = $this->read_lines($corefile);
        if ($corelines === null) {
            $this->add_report_entry($relpath, 'error', "Unable to read the core file: {$corefile}");
            return;
        }

        // Checks the existence of the corresponding __reference file, without ever throwing an exception.
        $hasreference = file_exists($referencefile);
        $referencelines = null;
        if ($hasreference) {
            $referencelines = $this->read_lines($referencefile);
            if ($referencelines === null) {
                $this->add_report_entry(
                    $relpath,
                    'warning',
                    "__reference file present but unreadable: {$referencefile} (continuing without divergence check)."
                );
                $hasreference = false;
            }
        } else {
            $this->add_report_entry(
                $relpath,
                'warning',
                "No matching __reference file found (continuing without divergence check)."
            );
        }

        $blocks = $this->extract_patch_blocks($patchlines, $relpath);
        if (empty($blocks)) {
            $this->add_report_entry($relpath, 'info', "No usable PATCH+/PATCH- block found.");
            return;
        }

        $workinglines = $corelines;
        $modified = false;

        foreach ($blocks as $block) {
            $result = $this->apply_block($patchlines, $block, $workinglines, $referencelines, $hasreference);
            if ($result['applied']) {
                $workinglines = $result['lines'];
                $modified = true;
                $this->add_report_entry(
                    $relpath,
                    'ok',
                    "Patch applied [{$block['reason']}]: {$result['message']}"
                );
            } else {
                $this->add_report_entry(
                    $relpath,
                    $result['status'],
                    "Patch skipped [{$block['reason']}]: {$result['message']}"
                );
            }
        }

        if (!$modified) {
            return;
        }

        if ($this->dobackup) {
            if (!$this->backup_corefile($corefile)) {
                $this->add_report_entry(
                    $relpath,
                    'error',
                    "Backup failed: writing the core file was cancelled for safety."
                );
                return;
            }
        }

        $newcontent = implode('', $workinglines);
        if ($this->write_corefile($corefile, $newcontent)) {
            $this->add_report_entry($relpath, 'ok', "Core file rewritten: {$corefile}");
        } else {
            $this->add_report_entry($relpath, 'error', "Failed to write the core file: {$corefile}");
        }
    }

    // Extraction of PATCH+/PATCH- blocks.

    /**
     * Identifies all the PATCH+/PATCH- blocks of a patch file.
     * Markers are line comments, possibly indented:
     *   // PATCH+ : <reason>.
     *   ...
     *   // PATCH-.
     *
     * @param array $lines
     * @param string $relpath
     * @return array list of blocks: openidx, closeidx, reason, content,
     *               lowerbound, upperbound (bounds for the pre/post-patterns).
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function extract_patch_blocks(array $lines, string $relpath): array {
        $openpattern = '/^\s*\/\/\s*PATCH\+\s*:\s*(.*?)\.\s*$/';
        $closepattern = '/^\s*\/\/\s*PATCH-\.\s*$/';

        $blocks = [];
        $openidx = null;
        $reason = null;

        foreach ($lines as $idx => $line) {
            if ($openidx === null) {
                if (preg_match($openpattern, $line, $m)) {
                    $openidx = $idx;
                    $reason = trim($m[1]);
                }
                continue;
            }

            if (preg_match($closepattern, $line)) {
                $blocks[] = [
                    'openidx' => $openidx,
                    'closeidx' => $idx,
                    'reason' => $reason,
                    'content' => array_slice($lines, $openidx + 1, $idx - $openidx - 1),
                ];
                $openidx = null;
                $reason = null;
            } else if (preg_match($openpattern, $line, $m)) {
                // Nested blocks are not supported by the convention: we report it and
                // restart from the new marker (the previous, unclosed block is discarded).
                $this->add_report_entry(
                    $relpath,
                    'warning',
                    "Nested PATCH+ marker detected at line ".($idx + 1).
                    "; block opened at line " . ($openidx + 1) . " discarded."
                );
                $openidx = $idx;
                $reason = trim($m[1]);
            }
        }

        if ($openidx !== null) {
            $this->add_report_entry(
                $relpath,
                'error',
                "PATCH+ marker without a matching PATCH- (line " . ($openidx + 1) . ")."
            );
        }

        // Bounds: a block cannot pick its context beyond a neighbouring block.
        $count = count($blocks);
        for ($i = 0; $i < $count; $i++) {
            $blocks[$i]['lowerbound'] = ($i > 0) ? $blocks[$i - 1]['closeidx'] + 1 : 0;
            $blocks[$i]['upperbound'] = ($i < $count - 1) ? $blocks[$i + 1]['openidx'] - 1 : count($lines) - 1;
        }

        return $blocks;
    }

    // Applying a block.

    /**
     * Locates the insertion/replacement point of a block in the core file
     * (via pre/post-pattern), checks the local divergence against
     * __reference, then applies the patch if relevant.
     *
     * @param array $patchlines
     * @param array $block
     * @param array $corelines
     * @param array|null $referencelines
     * @param bool $hasreference
     * @return array ['applied' => bool, 'status' => string, 'message' => string, 'lines' => array]
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function apply_block(
        array $patchlines,
        array $block,
        array $corelines,
        ?array $referencelines,
        bool $hasreference
    ): array {
        $pre = $this->build_unique_pattern($patchlines, $block['openidx'] - 1, -1, $block['lowerbound'], $corelines);
        $post = $this->build_unique_pattern($patchlines, $block['closeidx'] + 1, 1, $block['upperbound'], $corelines);

        if (!$pre['unique'] || !$post['unique']) {
            $detail = [];
            if (!$pre['unique']) {
                $detail[] = 'pre-pattern not found/ambiguous in the core file';
            }
            if (!$post['unique']) {
                $detail[] = 'post-pattern not found/ambiguous in the core file';
            }
            return [
                'applied' => false,
                'status' => 'error',
                'message' => "Insertion point could not be uniquely located (" . implode(', ', $detail) . ").",
                'lines' => $corelines,
            ];
        }

        $coreprend = $pre['position'] + $pre['length']; // Index right after the pre-pattern in corelines.
        $corepoststart = $post['position']; // Index of the start of the post-pattern in corelines.

        if ($corepoststart < $coreprend) {
            return [
                'applied' => false,
                'status' => 'error',
                'message' => "Pre-pattern and post-pattern overlap in the core file.",
                'lines' => $corelines,
            ];
        }

        // Divergence check: comparison restricted to the patch zone.
        $divergence = null;
        $divnote = "reference unavailable, applying without a divergence check";

        if ($hasreference && $referencelines !== null) {
            $refpre = $this->find_pattern_occurrences($referencelines, $pre['pattern']);
            $refpost = $this->find_pattern_occurrences($referencelines, $post['pattern']);

            if (count($refpre) === 1 && count($refpost) === 1) {
                $refprend = $refpre[0] + count($pre['pattern']);
                $refpoststart = $refpost[0];
                if ($refpoststart >= $refprend) {
                    $refzone = array_slice($referencelines, $refprend, $refpoststart - $refprend);
                    $corezone = array_slice($corelines, $coreprend, $corepoststart - $coreprend);
                    $divergence = $this->compute_divergence($refzone, $corezone);
                    $divnote = sprintf('local divergence with __reference: %.1f%%', $divergence);
                } else {
                    $divnote = "inconsistent reference zone (pre/post reversed), applying without a divergence check";
                }
            } else {
                $divnote = "pre/post-pattern could not be uniquely located in __reference, applying without a divergence check";
            }
        }

        if ($divergence !== null && $divergence > $this->divergencethreshold) {
            return [
                'applied' => false,
                'status' => 'warning',
                'message' => sprintf(
                    "local divergence from __reference too high (%.1f%% > threshold %.1f%%).",
                    $divergence,
                    $this->divergencethreshold
                ),
                'lines' => $corelines,
            ];
        }

        /*
         * Insertion (empty zone) or replacement (non-empty zone): in both cases,
         * the zone delimited by the pre/post-pattern is substituted with the block content.
         */
        $newlines = $corelines;
        array_splice($newlines, $coreprend, $corepoststart - $coreprend, $block['content']);

        return [
            'applied' => true,
            'status' => 'ok',
            'message' => $divnote,
            'lines' => $newlines,
        ];
    }

    // Pre/post-pattern: adaptive construction and occurrence search.

    /**
     * Builds, starting from a single line and growing it progressively, a
     * pre- or post-pattern that achieves a unique match in $targetlines.
     * Stops at the given bound or at MAX_CONTEXT_LINES.
     *
     * @param array $sourcelines lines of the patch file.
     * @param int   $anchoridx   starting index (last line before PATCH+,
     *                            or first line after PATCH-).
     * @param int   $direction   -1 to build backwards (pre-pattern),
     *                            +1 to build forwards (post-pattern).
     * @param int   $boundary    bound not to exceed (inclusive index).
     * @param array $targetlines lines in which to look for a unique match.
     */
    protected function build_unique_pattern(
        array $sourcelines,
        int $anchoridx,
        int $direction,
        int $boundary,
        array $targetlines
    ): array {
        $pattern = [];
        $n = 0;

        while ($n < self::MAX_CONTEXT_LINES) {
            if ($direction === -1) {
                $idx = $anchoridx - $n;
                if ($idx < $boundary || $idx < 0) {
                    break;
                }
                array_unshift($pattern, $sourcelines[$idx]);
            } else {
                $idx = $anchoridx + $n;
                if ($idx > $boundary || $idx >= count($sourcelines)) {
                    break;
                }
                $pattern[] = $sourcelines[$idx];
            }
            $n++;

            $positions = $this->find_pattern_occurrences($targetlines, $pattern);
            if (count($positions) === 1) {
                return ['unique' => true, 'pattern' => $pattern, 'length' => $n, 'position' => $positions[0]];
            }
        }

        return ['unique' => false, 'pattern' => $pattern, 'length' => $n, 'position' => null];
    }

    /**
     * Searches for all the positions where the line sequence $pattern
     * appears consecutively in $haystack (exact comparison, line endings
     * ignored).
     * @param array $haystack
     * @param array $pattern
     */
    protected function find_pattern_occurrences(array $haystack, array $pattern): array {
        $positions = [];
        $patterncount = count($pattern);
        if ($patterncount === 0) {
            return $positions;
        }
        $max = count($haystack) - $patterncount;
        for ($i = 0; $i <= $max; $i++) {
            $match = true;
            for ($j = 0; $j < $patterncount; $j++) {
                if (rtrim($haystack[$i + $j], "\r\n") !== rtrim($pattern[$j], "\r\n")) {
                    $match = false;
                    break;
                }
            }
            if ($match) {
                $positions[] = $i;
            }
        }
        return $positions;
    }

    /**
     * Computes a divergence percentage (0 = identical, 100 = completely
     * different) between two zones of lines.
     * @param array $reflines
     * @param array $corelines
     * @return int
     */
    protected function compute_divergence(array $reflines, array $corelines): float {
        $reftext = implode("\n", array_map(function ($l) {
            return rtrim($l, "\r\n");
        }, $reflines));
        $coretext = implode("\n", array_map(function ($l) {
            return rtrim($l, "\r\n");
        }, $corelines));

        if ($reftext === '' && $coretext === '') {
            return 0.0;
        }

        similar_text($reftext, $coretext, $percent);
        return max(0.0, 100.0 - $percent);
    }

    // File read / write.

    /**
     * Reads a file while preserving the original line endings, one array
     * entry per line.
     * @param string $path
     */
    protected function read_lines(string $path): ?array {
        if (!is_readable($path)) {
            return null;
        }
        $content = file_get_contents($path);

        if ($content === false) {
            return null;
        }

        $lines = preg_split('/(?<=\n)/', $content);

        if ($lines === false) {
            return null;
        }

        if (count($lines) && $lines[count($lines) - 1] === '') {
            array_pop($lines);
        }
        return $lines;
    }

    /**
     * Creates a timestamped backup of the core file before modification.
     * @param string $corefile
     */
    protected function backup_corefile(string $corefile): bool {
        $backupfile = $corefile . '.bak.' . date('Ymd_His');
        return $this->sudo_copy($corefile, $backupfile);
    }

    /**
     * Rewrites the core file with the new content atomically.
     * The temporary file is created in the same directory as $corefile so
     * that rename() is guaranteed to be atomic (same filesystem).
     * Original file permissions are preserved on the replacement.
     * @param string $corefile
     * @param string $content
     */
    protected function write_corefile(string $corefile, string $content): bool {
        // Tmpfile in the same directory → atomic rename() on the same filesystem.
        $tmpfile = tempnam(dirname($corefile), 'aplpatch_');

        if ($tmpfile === false) {
            return false;
        }

        if (file_put_contents($tmpfile, $content) === false) {
            @unlink($tmpfile);
            return false;
        }

        // Preserve the original file's permissions on the tmpfile before rename.
        $perms = fileperms($corefile);
        if ($perms !== false) {
            chmod($tmpfile, $perms & 0777);
        }

        // Atomic replace: rename() on the same filesystem is a POSIX-guaranteed
        // atomic operation — safer than copy() which leaves a window with partial content.
        if (!rename($tmpfile, $corefile)) {
            @unlink($tmpfile);
            $this->add_report_entry(null, 'error', "rename() failed: {$tmpfile} → {$corefile}");
            return false;
        }

        return true;
    }

    /**
     * Copies $source to $destination using pure-PHP functions (no shell exec).
     *
     * exec() and its equivalents (shell_exec, proc_open …) are forbidden by
     * Moodle coding standards and may be disabled via php.ini disable_functions.
     * The previous sudo-based implementation is therefore replaced by a direct
     * PHP copy that preserves file permissions and modification time (cp -p
     * behaviour).
     *
     * If the web-server user does not own the destination file, configure the
     * operating system so it can write there (ACL, setgid directory, or correct
     * ownership) and leave the 'sudoer' setting empty.
     *
     * @param string $source
     * @param string $destination
     * @return bool true on success, false on failure (error added to report).
     */
    protected function sudo_copy(string $source, string $destination): bool {
        if ($this->sudoeruser !== '') {
            // Privileged copy via exec(sudo …) is not supported: exec() is
            // forbidden by Moodle coding standards.  Clear the 'sudoer' setting
            // and configure filesystem permissions (ACL, ownership or setgid) so
            // the web-server user can write directly to Moodle's dirroot.
            $this->add_report_entry(
                null,
                'error',
                "Privileged copy is not supported: exec() is forbidden in Moodle plugins. " .
                "Clear the 'sudoer' setting and configure filesystem permissions (ACL, " .
                "ownership or setgid) so the web-server user can write to Moodle's dirroot."
            );
            return false;
        }

        if (!is_readable($source)) {
            $this->add_report_entry(null, 'error', "Source file is not readable: {$source}");
            return false;
        }

        $destdir = dirname($destination);
        $destexists = file_exists($destination);
        if (!($destexists ? is_writable($destination) : is_writable($destdir))) {
            $this->add_report_entry(
                null,
                'error',
                "Destination is not writable by the web-server user: {$destination}. " .
                "Configure filesystem permissions so the web-server user can write to Moodle's dirroot."
            );
            return false;
        }

        if (!copy($source, $destination)) {
            $this->add_report_entry(null, 'error', "copy() failed: {$source} → {$destination}");
            return false;
        }

        // Preserve permissions and modification time (equivalent of cp -p).
        $perms = fileperms($source);
        if ($perms !== false) {
            chmod($destination, $perms & 0777);
        }
        $mtime = filemtime($source);
        if ($mtime !== false) {
            touch($destination, $mtime);
        }

        return true;
    }

    /**
     * Add report entry to report.
     * @param string|null $file
     * @param string $status
     * @param string $message
     */
    protected function add_report_entry(?string $file, string $status, string $message): void {
        $this->report[] = [
            'file' => $file,
            'status' => $status,
            'message' => $message,
            'time' => time(),
        ];
    }

    /**
     * Get a report as an array
     * @return array the full execution report (associative array).
     */
    public function get_report(): array {
        return $this->report;
    }

    /**
     * Get report as a string.
     * @return string the execution report formatted as plain text.
     */
    public function get_report_as_string(): string {
        $lines = [];
        foreach ($this->report as $entry) {
            $lines[] = sprintf(
                '[%s] %s: %s',
                core_text::strtoupper($entry['status']),
                $entry['file'] ?? '(global)',
                $entry['message']
            );
        }
        return implode("\n", $lines);
    }
}
