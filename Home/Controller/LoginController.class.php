<?php
namespace Home\Controller;

use Think\Controller;
use Think\Verify;
use Think\View;
use Sms\Request\V20160927 as Sms;

class LoginController extends Controller
{
    public function login()
    {
        $user = session('user');
        if (!empty($user)) {
            $userName = $user['user_name'];

            $user = substr($userName, 0, 3) . '**' . substr($userName, -3);

        } else {
            $user = '游客';
        }

        if (!empty($_POST)) {
            $con['user_name'] = (I('post.user_name'));
            $con['pwd'] = md5(I('post.pwd'));
            $userModel = M('user');

            $result = $userModel->where($con)->find();

            if ($result) {
                session('user', $result, 3600 * 24 * 7);
                $msg['state'] = 1;
                $msg['msg'] = '';

            } else {
                $msg['state'] = 0;
                $msg['msg'] = '用户名或密码不正确，请重新输入';
            }
            echo json_encode($msg);

        } else {
            $this->assign('user', $user);
            $this->display();
        }


    }

    public function register()
    {
        $user = session('user');
        if (!empty($user)) {
            $userName = $user['user_name'];

            $user = substr($userName, 0, 3) . '**' . substr($userName, -3);

        } else {
            $user = '游客';
        }


        $msg = array();
        if (!empty($_POST)) {
            $con['phone'] = I('post.phone');
            $con['user_name'] = I('post.phone');
            $con['pwd'] = md5(I('pwd'));
            $con['register_time'] = date('Y-m-d H:i:s', time());
            $con['client_ip']=get_client_ip();
            $phoneCode = I('post.phoneCode');
            $vertiCode = I('post.vertiCode');
            $userModel = M('user');
            if (empty($con['phone'])) {

                $msg['state'] = 0;
                $msg['msg'] = '请输入手机号';
                $msg['remark'] = 'phone';
                echo json_encode($msg);
                return;
            }


            //        检查该手机号是否被注册过
            $num = $userModel->field('user_name')->where(array('user_name' => $con['phone']))->find();
            if ($num) {
                $msg['state'] = 0;
                $msg['msg'] = '该手机号已注册。';
                $msg['remark'] = 'phone';
                echo json_encode($msg);
                die;
            }

            if (empty($phoneCode)) {

                $msg['state'] = 0;
                $msg['msg'] = '请输入短信验证码';
                $msg['remark'] = 'phoneCode';
                echo json_encode($msg);
                return;
            }
            //            验证短信
            $phoneResult = $this->checkPhone($con['phone'], $phoneCode);
            $phoneResult1 = json_decode($phoneResult, true);
            if ($phoneResult1['state'] == 0) {
                echo $phoneResult;
                return;
            }

            if (empty($con['pwd'])) {

                $msg['state'] = 0;
                $msg['msg'] = '请输入密码';
                $msg['remark'] = 'pwd';
                echo json_encode($msg);
                return;
            }
//            if (empty($vertiCode)) {
//
//                $msg['state'] = 0;
//                $msg['msg'] = '请输入图片验证码';
//                echo json_encode($msg);
//                return;
//            }

//            验证图片验证码
//            $picResult = $this->checkCode($vertiCode);
//            $picResult1 = json_decode($picResult, true);
//            if ($picResult1['state'] == 0) {
//
//                echo $picResult;
//                return;
//            }

            $userModel = M('user');
            $res = $userModel->add($con);


            if ($res) {
                $msg['state'] = 1;
                $msg['msg'] = '注册成功';
                session('user', $con);
                echo json_encode($msg);
            } else {
                $msg['state'] = 0;
                $msg['msg'] = '注册失败';
                echo json_encode($msg);
            }


        } else {
            $this->assign('user', $user);
            $this->display();
        }

    }

    public function loginOut()
    {
        session('user', '');
        session_destroy();
        $this->redirect('/home/login/login');
    }

    public function getVertify()
    {

        $vertify = new Verify();
        $vertify->length = 4;
        $vertify->expire = 30;
        $vertify->entry();

    }

    public function checkCode($code)
    {

        $validte = new Verify();
        $isRight = $validte->check($code);


        if ($isRight == false) {
            $msg['state'] = 0;
            $msg['msg'] = '验证不正确，请点击更换';
            $msg['remark'] = 'vertiCode';
            return json_encode($msg);


        } else {
            $msg['state'] = 1;
            $msg['msg'] = '验证正确';
            return json_encode($msg);
        }

    }

//        检查短信验证码是否正确

    public function checkPhone($p, $phoneCode)
    {
        $sessionPrefix = 'f';//定义session前缀，不能全是数字

        if (empty($phoneCode)) {
            $msg['state'] = 0;
            $msg['msg'] = '短信验证码不能为空';
            $msg['remark'] = 'phoneCode';
            return json_encode($msg);


        } else if ($phoneCode != session($sessionPrefix . $p)) {
            $msg['state'] = 0;
            $msg['msg'] = '短信验证码不正确，请核对';
            $msg['remark'] = 'phoneCode';
            return json_encode($msg);


        } else {
            $msg['state'] = 1;
            $msg['msg'] = '短信验证码正确';
            return json_encode($msg);
        }


    }

    public function getPhone()
    {
        $sessionPrefix = 'f';//定义session前缀，不能全是数字
        $msg = array();
        $p = I('post.phone');


//        检查该手机号是否被注册过
        $userModel = M('user');
        $num = $userModel->field('user_name')->where(array('user_name' => $p))->find();
        if ($num) {
            $msg['state'] = 0;
            $msg['msg'] = '该手机号已注册。';
            $msg['remark'] = 'phone';
            echo json_encode($msg);
            die;
        }


        $sessionCode = session($sessionPrefix . $p);


        if (!empty($sessionCode) && $sessionCode != null) {

            $msg['state'] = 1;
            $msg['msg'] = '动态码已发送到您的手机，请核对';
            $msg['remark'] = 'phoneCode';
            echo json_encode($msg);
            die;
        } else {
            //调用阿里云短信服务--start


            $randCode = rand(100000, 999999);
            session($sessionPrefix . $p, $randCode);
            Vendor('aliyunSms.aliyun-php-sdk-core.Config');
            Vendor('aliyunSms.aliyun-php-sdk-core.Profile.DefaultProfile');

            $iClientProfile = \DefaultProfile::getProfile("cn-hangzhou", "LTAIc4X0KcsS9vRm", "Khj4vU5h7pnjvj28USlHndXs9R4AGl");
            $client = new \DefaultAcsClient($iClientProfile);
            $request = new Sms\SingleSendSmsRequest();
            $request->setSignName("房丫丫");/*签名名称*/
            $request->setTemplateCode("SMS_44670017");/*模板code*/
            $request->setRecNum($p);/*目标手机号*/
            $request->setParamString("{'msg':'$randCode'}");/*模板变量，数字一定要转换为字符串*/
            $request->setAcceptFormat('json');

            try {
                $response = $client->getAcsResponse($request);
                print_r($response);
            } catch (ClientException  $e) {
                print_r($e->getErrorCode());
                print_r($e->getErrorMessage());
            } catch (ServerException  $e) {
                print_r($e->getErrorCode());
                print_r($e->getErrorMessage());
            }
        }


//调用阿里云短信服务--end

    }


}