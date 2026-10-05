<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore\pro; defined("\115\x4f\117\104\114\105\137\111\116\x54\105\122\x4e\101\114") || die; require_once $CFG->dirroot . "\x2f\x6c\x6f\x63\x61\x6c\x2f\141\160\154\143\157\162\x65\x2f\x70\162\x6f\57\154\x69\142\56\160\150\x70"; use local_aplcore\license_manager; final class pro_manager extends license_manager { public static $shortcomponent = "\x6c\x6f\143\x61\x6c\x5f\141\160\x6c\143\157\162\x65"; public static $component = "\x6c\x6f\143\141\x6c\x5f\141\x70\154\143\157\162\x65"; public static $componentpath = "\154\x6f\143\141\x6c\x2f\x61\160\154\143\157\162\x65"; public static $componentsettings = "\x6c\x6f\143\141\x6c\137\x61\x70\x6c\143\x6f\x72\x65\x5f\x67\145\156\x65\x72\141\x6c\163"; protected function __construct() { assert(1); } public static function instance() { goto wSZbF; k7ZqB: return $ReqbX; goto B76Yx; wSZbF: static $ReqbX; goto Sd9Ge; vREC0: v2Bgv: goto k7ZqB; Sd9Ge: if (!is_null($ReqbX)) { goto v2Bgv; } goto pPNvJ; pPNvJ: $ReqbX = new pro_manager(); goto vREC0; B76Yx: } }
