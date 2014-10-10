<?php

class ControllerAllegroCategory extends Controller {

    private $error = array();
    
    public function index() {

        $this->document->setTitle($this->language->get('allegro_heading_title'));
        $this->load->model( 'allegro/category' );
        $this->load->model( 'allegro/product' );
        $this->data['heading_title'] = $this->language->get( 'allegro_heading_title' );
        $this->data['button_insert'] = $this->language->get( 'button_insert' );
        $this->data['button_cancel'] = $this->language->get( 'button_cancel' );
        $this->data['insert'] = 'index.php?route=allegro/category/insert&token=' . $this->session->data['token'] . '&category_id=' . $_GET['category_id'] . '&checkauction=1' ;
        $this->data['cancel'] = 'index.php?route=catalog/category&token=' . $this->session->data['token'] . '&category_id=' . $_GET['category_id'] ;
        $this->data['success'] = '';
        $_SESSION['getcategoryoptions'] = 'index.php?route=allegro/category/getcategoryoptions&token=' . $this->session->data['token'] . '&' ;
        $_SESSION['getcategorybyparent'] = 'index.php?route=allegro/product/getcategorybyparent&token=' . $this->session->data['token'] . '&' ;
        
        $Saved = file_get_contents( DIR_CACHE . 'AllegroSavedInputs.txt' );

        $this->data['saved'] = $this->objecttoarray( json_decode( $Saved ) );

        if ( isset( $this->error['warning'] ) ) { $this->data['error_warning'] = $this->error['warning']; }
        else { $this->data['error_warning'] = ''; }

        if ( isset( $this->error['name'] ) ) { $this->data['error_name'] = $this->error['name']; }
        else { $this->data['error_name'] = ''; }

        $this->document->breadcrumbs = array();
        $this->document->breadcrumbs[] = array( 'href' => HTTPS_SERVER .
            'index.php?route=allegro/category&token=' . $this->session->data['token'],
            'text' => $this->language->get( 'text_home' ), 'separator' => false );

        $this->load->library( 'class.allegrowebapi' );

        $ProductsByCategory = $this->model_allegro_category->getProductsByCategory( $_GET['category_id'] );
        
        //$this->model_allegro_product->AddCategoriesToDB($AllegroCategories) ;
        
        $this->data['AllegroStates'] = $this->getAllegroData( 'GetStatesInfo', $Options = '' );
        $AllegroShipments = $this->getAllegroData( 'GetSellFormFieldsExt', $Options = '' );

        foreach ( $ProductsByCategory as $Product ) {

            $ProductId = $Product['product_id'];
            $Prod = $this->model_allegro_category->getProduct( $ProductId );
            $ProdSpecials = $this->model_allegro_category->getProductSpecial( $ProductId );
            
            if ( isset($ProdSpecials[0]['special']) ) {
                $ProdSpecials[0]['special'] = $ProdSpecials[0]['special'] * 1.23 ; 
            }
            
            $this->load->library( 'tax' ) ;
            $this->tax = new Tax($this->registry) ;
        
            $Prod['price'] = $this->currency->format($this->tax->calculate($Prod['price'], $Prod['tax_class_id'], $this->config->get('config_tax')));
            //$Prod['price'] = $Prod['price'] * 1.23 ;
            
            $ProductImages = $this->model_allegro_category->getProductImages( $ProductId ) ;
    		if ( !empty($Prod['image']) ) {
                $ProductImages[]['image'] = $Prod['image'] ;
    		}
            
            $this->data['Products'][] = array( 
                'ProductId' => $ProductId,
                'Product' => $Prod,
                'ProductOptions' => $this->model_allegro_category->getProductOptions( $ProductId ),
                'ProductImages' => $ProductImages,
                'ProductSpecial' => $ProdSpecials, 
                'ProductSpecials' => $this->model_allegro_category->getProductSpecials( $ProductId ), 
                'ProductTotalSpecials' => $this->model_allegro_category->getTotalProductSpecials( $ProductId ),
                'ProductRelated' => $this->model_allegro_category->getProductRelated( $ProductId ),
                'ProductCategories' => $this->model_allegro_category->getCategories( $ProductId ),
                'ProductTemplates' => $this->model_allegro_category->getTemplates(),
            );

            $_SESSION[$ProductId]['product_quantity'] = $Prod['quantity'];

            if ( isset( $ProdSpecials[0]['special'] ) ) {
                $_SESSION[$ProductId]['product_specials'] = substr( $ProdSpecials[0]['special'], 0, -2 );
            }
            else {
                $_SESSION[$ProductId]['product_specials'] = substr( $Prod['price'], 0, -2 );
            }
        }

        for ( $i = 33; $i < 84; $i++ ) {

            if ( $AllegroShipments['sell-form-fields'][$i]['sell-form-res-type'] == 1 ) {

                $FormType = 'string';
            }
            else if ( $AllegroShipments['sell-form-fields'][$i]['sell-form-res-type'] == 2 ) {

                $FormType = 'int';
            }
            else if ( $AllegroShipments['sell-form-fields'][$i]['sell-form-res-type'] == 3 ) {

                $FormType = 'float';
            }

            $this->data['AllegroShipments'][] = array( 'FormId' => $AllegroShipments['sell-form-fields'][$i]['sell-form-id'],
                'Title' => $AllegroShipments['sell-form-fields'][$i]['sell-form-title'],
                'FormType' => $FormType );

        }

        /*
        if ( !file_exists( DIR_CACHE.'AllegroCategories.txt' ) ) {

            foreach ( $AllegroCategories['cats-list'] as $AllegroCategory ) {

                if ( $AllegroCategory['cat-parent'] == 0 ) {

                    $this->data['AllegroCategories'][$AllegroCategory['cat-id']] = array( 
                        'cat-id' => $AllegroCategory['cat-id'], 
                        'cat-name' => $AllegroCategory['cat-name']
                    );
                }
                else {

                    if ( !isset( $this->data['AllegroCategories'][$AllegroCategory['cat-parent']] ) ) {

                        foreach ( $this->data['AllegroCategories'] as $ACat ) {

                            if ( isset( $ACat['cat-parents'] ) ) {

                                for ( $i = 0; $i < count( $ACat['cat-parents'] ); $i++ ) {

                                    if ( $ACat['cat-parents'][$i]['cat-id'] == $AllegroCategory['cat-parent'] ) {

                                        $this->data['AllegroCategories'][$ACat['cat-id']]['cat-parents'][$i]['cat-parents'][] = array(
                                            'cat-id' => $AllegroCategory['cat-id'], 
                                            'cat-name' => $AllegroCategory['cat-name'],
                                            'cat-parent' => $AllegroCategory['cat-parent']
                                        );
                                    }

                                    if ( isset( $ACat['cat-parents'][$i]['cat-parents'] ) ) {

                                        for ( $ii = 0; $ii < count( $ACat['cat-parents'][$i]['cat-parents'] ); $ii++ ) {

                                            if ( $ACat['cat-parents'][$i]['cat-parents'][$ii]['cat-id'] == $AllegroCategory['cat-parent'] ) {

                                                $this->data['AllegroCategories'][$ACat['cat-id']]['cat-parents'][$i]['cat-parents'][$ii]['cat-parents'][] = array( 
                                                    'cat-id' => $AllegroCategory['cat-id'], 
                                                    'cat-name' => $AllegroCategory['cat-name'],
                                                    'cat-parent' => $AllegroCategory['cat-parent']
                                                );
                                            }

                                            if ( isset( $ACat['cat-parents'][$i]['cat-parents'][$ii]['cat-parents'] ) ) {

                                                for ( $iii = 0; $iii < count( $ACat['cat-parents'][$i]['cat-parents'][$ii]['cat-parents'] ); $iii++ ) {

                                                    if ( $ACat['cat-parents'][$i]['cat-parents'][$ii]['cat-parents'][$iii]['cat-id'] == $AllegroCategory['cat-parent'] ) {

                                                        $this->data['AllegroCategories'][$ACat['cat-id']]['cat-parents'][$i]['cat-parents'][$ii]['cat-parents'][$iii]['cat-parents'][] = array( 
                                                            'cat-id' => $AllegroCategory['cat-id'], 
                                                            'cat-name' => $AllegroCategory['cat-name'],
                                                            'cat-parent' => $AllegroCategory['cat-parent']
                                                        );
                                                    }

                                                    if ( isset( $ACat['cat-parents'][$i]['cat-parents'][$ii]['cat-parents'][$iii]['cat-parents'] ) ) {

                                                        for ( $iiii = 0; $iiii < count( $ACat['cat-parents'][$i]['cat-parents'][$ii]['cat-parents'][$iii]['cat-parents'] ); $iiii++ ) {

                                                            if ( $ACat['cat-parents'][$i]['cat-parents'][$ii]['cat-parents'][$iii]['cat-parents'][$iiii]['cat-id'] == $AllegroCategory['cat-parent'] ) {

                                                                $this->data['AllegroCategories'][$ACat['cat-id']]['cat-parents'][$i]['cat-parents'][$ii]['cat-parents'][$iii]['cat-parents'][$iiii]['cat-parents'][] = array( 
                                                                    'cat-id' => $AllegroCategory['cat-id'], 
                                                                    'cat-name' => $AllegroCategory['cat-name'],
                                                                    'cat-parent' => $AllegroCategory['cat-parent']
                                                                );
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

                        $this->data['AllegroCategories'][$AllegroCategory['cat-parent']]['cat-parents'][] =
                            array( 'cat-id' => $AllegroCategory['cat-id'], 'cat-name' => $AllegroCategory['cat-name'],
                            'cat-parent' => $AllegroCategory['cat-parent'] );
                    }
                }
            }

            file_put_contents( DIR_CACHE.'AllegroCategories.txt', json_encode( $this->data['AllegroCategories'] ) );
        }
        else {

            $this->data['AllegroCategories'] = json_decode( file_get_contents( DIR_CACHE.'AllegroCategories.txt' ), true );
        }
        */
        
        $this->data['AllegroCategories'] = $this->model_allegro_product->GetCategoriesByCatParent(0) ;

        $this->template = 'allegro/category.tpl';
        $this->children = array( 'common/header', 'common/footer' );
        $this->response->setOutput( $this->render( true ), false );
    }

    public function Insert() {

        $this->load->library( 'class.allegrowebapi' );
        $this->load->model( 'allegro/product' ) ;

        if ( !isset( $_POST['a'] ) ) {
            echo 'Błąd: Nie wybrałeś żadnych produktów';
            exit;
        }

        foreach ( $_POST['a'] as $PostActive ) {

            $Post[] = array_merge( $_POST['p'][$PostActive], $_POST['k'] );
        }

        $DataIterator = 0;

        foreach ( $Post as $P ) {
                        
            ksort( $P, SORT_NUMERIC );

            $ProductModel = $this->model_allegro_product->getProduct( $P['model'] ) ;
            $ProductModel = $ProductModel['model'] ;

            if ( isset( $P['15-int'] ) ) {

                $Ints15 = 0;

                foreach ( $P['15-int'] as $Int15 ) {

                    $Ints15 = $Ints15 + $Int15;
                }

                $P['15-int'] = $Ints15;
            }

            if ( isset( $P['35-int'] ) ) {

                $Ints15 = 0;

                foreach ( $P['35-int'] as $Int15 ) {

                    $Ints15 = $Ints15 + $Int15;
                }

                $P['35-int'] = $Ints15;
            }

            if ( isset( $P['14-int'] ) ) {

                $Ints15 = 0;

                foreach ( $P['14-int'] as $Int15 ) {

                    $Ints15 = $Ints15 + $Int15;
                }

                $P['14-int'] = $Ints15;
            }

            if ( isset( $P['13-int'] ) ) {

                $Ints15 = 0;

                foreach ( $P['13-int'] as $Int15 ) {

                    $Ints15 = $Ints15 + $Int15;
                }

                $P['13-int'] = $Ints15;
            }

            if ( isset( $P['16-image'] ) ) {

                $ExtImages = '<a href="' . $P['16-image'] . '"><img src="' . $P['16-image'] . '"></a>';
            }

            else {

                $ExtImages = '';
            }

            for ( $i = 0; $i < 20; $i++ ) {

                if ( isset( $P["imgs$i"] ) ) {

                    $ExtImages .= '<a href="' . $P["imgs$i"] . '"><img src="' . $P["imgs$i"] . '"></a>';
                }
            }
            
            if ( !isset( $_GET['checkauction'] ) ) {

                $P['24-string'] = base64_decode( $P['24-string'] );
                $P['24-string'] = html_entity_decode( $P['24-string'] );
                $P['24-string'] = str_replace( array( '{PRODUCT_NAME}', '{PRODUCT_DESCRIPTION}', '{IMG}', '{PRODUCT_MODEL}' ), array( $P['1-string'], $P['24-string'], $ExtImages, $ProductModel ), str_replace('""','"',html_entity_decode(base64_decode( $P['allegrotemplate'] ))) );
            }
            
            $FieldsData = $this->getAllegroData( 'GetSellFormFieldsExt', $Options = '' );

            foreach ( $P as $Key => $Value ) {
                
                $KeyE = explode( '-', $Key );
                
                if ( strpos( $Key, 'active' ) === false && strpos( $Key, 'imgs' ) === false && $Key != 'allegrotemplate' && $Key != 'model' && $Value != '' ) {

                    if ( $KeyE[1] == 'string' ) {

                        $Insert[] = array( 
                            'fid' => $KeyE[0], 
                            'fvalue-string' => $Value, 
                            'fvalue-int' => 0, 
                            'fvalue-float' => 0, 
                            'fvalue-image' => 0, 
                            'fvalue-datetime' => 0,
                            'fvalue-boolean' => false, 
                            'fvalue-date' => 0, 
                            'fvalue-range-int' => array( 
                                'fvalue-range-int-min' => 0, 
                                'fvalue-range-int-max' => 0 
                            ), 
                            'fvalue-range-float' => array( 
                                'fvalue-range-float-min' => 0.00, 
                                'fvalue-range-float-max' => 0.00 
                            ), 'fvalue-range-date' => array( 
                                'fvalue-range-date-min' => 0, 
                                'fvalue-range-date-max' => 0 
                            ), 
                        );

                        $InsertSaved[$KeyE[0]] = $Value;
                    }

                    else if ( $KeyE[1] == 'int' ) {

                        $Insert[] = array( 
                            'fid' => $KeyE[0], 
                            'fvalue-string' => '', 
                            'fvalue-int' => $Value,
                            'fvalue-float' => 0, 
                            'fvalue-image' => 0, 
                            'fvalue-datetime' => 0,
                            'fvalue-boolean' => false, 
                            'fvalue-date' => 0, 
                            'fvalue-range-int' => array( 
                                'fvalue-range-int-min' => 0, 
                                'fvalue-range-int-max' => 0 
                            ), 
                            'fvalue-range-float' => array( 
                                'fvalue-range-float-min' => 0.00, 
                                'fvalue-range-float-max' => 0.00 
                            ), 'fvalue-range-date' => array( 
                                'fvalue-range-date-min' => 0, 
                                'fvalue-range-date-max' => 0 
                            ), 
                        );

                        $InsertSaved[$KeyE[0]] = $Value;
                    }
                    else if ( $KeyE[1] == 'float' ) {

                        $Insert[] = array( 
                            'fid' => $KeyE[0], 
                            'fvalue-string' => '', 
                            'fvalue-int' => 0,
                            'fvalue-float' => $Value, 
                            'fvalue-image' => 0, 
                            'fvalue-datetime' => 0,
                            'fvalue-boolean' => false, 
                            'fvalue-date' => 0, 
                            'fvalue-range-int' => array( 
                                'fvalue-range-int-min' => 0, 
                                'fvalue-range-int-max' => 0 
                            ), 
                            'fvalue-range-float' => array( 
                                'fvalue-range-float-min' => 0.00, 
                                'fvalue-range-float-max' => 0.00 
                            ), 'fvalue-range-date' => array( 
                                'fvalue-range-date-min' => 0, 
                                'fvalue-range-date-max' => 0 
                            ), 
                        );

                        $InsertSaved[$KeyE[0]] = $Value;
                    }

                    else if ( $KeyE[1] == 'datetime' ) {

                        $Date = explode( '/', $Value );

                        $Insert[] = array( 
                            'fid' => $KeyE[0], 
                            'fvalue-string' => '', 
                            'fvalue-int' => 0,
                            'fvalue-float' => 0, 
                            'fvalue-image' => 0, 
                            'fvalue-datetime' => mktime( 0, 0, 0, $Date[1], $Date[0], $Date[2] ), 
                            'fvalue-boolean' => false, 
                            'fvalue-date' => 0,
                            'fvalue-range-int' => array( 
                                'fvalue-range-int-min' => 0, 
                                'fvalue-range-int-max' => 0 ), 
                            'fvalue-range-float' => array( 
                                'fvalue-range-float-min' => 0.00,
                                'fvalue-range-float-max' => 0.00 
                            ), 'fvalue-range-date' => array( 
                                'fvalue-range-date-min' => 0, 
                                'fvalue-range-date-max' => 0 
                            ), 
                        );
                    } 
                    else if ( $KeyE[1] == 'image' ) {

                        $Insert[] = array( 
                            'fid' => $KeyE[0], 
                            'fvalue-string' => '', 
                            'fvalue-int' => 0,
                            'fvalue-float' => 0, 
                            'fvalue-image' => file_get_contents( $Value ),
                            'fvalue-datetime' => 0, 
                            'fvalue-boolean' => false, 
                            'fvalue-date' => 0,
                            'fvalue-range-int' => array( 
                                'fvalue-range-int-min' => 0, 
                                'fvalue-range-int-max' => 0 
                            ), 'fvalue-range-float' => array( 
                                'fvalue-range-float-min' => 0.00,
                                'fvalue-range-float-max' => 0.00 
                            ), 'fvalue-range-date' => array( 
                                'fvalue-range-date-min' => 0, 
                                'fvalue-range-date-max' => 0 
                            ), 
                        );
                    }
                }
                else if ( $Value == '' ) {
                    
                    if ( $KeyE[1] == 'string' || $KeyE[1] == 'int' || $KeyE[1] == 'float' ) {
                        
                        $InsertSaved[$KeyE[0]] = $Value;
                    }
                }
            }

            foreach ( $FieldsData['sell-form-fields'] as $Field ) {

                $Search = $this->search( $Insert, 'fid', $Field['sell-form-id'] );

                if ( $Search == false ) {
    
                    if ( $Field['sell-form-opt'] == 1 ) {
                    
                        if ( $Field['sell-form-res-type'] == 1 ) {
    
                            $Insert[] = array(
                                'fid' => $Field['sell-form-id'],
                                'fvalue-string' => $Field['sell-form-def-value'],
                                'fvalue-int' => 0,
                                'fvalue-float' => 0,
                                'fvalue-image' => 0,
                                'fvalue-datetime' => 0,
                                'fvalue-boolean' => false,
                                'fvalue-date' => 0,
                                'fvalue-range-int' => array(
                                    'fvalue-range-int-min' => 0,
                                    'fvalue-range-int-max' => 0
                                ),
                                'fvalue-range-float' => array(
                                    'fvalue-range-float-min' => 0.00,
                                    'fvalue-range-float-max' => 0.00
                                ),
                                'fvalue-range-date' => array(
                                    'fvalue-range-date-min' => 0,
                                    'fvalue-range-date-max' => 0
                                ),
                            ) ;
                            
                            $InsertSaved[$Field['sell-form-id']] = $Field['sell-form-def-value'];
                        }
                        else if ( $Field['sell-form-res-type'] == 2 ) {
    
                            $Insert[] = array(
                                'fid' => $Field['sell-form-id'],
                                'fvalue-string' => '',
                                'fvalue-int' => $Field['sell-form-def-value'],
                                'fvalue-float' => 0,
                                'fvalue-image' => 0,
                                'fvalue-datetime' => 0,
                                'fvalue-boolean' => false,
                                'fvalue-date' => 0,
                                'fvalue-range-int' => array(
                                    'fvalue-range-int-min' => 0,
                                    'fvalue-range-int-max' => 0
                                ),
                                'fvalue-range-float' => array(
                                    'fvalue-range-float-min' => 0.00,
                                    'fvalue-range-float-max' => 0.00
                                ),
                                'fvalue-range-date' => array(
                                    'fvalue-range-date-min' => 0,
                                    'fvalue-range-date-max' => 0
                                ),
                            ) ;                            
        
                            $InsertSaved[$Field['sell-form-id']] = $Field['sell-form-def-value'];
                        }
                        else if ( $Field['sell-form-res-type'] == 3 ) {
    
                            $Insert[] = array(
                                'fid' => $Field['sell-form-id'],
                                'fvalue-string' => '',
                                'fvalue-int' => 0,
                                'fvalue-float' => $Field['sell-form-def-value'],
                                'fvalue-image' => 0,
                                'fvalue-datetime' => 0,
                                'fvalue-boolean' => false,
                                'fvalue-date' => 0,
                                'fvalue-range-int' => array(
                                    'fvalue-range-int-min' => 0,
                                    'fvalue-range-int-max' => 0
                                ),
                                'fvalue-range-float' => array(
                                    'fvalue-range-float-min' => 0.00,
                                    'fvalue-range-float-max' => 0.00
                                ),
                                'fvalue-range-date' => array(
                                    'fvalue-range-date-min' => 0,
                                    'fvalue-range-date-max' => 0
                                ),
                            ) ;                            
        
                            $InsertSaved[$Field['sell-form-id']] = $Field['sell-form-def-value'];
                    }
                    else if ( $Field['sell-form-res-type'] == 9 ) {

                        $Insert[] = array(
                            'fid' => $Field['sell-form-id'],
                            'fvalue-string' => '',
                            'fvalue-int' => 0,
                            'fvalue-float' => 0,
                            'fvalue-image' => 0,
                            'fvalue-datetime' => $Field['sell-form-def-value'],
                            'fvalue-boolean' => false,
                            'fvalue-date' => 0,
                            'fvalue-range-int' => array(
                                'fvalue-range-int-min' => 0,
                                'fvalue-range-int-max' => 0
                            ),
                            'fvalue-range-float' => array(
                                'fvalue-range-float-min' => 0.00,
                                'fvalue-range-float-max' => 0.00
                            ),
                            'fvalue-range-date' => array(
                                'fvalue-range-date-min' => 0,
                                'fvalue-range-date-max' => 0
                            ),
                        ) ;
                    }
                }
            }

            else {

                for ( $i = 0; $i < count( $Insert ); $i++ ) {

                    if ( $Field['sell-form-id'] == $Insert[$i]['fid'] ) {

                        if ( $Field['sell-form-res-type'] == 1 && $Insert[$i]['fvalue-string'] == '' ) {

                            $Insert[$i]['fvalue-string'] = $Field['sell-form-def-value'];
                        }
                        else if ( $Field['sell-form-res-type'] == 2 && $Insert[$i]['fvalue-int'] == '' ) {

                            $Insert[$i]['fvalue-int'] = $Field['sell-form-def-value'];
                        }
                        else if ( $Field['sell-form-res-type'] == 3 && $Insert[$i]['fvalue-float'] == '' ) {

                            $Insert[$i]['fvalue-float'] = $Field['sell-form-def-value'];
                        }
                        else if ( $Field['sell-form-res-type'] == 9 && $Insert[$i]['fvalue-datetime'] == '' ) {

                            $Insert[$i]['fvalue-datetime'] = $Field['sell-form-def-value'];
                            $Insert[$i]['fvalue-date'] = $Field['sell-form-def-value'];
                            $Insert[$i]['fvalue-range-int'] = array( 
                                'fvalue-range-int-min' => 0,
                                'fvalue-range-int-max' => 0 
                            );
                            $Insert[$i]['fvalue-range-float'] = array( 
                                'fvalue-range-float-min' => 0.00,
                                'fvalue-range-float-max' => 0.00 
                            );
                            $Insert[$i]['fvalue-range-date'] = array( 
                                'fvalue-range-date-min' => 0,
                                'fvalue-range-date-max' => 0 
                            );
                        }
                    }
                }
            }
        }
        try {

            $allegro = new AllegroWebAPI();
			// miechu mod
			$allegro_id=$this->config->get('config_allegro_id');
			$login=$this->config->get('config_allegro_login');
			$pass=$this->config->get('config_allegro_pass');
			$webapi=$this->config->get('config_allegro_webapi');
			$allegro->setInitial($allegro_id,$login,$pass,$webapi);
            $allegro->Login();
            $AllegroData = $allegro->object_to_array( $allegro->CheckNewAuctionExt( $Insert ) );
            file_put_contents( DIR_CACHE . 'AllegroSavedInputs.txt', json_encode( $InsertSaved ) );

            if ( isset( $AllegroData['item-price-desc'] ) ) {

                $this->data['Data'][$DataIterator]['KosztAukcji'] = $AllegroData['item-price'];
                $this->data['Data'][$DataIterator]['OpisKosztuAukcji'] = $AllegroData['item-price-desc'];

                if ( !isset( $_GET['checkauction'] ) ) {

                    $Wystawiona = $allegro->object_to_array( $allegro->NewAuctionExt( array( 'fields' => $Insert, 'private' => '', 'local-id' => '' ) ) );
                    $this->data['Data'][$DataIterator]['Identyfikator'] = $Wystawiona['item-id'];
                    $this->data['Data'][$DataIterator]['Link'] = "<a href='http://allegro.pl/ShowItem2.php?item=" . $Wystawiona['item-id'] . "'>http://allegro.pl/ShowItem2.php?item=" . $Wystawiona['item-id'] . "</a>";
                }

                //file_put_contents( DIR_CACHE . 'AllegroSavedInputs.txt', json_encode( $InsertSaved ) );
            }
            else {
                    //file_put_contents( DIR_CACHE . 'AllegroSavedInputs.txt', json_encode( $InsertSaved ) );
                    $this->data['Data'][$DataIterator]['AllegroData'] = $AllegroData;
                }
            }

            catch ( SoapFault $fault ) {

                $this->data['Data'][$DataIterator]['Fault'] = $fault->faultstring;
            }

            $DataIterator++;
            $P = '';
            $Insert = '';
        }

        $this->template = 'allegro/added.tpl';
        $this->children = array( 'common/header', 'common/footer' );
        $this->response->setOutput( $this->render( true ), $this->config->get( 'config_compression' ) );
    }
    
    public function Getcategoryoptions($CategoryId = false, $Echo = true) {


        
        if ( $CategoryId == false ) {
            
            $CategoryId = $_GET['allegro_category_id'] ;
        }
        
        $this->load->model( 'allegro/category' ) ;
        $CatOptions = $this->model_allegro_category->GetCategoryOptions($CategoryId) ;

        if ( empty($CatOptions) ) {



            $this->load->library( 'class.allegrowebapi' );

            $allegro = new AllegroWebAPI();
			// miechu mod
			$allegro_id=$this->config->get('config_allegro_id');
			$login=$this->config->get('config_allegro_login');
			$pass=$this->config->get('config_allegro_pass');
			$webapi=$this->config->get('config_allegro_webapi');
			$allegro->setInitial($allegro_id,$login,$pass,$webapi);


			//
            $allegro->Login();

            set_time_limit(240) ;


            
            $AllegroData = $allegro->object_to_array( $allegro->GetSellFormFieldsForCategory( $CategoryId ) );



            if ( !empty($AllegroData['sell-form-fields-list']) ) {
                
                foreach( $AllegroData['sell-form-fields-list'] as $kk => $AD ) {
                    
                    if( $kk > 88 ) {
                        
                        $this->model_allegro_category->AddCategoryOptions($AD, $CategoryId) ;
                    }
                }
            }
            
            $CatOptions = $this->model_allegro_category->Getcategoryoptions($CategoryId) ;
        }
        // OK

        
        $this->ConvertCatOptionsToForm($CatOptions) ;
    }
    
    public function ConvertCatOptionsToForm($CatOptions, $SellFormParentId = 0) {
        
        $ResType = array(
            1 => 'string', 
            2 => 'int',
            3 => 'float',
            7 => 'image',
            9 => 'datetime',
            13 => 'date'
        ) ;
            
        if ( isset($_GET['kkk']) && $_GET['kkk'] == 1 ) {
            $KkkStart = 'k[' ; $KkkEnd = ']' ;
        } else {
            $KkkStart = '' ; $KkkEnd = '' ;
        }
        
        foreach( $CatOptions as $AD ) {
            
            if ( $AD['sell-form-parent-id'] == $SellFormParentId ) {
                if ( $AD['sell-form-type'] == 1 || $AD['sell-form-type'] == 2 || $AD['sell-form-type'] == 3 ) {
                    $Return[] = '<div><span class="CategoryOptionTitle" >'.$AD['sell-form-title'].'</span> <input type="text" name="'.$KkkStart.$AD['sell-form-id'].'-'.$ResType[$AD['sell-form-type']].$KkkEnd.'" title="'.$AD['sell-form-id'].'" class="CategoryOption"></div>' ;
                } else if ( $AD['sell-form-type'] == 4 ) {
                    $Retur = '<div><span class="CategoryOptionTitle" >'.$AD['sell-form-title'].'</span> <select name="'.$KkkStart.$AD['sell-form-id'].'-'.$ResType[$AD['sell-form-res-type']].$KkkEnd.'" title="'.$AD['sell-form-id'].'" multiple="multiple" class="CategoryOption">' ;
                        $sellformdesc = explode('|',$AD['sell-form-desc']) ;
                        $sellformoptsvalues = explode('|',$AD['sell-form-opts-values']) ;
                        foreach( $sellformdesc as $k => $v ) {
                            $Retur .= '<option value="'.$sellformoptsvalues[$k].'">'.$v.'</option>' ;
                        }
                    $Retur .= '</select></div>' ;
                    $Return[] = $Retur ;
                } else if ( $AD['sell-form-type'] == 5 || $AD['sell-form-type'] == 6 ) {
                    $Retur = '<div><span class="CategoryOptionTitle" >'.$AD['sell-form-title'].'</span> <select name="'.$KkkStart.$AD['sell-form-id'].'-'.$ResType[$AD['sell-form-res-type']].$KkkEnd.'" title="'.$AD['sell-form-id'].'" class="CategoryOption">' ;
                        $sellformdesc = explode('|',$AD['sell-form-desc']) ;
                        $sellformoptsvalues = explode('|',$AD['sell-form-opts-values']) ;
                        foreach( $sellformdesc as $k => $v ) {
                            $Retur .= '<option value="'.$sellformoptsvalues[$k].'">'.$v.'</option>' ;
                        }
                    $Retur .= '</select></div>' ;
                    $Return[] = $Retur ;
                } else if ( $AD['sell-form-type'] == 8 ) {
                    $Return[] = '<div><span class="CategoryOptionTitle" >'.$AD['sell-form-title'].'</span> <textarea name="'.$KkkStart.$AD['sell-form-id'].'-'.$ResType[$AD['sell-form-type']].$KkkEnd.'" title="'.$AD['sell-form-id'].'" class="CategoryOption"></textarea></div>' ;
                }
            }
        }
        
        if ( isset($Return) ) {
            if ( $SellFormParentId != 0 ) {
                echo '<div class="ChildCatOptions">' ;
                    echo implode('',$Return) ;
                echo '</div>' ;
            } else {
                echo '<div class="ParentCatOptions">' ;
                    echo implode('',$Return) ;
                echo '</div>' ;
            }
            
        }
    }
    
    public function Checkandgetchildcatoptions($OptionId = false, $ParentValue = false, $CategoryId = false) {
        
        if ( $OptionId == false || $ParentValue == false || $CategoryId == false) {
            $OptionId = $_GET['allegro_option_id'] ;
            $ParentValue = $_GET['allegro_parent_value'] ;
            $CategoryId = $_GET['allegro_category_id'] ;
        }
        
        $this->load->model( 'allegro/category' ) ;
        $ChildOptions = $this->model_allegro_category->GetChildCategoryOptions($OptionId, $ParentValue, $CategoryId) ;
        
        if ( !empty($ChildOptions) ) {
            
            $this->ConvertCatOptionsToForm($ChildOptions['rows'], $ChildOptions['parent']) ;
        }
    }
    
    private function GetLastCats($CatParent) {
        
        $Reklamas = $this->model_allegro_category->GetCategoriesByCatParent($CatParent) ;

        foreach( $Reklamas as $Reklama ) {
            
            $ReklamaChilds = $this->model_allegro_category->GetCategoriesByCatParent($Reklama['cat_id']) ;
            
            if ( !empty($ReklamaChilds) ) {
                
                foreach( $ReklamaChilds as $ReklamaChild ) {
                    
                    $ReklamaChilds2 = $this->model_allegro_category->GetCategoriesByCatParent($ReklamaChild['cat_id']) ;
                    
                    if ( !empty($ReklamaChilds2) ) {
                        
                        foreach( $ReklamaChilds2 as $ReklamaChild2 ) {
                            
                            $ReklamaChilds3 = $this->model_allegro_category->GetCategoriesByCatParent($ReklamaChild2['cat_id']) ;
                            
                            if ( !empty($ReklamaChilds3) ) {
                                
                                foreach( $ReklamaChilds3 as $ReklamaChild3 ) {
                                    
                                    $ReklamaChilds4 = $this->model_allegro_category->GetCategoriesByCatParent($ReklamaChild3['cat_id']) ;
                                    
                                    if ( !empty($ReklamaChilds4) ) {
                                        
                                        foreach( $ReklamaChilds4 as $ReklamaChild4 ) {
                                            
                                            $ReklamaChilds5 = $this->model_allegro_category->GetCategoriesByCatParent($ReklamaChild4['cat_id']) ;
                                            
                                            if ( !empty($ReklamaChilds5) ) {
                                                
                                                foreach( $ReklamaChilds5 as $ReklamaChild5 ) {
                                                    
                                                    $ReklamaChilds6 = $this->model_allegro_category->GetCategoriesByCatParent($ReklamaChild5['cat_id']) ;
                                                    
                                                    if ( !empty($ReklamaChilds6) ) {
                                                        
                                                        foreach( $ReklamaChilds6 as $ReklamaChild6 ) {
                                                            
                                                            $ReklamaCats[] = $ReklamaChild6['cat_id'] ;
                                                        }
                                                    }
                                                    else {
                
                                                        foreach( $ReklamaChilds5 as $ReklamaChild5 ) {
                                                                                            
                                                            $ReklamaCats[] = $ReklamaChild5['cat_id'] ;
                                                        }
                                                    }
                                                }
                                            }
                                            else {
                
                                                foreach( $ReklamaChilds4 as $ReklamaChild4 ) {
                                                                                    
                                                    $ReklamaCats[] = $ReklamaChild4['cat_id'] ;
                                                }
                                            }
                                        }
                                    }
                                    else {
                
                                        foreach( $ReklamaChilds3 as $ReklamaChild3 ) {
                                                                            
                                            $ReklamaCats[] = $ReklamaChild3['cat_id'] ;
                                        }
                                    }
                                }
                            }
                            else {
                
                                foreach( $ReklamaChilds2 as $ReklamaChild2 ) {
                                                                    
                                    $ReklamaCats[] = $ReklamaChild2['cat_id'] ;
                                }
                            }
                        }
                    }
                    else {
                
                        foreach( $ReklamaChilds as $ReklamaChild ) {
                                                            
                            $ReklamaCats[] = $ReklamaChild['cat_id'] ;
                        }
                    }
                }
            }
            else {
                
                $ReklamaCats[] = $Reklama['cat_id'] ;
            }
        }
        
        return $ReklamaCats ;
    }
    
    public function Getoptionsbycats() {
        
        $this->load->model( 'allegro/category' ) ;
        //session_write_close() ;
        // Reklama 64485
        $Reklamas = $this->GetLastCats(121722) ;
        $Reklamas = array_unique($Reklamas) ;
        $Excluded = array(16458,16459,16461,16460,109679,109682,109680,109681,109683,109684) ;
		
        foreach( $Reklamas as $Reklama ) {
            
			if ( !in_array($Reklama,$Excluded) ) {
				$this->Getcategoryoptions($Reklama,false) ;
			}
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function Deletecategories() {
        
        $Categories = json_decode(file_get_contents(DIR_CACHE.'AllegroCategories.txt'), true) ;

        foreach( $Categories as $k => $Category ) {

            if ( $Category['cat-name'] != 'Odzież, Obuwie, Dodatki' ) {
                echo $Category['cat-name'].'<BR>' ;
                unset($Categories[$k]) ;
            }
        }
        
        file_put_contents(DIR_CACHE.'AllegroCategories.txt',json_encode($Categories)) ;
    }

    private function Template() {

        $this->load->model( 'allegro/product' );
        echo $this->model_allegro_category->showTemplate( $_GET['name'] );
    }

    private function getAllegroData($AllegroMetod,$Options = '') {

        if ( !file_exists(DIR_CACHE."allegro_$AllegroMetod.txt") ) {

            try {

                $allegro = new AllegroWebAPI() ;
				// miechu mod
				$allegro_id=$this->config->get('config_allegro_id');
			$login=$this->config->get('config_allegro_login');
			$pass=$this->config->get('config_allegro_pass');
			$webapi=$this->config->get('config_allegro_webapi');
			$allegro->setInitial($allegro_id,$login,$pass,$webapi);
			//
            	$allegro->Login();          

                if ( $Options != '' ) {                   

                    eval('$AllegroData = $allegro->'.$AllegroMetod.'($Options);');
                    $AllegroData = $allegro->object_to_array($AllegroData) ;
                }
                else {                   

                    eval('$AllegroData = $allegro->'.$AllegroMetod.'();');
                    $AllegroData = $allegro->object_to_array($AllegroData) ;
                }              

                file_put_contents(DIR_CACHE."allegro_$AllegroMetod.txt",json_encode($AllegroData)) ;
            }
            catch(SoapFault $fault) {

               	print($fault->faultstring);
            }
        }
        else {
            
            $AllegroData = file_get_contents(DIR_CACHE."allegro_$AllegroMetod.txt") ;
            $AllegroData = json_decode($AllegroData,true) ;
        }     

        return $AllegroData ;
    }   

    private function search($array, $key, $value) {
    
        $Return = false ;

        foreach( $array as $a ) {
            
            if ( $a[$key] == $value ) {
                
                $Return = true ;
                break ;
            }
        }
        
        return $Return;
    }

    private function validateForm() {

        if ( !$this->user->hasPermission( 'modify', 'catalog/product' ) ) {

            $this->error['warning'] = $this->language->get( 'error_permission' );
        }

        foreach ( $this->request->post['product_description'] as $language_id => $value ) {

            if ( ( strlen( utf8_decode( $value['name'] ) ) < 1 ) || ( strlen( utf8_decode( $value['name'] ) ) > 255 ) ) {

                $this->error['name'][$language_id] = $this->language->get( 'error_name' );
            }
        }

        if ( ( strlen( utf8_decode( $this->request->post['model'] ) ) < 1 ) || ( strlen( utf8_decode( $this->request->post['model'] ) ) > 64 ) ) {

            $this->error['model'] = $this->language->get( 'error_model' );
        }

        if ( !$this->error ) {

            return true;
        }
        else {

            if ( !isset( $this->error['warning'] ) ) {

                $this->error['warning'] = $this->language->get( 'error_required_data' );
            }

            return false;
        }
    }

    private function objecttoarray( $object ) {

        if ( is_array( $object ) || is_object( $object ) ) {

            $array = array();

            foreach ( $object as $key => $value ) {

                $array[$key] = $this->objecttoarray( $value );
            }

            return $array;
        }

        return $object;
    }

}

?>