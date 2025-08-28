<?php
defined('_VALID_CALL') or die('Direct Access is not allowed.');

// Correct path - only _SRV_WEBROOT needed
require_once _SRV_WEBROOT . 'plugins/xt_all_in_one_accessibility/classes/constants.php';

// Ensure $result is always an array
if (!isset($result) || !is_array($result)) $result = [];

// Safe handling of $request['get']
$requestKey = $request['get'] ?? '';

switch ($requestKey) {

    case 'plg_xt_aioa_position':
        $result = [
            ['id' => 'top_left', 'name' => XT_AIOA_POSITION_TOP_LEFT, 'desc' => XT_AIOA_POSITION_TOP_LEFT],
            ['id' => 'top_center', 'name' => XT_AIOA_POSITION_TOP_CENTER, 'desc' => XT_AIOA_POSITION_TOP_CENTER],
            ['id' => 'top_right', 'name' => XT_AIOA_POSITION_TOP_RIGHT, 'desc' => XT_AIOA_POSITION_TOP_RIGHT],
            ['id' => 'middle_left', 'name' => XT_AIOA_POSITION_MIDDLE_LEFT, 'desc' => XT_AIOA_POSITION_MIDDLE_LEFT],
            ['id' => 'middle_right', 'name' => XT_AIOA_POSITION_MIDDLE_RIGHT, 'desc' => XT_AIOA_POSITION_MIDDLE_RIGHT],
            ['id' => 'bottom_left', 'name' => XT_AIOA_POSITION_BOTTOM_LEFT, 'desc' => XT_AIOA_POSITION_BOTTOM_LEFT],
            ['id' => 'bottom_center', 'name' => XT_AIOA_POSITION_BOTTOM_CENTER, 'desc' => XT_AIOA_POSITION_BOTTOM_CENTER],
            ['id' => 'bottom_right', 'name' => XT_AIOA_POSITION_BOTTOM_RIGHT, 'desc' => XT_AIOA_POSITION_BOTTOM_RIGHT],
        ];
        break;

    case 'url_XT_AIOA_CUSTOM_POSITION_HORIZONTAL_TYPE':
        $result = [
            ['id' => 'left', 'name' => 'To the Left'],
            ['id' => 'right', 'name' => 'To the Right'],
        ];
        break;

    case 'url_XT_AIOA_CUSTOM_POSITION_VERTICAL_TYPE':
        $result = [
            ['id' => 'top', 'name' => 'To the Top'],
            ['id' => 'bottom', 'name' => 'To the Bottom'],
        ];
        break;

    case 'url_XT_AIOA_WIDGET_SIZE':
        $result = [
            ['id' => '0', 'name' => __define('TEXT_XT_AIOA_SIZE_REGULAR')],
            ['id' => '1', 'name' => __define('TEXT_XT_AIOA_SIZE_OVERSIZE')],
        ];
        break;

    case 'url_XT_AIOA_ICON_TYPE':
        for ($i = 1; $i <= 29; $i++) {
            $result[] = [
                'id' => 'aioa-icon-type-' . $i,
                'name' => __define('TEXT_XT_ICON_TYPE_' . $i)
            ];
        }
        break;

    case 'url_XT_AIOA_ICON_SIZE':
        $result = [
            ['id' => 'aioa-big-icon', 'name' => __define('TEXT_XT_ICON_SIZE_BIG')],
            ['id' => 'aioa-medium-icon', 'name' => __define('TEXT_XT_ICON_SIZE_MEDIUM')],
            ['id' => 'aioa-default-icon', 'name' => __define('TEXT_XT_ICON_SIZE_DEFAULT')],
            ['id' => 'aioa-small-icon', 'name' => __define('TEXT_XT_ICON_SIZE_SMALL')],
            ['id' => 'aioa-extra-small-icon', 'name' => __define('TEXT_XT_ICON_SIZE_EXTRA_SMALL')],
        ];
        break;

    default:
        $result[] = ['id' => 'fallback', 'name' => 'No Option Found'];
        break;
}
