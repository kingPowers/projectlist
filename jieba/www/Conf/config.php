<?php

return array(
    'APP_STATUS' => 'dev',
    'URL_MODE' => 2,
    'DEFAULT_THEME' => THEME_NAME,
    'LOAD_EXT_CONFIG' => 'mysql',
    'SECURE_KEY' => 'G2K3s248waz2V574rcG6F1U0Ba36',
     'TMPL_TEMPLATE_SUFFIX' => '.php',
    'URL_HTML_SUFFIX' => '.html',
    'URL_CASE_INSENSITIVE' => false,
    'URL_ROUTER_ON' => true,
    'URL_ROUTE_RULES' => array(
        '/^login$/' => 'Public/login',
    ),
    'TMPL_PARSE_STRING' => array(
        '_OS_' => 'http://manager.' . DOMAIN_ROOT,
        '_STATIC_' => 'http://static.' . DOMAIN_ROOT,
        '_WWW_' => 'http://wx.' . DOMAIN_ROOT,
    ),
    'TMPL_ACTION_ERROR' => 'Tpl/' . THEME_NAME . '/Public/notice.html',
    'TMPL_ACTION_SUCCESS' => 'Tpl/' . THEME_NAME . '/Public/notice.html',
    //REDIS相关配置
    'REDIS_CONFIG' => array(
        'HOST' => REDIS_SERVER,
        'PORT' => '6379',
    ),
);
