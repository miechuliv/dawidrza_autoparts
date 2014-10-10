<?php
class ControlleramazonusProduct extends Controller
{   
    public function inbound() {
        if ($this->config->get('amazonus_status') != '1') {
            $this->response->setOutput("disabled");
            return;
        }
         ob_start();
         
         $this->load->library('amazonus');
         $this->load->model('amazonus/product');  
         $this->load->library('log');
         $logger = new Log('amazonus_product.log');
         
         $logger->write("amazonusProduct/inbound: incoming data");
         
         $incomingToken = isset($this->request->post['token']) ? $this->request->post['token'] : '';
         
         if($incomingToken != $this->config->get('openbay_amazonus_token')) {
             $logger->write("Error - Incorrect token: " . $this->request->post['token']);
             ob_get_clean();
             $this->response->setOutput("tokens did not match");
             return;
         }
         
         $data = $this->amazonus->decryptArgs($this->request->post['data']);
         if(!$data) {
             $logger->write("Error - Failed to decrypt received data.");
             ob_get_clean();
             $this->response->setOutput("failed to decrypt");  
             return;
         }
         

         $decodedData = (array)json_decode($data);
         $logger->write("Received data: " . print_r($decodedData, true));
         $status = $decodedData['status'];
         if($status == "submit_error") {
             $message = 'Product was not submited to amazonus properly. Please try again or contact OpenBay.';
             $this->model_amazonus_product->setSubmitError($decodedData['insertion_id'], $message);
         }
         else {
            $status = (array)$status;
            if($status['successful'] == 1) {

                $this->model_amazonus_product->setOk($decodedData['insertion_id']);
                $insertionProduct = $this->model_amazonus_product->getProduct($decodedData['insertion_id']);
                $this->model_amazonus_product->linkProduct($insertionProduct['sku'], $insertionProduct['product_id'], $insertionProduct['var']);
                $this->model_amazonus_product->deleteErrors($decodedData['insertion_id']);
                
                $quantityData = array(
                    $insertionProduct['sku'] => $this->model_amazonus_product->getProductQuantity($insertionProduct['product_id'], $insertionProduct['var']) 
                );
                $logger->write('Updating quantity with data: ' . print_r($quantityData, true));
                $logger->write('Response: ' . print_r($this->amazonus->updateQuantities($quantityData), true));

            } else {
                $msg = 'Product was not accepted by Amazon US. Please try again or contact OpenBay.';
                $this->model_amazonus_product->setSubmitError($decodedData['insertion_id'], $msg);

                if(isset($decodedData['error_details'])) {
                    foreach($decodedData['error_details'] as $error) {
                        $error = (array)$error;
                        $error_data = array(
                            'sku' => $error['sku'],
                            'error_code' => $error['error_code'],
                            'message' => $error['message'],
                            'insertion_id' => $decodedData['insertion_id']
                        );
                        $this->model_amazonus_product->insertError($error_data);

                    }
                }
            }
         }
         $logger->write("Data processed successfully.");
         ob_get_clean();
         $this->response->setOutput("ok");
    }
    
}
?>