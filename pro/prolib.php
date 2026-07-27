<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore; defined("\x4d\x4f\x4f\x44\114\105\x5f\111\116\x54\105\122\x4e\101\x4c") || die; require_once $CFG->dirroot . "\57\x6c\x6f\x63\x61\154\57\x61\160\154\143\x6f\x72\x65\x2f\160\x72\x6f\57\154\x69\x62\56\x70\150\x70"; final class pro_manager extends license_manager { public static $shortcomponent = "\154\x6f\143\x61\154\137\x61\160\x6c\143\x6f\x72\145"; public static $component = "\154\157\143\x61\154\137\141\160\x6c\143\x6f\162\x65"; public static $componentpath = "\154\157\x63\141\154\57\141\160\x6c\x63\157\x72\145"; public static $componentsettings = "\154\x6f\x63\141\154\x5f\x61\160\154\x63\157\162\145\137\147\x65\x6e\x65\x72\141\154\x73"; protected function __construct() { assert(1); } public static function instance() { goto YXDSv; NQM2w: if (!is_null($ShFbq)) { goto hjLsC; } goto AtAfA; AtAfA: $ShFbq = new pro_manager(); goto jdl6b; YXDSv: static $ShFbq; goto NQM2w; paFz3: return $ShFbq; goto M40_C; jdl6b: hjLsC: goto paFz3; M40_C: } }
