<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 16.07.13
 * Time: 15:50
 * To change this template use File | Settings | File Templates.
 */

class Debay {

     // @todo aktualizacja tokenów
     // test token
    // private static $_token = "AgAAAA**AQAAAA**aAAAAA**A1HlUQ**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFk4GhCpWBpg2dj6x9nY+seQ**nUcCAA**AAMAAA**Pd7nwyCk/qVFAozflbzwpGo1OsYqb8m8EUFsxA0eaM5jCSnlSKkzlaMl06Eb7NdlvCmFCCfy2cQlu2bMAPTAA9n5Oksp98a1yF7uA4VUUuSYpi+ukima4a8GQMOdGzIn/UogtCZ4WMUveVqzKiSLj6qygjY96m2icxwmp70txF3xvec0u8SUuALox4bDR9Qcu7T1u+Dv5RG55pjsTt1BY+DJl94Tgfee+yb3KLJYyeCaRTcI71aQQbUmquyDjEWbhKuzsh9CFU1XT+mEoJMJRXHcv3E/7E7qPDt89q+7McincSnHsG4XNPTnt1a8rmDhTHU2uKXbA4+HoIVupMnOz+po+kO2x7Bia63YIexGbHuFMbhmBgL6Dq5ahMoTPNPaPGz3AKG4hS0NcLAqcD+xbhr51odu2E+BxjRE8HIqI/PKPYCcqbXszbHUAvqowrar5cum30FQKWTFjTnjCapLcnjE+u8iBBSOJA4U2NWQIW6tVAezlySScl6xjADaUF2w6WqskDmnKdvdARORlNrkY1fwDsHD5M9RPDkM6HXVJXgt8EoIjwar2OQKBRswVFzJv93T+eYAYGI3feFBwXd6cVZZ4LIVlsXuAfPWvI59gKjZ5kJ6tVqFE71/6cSxInJwNlSarTUh07CJc3kdLQS2Vfiq4D9eQAvaTD/mstRLUUoKqh+7mQeCVz9O3gOvz7cmUBKGJJjo64/Qbe6Ebf4pQYyRHHNfqKATZK3KAr87my9sTWxW0rWX/fBw8iFRa2IK";
    // production token
    private static $_token = "AgAAAA**AQAAAA**aAAAAA**fkv2UQ**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AFlIOlCpKLpQudj6x9nY+seQ**7OoBAA**AAMAAA**p8XgUDNyshlMzwxxo7oCg/rtaPQ5hpWqpOMgCiuvMDzEhO+epKbAa03w9xerFeoqirPyE3KMilto/men4Gu/arzcsE59QBJq20JX1PwrUsECJpNdVrOi9v7PRqJFlWEdGZrpp5H8xcPb0RQ0Z5sUweU7X6+8E4e0rI5tr5sttxbEuG1TsAgPzfY/AWAWrzGVKX4Da16u3e1LbGluvouMH37vsPk4XkP1JgFmBIkJIBfRtDKpO7oIjte5HTV/Rj0GSBa03KFxx1hbg2UHqyAfQmTOGZHMTIH3MuSzhqHeboPZ+5RQ3axc4zC1dIejd5brgM5IGPRJr/aN+lY0KDt5SbKneulTnBkfqqQfi70oT7DltBv6vRA8LlCHiO+EgZAmUmYPQ3xyAF/wmNg1m7AcuitDh0prSfTqrbTNme0PLiqE+OoXg+06X8cBmqDJgUrk/cRLqJe8sgAl6Y3/uZEbLSt6FWNKxhJrn4hdnKSgvRekxY7n/T3PWbgtUWJO8jxyniPk7CDeNebFSuRPuD9vpZTfNTPzM+bgzpHOx6krlhAvbdA2Q3qF8WjHEQd4n4VHUwBq+E6JAvMbWiJwh5hd+j9HbvyVSAa9A1kPR7nYTEDZYdZXWKa2YuLjyYjBR/IQtdf7PIQIOHPbkV8jsRWYsbWiJcloTK6keHbaeQrD1WrlbqWHVKiJ4GFJZlQ7lNXRluVcTAnhuMrQSt2l18f7nagBlwVSFzRaLniLNlwokS1/So5seKVTzD1zZtrL9nh5";
     // testowy
  //   private static $_appId = 'dawidrza-1bcd-4bb7-9792-7f7dfb979810';
     // produkcyjny
     private static $_appId = 'somecomp-4d5c-4469-905d-dff647372cf4';

     // nowy wsdl nie działa
     //private static $_wsdl_url = 'http://developer.ebay.com/webservices/latest/eBaySvc.wsdl'; // downloaded from http://developer.ebay.com/webservices/latest/eBaySvc.wsdl
     // uzylem starszego
     private static $_wsdl_url = 'http://developer.ebay.com/webservices/805/eBaySvc.wsdl';

     private static $error_codes = array(
         196 => "Aukcja trwa albo nie ma jej już w archiwum",
         515 => "Wprowadzono błędna ilość sztuk",
         21919067 => 'Identyczny przedmiot został już wystawiony w innej auckji',
     );


     public static function sendRequest($method,$params){

         // upewnia się ze jest wlasciwa wersja
         $param['Version'] = 805;

         $param['ErrorLanguage'] = 'en_GB';


         // site codes 0 -US 77 - DE  212 - Polska
         // test
         $client = new SOAPClient(self::$_wsdl_url, array('trace' => 1, 'exceptions' => true, 'location' => 'https://api.sandbox.ebay.com/wsapi?callname=' . $method . '&appid=' . self::$_appId . '&siteid=0&version=821&routing=new'));
//
         // production
         $client = new SOAPClient(self::$_wsdl_url, array('trace' => 1, 'exceptions' => true, 'location' => 'https://api.ebay.com/wsapi?callname=' . $method . '&appid=' . self::$_appId . '&siteid=77&version=821&routing=new'));
         $requesterCredentials = new stdClass();
         $requesterCredentials->eBayAuthToken = self::$_token;

         $header = new SoapHeader('urn:ebay:apis:eBLBaseComponents', 'RequesterCredentials', $requesterCredentials);

// the API call parameters

         try{
             $responseObj = $client->__soapCall($method, array($params), null, $header);  // make the API call



             if($responseObj->Ack=='Failure')
             {
                  throw new Exception("Bład metody: ".$method);
             }
         }
         catch (Exception $e)
         {
            $error =  'Exception caught. Here are the xml request & response:<br><br>';
             $error.= '$client->__getLastRequest():<br><pre><xmp>' . $client->__getLastRequest() . '</xmp></pre>';
             $error.= '$client->__getLastResponse():<br><pre><xmp>' . $client->__getLastResponse() . '</xmp></pre><br>';

             $error.= '<p>Exception trying to call ' . $method . '</p>';
             $error.= '$e->getMessage()';
             $error.= '<pre>' . $e->getMessage() . '</pre>';


             $resp = $client->__getLastResponse();
             $z = new DOMDocument();
             $z->loadXML($resp);
             $codes = $z->getElementsByTagName('ErrorCode');

             foreach($codes as $code)
             {
                 $error = $code->nodeValue;

                 if(isset(self::$error_codes[$error]))
                 {
                     throw new Exception(self::$error_codes[$error]);
                 }
                 else
                 {
                     throw new Exception($error);
                 }

             }






         }


         return $responseObj;
     }
}
