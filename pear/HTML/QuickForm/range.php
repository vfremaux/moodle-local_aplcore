<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 4.0                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997, 1998, 1999, 2000, 2001 The PHP Group             |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Valery Fremaux <valery.fremaux@gmail.com>                   |
// +----------------------------------------------------------------------+
//
// $Id$

require_once("HTML/QuickForm/input.php");

/**
 * HTML class for a text field
 * 
 * @author       Valery Fremaux <valery.fremaux@gmail.com> 
 * @version      1.0
 * @access       public
 */
class HTML_QuickForm_range extends HTML_QuickForm_input
{
    // {{{ constructor

    /**
     * Class constructor
     * 
     * @param     string    $elementName    (optional)Input field name attribute
     * @param     string    $elementLabel   (optional)Input field label
     * @param     mixed     $attributes     (optional)Either a typical HTML attribute string 
     *                                      or an associative array
     * @since     1.0
     * @access    public
     * @return    void
     */
    public function __construct($elementName = null, $elementLabel = null, $attributes = null) {
        parent::__construct($elementName, $elementLabel, $attributes);
        $this->_persistantFreeze = true;
        $this->setType('range');
    } //end constructor

    // }}}
    // {{{ setSize()

    /**
     * Sets size of text field
     * 
     * @param     string    $size  Size of text field
     * @since     1.3
     * @access    public
     * @return    void
     */
    function setSize($size)
    {
        $this->updateAttributes(['size' => $size]);
    } //end func setSize

    // {{{ setMin()

    /**
     * Sets min of range
     * 
     * @param     string    $size  Size of text field
     * @since     1.3
     * @access    public
     * @return    void
     */
    function setMin($min)
    {
        $this->updateAttributes(['min' => $min]);
    } //end func setMin

    // {{{ setMax()

    /**
     * Sets min of range
     * 
     * @param     string    $size  Size of text field
     * @since     1.3
     * @access    public
     * @return    void
     */
    function setMax($max)
    {
        $this->updateAttributes(['max' => $max]);
    } //end func setMin

    // {{{ setRange()

    /**
     * Sets min and max of range
     * 
     * @param     string    $size  Size of text field
     * @since     1.3
     * @access    public
     * @return    void
     */
    function setRange($min, $max)
    {
        $this->updateAttributes(['max' => $max, 'min' => $min]);
    } //end func setRange

    // {{{ setRange()

    /**
     * Sets min and max of range
     * 
     * @param     string    $size  Size of text field
     * @since     1.3
     * @access    public
     * @return    void
     */
    function setStep($step)
    {
        $this->updateAttributes(['step' => $step]);
    } //end func setStep

    /**
     * Returns the input field in HTML
     * 
     * @since     1.0
     * @access    public
     * @return    string
     */
    function toHtml()
    {
        if ($this->_flagFrozen) {
            return $this->getFrozenHtml();
        } else {
            return parent::toHtml().' <div id="val_'.$this->elementName.'"></div>';
        }
    } //end func toHtml

} //end class HTML_QuickForm_range

require_once('HTML/QuickForm.php');
