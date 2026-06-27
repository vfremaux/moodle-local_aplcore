<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore; defined("\x4d\x4f\x4f\x44\x4c\x45\137\111\116\x54\105\122\x4e\101\x4c") || die; require_once $CFG->dirroot . "\57\x6c\157\x63\141\x6c\57\x61\x70\x6c\143\157\162\145\x2f\160\x72\x6f\57\x6c\151\142\x2e\x70\x68\160"; final class pro_manager extends license_manager { public static $shortcomponent = "\x6c\157\x63\141\x6c\137\x61\x70\x6c\143\x6f\162\145"; public static $component = "\154\x6f\143\141\154\x5f\x61\x70\154\x63\157\162\145"; public static $componentpath = "\154\157\x63\141\x6c\57\141\x70\154\143\157\162\145"; public static $componentsettings = "\x6c\157\143\x61\x6c\x5f\141\160\154\x63\157\x72\145\x5f\x67\x65\156\x65\x72\141\x6c\x73"; protected function __construct() { assert(1); } public static function instance() { goto wY2vQ; wY2vQ: static $MtB73; goto XwihG; SWd25: Na7Ns: goto H1Bbu; H1Bbu: return $MtB73; goto ta5Zg; XwihG: if (!is_null($MtB73)) { goto Na7Ns; } goto inkMo; inkMo: $MtB73 = new pro_manager(); goto SWd25; ta5Zg: } }
