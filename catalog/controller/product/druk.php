<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 10.04.14
 * Time: 15:20
 * To change this template use File | Settings | File Templates.
 */

class ControllerProductDruk extends Controller{


        public function index()
        {
		
		$this->language->load('product/druk');

            // 5 - plik, 6 - komentarz
           /* for($i = 1; $i < 775; $i++)
            {
                $this->db->query("INSERT INTO `".DB_PREFIX."product_option` SET `option_id` = 5 , `product_id` = '".(int)$i."', required = 0 ");
                $this->db->query("INSERT INTO `".DB_PREFIX."product_option` SET `option_id` = 6 , `product_id` = '".(int)$i."', required = 0 ");
            }*/


            if(isset($this->request->get['product_id']))
            {
                $product_id = $this->request->get['product_id'];
            }
            else
            {
                $this->redirect($this->url->link('checkout/cart'));
            }

            $this->load->model('catalog/product');

            $this->data['cart'] = $this->url->link('checkout/cart');
            $this->data['product'] = $this->url->link('product/product','&product_id='.$product_id);

            $file = false;

            $this->data['error'] = false;

            if (($this->request->server['REQUEST_METHOD'] == 'POST') && (!isset($this->request->post['file_checkbox']) OR ($file = $this->upload($this->request->post))!==false)) {

                    // dodajemy dwie nowe opcje do produktu
                    foreach($this->cart->getProducts() as $key => $product)
                    {


                        if($product_id == $product['product_id'])
                        {

                            // dekodujemy key żeby odnależć poprzednie opcje i dołączyć obecne
                            $t = explode(':',$product['key']);


                            if(isset($t[1]))
                            {
                                $options = unserialize(base64_decode($t[1]));
                            }
                            else
                            {
                                $options = array();
                            }




                            // musimy teraz ogarnąć jaki jest product_option_id dla option_id = 5 oraz 6 i następnie dodać wartości

                            $plik_opt = $this->model_catalog_product->getProductOptionByOption($product['product_id'],5,$this->config->get('config_language_id'));
                            $comment_opt = $this->model_catalog_product->getProductOptionByOption($product['product_id'],6,$this->config->get('config_language_id'));

                            if($file)
                            {
                                $options[$plik_opt['product_option_id']] = $file;
                            }


                            if($this->request->post['comment'])
                            {
                                $options[$comment_opt['product_option_id']] = $this->request->post['comment'];
                            }



                            // quantity
                            $quantity = $product['quantity'];

                            // usuwamy obecny produkt i dodajemy nowy
                            $this->cart->remove($product['key']);

                            // dodajemy nowy z poprawionymi opcjami
                            $this->cart->add($product_id,$quantity,$options);
                            // przechodzimy do koszyka
                            $this->redirect($this->url->link('checkout/cart'));

                        }
                    }

            }

            if(isset($this->request->post['comment']))
            {
                $this->data['comment'] = $this->request->post['comment'];
            }
            else
            {
                $this->data['comment'] = false;
            }

            if(isset($this->request->post['file_checkbox']))
            {
                $this->data['file_checkbox'] = $this->request->post['file_checkbox'];
            }
            else
            {
                $this->data['file_checkbox'] = false;
            }




            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/druk.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/product/druk.tpl';
            } else {
                $this->template = 'default/template/product/druk.tpl';
            }



            $this->children = array(
                'common/column_left',
                'common/column_right',
                'common/content_bottom',
                'common/content_top',
                'common/footer',
                'common/header'
            );



            $this->response->setOutput($this->render());
        }

    private function upload() {

        $this->data['error'] = false;

        if (!empty($this->request->files['file']['name'])) {
            $filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));

            if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
                $this->data['error'] = $this->language->get('error_filename');
            }

            // Allowed file extension types
            $allowed = array();

            $filetypes = explode("\n", $this->config->get('config_file_extension_allowed'));

            foreach ($filetypes as $filetype) {
                $allowed[] = trim($filetype);
            }

            if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
                $this->data['error'] = $this->language->get('error_filetype');
            }

            // Allowed file mime types
            $allowed = array();

            $filetypes = explode("\n", $this->config->get('config_file_mime_allowed'));



            foreach ($filetypes as $filetype) {
                $allowed[] = trim($filetype);
            }


            if (!in_array($this->request->files['file']['type'], $allowed)) {

                $this->data['error'] = $this->language->get('error_filetype');
            }

            if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
                $this->data['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
            }
        } else {
            $this->data['error'] = $this->language->get('error_upload');
        }



        if (!$this->data['error'] && is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {

            $file = basename($filename) . '.' . md5(mt_rand());

            // Hide the uploaded file name so people can not link to it directly.
            $res = $this->encryption->encrypt($file);

            move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);

            return  $res;


        }

        return false;
    }
}