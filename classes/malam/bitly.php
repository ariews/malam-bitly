<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

class Malam_Bitly
{
    const API_URL = 'http://api.bit.ly/v3/';

    private $config;

    public function __construct()
    {
        $this->config = Kohana::config('bitly');
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

        $resp = Remote::get(Bitly::API_URL.'shorten'.URL::query($params));

        return $resp;
    }
}