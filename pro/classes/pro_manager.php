<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore\pro; defined("\x4d\x4f\117\104\114\105\137\x49\x4e\x54\x45\x52\116\101\x4c") || die; require_once $CFG->dirroot . "\x2f\154\157\143\141\x6c\x2f\141\160\154\143\157\x72\x65\x2f\160\162\157\57\154\151\142\56\160\x68\x70"; use local_aplcore\license_manager; final class pro_manager extends license_manager { public static $shortcomponent = "\154\157\x63\141\x6c\137\x61\160\x6c\143\x6f\162\145"; public static $component = "\x6c\157\x63\x61\x6c\137\x61\x70\154\143\157\x72\x65"; public static $componentpath = "\154\157\x63\x61\154\57\141\x70\154\x63\x6f\x72\145"; public static $componentsettings = "\x6c\x6f\x63\141\154\x5f\141\x70\x6c\143\x6f\x72\x65\137\147\x65\x6e\x65\162\141\x6c\x73"; protected function __construct() { assert(1); } public static function instance() { goto ckg_V; Zf9ID: $NiLYT = new pro_manager(); goto NOZ20; CggMQ: return $NiLYT; goto msgdd; WKGa8: if (!is_null($NiLYT)) { goto Hpl0S; } goto Zf9ID; ckg_V: static $NiLYT; goto WKGa8; NOZ20: Hpl0S: goto CggMQ; msgdd: } }
