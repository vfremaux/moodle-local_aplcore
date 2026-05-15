<?php
// This file is part of Moogwai - Private project

// This file is a crossporting of Moogwai Framework to Moodle.
// Cross porting admits license of the Moogwai for Moodle agrees with
// Moodle's GNU licence and accepts its terms as for.

/**
 * Generates a captcha challenge image, storing info in session to validate it.
 * Uses gd generator to make image. No link to thirdparty nor google.
 *
 * @package    local_aplcore
 */

require(__DIR__.'../../../../config.php');
require_once($CFG->dirroot.'/local_aplcore/moogwai/simplecaptchalib.php');

simplecaptcha_generate($CFG->captchatype ?? 'glyphs');
