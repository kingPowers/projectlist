<?php
return array(
    'APP_STATUS' => APP_STATUS,
    'URL_MODE' => 2,
    'DEFAULT_THEME' => THEME_NAME,
    'TMPL_ACTION_ERROR' => 'Tpl/' . THEME_NAME . '/Public/notice.php',
    'TMPL_ACTION_SUCCESS' => 'Tpl/' . THEME_NAME . '/Public/notice.php',
    'TMPL_EXCEPTION_FILE' => 'Tpl/' . THEME_NAME . '/__system__/exception.php',
    'TMPL_TRACE_FILE' => 'Tpl/' . THEME_NAME . '/__system__/trace.php',
    'TMPL_TEMPLATE_SUFFIX' => '.php',
    'URL_HTML_SUFFIX' => '.html',
    'URL_CASE_INSENSITIVE' => true,
    'LOAD_EXT_CONFIG' => 'project,site,sms,,mysql,cache'.','.APP_STATUS,//非调试模式无法自动加载自定义模式配置
    'DEFAULT_MODULE' => DEFAULT_MODULE,
    'LAYOUT_ON' => true,
    'SINA_PAYEE_ID' => 1,

		'AppSecret'=>'87e0cf7a65b6fb01bc86a6c8cdabe960',
		
		'AppID'=>'wx713f7c915e5634bb',
);
