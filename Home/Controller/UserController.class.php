<?php
/**
 * Created by PhpStorm.
 * User: fukai3
 * Date: 2017-2-13
 * Time: 13:51
 */

namespace Home\Controller;


class UserController extends BaseController
{
    public function index()
    {

        if (!empty($this->currentUser)) {
            $userName = $this->currentUser['user_name'];

            $user = substr($userName, 0, 3) . '**' . substr($userName, -3);

        } else {
            $user = '游客';
        }
        $this->assign('user', $user);

        $p = I('get.p', 1);
        $resourceModel = M('house_resource');
        $con['user_id'] = $this->currentUser['id'];
        $totalPage = $resourceModel->where($con)->count();
        $homeList = $resourceModel->field('f_house_resource.*,f_pay.style')->order('create_time desc')->where($con)->join('left join f_pay on f_house_resource.pay_style=f_pay.id')->page($p, C('PERPAGE'))->select();
//        echo $resourceModel->getLastSql();
//
//        var_dump($homeList);
//        $sql = $resourceModel->getLastSql();
//        var_dump($sql);

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


            $pagebar = $page->show();
        }
        $this->assign('user', $this->getUserName());

        $this->assign('homeList', $homeList);
        $this->assign('pageBar', $pagebar);
        $this->assign('cityList',$this->getCitys(0));

        $this->display();
    }

    //删除帖子
    public function delete()
    {
        $id = I('post.id');
        if (!empty($id)) {
            $resourceModel = M('house_resource');
            $result = $resourceModel->where(array('id' => $id))->delete();
            $picModel = M('picture');
            $picPath = $picModel->field('pic_ori,pic_large')->where(array('resource_id' => $id))->select();

            foreach ($picPath as $p) {
                unlink($p['pic_ori']);
                unlink($p['pic_large']);
            }
            $picModel->where(array('resource_id' => $id))->delete();


            if ($result) {
                $msg['state'] = 1;
                $msg['msg'] = '已删除';
                echo json_encode($msg);

            } else {
                $msg['state'] = 0;
                $msg['msg'] = '删除失败，请重试';
                echo json_encode($msg);
            }
        }
    }


    private function getCitys($areaId)
    {
        $cityModel = M('city');
        $cityName = $cityModel->field('id,city_name')->where(array('pid' => $areaId, 'is_del' => 0))->select();
        // echo print_r($cityName);die;

        return $this->orderByAbc($cityName);


    }
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