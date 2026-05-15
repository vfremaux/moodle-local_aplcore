<?php
// This file is part of Moogwai - private project.

/**
 * A Generic controller interface for all MVC schemes.
 *
 * @package     moogwai_backoffice
 */

namespace local_aplcore\moogwai;

defined('MOOGWAI_INTERNAL') || die();

interface controller {

    public function receive($cmd, $data = null, $mform = null);

    public function process();
}