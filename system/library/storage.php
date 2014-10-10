<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 11.07.13
 * Time: 13:32
 * To change this template use File | Settings | File Templates.
 */

class Storage {

      static private $data = array();
      static private $instance = false;

      private function __construct()
      {

      }

      public static function getInstance()
{
      if(self::$instance instanceof Storage)
      {
           return self::$instance;
      }
      else{

          self::$instance = new Storage();
          return self::$instance;
      }

}




      public static function getStorage()
      {
            return self::$data;
      }

      public static function getSingleItem($key)
      {
           if(isset(self::$data[$key]))
           {
               return self::$data[$key];
           }
           else
           {
               return false;
           }

      }

      public static function addItem($key,$data)
      {
          if(is_array($data))
          {
              if(!isset(self::$data[$key]) OR !is_array(self::$data[$key]))
              {
                  self::$data[$key] = array();
              }
              self::$data[$key] = array_merge(self::$data[$key],$data);
          }
          else
          {
              self::$data[$key] = $data;
          }
      }

}