<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 28.03.14
 * Time: 10:09
 * To change this template use File | Settings | File Templates.
 */

class ControllerTotalGrupyDruku extends Controller{

    public function index() {
        $this->language->load('total/grupy_druku');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('grupy_druku', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');

        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_total'),
            'href'      => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('total/grupy_druku', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('total/grupy_druku', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['grupy_druku_status'])) {
            $this->data['grupy_druku_status'] = $this->request->post['grupy_druku_status'];
        } else {
            $this->data['grupy_druku_status'] = $this->config->get('grupy_druku_status');
        }

        if (isset($this->request->post['grupy_druku_sort_order'])) {
            $this->data['grupy_druku_sort_order'] = $this->request->post['grupy_druku_sort_order'];
        } else {
            $this->data['grupy_druku_sort_order'] = $this->config->get('grupy_druku_sort_order');

        }

        $this->load->model('catalog/category');

        $categories = $this->model_catalog_category->getCategories();

        $this->data['categories'] = $categories;

        if (isset($this->request->post['grupy_druku_discounts'])) {
            $this->data['grupy_druku_discounts'] = $this->request->post['grupy_druku_discounts'];
        } else {
            $this->data['grupy_druku_discounts'] = $this->config->get('grupy_druku_discounts');

        }


        $this->template = 'total/grupy_druku.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'total/coupon')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

}