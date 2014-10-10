<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 10.12.13
 * Time: 14:22
 * To change this template use File | Settings | File Templates.
 */

/*
 * wprowadzanie produktów do google merchant
 */

class ModelGoogleMerchant extends Model{


    private $_client;
    /*
     * przygotowuje produkt do wyslania, moze miec rozne parametry zaleznie od operacji (insert|update|delete)
     * param int $product_id
     * param string $operation insert|update|delete
     * return GSC_product
     */
    private function buildProduct($product_id,$operation)
    {


        $productOp = $this->model_catalog_product->getProduct($product_id);

        $country_id = $this->config->get('config_country_id');
        $country = $this->model_localisation_country->getCountry($country_id);


        if($operation =='insert')
        {
            // sprawdzamy czy taki juz istnieje w google merchant
            // produkt znajduje sie po id(sku), kraju i jezyku
            $retrievedProduct = false;
                try{
                    $retrievedProduct = $this->_client->getProduct($productOp['product_id'], $country['iso_code_2'], $this->config->get('config_language'));
                }
                catch(Exception $e)
                {

                }

                // już istnieje
                if($retrievedProduct)
                {

                    return $this->getUpdateProduct($productOp);
                }
                else
                {
                    return $this->getNewProduct($productOp);
                }



        }
        elseif($operation =='delete')
        {
            return $this->getDeleteProduct($productOp);
        }
        elseif($operation =='update')
        {

            return $this->getUpdateProduct($productOp);
        }


    }

    /*
     * param array product
     * return GSC_product
     */
    private function getNewProduct($productOp)
    {

     //   var_dump('insert');
        $price = $this->tax->calculate($productOp['price'], $productOp['tax_class_id'], $this->config->get('config_tax'));


        $country_id = $this->config->get('config_country_id');
        $country = $this->model_localisation_country->getCountry($country_id);

        $product = new GSC_Product();
        // id produktu jako sku
        $product->setSKU($productOp['product_id']);
        $product->setTargetCountry(strtolower($country['iso_code_2']));
        $product->setContentLanguage($this->config->get('config_language'));
        // @todo sprawdzić jak jest niedostepny
        $product->setAvailability('in stock');
        $link = $this->url->link('product/product',  '&product_id=' . $productOp['product_id']);
        $link = str_ireplace(array('/admin','amp;'),'',$link);
        $product->setProductLink($link);
        $product->setTitle($productOp['name']);
        $product->setDescription($productOp['description']);

        // podawanie obrazka z cache nie jest zbyt bezpieczne :)
        if ($productOp['image']) {
            $image_link = HTTP_IMAGE.$productOp['image'];
        } else {
            $image_link = '';
        }


        $product->setImageLink($image_link);
        // główna waluta

        $product->setPrice($price,strtoupper($this->config->get('config_currency')));
        // manufacturer
        $manufacturer = $this->model_catalog_manufacturer->getManufacturer($productOp['manufacturer_id']);
        $product->setBrand(isset($manufacturer['name'])?$manufacturer['name']:'');
        // @todo co to wogole ten MPN
        $product->setMPN($productOp['model']);
        $product->setAdult("false");
        $product->setCondition("new");


        $product->setBatchOperation('insert');

        return $product;
    }


    /*
     * param array product
     * return GSC_product
     */
    private function getUpdateProduct($productOp)
    {
      //  var_dump('update');
        $price = $this->tax->calculate($productOp['price'], $productOp['tax_class_id'], $this->config->get('config_tax'));


        $country_id = $this->config->get('config_country_id');
        $country = $this->model_localisation_country->getCountry($country_id);

        $retrievedProduct = $this->checkProductExists($productOp['product_id'], $country['iso_code_2'], $this->config->get('config_language'));

        $product = new GSC_Product();
        $product->setAtomId($retrievedProduct);
        // id produktu jako sku
        $product->setSKU($productOp['product_id']);
        $product->setTargetCountry(strtolower($country['iso_code_2']));
        $product->setContentLanguage($this->config->get('config_language'));
        // @todo sprawdzić jak jest niedostepny
        $product->setAvailability('in stock');
        $link = $this->url->link('product/product',  '&product_id=' . $productOp['product_id']);
        $link = str_ireplace(array('/admin','amp;'),'',$link);
        $product->setProductLink($link);
        $product->setTitle($productOp['name']);
        $product->setDescription($productOp['description']);

        // podawanie obrazka z cache nie jest zbyt bezpieczne :)
        if ($productOp['image']) {
            $image_link = HTTP_IMAGE.$productOp['image'];
        } else {
            $image_link = '';
        }


        $product->setImageLink($image_link);
        // główna waluta

        $product->setPrice($price,strtoupper($this->config->get('config_currency')));
        // manufacturer
        $manufacturer = $this->model_catalog_manufacturer->getManufacturer($productOp['manufacturer_id']);
        $product->setBrand(isset($manufacturer['name'])?$manufacturer['name']:'');
        // @todo co to wogole ten MPN
        $product->setMPN($productOp['model']);
        $product->setAdult("false");
        $product->setCondition("new");



        $product->setBatchOperation('update');

        return $product;
    }

    private function getDeleteProduct($productOp)
    {
        /*
           * jesli kasujemy produkt to trzeba odzyskac link do niego z google
           */
      //  var_dump('delete');
        $country_id = $this->config->get('config_country_id');
        $country = $this->model_localisation_country->getCountry($country_id);
        $retrievedProduct = $this->checkProductExists($productOp['product_id'], $country['iso_code_2'], $this->config->get('config_language'));


        $dummyDeleteEntry = new GSC_Product();
        $dummyDeleteEntry->setSKU($productOp['product_id']);
        $dummyDeleteEntry->setTargetCountry($country['iso_code_2']);
        $dummyDeleteEntry->setContentLanguage($this->config->get('config_language'));
        $dummyDeleteEntry->setAtomId($retrievedProduct);

        $dummyDeleteEntry->setBatchOperation('delete');

        return $dummyDeleteEntry;
    }


    /*
     * sprawdza czy produkt instniej jesli tak to zwraca atomId
     * return unknown
     */
    private function checkProductExists($id,$country,$language)
    {
        $editLink = $this->_client->getProductUri($id, $country, $language);

        return $editLink;
    }

    /*
     * wysyła liste produktów do wstawinia, updatowanie i usuniecia
     * param array $list - lista id produktów i operacji przewidzanych
     */

    public function sendBatch($list = array())
    {
        $this->load->model('catalog/product');
        $this->load->model('catalog/manufacturer');
        $this->load->model('localisation/country');
        $this->load->model('tool/image');

        error_reporting(E_ALL);
        ini_set('display_errors',1);


        if(!empty($list))
        {
            // Get the user credentials
            $creds = Credentials::get();

            $this->_client = new GSC_Client($creds["merchantId"]);
            $this->_client->login($creds["email"], $creds["password"]);

            $batch = new GSC_ProductList();

            foreach($list as $item)
            {
                if(!isset($item['product_id']) OR !isset($item['operation']))
                {
                    continue;
                }
                else
                {



                    $batch->addEntry($this->buildProduct($item['product_id'],$item['operation']));
                }

            }

            // Finally send the data to the API
            $feed = $this->_client->batch($batch);


            $products = $feed->getProducts();
            foreach ($products as $product) {
                if ($product->getBatchStatus() != '200' &&
                    $product->getBatchStatus() != '201') {
                    $errors = $product->getErrorsFromBatch();


                    $errorArray = $errors->getErrors();

                    foreach ($errorArray as $error) {
                        echo 'Code: ' . $error->getCode() . "\n";
                        echo 'Domain: ' . $error->getDomain() . "\n";
                        echo 'Location: ' . $error->getLocation() . "\n";
                        echo 'Internal Reason: ' . $error->getInternalReason() . "\n";
                    }
                }
            }

        }


    }


}



class Credentials {
    public static function get() {
        return array(
            "merchantId" => "11146588",
            "email" => "google@dawidrza.com",
            "password" => "Q4zPYkNNBv3s",
        );
    }
}