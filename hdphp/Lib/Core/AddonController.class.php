<?php

/**
 * 插件父级控制器
 * Class AddonController
 * @author 后盾向军 <houdunwangxj@gmail.com>
 */
class AddonController extends Controller
{
    public function __construct()
    {
        parent::construct();
        C('TPL_FIX', '.php');
    }
}