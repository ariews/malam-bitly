<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

class Malam_Bitly
{
    /**
     * Bitly API url
     */
    const API_URL               = 'http://api.bit.ly/v3/';

    /**
     * Config
     *
     * @var Config_Group
     */
    protected $config;

    /**
     * Respond from Bitly
     *
     * @var string
     */
    protected $respond;

    /**
     * Instance
     *
     * @var array
     */
    protected $instance         = array();

    /**
     * Create instance
     *
     * @param string $group
     */
    public function __construct($group = 'default')
    {
        $this->config = Kohana::$config->load("bitly.{$group}");
    }

    /**
     * Get instance
     *
     * @param string $group
     * @return Bitly
     */
    public static function instance($group = 'default')
    {
        ! isset($this->instance[$group]) && $this->instance[$group] = new Bitly($group);

        return $this->instance[$group];
    }

    /**
     * Shorten URL
     *
     * @param string $longUrl
     * @return Bitly
     * @throws Kohana_Exception
     */
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

    /**
     * Error checker
     *
     * @return boolean
     */
    public function is_error()
    {
        return $this->respond->status_code != 200;
    }

    /**
     * Error code
     *
     * @return integer
     */
    public function error_code()
    {
        return $this->respond->status_code;
    }

    /**
     * Error message
     *
     * @return string
     */
    public function error_msg()
    {
        return $this->respond->status_txt;
    }

    /**
     * URL
     *
     * @return string
     */
    public function short()
    {
        return $this->respond->data->url;
    }
}