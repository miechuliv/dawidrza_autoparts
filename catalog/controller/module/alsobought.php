<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 11.07.13
 * Time: 17:14
 * To change this template use File | Settings | File Templates.
 */

class ControllerModuleAlsoBought extends Controller{


       protected function index()
       {

           $this->language->load('module/alsobought');
           // miechu also bought
           $this->data['text_also_bought_heading'] = $this->language->get('text_also_bought_heading');
           $this->data['text_also_bought_more'] = $this->language->get('text_also_bought_more');

           $this->data['button_cart'] = $this->language->get('button_cart');

           $this->load->model('module/cross_sell');
           $results=$this->model_module_cross_sell->getAlsoBought($this->data['product_id']);

           if($results){
               foreach ($results as $result) {
                   if ($result['image']) {
                       $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
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

                   if ($this->config->get('config_review_status')) {
                       $rating = (int)$result['rating'];
                   } else {
                       $rating = false;
                   }



                   $this->data['also_bought'][] = array(
                       'product_id' => $result['product_id'],
                       'thumb'   	 => $image,
                       'name'    	 => $result['name'],
                       'price'   	 => $price,
                       'special' 	 => $special,
                       'rating'     => $rating,
                       'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
                       'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                   );

               }
           }
           else
           {
               $this->data['also_bought'] = false;
           }



           if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/alsobought.tpl')) {
               $this->template = $this->config->get('config_template') . '/template/module/alsobought.tpl';
           } else {
               $this->template = 'default/template/module/alsobought.tpl';
           }

           $this->render();


       }


}