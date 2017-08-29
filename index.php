<?php

include_once __DIR__ . '/Fun.php';

define("TOKEN","weixin");
$appid = "wx1df1a198b53b46b5";
$appsecret = "44e0a0be39e91ab2f3271836366749f2";

$wechatObj = new wechatCallbackapiTest(); 
$wechatObj->responseMsg(); 
class wechatCallbackapiTest 
{ 
   //签名验证公共接口 
   public function valid() 
   { 
       $echoStr = $_GET["echostr"]; 
       if($this->checkSignature()){ 
           echo $echoStr; 
           exit; 
       } 
   } 
 
   //主入口处理函数 
   public function responseMsg() 
   { 
       $postStr = $GLOBALS["HTTP_RAW_POST_DATA"]; 
       if (!emptyempty($postStr)){ 
           $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); 
           $msgType=trim($postObj->MsgType); 
           
           switch($msgType){ 
               case 'text': 
                   $resultStr=$this->handleText($postObj); 
                   break; 
               case 'event': 
                   $resultStr=$this->handleEvent($postObj); 
                   break; 
               default: 
                   $resultStr=$this->handleDefault($postObj); 
                   break; 
           } 
           echo $resultStr; 
           
       }else { 
           echo "Error"; 
           exit; 
       } 
   } 
   
   //处理文本消息 
      private function handleText($obj){ 
          $keyword=trim($obj->Content); 
          if(preg_match('/天气/',$keyword)){ 
              $contentStr=$this->handleWeather($obj); 
          }elseif(preg_match('/翻译/',$keyword)){ 
              $contentStr=$this->handleTranslation($obj); 
          }else{ 
              $contentStr='Hello World'; 
          } 
          return $this->handleStr($obj,$contentStr); 
      } 
      
      //处理天气 
      private function handleWeather($obj){ 
          $keyword=mb_substr($obj->Content,-2,2,'utf-8'); 
          $zone=mb_substr($obj->Content,0,-2,'utf-8'); 
          if($keyword=='天气' && !emptyempty($zone)){ 
              $zoneArr=json_decode(file_get_contents('http://api.k780.com:88/?app=weather.city&format=json'),true); 
              $zoneArr=$zoneArr['result']; 
              $cityId=''; 
              foreach($zoneArr as $value){ 
                  if($zone==$value['citynm']){ 
                      $cityId=$value['weaid']; 
                      break; 
                  } 
              } 
              
              if(!emptyempty($cityId)){ 
                  $data=file_get_contents("http://api.k780.com:88/?app=weather.today&weaid=$cityId&appkey=10638&sign=3736578f099375665f9f141a6326b757&format=json"); 
                  $data=json_decode($data); 
                  $contentStr="今天是:".$data->result->days.",".$data->result-> week.",".$data->result->citynm."天气:".$data->result->weather."n温度:".$data->result->temperature.", 
                  ".$data->result->wind_direction.",".$data->result->wind_power.", 最低温度:".$data->result->temp_low.",最高温度:".$data->result->temp_high; 
              }else{ 
                  $contentStr='找不到输入的城市!'; 
              }    
          }else{ 
              $contentStr='输入的查询格式不正确!'; 
          } 
          return $contentStr; 
      } 
      
      //处理翻译 
      private function handleTranslation($obj){ 
       $keyword=mb_substr($obj->Content,0,2,'utf-8'); 
       $words=mb_substr($obj->Content,2,220,'utf-8'); 
       if($keyword=='翻译' && !emptyempty($words)){ 
           $data=file_get_contents('http://fanyi.youdao.com/openapi.do?keyfrom=zfsblog&key=364295447&type=data&doctype=json&version=1.1&q='.urlencode($words));        
           $data=json_decode($data,true); 
           
           switch($data['errorCode']){ 
               case '0': 
                   $contentStr=$data['translation'][0]; 
                   break; 
               case '20': 
                   $contentStr='要翻译的文本过长'; 
                   break; 
               case '30': 
                   $contentStr='无法进行有效的翻译'; 
                   break; 
               case '40': 
                   $contentStr='不支持的语言类型'; 
                   break; 
               case '50': 
                   $contentStr='无效的key'; 
                   break; 
               default: 
                   $contentStr='Error'; 
                   break; 
           } 
       }else{ 
           $contentStr='输入的翻译格式不正确!'; 
       } 
       return $contentStr; 
      } 
      
      //处理聊天信息 
      private function handleChat($obj){ 
       $keywords=$obj->Content; 
       $curlPost=array("chat"=>$keywords); 
       $ch = curl_init();//初始化curl 
       curl_setopt($ch, CURLOPT_URL,'http://www.xiaojo.com/bot/chata.php');//抓取指定网页 
       curl_setopt($ch, CURLOPT_HEADER, 0);//设置header 
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上 
       curl_setopt($ch, CURLOPT_POST, 1);//post提交方式 
       curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost); 
       $data = curl_exec($ch);//运行curl 
       curl_close($ch); 
       
       if(!emptyempty($data)){ 
           $contentStr=$data; 
       }else{ 
           $ran=rand(1,5); 
           
           switch($ran){ 
               case 1: 
                   $contentStr= "小九今天累了，明天再陪你聊天吧"; 
                   break; 
               case 2: 
                   $contentStr= "小九睡觉喽~~"; 
                   break; 
               case 3: 
                   $contentStr= "呼呼~~呼呼~~"; 
                   break; 
               case 4: 
                   $contentStr= "你话好多啊，不跟你聊了"; 
                   break; 
               case 5: 
                   $contentStr= "你话好多啊，不跟你聊了"; 
                   break; 
           } 
       } 
       return $contentStr; 
      } 
      
      //创建自定义菜单 
      public function createMenu($data,$token){ 
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$token); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)'); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        $result = curl_exec($ch);      
        curl_close($ch); 
        return $result; 
      } 
      
      //查询自定义菜单 
   function getMenu($token){ 
       $url="https://api.weixin.qq.com/cgi-bin/menu/get?access_token=$token"; 
       $ch = curl_init($url); 
       curl_setopt($ch, CURLOPT_RETURNTRANSFER,true) ; //获取数据返回 
       curl_setopt($ch, CURLOPT_BINARYTRANSFER,true) ; //在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回    
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false); 
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false); 
       return $output = curl_exec($ch);        
      } 
      
      //删除自定义菜单 
      public function deleteMenu($token){ 
       $url="https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$token; 
       $ch = curl_init(); 
       curl_setopt($ch, CURLOPT_URL, $url); 
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
       $result = curl_exec($ch); 
       curl_close($ch); 
       return $result; 
      }
      //处理事件消息 
      private function handleEvent($obj){ 
          $content=''; 
          switch($obj->Event){ 
              case 'subscribe': 
                  $content.="welcome-欢迎关注该公众号号!"; 
                  break; 
              case 'unsubscribe': 
                  $content.="感谢您一直以来对该公众号的关注,再见!"; 
                  break; 
              default: 
                  $content.=""; 
                  break; 
          } 
          return $this->handleStr($obj,$content); 
      } 
      
      //处理回复消息字符串 
      private function handleStr($obj,$content='',$flag=0){ 
       $textTpl = ""; 
       return sprintf($textTpl, $obj->FromUserName, $obj->ToUserName, time(), $content,$flag); 
      } 
   
   //签名验证函数 
   private function checkSignature() 
   { 
       $signature = $_GET["signature"]; 
       $timestamp = $_GET["timestamp"]; 
       $nonce = $_GET["nonce"];    
       $token = TOKEN; 
       $tmpArr = array($token, $timestamp, $nonce); 
       sort($tmpArr, SORT_STRING); 
       $tmpStr = implode( $tmpArr ); 
       $tmpStr = sha1( $tmpStr ); 
       if( $tmpStr == $signature ){ 
           return true; 
       }else{ 
           return false; 
       } 
   } 
}