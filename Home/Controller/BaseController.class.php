<?php
/**
 * Created by PhpStorm.
 * User: fukai3
 * Date: 2017-1-10
 * Time: 17:18
 */

namespace Home\Controller;

use Think\Controller;


class BaseController extends Controller
{
    protected $currentUser;
    public function __construct()

    {

        parent::__construct();

        $this->checkLogin();



    }


    protected function checkLogin()
    {
        $no_check = array(

            'index' => array('index', 'detail', 'test','t'),

        );


        $controller = strtolower(substr(__CONTROLLER__, strrpos(__CONTROLLER__, '/') + 1));

        $action = strtolower(substr(__ACTION__, strrpos(__ACTION__, '/') + 1));
//   echo __CONTROLLER__ . '<br>' . $controller . '<br>' . $action;
        if (isset($no_check[$controller]) && in_array($action, $no_check[$controller])) {

//        不需要检查登录，直接return

            return;

        } else {
//            判断是否登录
            $user = session('user');
            if (!empty($user)) {
                $this->currentUser=$user;
                return;
            } else {

                $this->redirect('home/login/login');

            }

        }


    }

    protected function getUserName()
    {
        $user = session('user');
        if (!empty($user)) {
            $userName = $user['user_name'];

            $user = substr($userName, 0, 3) . '**' . substr($userName, -3);

        } else {
            $user = '游客';
        }
        return $user;
    }

}