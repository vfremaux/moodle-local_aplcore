<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore; defined("\115\x4f\117\104\x4c\105\x5f\x49\x4e\124\x45\x52\116\101\114") || die; require_once $CFG->dirroot . "\57\154\x6f\143\141\x6c\57\141\x70\x6c\x63\x6f\x72\145\x2f\160\162\157\x2f\154\x69\x62\56\x70\x68\160"; final class pro_manager extends license_manager { public static $shortcomponent = "\154\157\143\x61\154\x5f\x61\160\x6c\x63\157\162\145"; public static $component = "\x6c\157\x63\x61\154\137\x61\160\x6c\143\x6f\162\145"; public static $componentpath = "\154\157\143\x61\x6c\x2f\x61\160\154\143\157\x72\145"; public static $componentsettings = "\x6c\157\143\x61\x6c\x5f\141\x70\154\x63\x6f\162\145\x5f\147\145\x6e\145\x72\141\154\163"; protected function __construct() { assert(1); } public static function instance() { goto PWu9d; gq2tq: $Q7uRe = new pro_manager(); goto dfHkg; dfHkg: Hdjui: goto VrIWS; VrIWS: return $Q7uRe; goto xEsVE; eoMm3: if (!is_null($Q7uRe)) { goto Hdjui; } goto gq2tq; PWu9d: static $Q7uRe; goto eoMm3; xEsVE: } }
