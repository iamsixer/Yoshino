<?php
/**
 * Created by PhpStorm.
 * User: Volio
 * Date: 2016/3/19
 * Time: 17:25
 */
namespace App\Services;

class Leancloud
{
    protected $appId;
    protected $appKey;
    protected $url = 'https://api.leancloud.cn/1.1/classes/_Conversation';

    public function __construct($appId,$appKey)
    {
        $this->appId = $appId;
        $this->appKey = $appKey;
    }

    protected function request($url,$data=null){
        $ch = curl_init();
        $header[] = 'X-LC-Id: '.$this->appId;
        $header[] = 'X-LC-Key: '.$this->appKey;
        $header[] = 'Content-Type: application/json';
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_SSL_VERIFYPEER => false,
        ));
        if ($data) {
            curl_setopt_array($ch, array(
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $data));
        }
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function createRoom($name){
        $info = [
            'name' => $name,
            'tr' => true
        ];
        $data = json_encode($info,JSON_UNESCAPED_UNICODE);
        $result = $this->request($this->url,$data);
        return $result;
    }

    public function getAppId(){
        return $this->appId;
    }
}