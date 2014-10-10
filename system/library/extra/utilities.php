<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 12.07.13
 * Time: 10:39
 * To change this template use File | Settings | File Templates.
 */

class Utilities {


    private static $instance=false;

    private static $list_of_controllers=array();

    private function __constructor(){

    }

    static public function getInstance()
    {
        if(self::$instance instanceof Utilities)
        {
            return self::$instance;
        }
        else{
            self::$instance = new Utilities();
            return self::$instance;
        }
    }

    static public function addController($controller)
    {
          self::$list_of_controllers[]=$controller;
    }


    static public function getControllerList()
    {
       return  self::$list_of_controllers;
    }

    static public function isController($string)
    {
         if(in_array($string,self::$list_of_controllers))
         {
             return true;
         }
         else
         {
             return false;
         }
    }



}