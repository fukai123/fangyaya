<?php
namespace Home\Controller;

use Think\Controller;
use Think\Image;

class IndexController extends BaseController
{
//首页
    public function index()
    {


        $cityModel = M('city');
//先判断用户是否是要切换城市


        $cityId = I('get.cityId');

        if (!empty($cityId)) {

            $cityId = $cityId;
            cookie('cityId', $cityId, 3600 * 24 * 30 * 12);

        } else {
            $ck = cookie('cityId');
            if (!empty($ck)) {
                $cityId = cookie('cityId');
                cookie('cityId', $cityId, 3600 * 24 * 30 * 12);
            } else {
                $cityId = 1;
                cookie('cityId', $cityId, 3600 * 24 * 30 * 12);
            }

        }

        $currentCity = $cityModel->field('city_name')->where(array('id' => $cityId))->find();

        cookie('cityName', $currentCity['city_name'], 3600 * 24 * 30 * 12);

//切换  城市列表
        $cityList = $this->getCitys(0);
//         print_r($cityList);
//区域列表

        $areaList = $this->getCitysWithoutOrder($cityId);
        // print_r($areaList);


//城镇列表
        $currentArea = '';
        $areaId = I('get.areaId', 'fang');
        if ($areaId != 'fang' && !empty($areaId)) {
            $countryList = $this->getCitys($areaId);
//            当前城镇名称
            $currentArea = $cityModel->field('city_name')->where(array('id' => $areaId))->find();
//            var_dump($currentArea);
//            print_r($countryList);

        }

        $currentCoutry = '';
        $countryId = I('get.countryId', 'fang');
        if ($countryId != 'fang' && !empty($countryId)) {
            $currentCoutry = $cityModel->field('city_name')->where(array('id' => $countryId))->find();
        }
//        var_dump($currentCoutry);


        //租金
        $moneyModel = M('money');
        $money = $moneyModel->select();

///户型
        $hxModel = M('huxing');
        $hx = $hxModel->select();
//        方式
        $rentStyleModle = M('rentstyle');
        $rentStyle = $rentStyleModle->select();

        //主次卧
        $roomModel = M('roomstyle');
        $roomList = $roomModel->select();


//        echo '<pre>';
//        var_dump($countryList);
//        echo '</pre>';
        //用户当前选择条件
        $current = array('cityId' => $cityId, 'cityName' => $currentCity['city_name'], 'areaId' => I('get.areaId', 'fang'), 'countryId' => I('get.countryId', 'fang'), 'money' => I('get.money', 'fang'), 'hx' => I('hx', 'fang'), 'rs' => I('rs', 'fang'), 'roomstyle' => I('get.roomstyle', 'fang'));
//显示数据
        $con = array();
        $con['province'] = cookie('cityId');
        $areaId_data = I('areaId', 'fang');
        if ($areaId_data != 'fang' && !empty($areaId_data)) {

            $con['city'] = $areaId_data;
        }
        $countryId_data = I('countryId', 'fang');
        if ($countryId_data != 'fang' && !empty($countryId_data)) {
            $con['country'] = $countryId_data;
        }
        $money_data = I('money', 'fang');
        if ($money_data != 'fang' && !empty($money_data)) {
            $split = explode('-', $money_data);

            $con['rent_money'] = array(array('between', array($split[0], $split[1])), array('eq', 0), 'or');

        }
        $hx_data = I('hx', 'fang');

        if ($hx_data != 'fang' && !empty($hx_data) && $hx_data != '0') {
            $con['shi'] = $hx_data;
        }
        $rs_data = I('rs', 'fang');
        if ($rs_data != 'fang' && !empty($rs_data) && $rs_data != 'bx') {
            $con['rent_style'] = $rs_data;
        }
//        var_dump($rs_data);
        if ($rs_data == 'dj') {
            $roomStyle_data = I('roomstyle', 'fang');
            if ($roomStyle_data != 'fang' && !empty($roomStyle_data) && $roomStyle_data != '5') {
                $con['room_style'] = $roomStyle_data;
            }
        }
//        print_r($con);
        $resourceModel = M('house_resource');

        $totalPage = $resourceModel->where($con)->count();

        $p = I('get.p', 1);
        $homeList = $resourceModel->field('f_house_resource.*,f_pay.style')->order('create_time desc')->where($con)->join('left join f_pay on f_house_resource.pay_style=f_pay.id')->page($p, C('PERPAGE'))->select();
//        echo $resourceModel->getLastSql();
//
//        var_dump($homeList);
//        $sql = $resourceModel->getLastSql();
//        var_dump($sql);
//var_dump($homeList);
        $picModel = M('picture');
        foreach ($homeList as &$home1) {
//            var_dump($home1);
            $pic = $picModel->where(array('resource_id' => $home1['id']))->find();
            $home1['pic_small'] = $pic['pic_large'];

        }

        if ($totalPage <= 0) {
            $pagebar = '';
        } else {
            $page = new \tool\Page($totalPage, C('PERPAGE'));

            foreach ($current as $key => $val) {
                $page->parameter[$key] = urlencode($val);
            }

            $pagebar = $page->show();
        }
        $this->assign('user', $this->getUserName());
        $this->assign('cityList', $cityList);
        $this->assign('areaList', $areaList);
        $this->assign('currentCity', array(cookie('cityId'), cookie('cityName')));
        $this->assign('currentArea', $currentArea);
        $this->assign('currentCountry', $currentCoutry);
        $this->assign('current', $current);
        $this->assign('countryList', $countryList);
        $this->assign('money', $money);
        $this->assign('hx', $hx);
        $this->assign('rentStyle', $rentStyle);
        $this->assign('roomList', $roomList);
        $this->assign('homeList', $homeList);
        $this->assign('pageBar', $pagebar);



//var_dump($homeList);
        $this->display();

    }


//详情页
    public function detail()
    {
        $houseId = I('get.id');
        $cId=I('get.currentCity');
        $curentCity=M('city')->where(array('id'=>$cId))->find();

        $resourceModel = M('house_resource');

        $detailList = $resourceModel->field('f_house_resource.*,f_pay.style as yajin,f_rentstyle.rent_style as chuzu,f_decoration.decoration as zhuangxiu,f_direction.direction as fangxiang')->join('left join f_pay on f_house_resource.pay_style=f_pay.id left join f_rentstyle on f_house_resource.rent_style=f_rentstyle.name left join f_decoration on f_house_resource.decoration=f_decoration.id left join f_direction on f_house_resource.direction=f_direction.id')->where(array('f_house_resource.id' => $houseId))->find();

//        更新浏览次数

        $resourceModel->execute("update f_house_resource set scancount=scancount+1 where id=$houseId");
        //        查询图片
        $picModel = M('picture');
        $picList = $picModel->where(array('resource_id' => $houseId))->select();
//        获取城市一级二级地区 如 海淀区-西三旗
        $cityModel = M('city');

        $c = $cityModel->field('city_name')->where(array('id' => array('in', array($detailList['city'], $detailList['country']))))->select();


//        echo $resourceModel->getLastSql();
//        var_dump($detailList);
//        var_dump($picList);
        $furniture = $resourceModel->field('furniture')->where(array('id' => $houseId))->find();
//        var_dump($furniture);
        if ($furniture['furniture'] != '') {
            $fur = explode('-', $furniture['furniture']);
//        房屋配置
            foreach ($fur as $val) {
                switch ($val) {
                    case  'chest':
                        $furList[] = '衣柜';
                        break;
                    case  'sofa':
                        $furList[] = '沙发';
                        break;
                    case  'telev':
                        $furList[] = '电视';
                        break;
                    case  'icebox':
                        $furList[] = '冰箱';
                        break;
                    case  'balcony':
                        $furList[] = '阳台';
                        break;
                    case  'air-condition':
                        $furList[] = '空调';
                        break;
                    case  'broadband':
                        $furList[] = '宽带';
                        break;
                    case  'fuel-gas':
                        $furList[] = '可做饭';
                        break;
                    case  'washer':
                        $furList[] = '洗衣机';
                        break;
                    case  'water-heater':
                        $furList[] = '热水器';
                        break;
                    case  'toilet':
                        $furList[] = '独立卫生间';
                        break;
                    case  'bed':
                        $furList[] = '床';
                        break;
                    case
                    'central-heater':
                        $furList[] = '暖气';
                        break;
                }
            }
            $fur1 = implode('-', $furList);
        } else {
            $fur1 = '暂无介绍';
        }

        if ($furniture['furniture'] != '') {
            $fur = explode('-', $furniture['furniture']);
            //        房源详情
            foreach ($fur as $val) {
                switch ($val) {
                    case  'chest':

                        $f[] = array('name' => $val, 'val' => '衣柜');
                        break;
                    case  'sofa':
                        $f[] = array('name' => $val, 'val' => '沙发');
                        break;
                    case  'telev':
                        $f[] = array('name' => $val, 'val' => '电视');
                        break;
                    case  'icebox':
                        $f[] = array('name' => $val, 'val' => '冰箱');
                        break;
                    case  'balcony':
                        $f[] = array('name' => $val, 'val' => '阳台');
                        break;
                    case  'air-condition':
                        $f[] = array('name' => $val, 'val' => '空调');
                        break;
                    case  'broadband':
                        $f[] = array('name' => $val, 'val' => '宽带');
                        break;
                    case  'fuel-gas':
                        $f[] = array('name' => $val, 'val' => '可做饭');
                        break;
                    case  'washer':
                        $f[] = array('name' => $val, 'val' => '洗衣机');
                        break;
                    case  'water-heater':
                        $f[] = array('name' => $val, 'val' => '热水器');
                        break;
                    case  'toilet':
                        $f[] = array('name' => $val, 'val' => '独立卫生间');
                        break;
                    case  'bed':
                        $f[] = array('name' => $val, 'val' => '床');
                        break;
                    case
                    'central-heater':
                        $f[] = array('name' => $val, 'val' => '暖气');
                        break;
                }
            }

        } else {
            $f = array();
        }


        $cityList = $this->getCitys(0);
        $this->assign('cityList', $cityList);
        $this->assign('detailList', $detailList);
        $this->assign('picList', $picList);
        $this->assign('area', $c);
        $this->assign('fur1', $fur1);//房屋配置
        $this->assign('fur2', $f);//房源详情
        $this->assign('user', $this->getUserName());
        $this->assign('currentCity',$curentCity['city_name']);
        $this->display();
    }

    public function _after_detail()
    {

    }

//免费发布
    public function publish()
    {
        if (empty($_POST)) {
            $this->assign('currentCity', cookie('cityName'));

            //加载区域
            $cityModel = M('city');
            //顶部切换城市
            $cityList = $this->getCitys(0);

            $cityList1 = $this->getCitysWithoutOrder(cookie('cityId'));
//      押金方式
            $payModel = M('pay');
            $payList = $payModel->select();
            //朝向
            $direcModel = M('direction');
            $direcList = $direcModel->select();
            //装修情况
            $decoModel = M('decoration');
            $decoList = $decoModel->select();
            //房子类型
            $houseModel = M('housetype');
            $houseList = $houseModel->select();

            //主次卧
            $roomModel = M('roomstyle');
            $roomList = $roomModel->select();

            $this->assign('cityList', $cityList);
            $this->assign('cityList1', $cityList1);
            $this->assign('payList', $payList);
            $this->assign('direcList', $direcList);
            $this->assign('decoList', $decoList);
            $this->assign('houseList', $houseList);
            $this->assign('roomList', $roomList);
            $this->assign('user', $this->getUserName());
            $this->display();
        } else {
            //提交表单

            $con = array();
            $con['rent_style'] = I('rent_style', 'dj');
            $con['identity'] = I('identity', '个人');
            $con['street'] = I('street', '暂无信息');
            $con['province'] = cookie('cityId');
            $con['city'] = I('city', '');
            $con['country'] = I('country', '');
            $con['address'] = I('address');
            $con['shi'] = I('shi', 1);
            $con['ting'] = I('ting', 1);
            $con['wei'] = I('wei', 1);
            $con['square'] = I('square', 0);
            $con['floor'] = I('floor', 1);
            $con['total_floor'] = I('total_floor', 1);
            $con['direction'] = I('direction');
            $con['decoration'] = I('decoration');
            $con['house_type'] = I('house_type');
            $con['room_style'] = I('room_style');
            $furniture = I('furniture', '');
            if (count($furniture) > 0) {
                $con['furniture'] = implode('-', $furniture);
            } else {
                $con['furniture'] = '';
            }

            $con['rent_money'] = I('rent_money', 0);
            $con['pay_style'] = I('pay_style');
            $con['title'] = I('title', '');
            $con['discribe'] = $_POST['discribe'];
            $con['contact'] = I('contact', '');
            $con['gender'] = I('gender');
            $con['phone'] = I('phone', '');
            $con['create_time'] = date('Y-m-d H:i:s', time());
            $user = session('user');
            $con['user_id'] = $this->currentUser['id'];
            $con['unique_id'] = md5(uniqid('fyy', true));


            $recourceModel = M('house_resource');
//          var_dump($con);die;


            $result = $recourceModel->add($con);
            if ($result) {
                $insertId = $recourceModel->getLastInsID();
                if ($_FILES['files']['name'][0] != '') {
                    $this->uploadPic($_FILES['files'], $insertId);
                }

            }
//            $this->redirect('/home/user/index');
            echo '<script>alert("发布成功！");window.location.href="/home/user/index"</script>';


        }

    }


    private function uploadPic($files, $insertId)

    {
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 3145728;// 设置附件上传大小
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath = './Uploads/';
        $upload->savePath = 'ori/'; // 原图存放路径
        $upload->saveName = array('uniqid', '');

        $thumbPath = 'thumb/';//缩略图存放目录
        // 上传文件
        $info = $upload->upload(array($files));
        if (!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }

        if ($info) {


            foreach ($info as $pic) {

                $image = new Image();
                //echo $upload->rootPath.$pic['savepath'].$pic['savename'];
                $image->open($upload->rootPath . $pic['savepath'] . $pic['savename']);
                $image->thumb(500, 500, 1)->water('./Public/img/logo/water.png',9,85)->save($upload->rootPath . $thumbPath . 'large_500_500_' . $pic[savename]);
                $data['resource_id'] = $insertId;
                $data['pic_ori'] = $upload->rootPath . $pic['savepath'] . $pic['savename'];
                $data['pic_large'] = $upload->rootPath . $thumbPath . 'large_500_500_' . $pic[savename];
                $data['create_time'] = date('Y-m-d H:i:s', time());
                $pictureModel = M('picture');

                $pictureModel->add($data);

//var_dump($data);
            }

        }
    }

//根据父节点id获取下属地区
    private function getCitys($areaId)
    {
        $cityModel = M('city');
        $cityName = $cityModel->field('id,city_name')->where(array('pid' => $areaId, 'is_del' => 0))->select();
        // echo print_r($cityName);die;

        return $this->orderByAbc($cityName);


    }

    public function getCitysWithoutOrder($areaId)
    {
        $cityModel = M('city');
        $cityName = $cityModel->field('id,city_name')->where(array('pid' => $areaId))->select();
        return $cityName;
    }

    public function getCitysWithoutOrderJson($areaId)
    {
        $cityModel = M('city');
        $cityName = $cityModel->field('id,city_name')->where(array('pid' => $areaId))->select();
        echo json_encode($cityName);
    }


//数据进行字母排序
    private function orderByAbc($data = array())
    {
        $result = array();
        //var_dump($data);
        foreach ($data as $name) {

            $char = $this->getFirstChar($name['city_name']);


            $nameArr['id'] = $name['id'];
            $nameArr['city_name'] = $name['city_name'];

            $result[$char][] = $nameArr;

        }
        ksort($result);

        return $result;

    }

    public function test()
    {


//echo get_client_ip();

//        echo phpinfo();
    }


//获取字符串首字母
    private function getFirstChar($s = '')
    {

        $s = iconv('UTF-8', 'gb2312', $s);       //将UTF-8转换成GB2312编码
        if (ord($s) > 128) {                      //汉字开头，汉字没有以U、V开头的
            $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;//echo $s.'|'.$asc.'</br>';
//            echo $asc.'-----'.$s;
//           echo iconv('gb2312', 'UTF-8', $asc.'-----'.$s);


            if ($asc >= -20319 and $asc <= -20284) return "A";
            if ($asc >= -20283 and $asc <= -19776) return "B";
            if (($asc >= -19775 and $asc <= -19219) || $asc == -10536) return "C";
            if ($asc >= -19218 and $asc <= -18711) return "D";
            if ($asc >= -18710 and $asc <= -18527) return "E";
            if ($asc >= -18526 and $asc <= -18240) return "F";
            if ($asc >= -18239 and $asc <= -17923) return "G";
            if (($asc >= -17759 and $asc <= -17248) || $asc == -17922) return "H";
            if ($asc >= -17922 and $asc <= -17418) return "I";
            if ($asc >= -17417 and $asc <= -16475) return "J";
            if ($asc >= -16474 and $asc <= -16213) return "K";
            if ($asc >= -16212 and $asc <= -15641) return "L";
            if ($asc >= -15640 and $asc <= -15166) return "M";
            if ($asc >= -15165 and $asc <= -14923) return "N";
            if ($asc >= -14922 and $asc <= -14915) return "O";
            if ($asc >= -14914 and $asc <= -14631) return "P";
            if ($asc >= -14630 and $asc <= -14150) return "Q";
            if ($asc >= -14149 and $asc <= -14091) return "R";
            if ($asc >= -14090 and $asc <= -13319) return "S";
            if ($asc >= -13318 and $asc <= -12839) return "T";
            if ($asc >= -12838 and $asc <= -12557) return "W";
            if ($asc >= -12556 and $asc <= -11848) return "X";
            if ($asc >= -11847 and $asc <= -11056) return "Y";
            if ($asc >= -11055 and $asc <= -10247) return "Z";

            //这几个比较特殊 暂时单独处理一下吧
            if ($asc == -9743) return 'B';
            if ($asc == -9767) return 'D';
            if ($asc == -6928) return 'L';
            if ($asc == -7182) return 'L';
            if ($asc == -6745) return 'P';
            if ($asc == -9297) return 'P';
            if ($asc == -7703) return 'Q';
            if ($asc == -9262) return 'F';
            if ($asc == -10536) return 'C';

        } else if (ord($s) >= 48 and ord($s) <= 57) { //数字开头
            switch (iconv_substr($s, 0, 1, 'utf-8')) {
                case 1:
                    return "Y";
                case 2:
                    return "E";
                case 3:
                    return "S";
                case 4:
                    return "S";
                case 5:
                    return "W";
                case 6:
                    return "L";
                case 7:
                    return "Q";
                case 8:
                    return "B";
                case 9:
                    return "J";
                case 0:
                    return "L";
            }
        } else if (ord($s) >= 65 and ord($s) <= 90) { //大写英文开头
            return substr($s, 0, 1);
        } else if (ord($s) >= 97 and ord($s) <= 122) { //小写英文开头
            return strtoupper(substr($s, 0, 1));
        } else {
            return iconv_substr($s, 0, 1, 'utf-8');
            //中英混合的词语，不适合上面的各种情况，因此直接提取首个字符即可
        }
    }


}