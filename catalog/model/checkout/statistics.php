<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 11.07.13
 * Time: 19:41
 * To change this template use File | Settings | File Templates.
 */

class ModelCheckoutStatistics extends Model{


        public function saveProducts($products)
        {
               try
                 {

                     if(!$products)
                     {
                         throw new Exception('Brak produktów do zliczenia!');
                     }

                     $location = geoLocations::getInstance()->getCityByIp();

                     $location['cityName'] = geoLocations::getInstance()->translate($location['cityName']);

                     $location['countryName'] = geoLocations::getInstance()->translate($location['countryName']);

                     foreach ($products as $product) {

                          $this->db->query("INSERT INTO cart_statistics SET product_id='".$product['product_id']."' , city='".$location['cityName']."', country='".$location['countryName']."' , date=NOW()  ");

                     }


                 }catch(Exception $e){
                   //   echo $e->getMessage();
                 }
        }

       public function getLatestFromCart($limit)
       {

              $result = $this->db->query("SELECT * FROM cart_statistics  ORDER BY date DESC LIMIT ".$limit." ");

              $this->load->model('catalog/product');

              if($result)
              {
                  $data = array();

                   foreach($result->rows as $row )
                   {
                       $result = $this->model_catalog_product->getProduct($row['product_id']);

                       if($result)
                       {

                           if ($result['image']) {
                               $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                           } else {
                               $image = false;
                           }



                           if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                               $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                           } else {
                               $price = false;
                           }



                           if ((float)$result['special']) {
                               $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
                           } else {
                               $special = false;
                           }

                           if ($this->config->get('config_tax')) {
                               $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
                           } else {
                               $tax = false;
                           }

                           if ($this->config->get('config_review_status')) {
                               $rating = (int)$result['rating'];
                           } else {
                               $rating = false;
                           }

                           // CodeHouse: get additional image
                           $results = $this->model_catalog_product->getProductImages($result['product_id']);


                           if ( isset($results[0]['image']) ) {
                               $additional_image = $this->model_tool_image->resize($results[0]['image'] , $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')) ;
                           } else { $additional_image = false ; }
                           // CodeHouse: END
                           $url = '';
                           $data[] = array(
                               'product_id'  => $result['product_id'],
                               'thumb'       => $image,
                               // CodeHouse: get additional image
                               'additional_image' => $additional_image,
                               // CodeHouse: END
                               'name'        => $result['name'],
                               'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..',
                               'price'       => $price,
                               'special'     => $special,
                               'tax'         => $tax,
                               'rating'      => $result['rating'],
                               'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
                               'href'        => $this->url->link('product/product',  'product_id=' . $result['product_id'] . $url),
                               'city' => $row['city'],
                               'country' => $row['country'],
                           );


                       }
                       else{
                           $this->logger->info("Nie udało się odnależć produktu:".$row['product_id']);
                       //    throw new exception("Nie udało się odnależć produktu:".$row['product_id']);
                       }
                   }

                   return $data;


              }
              else
              {
                  $this->logger->info("Brak produktów do wyświelenia");
               //   throw new exception("Brak produktów do wyświelenia");
              }
       }
}