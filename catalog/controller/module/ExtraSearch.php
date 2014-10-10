<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 12.07.13
 * Time: 10:25
 * To change this template use File | Settings | File Templates.
 */

class ControllerModuleExtraSearch extends Controller{

        protected function index(){
            $this->language->load('module/category');

            if(isset($this->request->get['path']))
            {
                $path = $this->request->get['path'];
            }
            else
            {
                $path = '';
            }

            $this->data['text_price_search']=$this->language->get('text_price_search');
            $this->data['text_manufacturer_search']=$this->language->get('text_manufacturer_search');

            $this->data['text_reset']=$this->language->get('text_reset');

            $this->data['search_action'] = $this->url->link('product/category','path=' . $path );

            // additional search

            $this->load->model('module/options');

            $this->data['options']=$this->model_module_options->getOptions();

            $this->data['symbol_currency'] = $this->currency->getSymbolRight();

            $this->load->model('catalog/manufacturer');

            $this->data['manufacturers']=$this->model_catalog_manufacturer->getManufacturers();

            // code end
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/extrasearch.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/module/extrasearch.tpl';
            } else {
                $this->template = 'default/template/module/extrasearch.tpl';
            }

            $this->render();
        }

}