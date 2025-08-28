<?php

defined('_VALID_CALL') or die('Direct Access is not allowed.');

require_once _SRV_WEBROOT . _SRV_WEB_PLUGINS. 'xt_all_in_one_accessibility/classes/class.xt_all_in_one_accessibility_banner.php';
AllinOneAccessibilityRegistry::setAllinOneAccessibilityBanner(new xt_all_in_one_accessibility_banner());
if(!AllinOneAccessibilityRegistry::hasAllinOneAccessibility()) AllinOneAccessibilityRegistry::setEmitAllinOneAccessibilitySettingsJs(true);
