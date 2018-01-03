<?php
/**
 * Created by PhpStorm.
 * User: fukai3
 * Date: 2017-2-27
 * Time: 17:32
 */

namespace Home\Controller;


use Think\Controller;

class TestController extends Controller
{

    private function addCityOne()
    {
        $cityId = 379;
        $cityStr = '沙坡头 中宁 海原';
        $cityList = explode(' ', trim($cityStr));
        $model = M();

        foreach ($cityList as $k => $v) {
            $model->execute("insert into f_city values(null,'$v',$cityId,0)");
        }
        echo '<pre>';
        print_r($cityList);

//



    }

    private function addCityTwo()
    {
        $cityId = 12734;
        $cityStr = 'B滨江广场 C长江广场 长江北路 L栗雨湖 S神龙城 T天虹百货 泰山广场 W武广高铁 Y银天国际 Z珠江株百超市 其他';
        $cityStr = preg_replace('|[a-zA-Z/]+|', '', $cityStr);

        $cityList = explode(' ', trim($cityStr));
        $model = M();

//        die;
        foreach ($cityList as $k => $v) {
            $model->execute("insert into f_city values(null,'$v',$cityId,0)");

        } echo '<pre>';
        print_r($cityList);
    }
    public function getInfo(){
        vendor('simplehtmldom.simple_html_dom');
        $html=new \simple_html_dom();
        $html->load_file('http://www.baidu.com');
        $ss=$html->find('#lg > img');
        var_dump($ss[0]);
    }
}