<?php

/**
 * giropay.de payment gateway for Opencart by Extensa Web Development
 *
 * Copyright © 2012-2013 Extensa Web Development Ltd. All Rights Reserved.
 * This file may not be redistributed in whole or significant part.
 * This copyright notice MUST APPEAR in all copies of the script!
 *
 * @author 		Extensa Web Development Ltd. (www.extensadev.com)
 * @copyright	Copyright (c) 2012-2013, Extensa Web Development Ltd.
 * @package 	giropay.de payment gateway
 * @link		http://www.opencart.com/index.php?route=extension/extension/info&extension_id=8683
 */

class ControllerPaymentGiropay extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/giropay');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('giropay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');

		$this->data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
		$this->data['entry_project_id'] = $this->language->get('entry_project_id');
		$this->data['entry_secret'] = $this->language->get('entry_secret');
		$this->data['entry_3d_secure'] = $this->language->get('entry_3d_secure');
		$this->data['entry_paymode'] = $this->language->get('entry_paymode');
		$this->data['entry_cc_type'] = $this->language->get('entry_cc_type');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_giropay'] = $this->language->get('tab_giropay');
		$this->data['tab_direct'] = $this->language->get('tab_direct');
		$this->data['tab_credit'] = $this->language->get('tab_credit');
		$this->data['tab_paypal'] = $this->language->get('tab_paypal');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['merchant_id'])) {
			$this->data['error_merchant_id'] = $this->error['merchant_id'];
		} else {
			$this->data['error_merchant_id'] = '';
		}

		if (isset($this->error['project_id'])) {
			$this->data['error_project_id'] = $this->error['project_id'];
		} else {
			$this->data['error_project_id'] = '';
		}

		if (isset($this->error['secret'])) {
			$this->data['error_secret'] = $this->error['secret'];
		} else {
			$this->data['error_secret'] = '';
		}

		if (isset($this->error['direct_project_id'])) {
			$this->data['error_direct_project_id'] = $this->error['direct_project_id'];
		} else {
			$this->data['error_direct_project_id'] = '';
		}

		if (isset($this->error['direct_secret'])) {
			$this->data['error_direct_secret'] = $this->error['direct_secret'];
		} else {
			$this->data['error_direct_secret'] = '';
		}

		if (isset($this->error['credit_project_id'])) {
			$this->data['error_credit_project_id'] = $this->error['credit_project_id'];
		} else {
			$this->data['error_credit_project_id'] = '';
		}

		if (isset($this->error['credit_secret'])) {
			$this->data['error_credit_secret'] = $this->error['credit_secret'];
		} else {
			$this->data['error_credit_secret'] = '';
		}

		if (isset($this->error['credit_cc_type'])) {
			$this->data['error_credit_cc_type'] = $this->error['credit_cc_type'];
		} else {
			$this->data['error_credit_cc_type'] = '';
		}

		if (isset($this->error['paypal_project_id'])) {
			$this->data['error_paypal_project_id'] = $this->error['paypal_project_id'];
		} else {
			$this->data['error_paypal_project_id'] = '';
		}

		if (isset($this->error['paypal_secret'])) {
			$this->data['error_paypal_secret'] = $this->error['paypal_secret'];
		} else {
			$this->data['error_paypal_secret'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/giropay', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		if (isset($this->request->get['type'])) {
			$this->data['type'] = $this->request->get['type'];
		} else {
			$this->data['type'] = '';
		}

		$this->data['action'] = $this->url->link('payment/giropay', 'token=' . $this->session->data['token'] . '&type=' . $this->data['type'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['giropay_merchant_id'])) {
			$this->data['giropay_merchant_id'] = $this->request->post['giropay_merchant_id'];
		} else {
			$this->data['giropay_merchant_id'] = $this->config->get('giropay_merchant_id');
		}

		if (isset($this->request->post['giropay_project_id'])) {
			$this->data['giropay_project_id'] = $this->request->post['giropay_project_id'];
		} else {
			$this->data['giropay_project_id'] = $this->config->get('giropay_project_id');
		}

		if (isset($this->request->post['giropay_secret'])) {
			$this->data['giropay_secret'] = $this->request->post['giropay_secret'];
		} else {
			$this->data['giropay_secret'] = $this->config->get('giropay_secret');
		}

		if (isset($this->request->post['giropay_description'])) {
			$this->data['giropay_description'] = $this->request->post['giropay_description'];
		} else {
			$this->data['giropay_description'] = $this->config->get('giropay_description');
		}

		if (isset($this->request->post['giropay_total'])) {
			$this->data['giropay_total'] = $this->request->post['giropay_total'];
		} else {
			$this->data['giropay_total'] = $this->config->get('giropay_total');
		}

		if (isset($this->request->post['giropay_order_status_id'])) {
			$this->data['giropay_order_status_id'] = $this->request->post['giropay_order_status_id'];
		} else {
			$this->data['giropay_order_status_id'] = $this->config->get('giropay_order_status_id');
		}

		if (isset($this->request->post['giropay_geo_zone_id'])) {
			$this->data['giropay_geo_zone_id'] = $this->request->post['giropay_geo_zone_id'];
		} else {
			$this->data['giropay_geo_zone_id'] = $this->config->get('giropay_geo_zone_id');
		}

		if (isset($this->request->post['giropay_status'])) {
			$this->data['giropay_status'] = $this->request->post['giropay_status'];
		} else {
			$this->data['giropay_status'] = $this->config->get('giropay_status');
		}

		if (isset($this->request->post['giropay_sort_order'])) {
			$this->data['giropay_sort_order'] = $this->request->post['giropay_sort_order'];
		} else {
			$this->data['giropay_sort_order'] = $this->config->get('giropay_sort_order');
		}

		if (isset($this->request->post['giropay_direct_project_id'])) {
			$this->data['giropay_direct_project_id'] = $this->request->post['giropay_direct_project_id'];
		} else {
			$this->data['giropay_direct_project_id'] = $this->config->get('giropay_direct_project_id');
		}

		if (isset($this->request->post['giropay_direct_secret'])) {
			$this->data['giropay_direct_secret'] = $this->request->post['giropay_direct_secret'];
		} else {
			$this->data['giropay_direct_secret'] = $this->config->get('giropay_direct_secret');
		}

		if (isset($this->request->post['giropay_direct_description'])) {
			$this->data['giropay_direct_description'] = $this->request->post['giropay_direct_description'];
		} else {
			$this->data['giropay_direct_description'] = $this->config->get('giropay_direct_description');
		}

		if (isset($this->request->post['giropay_direct_total'])) {
			$this->data['giropay_direct_total'] = $this->request->post['giropay_direct_total'];
		} else {
			$this->data['giropay_direct_total'] = $this->config->get('giropay_direct_total');
		}

		if (isset($this->request->post['giropay_direct_order_status_id'])) {
			$this->data['giropay_direct_order_status_id'] = $this->request->post['giropay_direct_order_status_id'];
		} else {
			$this->data['giropay_direct_order_status_id'] = $this->config->get('giropay_direct_order_status_id');
		}

		if (isset($this->request->post['giropay_direct_geo_zone_id'])) {
			$this->data['giropay_direct_geo_zone_id'] = $this->request->post['giropay_direct_geo_zone_id'];
		} else {
			$this->data['giropay_direct_geo_zone_id'] = $this->config->get('giropay_direct_geo_zone_id');
		}

		if (isset($this->request->post['giropay_direct_status'])) {
			$this->data['giropay_direct_status'] = $this->request->post['giropay_direct_status'];
		} else {
			$this->data['giropay_direct_status'] = $this->config->get('giropay_direct_status');
		}

		if (isset($this->request->post['giropay_direct_sort_order'])) {
			$this->data['giropay_direct_sort_order'] = $this->request->post['giropay_direct_sort_order'];
		} else {
			$this->data['giropay_direct_sort_order'] = $this->config->get('giropay_direct_sort_order');
		}

		if (isset($this->request->post['giropay_credit_project_id'])) {
			$this->data['giropay_credit_project_id'] = $this->request->post['giropay_credit_project_id'];
		} else {
			$this->data['giropay_credit_project_id'] = $this->config->get('giropay_credit_project_id');
		}

		if (isset($this->request->post['giropay_credit_secret'])) {
			$this->data['giropay_credit_secret'] = $this->request->post['giropay_credit_secret'];
		} else {
			$this->data['giropay_credit_secret'] = $this->config->get('giropay_credit_secret');
		}

		if (isset($this->request->post['giropay_credit_3d_secure'])) {
			$this->data['giropay_credit_3d_secure'] = $this->request->post['giropay_credit_3d_secure'];
		} else {
			$this->data['giropay_credit_3d_secure'] = $this->config->get('giropay_credit_3d_secure');
		}

		if (isset($this->request->post['giropay_credit_paymode'])) {
			$this->data['giropay_credit_paymode'] = $this->request->post['giropay_credit_paymode'];
		} else {
			$this->data['giropay_credit_paymode'] = $this->config->get('giropay_credit_paymode');
		}

		$this->data['paymodes'] = array(
			array('value' => 'payonly', 'name' => $this->language->get('text_payonly')),
			array('value' => 'payplus', 'name' => $this->language->get('text_payplus')),
			array('value' => 'fullpay', 'name' => $this->language->get('text_fullpay'))
		);

		if (isset($this->request->post['giropay_credit_cc_type'])) {
			$this->data['giropay_credit_cc_type'] = $this->request->post['giropay_credit_cc_type'];
		} elseif ($this->config->get('giropay_credit_cc_type')) {
			$this->data['giropay_credit_cc_type'] = $this->config->get('giropay_credit_cc_type');
		} else {
			$this->data['giropay_credit_cc_type'] = array();
		}

		$this->data['cc_types'] = array(
			array('value' => 'M', 'name' => 'MasterCard'),
			array('value' => 'V', 'name' => 'Visa'),
			array('value' => 'A', 'name' => 'American Express'),
			array('value' => 'C', 'name' => 'Diners'),
			array('value' => 'J', 'name' => 'JCB')
		);

		if (isset($this->request->post['giropay_credit_description'])) {
			$this->data['giropay_credit_description'] = $this->request->post['giropay_credit_description'];
		} else {
			$this->data['giropay_credit_description'] = $this->config->get('giropay_credit_description');
		}

		if (isset($this->request->post['giropay_credit_total'])) {
			$this->data['giropay_credit_total'] = $this->request->post['giropay_credit_total'];
		} else {
			$this->data['giropay_credit_total'] = $this->config->get('giropay_credit_total');
		}

		if (isset($this->request->post['giropay_credit_order_status_id'])) {
			$this->data['giropay_credit_order_status_id'] = $this->request->post['giropay_credit_order_status_id'];
		} else {
			$this->data['giropay_credit_order_status_id'] = $this->config->get('giropay_credit_order_status_id');
		}

		if (isset($this->request->post['giropay_credit_geo_zone_id'])) {
			$this->data['giropay_credit_geo_zone_id'] = $this->request->post['giropay_credit_geo_zone_id'];
		} else {
			$this->data['giropay_credit_geo_zone_id'] = $this->config->get('giropay_credit_geo_zone_id');
		}

		if (isset($this->request->post['giropay_credit_status'])) {
			$this->data['giropay_credit_status'] = $this->request->post['giropay_credit_status'];
		} else {
			$this->data['giropay_credit_status'] = $this->config->get('giropay_credit_status');
		}

		if (isset($this->request->post['giropay_credit_sort_order'])) {
			$this->data['giropay_credit_sort_order'] = $this->request->post['giropay_credit_sort_order'];
		} else {
			$this->data['giropay_credit_sort_order'] = $this->config->get('giropay_credit_sort_order');
		}

		if (isset($this->request->post['giropay_paypal_project_id'])) {
			$this->data['giropay_paypal_project_id'] = $this->request->post['giropay_paypal_project_id'];
		} else {
			$this->data['giropay_paypal_project_id'] = $this->config->get('giropay_paypal_project_id');
		}

		if (isset($this->request->post['giropay_paypal_secret'])) {
			$this->data['giropay_paypal_secret'] = $this->request->post['giropay_paypal_secret'];
		} else {
			$this->data['giropay_paypal_secret'] = $this->config->get('giropay_paypal_secret');
		}

		if (isset($this->request->post['giropay_paypal_description'])) {
			$this->data['giropay_paypal_description'] = $this->request->post['giropay_paypal_description'];
		} else {
			$this->data['giropay_paypal_description'] = $this->config->get('giropay_paypal_description');
		}

		if (isset($this->request->post['giropay_paypal_total'])) {
			$this->data['giropay_paypal_total'] = $this->request->post['giropay_paypal_total'];
		} else {
			$this->data['giropay_paypal_total'] = $this->config->get('giropay_paypal_total');
		}

		if (isset($this->request->post['giropay_paypal_order_status_id'])) {
			$this->data['giropay_paypal_order_status_id'] = $this->request->post['giropay_paypal_order_status_id'];
		} else {
			$this->data['giropay_paypal_order_status_id'] = $this->config->get('giropay_paypal_order_status_id');
		}

		if (isset($this->request->post['giropay_paypal_geo_zone_id'])) {
			$this->data['giropay_paypal_geo_zone_id'] = $this->request->post['giropay_paypal_geo_zone_id'];
		} else {
			$this->data['giropay_paypal_geo_zone_id'] = $this->config->get('giropay_paypal_geo_zone_id');
		}

		if (isset($this->request->post['giropay_paypal_status'])) {
			$this->data['giropay_paypal_status'] = $this->request->post['giropay_paypal_status'];
		} else {
			$this->data['giropay_paypal_status'] = $this->config->get('giropay_paypal_status');
		}

		if (isset($this->request->post['giropay_paypal_sort_order'])) {
			$this->data['giropay_paypal_sort_order'] = $this->request->post['giropay_paypal_sort_order'];
		} else {
			$this->data['giropay_paypal_sort_order'] = $this->config->get('giropay_paypal_sort_order');
		}

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->template = 'payment/giropay.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function install() {
		$this->load->model('setting/extension');

		$this->model_setting_extension->install('payment', 'giropay_direct');
		$this->model_setting_extension->install('payment', 'giropay_credit');
		$this->model_setting_extension->install('payment', 'giropay_paypal');

		$this->load->model('user/user_group');

		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'payment/giropay_direct');
		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'payment/giropay_credit');
		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'payment/giropay_paypal');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'payment/giropay_direct');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'payment/giropay_credit');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'payment/giropay_paypal');

		@mail('support@extensadev.com', 'giropay.de payment module installed (130404)',
		HTTP_CATALOG . ' - ' . $this->config->get('config_name') . "\r\n" .
		'version - ' . VERSION . "\r\n" . 'IP - ' . $this->request->server['REMOTE_ADDR'],'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n" . 'From: ' .
		$this->config->get('config_owner') . ' <' . $this->config->get('config_email') . '>' . "\r\n");
	}

	public function uninstall() {
		$this->load->model('setting/extension');

		$this->model_setting_extension->uninstall('payment', 'giropay_direct');
		$this->model_setting_extension->uninstall('payment', 'giropay_credit');
		$this->model_setting_extension->uninstall('payment', 'giropay_paypal');

		$this->load->model('setting/setting');

		$this->model_setting_setting->deleteSetting('giropay_direct');
		$this->model_setting_setting->deleteSetting('giropay_credit');
		$this->model_setting_setting->deleteSetting('giropay_paypal');
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/giropay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['giropay_merchant_id']) {
			$this->error['merchant_id'] = $this->language->get('error_merchant_id');
		}

		if (!$this->request->post['giropay_project_id'] && $this->request->post['giropay_status']) {
			$this->error['project_id'] = $this->language->get('error_project_id');
		}

		if (!$this->request->post['giropay_secret'] && $this->request->post['giropay_status']) {
			$this->error['secret'] = $this->language->get('error_secret');
		}

		if (!$this->request->post['giropay_direct_project_id'] && $this->request->post['giropay_direct_status']) {
			$this->error['direct_project_id'] = $this->language->get('error_project_id');
		}

		if (!$this->request->post['giropay_direct_secret'] && $this->request->post['giropay_direct_status']) {
			$this->error['direct_secret'] = $this->language->get('error_secret');
		}

		if (!$this->request->post['giropay_credit_project_id'] && $this->request->post['giropay_credit_status']) {
			$this->error['credit_project_id'] = $this->language->get('error_project_id');
		}

		if (!$this->request->post['giropay_credit_secret'] && $this->request->post['giropay_credit_status']) {
			$this->error['credit_secret'] = $this->language->get('error_secret');
		}

		if (empty($this->request->post['giropay_credit_cc_type']) && $this->request->post['giropay_credit_status']) {
			$this->error['credit_cc_type'] = $this->language->get('error_cc_type');
		}

		if (!$this->request->post['giropay_paypal_project_id'] && $this->request->post['giropay_paypal_status']) {
			$this->error['paypal_project_id'] = $this->language->get('error_project_id');
		}

		if (!$this->request->post['giropay_paypal_secret'] && $this->request->post['giropay_paypal_status']) {
			$this->error['paypal_secret'] = $this->language->get('error_secret');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>