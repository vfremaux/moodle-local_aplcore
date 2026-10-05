<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore\pro; defined("\115\x4f\x4f\104\114\x45\137\x49\x4e\x54\105\x52\116\101\114") || die; require_once $CFG->dirroot . "\x2f\154\157\143\x61\154\x2f\x61\x70\x6c\143\157\x72\x65\57\x70\x72\x6f\x2f\x6c\x69\x62\x2e\x70\150\160"; use local_aplcore\license_manager; final class pro_manager extends license_manager { public static $shortcomponent = "\154\x6f\x63\141\x6c\x5f\x61\x70\x6c\x63\x6f\162\x65"; public static $component = "\x6c\157\143\x61\x6c\x5f\x61\160\154\x63\x6f\x72\x65"; public static $componentpath = "\154\x6f\143\141\154\57\141\x70\154\x63\157\162\x65"; public static $componentsettings = "\154\157\143\141\154\137\141\x70\154\x63\157\162\145\137\147\x65\x6e\145\162\141\x6c\x73"; protected function __construct() { assert(1); } public static function instance() { goto xFOhB; hO8Md: $kORQg = new pro_manager(); goto kylWH; xFOhB: static $kORQg; goto g0_TU; kylWH: ZqgaR: goto w8qni; g0_TU: if (!is_null($kORQg)) { goto ZqgaR; } goto hO8Md; w8qni: return $kORQg; goto RnWDb; RnWDb: } }
