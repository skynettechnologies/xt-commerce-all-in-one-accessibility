<?php
/*
 #########################################################################
 #                       All in One Accessibility Plugin
 # ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 #
 # Copyright 2025 Skynet Technologies USA LLC. All Rights Reserved.
 # This file is part of the xt_all_in_one_accessibility plugin.
 #
 # Unauthorized copying of this file, via any medium, is strictly prohibited.
 # Proprietary and confidential.
 #
 # ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 #
 # @author   Skynet Technologies USA LLC
 # @website  https://www.skynettechnologies.com
 # @contact  developer3@skynettechnologies.com
 #
 #########################################################################
 */

use GuzzleHttp\Cookie\SetCookie;

defined('_VALID_CALL') or die('Direct Access is not allowed.');


class AllinOneAccessibilityRegistry
{
    public const AIOA_ALLINONEACCESSIBILITY_NAME = '_all_in_one_accessibility';

    /** @var AllinOneAccessibilityRegistry  */
    private static $_instance;

    private $_request_cookies = [];

    /** @var  array */
    private $_cookie_jars = [];

    /** @var AllinOneAccessibilityBanner */
    private $_allinoneaccessibilityBanner;

    private $_isDirty = false;

    private $_emitAllinOneAccessibilitySettingsJs = true;

    private $_initFunctions = [];
    /**
     * @var AllinOneAccessibilityBanner
     */
    private $_cookieBanner;

    /**
     * @return bool
     */
    public static function emitAllinOneAccessibilitySettingsJs(): bool
    {
        return self::_instance()->_emitAllinOneAccessibilitySettingsJs;
    }

    /**
     * @param bool $emit
     * @return AllinOneAccessibilityRegistry
     */
    public static function setEmitAllinOneAccessibilitySettingsJs(bool $emit)
    {
        self::_instance()->_emitAllinOneAccessibilitySettingsJs = $emit;
        return self::_instance();
    }

    /**
     * @return AllinOneAccessibilityBanner
     */
    public function getAllinOneAccessibilityBanner(): AllinOneAccessibilityBanner
    {
        return $this->_cookieBanner;
    }

    /**
     * @param AllinOneAccessibilityBanner $cookieBanner
     * @return AllinOneAccessibilityRegistry
     */
    public static function setAllinOneAccessibilityBanner(AllinOneAccessibilityBanner $cookieBanner)
    {
        self::_instance()->_cookieBanner = $cookieBanner;
        return self::_instance();
    }

    /**
     * AllinOneAccessibilityRegistry constructor.
     * @param array $cookies
     */
    private function __construct($cookies = [])
    {
        $this->_request_cookies = $cookies;
        foreach(CookieType::types() as $type)
        {
            $this->_cookie_jars[$type] = [];
        }
    }

    /**
     * @return AllinOneAccessibilityRegistry
     */
    private static function _instance()
    {
        if(empty(self::$_instance))
            self::$_instance = new AllinOneAccessibilityRegistry();
        return self::$_instance;
    }

    /**
     * @param null $cookies
     */
    public static function init($cookies = null)
    {
        if(!is_array($cookies)) $cookies = [];
        self::_instance()->_request_cookies = $cookies;
        if(self::hasAllinOneAccessibility())
        {
            $c = new SetCookie();
            $c->setName(self::AIOA_ALLINONEACCESSIBILITY_NAME);
            $c->setValue(self::_instance()->_request_cookies[self::AIOA_ALLINONEACCESSIBILITY_NAME]);
            $c->setDomain($_SERVER["SERVER_NAME"]);
            $c->setSecure(checkHTTPS());
            $c->setHttpOnly(false);
            $c->setPath(_SRV_WEB);
            $ci = new AllinOneAccessibilityInfo(CookieType::FUNCTIONAL, $_SERVER["SERVER_NAME"], $c,
                'Zustimmung zur Verwendung von Cookies / All in One Accessibility');
            self::registerCookie($ci);
            $_SESSION[self::AIOA_ALLINONEACCESSIBILITY_NAME] = '1';
        }
    }

    public static function hasAllinOneAccessibility(): bool
    {
        return (is_array(self::_instance()->_request_cookies) && array_key_exists(self::AIOA_ALLINONEACCESSIBILITY_NAME, self::_instance()->_request_cookies))
            || (is_array($_SESSION) && array_key_exists(self::AIOA_ALLINONEACCESSIBILITY_NAME, $_SESSION));
    }

    public static function registerCookieScript(AllinOneAccessibilityInfo $ci)
    {
        self::registerCookie($ci, false);
    }

    public static function registerCookie(AllinOneAccessibilityInfo $ci, $send = false)
    {
        if(!CookieType::valid($ci->getType()))
            throw new InvalidArgumentException('['.$ci->getType() .'] is not an accepted cookie type');
        self::_instance()->_cookie_jars[$ci->getType()][] = $ci;
    }


    public static function sendCookies()
    {

    }

    public static function checkGetBanner()
    {
        if(!self::hasAllinOneAccessibility()) return self::getBanner();
        return '';
    }

    public static function getCookieAllowed($type)
    {

        if($type === CookieType::FUNCTIONAL)
            return true;
        if(!self::hasAllinOneAccessibility())
            return false;

        static $_all_in_one_accessibility = 0;
        if($_all_in_one_accessibility === 0)
        {
            $_all_in_one_accessibility = self::_instance()->_request_cookies[self::AIOA_ALLINONEACCESSIBILITY_NAME];
            $_all_in_one_accessibility = json_decode( html_entity_decode( stripslashes ($_all_in_one_accessibility ) ));
        }

        if(empty($_all_in_one_accessibility) || empty($_all_in_one_accessibility->$type))
            return false;
        if(!isset($_all_in_one_accessibility->$type->allowed))
            return false;

        return $_all_in_one_accessibility->$type->allowed;
    }

    public static function getAllinOneAccessibilitySettings()
    {
        $settings = ['topics' => []];
        foreach(self::_instance()->_cookie_jars as $type => $cookies)
        {
            $type_settings = new stdClass();
            $type_settings->allowed = self::getCookieAllowed($type);
            $type_settings->cookies = self::_instance()->_cookie_jars[$type];
            $settings['topics'][$type] = $type_settings;
        }
        return $settings;
    }

    public static function getAllinOneAccessibilitySettingsJs(): string
    {
        $settings = self::getAllinOneAccessibilitySettings();
        $js = self::$js;
        $js .= "\n<script>\nconst AIOA_ALLINONEACCESSIBILITY_NAME = \"".self::AIOA_ALLINONEACCESSIBILITY_NAME."\";\nconst allinoneaccessibility_settings = ".json_encode($settings,JSON_PRETTY_PRINT).";\n</script>\n";
        return $js;
    }

    public static function getBannerHtml()
    {
        if (empty(self::_instance()->_cookieBanner )) return '';
        return self::_instance()->_cookieBanner::getBanner();
    }

    public static function registerInitFunction($fnName)
    {
        self::_instance()->_initFunctions[] = $fnName;
    }

    public static function getInitFunctions()
    {
        return self::_instance()->_initFunctions;
    }

    private static $js = '
<script>    
// returns the cookie with the given name,
// or undefined if not found
function getCookie(name) {
    let matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, \'\\$1\') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

function xtSetCookie(name, value, options) 
{
    if (typeof options != "object")
        options = {};
    
    let options_local = {
        path: baseUri
    };
    
    for (let attrname in options) { options_local[attrname] = options[attrname]; }
    
    if (options.expires instanceof Date) {
        options_local.expires = options.expires.toUTCString();
    }
    
    //console.log(options_local);
    
    let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);
    
    for (let optionKey in options_local) {
        updatedCookie += "; " + optionKey;
        let optionValue = options_local[optionKey];
        if (optionValue !== true) {
            updatedCookie += "=" + optionValue;
        }
    }
    
    //console.log(updatedCookie);
    
    document.cookie = updatedCookie;
}

function xtDeleteCookie(name) {
    setCookie(name, "", {
        \'max-age\': -1
    })
}
</script>  
    ';

}
