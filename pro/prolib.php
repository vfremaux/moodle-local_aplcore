<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore; defined("\x4d\x4f\117\104\114\x45\x5f\111\116\124\x45\x52\116\101\114") || die; require_once $CFG->dirroot . "\57\154\x6f\x63\141\x6c\57\x61\x70\x6c\143\157\162\x65\x2f\x70\162\x6f\x2f\x6c\x69\142\x2e\x70\150\160"; final class pro_manager extends license_manager { public static $shortcomponent = "\154\x6f\x63\141\x6c\x5f\x61\160\154\x63\x6f\x72\145"; public static $component = "\154\x6f\x63\x61\x6c\x5f\x61\160\x6c\143\157\x72\145"; public static $componentpath = "\x6c\x6f\x63\x61\154\x2f\x61\x70\x6c\143\x6f\162\145"; public static $componentsettings = "\x6c\x6f\143\x61\x6c\x5f\141\x70\x6c\x63\157\x72\x65\x5f\x67\145\x6e\145\x72\141\154\163"; protected function __construct() { assert(1); } public static function instance() { goto ULWHB; mohis: FgOYx: goto ES0YP; ULWHB: static $GFdg6; goto Kc2nH; ES0YP: return $GFdg6; goto G_HOx; tNQHi: $GFdg6 = new pro_manager(); goto mohis; Kc2nH: if (!is_null($GFdg6)) { goto FgOYx; } goto tNQHi; G_HOx: } }
