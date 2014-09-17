
# HDPHP 

* 后盾网HDPHP框架是一个为用PHP程序语言编写网络应用程序的人员提供的软件包。 提供强大的、完整的类库包,满足开发中的项目需求,可以将需要完成的任务代码量最小化，大大提高项目开发效率与质量。高效的核心编译处理机制让系统运行更快。
* 做为优秀的框架产品,在系统性能上做的大量的优化处理,只为让程序员使用HDPHP框架强悍的功能同时,用最短的时间完成项目的开发。
----
#技术支持：
>后盾网： [http://www.houdunwang.com](http://www.houdunwang.com "后盾网")
> 
>HDPHP官网： [http://www.hdphp.com ](http://www.hdphp.com "HDPHP官网")
>
![后盾网  人人做后盾](https://git.oschina.net/houdunwang/hdphp/raw/master/hdphp/Data/Image/houdunwang.jpg)  
----
# 全面的WEB开发特性支持
* HDPHP是否完全免费的，你不用担心任何版权问题
* 提供多项优化策略，速度非常快
* 采用 MVC 设计模式
* URL全站路由控制
* 支持Memcached、Redis等NoSql
* 高效的HDView模板引擎
* 拥有全范围的类库
* 通过自定义类库、辅助函数来实现框架的扩展
* JS前端自动验证
* PHP自动验证、自动完成、字段映射、表单令牌
* 高级扩展模型
* 全站缓存控制
* 中文分词
* 商城购物车处理
* RBAC角色控制
* 完整的错误处理机制
* 集成前端常用库（编辑器、文件上传、图片缩放等等）
* 对象关系映射(ORM)
* 与后盾网hdjs完美整合

----
#安全性
框架在系统层面提供了众多的安全特性，确保你的网站和产品安全无忧。这些特性包括：

* COOKIE加密处理
* 数据预处理机制
* XSS安全防护
* 表单自动验证
* 强制数据类型转换
* 输入数据过滤
* 表单令牌验证
* 防SQL注入
* 图像上传检测

----
#商业友好的开源协议
HDPHP遵循Apache2开源协议发布。Apache Licence是著名的非盈利开源组织Apache采用的协议。该协议和BSD类似，鼓励代码共享和尊重原作者的著作权，同样允许代码修改，再作为开源或商业软件发布。

----

#更新日志

2014.8.23

```
1. 修复路由器
```

2014.8.19

```
1. 增强where()查询方法
2. 增加Hooks钩子机制
3. 增加Addons插件机制
4. 增加二维码操作
5. 增加远程下载操作
6. 增加下载内容页图片操作
7. 优化Db.class.php类
```

2014.6.27

```
更新原有的ACP模式为MCA模式
```
2014.3.29

```
1. 修复设置Page类url属性时，不能正常显示页码问题
2. Debug调试界面增加显示常量标签
3. 自定义Uploadify插件php程序时，返回数据包含table_id时，将用隐藏表单记录值，可用于记录上传的表主键数据
```

2014.3.28

```
1. 修复视图模型执行后，关联表不复位的问题
2. 修复一处hdjs错误
3. 更新KindEditor 至4.1.10
4. 修复通过Jquery异步提交表单时KindEditor第一次无值的情况
```

2014.3.22

```
1. 修复Route路由添加模式修正符失效的问题
```

2014.3.17
```
1. 用户密码采用3层加密更安全
2. 修改学员反映的JS验证错误
```

2014.3.16

```
1. 修复uploadify插件上传文件失败问题
2. 修复Backup::recovery()还原数据失败
3. 修复hdjs当ajax验证规则与其他规则同时作用时，ajax验证失败的问题
4. 增加less标签用于调用less.js快速进行CSS编写
```

2014.3.14

```
1. 修改数据还原失败问题
2. 修改ueditor上传失败问题
3. Bootstrap更新到3.0
```

2014.3.6

```
1. 修复HDJS在回车时，ajax验证失败问题
2. 优化hdjs自动验证代码
3. 更新hdjs手册
```

2014.3.5

```
1. 修复当不设置APP_PATH或GROUP_PATH时__PUBLIC__等常量路径错误问题
```

2014.3.4

```
1. 更新HDPHP手册
2. 更新BootStrap 3.0
```

2014.2.8

```
1. import方法支持应用组中的类加载
2. Upload类返回值增加上传目录
3. 修改ViewCompile重复加载标签类的问题
```
 
2014.1.22

```
1. 修改uploadify插件input表单的value值错误
2. 修改HDJS的CSS错误
3. 更新HDPHP手册
```

2014.1.15

```
1. 修改上传类Upload.clas.php，单文件上传时返回一维数组
2. HDJS增加新功能
```

2014.1.15

```
1. 模型新增检测表是否已经存在的方法isTable
```

2014.1.14

```
1. 增加hd_ajax快速异步提交方法
2. 修改高级模型中MANY_TO_MANY关联删除
```

2014.1.13

```
1. 修改自动完成错误
2. HDJS添加加入收藏夹方法
3. 添加获得时空中唯一值uuid方法get_uuid
4. Cart::getOrderId()获得定单号
```

2014.1.12

```
1. HDJS库增加新功能
2. 更新HDJS手册
3. 修改__GROUP__常量错误
```

2014.1.11

```
1. 模型增加addAll支持批量插入数据
2. 更新HDPHP手册
3. 修复盾友‘lvdong5830’提出的当where值为0时的问题
```

2014.1.10

```
1. 修改目录常量错误
2. 更新hdjs手册
```

2014.1.8

```
1. 修改url错误问
```
2014.1.7

```
1. hdui更名为hdjs
2. hdjs做为单独应用处理，不做为hdphp扩展
3. 更新HDJS的validation验证插件名为validate
4. DEBUG添加session与cookie标签 
5. 修正HDJS中confirm方法验证失败问题
6. 修改validate.class.php方法maxlen中文验证失败问题
7. hdjs增加slide轮换版插件
8. 更新HDJS与HDPHP手册
9. Model增加触发器开关
```
2014.1.6

```
1. 添加新的DEBUG调试界面
2. 修正HDJS中confirm方法验证失败问题
```

2014.1.5

```
1. 解决$_REQUEST数据不正确的问题
2. 解决模板存在扩展名时还是添加扩展名的问题
3. 修复Validate.class.php中minlen方法错误
4. 添加新的DEBUG调试界面

```

2014.1.3

```
1. $this->_post()这种调用方式废弃了
2. 自定义session处理方式发生改变，请参考手册
3. 升级百度编辑器ueditor至1.3.6版本
4. 自动验证增加身份证验证
5. 自动验证方法maxlen与minlen改变验证字符长度(支持中文)
6. 修改keditor目录结构
```

2013.12.30

```
1. 修改success.html与error.html样式
2. 修改sessionFile.class.php目录问题
3. 增加捕获致命性错误问题
```

2013.12.25

```
1. 修正session文件创建失败的问题
2. 添加cookie支持
3. 优化session处理机制
4. 重新定义session的配置项设置
5. 新增cookie配置项设置
```
2013.12.21

```
1. 修改在应用组模式时，配置auto_load_file失败的问题
2. 压缩HDPHP数据，去除一些不用的JS插件如ICHECK
3. 修改模板标签加载失败问题
4. 修复Data::parentChannel()由于使用static在生成全站静态时造成的问题
5. hdui中添加slide轮换效果
```
2013.12.19

```
1. 增加应用分组变量g,分组应用管理更加强大
2. 增加目录安全文件创建方法Dir::safeFile
3. 更新HDPHP手册
4. 优化Html静态文件生成类
5. 修复js自动验证confirm不起作用问题
```
