<?php
// .-----------------------------------------------------------------------------------
// |  Software: [HDPHP framework]
// |   Version: 2013.01
// |      Site: http://www.hdphp.com
// |-----------------------------------------------------------------------------------
// |    Author: 向军 <houdunwangxj@gmail.com>
// | Copyright (c) 2012-2013, http://www.houdunwang.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
// |   License: http://www.apache.org/licenses/LICENSE-2.0
// '-----------------------------------------------------------------------------------
/**
 * HDPHP框架入口文件
 * 在应用入口引入hdphp.php即可运行框架
 * @package hdphp
 * @supackage core
 * @author hdxj <houdunwangxj@gmail.com>
 */
//对旧框架的使用建议
if (defined("APP")) {
    _error_msg("必须使用APP_NAME定义应用名");
}
if (defined("APP_PATH") && substr(APP_PATH, -1) !== '/') {
    _error_msg("APP_PATH常量必须以/结尾");
}
if (defined("GROUP_PATH") && substr(GROUP_PATH, -1) !== '/') {
    _error_msg("GROUP_PATH常量必须以/结尾");
}
//debug
defined("DEBUG") or define("DEBUG", FALSE);
//应用组
if (defined('GROUP_NAME') or defined('GROUP_PATH')) {
    defined('GROUP_NAME') or define('GROUP_NAME', basename(dirname($_SERVER['SCRIPT_NAME'])));
    defined('GROUP_PATH') or define('GROUP_PATH', './' . GROUP_NAME . '/');
} else {
//应用
    defined('APP_NAME') or define('APP_NAME', basename(dirname($_SERVER['SCRIPT_NAME'])));
    defined('APP_PATH') or define('APP_PATH', './' . APP_NAME . '/');
}

//临时目录
defined('TEMP_PATH') or define('TEMP_PATH', (defined('APP_PATH') ? APP_PATH : GROUP_PATH) . 'Temp/');
define('TEMP_FILE', TEMP_PATH . 'boot.file');
//加载核心编译文件
if (!DEBUG and is_file(TEMP_FILE)) {
    require TEMP_FILE;
} else {
    //编译文件
    define('HDPHP_PATH', dirname(__FILE__) . '/');
    require HDPHP_PATH . '/Lib/Core/Boot.class.php';
    Boot::run();
}
function _error_msg($msg)
{
    header("Content-type:text/html;charset=utf-8");
    echo "<div style='font-size:18px;padding:20px;background:#FFD896;font-family: 微软雅黑;'>$msg</div>";
    exit;
}

?>