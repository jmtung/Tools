<?php

/**
 * Created by PhpStorm.
 * User: jm
 * Date: 17-2-26
 * Time: 下午7:49
 * Describe:php实现get和post的一个curl类
 */

/**
 * Class curl
 */
class Curl
{
    /**
     * @var 发送请求的url
     * @param $ch
     * @param array $data 发送请求附带的数据
     * @param int $jsonDecode
     * @param int $jsonDecodeMethod
     * @param $output 输出数据 可以是对象,数组,字符串
     */
    public $url;
    public $ch;
    public $data = array();
    public $jsonDecode;
    public $jsonDecodeMethod;
    public $output;

    /**
     * curl constructor.
     * @param $url 发送请求的url
     * @param array $data post或get请求的数据
     * @param int $jsonDecode 选择是否解析json数据 1为选择解析,0为不解析
     * @param int $jsonDecodeMethod 选择解析json数据返回值 0为对象,1为数组
     * @param string $type 请求类型
     */
    public function __construct($url, $data = array(), $jsonDecode = 1, $jsonDecodeMethod = 0, $type = "post")
    {
        $this->url = $url;
        $this->ch = curl_init();
        $this->data = $data;
        $this->jsonDecode = $jsonDecode;
        $this->jsonDecodeMethod = $jsonDecodeMethod;
        exit($type);
        if ($type == "post") {
            $this->post();
        } elseif ($type == "get") {
            $this->get();
        } else {
            $this->error("请输入正确参数");
        }
    }

    /**
     * 发送get请求
     */
    public function get()
    {
        $this->url .= "?";
        foreach ($this->data as $key => $val) {
            $this->url .= ($key . "=" . $val . "&");
        }
        $this->url = rtrim($this->url, "&");
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_HEADER, 0);
        $this->exec();
    }

    /**
     * 法搜post请求
     */
    public function post()
    {
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->data);
        $this->exec();
    }

    /**
     * 执行请求
     */
    public function exec()
    {
        $this->output = curl_exec($this->ch);
        //判断是否需要解析json数据
        if ($this->jsonDecode == 1) {
            $this->jsonDecode();
        }
    }

    /**
     * 解析json数据
     */
    public function jsonDecode()
    {
        $this->output = json_decode($this->output, $this->jsonDecodeMethod);
    }

    /**
     * @return mixed返回数据
     */
    public function getResult()
    {
        return $this->output;
    }

    /**
     * 错误函数
     * @param $info 输出的错误信息
     */
    public function error($info)
    {
        exit($info);
    }
}

$test = new Curl("localhost/api/test.php", array("username" => "jm"), 1, 0);
$result = $test->getResult();
var_dump($result->result);