<?php
// This file is part of Moogwai - private project

namespace local_vflibs\moogwai\form\elements;

use local_vflibs\moogwai\exceptions\CodingException;
use core\output\renderer_base;

/**
 * Adds export_for_template behaviour to an mform element in a consistent and predictable way.
 *
 * @package   core_form
 */
defined('MOOGWAI_INTERNAL') || die();

/**
 * templatable_form_element trait.
 *
 * @package   core_form
 */
trait templatable_form_element {

    /**
     * Function to export the renderer data in a format that is suitable for a
     * mustache template. This means:
     * 1. No complex types - only stdClass, array, int, string, float, bool
     * 2. Any additional info that is required for the template is pre-calculated (e.g. capability checks).
     *
     * This trait can be used as-is for simple form elements - or imported with a different name
     * so it can be extended with additional context variables before being returned.
     *
     * @param renderer_base $output Used to do a final render of any components that need to be rendered for export.
     * @return stdClass|array
     */
    public function export_for_template(renderer_base $output) {
        $context = [];

        // Not all elements have all of these attributes - but they are common enough to be valid for a few.
        $standardattributes = ['id', 'name', 'label', 'multiple', 'checked', 'error', 'size', 'value', 'type'];
        $standardproperties = ['helpbutton', 'hiddenLabel'];

        // Standard attributes.
        foreach ($standardattributes as $attrname) {
            $value = $this->getAttribute($attrname);
            $context[$attrname] = $value;
        }

        // Standard class properties.
        foreach ($standardproperties as $propname) {
            $classpropname = '_' . $propname;
            $context[strtolower($propname)] = isset($this->$classpropname) ? $this->$classpropname : false;
        }
        $extraclasses = $this->getAttribute('class');

        // Special wierd named property.
        $context['frozen'] = !empty($this->_flagFrozen);
        $context['hardfrozen'] = !empty($this->_flagFrozen) && empty($this->_persistantFreeze);

        // Other attributes.
        $otherattributes = [];
        foreach ($this->getAttributes() as $attr => $value) {
            if (!in_array($attr, $standardattributes) && $attr != 'class' && !is_object($value)) {
                if (is_array($value)) {
                    throw new CodingException("Unexpected attribute array value");
                }
                $otherattributes[] = $attr . '="' . s($value) . '"';
            }
        }
        $context['extraclasses'] = $extraclasses;
        $context['type'] = $this->getType();
        $context['attributes'] = implode(' ', $otherattributes);
        $context['emptylabel'] = ($this->getLabel() === '');
        $context['iderror'] = preg_replace('/_id_/', '_id_error_', $context['id']);
        $context['iderror'] = preg_replace('/^id_/', 'id_error_', $context['iderror']);

        // Elements with multiple values need array syntax.
        if ($this->getAttribute('multiple')) {
            $context['name'] = $context['name'] . '[]';
        }

        return $context;
    }
}
