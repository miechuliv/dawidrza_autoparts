<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 12.07.13
 * Time: 16:07
 * To change this template use File | Settings | File Templates.
 */

class ProjectSpecyfic {

      static public $data = array();

      static public function addData($key,$data)
      {
            self::$data[$key] = $data;
      }

      static public function getData($key)
      {
           if(isset(self::$data[$key]))
           {
               return self::$data[$key];
           }
      }
}