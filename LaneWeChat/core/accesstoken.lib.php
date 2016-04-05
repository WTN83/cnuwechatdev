<?php
namespace LaneWeChat\Core;
/**
 * 微信Access_Token的获取与过期检查
 * Created by Lane.
 * User: lane
 * Date: 13-12-29
 * Time: 下午5:54
 * Mail: lixuan868686@163.com
 * Website: http://www.lanecn.com
 */
class AccessToken{

    /**
     * 获取微信Access_Token
     */
    public static function getAccessToken(){
        //检测本地是否已经拥有access_token，并且检测access_token是否过期
        // $accessToken = self::_checkAccessToken();
        // if($accessToken === false){
        //     $accessToken = self::_getAccessToken();
        // }

        // if (isset($_SERVER['HTTP_APPNAME'])){        //SAE环境，需要开通memcache
        //     $mem = memcache_init();
        // }else {                                        //本地环境，需已安装memcache
        //     $mem = new Memcache;
        //     $mem->connect('localhost', 11211) or die ("Could not connect");
        // }
        // $accessToken = json_decode($mem->get('accesstoken'),true);
        // if (!isset($accessToken) || empty($accessToken)){
        //     $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".WECHAT_APPID."&secret=".WECHAT_APPSECRET;
        //     $accessToken = Curl::callWebServer($url, '', 'GET');//返回的是一个数组
        // if(!isset($accessToken['access_token'])){
        //     return Msg::returnErrMsg(MsgConstant::ERROR_GET_ACCESS_TOKEN, '获取ACCESS_TOKEN失败');
        // }
        //     $mem->set('accesstoken', $accessToken, 0, 3600);
        // }


        // return $accessToken['access_token'];

        $mmc = memcache_init();
        $access_token = memcache_get($mmc, "token");
 
        if(empty($access_token)){
          $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.WECHAT_APPID.'&secret='.WECHAT_APPSECRET;
         
        $accessToken = Curl::callWebServer($url, '', 'GET');//返回的是一个数组
        if(!isset($accessToken['access_token'])){
            return Msg::returnErrMsg(MsgConstant::ERROR_GET_ACCESS_TOKEN, '获取ACCESS_TOKEN失败');
        }
         
          $token = $accessToken['access_token'];
         
          memcache_set($mmc, "token", $token, 0, 7100);
         
          $access_token = memcache_get($mmc, "token");


    }
    return $access_token;
}

    /**
     * @descrpition 从微信服务器获取微信ACCESS_TOKEN
     * @return Ambigous|bool
     */
    private static function _getAccessToken(){
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.WECHAT_APPID.'&secret='.WECHAT_APPSECRET;
        $accessToken = Curl::callWebServer($url, '', 'GET');//返回的是一个数组
        if(!isset($accessToken['access_token'])){
            return Msg::returnErrMsg(MsgConstant::ERROR_GET_ACCESS_TOKEN, '获取ACCESS_TOKEN失败');
        }
        // $accessToken['time'] = time();
        $accessTokenJson = json_encode($accessToken); //生成一个json格式的字符串变量$accessTokenJson
        /**
         * 原方法是存入数据库，这里我为了提高读取速度 选择sae提供的memcache
         *
         * sae还提供了storage的存储方式，由于说明文档没有看懂，故选用memcache
         */
        // 存入memcache
        // $mmc = memcache_init();
        // $mmc = new Memcache;
        // $ret =$mmc->connect();
        // if($ret == true){
        //     $mmc ->set('access_token',$accessTokenJson,0,7200)
        // }else{
        //     echo "mc init failed\n";
        // }
        // $mmc->close();


        //存入数据库
        /**
         * 这里通常我会把access_token存起来，然后用的时候读取，判断是否过期，如果过期就重新调用此方法获取，存取操作请自行完成
         *
         * 请将变量$accessTokenJson给存起来，这个变量是一个字符串
         */
        $f = fopen('access_token', 'w+');
        fwrite($f, $accessTokenJson);
        fclose($f);
        return $accessToken;
    }

    /**
     * @descrpition 检测微信ACCESS_TOKEN是否过期
     *              -10是预留的网络延迟时间
     * @return bool
     */
    private static function _checkAccessToken(){
        //获取access_token。是上面的获取方法获取到后存起来的。
       // $accessToken = YourDatabase::get('access_token');
        $data = file_get_contents('access_token');


        $accessToken['value'] = $data;
        if(!empty($accessToken['value'])){
            $accessToken = json_decode($accessToken['value'], true);
            if(time() - $accessToken['time'] < $accessToken['expires_in']-10){
                return $accessToken;
            }
        }
        return false;


         /**
         * 由于上面的方法中使用了memcache，下面的方法中，从memcache调用accesstoken
         *
         * 该方法错误
         */
        // $mmc = new Memcache;
        // $ret =$mmc->connect();
        // if ($ret == false) {
        //     return false;
        // } else {
        //     $accessToken = $mmc->get("access_token");
        //     if($accessToken == false){
        //         $mmc->close();
        //         return false;
        //     }else{
        //         $mmc->close();
        //         $accessToken = json_decode($accessToken, true);
        //         return $accessToken;
        //     }

        // }

    }
}
?>