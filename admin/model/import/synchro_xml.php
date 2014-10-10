<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 03.04.14
 * Time: 12:58
 * To change this template use File | Settings | File Templates.
 */

class ModelImportSynchroXml extends Model{

    /**
     * @param $url
     * @param $post
     * @param $update_only
     * @throws Exception
     * Zadaniem synchronizatora jest ustalanie bierzących stanów magazynowych dla produktów katalogu.
     * Sprawdzana jest także zmiana ceny, cena jest automatycznie nadpisywana na nową.
     * Produkty w katalogu hurtowni sa rodzielone wzgledem kolorów tzn . ten sam długopis widnieje w katalogu jako trzy pozycji ponieważ ma rózne kolory
     * W naszym sklepie widnieje on za to jako jedna pozycja z 3 różnymi opcjami, co za tym idzie trzeba ustalac nowy stan magazynowy osobno dla każdej z tych opcji.
     * Na szczescie w przypadku ceny nie ma tego problemu: ten sam produkt w 3 róznych kolorach zawsze kosztuje tyle samo więc nie trzeba sie martwić o ceny opcji, wystarczy tylko aktualizować
     * główną cene produtku
     * Aktualnie nie wiedzieć czemy w update katalogu ( XML ) nie jest dostępna cena ??
     * Cena na sklepie stanowi 0.55 ceny oryginalnej ponieważ mamy zniżkę
     * paramtery w bazie damych
     * original_price = cena z katalogu xml
     * buy_price / price = cena z katalogu * 0.55
     */
    public function synchro($url,$post,$update_only)
    {
        $this->log->write('starting xml catalog synchronization');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_USERPWD, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        $data = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($data, 0, $header_size);
        $body = substr($data, $header_size);
        curl_close($ch);




            if(!$body)
            {
                throw new Exception('no data from: '.$url);
            }

        file_put_contents(__DIR__.'/last_xml.xml',$body);

        $dom = new DOMDocument();
        $dom->loadXML($body);

        $products = $dom->getElementsByTagName('product');

        if(empty($products))
        {
            $this->log->write('nothing to sync');
            throw new Exception('nothing to sync');
        }

        $productsProcessed = 0;
        $productsUpdated = 0;
        $optionsUpdated = 0;

        foreach($products as $product)
        {
            $data = $this->getProduct($product);


            $result = $this->db->query("SELECT * FROM `".DB_PREFIX."product` WHERE
             original_model = '".$data['original_model']."' ");

            $productsProcessed++;

            if($result->num_rows)
            {
                // update productuktu

                $this->updateProduct($result->row,$data,$update_only);
                $productsUpdated++;
            }
            else
            {
                $result = $this->db->query("SELECT * FROM `".DB_PREFIX."product_option_value` WHERE
             original_model = '".$data['original_model']."' ");

                // update stanów magazynowych opcji
                if($result->num_rows)
                {

                        $this->updateProductOption($result->row,$data,$update_only);
                        $optionsUpdated++;


                }
            }

            $this->log->write('done syncing, products processed : '.$productsProcessed.' , updated: '.$productsUpdated.' , options updated: '.$optionsUpdated);

        }
    }

    public function updateProduct($product,$data,$update_only)
    {

            // sprawdzamy czy zmieniał się dostępność albo cena albo czas dostawy
            if($update_only)
            {

            }
            else
            {


                if(isset($data['price']))
                {
                    // zapisujemy dostępność, cene i czas_dostawy,
                    $this->db->query("UPDATE `".DB_PREFIX."product` SET

                   quantity = '".(int)$data['quantity']."',
                   price = '".(0.55*(float)$data['price'])."',
                   buy_price = '".(0.55*(float)$data['buy_price'])."',
                    original_price = '".((float)$data['buy_price'])."'

                    WHERE product_id = '".(int)$product['product_id']."' ");
                }
                else
                {

                    $this->db->query("UPDATE `".DB_PREFIX."product` SET

                   quantity = '".(int)$data['quantity']."'

                    WHERE product_id = '".(int)$product['product_id']."' ");
                }

                if(isset($data['delivery_time']) && $data['delivery_time'])
                {

                    $date = new \DateTime($data['delivery_time']);
                    $this->db->query("UPDATE `".DB_PREFIX."product` SET

                   `delivery_time` = '".$this->db->escape($date->format('Y-m-d'))."'

                    WHERE product_id = '".(int)$product['product_id']."' ");
                }
            }


    }

    public function updateProductOption($option,$data,$update_only)
    {



                $this->db->query("UPDATE `".DB_PREFIX."product_option_value` SET

                   quantity = '".(int)$data['quantity']."'

                    WHERE product_option_value_id = '".(int)$option['product_option_value_id']."' ");



    }

    public function getProduct(DOMElement $product)
    {
        $data = array();
        $original_model = $product->getElementsByTagName('kod');

        if(is_object($original_model->item(0)))
        {
            $data['original_model'] = $original_model->item(0)->nodeValue;
        }

        $price = $product->getElementsByTagName('cena5');

        if(is_object($price->item(0)))
        {
            $data['price'] = $price->item(0)->nodeValue;
        }

        $delivery_time = $product->getElementsByTagName('data_dostawy');

        if(is_object($delivery_time->item(0)))
        {
            $data['delivery_time'] = $delivery_time->item(0)->nodeValue;
        }

        $quantity = $product->getElementsByTagName('stan_magazynowy');

        if(is_object($quantity->item(0)))
        {
            $data['quantity'] = $quantity->item(0)->nodeValue;
        }



        return $data;
    }
}