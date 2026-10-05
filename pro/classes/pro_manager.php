<?php
/*   __________________________________________________
    |              on 2.0.12              |
    |__________________________________________________|
*/
 namespace local_aplcore\pro; defined("\x4d\x4f\x4f\104\114\x45\x5f\111\x4e\x54\x45\x52\x4e\x41\114") || die; require_once $CFG->dirroot . "\x2f\x6c\x6f\143\x61\x6c\x2f\x61\x70\x6c\x63\x6f\x72\x65\x2f\160\x72\157\57\x6c\x69\142\56\x70\150\160"; use local_aplcore\license_manager; final class pro_manager extends license_manager { public static $shortcomponent = "\x6c\157\143\x61\154\x5f\141\160\x6c\143\157\x72\145"; public static $component = "\x6c\157\x63\x61\154\137\141\x70\x6c\x63\x6f\162\145"; public static $componentpath = "\x6c\x6f\x63\141\x6c\57\141\x70\154\143\157\x72\145"; public static $componentsettings = "\154\x6f\x63\141\154\137\x61\x70\154\x63\x6f\162\145\x5f\147\x65\x6e\x65\162\141\154\x73"; protected function __construct() { assert(1); } public static function instance() { goto RvxaB; wYQ9G: $VZCsv = new pro_manager(); goto Qdgqv; EPPG7: return $VZCsv; goto oKiPz; RvxaB: static $VZCsv; goto SqkP2; SqkP2: if (!is_null($VZCsv)) { goto v_Mdp; } goto wYQ9G; Qdgqv: v_Mdp: goto EPPG7; oKiPz: } }
