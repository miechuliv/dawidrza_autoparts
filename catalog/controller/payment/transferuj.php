<?php

class ControllerPaymentTransferuj extends Controller
{
	protected function index()
	{
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['text_lang'] = $this->language->get('text_lang');
		
		$this->load->model('checkout/order');
		$this->load->library('encryption');
		$this->load->language('payment/transferuj');
		
		$order_data = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$this->data['action'] = HTTPS_SERVER . 'index.php?route=payment/transferuj/pay';
		$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		$this->data['order_id'] = $order_data['order_id'];
		$this->id = 'payment';
		
		$this->template = (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/transferuj.tpl') ? $this->config->get('config_template') . '/template/payment/transferuj.tpl' : 'default/template/payment/transferuj.tpl');
		$this->render();
	}
	
	public function pay()
	{
		$this->load->library('encryption');
		$this->load->model('checkout/order');
		$this->load->language('payment/transferuj');
		$order_data = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$this->id = 'payment';
		
		$transferuj_currency = $this->config->get('transferuj_currency');
		$transferuj_currency = 'PLN';				
		
		$transferuj_seller_id = $this->config->get('transferuj_seller_id');
		$transferuj_conf_code = $this->config->get('transferuj_conf_code');

		$crc = base64_encode($order_data['order_id']);

		$amount = number_format($this->currency->format($order_data['total'], $transferuj_currency, $order_data['currency_value'], FALSE),2,'.','');
		$from = $this->currency->getCode();
		$amount = $this->currency->convert($amount, $from, $transferuj_currency);
		
		
		$this->data['seller_id'] = $transferuj_seller_id;
		$this->data['kwota'] = $amount;
		$this->data['opis'] = $this->language->get('text_order') . $order_data['order_id'];
		$this->data['email'] = $order_data['email'];
		$this->data['nazwisko'] = $order_data['payment_lastname'];
		$this->data['imie'] = $order_data['payment_firstname'];
		$this->data['adres'] = $order_data['payment_address_1'].$order_data['payment_address_2'];
		$this->data['miasto'] = $order_data['payment_city'];
		$this->data['kraj'] = $order_data['payment_country'];
		$this->data['kod'] = $order_data['payment_postcode'];
		$this->data['crc'] = $crc;
		$this->data['md5sum'] = md5($transferuj_seller_id . $amount . $crc . $transferuj_conf_code);
		$this->data['telefon'] = $order_data['telephone'];
		$this->data['pow_url'] = HTTPS_SERVER . 'index.php?route=checkout/success';
		$this->data['pow_url_blad'] = HTTPS_SERVER . 'index.php?route=checkout/confirm';
		$this->data['wyn_url'] = HTTPS_SERVER . 'index.php?route=payment/transferuj/validate';
		
		$this->model_checkout_order->confirm($order_data['order_id'], 1);
		
		$this->data['text_transferuj_redirect'] =  $this->language->get('text_transferuj_redirect');
		$this->data['text_transferuj_redirect_btn'] =  $this->language->get('text_transferuj_redirect_btn');
		
		$this->template = (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/transferuj_redirect.tpl') ? $this->config->get('config_template') . '/template/payment/transferuj_redirect.tpl' : 'default/template/payment/transferuj_redirect.tpl');
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));		
	}

	
	public function validate()
	{
		if($_SERVER["REMOTE_ADDR"] != $this->config->get('transferuj_ip')) return false;
		
		echo "TRUE";

		if( isset($_POST) && !empty($_POST) && isset($_POST['tr_crc']) && isset($_POST['tr_status']) )
		{
			$seller_id = $_POST['id'];
			$order_id = base64_decode($_POST['tr_crc']);
			$tr_status = $_POST['tr_status'];
			$tr_id = $_POST['tr_id'];
			$amount_paid = number_format($_POST['tr_paid'],2,'.','');
			$conf_code = $this->config->get('transferuj_conf_code');
			$md5sum = md5($seller_id . $tr_id . $_POST['tr_amount'] . $_POST['tr_crc'] . $conf_code);
		
			$tr_error = $_POST['tr_error'];
			
			$this->load->model('checkout/order');
			$this->load->language('payment/transferuj');
			
			$order_data = $this->model_checkout_order->getOrder($order_id);		
			$completed_status = $this->config->get('transferuj_order_status_completed');
			$error_status = $this->config->get('transferuj_order_status_completed');
			
			$current_status = $order_data['order_status_id'];

			if($md5sum != $_POST['md5sum'])
			{
				$note = $this->language->get('text_incorrect_md5sum');
				$this->model_checkout_order->update($order_data['order_id'], $current_status, date('H:i:s ') . $this->language->get('text_incorrect_md5sum'), TRUE);
				$this->model_checkout_order->confirm($order_data['order_id'], $current_status, date('H:i:s ') . $this->language->get('text_incorrect_md5sum'));
				return false;
			}

			if($current_status != $completed_status)
			{
				$note = date('H:i:s ') . $this->language->get('text_payment_tr_id') . $tr_id;
				if($tr_error!='none') $note .= '<br />' . $this->language->get('text_payment_'.$tr_error) . $amount_paid;
				
				if($tr_status == 'TRUE')
				{
					$this->model_checkout_order->update($order_data['order_id'], $this->config->get('transferuj_order_status_completed'), $note, TRUE);
					$this->model_checkout_order->confirm($order_data['order_id'], $this->config->get('transferuj_order_status_completed'), $note);
				}
				elseif($tr_status == 'FALSE')
				{
					$this->model_checkout_order->update($order_data['order_id'], $this->config->get('transferuj_order_status_error'), $note, TRUE);
					$this->model_checkout_order->confirm($order_data['order_id'], $this->config->get('transferuj_order_status_error'), $note);					
				}
			}
		}

	}	

}
?>