<?php
// .-----------------------------------------------------------------------------------
// |  Software: [HDPHP framework]
// |   Version: 2013.01
// |      Site: http://www.hdphp.com
// |-----------------------------------------------------------------------------------
// |    Author: 向军 <houdunwangxj@gmail.com>
// | Copyright (c) 2012-2013, http://houdunwang.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
// |   License: http://www.apache.org/licenses/LICENSE-2.0
// '-----------------------------------------------------------------------------------
final class App
{
    /**
     * 运行应用
     * @access public
     * @reutrn mixed
     */
    public static function run()
    {
        //session处理
        session(C("SESSION_OPTIONS"));
        //加载应用与事件处理类
        self::loadEventClass();
        //执行应用开始事件
        event("APP_START");
        //Debug Start
        DEBUG and Debug::start("APP_START");
        self::start();
        //Debug End
        DEBUG and Debug::show("APP_START", "APP_END");
        //日志记录
        Log::save();
        event("APP_END");
    }

    //加载应用与模块end事件类
    static private function loadEventClass()
    {
        $app_end_event = C("app_event.app_end");
        if ($app_end_event) {
            foreach ($app_end_event as $c) {
                HDPHP::autoload($c . 'Event');
            }
        }
        $content_end_event = C("app_event.control_end");
        if ($content_end_event) {
            foreach ($content_end_event as $c) {
                HDPHP::autoload($c . 'Event');
            }
        }
    }

    /**
     * 运行应用
     * @access private
     */
    static private function start()
    {
        //控制器实例
        $controller = control(CONTROLLER);
        //控制器不存在
        if (!$controller) {
            //模块检测
            if(!is_dir(MODULE_PATH)){
                _404('模块' .MODULE  . '不存在');
            }
            //空控制器
            $controller = Control("Empty");
            if (!$controller) {
                _404('控制器' . CONTROLLER .C("CONTROLLER_FIX") .'不存在');
            }
        }
        //执行动作
        try {
            $action = new ReflectionMethod($controller, ACTION);
            if ($action->isPublic()) {
                $action->invoke($controller);
            } else {
                throw new ReflectionException;
            }
        } catch (ReflectionException $e) {
            $action = new ReflectionMethod($controller, '__call');
            $action->invokeArgs($controller, array(ACTION, ''));
        }
    }
}
?>