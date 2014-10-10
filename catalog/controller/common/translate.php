<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 19.11.13
 * Time: 11:54
 * To change this template use File | Settings | File Templates.
 */

/*
 * automatyczne tłumaczenie tekstów z poziomu storony za pomocą Ajax
 * @depends /system/library/translator.php
 */
class ControllerCommonTranslate extends Controller{

     /*
      * sprawdzamy czy podana fraza istnieje w plikach z tłumaczeniami
      * lista kontrollerów zawęza pole wyszukiwanie
      *
      */
     public function check()
     {
         $fraza = trim($this->request->post['text']);
         $listaKontrollerow = $this->request->post['controllers'];   


         if(empty($language))
         {
              // @todo nie znaleziono jezyka
         }

         $result = $this->translator->findString($fraza,$this->config->get('config_language'),$listaKontrollerow);

         echo json_encode($result);


     }


     public function save()
     {
         $stara_fraza = trim($this->request->post['oldText']);
         $nowa_fraza = trim($this->request->post['newText']);
         $zapisac_do_pliku = isset($this->request->post['saveText'])?$this->request->post['saveText']:false;
         $url = $this->request->post['url'];
         $listaKontrollerow = isset($this->request->post['controllers'])?$this->request->post['controllers']:false;

         if($zapisac_do_pliku)
         {
              $this->saveToLog($url,$stara_fraza,$nowa_fraza);
         }

         if(!empty($listaKontrollerow))
         {
              $this->saveTranslation($stara_fraza,$nowa_fraza,$listaKontrollerow);
         }
     }

     private function saveTranslation($stara_fraza,$nowa_fraza,$listaKontrollerow)
     {
         $this->translator->save($stara_fraza,$nowa_fraza,$listaKontrollerow);
     }

     private function saveToLog($url,$stara_fraza,$nowa_fraza)
     {

         $filename = DIR_APPLICATION."translations.txt";
         if (file_exists($filename)) {
             $translations = file_get_contents($filename);
         } else {
             file_put_contents($filename, '');
             $translations = '';
         }

         $translations .= "\n link: ".$url." stara_fraza: ".$stara_fraza." nowa_fraza: ".$nowa_fraza;

         file_put_contents($filename, $translations);

     }
}