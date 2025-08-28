<?php

defined('_VALID_CALL') or die('Direct Access is not allowed.');

require_once _SRV_WEBROOT . _SRV_WEB_PLUGINS. 'xt_all_in_one_accessibility/classes/constants.php';
require_once _SRV_WEBROOT . _SRV_WEB_PLUGINS. 'xt_all_in_one_accessibility/classes/class.AllinOneAccessibilityRegistry.php';

global $xtMinify;

$xtMinify->add_resource(_SRV_WEB_PLUGINS.'xt_all_in_one_accessibility/javascript/aioa.js', 900, 'footer');

//
