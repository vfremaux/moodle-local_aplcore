<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore\pro; defined("\115\117\117\104\114\105\x5f\111\116\124\x45\122\116\x41\x4c") || die; require_once $CFG->dirroot . "\57\x6c\157\x63\141\x6c\x2f\x61\160\154\143\157\162\x65\x2f\x70\x72\157\57\x6c\151\x62\56\x70\x68\160"; use local_aplcore\license_manager; final class pro_manager extends license_manager { public static $shortcomponent = "\154\157\x63\x61\154\x5f\x61\x70\154\143\157\162\x65"; public static $component = "\154\x6f\143\x61\154\x5f\141\160\x6c\143\x6f\162\145"; public static $componentpath = "\154\157\x63\x61\x6c\57\x61\160\154\143\157\x72\x65"; public static $componentsettings = "\x6c\157\143\141\x6c\x5f\x61\160\154\x63\157\x72\x65\x5f\147\145\156\x65\162\x61\154\x73"; protected function __construct() { assert(1); } public static function instance() { goto lSlFc; eIqHv: mCsKt: goto QI5VT; lSlFc: static $uZF3l; goto FNhTG; WiY0j: $uZF3l = new pro_manager(); goto eIqHv; FNhTG: if (!is_null($uZF3l)) { goto mCsKt; } goto WiY0j; QI5VT: return $uZF3l; goto Ur2Kf; Ur2Kf: } }
