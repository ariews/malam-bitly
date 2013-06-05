<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

class Malam_Bitly
{
    const API_URL = 'http://api.bit.ly/v3/';

    protected $config;

    protected $respond;

    public function __construct()
    {
        $this->config = Kohana::$config->load('bitly');
    }

    public static function instance()
    {
        static $instance;
        empty($instance) && $instance = new Bitly();

        return $instance;
    }

    public function shorten($longUrl)
    {
        $params = array(
            'longUrl'   => $longUrl,
            'login'     => $this->config['login'],
            'apiKey'    => $this->config['apiKey'],
            'domain'    => $this->config['domain'],
            'format'    => 'json',
        );

        $respond = Request::factory(Bitly::API_URL.'shorten'.URL::query($params))
                    ->method(Request::GET)
                    ->execute();

        $this->respond = json_decode($respond->body());

        if ($this->is_error())
        {
            throw new Kohana_Exception('Error [:code]: :message', array(
                ':code'     => $this->error_code(),
                ':message'  => $this->error_msg()
            ));
        }

        return $this;
    }

    public function is_error()
    {
        return $this->respond->status_code != 200;
    }

    public function error_code()
    {
        return $this->respond->status_code;
    }

    public function error_msg()
    {
        return $this->respond->status_txt;
    }

    public function short()
    {
        return $this->respond->data->url;
    }
}