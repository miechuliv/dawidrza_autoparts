<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 11.07.13
 * Time: 18:52
 * To change this template use File | Settings | File Templates.
 */

class geoLocations {


      private static $instance=false;

     private function __constructor(){

}

      static public function getInstance()
      {
           if(self::$instance instanceof geoLocations)
           {
               return self::$instance;
           }
           else{
               self::$instance = new geoLocations();
               return self::$instance;
           }
      }

      static public function getCustomerIp()
      {



          if(!empty($_SERVER['REMOTE_ADDR'])){
              return $_SERVER['REMOTE_ADDR'];
          } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
              return  $_SERVER['HTTP_X_FORWARDED_FOR'];
          } elseif(!empty($_SERVER['HTTP_CLIENT_IP'])) {
              return $_SERVER['HTTP_CLIENT_IP'];
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
          try{

              $ip = self::getCustomerIp();
              if($ip)
              {

                  $locations = $ipLite->getCity($ip);
                  $errors = $ipLite->getError();

                  if(isset($locations['cityName']))
                  {

                      return $locations;
                  }
                  else
                  {
                      throw new Exception("Nie udało się wykryć miasta");
                  }


              }
              else
              {
                  throw new Exception("Nie można wykryć IP");
              }
          }catch(Exception $e)
          {
              echo $e->getMessage();
          }


          return false;
      }

      static function translate($string)
      {
           if(!$string OR $string == '')
           {
               throw new Exception('Brak hasła do przetłymaczenia');
           }
            $string = strtolower($string);

           if($string === 'gdansk')
           {
               return 'gdańsk';
           }

           return $string;

      }

}