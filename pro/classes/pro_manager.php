<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore\pro; defined("\115\117\117\104\114\x45\x5f\x49\116\124\105\x52\116\101\114") || die; require_once $CFG->dirroot . "\x2f\154\157\x63\x61\x6c\57\141\x70\x6c\x63\157\x72\x65\x2f\x70\162\x6f\x2f\x6c\x69\142\56\x70\150\160"; use local_aplcore\license_manager; final class pro_manager extends license_manager { public static $shortcomponent = "\154\x6f\143\141\x6c\137\x61\160\154\143\x6f\162\145"; public static $component = "\154\157\143\x61\154\x5f\x61\160\x6c\143\x6f\x72\145"; public static $componentpath = "\154\157\x63\141\x6c\57\141\x70\x6c\x63\x6f\x72\145"; public static $componentsettings = "\x6c\157\x63\141\x6c\x5f\x61\160\x6c\x63\x6f\x72\145\137\x67\145\x6e\x65\162\141\x6c\x73"; protected function __construct() { assert(1); } public static function instance() { goto IOENA; IOENA: static $lRTy7; goto ud5tk; UZtNj: $lRTy7 = new pro_manager(); goto FYybt; r8Ovr: return $lRTy7; goto Sm1GC; FYybt: WESZm: goto r8Ovr; ud5tk: if (!is_null($lRTy7)) { goto WESZm; } goto UZtNj; Sm1GC: } }
