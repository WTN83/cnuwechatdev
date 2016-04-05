#cnuwechatdev
##微信公众平台二次开发笔记

###预计实现目标：
* 快递查询---通过接入公共快递api
* 周边美食推荐---微信LBS
* 日常出行信息查询---包括但不限于 天气 空气质量 穿衣指数 交通情况等（DB）
* 法律知识在线咨询---法律人员微信接入--在线解答
* 社区物业客服接入---社区服务人员微信接入
* 提交建议---以及建议回复

###用户分类
* 社区管理者---公众平台管理，公众平台信息录入
* 社区居民---公众平台使用者
* 社区法律顾问---公众平台法律客服
* 社区志愿者---需要报名，可由社区居民以及获得认证的社会志愿者组成
* 社区物业客服---客服人员微信接入  24小时服务

###开发难点
* 公共快递api的接入
* 社区周边o2o 微信LBS的运用
* 微信智能语义接口的使用
* 日常出行信息的导入   如何运用第三方 而不使用人工录入

####拓展开发
* 用户行为数据收集---用户脸谱描绘


本项目基于[LaneWeChat框架](https://github.com/lixuancn/LaneWeChat)  
关于LaneWeChat：
>框架简介：为快速开发微信应用而生的PHP框架。将微信的开发者功能根据文档进行了封装。  
开发语言：PHP  
版本要求：原则PHP5.3以上  
版本规避：若版本低于PHP5.3，则删除本框架所有页面开头“namespace”的行、删除本框架中所有的“use LaneWeChat”开头的行，删除“LaneWeChat\Core”，修改Autoloader::NAMESPACE_PREFIX=''，修改curl.lib.php的\Exception为Exception即可。  
命名空间：本框架的命名空间均为LaneWeChat开头。  
开源协议：Do What The Fuck You Want To Public License  
开发者博客：http://www.lanecn.com  
文档地址：<a href="http://lanewechat.lanecn.com/">http://lanewechat.lanecn.com/</a>


---
**本项目根据实际开发情况对该框架部分已封装功能进行了重写，并对部分命名模糊的方法名称作出了修改。  
本项目实际版本要求为:PHP5.6以上**  
开源协议: Do What The Fuck You Want To Public License  
本项目地址：<a href="https://github.com/WTN83/cnuwechatdev">https://github.com/WTN83/cnuwechatdev</a>
###常识普及
	微信公众账号分两种，一种是订阅号，一种是服务号。
	1、订阅号是被动响应用户消息功能，并且每天推送一条消息。
	
	2、服务号是300元/每年认证，被动响应用户消息，主动给用户发送消息，自定义菜单按钮，网页授权等功能，并且每月推送一条消息。
	
	3、订阅号适合消息类，新闻类应用，常常需要推送文章给用户的；服务号适合自助查询等。

	4、订阅号被认证后也享用自定义菜单等功能，仍旧是300元/每年
###相关关键字解释：

	1、OpenId：微信服务器并不会告诉公众号用户的微信ID，即使是你的关注者也不行，为了解决开发中唯一标识的问题，微信使用了OpenId，所谓的OpenId，就是用户和微信公众号之间的一种唯一关系。一个用户在一个公众号面前，享用唯一的OpenId，不会和别人重复。换言之，同一个用户在另一个公众号面前，是拥有另一个OpenId的。再直白些就是$openId = md5('用户微信ID+公众号ID')
	
	2、Access_Token：此项只有认证号的功能才会使用的到，Access_token是一个授权标识，即一个授权验证码，一个标识7200s内有效，7200s的有效期内公众号的多个关注者可以使用同一个Access_Token。在使用主动给指定用户发送消息、自定义菜单、用户管理和用户组管理等功能的时候，每次操作需要给微信服务器以参数的形式附带Access_token。
	在本小组的科研中，使用了Memcache缓存access_token;使用者的程序运行环境中若没有安装Memcache，可以注释掉Memcache中的方法，并打开原框架中的方法。
	
	3、Access_Token网页版：本Access_Token网页版授权时会使用到，和2中的Access_Toekn是不同的东西，不过使用我们的LaneWeChat微信快速开发框架是不需要了解这些的。Access_Token网页版是说在用户打开你的公众号提供的网页的时候，你的网页需要获取用户的OpenId、昵称、头像等信息的时候授权用的。同时，本Access_Token网页版有两种用法，一种是打开网页后弹出一个授权框，让用户点击是否授权，界面像主流的开放平台授权界面（比如QQ登陆某网站，支付宝账号登陆某网站等）；另一种是不需要弹出授权框仍旧可以获取用户信息，用法可以在实例中看到。



###如何安装：
	1、本框架可以以代码包的插件形式放在项目的目录中也可以将根目录下的wechat.php直接作为文件入口。调用时只需要include 'lanewechat/lanewechat.php'即可。如：
	        <?php
	        include 'lanewechat/lanewechat.php';
	        //获取自定义菜单列表
	        $menuList = Menu::getMenu();
	
	2、配置项：打开根目录下的config.php，修改定义常量WECHAT_APPID，WECHAT_APPSECRET，WECHAT_URL。其中前两项可以在微信公众号官网的开发者页面中找到，而WECHAT_URL是你微信项目的URL，以http://开头
	
	3、本框架的外部访问唯一入口为根目录下的wechat.php，本框架的内部调用唯一入口为根目录下的lanewechat.php。两者的区别是wechat.php是通过http://www.abc.com/lanewechat/wechat.php访问，是留给微信平台调用的入口。而lanewechat.php是我们项目内部调用时需要include 'lanewechat/lanewechat.php';
	
	4、首次使用时，请打开根目录下的wechat.php，注释掉echo $wechat->run();并打开$wechat->checkSignature();以完成微信服务器配置。
	
	5、在微信开发者-填写服务器配置页面，填写URL为http://www.lanecn.com/wechat.php，保证该URL可以通过80端口正常访问（微信服务器目前只支持80端口），并且将Token填写为config.php中的WECHAT_TOKEN常量的内容（可以修改）。
	
	6、微信服务器在第4步验证通过后，反向操作第4步，即注释掉echo $wechat->run()，打开注释$wechat->checkSignature()。至此，安装配置完成。

	7、文件入口wechat.php中，我们注释了对自定义菜单的操作，使用者可以在完成微信服务器验证之后，打开这几行代码，完成自定义菜单的设置。
