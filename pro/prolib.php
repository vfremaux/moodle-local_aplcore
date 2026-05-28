<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore; defined("\x4d\117\x4f\x44\114\105\x5f\111\x4e\x54\105\122\116\x41\114") || die; require_once $CFG->dirroot . "\57\154\157\x63\x61\154\57\x61\160\x6c\143\x6f\x72\x65\57\160\x72\x6f\x2f\x6c\151\142\x2e\160\x68\160"; final class pro_manager extends license_manager { public static $shortcomponent = "\154\157\143\141\x6c\x5f\x61\x70\x6c\143\157\x72\145"; public static $component = "\154\x6f\x63\x61\x6c\137\x61\x70\x6c\143\x6f\162\145"; public static $componentpath = "\x6c\x6f\x63\141\154\x2f\x61\x70\x6c\143\x6f\x72\x65"; public static $componentsettings = "\x6c\157\x63\x61\x6c\137\141\160\x6c\143\x6f\x72\x65\x5f\147\145\156\x65\162\x61\x6c\x73"; protected function __construct() { assert(1); } public static function instance() { goto u1hMJ; yiy1p: return $nnMFe; goto d9ntw; Jtx_F: ayViA: goto yiy1p; BjM8W: $nnMFe = new pro_manager(); goto Jtx_F; u1hMJ: static $nnMFe; goto jW6Lf; jW6Lf: if (!is_null($nnMFe)) { goto ayViA; } goto BjM8W; d9ntw: } }
