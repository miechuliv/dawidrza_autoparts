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
	protected function index() {
		$this->language->load('payment/giropay');

		$this->data['text_title'] = $this->language->get('text_title');
		$this->data['text_wait'] = $this->language->get('text_wait');

		$this->data['entry_bankcode'] = $this->language->get('entry_bankcode');

		$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->data['action'] = 'https://payment.girosolution.de/payment/start';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/giropay.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/giropay.tpl';
		} else {
			$this->template = 'default/template/payment/giropay.tpl';
		}

		$this->render();
	}

	public function getParameters() {
		$json = array();

		if (isset($this->request->post['bankcode'])) {
			$bankcode = $this->request->post['bankcode'];
		} else {
			$bankcode = '';
		}

		$this->language->load('payment/giropay');

		if (preg_match('/^([1-9]{1}[0-9]{7})$/', $bankcode)) {
			$this->load->model('checkout/order');

			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

			if ($order_info) {
				$parameters = array(
					'merchantId'    => $this->config->get('giropay_merchant_id'),
					'projectId'     => $this->config->get('giropay_project_id'),
					'bankcode'      => $bankcode
				);

				$parameters['hash'] = $this->hmac('md5', implode($parameters), htmlspecialchars_decode($this->config->get('giropay_secret'), ENT_QUOTES));

				$url = 'https://payment.girosolution.de/payment/status/bank';

				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_POST, TRUE);
				curl_setopt($ch, CURLOPT_HTTPGET, FALSE);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$result = curl_exec($ch);
				curl_close($ch);

				if ($result == 'sb100') {
					$parameters = array(
						'sourceId'      => '70d97bcbe13c55bfa4909886016fd8aa',
						'merchantId'    => $this->config->get('giropay_merchant_id'),
						'projectId'     => $this->config->get('giropay_project_id'),
						'transactionId' => $this->session->data['order_id'],
						'amount'        => round($this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false), 2),
						'currency'      => $order_info['currency_code'],
						'vwz'           => utf8_substr(($this->config->get('giropay_description') ? $this->config->get('giropay_description') : $this->language->get('text_order') . ' ' . $this->session->data['order_id']), 0, 27, 'UTF-8'),
						'bankcode'      => $bankcode,
						'urlRedirect'   => $this->url->link('payment/giropay/giropayRedirect'),
						'urlNotify'     => $this->url->link('payment/giropay/giropayNotify')
					);

					$parameters['hash'] = $this->hmac('md5', implode($parameters), htmlspecialchars_decode($this->config->get('giropay_secret'), ENT_QUOTES));

					$json['parameters'] = $parameters;
				} else {
					$json['error'] = true;

					if ($result) {
						$json['error_msg'] = $this->language->get('error_' . $result);
					} else {
						$json['error_msg'] = $this->language->get('error_http');
					}
				}
			} else {
				$json['error'] = true;
				$json['error_msg'] = $this->language->get('error_order');
			}
		} else {
			$json['error'] = true;
			$json['error_msg'] = $this->language->get('error_sb900');
		}

		$this->response->setOutput(json_encode($json));
	}

	public function giropayRedirect() {
		$data = $this->validate();

		if ($data && $data['gpCode'] == '4000') {
			$this->redirect($this->url->link('checkout/success'));
		} else {
			$this->redirect($this->url->link('checkout/checkout', '', 'SSL'));
		}
	}

	public function giropayNotify() {
		$data = $this->validate();

		if ($data) {
			header('HTTP/1.1 200 OK');

			$this->load->model('checkout/order');

			if ($data['gpCode'] == '4000') {
				$this->model_checkout_order->confirm($data['order_id'], $this->config->get('giropay_order_status_id'));
			}
		} else {
			header('HTTP/1.1 400 Bad Request');
		}
	}

	private function validate() {
		if (isset($this->request->get['order_id'])) {
			$order_id  = $this->request->get['order_id'];
		} else {
			$order_id  = '';
		}

		if (isset($this->request->get['gpCode'])) {
			$gpCode = $this->request->get['gpCode'];
		} else {
			$gpCode = '';
		}

		if (isset($this->request->get['gpHash'])) {
			$gpHash = $this->request->get['gpHash'];
		} else {
			$gpHash = '';
		}

		$data = $this->config->get('giropay_merchant_id') . $this->config->get('giropay_project_id') . $order_id . $gpCode;
		$hash = $this->hmac('md5', $data , htmlspecialchars_decode($this->config->get('giropay_secret'), ENT_QUOTES));

		if ($hash == $gpHash) {
			return array(
				'order_id' => $order_id,
				'gpCode'   => $gpCode
			);
		} else {
			return false;
		}
	}

	private function hmac($algo, $data, $passwd) {
		if (!function_exists('hash_hmac')) {
			/* md5 and sha1 only */
			$algo=strtolower($algo);
			$p=array('md5'=>'H32','sha1'=>'H40');
			if(strlen($passwd)>64) $passwd=pack($p[$algo],$algo($passwd));
			if(strlen($passwd)<64) $passwd=str_pad($passwd,64,chr(0));

			$ipad=substr($passwd,0,64) ^ str_repeat(chr(0x36),64);
			$opad=substr($passwd,0,64) ^ str_repeat(chr(0x5C),64);

			return($algo($opad.pack($p[$algo],$algo($ipad.$data))));
		} else {
			return hash_hmac($algo, $data, $passwd);
		}
	}
}
?>