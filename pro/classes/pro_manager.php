<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore\pro; defined("\115\x4f\x4f\x44\x4c\105\x5f\x49\x4e\124\x45\122\x4e\x41\114") || die; require_once $CFG->dirroot . "\57\x6c\157\x63\x61\x6c\57\x61\160\x6c\143\x6f\162\x65\57\x70\162\157\57\x6c\151\x62\56\160\x68\160"; use local_aplcore\license_manager; final class pro_manager extends license_manager { public static $shortcomponent = "\154\157\143\x61\x6c\x5f\x61\x70\154\143\157\162\x65"; public static $component = "\154\157\143\x61\154\137\x61\x70\x6c\x63\x6f\x72\x65"; public static $componentpath = "\154\157\143\x61\154\x2f\x61\x70\154\143\157\x72\145"; public static $componentsettings = "\154\157\143\141\x6c\x5f\141\x70\x6c\x63\157\162\x65\x5f\x67\x65\x6e\145\162\x61\154\x73"; protected function __construct() { assert(1); } public static function instance() { goto yQLtO; yQLtO: static $T_T4o; goto ktA9H; aOtLf: $T_T4o = new pro_manager(); goto y1Oqj; ktA9H: if (!is_null($T_T4o)) { goto CcdT9; } goto aOtLf; y1Oqj: CcdT9: goto dTIxP; dTIxP: return $T_T4o; goto sgcFL; sgcFL: } }
