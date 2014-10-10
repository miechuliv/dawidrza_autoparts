<?php
class ModelTotalBankTransferRabat extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->language->load('total/bank_transfer_rabat');

        foreach($total_data as $t_data)
        {
            if($t_data['code'] == 'bank_transfer_rabat')
            {
                return false;
            }
        }

        $payment_method = isset($this->session->data['payment_method'])?$this->session->data['payment_method']:null;

        if(!$payment_method)
        {
            $payment_method = isset($this->request->post['payment_method'])?$this->request->post['payment_method']:null;
        }

        if($payment_method == 'bank_transfer')
        {
            $bank_transfer_rabat_status = $this->config->get('bank_transfer_rabat_status');
            $bank_transfer_rabat_percent = $this->config->get('bank_transfer_rabat_percent');

            if($bank_transfer_rabat_status && $bank_transfer_rabat_percent)
            {
                $rabat = -($total*((((int)($bank_transfer_rabat_percent))/100)));

                $total_data[] = array(
                    'code'       => 'bank_transfer_rabat',
                    'title'      => $this->language->get('text_bank_transfer_rabat'),
                    'text'       => $this->currency->format($rabat),
                    'value'      => $rabat,
                    'sort_order' => $this->config->get('bank_transfer_rabat_sort_order')
                );

                $total += $rabat;
            }






        }
	 

	}
}
?>