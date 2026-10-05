<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore\pro; defined("\115\117\x4f\104\114\105\x5f\x49\116\x54\105\x52\x4e\101\114") || die; require_once $CFG->dirroot . "\57\x6c\x6f\143\x61\x6c\57\x61\x70\x6c\x63\x6f\162\x65\57\x70\x72\157\57\154\151\142\x2e\160\150\x70"; use local_aplcore\license_manager; final class pro_manager extends license_manager { public static $shortcomponent = "\x6c\157\x63\x61\154\x5f\x61\x70\x6c\143\x6f\162\145"; public static $component = "\x6c\x6f\143\141\154\137\x61\x70\154\x63\157\x72\x65"; public static $componentpath = "\x6c\x6f\x63\141\x6c\57\141\160\154\x63\157\x72\145"; public static $componentsettings = "\x6c\157\143\x61\154\137\141\x70\154\143\x6f\162\145\x5f\147\145\156\x65\162\141\x6c\x73"; protected function __construct() { assert(1); } public static function instance() { goto gIdsk; gIdsk: static $Ju8KJ; goto TlKxs; QE7fY: return $Ju8KJ; goto H03Kz; TlKxs: if (!is_null($Ju8KJ)) { goto Sb0Uf; } goto Shu8G; Shu8G: $Ju8KJ = new pro_manager(); goto iTbgz; iTbgz: Sb0Uf: goto QE7fY; H03Kz: } }
