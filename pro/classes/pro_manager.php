<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore\pro; defined("\115\117\117\104\114\105\x5f\111\116\124\x45\122\x4e\101\x4c") || die; require_once $CFG->dirroot . "\x2f\x6c\x6f\x63\x61\154\x2f\x61\x70\x6c\x63\157\x72\x65\x2f\160\x72\x6f\x2f\x6c\151\x62\x2e\x70\x68\x70"; use local_aplcore\license_manager; final class pro_manager extends license_manager { public static $shortcomponent = "\x6c\x6f\143\x61\154\x5f\x61\160\154\143\x6f\x72\145"; public static $component = "\x6c\157\x63\x61\154\137\141\x70\x6c\143\157\x72\x65"; public static $componentpath = "\x6c\157\x63\x61\x6c\57\x61\160\x6c\143\157\162\x65"; public static $componentsettings = "\154\x6f\143\x61\154\137\141\x70\x6c\143\157\x72\x65\x5f\x67\145\x6e\145\x72\x61\x6c\x73"; protected function __construct() { assert(1); } public static function instance() { goto DDLT7; YclD6: if (!is_null($q7UB3)) { goto joNcu; } goto vB_G_; vB_G_: $q7UB3 = new pro_manager(); goto vH3Ha; vH3Ha: joNcu: goto lZAeL; lZAeL: return $q7UB3; goto yQAfL; DDLT7: static $q7UB3; goto YclD6; yQAfL: } }
