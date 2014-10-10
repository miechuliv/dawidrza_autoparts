<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 16.07.13
 * Time: 17:10
 * To change this template use File | Settings | File Templates.
 */

class ModelEbayDebay extends Model{

    private $category_fields = array('BestOfferEnabled' => 'best_offer_enabled',
     'AutoPayEnabled' => 'auto_pay_enabled',
    'CategoryID' => 'category_id',
     'CategoryLevel' => 'category_level',
 'CategoryName' => 'category_name',
 'CategoryParentID' => 'category_parent_id',
);

    private $country_fields = array(
        'Country' =>'country',
'Description' => 'description',
    );

    private $currency_fields = array(
        'Currency' =>'currency',
        'Description' => 'description',
    );


    public function getMessagesFromEbay($type,$pagination = false,$page = 1, $limit = 15)
    {
        $params = array(
            'Version' => 831,
            'DetailLevel' => $type,
        );

        if($pagination)
        {
            $params['Pagination'] = array(
                'EntriesPerPage' => $limit,
                'PageNumber' => $page,
            );
        }


        $method = 'GetMyMessages';

        try{

            $resp =  debay::sendRequest($method,$params);

            return $resp;

        }
        catch(Exception $e)
        {
            ob_start();
            var_dump($e);
            $contents = ob_get_contents();
            ob_end_clean();
            $this->logger->warning("Bład przy sciąganiu wiadomosci z ebay: ".$contents,'ebay');
            echo $e->getMessage();
        }
    }

    public function getSingleMessageFromEbay($message_id)
    {
        $params = array(
            'Version' => 831,
            'DetailLevel' => 'ReturnMessages',
            'MessageIDs' => array( 'MessageID' => (int)$message_id ),

        );

        $method = 'GetMyMessages';

        try{

            $resp =  debay::sendRequest($method,$params);

            return $resp;

        }
        catch(Exception $e)
        {
            ob_start();
            var_dump($e);
            $contents = ob_get_contents();
            ob_end_clean();
            $this->logger->warning("Bład przy sciąganiu wiadomosci z ebay: ".$contents,'ebay');
            echo $e->getMessage();
        }
    }

    public function addAuction($product_id,$auction_id,$quantity)
    {

          $this->db->query("INSERT INTO `debay_auctions` SET `product_id`='".(int)$product_id."', `auction_id`='".$auction_id."', quantity='".$quantity."' ");

         // pierwotnie chcialem zdejmowac stany juz przy wystawianiu aukcji
      //    $this->db->query("UPDATE product SET quantity=quantity - '".(int)$quantity."' WHERE product_id='".(int)$product_id."' ");

    }

    public function checkQuantity($product_id)
    {
        $result = $this->db->query("SELECT quantity FROM product  WHERE product_id='".(int)$product_id."' ");

        return $result->row;

    }

    public function getAuctions()
    {
        $query = $this->db->query("SELECT * FROM `debay_auctions`");

        return $query->rows;
    }

    public function saveOrder($order_id)
    {

        $this->load->model('catalog/product');
        $this->load->model('sale/order');
        $this->load->model('localisation/currency');
        /*
         * sprawdzamy czy zamowienie bylo juz sprawdzane, jesli tak nie ma sensu drugi raz obnizac stanu
         * jednoznaczne sprawdznie prze kombinacje auction_id (item_id na ebayu) i transaction_id
         * zaklada ze w tej samej transakcji nie bedzie dwa razy osobno tego samego pruduktu
         * auction_id == itemID na ebayu
         */
        $query = $this->db->query("SELECT * FROM `debay_order`  WHERE `order_id`='".$order_id."'  ");


        if(!$query->row)
        {
        /*
         * dodajmy zzamowienie do sprawdzonych
         */
        $this->db->query("INSERT INTO `debay_order` SET  `order_id`='".$order_id."' ");
            /*
             * @todo dodac do zamowien opencarta
             */

        $this->addOpenCartOrder($order_id);

        }



    }

    private function addOpenCartOrder($order_id)
    {

        $params = array(
            'Version' => 831,
            'OrderIDArray' => array('OrderID' => $order_id),

        );

        $method = 'GetOrderTransactions';

        $resp =  debay::sendRequest($method,$params);

        if(isset($resp->OrderArray->Order)){

            $order = $resp->OrderArray->Order;

            $ship_addres = $order->ShippingAddress;

            $ship_service = $order->ShippingServiceSelected;

            $paid_time = explode('T',$order->PaidTime);

            $items =  array();



            //waluta w openkarcie
            $currency_info = $this->model_localisation_currency->getCurrencyByCode($this->config->get('config_currency'));


            if ($currency_info) {
                $currency_id = $currency_info['currency_id'];
                $currency_code = $currency_info['code'];
                $currency_value = $currency_info['value'];
            } else {
                $currency_id = 0;
                $currency_code = $this->config->get('config_currency');
                $currency_value = 1.00000;
            }

            // waluta z akcji
            $currency_ebay = $this->getCurrencyByCode($order->AmountPaid->currencyID);

            $currency_ebay_id = $currency_ebay ['currency_id'];
            $currency_ebay_code = $currency_ebay ['code'];
            $currency_ebay_value = $currency_ebay ['value'];



            foreach($order->TransactionArray->Transaction as $transaction)
            {



                // wyczyscielem table transakcji wice trzeba cos tam wpisac an pale albo zrobic jakies aukcje
                $product = $this->getProductByAuction($transaction->Item->ItemID);
                if(isset($product['product_id'])){
                    $product_data = $this->model_catalog_product->getProduct($product['product_id']);
                    // modyfikacja ceny na to z ebaya



                   if(!empty($product_data))
                   {
                       $product_data['price'] = $this->currency->convert((int)$transaction->Item->SellingStatus->CurrentPrice->_,$currency_ebay_value,$currency_value,true);
                       $product_data['quantity'] =  $transaction->QuantityPurchased;
                       $product_data['total'] = $this->currency->convert((int)$transaction->TransactionPrice->_,$currency_ebay_value,$currency_value,true);
                       $product_data['tax'] = NULL;
                       $items[] = $product_data;
                   }

                }



            }



            // @todo $ship_addres['Country'], przetłumaczyć na opencartowe country
            $country_id = $this->getCountryByCode($ship_addres->Country);


            // totalsy na sztywno
            $totals = array();
            $totals[] = array(
                'code' => 'sub_total',
                'title' => 'Suma cześciowa',
                'text' => (int)$order->Subtotal->_.' '.$order->Subtotal->currencyID,
                'value'=> $this->currency->convert((int)$order->Subtotal->_,$currency_ebay_value,$currency_value,true),
                'sort_order' => 1
            );

            $totals[] = array(
                'code' => 'shipping',
                'title' => $ship_service->ShippingService,
                'text'=> ((int)$order->Total->_ - (int)$order->Subtotal->_).' '.$order->Subtotal->currencyID,
                'value' => $this->currency->convert((int)$order->Total->_ - (int)$order->Subtotal->_,$currency_ebay_value,$currency_value,true),
                'sort_order' => 2
            );

            $totals[] = array(
                'code' => 'total',
                'title' => 'Razem:',
                'text'=> $order->Total->_.' '.$order->Total->currencyID,
                'value' => $this->currency->convert((int)$order->Total->_,$currency_ebay_value,$currency_value,true),
                'sort_order' => 3
            );

            $data = array(
                'store_id' => 0,

                'shipping_country_id' => $country_id,
                // zone nie bedzie
                'shipping_zone_id' => '',
                'payment_country_id' => $country_id,
                // zone nie bedzie
                'payment_zone_id' => '',

                'customer_id' => NULL,
                'customer_group_id' => NULL,
                'firstname' => $ship_addres->Name,
                'lastname' => '',
                // moze skads sie da zabrac
                'email' => '',
                'telephone' => $ship_addres->Phone,
                'fax' => '',

                'payment_firstname' => $ship_addres->Name,
                'payment_lastname' => '',
                'payment_company' => '',
                'payment_tax_id' => 0,
                'payment_address_1' => $ship_addres->Street1,
                'payment_address_2' => $ship_addres->Street2,
                'payment_city' => $ship_addres->CityName,
                'payment_postcode' => $ship_addres->PostalCode,
                'payment_method' => $order->CheckoutStatus->PaymentMethod,
                'payment_code' => '',
                'payment_company_id' => NULL,

                'shipping_firstname' => $ship_addres->Name,
                'shipping_lastname' => '',
                'shipping_company' => '',
                'shipping_tax_id' => NULL,
                'shipping_address_1' => $ship_addres->Street1,
                'shipping_address_2' => $ship_addres->Street2,
                'shipping_city' => $ship_addres->CityName,
                'shipping_postcode' => $ship_addres->PostalCode,
                'shipping_method' => $ship_service->ShippingService,
                'shipping_code' => '',
                'comment' => '',
                // na sztywno, 2 - zapłacono, zakładam ze tu przechodza tylko zaplacone zamowienia po ukonczeniu czekautu na ebay
                'order_status_id' => 18,
                'affiliate_id' => NULL,

                'order_product' => $items,


                'order_total' => $totals,

            );

            $this->model_sale_order->addOrder($data,true);

        }

    }

    private function getCountryByCode($code)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "country WHERE iso_code_2 = '" . $code . "'");

        if(isset($query->row['country_id']))
        {
            return $query->row['country_id'];
        }
        else
        {
            return 0;
        }

    }

    private function getCurrencyByCode($currency)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape($currency) . "'");

        return $query->row;
    }

    public function getProductByAuction($auction_id)
    {

        $result = $this->db->query("SELECT * FROM `debay_auctions`  WHERE auction_id='".$auction_id."' ");

        return $result->row;
    }




    public function updateCategoriesFromEbay($site = '0')
    {
        $params = array(
            'Version' => 831,


            'LevelLimit' => 8,
            'ViewAllNodes' => true,
            'DetailLevel' => 'ReturnAll',
            'CategorySiteID' => $site

        );

        $method = 'getCategories';

        try{

            $resp =  debay::sendRequest($method,$params);
            $this->updateCategories($resp);

        }
        catch(Exception $e)
        {
            ob_start();
            var_dump($e);
            $contents = ob_get_contents();
            ob_end_clean();
            $this->logger->warning("Nie udało sie uzyskać info o kategoriach: ".$contents,'ebay');
            echo $e->getMessage();
        }
    }


    public function updateCategories($data)
    {

          if(!isset($data->CategoryArray->Category) OR !is_array($data->CategoryArray->Category))
          {
              ob_start();
              var_dump($data);
              $contents = ob_get_contents();
              ob_end_clean();
              $this->logger->warning("Prblem przy aktualizacji kategorii: ".$contents,'ebay');
              throw new Exception('Brak kategorii do zaktualizowania!');
          }

          $this->deleteCategories();

          foreach($data->CategoryArray->Category as $key => $category)
          {

                if(!isset($category->BestOfferEnabled))
          {
              $category->BestOfferEnabled = false;
          }

              if(!isset($category->AutoPayEnabled))
              {
                  $category->AutoPayEnabled = false;
              }

              if(!isset($category->LeafCategory))
              {
                  $category->LeafCategory = false;
              }



                $this->db->query("INSERT INTO `debay_categories` SET `best_offer_enabled` = '".$category->BestOfferEnabled."',
                `auto_pay_enabled` = '".$category->AutoPayEnabled."',
                `category_id` = '".$category->CategoryID."',
                `category_level` = '".$category->CategoryLevel."',
                `category_name` = '".$this->db->escape($category->CategoryName)."',
                `category_parent_id` = '".$category->CategoryParentID."',
                `leaf_category` = '".$category->LeafCategory."'


                 ");



          }

        return true;

    }

    public function deleteCategories()
    {
         $this->db->query("DELETE FROM debay_categories");
    }

    public function getChildrenById($parent_id)
    {
         if(!$parent_id)
         {

             throw new Exception('Nie podano id rodzica kategorii');
         }

         $results = $this->db->query("SELECT * FROM debay_categories WHERE `category_parent_id`='".(int)$parent_id."' AND `category_id`!='".(int)$parent_id."'  ");

         $data = array();

         if($results)
         {
             foreach($results->rows as $row)
             {
                 $data[] = new categoryRow($row);
             }
         }

         return $data;
    }

    public function getFirstLevel()
    {


        $results = $this->db->query("SELECT * FROM debay_categories WHERE `category_level`='1'  ");

        $data = array();

        if($results)
        {
            foreach($results->rows as $row)
            {
                $data[] = new categoryRow($row);
            }
        }

        return $data;
    }

    /*
     * sciaga features dozwolone dla danej kategorii
     */
    public function updateCategoryFeaturesFromEbay($site = '0')
    {
        $params = array(
            'Version' => 831,

            'CategorySiteID' => $site,
            'CategoryID' => 111422,
            'DetailLevel' => 'ReturnAll',


        );

        $method = 'GetCategoryFeatures';

        try{

            $resp =  debay::sendRequest($method,$params);


        }
        catch(Exception $e)
        {
            ob_start();
            var_dump($e);
            $contents = ob_get_contents();
            ob_end_clean();
            $this->logger->warning("Prblem przy aktualizacji cech kategorii: ".$contents,'ebay');
            echo $e->getMessage();
        }
    }

    /*
     * sicaga atrybuty przypisane do danej kategorii
     * @todo to trzeba bedzie sciagac ajaxem i potworzyc jakies obiekty
     */
    public function updateCategoryAttributesFromEbay($category)
    {

            $params = array(
                'Version' => 831,
                'CategorySpecific' => array( 'CategoryID' => (int)$category ),
            );




        $method = 'GetCategorySpecifics';

        try{

            $resp =  debay::sendRequest($method,$params);

            $this->load->model('ebay/debayattribute');

            $fields =  $this->model_ebay_debayattribute->generatefields($resp);

            return $fields;


        }
        catch(Exception $e)
        {
            ob_start();
            var_dump($e);
            $contents = ob_get_contents();
            ob_end_clean();
            $this->logger->warning("Prblem przy aktualizacji atrybutów kategorii: ".$contents,'ebay');
            throw new Exception($e->getMessage());
        }
    }



    /*
     *  funkcja sciaga u updatuje dane takie jak: koidy krajów, metod wysyłki i płatności, paramter detailName decyduje o tym co sie sciaga
     *  lista detailCodes: http://developer.ebay.com/devzone/xml/docs/reference/ebay/types/DetailNameCodeType.html
     * @todo zrobic kategorie wsysyłek, zasady zwrotu,
     * @todo podzialić to wszystko an pliki i segmenty, złapac ficzersy kategorii
     */

    public function updateEbayDetails($post)
    {

        set_time_limit(3000);

        define("US",0);
        define("DE",77);
        define("PL",212);

        $site = '77';


        if(isset($post['country']) AND $post['country']=='on')
        {
            $this->getCountryCodesFromEbay($site);
        }

        if(isset($post['currency']) AND $post['currency']=='on')
        {
            $this->getCurrencyfromEbay($site);
        }


        if(isset($post['dispatch']) AND $post['dispatch']=='on')
        {
            $this->getDisptachTimeFromEbay($site);
        }


        if(isset($post['details']) AND $post['details']=='on')
        {
            $this->getRegionDetailsFromEbay($site);
        }

        if(isset($post['carrier']) AND $post['carrier']=='on')
        {
            $this->getShippingCarrierDetailsFromEbay($site);
        }

        if(isset($post['location']) AND $post['location']=='on')
        {
            $this->getShippingLocationDetailsFromEbay($site);
        }

        if(isset($post['package']) AND $post['package']=='on')
        {
            $this->getShippingPackageDetailsFromEbay($site);
        }

        if(isset($post['service']) AND $post['service']=='on')
        {
            $this->getShippingServiceDetailsFromEbay($site);
        }

        if(isset($post['category']) AND $post['category']=='on')
        {
            $this->updateCategoriesFromEbay($site);
        }






    }

    /*
     * sciaga dane o walutach
     */
    public function getCurrencyfromEbay($site = '0')
    {
        $params = array(
            'Version' => 831,
            'DetailName' => 'CurrencyDetails',
            'CategorySiteID' => $site
        );

        $method = 'geteBayDetails';

        try{

            $resp =  debay::sendRequest($method,$params);
            $this->updateCurrency($resp);

        }
        catch(Exception $e)
        {
            ob_start();
            var_dump($e);
            $contents = ob_get_contents();
            ob_end_clean();
            $this->logger->warning("Prblem przy aktualizacji walut: ".$contents,'ebay');
            echo $e->getMessage();
        }
    }

    public function updateCurrency($data)
    {


        if(!isset($data->CurrencyDetails) OR !$data->CurrencyDetails)
        {
            throw new Exception("Brak danych o walutach do zaladowania");
        }

        $this->deleteCurrency();

        foreach($data->CurrencyDetails as $currency)
        {
            $this->db->query("INSERT INTO debay_currency SET `currency`= '".$currency->Currency."',  `description`='".$this->db->escape($currency->Description)."'  ");
        }

    }

    public function deleteCurrency()
    {
        $this->db->query("DELETE FROM debay_currency");
    }

    public function getCurrency()
    {
        $results = $this->db->query("SELECT * FROM debay_currency ");

        $data = array();

        if($results)
        {
            foreach($results->rows as $row)
            {
                $data[] = new currencyRow($row);
            }
        }

        return $data;
    }

    /*
     * sciaga kody krajow z ebaya
     */
    public function getCountryCodesFromEbay($site = '0')
    {
        $params = array(
            'Version' => 831,
            'DetailName' => 'CountryDetails',
            'CategorySiteID' => $site
        );

        $method = 'geteBayDetails';

        try{

            $resp =  debay::sendRequest($method,$params);
            $this->updateCountryCodes($resp);

        }
        catch(Exception $e)
        {
            ob_start();
            var_dump($e);
            $contents = ob_get_contents();
            ob_end_clean();
            $this->logger->warning("Prblem przy aktualizacji kodów krajów: ".$contents,'ebay');
            echo $e->getMessage();
        }
    }
    /*
     * funckcja zapisauje kody krajów z ebay
     */

    public function updateCountryCodes($data)
    {


        if(!isset($data->CountryDetails) OR !$data->CountryDetails)
        {
            throw new Exception("Brak kodów krajów do zaladowania");
        }

        $this->deleteCountryCodes();

        foreach($data->CountryDetails as $country)
        {
            $this->db->query("INSERT INTO debay_country_codes SET `country`= '".$country->Country."',  `description`='".$this->db->escape($country->Description)."'  ");
         }

    }

    public function deleteCountryCodes()
    {
        $this->db->query("DELETE FROM debay_country_codes");
    }

    public function getCountryCodes()
    {
        $results = $this->db->query("SELECT * FROM debay_country_codes ");

        $data = array();

        if($results)
        {
            foreach($results->rows as $row)
            {
                $data[] = new countryRow($row);
            }
        }

        return $data;
    }



    /*
     * sciaga czasy dispatch time an eba, np : 1 dzien -> id 1 itp
     */

    public function getDisptachTimeFromEbay($site = '0')
    {
        $params = array(
            'Version' => 831,
            'DetailName' => 'DispatchTimeMaxDetails',
            'CategorySiteID' => $site
        );

        $method = 'geteBayDetails';

        try{

            $resp =  debay::sendRequest($method,$params);
            $this->updateDispatchTimes($resp);

        }
        catch(Exception $e)
        {
            ob_start();
            var_dump($e);
            $contents = ob_get_contents();
            ob_end_clean();
            $this->logger->warning("Prblem przy aktualizacji czasów wysyłki: ".$contents,'ebay');
            echo $e->getMessage();
        }
    }
    /*
     * funckcja zapisauje kody krajów z ebay
     */

    public function updateDispatchTimes($data)
    {


        if(!isset($data->DispatchTimeMaxDetails) OR !$data->DispatchTimeMaxDetails)
        {
            throw new Exception("Brak danych o czasach wysyłki");
        }

        $this->deleteDispatchTime();

        foreach($data->DispatchTimeMaxDetails as $time)
        {
            $this->db->query("INSERT INTO debay_time_max_details SET `dispatch_time_max`= '".(int)$time->DispatchTimeMax."',  `description`='".$this->db->escape($time->Description)."', `extended_handling`='".(int)$time->ExtendedHandling."'    ");
        }

    }

    public function deleteDispatchTime()
    {
        $this->db->query("DELETE FROM debay_time_max_details");
    }

    public function getDispatchTime()
    {
        $results = $this->db->query("SELECT * FROM debay_time_max_details ");

        $data = array();

        if($results)
        {
            foreach($results->rows as $row)
            {
                $data[] = new timeRow($row);
            }
        }

        return $data;
    }


    /*
     * funkcja zapisuje wojwodztwa ebay
     */

    public function getRegionDetailsFromEbay($site = '0')
    {
        $params = array(
            'Version' => 831,
            'DetailName' => 'RegionDetails',
            'CategorySiteID' => $site
        );

        $method = 'geteBayDetails';

        try{

            $resp =  debay::sendRequest($method,$params);
            $this->updateRegionDetails($resp);

        }
        catch(Exception $e)
        {
            ob_start();
            var_dump($e);
            $contents = ob_get_contents();
            ob_end_clean();
            $this->logger->warning("Prblem przy aktualizacji regionów: ".$contents,'ebay');
            echo $e->getMessage();
        }
    }

    public function updateRegionDetails($data)
    {


        if(!isset($data->RegionDetails) OR !$data->RegionDetails)
        {
            throw new Exception("Brak danych o wojewodztwach");
        }

        $this->deleteRegionDetails();

        foreach($data->RegionDetails as $region)
        {
            $this->db->query("INSERT INTO debay_regions SET `region_id`= '".(int)$region->RegionID."',  `description`='".$this->db->escape($region->Description)."'   ");
        }

    }

    public function deleteRegionDetails()
    {
        $this->db->query("DELETE FROM debay_regions");
    }

    public function getRegionDetails()
    {
        $results = $this->db->query("SELECT * FROM debay_regions ");

        $data = array();

        if($results)
        {
            foreach($results->rows as $row)
            {
                $data[] = new regionRow($row);
            }
        }

        return $data;
    }


    /*
    * funkcja zapisuje dane o tym do jakich regionow sa prowadzone wysylki
    */

    public function getShippingLocationDetailsFromEbay($site = '0')
    {
        $params = array(
            'Version' => 831,
            'DetailName' => 'ShippingLocationDetails',
            'CategorySiteID' => $site
        );

        $method = 'geteBayDetails';

        try{

            $resp =  debay::sendRequest($method,$params);
            $this->updateShippingLocationDetails($resp);

        }
        catch(Exception $e)
        {
            ob_start();
            var_dump($e);
            $contents = ob_get_contents();
            ob_end_clean();
            $this->logger->warning("Prblem przy aktualizacji lokalizacji: ".$contents,'ebay');
            echo $e->getMessage();
        }
    }

    public function updateShippingLocationDetails($data)
    {


        if(!isset($data->ShippingLocationDetails) OR !$data->ShippingLocationDetails)
        {
            throw new Exception("Brak danych o strefach wysyłki");
        }

        $this->deleteShippingLocationDetails();

        foreach($data->ShippingLocationDetails as $location)
        {
            $this->db->query("INSERT INTO debay_shipping_location SET `shipping_location`= '".(int)$location->ShippingLocation."',  `description`='".$this->db->escape($location->Description)."'    ");
        }

    }

    public function deleteShippingLocationDetails()
    {
        $this->db->query("DELETE FROM debay_shipping_location");
    }

    public function getShippingLocationDetails()
    {
        $results = $this->db->query("SELECT * FROM debay_shipping_location ");

        $data = array();

        if($results)
        {
            foreach($results->rows as $row)
            {
                $data[] = new shippingRegionRow($row);
            }
        }

        return $data;
    }

    /*
    * funkcja zapisuje dane o rodzajach paczek
    */

    public function getShippingPackageDetailsFromEbay($site = '0')
    {
        $params = array(
            'Version' => 831,
            'DetailName' => 'ShippingPackageDetails',
            'CategorySiteID' => $site
        );

        $method = 'geteBayDetails';

        try{

            $resp =  debay::sendRequest($method,$params);

            $this->updateShippingPackageDetails($resp);

        }
        catch(Exception $e)
        {
            ob_start();
            var_dump($e);
            $contents = ob_get_contents();
            ob_end_clean();
            $this->logger->warning("Prblem przy aktualizacji paczek: ".$contents,'ebay');
            echo $e->getMessage();
        }
    }

    public function updateShippingPackageDetails($data)
    {


        if(!isset($data->ShippingPackageDetails) OR !$data->ShippingPackageDetails)
        {
            throw new Exception("Brak danych o rodzajach paczek");
        }

        $this->deleteShippingPackageDetails();

        foreach($data->ShippingPackageDetails as $package)
        {

            if(!isset($package->DefaultValue))
            {
                $package->DefaultValue = NULL;
            }

            $this->db->query("INSERT INTO debay_package_types SET
            `package_id`= '".(int)$package->PackageID."',
            `description`='".$this->db->escape($package->Description)."',
            `shipping_package`='".$this->db->escape($package->ShippingPackage)."',
            `default_value`='".(int)$package->DefaultValue."'
                ");
        }


    }

    public function deleteShippingPackageDetails()
    {
        $this->db->query("DELETE FROM debay_package_types");
    }

    public function getShippingPackageDetails()
    {
        $results = $this->db->query("SELECT * FROM debay_package_types ");

        $data = array();

        if($results)
        {
            foreach($results->rows as $row)
            {
                $data[] = new packageTypeRow($row);
            }
        }

        return $data;
    }

    public function getShippingPackageDetailsById($package_id)
    {
        $results = $this->db->query("SELECT * FROM debay_package_types WHERE package_id='".$package_id."' ");

        $data = array();

        if($results)
        {
               $data = new packageTypeRow($results->row);
        }

        return $data;
    }

    public function getShippingPackageDetailsByName($description)
    {
        $results = $this->db->query("SELECT * FROM debay_package_types WHERE description='".$description."' ");

        $data = array();

        if($results)
        {
            $data = new packageTypeRow($results->row);
        }

        return $data;
    }


    /*
   * funkcja zapisuje dane o dostepnych kurierach
   */

    public function getShippingCarrierDetailsFromEbay($site = '0')
    {
        $params = array(
            'Version' => 831,
            'DetailName' => 'ShippingCarrierDetails',
            'CategorySiteID' => $site
        );

        $method = 'geteBayDetails';

        try{

            $resp =  debay::sendRequest($method,$params);


            $this->updateShippingCarrierDetails($resp);

        }
        catch(Exception $e)
        {
            ob_start();
            var_dump($e);
            $contents = ob_get_contents();
            ob_end_clean();
            $this->logger->warning("Prblem przy aktualizacji kurierów: ".$contents,'ebay');
            echo $e->getMessage();
        }
    }

    public function updateShippingCarrierDetails($data)
    {


        if(!isset($data->ShippingCarrierDetails) OR !$data->ShippingCarrierDetails)
        {
            throw new Exception("Brak danych o kurierach");
        }

        $this->deleteShippingCarrierDetails();

        foreach($data->ShippingCarrierDetails as $package)
        {
            $this->db->query("INSERT INTO debay_shipping_carriers SET
            `shipping_carrier_id`= '".(int)$package->ShippingCarrierID."',
            `description`='".$this->db->escape($package->Description)."',
            `shipping_carrier`='".$this->db->escape($package->ShippingCarrier)."'

                ");
        }
    }

    public function deleteShippingCarrierDetails()
    {
        $this->db->query("DELETE FROM debay_shipping_carriers");
    }

    public function getShippingCarrierDetails()
    {
        $results = $this->db->query("SELECT * FROM debay_shipping_carriers ");

        $data = array();

        if($results)
        {
            foreach($results->rows as $row)
            {
                $data[] = new shippingCarrierRow($row);
            }
        }

        return $data;
    }


    /*
   * funkcja zapisuje dane dostepnych metodach wysyłki, każda metoda ma przyhpisane rodzaje paczek
   */

    public function getShippingServiceDetailsFromEbay($site = '0')
    {
        $params = array(
            'Version' => 831,
            'DetailName' => 'ShippingServiceDetails',
            'CategorySiteID' => $site
        );

        $method = 'geteBayDetails';

        try{

            $resp =  debay::sendRequest($method,$params);

            $this->updateShippingServiceDetails($resp);

        }
        catch(Exception $e)
        {
            ob_start();
            var_dump($e);
            $contents = ob_get_contents();
            ob_end_clean();
            $this->logger->warning("Prblem przy aktualizacji metod wysyłki: ".$contents,'ebay');
            echo $e->getMessage();
        }
    }

    public function updateShippingServiceDetails($data)
    {


        if(!isset($data->ShippingServiceDetails) OR !$data->ShippingServiceDetails)
        {
            throw new Exception("Brak danych o metodach wysyłki");
        }

        $this->deleteShippingServiceDetails();


        foreach($data->ShippingServiceDetails as $service)
        {

            if(!isset($service->ShippingService))
            {
                 continue;
            }
            if(!isset($service->InternationalService))
            {
                $service->InternationalService = FALSE;
            }
            if(!isset($service->ShippingTimeMax))
            {
                $service->ShippingTimeMax = FALSE;
            }
            if(!isset($service->ShippingTimeMin))
            {
                $service->ShippingTimeMin = FALSE;
            }

            $this->db->query("INSERT INTO debay_shipping_services SET
            `description`='".$this->db->escape($service->Description)."',
            `international_service`='".(int)$service->InternationalService."',
            `shipping_service`='".$this->db->escape($service->ShippingService)."',
            `shipping_service_id`='".(int)$service->ShippingServiceID."',
            `shipping_time_max`='".(int)$service->ShippingTimeMax."',
            `shipping_time_min`='".(int)$service->ShippingTimeMin."'
             ");

            if(isset($service->ShippingPackage) AND is_array($service->ShippingPackage))
            {
                foreach($service->ShippingPackage as $package)
                {
                    $this->db->query("INSERT INTO debay_packages_to_services SET
            `service`='".$service->ShippingService."',
            `package`='".$package."'

                ");
                }
            }

        }


    }

    public function deleteShippingServiceDetails()
    {
        $this->db->query("DELETE FROM debay_shipping_services");

        $this->db->query("DELETE FROM debay_packages_to_services");
    }

    public function getShippingServiceDetails()
    {
        $results = $this->db->query("SELECT * FROM debay_shipping_services  ");

        $data = array();

        if($results)
        {
            foreach($results->rows as $row)
            {
                $subresult = $this->db->query("SELECT * FROM debay_packages_to_services WHERE service='".$row['shipping_service']."'  ");


                $data[] = new shippingServiceRow($row,$subresult);
            }
        }

        return $data;
    }

    public function getEndAuction($item,$reason)
    {
        $params = array(
            'Version' => 831,
            'ItemID' => $item,
            'EndingReason' => $reason,
        );

        $method = 'EndItem';

        try{

            $resp =  debay::sendRequest($method,$params);

            return $resp;

        }
        catch(Exception $e)
        {
            ob_start();
            var_dump($e);
            var_dump($item);
            $contents = ob_get_contents();
            ob_end_clean();
            $this->logger->warning("Prblem przy zakończeniu aukcji: ".$contents,'ebay');
            throw new Exception($e->getMessage());
        }

    }

    public function getSalesfromEbay($type='all',$sort=false)
    {
        if($type=='all')
        {
            $params = array(
                'Version' => 831,
                'DetailLevel' => 'ReturnAll',
            );
        }

        // sortowanie wg....
        // EndTime - data zakonczenie rosnoca
        // EndTimeDescending - odwrotnie
        // Price - cena rosnoca
        // PriceDescending - cena opadajoco
        if($type=='sold')
        {
            $params = array(
                'Version' => 831,
                'SoldList' => array( 'Include' => TRUE ),
            );

            if($sort)
            {
                $params['SoldList']['Sort'] = $sort;
            }
        }

        if($type=='unsold')
        {
            $params = array(
                'Version' => 831,
                'UnsoldList' => array( 'Include' => TRUE ),
            );

            if($sort)
            {
                $params['UnsoldList']['Sort'] = $sort;
            }
        }

        if($type=='active')
        {
            $params = array(
                'Version' => 831,
                'ActiveList' => array( 'Include' => TRUE ),
            );

            if($sort)
            {
                $params['ActiveList']['Sort'] = $sort;
            }
        }

        if($type=='summary')
        {
            $params = array(
                'Version' => 831,
                'SellingSummary' => array( 'Include' => TRUE, 'DetailLevel' => 'ReturnAll', ),

            );


        }






        $method = 'GetMyeBaySelling';

        try{

            $resp =  debay::sendRequest($method,$params);

            return $resp;

        }
        catch(Exception $e)
        {

            ob_start();
            var_dump($e);
            var_dump($type);
            $contents = ob_get_contents();
            ob_end_clean();
            $this->logger->warning("Prblem przy sciaganiu aukcji: ".$contents,'ebay');
            throw new Exception($e->getMessage());
        }
    }





}

class categoryRow
{

    public $BestOfferEnabled;
    public     $AutoPayEnabled;
public    $CategoryID;
public   $CategoryLevel;
public   $CategoryName;
public  $CategoryParentID;



     public function __construct($data)
       {
           $this->BestOfferEnabled = $data['best_offer_enabled'];
           $this->AutoPayEnabled =$data['auto_pay_enabled'];
    $this->CategoryID =$data['category_id'];
     $this->CategoryLevel =$data['category_level'];
 $this->CategoryName =$data['category_name'];
 $this->CategoryParentID =$data['category_parent_id'];
       }
}

class countryRow
{
     public $Country;
     public $Description;

    public function __construct($data)
    {
        $this->Country = $data['country'];
        $this->Description =$data['description'];

    }
}


class currencyRow
{
    public $Country;
    public $Description;

    public function __construct($data)
    {
        $this->Currency = $data['currency'];
        $this->Description =$data['description'];

    }
}

class timeRow
{
    public $DispatchTimeMax;
    public $Description;
    public $ExtendedHandling;

    public function __construct($data)
    {
        $this->DispatchTimeMax = $data['dispatch_time_max'];
        $this->Description =$data['description'];
        $this->ExtendedHandling =$data['extended_handling'];


    }
}

class regionRow
{
    public $RegionID;
    public $Description;


    public function __construct($data)
    {
        $this->RegionID = $data['region_id'];
        $this->Description =$data['description'];



    }
}

class shippingRegionRow
{
    public $ShippingRegion;
    public $Description;


    public function __construct($data)
    {
        $this->ShippingRegion = $data['shipping_region'];
        $this->Description =$data['description'];



    }
}

class packageTypeRow
{
    public $PackageID;
    public $Description;
    public $ShippingPackage;
    public $DefaultValue;


    public function __construct($data)
    {
        $this->PackageID = $data['package_id'];
        $this->Description = $data['description'];
        $this->ShippingPackage = $data['shipping_package'];
        $this->DefaultValue = $data['default_value'];

    }
}

class shippingCarrierRow
{
    public $ShippingCarrierID;
    public $Description;
    public $ShippingCarrier;


    public function __construct($data)
    {
        $this->ShippingCarrierID = $data['shipping_carrier_id'];
        $this->Description = $data['description'];
        $this->ShippingCarrier = $data['shipping_carrier'];

    }
}


class shippingServiceRow
{

    public $Description;
    public $ShippingService;
    public $InternationalService;
    public $ShippingServiceID;
    public $ShippingTimeMax;
    public $ShippingTimeMin;
    public $ShippingPackage=array();
    public $Cost;


    public function __construct($data,$packages)
    {

        $this->Description = $data['description'];
        $this->ShippingService = $data['shipping_service'];

        $this->InternationalService = $data['international_service'];

        $this->ShippingServiceID= $data['shipping_service_id'];
        $this->ShippingTimeMax= $data['shipping_time_max'];
        $this->ShippingTimeMin= $data['shipping_time_min'];

        $this->setShippingPackages($packages);


    }

    public function setShippingPackages($data)
      {
                    foreach($data->rows as $row)
                    {
                        $this->ShippingPackage[] = $row['package'];
                    }

      }



}


