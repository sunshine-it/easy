<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Status;
use Faker\Factory;

class FreeApi
{
    private $apiUrl = 'http://api.guaqb.cn/v1/onesaid/';

    /**
     * 获取结果
     * @return array
     */
    public function getResult(){
        return $this->freeApiCurl($this->apiUrl);
    }
    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    public function freeApiCurl($url,$params=false,$ispost=0){
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'free-api' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            return false;
        }
        curl_close( $ch );
        return $response;
    }
}

$api = new FreeApi();
// $str = $api->getResult(); // string

// 初始化 Faker\Factory 使用中文
$faker = Factory::create('zh_CN');

// 发布微博工厂模型类
$factory->define(Status::class, function ($faker) use ($api) {
    $date_time = $faker->date . ' ' . $faker->time;
    return [
        // 'content'    => $faker->text(),
        'content'    => $faker->content = $api->getResult(), // 使用第三方接口
        'created_at' => $date_time,
        'updated_at' => $date_time,
    ];
});



