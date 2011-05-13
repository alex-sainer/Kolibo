<?php
/**
 * 
 * 
 * 
 */


class Kolibo_View_Helper_Ext_Js extends Zend_View_Helper_Abstract
{
    /**
     *
     * @param string $version the used ExtJS-Version
     * @param string $theme the used ExtJS-Theme
     * @param string $lang the selected Language
     * @return string the HEAD-part: JS and CSS-Files needed for ExtJS
     */
    public function ext_js($version = '3.3.1', $theme = 'xtheme-blue', $lang = 'de')
    {
	$ret = '<link rel="stylesheet" type="text/css" href="/js/ext-'.$version.'/resources/css/ext-all.css" />';
	$ret.= '<link rel="stylesheet" type="text/css" href="/js/ext-'.$version.'/resources/css/'.$theme.'.css" />';
	$ret.= '<script type="text/javascript" src="/js/ext-'.$version.'/adapter/ext/ext-base.js"></script>';
	if (APPLICATION_ENV == 'development') {
	    $ret.= '<script type="text/javascript" src="/js/ext-'.$version.'/ext-all-debug-w-comments.js"></script>';
	} else {
	    $ret.= '<script type="text/javascript" src="/js/ext-'.$version.'/ext-all.js"></script>';
	}

	$ret.= '<script type="text/javascript" src="/js/ext-'.$version.'/src/locale/ext-lang-'.$lang.'.js" ></script>';

	return $ret;
    }
}