<?php
// This file is part of Moogwai - private project.

/**
 * Setup autoloader for all moogwai classes. This fils should be required once
 * in any moodle page using Moogwai framework parts.
 *
 * @package     moogwai_backoffice
 */

defined('MOODLE_INTERNAL') || die;
define('MOOGWAI_INTERNAL', true);

spl_autoload_register(function($classname) {
    global $CFG;

    if (strpos($classname, 'local_vflibs\\moogwai') === 0) {
        $classshort = str_replace('local_vflibs\\moogwai\\', '', $classname);
        $classpath = str_replace('\\', '/', $classshort);
        $classfile = $CFG->dirroot.'/local/vflibs/classes/moogwai/'.$classpath.'.class.php';
        if (file_exists($classfile)) {
            include_once($classfile);
            return true;
        } else {
            $classfile = str_replace('.class.', '.interface.', $classfile);
            if (file_exists($classfile)) {
                include_once($classfile);
                return true;
            } else {
                $classfile = str_replace('.interface.', '.trait.', $classfile);
                if (file_exists($classfile)) {
                    include_once($classfile);
                    return true;
                }
            }
            throw new coding_exception("Moogwai class not found ".$classname.' as '.$classfile);
        }
        return false;
    }
}, true, true);