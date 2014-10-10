<?php

class ModelAllegroAuctions extends Model {

      /*
       * sprawdza czy dany przedmiot ma juz status zakonczony, jesli istnieje i nie jest zakonczony zwraca product_id
       * @param: item_id: string
       * @return product_id: int
       */
      public function checkIfSold($item_id)
      {
           $result = $this->db->query("SELECT * FROM    `allegro_auction_m` WHERE `item_id`='".$item_id."' AND sold_out='0' ");

           if($result->row)
           {
               /*
                *  znaleziono w tabeli i NIE jest zakonczony
                */
               return $result->row['product_id'];
           }
            else
            {
                /*
                 * albo zakonczony albo nie mawogule w tabeli
                 */
                return false;
            }
      }

      /*
       * zapisuje przedmiot jako zakonczony
       * @param: item_id: string
       * @return void
       */
      public function markAsSold($item_id)
      {

          $this->db->query("UPDATE  `allegro_auction_m` SET sold_out='1'  WHERE `item_id`='".$item_id."' ");
      }

      /*
       * dodaje zamuwienie z allegro, do pozniejszego uzytku
       * @param item_id: string, quantity: int
       * @return void
       */
      public function addOrder($item_id,$quantity,$user_id)
      {
          $this->db->query("INSERT INTO `allegro_order_m` SET `item_id`='".$item_id."', `quantity`='".(int)$quantity."',  `user_id`='".$user_id."' ");


      }

      public function getProductByAuction($item_id)
      {
          $result = $this->db->query("SELECT * FROM    `allegro_auction_m` WHERE `item_id`='".$item_id."'  ");

          if($result->row)
          {
               return $result->row;
          }

          return false;
      }

    public function saveTransactionAsOrder($transaction,$allegro)
    {

            $items = $transaction->{'post-buy-form-items'};

            $this->load->model('localisation/currency');

            $this->load->model('catalog/product');

            $this->load->model('sale/order');

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
            $currency_allegro = $this->getCurrencyByCode('PLN');

            $currency_allegro_id = $currency_allegro['currency_id'];
            $currency_allegro_code = $currency_allegro['code'];
            $currency_allegro_value = $currency_allegro['value'];

          $order_items = array();


           foreach($items as $item)
           {
               // wyczyscielem table transakcji wice trzeba cos tam wpisac an pale albo zrobic jakies aukcje
               $product = $this->getProductByAuction($item->{'post-buy-form-it-id'});

               if(isset($product['product_id'])){
                   $product_data = $this->model_catalog_product->getProduct($product['product_id']);
                   // modyfikacja ceny na to z ebaya



                   if(!empty($product_data))
                   {
                       $product_data['price'] = $this->currency->convert((int)$item->{'post-buy-form-it-price'},$currency_allegro_value,$currency_value,true);
                       $product_data['quantity'] =  (int)$item->{'post-buy-form-it-quantity'};
                       $product_data['total'] = $this->currency->convert((int)$item->{'post-buy-form-it-price'},$currency_allegro_value,$currency_value,true);
                       $product_data['tax'] = NULL;
                       $order_items[] = $product_data;
                   }

               }
           }

           // jesli nie znaleziono zadnego przedmiotu
           if(empty($order_items))
           {
                return false;
           }

            // @todo $ship_addres['Country'], przetłumaczyć na opencartowe country
            $country_id = 170;

            $language_id = 2;


            // totalsy na sztywno
            $totals = array();
            $totals[] = array(
                'code' => 'sub_total',
                'title' => 'Suma cześciowa',
                'text' => ((int)$transaction->{'post-buy-form-amount'} - (int)$transaction->{'post-buy-form-postage-amount'}).' '.'PLN',
                'value'=> $this->currency->convert(((int)$transaction->{'post-buy-form-amount'} - (int)$transaction->{'post-buy-form-postage-amount'}),$currency_allegro_value,$currency_value,true),
                'sort_order' => 1
            );

            $totals[] = array(
                'code' => 'shipping',
                'title' => 'allegro',
                'text'=> (int)$transaction->{'post-buy-form-postage-amount'}.' '.'PLN',
                'value' => $this->currency->convert((int)$transaction->{'post-buy-form-postage-amount'},$currency_allegro_value,$currency_value,true),
                'sort_order' => 2
            );

            $totals[] = array(
                'code' => 'total',
                'title' => 'Razem:',
                'text'=> $transaction->{'post-buy-form-amount'}.' '.'PLN',
                'value' => $this->currency->convert((int)$transaction->{'post-buy-form-amount'},$currency_allegro_value,$currency_value,true),
                'sort_order' => 3
            );

            $shipping_data = $transaction->{'post-buy-form-shipment-address'};

            $full_name = $shipping_data->{'post-buy-form-adr-full-name'};

            $tmp = explode(' ',$full_name);

            $f_name = array_pop($tmp);

           $l_name = array_shift($tmp);

            $pay_types = array(
                'p' => 'PayU',
                'co' => 'Checkout Payu',
                'ro' => 'Raty Payu',
                'collect_no_delivery' => 'Płatność przy odbiorze',
                'wire_transfer' => 'Przelew bankowy',
                'not_specified' => 'Nie podano',
            );

            if(isset($pay_types[$transaction->{'post-buy-form-pay-type'}]))
            {
                 $payment_method = $pay_types[$transaction->{'post-buy-form-pay-type'}];
            }
            else
            {
                $payment_method = 'Nie podano';
            }

            // shipping method
            $shipping_method = $allegro->getShipmentName($transaction->{'post-buy-form-shipment-id'});

            // order-status
            $order_status_array = array(
                  'Rozpoczęta' => 19,
                  'Anulowana' => 23,
                  'Odrzucona' => 21,
                  'Zakończona' => 20,
                  'Wycofana' => 22,
            );

            if(isset($order_status_array[$transaction->{'post-buy-form-pay-status'}]))
            {
                $order_status = $order_status_array[$transaction->{'post-buy-form-pay-status'}];
            }
            else
            {
                $order_status = 2;
            }

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
                'firstname' => $f_name,
                'lastname' => $l_name,
                // moze skads sie da zabrac
                'email' => isset($shipping_data->{'post-buy-form-buyer-email'}) ? $shipping_data->{'post-buy-form-buyer-email'}: NULL,
                'telephone' => $shipping_data->{'post-buy-form-adr-phone'},
                'fax' => '',

                'payment_firstname' => $f_name,
                'payment_lastname' => $l_name,
                'payment_company' => '',
                'payment_tax_id' => 0,
                'payment_address_1' => $shipping_data->{'post-buy-form-adr-street'},
                'payment_address_2' => NULL,
                'payment_city' => $shipping_data->{'post-buy-form-adr-city'},
                'payment_postcode' => $shipping_data->{'post-buy-form-adr-postcode'},
                'payment_method' => $payment_method,
                'payment_code' => '',
                'payment_company_id' => NULL,

                'shipping_firstname' => $f_name,
                'shipping_lastname' => $l_name,
                'shipping_company' => '',
                'shipping_tax_id' => NULL,
                'shipping_address_1' => $shipping_data->{'post-buy-form-adr-street'},
                'shipping_address_2' => NULL,
                'shipping_city' => $shipping_data->{'post-buy-form-adr-city'},
                'shipping_postcode' => $shipping_data->{'post-buy-form-adr-postcode'},
                'shipping_method' => $shipping_method,
                'shipping_code' => '',
                'comment' => 'Zamówienie allegro, nick użytkownika allegro: '.$transaction->{'post-buy-form-buyer-login'}.' <br/>'.$transaction->{'post-buy-form-gd-additional-info'},
                // na sztywno, 2 - zapłacono, zakładam ze tu przechodza tylko zaplacone zamowienia po ukonczeniu czekautu na ebay
                'order_status_id' => $order_status,
                'affiliate_id' => NULL,

                'order_product' => $order_items,
                'order_total' => $totals,

            );

            $this->model_sale_order->addOrder($data,false,true);

            return true;

    }

    private function getCurrencyByCode($currency)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape($currency) . "'");

        return $query->row;
    }

      /*
       * obniza stan produktu
       * @param product_id: int, quantity: int
       * @return void
       */
      public function lowerProductQuantity($product_id,$quantity)
      {

          $this->db->query("UPDATE  `product` SET quantity=quantity - '".(int)$quantity."'  WHERE `product_id`='".(int)$product_id."' ");
      }

      /*
       * sprawdza  item_id oraz sold_quantity oraz user_id zeby rozruznic srawdzone zamuwienia od nie sprawdzonych
       * @param product_id: int, quantity: int
       * @return bool
       */
      public function findOrderByItemIdAndQuantityAndUser($item_id,$quantity,$user_id)
      {
          $result = $this->db->query("SELECT * FROM `allegro_order_m` WHERE `item_id`='".$item_id."' AND `quantity`='".(int)$quantity."' AND `user_id`='".$user_id."' ");

          if($result->row)
          {
              /*
               * jest juz wpis dla tego item_id i tej liczby sprzedanych
               */
              return true;
          }
          else
          {
              return false;
          }
      }


}

?>