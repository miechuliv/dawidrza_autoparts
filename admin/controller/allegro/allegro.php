<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Robert
 * Date: 19.06.13
 * Time: 14:38
 * To change this template use File | Settings | File Templates.
 */

class allegro {

    private $_client;
    private $_country;
    private $_login;
    private $_password;
    private $_key;
    private $_version;
    private $_session;



    public function __construct($config)
    {

         if(isset($config['login']) AND isset($config['password']) AND isset($config['key']) AND isset($config['country']) AND isset($config['version']))
         {
             $this->_login = $config['login'];
             $this->_password = $config['password'];
             $this->_key = $config['key'];
             $this->_country = (int)$config['country'];
             $this->_version = $config['version'];
             $this->_client =  new SoapClient('http://webapi.allegro.pl/uploader.php?wsdl');
         }
         else
         {
             throw new Exception('Konfiguracja nie prawidłowa');
         }

    }

    public function login()
    {



        try
        {

            $this->getVerKey();

           $this->_session = $this->_client->doLoginEnc($this->_login, base64_encode( hash('sha256', $this->_password, true) ), $this->_country, $this->_key, $this->_version);

        }
        catch(SoapFault $Error){
            throw new Exception('Błąd: '.$Error->getMessage().' login: '.$this->_login.' hasło: '.$this->_password.' klucz: '.$this->_key.' kraj: '.$this->_country.' wersja: '.$this->_version);
        }

    }

    public function getVerKey()
    {

        $component = 1;
        $result = $this->_client->doQuerySysStatus($component,$this->_country,$this->_key);

        if(isset($result['ver-key']))
        {
            $this->_version = $result['ver-key'];
        }
        else
        {
            throw new Exception("Nie udało sie pobrać wersji komponentu");
        }
    }

    public function checkConnection(){
        if(!$this->_session)
        {
            throw new Exception('Błąd: brak połączenia z allegro');
        }
    }

    /*
     *  pobiera domyslny sposób obliczania kosztów przesyłki
     *  @return int
     */

    public function getMyPricing()
    {
        try{

            $response = $this->_client->doGetMyCurrentShipmentPriceType($this->_session['session-handle-part']);

            return $response;

        }
        catch(SoapFault $Error)
        {
            if($Error->faultcode = 'ERR_NO_SESSION' OR $Error->faultcode = 'ERR_SESSION_EXPIRED')
            {
                $this->login();
                $this->getMyPricing();
            }
            else
            {
                throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
            }

        }

    }


    // drzewo kategorii

    /*
     *   pobiera wszystkie kategorie
     */

    public function getAllCatsByCountry()
    {
        try{


            //var_dump($data);

            $result = $this->_client->doGetCatsData($this->_country,0,$this->_key);

            $categories = new allegroCategoryCollection();

            foreach($result['cats-list'] as $cats)
            {
                $categories->add(new allegroCategory($cats));
            }

            return $categories;
        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }

    }

    /*
     *  zwraca info o userze
     *  @retrun array
     */

    public function getMyData()
    {
        try{


            $result = $this->_client->doGetMyData($this->_session['session-handle-part']);

            return $result;

        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }


    }


    // moje allegro

    /*
     * zwraca info o niesprzedanych produktach
     * @return array
     */

    public function getNotSold()
    {
        try{

            // @todo tu można przekazać też filtry i sortowanie

            $result = $this->_client->doGetMyNotSoldItems($this->_session['session-handle-part']);

            return $result;

        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }
    }

    /*
     * zwraca aktywna sprzedaż
     * @return array
     */

    public function getCurrentSell()
    {
        try{

            // @todo tu można przekazać też filtry i sortowanie

            $result = $this->_client->doGetMySellItems($this->_session['session-handle-part']);



            return $result;

        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }
    }

    /*
     *  zwraca sprzedane
     *  @return array
     */
    public function getSoldItems()
    {
        try{

            // @todo tu można przekazać też filtry i sortowanie

            $result = $this->_client->doGetMySoldItems($this->_session['session-handle-part']);


            return $result;

        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }
    }

    // wystawianie oferty
    /*
     * pozwala na sprawdzenie czy opis jest zgodny z standardem allegro
     * @param string description
     * @return string
     */

    public function checkItemDescription($description)
    {
        try{



            $result = $this->_client->doCheckItemDescription($this->_session['session-handle-part'],$description);

            return $result;

        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }
    }

    /*
     *  pozwala na wytestowanie aukcji, zwraca koszty i paramtery aukcji
     *  @param array fields
     *  @return array
     */

    public function checkNewAuction($fields)
    {
        try{

            // @todo wytestowac

            $result = $this->_client->doCheckNewAuctionExt($this->_session['session-handle-part'],$fields);

            return $result;

        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }
    }

    /*
     * pobiera pola specyficzne dla danej kategorii
     * @param int category_id
     * @return array
     */
    public function getFieldsByCats($category_id)
    {
        try{

            // @todo wytestowac

            $result = $this->_client->doGetSellFormFieldsForCategory($this->_key,$this->_country,$category_id);

            return $result;

        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }
    }

    /*
     * pobierz wszytkie pola
     *
     */
    public function getAllFields(){
        try{

            // @todo wytestowac

            $result = $this->_client->doGetSellFormFieldsExt($this->_country,$this->_version,$this->_key);

            return $result;

        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }
    }
    /*
     *  pobeira atrybuty dla danej kategorii
     */

    public function getAttribByCats($category_id)
    {
        try{

            // @todo wytestowac

            $result = $this->_client->doGetSellFormAttribs($this->_country,$this->_key,$this->_version,$category_id);

            return $result;

        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }
    }

    public function newAuction($fields)
    {
        try{

            // @todo wytestowac
            $local_id = uniqid();
            $this->_client->doNewAuctionExt($this->_session['session-handle-part'],$fields,0,$local_id);

            $result = $this->_client->doVerifyItem($this->_session['session-handle-part'],$local_id);

            return $result;

        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }
    }

    public function resell($item_ids,$duration)
    {
        try{

            // @todo wytestowac

            $local_ids = array();
            foreach($item_ids as $item)
            {
                $local_ids[]=uniqid();
            }

           $result =  $this->_client->doSellSomeAgain($this->_session['session-handle-part'],$item_ids,0,$duration,$local_ids);

         /*   $result = array();
            foreach($local_ids as $local_id)
            {
                $result[] = $this->_client->doVerifyItem($this->_session['session-handle-part'],$local_id);
            } */



            return $result;

        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }

    }
    // PAYU

    /*
     * pobiera info o przychodzących płatnościach z allegro
     */

   public function getIncomingPAYU()
   {

        try{




            $now = new DateTime();
            $timestamp2 = $now->getTimestamp();

            $date = $now->sub(date_interval_create_from_date_string('90 days'));
            $timestamp = $date->getTimestamp();



            $result = $this->_client->doGetMyIncomingPayments($this->_session['session-handle-part'],0,0,$timestamp,$timestamp2,0,0);




            return $result;

        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }
   }

    /*
     * pobiera płatności użytkownika
     */
    public function getMyPAYU()
    {
        try{

            $now = new DateTime();
            $timestamp2 = $now->getTimestamp();

            $date = $now->sub(date_interval_create_from_date_string('25 days'));
            $timestamp = $date->getTimestamp();

            $result = $this->_client->doGetMyPayouts($this->_session['session-handle-part'],$timestamp,$timestamp2,0,0);

            return $result;

        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }
    }

    // komentarze

    /*
     *  pobiera komnatarze
     *  $param string fb_recvd komantarze otrzymane | fb_gave komentarze wystawione
     */

    public function getComments($mode = 'fb_recvd')
    {
        if($mode!=='fb_recvd' AND $mode!=='fb_gave')
        {
             throw new Exception('Paramter mode funkcji'.__FUNCTION__.' przyjmuje wartości fb_recvd i fb_gave ');
        }
        try{

            $result = $this->_client->doMyFeedback2($this->_session['session-handle-part'],$mode);

            return $result;

        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }
    }

    public function getKontrahent($auction)
    {

        try{

            $result = $this->_client->doGetPostBuyData($this->_session['session-handle-part'],$auction);

            return $result;

        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }
    }

    /*
     * na razie nie mam pomyslu co ztym zrobic bo metoda nie zwarca koduw iso tylko nazwy i to po polsku :(
     * wiec zwracam na sztywno id polski
     */

    public function getCountries($country_id)
    {

        return 2;

        try{

        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }
    }

    function getTransactions($method)
    {
        try{

            $result = $this->_client->doGetFilledPostBuyForms($this->_session['session-handle-part'],(int)$method,1);

            return $result;

        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }
    }


    function getTransactionsData($tranasction_id_array)
    {
        try{

            $result = $this->_client->doGetPostBuyFormsDataForSellers($this->_session['session-handle-part'],$tranasction_id_array);

            return $result;

        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }
    }

    function getShipmentName($shipment_id)
    {
        try{

            $result = $this->_client->doGetShipmentData($this->_country,$this->_key);

         

            foreach($result['shipment-data-list'] as $shipment)
            {
                if($shipment->{'shipment-id'}==$shipment_id)
                {
                     return $shipment->{'shipment-name'};
                }
            }

            return 'Błąd! Nie określono';

        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }
    }




    public function getJournal()
    {
        try{

            $result = $this->_client->doGetSiteJournal($this->_session['session-handle-part']);

            return $result;

        }
        catch(SoapFault $Error)
        {
            throw new Exception('Bład metody: '.__FUNCTION__.' Bład Soap: '.$Error->getMessage());
        }
    }





}

class allegroCategory{

    public $cat_id;
    public $cat_name;
    public $cat_position;
    public $cat_parent;
    public $cat_is_product_catalogue_enabled;

    public function __construct($data)
    {

         $this->cat_id = $data->{'cat-id'};
         $this->cat_is_product_catalogue_enabled = $data->{'cat-is-product-catalogue-enabled'};
         $this->cat_name = $data->{'cat-name'};
         $this->cat_parent = $data->{'cat-parent'};
         $this->cat_position = $data->{'cat-position'};
    }
}

class allegroCategoryCollection implements Countable
{
    public $categories=array();

    public function add(allegroCategory $category)
    {

        $this->categories[]=$category;
    }

    public function count()
    {
        return count($this->categories);
    }

    public function getChildren($parent_id)
    {
        $children = array();

        foreach($this->categories as $category){
              if($category->cat_parent == $parent_id)
              {
                  $children[]=$category;
              }
        }

        return $children;
    }

    public function getTop(){
        $top = array();

        foreach($this->categories as $category){
            if($category->cat_parent == '0')
            {
                $top[]=$category;
            }
        }

        return $top;
    }
}

