<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore; defined("\x4d\x4f\x4f\104\114\105\x5f\x49\x4e\x54\105\x52\116\101\x4c") || die; require_once $CFG->dirroot . "\x2f\x6c\157\143\141\154\x2f\141\160\154\143\x6f\162\x65\57\x70\x72\x6f\x2f\154\151\142\x2e\160\x68\160"; final class pro_manager extends license_manager { public static $shortcomponent = "\x6c\x6f\143\141\x6c\x5f\x61\160\x6c\x63\x6f\x72\145"; public static $component = "\154\x6f\x63\141\x6c\137\141\160\x6c\x63\157\162\x65"; public static $componentpath = "\x6c\x6f\x63\x61\x6c\57\x61\x70\x6c\143\157\162\x65"; public static $componentsettings = "\x6c\x6f\143\x61\154\137\141\160\x6c\x63\157\x72\x65\x5f\x67\x65\156\x65\x72\x61\154\163"; protected function __construct() { assert(1); } public static function instance() { goto IIcPI; wkQzj: $d9IiY = new pro_manager(); goto IkO2a; dDNd6: return $d9IiY; goto DMCvX; IkO2a: sRa2V: goto dDNd6; DenZe: if (!is_null($d9IiY)) { goto sRa2V; } goto wkQzj; IIcPI: static $d9IiY; goto DenZe; DMCvX: } }
