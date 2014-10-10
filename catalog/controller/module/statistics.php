<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 12.07.13
 * Time: 09:15
 * To change this template use File | Settings | File Templates.
 */

class ControllerModuleStatistics extends Controller{

       protected function index()
       {
             $this->load->model('checkout/statistics');

             $this->load->language('module/statistics');

             $this->data['products'] = $this->model_checkout_statistics->getLatestFromCart(5);

             $this->data['button_cart'] = $this->language->get('button_cart');

             $this->data['title'] = $this->language->get('title');


           if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/statistics.tpl')) {
               $this->template = $this->config->get('config_template') . '/template/module/statistics.tpl';
           } else {
               $this->template = 'default/template/module/statistics.tpl';
           }

           $this->render();
       }

}