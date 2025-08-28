<?php
/*
 #########################################################################
 #                       xt:Commerce Shopsoftware
 # ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 #
 # Copyright 2021 xt:Commerce GmbH All Rights Reserved.
 # This file may not be redistributed in whole or significant part.
 # Content of this file is Protected By International Copyright Laws.
 #
 # ~~~~~~ xt:Commerce Shopsoftware IS NOT FREE SOFTWARE ~~~~~~~
 #
 # https://www.xt-commerce.com
 #
 # ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 #
 # @copyright xt:Commerce GmbH, www.xt-commerce.com
 #
 # ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 #
 # xt:Commerce GmbH, Maximilianstrasse 9, 6020 Innsbruck
 #
 # office@xt-commerce.com
 #
 #########################################################################
 */

defined('_VALID_CALL') or die('Direct Access is not allowed.');

require_once _SRV_WEBROOT . _SRV_WEB_PLUGINS. 'xt_all_in_one_accessibility/classes/constants.php';

class xt_all_in_one_accessibility_banner extends AllinOneAccessibilityBanner
{
    public static function getBanner(): string
    {
        global $_content;

        $tpl_data = [
            'color' => constant('XT_AIOA_COLOR'),
            'position' => constant('XT_AIOA_POSITION'),
            'widget_size' => constant('XT_AIOA_WIDGET_SIZE'),
            'icon_type' => constant('XT_AIOA_ICON_TYPE'),
            'icon_size' => constant('XT_AIOA_ICON_SIZE'),
            'is_custom_position' => constant('XT_AIOA_IS_CUSTOM_POSITION'),
            'is_custom_size' => constant('XT_AIOA_IS_CUSTOM_SIZE'),
            'custom_icon_size' => constant('XT_AIOA_CUSTOM_ICON_SIZE'),
            'position_right' => constant('XT_AIOA_POSITION_RIGHT'),
            'position_left' => constant('XT_AIOA_POSITION_LEFT'),
            'position_top' => constant('XT_AIOA_POSITION_TOP'),
            'position_bottom' => constant('XT_AIOA_POSITION_BOTTOM'),
        ];

        $template = new Template();
        $template->getTemplatePath('all_in_one_accessibility.tpl.html', 'xt_all_in_one_accessibility', '', 'plugin');
        return $template->getTemplate('', 'all_in_one_accessibility.tpl.html', $tpl_data);
    }
}

