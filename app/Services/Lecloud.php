<?php

/**
 * Created by PhpStorm.
 * User: Volio
 * Date: 2016/3/18
 * Time: 21:07
 */

namespace App\Services;

class Lecloud
{
    protected $secretkey;
    protected $userId;
    protected $uu;
    protected $url = 'http://api.open.letvcloud.com/live/execute';

    public function __construct($sceretkey,$userId,$uu)
    {
        $this->secretkey = $sceretkey;
        $this->userId = $userId;
        $this->uu = $uu;
    }

    protected function request($url,$data=null){
        $ch = curl_init();
        $header[] = 'Content-Type: application/x-www-form-urlencoded;charset=utf-8';
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $header));
        if ($data) {
            curl_setopt_array($ch, array(
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $data));
        }
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    //创建活动
    public function  creatActivity($config){
        $parameter = array(
            'method' => 'letv.cloudlive.activity.create',
            'ver' => '3.0',
            'userid' => $this->userId,
            'timestamp' => $this->getTimeStamp(),
            'startTime' => date('YmdHis',time()),
            'endTime' => date('YmdHis',time()+43200),
            'liveNum' => 1,
            'codeRateTypes' => $config['codeRateTypes'],
            'needFullView' => 0,
            'activityCategory' => '005',
            'playMode' => 0
        );

        $parameter['activityName'] = $config['livename'] ? $config['livename'] : false;
        $parameter['needRecord'] = $config['record'];
        $parameter['sign'] = $this->getSign($parameter);

        $data = http_build_query($parameter);
        $result = $this->request($this->url,$data);
        return $result;
    }

    //停止活动
    public function stopActivity($activityId){
        $parameter = array(
            'method' => 'letv.cloudlive.activity.stop',
            'ver' => '3.0',
            'userid' => $this->userId,
            'timestamp' => $this->getTimeStamp(),
            'activityId' => $activityId
        );

        $parameter['sign'] = $this->getSign($parameter);

        $data = http_build_query($parameter);
        $this->request($this->url,$data);
        return true;
    }

    //查询活动
    public function queryActivity($activityId){
        $parameter = array(
            'method' => 'letv.cloudlive.activity.search',
            'ver' => '3.0',
            'userid' => $this->userId,
            'timestamp' => $this->getTimeStamp(),
            'activityId' => $activityId
        );
        if($activityId){
            $parameter['sign'] = $this->getSign($parameter);
            $data = http_build_query($parameter);
            return $this->request($this->url.$data);
        }else
            return false;
    }

    //获取推流地址
    public function getPushUrl($activityId){
        $parameter = array(
            'method' => 'letv.cloudlive.activity.getPushUrl',
            'ver' => '3.0',
            'userid' => $this->userId,
            'timestamp' => $this->getTimeStamp(),
            'activityId' => $activityId
        );

        $parameter['sign'] = $this->getSign($parameter);
        $data = http_build_query($parameter);
        return $this->request($this->url.'?'.$data);
    }

    //获取视频录制信息
    public function getPlayInfo($activityId){
        $parameter = array(
            'method' => 'letv.cloudlive.activity.getPlayInfo',
            'ver' => '3.0',
            'userid' => $this->userId,
            'timestamp' => $this->getTimeStamp(),
            'activityId' => $activityId
        );

        $parameter['sign'] = $this->getSign($parameter);
        $data = http_build_query($parameter);
        return $this->request($this->url.'?'.$data);
    }

    //获取时间戳
    private function getTimeStamp(){
        $time = getdate();
        return $time['0'].'000';
    }

    //传入数组获取sign值
    private function getSign($array){
        ksort($array);
        $signstr = '';
        foreach ($array as $key => $value) {
            $signstr .= $key;
            $signstr .= $value;
        }
        $signstr .= $this->secretkey;
        return md5($signstr);
    }

    public function getUU(){
        return $this->uu;
    }
}