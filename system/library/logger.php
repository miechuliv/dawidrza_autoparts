<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 10.07.13
 * Time: 16:18
 * To change this template use File | Settings | File Templates.
 */

class Logger {

    // Name of the file where the message logs will be appended.
    private $LOGFILENAME;

    // Define the separator for the fields. Default is comma (,).
    private $SEPARATOR;

    // headers
    private $HEADERS;

    // Default tag.
    const DEFAULT_TAG = '--';

      public $state = 'development';

      public $allowed_ip = array();

      public $current_ip = false;

    private $mail;

    private $config;


      public function __construct($config,$logfilename = './_MyLogPHP-1.2.log.csv', $separator = ',')
      {
          if (!empty($_SERWER['HTTP_X_FORWARDED_FOR'])) {
              $this->current_ip = $_SERWER['HTTP_X_FORWARDED_FOR'];
          } elseif(!empty($_SERWER['HTTP_CLIENT_IP'])) {
              $this->current_ip = $_SERWER['HTTP_CLIENT_IP'];
          } else {
              $this->current_ip = false;
          }

          $this->LOGFILENAME = $logfilename;
          $this->SEPARATOR = $separator;
          $this->HEADERS =
              'DATETIME' . $this->SEPARATOR .
                  'ERRORLEVEL' . $this->SEPARATOR .
                  'TAG' . $this->SEPARATOR .
                  'VALUE' . $this->SEPARATOR .
                  'LINE' . $this->SEPARATOR .
                  'FILE';

          $this->mail = new Mail();
          $this->config = $config;


      }

      public function echoLog($message)
      {
          if($this->current_ip AND in_array($this->current_ip,$this->allowed_ip))
          {
              echo $message;
          }
      }

      public function dumpLog($variable)
      {
          if($this->current_ip AND in_array($this->current_ip,$this->allowed_ip))
          {
              var_dump($variable);
          }
      }

      public function isAllowed()
      {
          if($this->current_ip AND in_array($this->current_ip,$this->allowed_ip))
          {
              return true;
          }
          else
          {
              return false;
          }
      }

      public function criticalMail($message,$subject,$sklep)
      {


          $this->mail->protocol = $this->config->get('config_mail_protocol');
          $this->mail->parameter = $this->config->get('config_mail_parameter');
          $this->mail->hostname = $this->config->get('config_smtp_host');
          $this->mail->username = $this->config->get('config_smtp_username');
          $this->mail->password = $this->config->get('config_smtp_password');
          $this->mail->port = $this->config->get('config_smtp_port');
          $this->mail->timeout = $this->config->get('config_smtp_timeout');
          $this->mail->setTo('miechuliv.reports@o2.pl');
          $this->mail->setFrom('miechuliv.reports@o2.pl');
          $this->mail->setSender($sklep);
          $this->mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));

          $this->mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
          $this->mail->send();
      }

      public function addAllowed($ip)
      {
           $this->allowed_ip[] = $ip;
      }

      public function cleanAllowedIp()
      {
          $this->allowed_ip = array();
      }

    // Private method that will write the text logs into the $LOGFILENAME.
    private function log($errorlevel = 'INFO', $value = '', $tag) {

        setlocale(LC_CTYPE, 'pl_PL');

        $datetime = date("Y-m-d H:i:s");
        if (!file_exists($this->LOGFILENAME)) {
            $headers = $this->HEADERS . "\n";
        }

        $fd = fopen($this->LOGFILENAME, "a");


        if (isset($headers)) {
            fwrite($fd, $headers);
        }

        $debugBacktrace = debug_backtrace();
        $line = $debugBacktrace[1]['line'];
        $file = $debugBacktrace[1]['file'];

        $entry = array($datetime,$errorlevel,$tag,iconv('UTF-8', 'Windows-1251', $value),$line,$file);

        fputcsv($fd, $entry, $this->SEPARATOR);

        fclose($fd);

    }


    // Function to write not technical INFOrmation messages that will be written into $LOGFILENAME.
    function info($value = '', $tag = self::DEFAULT_TAG) {

        self::log('INFO', $value, $tag);
    }


    // Function to write WARNING messages that will be written into $LOGFILENAME.
    // These messages are non-fatal errors, so, the script will work properly even
    // if WARNING errors appears, but this is a thing that you must ponderate about.
    function warning($value = '', $tag = self::DEFAULT_TAG) {

        self::log('WARNING', $value, $tag);

       // $this->criticalMail($value,$tag." WARNING",'gacka de');
    }


    // Function to write ERROR messages that will be written into $LOGFILENAME.
    // These messages are fatal errors. Your script will NOT work properly if an ERROR happens, right?
    function error($value = '', $tag = self::DEFAULT_TAG) {

        self::log('ERROR', $value, $tag);
    }

    // Function to write DEBUG messages that will be written into $LOGFILENAME.
    // DEBUG messages are highly technical issues, like an SQL query or result of it.
    function debug($value = '', $tag = self::DEFAULT_TAG) {

        self::log('DEBUG', $value, $tag);
    }

}