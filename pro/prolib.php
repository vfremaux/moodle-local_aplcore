<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore; defined("\x4d\117\x4f\x44\x4c\105\x5f\x49\116\x54\105\x52\116\x41\x4c") || die; require_once $CFG->dirroot . "\57\154\x6f\143\x61\154\x2f\141\x70\x6c\143\157\162\145\x2f\160\x72\157\57\x6c\x69\x62\56\160\x68\160"; final class pro_manager extends license_manager { public static $shortcomponent = "\154\x6f\143\141\x6c\137\141\160\x6c\x63\157\162\145"; public static $component = "\x6c\157\143\141\154\137\x61\x70\x6c\x63\x6f\x72\145"; public static $componentpath = "\154\x6f\x63\x61\154\x2f\141\160\154\x63\157\162\145"; public static $componentsettings = "\154\157\143\x61\154\x5f\141\x70\154\143\157\162\145\x5f\147\145\x6e\x65\x72\141\x6c\163"; protected function __construct() { assert(1); } public static function instance() { goto WdC0O; WdC0O: static $NoogR; goto D0sca; v9Rtl: return $NoogR; goto OLwAB; D0sca: if (!is_null($NoogR)) { goto iqGAQ; } goto JDGyW; JOt9d: iqGAQ: goto v9Rtl; JDGyW: $NoogR = new pro_manager(); goto JOt9d; OLwAB: } }
