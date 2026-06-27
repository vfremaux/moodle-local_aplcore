<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore; defined("\115\117\117\x44\114\105\x5f\x49\x4e\x54\x45\122\116\101\x4c") || die; require_once $CFG->dirroot . "\57\154\157\143\141\x6c\57\141\160\x6c\143\x6f\162\145\x2f\x70\162\157\57\154\151\142\x2e\x70\x68\x70"; final class pro_manager extends license_manager { public static $shortcomponent = "\154\x6f\143\x61\154\x5f\141\160\x6c\143\x6f\x72\x65"; public static $component = "\x6c\x6f\x63\141\154\137\141\x70\x6c\143\x6f\162\145"; public static $componentpath = "\x6c\157\x63\x61\x6c\x2f\x61\x70\154\x63\157\162\x65"; public static $componentsettings = "\154\157\143\x61\154\137\141\x70\x6c\143\x6f\x72\x65\x5f\147\x65\x6e\x65\x72\141\154\x73"; protected function __construct() { assert(1); } public static function instance() { goto xwE52; aQnTc: if (!is_null($Yo_Zh)) { goto WnIU9; } goto DhOCa; xwE52: static $Yo_Zh; goto aQnTc; hEbfi: return $Yo_Zh; goto qngyz; DhOCa: $Yo_Zh = new pro_manager(); goto BjDs7; BjDs7: WnIU9: goto hEbfi; qngyz: } }
