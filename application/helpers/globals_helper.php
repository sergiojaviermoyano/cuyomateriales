<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Application specific global variables
class Globals
{
    private static $title; // = "mi titulo";

    private static function initialize()
    {
        if (self::$title)
            return;
        
        $cf = get_instance();
        $cf->load->model('Configuration');
        $data = $cf->Configuration->get_();

        var_dump($data);
        self::$title = $data['conf']['title'];
    }

    /*
    public static function setAuthenticatedMemeberId($memberId)
    {
        self::initialize();
        self::$authenticatedMemberId = $memberId;
    }
    */

    public static function getTitle()
    {
        self::initialize();
        return self::$title;
    }
}