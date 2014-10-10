<?php
class ModelShippingApaczkaUPSstandardPolska extends Model {
	function getQuote($address) {
		$this->load->language('shipping/apaczka');

		if ($this->config->get('apaczkaUPSstandardPolska_status')) {
		$method_data = array();
		$quote_data = array();
		
		// miechu get costs by country and weight
        $this->getData('xxx', 100);
		
		

		$quote_data['apaczkaUPSstandardPolska'] = array(
			'code'         => 'apaczkaUPSstandardPolska',
			'title'        => 'UPS standard',
			'cost'         => $cost,
			'text'         => 'UPS standard  - '. $this->currency->format($cost),
			'tax_class_id' => 1,
		);

		$method_data = array(
			'code'       => 'apaczkaUPSstandardPolska',
			'title'      => 'UPS standard',
			'quote'      => $quote_data,
			'sort_order' => 20,
			'error'      => FALSE,
		);

		return $method_data;
		}
	}

private function getData($address,$weight){
	
			//extract data from the post


//set POST variables
$url = 'http://domain.com/get-post.php';
$fields = array(
            'lname' => urlencode($last_name),
            'fname' => urlencode($first_name),
            'title' => urlencode($title),
            'company' => urlencode($institution),
            'age' => urlencode($age),
            'email' => urlencode($email),
            'phone' => urlencode($phone)
        );

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);
	
}
}
?>