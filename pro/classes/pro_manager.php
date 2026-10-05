<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore\pro; defined("\115\117\x4f\x44\114\x45\x5f\x49\x4e\124\x45\122\x4e\x41\114") || die; require_once $CFG->dirroot . "\57\154\157\143\141\x6c\x2f\x61\x70\154\x63\157\x72\145\x2f\160\x72\157\x2f\154\151\142\56\160\x68\x70"; use local_aplcore\license_manager; final class pro_manager extends license_manager { public static $shortcomponent = "\x6c\157\143\x61\x6c\x5f\x61\x70\154\143\157\x72\x65"; public static $component = "\x6c\157\143\x61\x6c\137\x61\x70\x6c\143\157\162\x65"; public static $componentpath = "\x6c\157\143\141\x6c\x2f\141\x70\154\143\157\x72\x65"; public static $componentsettings = "\154\x6f\143\141\x6c\x5f\x61\160\x6c\x63\157\x72\145\137\147\x65\156\x65\x72\x61\x6c\163"; protected function __construct() { assert(1); } public static function instance() { goto GA4Dw; w3hB3: if (!is_null($ynsry)) { goto WB4pM; } goto wyAa9; TyGO4: return $ynsry; goto ZoQJF; wyAa9: $ynsry = new pro_manager(); goto UkPVV; UkPVV: WB4pM: goto TyGO4; GA4Dw: static $ynsry; goto w3hB3; ZoQJF: } }
