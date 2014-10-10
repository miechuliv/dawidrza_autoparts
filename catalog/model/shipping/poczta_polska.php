<?php
// copl
class ModelShippingPocztaPolska extends Model {
	function calculateValueFee($price)
	        {
		     if ($price<1) return 0;   
		     return round((($price-1)/50)+0.5, 0); 
	        }    
	
	function calculateCost($rates,$weight)
	        {
             $cost = 0;   
		     foreach ($rates as $rate) 
			        {
				     $data = explode(':', $rate);				
				     if ($data[0] >= $weight) 
				        {
					     if (isset($data[1])) 
						     $cost = $data[1];
					     break;
				        }
			        }
			 return $cost;       
	        }    
	
	function getQuote($address) {
				
		if ($this->config->get('poczta_polska_status')) {
      		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('poczta_polska_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

      		if (!$this->config->get('poczta_polska_geo_zone_id')) {
        		$status = TRUE;
      		} elseif ($query->num_rows) {
        		$status = TRUE;
      		} else {
        		$status = FALSE;
      		}
		} else {
			$status = FALSE;
		}

		$quote_data = array();
	    
		// trzeba sprawdzi� ci�ar bo poczta przyjmuje tylko przesy�ki do 30kg
		$weight = $this->cart->getWeight();
		if ($weight > $this->weight->convert(30, $this->config->get('config_weight_class'), 'kg'))
		    $status = FALSE;		    		    

		// $total jest w tym wypadku kwot� netto
        $total = $this->cart->getSubTotal();  
           
	    // mo�e zakupy kwalifukuj� si� do darmowej wysy�ki, wtedy poczta polska nieaktywna (inne typy wysy�ki te�)	   
		if ($this->config->get('free_status'))
	       {	   
   		    $free_shipping_level = (float)$this->config->get('free_total');
		    if ($free_shipping_level!=0 && $total>=$free_shipping_level)
		        $status = FALSE;
           }
		if ($status) 
		  {
		   $this->load->language('shipping/poczta_polska');
			  
           $compensation = 0;	
           // sta�e koszty pakowania
           $packing_cost = (float)$this->config->get('poczta_polska_packing_cost');	
		   if ($packing_cost) 	  
			   $compensation += $packing_cost;
		   
           // doliczamy koszty za warto�� paczki
           $value_fee = (float)$this->config->get('poczta_polska_add_value_fee');		  
		   if ($value_fee && $total >= $value_fee) 
		      {	  
			   // wartos� liczymy od kwoty brutto   
			   $value_fee = $this->calculateValueFee($this->cart->getTotal());   
			   $compensation += $value_fee;
		      } 
		   else   
		       $value_fee = 0;   

		  //teraz sprawdzamy czy mo�emy zaproponowa� przesy�ki pobraniowe
          $allow_cod = TRUE;
          // COD w opcjach p�atno�ci mo�e by� wy��czone
          if ($this->config->get('cod_status')==0)
             $allow_cod = FALSE;      
          // pobranie tylko dla zarejestrowanych
		  if ($allow_cod && $this->config->get('cod_only_reg') && $this->customer->isLogged()==0)
              $allow_cod = FALSE;		
          // pobranie zabronione powy�ej podanej kwoty      
		  if ($allow_cod && $this->config->get('cod_limit') && $total>=$this->config->get('cod_limit'))
              $allow_cod = FALSE;	
              
          // teraz sprawdzamy, czy mo�emy proponowa� list polecony
          $allow_registered = FALSE;
          if ($this->config->get('poczta_polska_polecony_ekonom') || $this->config->get('poczta_polska_polecony_prio'))
              $allow_registered = TRUE;
          $weightNetto = $weight;    
          if ($allow_registered)
             {
	          if ($this->config->get('config_cart_weight_box'))
		          // przy li�cie poleconym nie powinni�my uwzgl�dnia� ci�aru pude�ka, bo list jest w kopercie
		          $weightNetto = $this->cart->getWeightWithoutBox();   

		      // sprawdzamy limit wagi    
	          if ($weightNetto > $this->weight->convert($this->config->get('max_weight_envelope'), $this->config->get('config_weight_class'), 'kg'))
	              $allow_registered = FALSE;
	          else
	          // limit ilo�ci produkt�w
	          if ($this->config->get('max_items_envelope') && $this->cart->hasProducts() && $this->cart->countProducts()>$this->config->get('max_items_envelope'))
	              $allow_registered = FALSE;
	          else
	             {		              
		          // i jeszcze wymiary - sprawdzamy czy zakup zmie�ci sie w kopercie
		          $limit = array ($this->config->get('max_size_envelope_x'),$this->config->get('max_size_envelope_y'),$this->config->get('max_size_envelope_z'));
		          asort($limit);
		          $limit = array_values($limit);
		          $products = $this->cart->getProducts();
		          $i=0;
                  $a = array();
		          foreach ($products as $product)
                          {
	                       for($k=0;$k<$product['quantity'];$k++)
	                          {
                               $a[$i]=array($product['length'],$product['width'],$product['height']);
                               asort($a[$i]);
                               $a[$i] = array_values($a[$i]);
                               $i++;
                              }
                          }		          
                  // wymiary produkt�w s� juz posortowane, teraz sprawdzamy, czy �aden z nich nie przekracza maksymalnego wymiaru        
                  foreach ($a as $item)
                          {
	                       if ($item[0]>$limit[0] || $item[1]>$limit[1] || $item[2]>$limit[2])
	                          {
		                       $allow_registered = FALSE;
	                           break;
	                          }
                          }    
                  // nast�pne sprawdzenie polega na tym, �e liczymy sum� najmniejszego wymiaru dla wszystkich produkt�w i sprawdzamy czy jest mniejsza od najmniejszego wymiaru z limitu
                  // je�li tak, to oznacza, �e mo�emy uk�ada� produkty jeden na drugim - to jest b. uproszczona i niedoskona�a metoda sprawdzenia czy produkty zmieszcz� sie w zadanej obj�to�ci
                  // ten algorytm dobrze by by�o w przysz�o�ci poprawi�
                  if ($allow_registered)
                     {
	                  $sum = 0;   
                      foreach ($a as $item)
	                           $sum += $item[0];    
	                  if ($sum>$limit[0]) 
	                      $allow_registered = FALSE;    	                          
                     }                          
                 }
             } 
		  // list polecony ekonomiczny
		  if ($allow_registered && $this->config->get('poczta_polska_polecony_ekonom')) 
		      {
			   $rates = explode(',', $this->config->get('poczta_polska_polecony_ekonom_rate'));
			   $cost = $this->calculateCost($rates,$weightNetto);			
			   if ((float)$cost) 
			      {
				   if ($this->config->get('poczta_polska_display_weight'))   
				       $weight_text = '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weightNetto, $this->config->get('config_weight_class')) . ')';
				   else
				       $weight_text = '';
				   $title = $this->language->get('text_poczta_polska_polecony_ekonom') . $weight_text;
			       $cost += $compensation;
			       $id = 'poczta_polska.poczta_polska_polecony_ekonom';
			       if ($value_fee) $id .= '.wartosc';
				   $quote_data['poczta_polska_polecony_ekonom'] = array(
        			'code'           => $id,
        			'title'        => $title,
        			'cost'         => $cost,
        			'tax_class_id' => $this->config->get('poczta_polska_tax_class_id'),
					'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('poczta_polska_tax_class_id'), $this->config->get('config_tax')))
      			    );      							        				
			     }					
	         }             
                           			              
		  // list polecony priorytet
		  if ($allow_registered && $this->config->get('poczta_polska_polecony_prio')) 
		      {
			   $rates = explode(',', $this->config->get('poczta_polska_polecony_prio_rate'));
			   $cost = $this->calculateCost($rates,$weightNetto);			
			   if ((float)$cost) 
			      {
				   if ($this->config->get('poczta_polska_display_weight'))   
				       $weight_text = '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weightNetto, $this->config->get('config_weight_class')) . ')';
				   else
				       $weight_text = '';
				   $title = $this->language->get('text_poczta_polska_polecony_prio') . $weight_text;
			       $cost += $compensation;
			       $id = 'poczta_polska.poczta_polska_polecony_prio';
			       if ($value_fee) $id .= '.wartosc';
				   $quote_data['poczta_polska_polecony_prio'] = array(
        			'code'           => $id,
        			'title'        => $title,
        			'cost'         => $cost,
        			'tax_class_id' => $this->config->get('poczta_polska_tax_class_id'),
					'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('poczta_polska_tax_class_id'), $this->config->get('config_tax')))
      			    );      							        				
			     }					
	         }             
                           			              
		  // paczka ekonomiczna
		  if ($this->config->get('poczta_polska_ekonom')) 
		      {
			   $rates = explode(',', $this->config->get('poczta_polska_ekonom_rate'));
			   $cost = $this->calculateCost($rates,$weight);			
			   if ((float)$cost) 
			      {
				   if ($this->config->get('poczta_polska_display_weight'))   
				       $weight_text = '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weightNetto, $this->config->get('config_weight_class')) . ')';
				   else
				       $weight_text = '';
				   $title = $this->language->get('text_poczta_polska_ekonom') . $weight_text;
			       $cost += $compensation;
			       $id = 'poczta_polska.poczta_polska_ekonom';
			       if ($value_fee) $id .= '.wartosc';
				   $quote_data['poczta_polska_ekonom'] = array(
        			  'code'           => $id,
        			  'title'        => $title,
        			  'cost'         => $cost,
        			  'tax_class_id' => $this->config->get('poczta_polska_tax_class_id'),
					  'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('poczta_polska_tax_class_id'), $this->config->get('config_tax')))
      			      );      							        				
			     }					
	        }
		
		  // paczka priorytetowa
		  if ($this->config->get('poczta_polska_prio')) 
		      {
			   $rates = explode(',', $this->config->get('poczta_polska_prio_rate'));
			   $cost = $this->calculateCost($rates,$weight);										
			   if ((float)$cost) 
			      {
				   if ($this->config->get('poczta_polska_display_weight'))   
				       $weight_text = '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weightNetto, $this->config->get('config_weight_class')) . ')';
				   else
				       $weight_text = '';
				   $title = $this->language->get('text_poczta_polska_prio') . $weight_text;
			       $cost += $compensation;
			       $id = 'poczta_polska.poczta_polska_prio';
			       if ($value_fee) $id .= '.wartosc';
			       $quote_data['poczta_polska_prio'] = array(
        		  	     'code'           => $id,
        			     'title'        => $title,
        			     'cost'         => $cost,
        			     'tax_class_id' => $this->config->get('poczta_polska_tax_class_id'),
					     'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('poczta_polska_tax_class_id'), $this->config->get('config_tax')))
      			       );      							        				
  			       }  
			 }					

		  // paczka ekonomiczna pobraniowa
		  if ($allow_cod && $this->config->get('poczta_polska_pobranie_ekonom')) 
		      {
			   $rates = explode(',', $this->config->get('poczta_polska_pobranie_ekonom_rate'));
			   $cost = $this->calculateCost($rates,$weight);										
			   if ((float)$cost) 
			      {
				   if ($this->config->get('poczta_polska_display_weight'))   
				       $weight_text = '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weightNetto, $this->config->get('config_weight_class')) . ')';
				   else
				       $weight_text = '';
				   $title = $this->language->get('text_poczta_polska_pobranie_ekonom') . $weight_text;
			       $cost += $compensation;
			       $id = 'poczta_polska.poczta_polska_pobranie_ekonom';
			       if ($value_fee) $id .= '.wartosc';
			       $quote_data['poczta_polska_pobranie_ekonom'] = array(
        			     'code'           => $id,
        			     'title'        => $title,
        			     'cost'         => $cost,
        			     'tax_class_id' => $this->config->get('poczta_polska_tax_class_id'),
					     'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('poczta_polska_tax_class_id'), $this->config->get('config_tax')))
      			       );      							        				
  			     }  
			 }					
		
		  // paczka priorytetowa pobraniowa
		  if ($allow_cod && $this->config->get('poczta_polska_pobranie_prio')) 
		      {
			   $rates = explode(',', $this->config->get('poczta_polska_pobranie_prio_rate'));
			   $cost = $this->calculateCost($rates,$weight);										
			   if ((float)$cost) 
			      {
				   if ($this->config->get('poczta_polska_display_weight'))   
				       $weight_text = '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weightNetto, $this->config->get('config_weight_class')) . ')';
				   else
				       $weight_text = '';
				   $title = $this->language->get('text_poczta_polska_pobranie_prio') . $weight_text;
			       $cost += $compensation;
			       $id = 'poczta_polska.poczta_polska_pobranie_prio';
			       if ($value_fee) $id .= '.wartosc';
			       $quote_data['poczta_polska_pobranie_prio'] = array(
        			     'code'           => $id,
        			     'title'        => $title,
        			     'cost'         => $cost,
        			     'tax_class_id' => $this->config->get('poczta_polska_tax_class_id'),
					     'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('poczta_polska_tax_class_id'), $this->config->get('config_tax')))
      			       );      							        				
  			      }  
			 }					
	      }
		
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$method_data = array();
	
       if ($status) {
		    $method_data = array(
        			'code'         => 'poczta_polska',
        			'title'      => $this->language->get('text_title'),
        			'quote'      => $quote_data,
					'sort_order' => $this->config->get('poczta_polska_sort_order'),
        			'error'      => FALSE
      			);
       }
		return $method_data;
	}
  }
?>