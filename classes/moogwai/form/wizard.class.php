<?php
// This file is part of Moogwai - private project

namespace local_vflibs\moogwai\form;

use stdClass;
use local_vflibs\moogwai\exceptions\CodingException;

/**
 * A wizard is a container for a succession of forms that 
 * accumulates data for the user, before a final submission.
 */
class wizard {

    /** @var list of forms */
    protected $forms;

    /** @var string $wizardname for naming the session container */
    protected $wizardname;

    /** @var current wizard step */
    protected $step;

    /** @var wizard internal routes. If empty, will be a straightforward linear wizard */
    protected $routes = [];

    /**
     * Constructor.
     * @param int $currentstep
     */
    public function __construct($wizardname, $currentstep) {
        $this->forms = [];
        $this->formids = [];
        $this->step = $currentstep;
        $this->wizardname = $wizardname;
    }

    /**
     * Adds a form to the wizard form group.
     * @param moodleform_wizard $form a form.
     */
    public function add_form(moogwaiform_wizard $form) {
        if (!empty($this->forms)) {
            // Checks the new form has same uniquid (should have)
            if ($form->get_uniqueid() !== $this->forms[0]->get_uniqueid()) {
                throw new CodingException("All forms in a wizard must have the same form id");
            }
        }

        $this->forms[] = $form;
        if (isset($this->forms[$this->step - 1])) {
            $this->forms[$this->step - 1]->set_current(true);
        }
    }

    public function get_name() {
        return $this->wizardname;
    }

    /**
     * Gets data from the current step form, storing in session
     * the new data state.
     */
    public function get_data() {
        $this->check_forms();
        $data = $this->forms[$this->step - 1]->get_data();
        return $data;
    }

    /**
     * Extracts a single variable from the wizard store if available.
     */
    public function get_variable($varname) {
        global $SESSION;
        $key = $this->wizardname;
        return $SESSION->$key->$varname ?? null;
    }

    /**
     * Get current step of the wizard.
     */
    public function get_step() {
        return $this->step;
    }

    /**
     * Get current path of the wizard as a string.
     */
    public function get_path_string() {
        global $SESSION;

        $pathkey = $this->wizardname.'_path';
        if (!isset($SESSION->$pathkey)) {
            $SESSION->$pathkey = [];
        }
        return implode('/', $SESSION->$pathkey);
    }

    public function get_uniqueid() {
        $this->check_forms();
        return $this->forms[0]->get_uniqueid();
    }

    /**
     * Get current's step form.
     */
    public function get_form() {
        $this->check_forms();
        return $this->forms[$this->step - 1];
    }

    /**
     * Gets all data accumulated in wizard.
     */
    public function get_all_data() {
        $this->check_forms();
        $data = $this->forms[$this->step - 1]->get_all_data();
        return $data;
    }

    /**
     * checks if the current step is cancelled, cancelling the whole operation.
     */
    public function is_cancelled() {
        $this->check_forms();
        return $this->forms[$this->step - 1]->is_cancelled();
    }

    /**
     * checks if the current step is cancelled, cancelling the whole operation.
     */
    public function is_backdrawn() {
        $this->check_forms();
        return $this->forms[$this->step - 1]->is_backdrawn();
    }

    /**
     * Set data in the current step form. Accumulated
     * data will also be set from session.
     */
    public function set_data($formdata) {
        $this->check_forms();
        return $this->forms[$this->step - 1]->set_data($formdata);
    }

    /**
     * Displays the current step.
     * @return void
     */
    public function display() {
        $this->check_forms();
        $this->add_path_element($this->step);
        $this->forms[$this->step - 1]->display();
    }

    public function is_last() {
        $this->check_forms();
        return $this->forms[$this->step - 1]->is_last();
    }

    protected function check_forms() {
        if (empty($this->forms)) {
            throw new CodingException("Empty form list in wizard");
        }
    }

    /**
     * Adds a route through wizard steps.
     * @param int $from the outgoing wizard step
     * @param int $to the next step to play
     * @param string $varname if is set, the route will be traversable if the variable matches the value
     * @param string $varvalue the reference value to test on above variable
     * @param bool $regexp if true, the $varvalue will be tested as a matching regexp.
     * @return void.
     */
    public function add_route(int $from, int $to, string $varname, string $varvalue, $isregexp = false) {
        $route = new wizard_route($from, $to, $varname, $varvalue, $isregexp);
        $routehash = $route->md5();
        $route->set_wizard($this);
        if (array_key_exists($routehash, $this->routes)) {
            throw new CodingException("Wizard already contains the route");
        }
        $this->routes[$routehash] = $route;
    }

    public function delete_route(wizard_route $route) {
        $routehash = $route->md5();
        if (array_key_exists($routehash, $this->routes)) {
            unset($this->routes[$routehash]);
        }
    }

    /**
     * Let add variables names to the wizard session from outside.
     * @param string $wizardname
     * @param string $key
     * @param string $value
     */
    public function add_variable($key, $value) {
        global $SESSION;

        $wizardkey = $this->wizardname;
        if (!isset($SESSION->$wizardkey)) {
            $SESSION->$wizardkey = new StdClass;
        }
        $SESSION->$wizardkey->$key = $value;
    }

    /**
     * Reset the wizard killing all data in session.
     */
    public function reset() {
        global $SESSION;

        $wizardkey = $this->wizardname;
        unset($SESSION->$wizardkey);
        $pathkey = $wizardkey.'_path';
        unset($SESSION->$pathkey);
    }

    /**
     * Do the wizard have routes ? If not, is a straight forward wizard.
     */
     public function has_routes() {
        return !empty($this->routes);
     }

    /**
     * Get the next routed step
     */
    public function get_route() {
        if (empty($this->routes)) {
            throw new CodingException("No routes in wizard. ");
        }

        foreach ($this->routes as $r) {
            if ($nextroute = $r->match_route()) {
                return $nextroute;
            }
        }

        throw new CodingException("No routes out to go further. Developers should revise routing. Current step: {$this->step}");
    }

    /**
     * Add the current step to the session path, that will grow until it is reset by:
     * - wizard is cancelled
     * - wizard is explicitely reset
     * - wizard has gone to last state and has been processed.
     * - General user SESSION is erased.
     * @param string $step;
     */
    public function add_path_element($step) {
        global $SESSION;

        $pathkey = $this->wizardname.'_path';
        if (!isset($SESSION->$pathkey)) {
            $SESSION->$pathkey = [];
        }
        $SESSION->$pathkey[] = $step;
    }

    /**
     * Get the last visited step, so we can go back to it.
     * Walk back the array till findiing a step number different
     * from current. This should address form reloading issue.
     */
    public function get_back_route() {
        global $SESSION;

        $pathkey = $this->wizardname.'_path';
        if (is_array($SESSION->$pathkey)) {
            $backpath = array_reverse($SESSION->$pathkey);
            do {
                $backstep = array_shift($backpath);
            } while ($backstep == $this->step);
            if (!$backstep) {
                return 1;
            }
            return $backstep;
        }
        return 1;
    }
}