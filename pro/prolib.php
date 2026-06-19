<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore; defined("\x4d\117\x4f\104\114\x45\x5f\x49\116\x54\105\x52\116\x41\114") || die; require_once $CFG->dirroot . "\x2f\x6c\157\x63\141\x6c\57\x61\160\154\143\157\162\x65\x2f\x70\x72\x6f\57\x6c\x69\x62\x2e\x70\150\x70"; final class pro_manager extends license_manager { public static $shortcomponent = "\x6c\157\143\141\x6c\x5f\141\x70\x6c\143\x6f\162\x65"; public static $component = "\x6c\x6f\143\141\x6c\137\141\x70\154\x63\x6f\162\x65"; public static $componentpath = "\x6c\x6f\143\141\154\57\141\160\154\x63\x6f\162\x65"; public static $componentsettings = "\154\x6f\x63\x61\154\x5f\x61\x70\154\143\x6f\162\145\x5f\147\145\156\x65\x72\141\x6c\x73"; protected function __construct() { assert(1); } public static function instance() { goto U7byL; PXNg0: $ZbKRo = new pro_manager(); goto b43Oc; b43Oc: kJvIr: goto TfLA_; TfLA_: return $ZbKRo; goto W4Jra; jvMvF: if (!is_null($ZbKRo)) { goto kJvIr; } goto PXNg0; U7byL: static $ZbKRo; goto jvMvF; W4Jra: } }
