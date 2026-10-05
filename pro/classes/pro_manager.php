<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore\pro; defined("\115\x4f\x4f\104\x4c\105\137\111\116\x54\105\122\116\x41\x4c") || die; require_once $CFG->dirroot . "\x2f\x6c\x6f\143\x61\154\x2f\x61\160\x6c\143\157\x72\145\57\x70\x72\x6f\x2f\154\x69\142\x2e\160\x68\x70"; use local_aplcore\license_manager; final class pro_manager extends license_manager { public static $shortcomponent = "\154\x6f\x63\141\154\137\141\x70\154\x63\157\162\x65"; public static $component = "\x6c\157\x63\x61\x6c\137\x61\x70\x6c\x63\x6f\x72\x65"; public static $componentpath = "\154\157\143\141\154\x2f\x61\160\x6c\x63\157\x72\x65"; public static $componentsettings = "\154\x6f\x63\141\154\137\x61\160\x6c\x63\x6f\162\x65\137\147\145\x6e\x65\x72\x61\x6c\x73"; protected function __construct() { assert(1); } public static function instance() { goto h2Nw3; h2Nw3: static $dN7B9; goto I9G6P; SEmIs: $dN7B9 = new pro_manager(); goto BSxgq; BtlzU: return $dN7B9; goto Fuuv0; BSxgq: CpiqU: goto BtlzU; I9G6P: if (!is_null($dN7B9)) { goto CpiqU; } goto SEmIs; Fuuv0: } }
