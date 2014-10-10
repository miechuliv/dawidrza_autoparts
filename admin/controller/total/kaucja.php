<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 13.11.13
 * Time: 11:31
 * To change this template use File | Settings | File Templates.
 */

class ControllerTotalKaucja extends Controller{
    private $error = array();

    public function index() {

        $this->language->load('total/total');

        $this->document->setTitle('Kaucja');

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('kaucja', $this->request->post);

            $this->session->data['success'] = 'Kaucja zmodyfikowana';

            $this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->data['heading_title'] = 'Kaucja';

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
            'text'      => 'Kaucja',
            'href'      => $this->url->link('total/kaucja', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('total/kaucja', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['kaucja_status'])) {
            $this->data['kaucja_status'] = $this->request->post['kaucja_status'];
        } else {
            $this->data['kaucja_status'] = $this->config->get('kaucja_status');
        }

        if (isset($this->request->post['kaucja_sort_order'])) {
            $this->data['kaucja_sort_order'] = $this->request->post['kaucja_sort_order'];
        } else {
            $this->data['kaucja_sort_order'] = $this->config->get('kaucja_sort_order');
        }

        $this->template = 'total/kaucja.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'total/kaucja')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}