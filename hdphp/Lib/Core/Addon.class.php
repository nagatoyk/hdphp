<?php

/**
 * 插件基类
 * Class Addon
 */
class Addon
{
    public $addonName; //插件名
    public $addonPath; //插件目录
    public $configFile; //插件配置文件
    public function __construct()
    {
        $this->addonName = $this->getAddonName();
        $this->addonPath = APP_ADDON_PATH . $this->addonName . '/';
        $this->configFile = $this->addonPath . 'config.php';
    }

    //获得配置项
    public function getConfig()
    {
        $config = array();
        if ($data = M('addons')->where(array('name' => $this->addonName))->find()) {
            $config = unserialize($data['config']);
        }
        if (empty($data)) {
            if (is_file($this->configFile)) {
                $data = require $this->configFile;
                $config = array();
                foreach ($data as $name => $v) {
                    $config[$name] = $v['value'];
                }
            }
        }
        return $config;
    }

    //获得插件名
    final public function getAddonName()
    {
        $class = get_class($this);
        return substr($class, 0, strrpos($class, 'Addon'));
    }
}