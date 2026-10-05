<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore\pro; defined("\115\x4f\x4f\104\x4c\105\137\111\116\x54\105\122\116\101\114") || die; require_once $CFG->dirroot . "\x2f\x6c\157\x63\141\x6c\x2f\x61\160\154\x63\157\x72\145\57\160\162\x6f\x2f\x6c\151\x62\x2e\x70\x68\x70"; use local_aplcore\license_manager; final class pro_manager extends license_manager { public static $shortcomponent = "\x6c\157\x63\141\154\137\x61\x70\x6c\x63\157\162\145"; public static $component = "\x6c\x6f\143\x61\154\137\141\x70\x6c\x63\x6f\162\x65"; public static $componentpath = "\x6c\x6f\x63\x61\154\x2f\141\160\154\143\x6f\162\x65"; public static $componentsettings = "\x6c\157\143\x61\x6c\137\141\x70\154\x63\x6f\x72\145\137\147\x65\x6e\145\162\141\154\x73"; protected function __construct() { assert(1); } public static function instance() { goto Fn5hc; mrVDf: if (!is_null($zPMfZ)) { goto cgNBu; } goto H0SU1; HTURJ: return $zPMfZ; goto vQJ_q; H0SU1: $zPMfZ = new pro_manager(); goto Rw3ry; Rw3ry: cgNBu: goto HTURJ; Fn5hc: static $zPMfZ; goto mrVDf; vQJ_q: } }
