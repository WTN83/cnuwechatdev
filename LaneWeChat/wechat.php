<?php
namespace LaneWeChat;

use LaneWeChat\Core\Wechat;
use LaneWechat\Core\Menu;
/**
 * 微信插件唯一入口文件.
 * @Created by Lane.
 * @Author: lane
 * @Mail lixuan868686@163.com
 * @Date: 14-1-10
 * @Time: 下午4:00
 * @Blog: Http://www.lanecn.com
 */
//引入配置文件
include_once __DIR__.'/config.php';
//引入自动载入函数
include_once __DIR__.'/autoloader.php';
//调用自动载入函数
AutoLoader::register();
//初始化微信类
$wechat = new WeChat(WECHAT_TOKEN, TRUE);



//首次使用需要注视掉下面这1行（26行），并打开最后一行（29行）
echo $wechat->run();
//首次使用需要打开下面这一行（29行），并且注释掉上面1行（26行）。本行用来验证URL
// $wechat->checkSignature();


// $menuList = array(
//     array('id'=>'1', 'pid'=>'',  'name'=>'常规', 'type'=>'', 'code'=>'key_1'),
//     array('id'=>'2', 'pid'=>'1',  'name'=>'点击', 'type'=>'click', 'code'=>'key_2'),
//     array('id'=>'3', 'pid'=>'1',  'name'=>'浏览', 'type'=>'view', 'code'=>'http://www.baidu.com'),
//     array('id'=>'4', 'pid'=>'',  'name'=>'扫码', 'type'=>'', 'code'=>'key_4'),
//     array('id'=>'5', 'pid'=>'4', 'name'=>'扫码带提示', 'type'=>'scancode_waitmsg', 'code'=>'key_5'),
//     array('id'=>'6', 'pid'=>'4', 'name'=>'扫码推事件', 'type'=>'scancode_push', 'code'=>'key_6'),
//     array('id'=>'7', 'pid'=>'',  'name'=>'发图', 'type'=>'', 'code'=>'key_7'),
//     array('id'=>'8', 'pid'=>'7', 'name'=>'系统拍照发图', 'type'=>'pic_sysphoto', 'code'=>'key_8'),
//     array('id'=>'9', 'pid'=>'7', 'name'=>'拍照或者相册发图', 'type'=>'pic_photo_or_album', 'code'=>'key_9'),
//     array('id'=>'10', 'pid'=>'7', 'name'=>'微信相册发图', 'type'=>'pic_weixin', 'code'=>'key_10'),
//     array('id'=>'11', 'pid'=>'1', 'name'=>'语义理解调试', 'type'=>'location_select', 'code'=>'key_11'),
// );
// \LaneWeChat\Core\Menu::setMenu($menuList);
\LaneWeChat\Core\Menu::delMenu();

