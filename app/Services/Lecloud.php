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
    protected $live_api_url = 'http://api.open.letvcloud.com/live/execute';
    protected $video_api_url = 'http://api.letvcloud.com/open.php';

    public function __construct($sceretkey, $userId, $uu)
    {
        $this->secretkey = $sceretkey;
        $this->userId = $userId;
        $this->uu = $uu;
    }

    protected function request($url, $data = null)
    {
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

    /**
     * 创建活动
     * version = 3.1
     * @param $config
     * @return mixed
     */
    public function creatActivity($config)
    {
        $parameter = [
            'method' => 'lecloud.cloudlive.activity.create',
            'ver' => '3.1',
            'userid' => $this->userId,
            'timestamp' => $this->getTimeStamp(),
            'startTime' => date('YmdHis', time()),
            'endTime' => date('YmdHis', time() + 43200),
            'coverImgUrl' => 'https://dn-vosina.qbox.me/eb7ee12cea92fb896954780fd26bf527.png',
            'liveNum' => 1,
            'codeRateTypes' => $config['codeRateTypes'],
            'needFullView' => 0,
            'activityCategory' => '005',
            'playMode' => 0
        ];

        $parameter['activityName'] = $config['livename'] ? $config['livename'] : false;
        $parameter['needRecord'] = $config['record'];
        $parameter['sign'] = $this->getSign($parameter);

        $data = http_build_query($parameter);
        $result = $this->request($this->live_api_url, $data);
        return $result;
    }

    /**
     * 停止活动
     * version = 3.1
     * @param $activityId
     * @return bool
     */
    public function stopActivity($activityId)
    {
        $parameter = [
            'method' => 'lecloud.cloudlive.activity.stop',
            'ver' => '3.1',
            'userid' => $this->userId,
            'timestamp' => $this->getTimeStamp(),
            'activityId' => $activityId
        ];

        $parameter['sign'] = $this->getSign($parameter);

        $data = http_build_query($parameter);
        $this->request($this->live_api_url, $data);
        return true;
    }

    /**
     * 活动信息查询
     * version = 3.1
     * @param $activityId
     * @return bool|mixed
     */
    public function queryActivity($activityId)
    {
        $parameter = [
            'method' => 'lecloud.cloudlive.vrs.activity.vrsinfo.search',
            'ver' => '3.1',
            'userid' => $this->userId,
            'timestamp' => $this->getTimeStamp(),
            'activityId' => $activityId
        ];
        if ($activityId) {
            $parameter['sign'] = $this->getSign($parameter);
            $data = http_build_query($parameter);
            return $this->request($this->live_api_url . $data);
        } else
            return false;
    }

    /**
     * 获取推流地址
     * version = 3.1
     * @param $activityId
     * @return mixed
     */
    public function getPushUrl($activityId)
    {
        $parameter = [
            'method' => 'lecloud.cloudlive.activity.getPushUrl',
            'ver' => '3.1',
            'userid' => $this->userId,
            'timestamp' => $this->getTimeStamp(),
            'activityId' => $activityId
        ];

        $parameter['sign'] = $this->getSign($parameter);
        $data = http_build_query($parameter);
        return $this->request($this->live_api_url . '?' . $data);
    }

    /**
     * 直播视频录制查询接口
     * version = 3.0
     * @param $activityId
     * @return mixed
     */
    public function getPlayInfo($activityId)
    {
        $parameter = [
            'method' => 'letv.cloudlive.activity.getPlayInfo',
            'ver' => '3.0',
            'userid' => $this->userId,
            'timestamp' => $this->getTimeStamp(),
            'activityId' => $activityId
        ];

        $parameter['sign'] = $this->getSign($parameter);
        $data = http_build_query($parameter);
        return $this->request($this->live_api_url . '?' . $data);
    }

    public function getVideoImage($video_id)
    {
        $parameter = [
            'user_unique' => $this->uu,
            'timestamp' => $this->getTimeStamp(),
            'api' => 'image.get',
            'format' => 'json',
            'ver' => 2.0,
            'video_id' => $video_id,
            'size' => '640_360'
        ];

        $parameter['sign'] = $this->getSign($parameter);
        $data = http_build_query($parameter);
        return $this->request($this->video_api_url,$data);
    }

    /**
     * 获取时间戳
     * @return string
     */
    protected function getTimeStamp()
    {
        $time = getdate();
        return $time['0'] . '000';
    }

    /**
     * 获取sign值
     * @param $array
     * @return string
     */
    protected function getSign($array)
    {
        ksort($array);
        $signstr = '';
        foreach ($array as $key => $value) {
            $signstr .= $key;
            $signstr .= $value;
        }
        $signstr .= $this->secretkey;
        return md5($signstr);
    }

    /**
     * 获取UUID
     * @return mixed
     */
    public function getUU()
    {
        return $this->uu;
    }
}