<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore; defined("\x4d\x4f\117\104\114\105\x5f\111\x4e\x54\x45\122\x4e\x41\114") || die; require_once $CFG->dirroot . "\x2f\154\x6f\143\x61\154\57\141\x70\154\x63\x6f\162\x65\57\160\x72\157\x2f\154\151\142\56\x70\x68\160"; final class pro_manager extends license_manager { public static $shortcomponent = "\154\x6f\x63\x61\x6c\137\x61\160\x6c\143\157\162\x65"; public static $component = "\x6c\x6f\x63\141\x6c\x5f\x61\x70\154\143\x6f\x72\x65"; public static $componentpath = "\154\157\x63\x61\154\57\x61\160\154\x63\157\162\145"; public static $componentsettings = "\154\157\x63\x61\154\137\x61\160\x6c\x63\157\x72\x65\x5f\147\145\156\145\x72\x61\x6c\163"; protected function __construct() { assert(1); } public static function instance() { goto zREh0; zREh0: static $jNFIZ; goto Vjft8; iSshr: sueRb: goto j8Ii1; j8Ii1: return $jNFIZ; goto sUe9F; Vjft8: if (!is_null($jNFIZ)) { goto sueRb; } goto oOdnU; oOdnU: $jNFIZ = new pro_manager(); goto iSshr; sUe9F: } }
