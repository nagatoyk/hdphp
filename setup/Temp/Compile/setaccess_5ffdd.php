<?php if(!defined("HDPHP_PATH"))exit;C("SHOW_NOTICE",FALSE);?><?php if(!defined("HDPHP_PATH"))exit;C("SHOW_NOTICE",FALSE);?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>        
        <title>欢迎使用  后盾HD框架安装配置</title>
        <meta http-equiv="content-type" content="text/htm;charset=utf-8"/>  
        <link href='http://localhost/hdphp/setup/./Tpl/State/Css/setup.css' rel='stylesheet' type='text/css'/>
        <script type='text/javascript' src='http://localhost/hdphp/hdphp/Extend/Org/Jquery/jquery-1.8.2.min.js'></script>
    </head>

<div class="hd_setup">
    <strong>欢迎使用后盾HD框架，通过HD框架手册或登录<a href="http://bbs.houdunwang.com/">后盾论坛</a>学习使用HD框架安装配置</strong>

    <h2>
        <a href="http://localhost/hdphp/setup/index.php?c=Rbac" class="home">返回安装首页</a>
        <a href="javascript:void(0)" class="home" onclick="window.close();return false;">关闭</a>
        <a href="<?php echo U('rbac/lock');?>" class="home">锁定SETUP应用</a>

    </h2>

    <h2><?php echo $role['title'];?>--权限</h2>
</div>
<div class="setup">
    <form action="<?php echo U('addaccess');?>" method="post">
        <input type="hidden" name="rid" value="<?php echo $_GET['rid'];?>"/>

        <div id="hd_rbac">
            <?php
            foreach($node as $v){
            ?>
            <h1>
                <label>
                    <input type="checkbox" name="node[]" value="<?php echo $v['nid'];?>" class="check"
                <?php $_emptyVar =isset($v['rid'])?$v['rid']:null?><?php  if( empty($_emptyVar)){?>
                    <?php }else{ ?>
                    checked='checked'
                <?php }?>
                /><?php echo $v['title'];?><strong>(<?php echo $v['name'];?>)</strong>
                </label>
            </h1>
            <?php
            if(isset($v['node'])&&is_array($v['node'])){
            foreach($v['node'] as $m){
            ?>
            <table>
                <thead>
                <tr>
                    <th>
                        <label>
                        <input type="checkbox" name="node[]" value="<?php echo $m['nid'];?>" class="check"
                        <?php $_emptyVar =isset($m['rid'])?$m['rid']:null?><?php  if( empty($_emptyVar)){?>
                            <?php }else{ ?>
                            checked='checked'
                        <?php }?>
                        /><?php echo $m['title'];?><strong>(<?php echo $m['name'];?>)</strong>
                        </label>
                    </th>
                </tr>
                </thead>
                <?php
                    if(isset($m['node'])&&!empty($m['node'])){
                    ?>
                <tbody>
                <tr>
                    <td>
                        <ul>
                            <?php
                                foreach($m['node'] as $j){
                                ?>
                            <li>
                                <label>
                                    <input type="checkbox" name="node[]" value="<?php echo $j['nid'];?>" class="check"
                                    <?php $_emptyVar =isset($j['rid'])?$j['rid']:null?><?php  if( empty($_emptyVar)){?>
                                        <?php }else{ ?>
                                        checked='checked'
                                    <?php }?>
                                    /> <?php echo $j['title'];?><strong>(<?php echo $j['name'];?>)</strong>
                                </label>
                            </li>
                            <?php
                                }
                                ?>
                        </ul>
                    </td>
                </tr>
                </tbody>
                <?php
                }
                ?>
            </table>
            <?php
            }
            }
            }
            ?>
        </div>
        <div style="margin-left:20px;margin-top:20px;">
            <input type="submit" value="修改" class="query"/>
        </div>
    </form>
</div>
</body>
</html>
