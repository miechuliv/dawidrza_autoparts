<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 25.07.13
 * Time: 15:53
 * To change this template use File | Settings | File Templates.
 */

class OpenCartSms {

      private static $client;
      private static $smsapi;
      private static $log;

      public static function initialize($sms_logger = false,$debug = false)
      {

          if($debug)
          {
              self::$log = $sms_logger;
          }
          else
          {
              self::$log = false;
          }


          require_once DIR_SYSTEM . 'library/extra/smsapi/Autoload.php';

          $login ='m.malawski@dawidrza.com';
          $pass = 'Episode777';

          self::$client = new \SMSApi\Client($login);
          self::$client->setPasswordHash( md5($pass) );

          self::$smsapi = new \SMSApi\Api\SmsFactory();
          self::$smsapi->setClient(self::$client);

      }

      public static function sendSms($number,$message,$sender)
      {
          try {

              $sender = NULL;

              $actionSend = self::$smsapi->actionSend();

              $actionSend->setTo($number);
              $actionSend->setText($message);
              $actionSend->setSender($sender);
              $actionSend->setEncoding('utf-8');

              $response = $actionSend->execute();

              $result ='';

              foreach( $response->getList() as $status ) {
                 $result.=  $status->getNumber() . ' ' . $status->getPoints() . ' ' . $status->getStatus();


              }

              if(self::$log)
              {
                 self::$log->debug('wyslano sms, numer: '.$number.' tresc: '.$message.' rezultat:'.$result.' ' ,'sms');
              }

          }
          catch( \SMSApi\Exception\SmsapiException $e ) {
              $error =  'ERROR: ' . $e->getMessage();

              if(self::$log)
              {
              self::$log->error('wyslano sms, numer: '.$number.' tresc: '.$message.' blad:'.$error.' '.' nadawca '.$sender.' odbiorca: '.$number ,'sms');
              }
          }
      }
}