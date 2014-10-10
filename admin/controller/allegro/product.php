<?php

/*
 * 18.09.2013 Mieszko tytuł tworzy się z kategorii  i modelu a nie z nazwy produktu, problem ograniczenia dlugosci tytułu allegro
 */

class ControllerAllegroProduct extends Controller {
	private $error = array() ;

	public function index() {
        
        unset($_SESSION) ;
        
		$this->document->setTitle('Allegro');
		$this->load->model( 'allegro/product' ) ;
        
		$this->data['heading_title'] = 'Allegro' ;
		$this->data['button_insert'] = $this->language->get( 'button_insert' ) ;
		$this->data['button_cancel'] = $this->language->get( 'button_cancel' ) ;
		$this->data['insert'] = 'index.php?route=allegro/product/insert&token=' . $this->session->data['token'] . '&product_id=' . $_GET['product_id'] . '&checkauction=1' ;
		$this->data['cancel'] = 'index.php?route=catalog/product&token=' . $this->session->data['token'] ;
		$this->data['success'] = '' ;
        $_SESSION['getcategoryoptions'] = 'index.php?route=allegro/category/getcategoryoptions&token=' . $this->session->data['token'] . '&' ;
        $_SESSION['getcategorybyparent'] = 'index.php?route=allegro/product/getcategorybyparent&token=' . $this->session->data['token'] . '&' ;
        
		$Saved = file_get_contents( DIR_CACHE . 'AllegroSavedInputs.txt' ) ;
		$this->data['saved'] = $this->objecttoarray( json_decode( $Saved ) ) ;


		if ( isset( $this->error['warning'] ) ) {
			$this->data['error_warning'] = $this->error['warning'] ;
		}
		else {
			$this->data['error_warning'] = '' ;
		}

		if ( isset( $this->error['name'] ) ) {
			$this->data['error_name'] = $this->error['name'] ;
		}
		else {
			$this->data['error_name'] = '' ;
		}

		$this->document->breadcrumbs = array() ;
		$this->document->breadcrumbs[] = array( 'href' => HTTPS_SERVER . 'index.php?route=allegro/product&token=' . $this->session->data['token'], 'text' => $this->language->get( 'text_home' ), 'separator' => false ) ;
        
		$this->data['Product'] = $this->model_allegro_product->getProduct( $_GET['product_id'] ) ;

        $this->data['Product']['price'] = $this->data['Product']['price_pl'];

        // 19.09.2013

        require_once DIR_SYSTEM . 'library/HTMLPurifier.auto.php';

        $config = HTMLPurifier_Config::createDefault();
        $config->set('Core.Encoding', 'UTF-8'); // replace with your encoding
        //  $config->set('HTML.Doctype', 'HTML'); // replace with your doctype
        $config->set('CSS.AllowedProperties', array());
        $purifier = new HTMLPurifier($config);

        $this->data['Product']['description'] = $purifier->purify($this->data['Product']['description']);

        // 19.09.2013

        // 18.09.2013
        $Categories = $this->model_allegro_product->getCategories( $_GET['product_id'] ) ;


        $category = array_shift($Categories);

        $prefix = array(
            59 => array(
                'new' => "Nowa",
                'regenerated' => "Regenerowana",
                 'for_regeneration' => "Do regeneracji",
                0 => "Do regeneracji",
            ),
            73 => array(
                'new' => "Nowa",
                'regenerated' => "Regenerowana",
                'for_regeneration' => "Do regeneracji",
                0 => "Do regeneracji",
            ),
            60 => array(
                'new' => "Nowy",
                'regenerated' => "Regenerowany",
             'for_regeneration' => "Do regeneracji",
                0 => "Do regeneracji",
            )
        );

        $Title = '';

        if($category == 59)
        {
            $Title .= $prefix[59][$this->data['Product']['type']]." Pompa Paliwowa ".$this->data['Product']['make_name']." ".$this->data['Product']['model_name'] ." ".$this->data['Product']['model'];
        }
        elseif($category == 60)
        {
            $Title .= $prefix[60][$this->data['Product']['type']]." Wtryskiwacz ".$this->data['Product']['make_name']." ".$this->data['Product']['model_name']." ".$this->data['Product']['model'];
        }
        else
        {
            // 50 znaków ograniczenie allegro
            $Title = substr($this->data['Product']['name'],0,50);
        }

        $this->data['Product']['name'] = $Title;
        // 18.09.2013

		$this->data['ProductOptions'] = $this->model_allegro_product->getProductOptions( $_GET['product_id'] ) ;
		
        $this->data['ProductImages'] = $this->model_allegro_product->getProductImages( $_GET['product_id'] ) ;



		if ( !empty($this->data['Product']['image']) ) {
            $this->data['ProductImages'][]['image'] = $this->data['Product']['image'] ;
		}

        
		$this->data['ProductSpecial'] = $this->model_allegro_product->getProductSpecial( $_GET['product_id'] ) ;
		$this->data['ProductSpecials'] = $this->model_allegro_product->getProductSpecials( $_GET['product_id'] ) ;
		$this->data['ProductTotalSpecials'] = $this->model_allegro_product->getTotalProductSpecials( $_GET['product_id'] ) ;
		$this->data['ProductRelated'] = $this->model_allegro_product->getProductRelated( $_GET['product_id'] ) ;
		$this->data['ProductCategories'] = $this->model_allegro_product->getCategories( $_GET['product_id'] ) ;
		$this->data['ProductTemplates'] = $this->model_allegro_product->getTemplates() ;

        if ( isset($_GET['updatecategories']) ) {
            
            $this->model_allegro_product->UpdateCategoriesToDB($AllegroCategories, true) ;
        }
        
        $this->data['AllegroStates'] = $this->getAllegroData( 'GetStatesInfo', $Options = '' ) ;
		$AllegroShipments = $this->getAllegroData( 'GetSellFormFieldsExt', $Options = '' ) ;
		$_SESSION['product_quantity'] = $this->data['Product']['quantity'] ;
        
        if ( isset($this->data['ProductSpecials'][0]['special']) ) {
            $this->data['ProductSpecials'][0]['special'] = $this->data['ProductSpecials'][0]['special'] * 1.23 ; 
        }
        
        $this->load->library( 'tax' ) ;
        $this->tax = new Tax($this->registry) ;
        
		// miechu mod
        $this->data['Product']['price'] = $this->tax->calculate($this->data['Product']['price'], $this->data['Product']['tax_class_id'], $this->config->get('config_tax'));
         $this->data['Product']['price']=strip_tags( $this->data['Product']['price']);
		 // end miechu mod
        //$this->data['Product']['price'] = $this->currency->format($Tax->calculate($this->data['Product']['price'], $this->data['Product']['tax_class_id'], $this->config->get('config_tax'))) ;
        
		if ( isset( $this->data['ProductSpecials'][0]['special'] ) ) {
			$_SESSION['product_specials'] = substr( $this->data['ProductSpecials'][0]['special'], 0, -2 ) ;
		}
		else {
			$_SESSION['product_specials'] = $this->data['Product']['price'] ;
		}
        
        $_SESSION['product_specials'] = str_replace('','',$_SESSION['product_specials']) ;

        
		for ( $i = 33; $i < 84; $i++ ) {
		  
			if ( $AllegroShipments['sell-form-fields'][$i]['sell-form-res-type'] == 1 ) {
				$FormType = 'string' ;
			} elseif ( $AllegroShipments['sell-form-fields'][$i]['sell-form-res-type'] == 2 ) {
				$FormType = 'int' ;
			} elseif ( $AllegroShipments['sell-form-fields'][$i]['sell-form-res-type'] == 3 ) {
				$FormType = 'float' ;
			}
            
			$this->data['AllegroShipments'][] = array( 'FormId' => $AllegroShipments['sell-form-fields'][$i]['sell-form-id'], 'Title' => $AllegroShipments['sell-form-fields'][$i]['sell-form-title'], 'FormType' => $FormType ) ;
		}
        
        /*
		if ( !file_exists( DIR_CACHE . 'AllegroCategories.txt' ) ) {
		  
			foreach ( $AllegroCategories['cats-list'] as $AllegroCategory ) {
			 
				if ( $AllegroCategory['cat-parent'] == 0 ) {
				    
					$this->data['AllegroCategories'][$AllegroCategory['cat-id']] = array( 'cat-id' => $AllegroCategory['cat-id'], 'cat-name' => $AllegroCategory['cat-name'] ) ;
				}
				else {
				    
					if ( !isset( $this->data['AllegroCategories'][$AllegroCategory['cat-parent']] ) ) {
					   
						foreach ( $this->data['AllegroCategories'] as $ACat ) {
						  
							if ( isset( $ACat['cat-parents'] ) ) {
							 
								for ( $i = 0; $i < count( $ACat['cat-parents'] ); $i++ ) {
								    
									if ( $ACat['cat-parents'][$i]['cat-id'] == $AllegroCategory['cat-parent'] ) {
									   
										$this->data['AllegroCategories'][$ACat['cat-id']]['cat-parents'][$i]['cat-parents'][] = array( 'cat-id' => $AllegroCategory['cat-id'], 'cat-name' => $AllegroCategory['cat-name'], 'cat-parent' => $AllegroCategory['cat-parent'] ) ;
									}
									if ( isset( $ACat['cat-parents'][$i]['cat-parents'] ) ) {
									   
										for ( $ii = 0; $ii < count( $ACat['cat-parents'][$i]['cat-parents'] ); $ii++ ) {
										  
											if ( $ACat['cat-parents'][$i]['cat-parents'][$ii]['cat-id'] == $AllegroCategory['cat-parent'] ) {
											 
												$this->data['AllegroCategories'][$ACat['cat-id']]['cat-parents'][$i]['cat-parents'][$ii]['cat-parents'][] = array( 'cat-id' => $AllegroCategory['cat-id'], 'cat-name' => $AllegroCategory['cat-name'], 'cat-parent' => $AllegroCategory['cat-parent'] ) ;
											}
											if ( isset( $ACat['cat-parents'][$i]['cat-parents'][$ii]['cat-parents'] ) ) {
												
                                                for ( $iii = 0; $iii < count( $ACat['cat-parents'][$i]['cat-parents'][$ii]['cat-parents'] ); $iii++ ) {
													
                                                    if ( $ACat['cat-parents'][$i]['cat-parents'][$ii]['cat-parents'][$iii]['cat-id'] == $AllegroCategory['cat-parent'] ) {
														$this->data['AllegroCategories'][$ACat['cat-id']]['cat-parents'][$i]['cat-parents'][$ii]['cat-parents'][$iii]['cat-parents'][] = array( 'cat-id' => $AllegroCategory['cat-id'], 'cat-name' => $AllegroCategory['cat-name'], 'cat-parent' => $AllegroCategory['cat-parent'] ) ;
													}
													
                                                    if ( isset( $ACat['cat-parents'][$i]['cat-parents'][$ii]['cat-parents'][$iii]['cat-parents'] ) ) {
														
                                                        for ( $iiii = 0; $iiii < count( $ACat['cat-parents'][$i]['cat-parents'][$ii]['cat-parents'][$iii]['cat-parents'] ); $iiii++ ) {
															
                                                            if ( $ACat['cat-parents'][$i]['cat-parents'][$ii]['cat-parents'][$iii]['cat-parents'][$iiii]['cat-id'] == $AllegroCategory['cat-parent'] ) {
																$this->data['AllegroCategories'][$ACat['cat-id']]['cat-parents'][$i]['cat-parents'][$ii]['cat-parents'][$iii]['cat-parents'][$iiii]['cat-parents'][] = array( 'cat-id' => $AllegroCategory['cat-id'], 'cat-name' => $AllegroCategory['cat-name'], 'cat-parent' => $AllegroCategory['cat-parent'] ) ;
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
					else {
						
                        $this->data['AllegroCategories'][$AllegroCategory['cat-parent']]['cat-parents'][] = array( 'cat-id' => $AllegroCategory['cat-id'], 'cat-name' => $AllegroCategory['cat-name'], 'cat-parent' => $AllegroCategory['cat-parent'] ) ;
					}
				}
			}
            
			file_put_contents( DIR_CACHE . 'AllegroCategories.txt', json_encode( $this->data['AllegroCategories'] ) ) ;
		}
		else {
		  
			$this->data['AllegroCategories'] = json_decode( file_get_contents( DIR_CACHE . 'AllegroCategories.txt' ), true ) ;
		}
        */
        
        $this->data['AllegroCategories'] = $this->model_allegro_product->GetCategoriesByCatParent(0) ;
        
		$this->template = 'allegro/product.tpl' ;
		$this->children = array( 'common/header', 'common/footer' ) ;
		$this->response->setOutput( $this->render( true ), false ) ;
	}
	public function Insert() {
	   
		$this->load->library( 'class.allegrowebapi' ) ;
		
		//echo 'step1<br/>';
	/*	if(!isset($_POST['15-image']))
		{
			$_POST['15-image'] = $_POST['16-image'];
		} */
        $saved = array();

        foreach($_POST as $key => $field)
        {
             $t = explode('-',$key);
             $saved[$t[0]] = $field;
        }
		
		 
		ksort( $_POST, SORT_NUMERIC ) ;
        $this->load->model( 'allegro/product' ) ;
        $Producttt = $this->model_allegro_product->getProduct( $_GET['product_id'] ) ;
		
		$ProductImages = $this->model_allegro_product->getProductImages( $_GET['product_id'] ) ;
		$Categories = $this->model_allegro_product->getCategories( $_GET['product_id'] ) ;
		$Options = $this->model_allegro_product->getProductOptions( $_GET['product_id'] ) ;
	
		$ProductDescription = html_entity_decode( $Producttt['description'] ) ;
		$Price='';
		
		$Manufacturer=$this->model_allegro_product->getManuName( $Producttt['manufacturer_id'] );
		
		$ExtOptions='';
		foreach ( $Options as $Option ) {

			
			$ExtOptions .= '<p><strong>'.$Option['name'].': </strong></p><p>' ;
			if(is_array($Option['product_option_value']))
			{
				foreach ($Option['product_option_value'] as $value) {
				$ExtOptions .= ' '.$value['name'].', ' ;
			}
				
			}
			else 
			{
				$ExtOptions .= ' '.$Option['option_value'].' ' ;
			}
			
			$ExtOptions .= '</p><br/><br/>';
		}
		
		$ExtCategories='<p>';
		foreach ( $Categories as $Category ) {

			$ExtCategories .= ''.$this->model_allegro_product->getCategoryName( $Category ).', ' ;
		}
        $ExtCategories.='</p>';
		$ExtImagesM='';
		
		$IMG='<a  href="' . HTTP_IMAGE . $Producttt['image'] . '"><img class ="small" src="' . HTTP_IMAGE . $Producttt['image'] . '"></a>' ;
		foreach ( $ProductImages as $ProductImage ) {

			$ExtImagesM .= '<a  href="' . HTTP_IMAGE . $ProductImage['image'] . '"><img class ="small" src="' . HTTP_IMAGE . $ProductImage['image'] . '"></a>' ;
		}
		
    //    echo 'step2<br/>';
		
		if ( isset( $_GET['checkauction'] ) ) {
			
			
			
            if ( isset( $_POST['15-int'] ) ) {
				
                $Ints15 = 0 ;
				
                foreach ( $_POST['15-int'] as $Int15 ) {
					$Ints15 = $Ints15 + $Int15 ;
				
                }
				$_POST['15-int'] = $Ints15 ;
			}
			if ( isset( $_POST['35-int'] ) ) {
				
                $Ints15 = 0 ;
				
                foreach ( $_POST['35-int'] as $Int15 ) {
					$Ints15 = $Ints15 + $Int15 ;
				}
				
                $_POST['35-int'] = $Ints15 ;
			}
			if ( isset( $_POST['14-int'] ) ) {
				
                $Ints15 = 0 ;
				
                foreach ( $_POST['14-int'] as $Int15 ) {
					$Ints15 = $Ints15 + $Int15 ;
				}
				
                $_POST['14-int'] = $Ints15 ;
			}
			if ( isset( $_POST['13-int'] ) ) {
				
                $Ints15 = 0 ;
				
                foreach ( $_POST['13-int'] as $Int15 ) {
					$Ints15 = $Ints15 + $Int15 ;
				}
				
                $_POST['13-int'] = $Ints15 ;
			}
		}
		
		if ( isset( $_POST['16-image'] ) ) {
			
            $ExtImages = '<a href="' . $_POST['16-image'] . '"><img src="' . $_POST['16-image'] . '"></a>' ;
		}
		else {
			
            $ExtImages = '' ;
		}
		
        for ( $i = 0; $i < 20; $i++ ) {
			
            if ( isset( $_POST["imgs$i"] ) ) {
			
            	$ExtImages1 .= '<a href="' . $_POST["imgs$i"] . '"><img src="' . $_POST["imgs$i"] . '"></a>' ;    
			}
		} 
		
		if(isset($_POST['8-float']))
			{
				$Price=$_POST['8-float'];
				$Price=$this->currency->format($Price, $this->config->get('config_currency'));
                $Price= str_ireplace('€','PLN',$Price);
			}
	//	echo 'step3<br/>';
        
        if ( !isset( $_GET['checkauction'] ) ) {
			
            $_POST['24-string'] = base64_decode( $_POST['24-string'] ) ;
			$_POST['24-string'] = html_entity_decode( $_POST['24-string'] ) ;
			// $_POST['24-string'] = str_replace( array( '{PRODUCT_NAME}', '{PRODUCT_DESCRIPTION}', '{IMG}', '{PRODUCT_MODEL}' ,'{PRODUCT_PRICE}','PRODUCT_MANUFACTURER','{PRODUCT_OPTIONS}','{IMAGES}'), array( $_POST['1-string'], $_POST['24-string'], $ExtImages, $Producttt['model'] ), str_replace('""','"',html_entity_decode(base64_decode( $_POST['allegrotemplate'])) ) ) ;
            $Type = NULL;

            if($Producttt['type']=='new')
            {
                $Type = "Kup Nowy";
            }
            elseif($Producttt['type']=='regenerated')
            {
                $Type = "Regeneracja";
            }

			$_POST['24-string'] = str_replace( array( '{PRODUCT_NAME}', '{PRODUCT_DESCRIPTION}', '{IMG}', '{PRODUCT_MODEL}' ,'{PRODUCT_PRICE}','{PRODUCT_MANUFACTURER}','{PRODUCT_OPTIONS}','{IMAGES}','{MAKE_NAME}','{MODEL_NAME}','{TYPE}'), array( $_POST['1-string'], $_POST['24-string'], $IMG, $Producttt['model'] ,$Price,$Manufacturer,$ExtOptions,$ExtImagesM,$Producttt['make_name'],$Producttt['model_name'],$Type), str_replace('""','"',html_entity_decode(base64_decode( $_POST['allegrotemplate'])) ) ) ;
		}
		
        foreach ( $_POST as $Key => $Value ) { 
			
            $KeyE = explode( '-', $Key ) ;
            
            if ( strpos( $Key, 'active' ) === false && strpos( $Key, 'imgs' ) === false && $Key != 'allegrotemplate' && $Value != '' ) {

                if ( $KeyE[1] == 'string' ) {
				
                	$Insert[] = array( 'fid' => $KeyE[0], 'fvalue-string' => $Value, 'fvalue-int' => 0, 'fvalue-float' => 0, 'fvalue-image' => 0, 'fvalue-datetime' => 0, 'fvalue-boolean' => false, 'fvalue-date' => 0, 'fvalue-range-int' => array( 'fvalue-range-int-min' => 0, 'fvalue-range-int-max' => 0 ), 'fvalue-range-float' => array( 'fvalue-range-float-min' => 0.00, 'fvalue-range-float-max' => 0.00 ), 'fvalue-range-date' => array( 'fvalue-range-date-min' => 0, 'fvalue-range-date-max' => 0 ), ) ;
					$InsertSaved[$KeyE[0]] = $Value ;
				}
                elseif ( $KeyE[1] == 'int' ) {
				
                	$Insert[] = array( 'fid' => $KeyE[0], 'fvalue-string' => '', 'fvalue-int' => $Value, 'fvalue-float' => 0, 'fvalue-image' => 0, 'fvalue-datetime' => 0, 'fvalue-boolean' => false, 'fvalue-date' => 0, 'fvalue-range-int' => array( 'fvalue-range-int-min' => 0, 'fvalue-range-int-max' => 0 ), 'fvalue-range-float' => array( 'fvalue-range-float-min' => 0.00, 'fvalue-range-float-max' => 0.00 ), 'fvalue-range-date' => array( 'fvalue-range-date-min' => 0, 'fvalue-range-date-max' => 0 ), ) ;
					$InsertSaved[$KeyE[0]] = $Value ;
				}
                elseif ( $KeyE[1] == 'float' ) {
				
                	$Insert[] = array( 'fid' => $KeyE[0], 'fvalue-string' => '', 'fvalue-int' => 0, 'fvalue-float' => $Value, 'fvalue-image' => 0, 'fvalue-datetime' => 0, 'fvalue-boolean' => false, 'fvalue-date' => 0, 'fvalue-range-int' => array( 'fvalue-range-int-min' => 0, 'fvalue-range-int-max' => 0 ), 'fvalue-range-float' => array( 'fvalue-range-float-min' => 0.00, 'fvalue-range-float-max' => 0.00 ), 'fvalue-range-date' => array( 'fvalue-range-date-min' => 0, 'fvalue-range-date-max' => 0 ), ) ;
					$InsertSaved[$KeyE[0]] = $Value ;
				}
                elseif ( $KeyE[1] == 'datetime' ) {
				
                	$Date = explode( '/', $Value ) ;
					$Insert[] = array( 'fid' => $KeyE[0], 'fvalue-string' => '', 'fvalue-int' => 0, 'fvalue-float' => 0, 'fvalue-image' => 0, 'fvalue-datetime' => mktime( 0, 0, 0, $Date[1], $Date[0], $Date[2] ), 'fvalue-boolean' => false, 'fvalue-date' => 0, 'fvalue-range-int' => array( 'fvalue-range-int-min' => 0, 'fvalue-range-int-max' => 0 ), 'fvalue-range-float' => array( 'fvalue-range-float-min' => 0.00, 'fvalue-range-float-max' => 0.00 ), 'fvalue-range-date' => array( 'fvalue-range-date-min' => 0, 'fvalue-range-date-max' => 0 ), ) ;
				}
                elseif ( $KeyE[1] == 'image' ) {

                    $Value = str_ireplace('%2F','/',$Value);
                	$Insert[] = array( 'fid' => $KeyE[0], 'fvalue-string' => '', 'fvalue-int' => 0, 'fvalue-float' => 0, 'fvalue-image' => file_get_contents( html_entity_decode($Value) ), 'fvalue-datetime' => 0, 'fvalue-boolean' => false, 'fvalue-date' => 0, 'fvalue-range-int' => array( 'fvalue-range-int-min' => 0, 'fvalue-range-int-max' => 0 ), 'fvalue-range-float' => array( 'fvalue-range-float-min' => 0.00, 'fvalue-range-float-max' => 0.00 ), 'fvalue-range-date' => array( 'fvalue-range-date-min' => 0, 'fvalue-range-date-max' => 0 ), ) ;
				}
			}
            else if ( $Value == '' ) {
                    
                if ( $KeyE[1] == 'string' || $KeyE[1] == 'int' || $KeyE[1] == 'float' ) {
                    
                    $InsertSaved[$KeyE[0]] = $Value;
                }
            }
		}

     //   echo 'step4<br/>';
	//	var_dump($_POST);
		
		
		
		$FieldsData = $this->getAllegroData( 'GetSellFormFieldsExt', $Options = '' ) ;
		
		
		
        foreach ( $FieldsData['sell-form-fields'] as $Field ) {
		
        	$Search = $this->search( $Insert, 'fid', $Field['sell-form-id'] ) ;
		
        	if ( $Search == false ) {
		
        		if ( $Field['sell-form-opt'] == 1 ) {
		
        			if ( $Field['sell-form-res-type'] == 1 ) {
						$Insert[] = array( 'fid' => $Field['sell-form-id'], 'fvalue-string' => $Field['sell-form-def-value'], 'fvalue-int' => 0, 'fvalue-float' => 0, 'fvalue-image' => 0, 'fvalue-datetime' => 0, 'fvalue-boolean' => false, 'fvalue-date' => 0, 'fvalue-range-int' => array( 'fvalue-range-int-min' => 0, 'fvalue-range-int-max' => 0 ), 'fvalue-range-float' => array( 'fvalue-range-float-min' => 0.00, 'fvalue-range-float-max' => 0.00 ), 'fvalue-range-date' => array( 'fvalue-range-date-min' => 0, 'fvalue-range-date-max' => 0 ), ) ;
						$InsertSaved[$Field['sell-form-id']] = $Field['sell-form-def-value'] ;
					}
                    elseif ( $Field['sell-form-res-type'] == 2 ) {
					
                    	$Insert[] = array( 'fid' => $Field['sell-form-id'], 'fvalue-string' => '', 'fvalue-int' => $Field['sell-form-def-value'], 'fvalue-float' => 0, 'fvalue-image' => 0, 'fvalue-datetime' => 0, 'fvalue-boolean' => false, 'fvalue-date' => 0, 'fvalue-range-int' => array( 'fvalue-range-int-min' => 0, 'fvalue-range-int-max' => 0 ), 'fvalue-range-float' => array( 'fvalue-range-float-min' => 0.00, 'fvalue-range-float-max' => 0.00 ), 'fvalue-range-date' => array( 'fvalue-range-date-min' => 0, 'fvalue-range-date-max' => 0 ), ) ;
						$InsertSaved[$Field['sell-form-id']] = $Field['sell-form-def-value'] ;
					}
                    elseif ( $Field['sell-form-res-type'] == 3 ) {
					
                    	$Insert[] = array( 'fid' => $Field['sell-form-id'], 'fvalue-string' => '', 'fvalue-int' => 0, 'fvalue-float' => $Field['sell-form-def-value'], 'fvalue-image' => 0, 'fvalue-datetime' => 0, 'fvalue-boolean' => false, 'fvalue-date' => 0, 'fvalue-range-int' => array( 'fvalue-range-int-min' => 0, 'fvalue-range-int-max' => 0 ), 'fvalue-range-float' => array( 'fvalue-range-float-min' => 0.00, 'fvalue-range-float-max' => 0.00 ), 'fvalue-range-date' => array( 'fvalue-range-date-min' => 0, 'fvalue-range-date-max' => 0 ), ) ;
						$InsertSaved[$Field['sell-form-id']] = $Field['sell-form-def-value'] ;
					}
                    elseif ( $Field['sell-form-res-type'] == 9 ) {
					
                    	$Insert[] = array( 'fid' => $Field['sell-form-id'], 'fvalue-string' => '', 'fvalue-int' => 0, 'fvalue-float' => 0, 'fvalue-image' => 0, 'fvalue-datetime' => $Field['sell-form-def-value'], 'fvalue-boolean' => false, 'fvalue-date' => 0, 'fvalue-range-int' => array( 'fvalue-range-int-min' => 0, 'fvalue-range-int-max' => 0 ), 'fvalue-range-float' => array( 'fvalue-range-float-min' => 0.00, 'fvalue-range-float-max' => 0.00 ), 'fvalue-range-date' => array( 'fvalue-range-date-min' => 0, 'fvalue-range-date-max' => 0 ), ) ;
					}
				}
			}
			else {
				
                for ( $i = 0; $i < count( $Insert ); $i++ ) {
				
                	if ( $Field['sell-form-id'] == $Insert[$i]['fid'] ) {
				
                		if ( $Field['sell-form-res-type'] == 1 && $Insert[$i]['fvalue-string'] == '' ) {
							$Insert[$i]['fvalue-string'] = $Field['sell-form-def-value'] ;
						}
                        elseif ( $Field['sell-form-res-type'] == 2 && $Insert[$i]['fvalue-int'] == '' ) {
						
                        	$Insert[$i]['fvalue-int'] = $Field['sell-form-def-value'] ;
						}
                        elseif ( $Field['sell-form-res-type'] == 3 && $Insert[$i]['fvalue-float'] == '' ) {
						
                        	$Insert[$i]['fvalue-float'] = $Field['sell-form-def-value'] ;
						}
                        elseif ( $Field['sell-form-res-type'] == 9 && $Insert[$i]['fvalue-datetime'] == '' ) {
						
                        	$Insert[$i]['fvalue-datetime'] = $Field['sell-form-def-value'] ;
							$Insert[$i]['fvalue-date'] = $Field['sell-form-def-value'] ;
							$Insert[$i]['fvalue-range-int'] = array( 'fvalue-range-int-min' => 0, 'fvalue-range-int-max' => 0 ) ;
							$Insert[$i]['fvalue-range-float'] = array( 'fvalue-range-float-min' => 0.00, 'fvalue-range-float-max' => 0.00 ) ;
							$Insert[$i]['fvalue-range-date'] = array( 'fvalue-range-date-min' => 0, 'fvalue-range-date-max' => 0 ) ;
						}
					}
				}
			}
		}
        
    // hot fix
    $target=false;
    foreach ($Insert as $key => $field) {
        if(isset($field['fid']))
		{
			if($field['fid']==='15'){
				$target = $key;
			}
		}
    }
	
	
	
	if($target)
	{
		
		$Insert[$target]['fvalue-image']=file_get_contents( DIR_IMAGE.$Producttt['image'] );
        $Insert[$target+1]['fvalue-image']=file_get_contents( DIR_IMAGE.$Producttt['image'] );
      
	}
	
	//var_dump($Insert);
	//	echo 'step5<br/>';
	//var_dump($Insert);
	
		
		try {
            
            $allegro = new AllegroWebAPI() ;
			// miechu
			$allegro_id=$this->config->get('config_allegro_id');
			$login=$this->config->get('config_allegro_login');
			$pass=$this->config->get('config_allegro_pass');
			$webapi=$this->config->get('config_allegro_webapi');
			$allegro->setInitial($allegro_id,$login,$pass,$webapi);
			//
			$allegro->Login() ;
			$AllegroData = $allegro->object_to_array( $allegro->CheckNewAuctionExt( $Insert ) ) ;
			
			
		
			
			
		//	var_dump($AllegroData);
			
			//file_put_contents( DIR_CACHE . 'AllegroSavedInputs.txt', json_encode( $InsertSaved ) ) ;

            file_put_contents( DIR_CACHE . 'AllegroSavedInputs.txt', json_encode( $saved ) ) ;

            if ( isset( $AllegroData['item-price-desc'] ) ) {
            	
			
				
                $this->data['Data'][0]['KosztAukcji'] = $AllegroData['item-price'] ;
				$this->data['Data'][0]['OpisKosztuAukcji'] = $AllegroData['item-price-desc'] ;
				
			
				
                if ( !isset( $_GET['checkauction'] ) ) {
				
                	$Wystawiona = $allegro->object_to_array( $allegro->NewAuctionExt( array( 'fields' => $Insert, 'private' => '', 'local-id' => '' ) ) ) ;
					
					

                    $this->data['Data'][0]['Identyfikator'] = $Wystawiona['item-id'] ;
					$this->data['Data'][0]['Link'] = "<a href='http://allegro.pl/ShowItem2.php?item=" . $Wystawiona['item-id'] . "'>http://allegro.pl/ShowItem2.php?item=" . $Wystawiona['item-id'] . "</a>" ;
				}
				
                //file_put_contents( DIR_CACHE . 'AllegroSavedInputs.txt', json_encode( $InsertSaved ) ) ;
			}
			else {
				
                $this->data['Data'][0]['AllegroData'] = $AllegroData ;
                //file_put_contents( DIR_CACHE . 'AllegroSavedInputs.txt', json_encode( $InsertSaved ) ) ;
			}
		}
		catch ( SoapFault $fault ) {
			
            $this->data['Data'][0]['Fault'] = $fault->faultstring ;
		}
		
        $this->template = 'allegro/added.tpl' ;
		$this->children = array( 'common/header', 'common/footer' ) ;
		$this->response->setOutput( $this->render( true ), $this->config->get( 'config_compression' ) ) ;
	}
    
	private function Template() {
		
        $this->load->model( 'allegro/product' ) ;
		echo $this->model_allegro_product->showTemplate( $_GET['name'] ) ;
	}
       
	private function getAllegroData( $AllegroMetod, $Options = '' ) {
		
        if ( !file_exists( DIR_CACHE . "allegro_$AllegroMetod.txt" ) ) {
		
        	try {
		
        		$allegro = new AllegroWebAPI() ;
				// miechu 
				$allegro_id=$this->config->get('config_allegro_id');
			$login=$this->config->get('config_allegro_login');
			$pass=$this->config->get('config_allegro_pass');
			$webapi=$this->config->get('config_allegro_webapi');
			$allegro->setInitial($allegro_id,$login,$pass,$webapi);
				// miechu
				$allegro->Login() ;
		
        		if ( $Options != '' ) {
		
        			eval( '$AllegroData = $allegro->' . $AllegroMetod . '($Options);' ) ;
					$AllegroData = $allegro->object_to_array( $AllegroData ) ;
				}
				else {
		
        			eval( '$AllegroData = $allegro->' . $AllegroMetod . '();' ) ;
					$AllegroData = $allegro->object_to_array( $AllegroData ) ;
				}
		
        		file_put_contents( DIR_CACHE . "allegro_$AllegroMetod.txt", json_encode( $AllegroData ) ) ;
			}
			catch ( SoapFault $fault ) {
		
        		print ( $fault->faultstring ) ;
			}
		}
		else {
		
        	$AllegroData = file_get_contents( DIR_CACHE . "allegro_$AllegroMetod.txt" ) ;
			$AllegroData = json_decode( $AllegroData, true ) ;
		}
		
        return $AllegroData ;
	}
    
    public function Updatecategories() {
        
        $Time = time() ;
        $this->load->library( 'class.allegrowebapi' ) ;
		
        
        set_time_limit(7200) ;
        ini_set('mysql.connect_timeout', 7200);
        ini_set('default_socket_timeout', 7200);
        $PartLimit = 5000 ;
        
        try {
		
    		$allegro = new AllegroWebAPI() ;
			$allegro_id=$this->config->get('config_allegro_id');
			$login=$this->config->get('config_allegro_login');
			$pass=$this->config->get('config_allegro_pass');
			$webapi=$this->config->get('config_allegro_webapi');
			$allegro->setInitial($allegro_id,$login,$pass,$webapi);
			$allegro->Login() ;
            
            $CatCount = $allegro->GetCatsDataCount() ;
            $CatCount = $CatCount['cats-count'] ;
            $PartCount = $CatCount / $PartLimit ;
            
            for( $i = 0 ; $i < $PartCount ; $i++ ) {
                
                //if ( !isset($_SESSION["catpart_$i"]) ) {
                    
                    $Offset = $i ;//* $PartLimit - $i ;
                    $AllegroData = $allegro->GetCatsDataLimit(array('offset'=>$Offset, 'package-element'=>$PartLimit)) ;
                    $AllegroData = $allegro->object_to_array( $AllegroData ) ;
                    
                    //$_SESSION["catpart_$i"] = 1 ;
                    
                    $Left = $PartCount - ($i + 1) ;
                    
                    $this->load->model( 'allegro/product' ) ;
                    
                    if ( $i == 0 ) {
                        
                        $this->model_allegro_product->UpdateCategoriesToDB($AllegroData, true) ;
                    } else {
                        
                        $this->model_allegro_product->UpdateCategoriesToDB($AllegroData) ;
                    }
                    
                    $Zaktualizowano = ($i + 1) * $PartLimit ;
                    $Zostalo = $Left * $PartLimit ;
                    
                    
                    /*
                    if ( $Left != 0 ) {
                        
                        echo "<BR>odswiez te strone aby zaktualizowac nastepna czesc kategorii" ;
                    } else {
                        
                        for( $i = 0 ; $i < $PartCount ; $i++ ) {
                            
                            unset($_SESSION["catpart_$i"]) ;
                        }
                    }
                    */
                    
                    //break ;
                //}
            }
            
            echo "zaktualizowano $CatCount kategorii" ;
            $Time = time() - $Time ;
            $Time = round($Time / 60,2) ;
            
            echo "<BR><BR>aktualizacja trwala: $Time minut" ;
		}
		catch ( SoapFault $fault ) {
	
    		print ( $fault->faultstring ) ;
		}
    }
    
    public function Updatecategoriescron() {
        
        $Time = time() ;
        $this->load->library( 'class.allegrowebapi' ) ;
		
        
        set_time_limit(45) ;
        ini_set('mysql.connect_timeout', 45);
        ini_set('default_socket_timeout', 45);
        $PartLimit = 3 ;
        
        $Session = json_decode(file_get_contents(DIR_CACHE.'allegro_catsession.txt'),true) ;
        
        try {
		
    		$allegro = new AllegroWebAPI() ;
			// miechu
			$allegro_id=$this->config->get('config_allegro_id');
			$login=$this->config->get('config_allegro_login');
			$pass=$this->config->get('config_allegro_pass');
			$webapi=$this->config->get('config_allegro_webapi');
			$allegro->setInitial($allegro_id,$login,$pass,$webapi);
			//
			$allegro->Login() ;
            
            $CatCount = $allegro->GetCatsDataCount() ;
            $CatCount = $CatCount['cats-count'] ;
            $PartCount = $CatCount / $PartLimit ;
            
            for( $i = 0 ; $i < $PartCount ; $i++ ) {
                
                if ( !isset($Session["catpart_$i"]) ) {
                    
                    $Offset = $i * $PartLimit - $i ;
                    $AllegroData = $allegro->GetCatsDataLimit(array('offset'=>$Offset, 'package-element'=>$PartLimit)) ;
                    $AllegroData = $allegro->object_to_array( $AllegroData ) ;
                    
                    $Session["catpart_$i"] = 1 ;
                    
                    $Left = $PartCount - ($i + 1) ;
                    
                    $this->load->model( 'allegro/product' ) ;
                    
                    if ( $i == 0 ) {
                        
                        $this->model_allegro_product->UpdateCategoriesToDB($AllegroData, true) ;
                    } else {
                        
                        $this->model_allegro_product->UpdateCategoriesToDB($AllegroData) ;
                    }
                    
                    $Zaktualizowano = ($i + 1) * $PartLimit ;
                    $Zostalo = $Left * $PartLimit ;
                    echo "zaktualizowano $Zaktualizowano kategorii zostalo mniej niz $Zostalo kategorii" ;
                    
                    if ( $Left != 0 ) {
                        
                        echo "<BR>odswiez te strone aby zaktualizowac nastepna czesc kategorii" ;
                    } else {
                        
                        for( $i = 0 ; $i < $PartCount ; $i++ ) {
                            
                            unset($Session["catpart_$i"]) ;
                        }
                    }
                    
                    break ;
                }
            }
            
            $Time = time() - $Time ;
            $Time = round($Time / 60,2) ;
            
            echo "<BR><BR>aktualizacja trwala: $Time minut" ;
            file_put_contents(DIR_CACHE.'allegro_catsession.txt',json_encode($Session)) ;
		}
		catch ( SoapFault $fault ) {
	
    		print ( $fault->faultstring ) ;
		}
    }
    
	private function search( $array, $key, $value ) {
	
		$Return = false ;
	
    	foreach ( $array as $a ) {
	
    		if ( $a[$key] == $value ) {

				$Return = true ;
				break ;
			}
		}
	
    	return $Return ;
	}
	
    private function objecttoarray( $object ) {
	
    	if ( is_array( $object ) || is_object( $object ) ) {
	
    		$array = array() ;
	
    		foreach ( $object as $key => $value ) {
	
    			$array[$key] = $this->objecttoarray( $value ) ;
			}
	
    		return $array ;
		}
	
    	return $object ;
	}
    
    public function Getcategorybyparent() {
        
        $this->load->model( 'allegro/product' ) ;
        
        $Cats = $this->model_allegro_product->GetCategoriesByCatParent($_GET['allegro_category_id']) ;
        
        echo '<option>Wybierz subkategorię...</option>' ;
        
        foreach( $Cats as $Cat ) {
            
            echo '<option value="'.$Cat['cat_id'].'">'.$Cat['cat_name'].'</option>' ;
        }
    }
}

?>