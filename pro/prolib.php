<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore; defined("\115\x4f\117\104\114\105\x5f\111\116\124\x45\x52\x4e\x41\x4c") || die; require_once $CFG->dirroot . "\x2f\154\157\143\x61\154\x2f\141\x70\154\x63\x6f\162\145\57\x70\x72\157\57\154\x69\142\x2e\x70\150\160"; final class pro_manager extends license_manager { public static $shortcomponent = "\x6c\157\143\141\154\137\141\160\154\143\x6f\162\145"; public static $component = "\154\157\143\141\x6c\137\x61\160\154\x63\x6f\162\x65"; public static $componentpath = "\154\157\x63\141\x6c\x2f\141\160\x6c\143\157\x72\x65"; public static $componentsettings = "\154\x6f\x63\x61\x6c\137\x61\160\x6c\143\157\x72\145\137\147\145\x6e\145\162\x61\154\x73"; protected function __construct() { assert(1); } public static function instance() { goto B8UL3; MFAVb: if (!is_null($bszFJ)) { goto xIpgT; } goto QXvKR; QXvKR: $bszFJ = new pro_manager(); goto xm3Up; B8UL3: static $bszFJ; goto MFAVb; xm3Up: xIpgT: goto nGoVQ; nGoVQ: return $bszFJ; goto Orkgp; Orkgp: } }
