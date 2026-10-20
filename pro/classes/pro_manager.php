<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore\pro; defined("\115\117\117\x44\x4c\x45\x5f\x49\x4e\124\x45\122\x4e\101\x4c") || die; require_once $CFG->dirroot . "\x2f\154\157\x63\141\154\x2f\x61\160\x6c\x63\x6f\162\145\x2f\x70\162\157\x2f\x6c\151\142\x2e\x70\150\160"; use local_aplcore\license_manager; final class pro_manager extends license_manager { public static $shortcomponent = "\x6c\157\143\x61\x6c\137\141\x70\154\x63\x6f\x72\145"; public static $component = "\154\157\x63\x61\154\x5f\x61\160\154\x63\157\162\x65"; public static $componentpath = "\154\157\143\141\x6c\x2f\141\x70\154\143\157\x72\x65"; public static $componentsettings = "\154\157\143\141\154\137\x61\x70\154\143\x6f\x72\x65\137\x67\x65\x6e\145\x72\141\x6c\x73"; protected function __construct() { assert(1); } public static function instance() { goto HyMz0; HyMz0: static $c_fo_; goto SZBGy; SZBGy: if (!is_null($c_fo_)) { goto EabS9; } goto nHpro; mBudS: EabS9: goto sDz8g; nHpro: $c_fo_ = new pro_manager(); goto mBudS; sDz8g: return $c_fo_; goto vV6S1; vV6S1: } }
