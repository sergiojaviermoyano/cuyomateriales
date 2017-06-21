<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Application specific global variables
class Globals
{
    private static $title;
    private static $title2;

    private static function initialize()
    {
        if (self::$title)
            return;
        
        $cf = get_instance();
        $cf->load->model('Configurations');
        $data = $cf->Configurations->get_();

        self::$title = $data['conf']['title1'];
        self::$title2 = $data['conf']['title2'];
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

    public static function getTitle2()
    {
        self::initialize();
        return self::$title2;
    }
}