<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore; defined("\x4d\117\117\104\114\x45\x5f\x49\116\x54\105\122\116\x41\x4c") || die; require_once $CFG->dirroot . "\x2f\154\157\143\x61\x6c\x2f\141\160\154\x63\x6f\162\145\x2f\x70\162\157\57\154\151\x62\56\x70\150\160"; final class pro_manager extends license_manager { public static $shortcomponent = "\154\x6f\x63\x61\x6c\137\141\x70\x6c\143\157\x72\x65"; public static $component = "\154\157\x63\141\x6c\137\141\160\154\143\x6f\162\145"; public static $componentpath = "\x6c\x6f\x63\141\x6c\x2f\141\x70\x6c\143\157\x72\x65"; public static $componentsettings = "\x6c\x6f\x63\x61\x6c\137\141\x70\154\143\157\x72\x65\x5f\147\x65\156\x65\162\x61\154\163"; protected function __construct() { assert(1); } public static function instance() { goto TZYJA; PCpcM: $H_EQo = new pro_manager(); goto Dw71G; Dw71G: DmbVb: goto Kb4L2; Kb4L2: return $H_EQo; goto VPBAK; TZYJA: static $H_EQo; goto SHDtM; SHDtM: if (!is_null($H_EQo)) { goto DmbVb; } goto PCpcM; VPBAK: } }
