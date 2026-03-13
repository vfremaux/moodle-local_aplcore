<?php
// This file is part of Moogwai - private project

namespace local_aplcore\moogwai\form;

use local_aplcore\moogwai\exceptions\CodingException;

/**
 * A wizard route is a simple structure with routing information.
 * - incoming step
 * - outgoing step
 * - form session variable name
 * - variable value that triggers this route
 */
class wizard_route {

    /** @var parent $wizard */
    protected wizard $wizard;

    /** @var integer $fromstep */
    protected int $fromstep;

    /** @var integer $tostep */
    protected int $tostep;

    /** @var $varname */
    protected $varname;

    /** @var $value */
    protected $varvalue;

    /** @var $isregexp if false will perform a simple exact match. */
    protected $isregexp;

    /**
     * constructor.
     */
    public function __construct(int $fromstep, int $tostep, string $varname, string $varvalue, $isregexp = false) {
        $this->fromstep = $fromstep;
        $this->tostep = $tostep;
        $this->varname = $varname;
        $this->varvalue = $varvalue;
        $this->isregexp = $isregexp;
    }

    public function set_wizard($wizard) {
        $this->wizard = $wizard;
    }

    /**
     * Given an input step, tells where route goes...
     *
     * @return false if non maching route, the outgoing step if matches.
     */
    public function match_route() {
        global $SESSION;

        if (is_null($this->wizard)) {
            throw new CodingException("Wizard needs to be set before checking route");
        }

        if ($this->wizard->get_step() != $this->fromstep) {
            return false;
        }

        // Special pass all case :
        if ($this->varname == '*' && $this->varvalue = '*') {
            return $this->tostep;
        }

        $varname = $this->varname;
        $uniqid = $this->wizard->get_uniqueid();
        if (!empty($SESSION->$uniqid->$varname)) {
            if ($this->isregexp) {
                if (preg_match('/'.$this->varvalue.'/', $SESSION->$uniqid->$varname)) {
                    return $this->tostep;
                }
            } else {
                // Now we can handle some arithmetic operators, if first chars are : 
                // '<' , '<=', '>', '=>', '!='
                if (preg_match('/^(>|>=|<|<=|!=)\s*(.*)$/', $this->varvalue, $matches)) {
                    // we have an operator.
                    $route = false;
                    $operator = $matches[1];
                    $value = $matches[2];
                    switch($operator) {
                        case '<': {
                            if ($SESSION->$uniqid->$varname < $value) {
                                $route = $this->tostep;
                            }
                            break;
                        }
                        case '<=': {
                            if ($SESSION->$uniqid->$varname <= $value) {
                                $route = $this->tostep;
                            }
                            break;
                        }
                        case '>': {
                            if ($SESSION->$uniqid->$varname > $value) {
                                $route = $this->tostep;
                            }
                            break;
                        }
                        case '>=': {
                            if ($SESSION->$uniqid->$varname >= $value) {
                                $route = $this->tostep;
                            }
                            break;
                        }
                        case '!=': {
                            if ($SESSION->$uniqid->$varname != $value) {
                                $route = $this->tostep;
                            }
                            break;
                        }
                    }
                    return $route;
                }
                // Straight equality (with implicit operator ==)
                if ($SESSION->$uniqid->$varname == $this->varvalue) {
                    return $this->tostep;
                }
            }
        }

        return false;
    }

    /**
     * Compute a unique md5 identifier.
     */
    public function md5() {
        return md5($this->fromstep.'-'.$this->tostep.'-'.$this->varname.'-'.$this->varvalue);
    }
}