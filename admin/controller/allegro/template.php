<?php

/*
 * 18.09.2013 Mieszko tytuł tworzy się z kategorii  i modelu a nie z nazwy produktu, problem ograniczenia dlugosci tytułu allegro
 * - w szablonie pojawiaja sie 3 zmienne: MAKE_NAME, MODEL_NAME, TYPE
 * - tytuł tworzy się z nazwy kategorii, stanu (nowy/regen) oraz marki i modelu
 */

class ControllerAllegroTemplate extends Controller {

	private $error = array() ;

	public function index() {

       // miechu fix
        if(!defined('HTTP_IMAGE'))
        {

            define('HTTP_IMAGE',HTTP_CATALOG.'image/');

        }

		$this->document->setTitle($this->language->get('allegro_heading_title'));
        $this->load->model( 'allegro/product' ) ;
		$this->load->model( 'allegro/product' ) ;
		$Product = $this->model_allegro_product->getProduct( $_GET['product_id'] ) ;
		$ProductImages = $this->model_allegro_product->getProductImages( $_GET['product_id'] ) ;
		$Categories = $this->model_allegro_product->getCategories( $_GET['product_id'] ) ;
		$Options = $this->model_allegro_product->getProductOptions( $_GET['product_id'] ) ;

        // 19.09.2013

        require_once DIR_SYSTEM . 'library/HTMLPurifier.auto.php';

        $config = HTMLPurifier_Config::createDefault();
        $config->set('Core.Encoding', 'UTF-8'); // replace with your encoding
        //  $config->set('HTML.Doctype', 'HTML'); // replace with your doctype
        $config->set('CSS.AllowedProperties', array());
        $purifier = new HTMLPurifier($config);

        $Product['description'] = $purifier->purify($Product['description']);

        // 19.09.2013

        // 18.09.2013
        $category = array_shift($Categories);

        $prefix = array(
            59 => array(
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
            ),
            73 => array(
                'new' => "Nowa",
                'regenerated' => "Regenerowana",
                'for_regeneration' => "Do regeneracji",
                0 => "Do regeneracji",
            ),
        );

        $Title = '';


        if($category == 59)
        {
             $Title .= $prefix[59][$Product['type']]." Pompa Paliwowa ".$Product['make_name']." ".$Product['model_name'] ." ".$Product['model'];
        }
        elseif($category == 60)
        {
            $Title .= $prefix[60][$Product['type']]." Wtryskiwacz ".$Product['make_name']." ".$Product['model_name']." ".$Product['model'];
        }
        else
        {
            // 50 znaków ograniczenie allegro
            $Title = substr($Product['name'],0,50);
        }
        // 18.09.2013


	
		$ProductDescription = html_entity_decode( $Product['description'] ) ;
		$Price='';
		
		if(isset($_GET['price']))
		{
			$Price = $_GET['price'];
			$Price=$this->currency->format($Price, $this->config->get('config_currency'));
		}

        $Price = str_ireplace('€','PLN',$Price);
		
		$Delivery='';
		$ExtImages = '' ;
       
       $Manufacturer=$this->model_allegro_product->getManuName( $Product['manufacturer_id'] );
	   
       //$Manufacturer='';
		// miechu
		$IMG='<a  href="' . HTTP_IMAGE . $Product['image'] . '"><img class ="small" src="' . HTTP_IMAGE . $Product['image'] . '"></a>' ;
		foreach ( $ProductImages as $ProductImage ) {

			$ExtImages .= '<a  href="' . HTTP_IMAGE . $ProductImage['image'] . '"><img class ="small" src="' . HTTP_IMAGE . $ProductImage['image'] . '"></a>' ;
		}
		
		$ExtOptions='';
		
		foreach ( $Options as $Option ) {



			$ExtOptions .= '<p><strong>'.$Option['name'].': </strong></p><p>' ;
			if(isset($Option['product_option_value']) AND is_array($Option['product_option_value']))
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
       // var_dump($ProductDescription);
    
		$ShowTemplate = $this->model_allegro_product->showTemplate( $_GET['name'] ) ;
        // 18.09.2013

        $Type = NULL;

        if($Product['type']=='new')
        {
            $Type = "Kup Nowy";
        }
        elseif($Product['type']=='regenerated')
        {
            $Type = "Regenerowany";
        }
        elseif($Product['type']=='for_regeneration')
        {
            $Type = "Do renegeneracji";
        }

		$Template = str_replace( array( '{PRODUCT_NAME}', '{PRODUCT_DESCRIPTION}', '{IMAGES}', '{PRODUCT_MODEL}', '{PRODUCT_PRICE}', '{PRODUCT_CATEGORIES}', '{PRODUCT_OPTIONS}', '{PRODUCT_DELIVERY}', '{PRODUCT_MANUFACTURER}', '{IMG}','{MAKE_NAME}','{MODEL_NAME}','{TYPE}'), array( $Title, $ProductDescription, $ExtImages, $Product['model'], $Price, $ExtCategories, $ExtOptions, $Delivery, $Manufacturer, $IMG, $Product['make_name'],$Product['model_name'],$Type  ), html_entity_decode($ShowTemplate['value']) ) ;
        // 18.09.2013
	    echo $Template ;
	}
}

?>