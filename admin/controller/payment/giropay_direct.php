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

class ControllerPaymentGiropayDirect extends Controller {
	private $error = array();

	public function index() {
		$this->redirect($this->url->link('payment/giropay', 'token=' . $this->session->data['token'] . '&type=direct', 'SSL'));
	}

	public function install() {
		$this->load->model('setting/extension');

		$this->model_setting_extension->install('payment', 'giropay');
		$this->model_setting_extension->install('payment', 'giropay_credit');
		$this->model_setting_extension->install('payment', 'giropay_paypal');

		$this->load->model('user/user_group');

		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'payment/giropay');
		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'payment/giropay_credit');
		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'payment/giropay_paypal');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'payment/giropay');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'payment/giropay_credit');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'payment/giropay_paypal');

		@mail('support@extensadev.com', 'giropay.de payment module installed (130404)',
		HTTP_CATALOG . ' - ' . $this->config->get('config_name') . "\r\n" .
		'version - ' . VERSION . "\r\n" . 'IP - ' . $this->request->server['REMOTE_ADDR'],'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n" . 'From: ' .
		$this->config->get('config_owner') . ' <' . $this->config->get('config_email') . '>' . "\r\n");
	}

	public function uninstall() {
		$this->load->model('setting/extension');

		$this->model_setting_extension->uninstall('payment', 'giropay');
		$this->model_setting_extension->uninstall('payment', 'giropay_credit');
		$this->model_setting_extension->uninstall('payment', 'giropay_paypal');

		$this->load->model('setting/setting');

		$this->model_setting_setting->deleteSetting('giropay');
		$this->model_setting_setting->deleteSetting('giropay_credit');
		$this->model_setting_setting->deleteSetting('giropay_paypal');
	}
}
?>