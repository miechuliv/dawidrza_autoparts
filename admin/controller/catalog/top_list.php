<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 14.04.14
 * Time: 16:05
 * To change this template use File | Settings | File Templates.
 */

class ControllerCatalogTopList extends Controller{

        public function index()
        {
            $this->load->language('catalog/top_list');

            $this->load->model('catalog/top_list');
            $this->load->model('catalog/top_list_product');
            $this->load->model('catalog/top_list_description');

            if(isset($this->request->get['top_list_id']))
            {
                $this->getForm();
            }
            else
            {
                $this->getList();
            }


        }

        public function  getList()
        {
            $top_lists = $this->model_catalog_top_list->getTopLists();

            error_reporting(E_ALL);
            ini_set('display_errors', '1');

            $this->data['top_lists'] = array();

            $this->data['insert'] = $this->url->link('catalog/top_list/insert', 'token=' . $this->session->data['token'] , 'SSL');

            $this->data['delete'] = $this->url->link('catalog/top_list/delete', 'token=' . $this->session->data['token'] , 'SSL');

            $this->data['button_insert'] = $this->language->get('button_insert');
            $this->data['button_delete'] = $this->language->get('button_delete');

            foreach($top_lists as $top_list)
            {
                $description = $this->model_catalog_top_list_description->getTopListDescription($top_list['top_list_id'],$this->config->get('config_language_id'));

                    $this->data['top_lists'][] = array(
                            'top_list_id' => $top_list['top_list_id'],
                            'name' => $description['name'],
                            'sort_order' => $top_list['sort_order'],
                            'active' => $top_list['active'],
                            'action' => array(
                                array(
                                'text' => $this->language->get('text_edit'),
                                'href' => $this->url->link('catalog/top_list/update', 'token=' . $this->session->data['token'] . '&top_list_id=' . $top_list['top_list_id'] , 'SSL')
                            ),
                            )
                    )   ;
            }


            $this->template = 'catalog/top_list_list.tpl';
            $this->children = array(
                'common/header',
                'common/footer'
            );

            $this->response->setOutput($this->render());
        }



        public function getForm()
        {

            $this->load->model('localisation/language');
            $this->data['languages'] = $this->model_localisation_language->getLanguages();

            $this->data['button_save'] = $this->language->get('button_save');
            $this->data['button_cancel'] = $this->language->get('button_cancel');
            $this->data['cancel'] = $this->url->link('catalog/top_list', 'token=' . $this->session->data['token'] , 'SSL');


            error_reporting(E_ALL);
            ini_set('display_errors', '1');

            $this->data['token'] = $this->session->data['token'];

            if(isset($this->request->get['top_list_id']))
            {
                $top_list = $this->model_catalog_top_list->getTopList($this->request->get['top_list_id']);
            }
            else
            {
                $top_list = array();
            }

            if(isset($this->request->get['top_list_id']))
            {
                $this->data['save'] = $this->url->link('catalog/top_list/update', '&top_list_id='.  $this->request->get['top_list_id']  .'token=' . $this->session->data['token'] , 'SSL');
            }
            else
            {
                $this->data['save'] = $this->url->link('catalog/top_list/insert', 'token=' . $this->session->data['token'] , 'SSL');
            }



            $fields = array(
                'top_list_id',
                'sort_order',
                'active',
                'limit'



            );



            $this->setFields($fields,$top_list);

            if(isset($this->request->post['description']))
            {
                $this->data['description'] = $this->request->post['description'];
            }
            if(isset($this->request->get['top_list_id']))
            {
                $this->data['description'] = $this->model_catalog_top_list_description->getTopListDescriptions($this->request->get['top_list_id']);

            }
            else
            {
                $this->data['description'] = array();
            }

            if(isset($this->request->post['products']))
            {
                $this->data['products'] = $this->request->post['products'];
            }
            if(isset($this->request->get['top_list_id']))
            {
                $this->data['products'] = $this->model_catalog_top_list_product->getTopListProducts($this->request->get['top_list_id']);
            }
            else
            {
                $this->data['products'] = array();
            }

            $this->load->model('catalog/product');


            foreach($this->data['products'] as $key => $product)
            {
                $p = $this->model_catalog_product->getProduct($product['product_id']);

                $this->data['products'][$key]['name'] = $p['name'];
            }




            if (!isset($this->request->get['top_list_id'])) {
                $this->data['action'] = $this->url->link('catalog/top_list/insert', 'token=' . $this->session->data['token'] , 'SSL');
            } else {
                $this->data['action'] = $this->url->link('catalog/top_list/update', 'token=' . $this->session->data['token'] . '&top_list_id=' . $this->request->get['top_list_id'] , 'SSL');
            }





            $this->template = 'catalog/top_list_form.tpl';
            $this->children = array(
                'common/header',
                'common/footer'
            );

            $this->response->setOutput($this->render());
        }

        public function insert()
        {
            $this->load->language('catalog/top_list');

            $this->load->model('catalog/top_list');
            $this->load->model('catalog/top_list_product');
            $this->load->model('catalog/top_list_description');


            $this->load->model('catalog/top_list');

            if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
                $top_list_id = $this->model_catalog_top_list->add($this->request->post);

                $this->session->data['success'] = $this->language->get('text_success');



                $this->redirect($this->url->link('catalog/top_list', 'token=' . $this->session->data['token'] , 'SSL'));
            }

            $this->getForm();
        }

        public function update()
        {


            $this->load->language('catalog/top_list');

            $this->load->model('catalog/top_list');
            $this->load->model('catalog/top_list_product');
            $this->load->model('catalog/top_list_description');

            $this->load->model('catalog/top_list');

            if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
                $this->model_catalog_top_list->edit($this->request->get['top_list_id'],$this->request->post);

                $this->session->data['success'] = $this->language->get('text_success');



                $this->redirect($this->url->link('catalog/top_list', 'token=' . $this->session->data['token'] , 'SSL'));
            }

            $this->getForm();
        }

        public function delete()
        {
            $this->load->model('catalog/top_list');

            if (isset($this->request->post['selected'])) {
                foreach ($this->request->post['selected'] as $top_list_id) {
                    $this->model_catalog_top_list->delete($top_list_id);
                }

                $this->session->data['success'] = $this->language->get('text_success');


                $this->redirect($this->url->link('catalog/top_list', 'token=' . $this->session->data['token'] , 'SSL'));
            }
        }


}