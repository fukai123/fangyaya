<?php
//include_once 'aliyun-php-sdk-core/Config.php';
//use Sms\Request\V20160927 as Sms;
//
//$iClientProfile = DefaultProfile::getProfile("cn-hangzhou", "LTAIc4X0KcsS9vRm", "Khj4vU5h7pnjvj28USlHndXs9R4AGl");
//$client = new DefaultAcsClient($iClientProfile);
//$request = new Sms\SingleSendSmsRequest();
//$request->setSignName("房丫丫");/*签名名称*/
//$request->setTemplateCode("SMS_44670017");/*模板code*/
//$request->setRecNum("15011029126");/*目标手机号*/
//$request->setParamString("{'msg':'11111'}");/*模板变量，数字一定要转换为字符串*/
//try {
//    $response = $client->getAcsResponse($request);
//    print_r($response);
//} catch (ClientException  $e) {
//    print_r($e->getErrorCode());
//    print_r($e->getErrorMessage());
//} catch (ServerException  $e) {
//    print_r($e->getErrorCode());
//    print_r($e->getErrorMessage());
//}