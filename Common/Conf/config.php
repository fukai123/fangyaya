<?php
return array(
	// '配置项'=>'配置值'

    // 设置默认模块为Home
    'MODULE_ALLOW_LIST'     =>  array('Home'),
    'DEFAULT_MODULE'        =>  'Home',
    'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
    'DEFAULT_ACTION'        =>  'Index', // 默认操作名称
    'SITE'=>'http://www.jiuquhouse.com/',
    'SITELOCAL'=>'http://www.local.jiuquhouse.com:8888/',
    'SESSION_AUTO_START' => true,

    // 加载自定义标签

    
    // url模式设置
    'URL_MODEL'             =>  2,  
    'URL_ROUTER_ON'         =>  true,   // 是否开启URL路由
    'URL_HTML_SUFFIX'       =>  'html',  // URL伪静态后缀设置

     // 数据库设置 
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '120.77.154.193', // 服务器地址
    'DB_NAME'               =>  'fangyaya',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'fukai.aly.2462482017',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'f_',    // 数据库表前缀

    
);