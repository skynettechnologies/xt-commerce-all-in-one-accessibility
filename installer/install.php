<?php

defined('_VALID_CALL') or die('Direct Access is not allowed.');

require_once _SRV_WEBROOT . _SRV_WEB_PLUGINS . 'xt_all_in_one_accessibility/classes/constants.php';

global $db, $language, $store_handler;

$supported_langs = array('de','en');
$txt_dir = _SRV_WEBROOT.'plugins/xt_all_in_one_accessibility/installer/consent/';

function _getFileContent($filename) {
    $handle = fopen($filename, 'rb');
    $content = fread($handle, filesize($filename));
    fclose($handle);
    return $content;
}

$bodies = array(
    'de' => _getFileContent($txt_dir.'consent_de.txt'),
    'en' => _getFileContent($txt_dir.'consent_en.txt'),
    'all' => _getFileContent($txt_dir.'consent_en.txt')
);

$titles = array(
    'de' => 'Zustimmung zur Verwendung von Cookies',
    'en' => 'All in One Accessibility®',
    'all' => 'All in One Accessibility®'
);

$maxContentId = $db->GetOne("SELECT MAX(content_id) FROM ".TABLE_CONTENT_ELEMENTS);
if ($maxContentId==false) $maxContentId = 0;
$maxContentId++;

// Check store id column exists
$store_id_col_exists = $this->_FieldExists('content_store_id', TABLE_CONTENT_ELEMENTS);

// Insert into content elements
foreach($store_handler->getStores() as $store)
{
    foreach($language->_getLanguageList('admin') as $lang)
    {
        $lang_id = strtolower($lang['id']);
        $title = $titles[in_array($lang_id, $supported_langs) ? $lang_id : 'all'];
        $body = $bodies[in_array($lang_id, $supported_langs) ? $lang_id : 'all'];

        if ($store_id_col_exists) {
            $sql = "INSERT IGNORE INTO ".TABLE_CONTENT_ELEMENTS." (content_id,language_code,content_title,content_heading,content_body,content_store_id) VALUES (?, ?, ?, ?, ?, ?)";
            try {
                $db->Execute($sql, array($maxContentId, $lang_id, $title, $title, $body, $store['id']));
            } catch (Exception $e) {
                error_log("Install Error (content_elements): ".$e->getMessage());
            }
        } else {
            $sql = "INSERT IGNORE INTO ".TABLE_CONTENT_ELEMENTS." (content_id,language_code,content_title,content_heading,content_body) VALUES (?, ?, ?, ?, ?)";
            try {
                $db->Execute($sql, array($maxContentId, $lang_id, $title, $title, $body));
            } catch (Exception $e) {
                error_log("Install Error (content_elements no store): ".$e->getMessage());
            }
        }
    }
}

// Insert into content
$sql = "INSERT IGNORE INTO ".TABLE_CONTENT." (content_id,content_parent,content_status,content_hook,content_form,content_image,content_sort) VALUES (?, ?, ?, ?, ?, ?, ?)";
try {
    $db->Execute($sql, array($maxContentId, 0, 1, 0, 0, '', 0));
} catch (Exception $e) {
    error_log("Install Error (content): ".$e->getMessage());
}

// Update plugin configuration
try {
    $db->Execute("UPDATE ".TABLE_PLUGIN_CONFIGURATION." SET `config_value`=? WHERE `config_key`='XT_AIOA_ALLINONEACCESSIBILITY'", array($maxContentId));
    $db->Execute("UPDATE ".TABLE_PLUGIN_CONFIGURATION." SET `config_value`=? WHERE `config_key`='XT_AIOA_UNINSTALL_CONTENT_ID'", array($maxContentId));
} catch (Exception $e) {
    error_log("Install Error (plugin config update): ".$e->getMessage());
}

?>
