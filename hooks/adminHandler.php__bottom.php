<?php

defined('_VALID_CALL') or die('Direct Access is not allowed.');

if (!empty($form_grid) && $form_grid->code == 'plugin_installed' && isset($form_grid->params['header']['conf_XT_AIOA_COLOR_shop_1'])) {
    $base_path = str_replace('/xtAdmin/', '/', _SRV_WEB);
    $plugin_path = 'http://' . $_SERVER['HTTP_HOST'] . $base_path . 'plugins/xt_all_in_one_accessibility/';

    echo '<script type="text/javascript" src="' . $plugin_path . 'javascript/xt_all_in_one_accessibility.js"></script>';
}

