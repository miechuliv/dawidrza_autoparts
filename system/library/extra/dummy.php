<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 11.07.13
 * Time: 18:52
 * To change this template use File | Settings | File Templates.
 */

class Dummy {

      static public function getCustomerIp()
      {
          if (!empty($_SERWER['HTTP_X_FORWARDED_FOR'])) {
              return  $_SERWER['HTTP_X_FORWARDED_FOR'];
          } elseif(!empty($_SERWER['HTTP_CLIENT_IP'])) {
              return $_SERWER['HTTP_CLIENT_IP'];
          } else {
              return false;
          }

      }


      static public function getCityByIp()
      {
          //Load the class
          $ipLite = new ip2locationlite;
          $ipLite->setKey('aaea8c2c5301a444b66d9c22216abd57e313b25f4260ebdacd74a30e3ac3cca6');

          //Get errors and locations
          $ip = self::getCustomerIp();
          if($ip)
          {
              $locations = $ipLite->getCity($ip);
              $errors = $ipLite->getError();

              return $locations;
          }

          return false;
      }

}