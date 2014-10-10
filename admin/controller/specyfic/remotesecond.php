<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 31.07.13
 * Time: 11:58
 * To change this template use File | Settings | File Templates.
 */

class ControllerSpecyficRemoteSecond extends Controller{

    private $url = 'http://www.dieseljones.co.uk';

    private $categories = array(
      'http://www.dieseljones.co.uk/diesel-fuel-pumps/' ,
      'http://www.dieseljones.co.uk/common-rail-diesel-fuel-pumps/',
      'http://www.dieseljones.co.uk/common-rail-injectors/',
	  'http://www.dieseljones.co.uk/unit-injector/',
    );

    private $categories_flipped = array(
         'http://www.dieseljones.co.uk/diesel-fuel-pumps/' => 59,
         'http://www.dieseljones.co.uk/common-rail-diesel-fuel-pumps/' => 59,
         'http://www.dieseljones.co.uk/common-rail-injectors/' => 60,
		 'http://www.dieseljones.co.uk/unit-injector/' => 60,
    );

    private $current_category;

    private $current_category_id;

    private $current_prefix;

    private $current_make;
    private $current_make_id;
    private $current_model;

    private $new_to_regenerated = array();


    private $snoopy;
    private $langs;

    private $translateArray = array(
        // polski
        '2' => array(
            'Diesel Fuel Pump'  =>  'Regenerowana pompa paliwowa Diesel',

            'Car Type'   => 'Model samochodu: ',

            'Capacity in l/ccm'   => 'Pojemność w centymetrach sześciennych/ccm:',

            'Engine Power output in KW'  =>  'Moc silnika w KW:',

            'Engine Type'  => 'Model silnika:',

            'Export Market' => 'Rynek zbytu:',

            'Production Date'  => 'Data produkcji: ',

            'Special Cases' => 'Specjalne zastosowanie:',

            'Distrubutor pump VE and VR Number'   => 'Numer dystrybutora:',

            'Exchange Number' => 'Numer zamiennika: ',

            'Diesel Unit Injector'  => 'Regenerowana jednostka wtrysku/wtryskiwacz Diesel',

            'Common Rail Pump' => 'Regenerowana pompa Common Rail',

            'Common Rail Diesel Injector' => ' Regenerowany wtryskiwacz Common Rail Diesel',

            'Number of Injectors' => 'Liczba wtryskiwaczy: ',

            'Common Rail Diesel Injector Number' => 'Numer wtryskiwacza do Diesla:',

            'Common Rail Pump Number' => 'Numer pompy paliwowej common rail:',

            'Common Rail Injector Number' => 'Numer wtryskiwacza:',
        ),
        // niemiecki
        '3' => array(

            'Diesel Fuel Pump'  =>  'Regeneriert Diesel-Kraftstoffpumpe(n)/Dieselpumpe(n)',

            'Car Type'   => 'Fahrzeugtyp(en): ',

            'Capacity in l/ccm'   => 'Hubraum:',

            'Engine Power output in KW'  =>  'Leistung in kW:',

            'Engine Type'  => 'Motorentyp(en):',

            'Export Market' => 'Exportmarkt:',

            'Production Date'  => 'Produktionsjahr(e): ',

            'Special Cases' => 'Spezielle Anwendungen:',

            'Distrubutor pump VE and VR Number'   => 'Distributionsnummer(n)/Ersatzteilnummer(n):',

            'Exchange Number' => 'Ersatzteilnummer(n): ',

            'Diesel Unit Injector'  => 'Regeneriert Diesel Einspritzdüse(n)',

            'Common Rail Pump' => 'Regeneriert Common Rail Kraftsoffpumpe/Dieselpumpe',

            'Common Rail Diesel Injector' => 'Regeneriert Common Rail Einspritzdüse(n)',

            'Number of Injectors' => 'Anzahl der Einspritzdüsen: ',

            'Common Rail Diesel Injector Number' => 'Einspritzdüsen-Ersatzteilnummer:',

            'Common Rail Pump Number' => 'Teilenummer der Common Rail Kraftstoffpumpe:',

            'Common Rail Injector Number' => 'Teilenummer/Ersatzteilnummer der Common Rail Einspritzdüse:',

        )
    );

    private $translateArrayNew = array(
        // polski
        '2' => array(
            'Diesel Fuel Pump'  =>  'Nowa pompa paliwowa Diesel',

            'Car Type'   => 'Model samochodu: ',

            'Capacity in l/ccm'   => 'Pojemność w centymetrach sześciennych/ccm:',

            'Engine Power output in KW'  =>  'Moc silnika w KW:',

            'Engine Type'  => 'Model silnika:',

            'Export Market' => 'Rynek zbytu:',

            'Production Date'  => 'Data produkcji: ',

            'Special Cases' => 'Specjalne zastosowanie:',

            'Distrubutor pump VE and VR Number'   => 'Numer dystrybutora:',

            'Exchange Number' => 'Numer zamiennika: ',

            'Diesel Unit Injector'  => 'Nowa jednostka wtrysku/wtryskiwacz Diesel',

            'Common Rail Pump' => 'Nowa pompa Common Rail',

            'Common Rail Diesel Injector' => 'Nowy wtryskiwacz Common Rail Diesel',

            'Number of Injectors' => 'Liczba wtryskiwaczy: ',

            'Common Rail Diesel Injector Number' => 'Numer wtryskiwacza do Diesla:',

            'Common Rail Pump Number' => 'Numer pompy paliwowej common rail:',

            'Common Rail Injector Number' => 'Numer wtryskiwacza:',
        ),
        // niemiecki
        '3' => array(

            'Diesel Fuel Pump'  =>  'Neu Diesel-Kraftstoffpumpe(n)/Dieselpumpe(n)',

            'Car Type'   => 'Fahrzeugtyp(en): ',

            'Capacity in l/ccm'   => 'Hubraum:',

            'Engine Power output in KW'  =>  'Leistung in kW:',

            'Engine Type'  => 'Motorentyp(en):',

            'Export Market' => 'Exportmarkt:',

            'Production Date'  => 'Produktionsjahr(e): ',

            'Special Cases' => 'Spezielle Anwendungen:',

            'Distrubutor pump VE and VR Number'   => 'Distributionsnummer(n)/Ersatzteilnummer(n):',

            'Exchange Number' => 'Ersatzteilnummer(n): ',

            'Diesel Unit Injector'  => 'Neu Diesel Einspritzdüse(n)',

            'Common Rail Pump' => 'Neu Common Rail Kraftsoffpumpe/Dieselpumpe',

            'Common Rail Diesel Injector' => 'Neu Common Rail Einspritzdüse(n)',

            'Number of Injectors' => 'Anzahl der Einspritzdüsen: ',

            'Common Rail Diesel Injector Number' => 'Einspritzdüsen-Ersatzteilnummer:',

            'Common Rail Pump Number' => 'Teilenummer der Common Rail Kraftstoffpumpe:',

            'Common Rail Injector Number' => 'Teilenummer/Ersatzteilnummer der Common Rail Einspritzdüse:',

        )
    );



    private $injectors_category_id = 60;
    private $fuel_pumps_category = 59;

    private $downloaded_images = array();

    private $product_codes = array();

    private $manufacturer_pics = array();
	
	private $meta_description_pl = "Najlepszy sklep z nowymi oraz regenerowanymi injektorami / wtryskiwaczami, pompami paliwa i turbosprężarkami {title} auto-gatzka.pl ";

	private $meta_description_de = "Bester Online Shop fuer Injektoren / Einspritzduesen, Dieselpumpen und Turbolader {title} diesel-land.de ";
	
	private $polish_id = 2;
	
	private $german_id = 3;

    public function index()
    {
        $this->test();
    }

    public function test()
    {

        set_time_limit(300000);

        error_reporting(0);

        error_reporting(E_ALL);

        ini_set('memory_limit', '1024M');

        $this->load->model('catalog/category');

        ini_set('display_errors', '1');

        $this->load->model('tool/cars');

        $ids = array_flip($this->categories);

        foreach($this->categories as $category){

            $makes = $this->getMakeLinks($category);



            $this->current_category_id = $this->categories_flipped[$category];

            $this->getLanguageCodes();

            $this->load->model('catalog/product');

            $this->load->model('specyfic/codes');

            array_shift($makes);

            foreach($makes as $make)
            {
             /*   if(strpos($make['make_name'],'wagen')===false)
                {
                    continue;
                } */

                $model_links = $this->getModelLinks($make);

                $this->current_make = $make['make_name'];

                $this->current_make_id = $make['make_id'];

                // modele: linki + nazwy jescze nie zmapowane
                $products_in_model = array();

                foreach($model_links as $model_link)
                {


                    $this->getProductInModel($model_link,$make['make_id']);
					
					


                }




            }
        }





    }

    public function getMakeLinks($category)
    {
        $page = file_get_contents($category);

        $dom =  new DOMDocument();
        $dom->loadHTML($page);

        // nazwa modelu
        $finder = new DomXPath($dom);
        $classname="see-all";
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        $makes = array();

        foreach($nodes as $node)
        {
            $link = $node->getElementsByTagName('a')->item(0)->getAttribute('href');



            $tmp = explode('/',$link);

            $category = $tmp[1];

            $full = $tmp[2];

            $tmp2 = explode('-',$tmp[1]);

            $category_first_word = array_shift($tmp2);

            $this->current_prefix = $category_first_word;

            $tmp3 = explode($category_first_word,$full);

            $make_name = str_ireplace('-',' ',array_shift($tmp3));

            $make_name = trim($make_name);


            $make_id = $this->model_tool_cars->getMakeIdByName($make_name);

            if(!$make_id)
            {
                $data = array(
                   'make_name' => $make_name,
                );
                $make_id = $this->model_tool_cars->addMake($data);
            }

            $makes[] = array(
                'make_link'  => $this->url.$link,
                'make_name' => $make_name,
                'make_id' => $make_id,

            );
        }

        // makes ok , no zeros
        $this->checkForZeroIds($makes);

        return $makes;
    }

    private function checkForZeroIds($data)
    {
          foreach($data as $row)
          {
               if(!$row['make_id'] OR $row['make_id']=='')
               {
                  // throw new Exception("Błąd marki: ".var_dump($row));
               }
          }
    }

    public function getModelLinks($make)
    {

        $page = file_get_contents($make['make_link']);

        $dom =  new DOMDocument();
        $dom->loadHTML($page);

        // nazwa modelu
        $finder = new DomXPath($dom);
        $classname="see-all";
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");



        $models = array();

        foreach($nodes as $node)
        {
            $link = $node->getElementsByTagName('a')->item(0)->getAttribute('href');

            $image = $node->getElementsByTagName('img')->item(0)->getAttribute('src');


            $image = $this->dowloadImage($this->url.$image);


            $make_name = str_ireplace(' ','-',$make['make_name']);

            $tmp = explode('/',$link);

            // trailing slash
            array_pop($tmp);

            $model_part = array_pop($tmp);

            $tmp2 = explode($this->current_prefix,$model_part);

            $tmp3 = explode($make_name,array_shift($tmp2));

            $model_name = str_ireplace('-','',array_pop($tmp3));

            $model_name = trim($model_name);

            $models[] = array(
                'name' => $model_name,
                'model_link' => $this->url.$link,
                'image' => $image,
            );

        }


        return $models;

    }

    public function getProductInModel($model,$make_id)
    {
        $page = file_get_contents($model['model_link']);
		
		if(!$page)
		{
		    return true;
		}

        if(!$this->current_make_id)
        {
           // throw new Exception("Nie można okreslić aktualnej marki ".var_dump($model));
        }

        $dom =  new DOMDocument();
        $dom->loadHTML($page);

        $this->current_model = $model;

        $finder = new DomXPath($dom);
        $classname="pagination";
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        $links = array();

        if($nodes->item(0))
        {
            $as = $nodes->item(0)->getElementsByTagName('a');

            $number_of_pages = (int)$as->length + 1;



            for($i=1;$i<=$number_of_pages;$i++)
            {



                $part = str_ireplace($this->url,'',$model['model_link']);
				
		

                $link = $model['model_link']."#ty;pagination_contents;".$part.'page-'.$i.'/';


                $page = file_get_contents($link);
				
						if(!$page)
		       {
		             continue;
		       }

               /* $dom =  new DOMDocument();
                $dom->loadHTML($page);

                $finder = new DomXPath($dom);
                $classname="product-title";
                $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");



                foreach($nodes as $node)
                {

                    $text =  $node->nodeValue;

                    $tmp = explode(' ',$text);



                    $links[] = array(
                        'link' =>  $this->url.$node->getAttribute('href'),
                        'code' => array_pop($tmp),
                    );
                } */

                $dom =  new DOMDocument();
                $dom->loadHTML($page);

                $finder = new DomXPath($dom);
                $classname="boxtext";
                $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");



                foreach($nodes as $node)
                {

                    /*    $text =  $node->nodeValue;

                        $tmp = explode(' ',$text); */

                    // alt solution, get all spans
                    // get link first
                    $newdoc = new DOMDocument();
                    $cloned = $node->cloneNode(TRUE);
                    $newdoc->appendChild($newdoc->importNode($cloned,TRUE));

                    $finder = new DomXPath($newdoc);
                    $classname="product-title";
                    $linker = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

                    // get spans

                    $spans = $node->getElementsByTagName('span');

                    foreach($spans as $span)
                    {
                        $id = $span->getAttribute('id');

                        if(stripos($id,'product_code')!==false)
                        {
                            $code = $span->nodeValue;


                        }

                    }

                    if(!isset($code))
                    {
                        $code = uniqid();
                    }


                    /// @todo tu jest prbolem z kodem, czasem sie pobiera data, trzeba znależć dokładniejszy sposób, trzeba tez znależć alt rozwiazanie kiedy nie da sie zapisac kodu ( uniqid ? )

                    $links[] = array(
                        'link' =>  $this->url.$linker->item(0)->getAttribute('href'),
                        'code' => $code,
                    );
                }

            }

        }
        else
        {

            $finder = new DomXPath($dom);
            $classname="boxtext";
            $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");



            foreach($nodes as $node)
            {

            /*    $text =  $node->nodeValue;

                $tmp = explode(' ',$text); */

                // alt solution, get all spans
                // get link first
                $newdoc = new DOMDocument();
                $cloned = $node->cloneNode(TRUE);
                $newdoc->appendChild($newdoc->importNode($cloned,TRUE));

                $finder = new DomXPath($newdoc);
                $classname="product-title";
                $linker = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

                // get spans

                $spans = $node->getElementsByTagName('span');

                foreach($spans as $span)
                {
                     $id = $span->getAttribute('id');

                     if(stripos($id,'product_code')!==false)
                     {
                         $code = $span->nodeValue;


                     }

                }

                if(!isset($code))
                {
                    $code = uniqid();
                }


                /// @todo tu jest prbolem z kodem, czasem sie pobiera data, trzeba znależć dokładniejszy sposób, trzeba tez znależć alt rozwiazanie kiedy nie da sie zapisac kodu ( uniqid ? )

                $links[] = array(
                    'link' =>  $this->url.$linker->item(0)->getAttribute('href'),
                    'code' => $code,
                );
            }
        }




        $links = $this->removeLinksDuplicate($links);


        $this->checkIfLinksDuplicate($links);



        foreach($links as $link)
        {
            $this->getProduct($link);
        }


    }

    private function checkIfLinksDuplicate($data)
    {
        $newArray = array();

        foreach($data as $key => $row)
        {
            if(in_array($row['link'],$newArray))
            {
               //  throw new Exception("Duplikat linku do produkut: ".var_dump($row));
               // unset($data[$key]);
            }
            else
            {
                $newArray[] = $row['link'];
            }
        }

        return $data;
    }

    private function removeLinksDuplicate($data)
    {
        $newArray = array();

        foreach($data as $key => $row)
        {
             if(in_array($row['link'],$newArray))
             {
                // throw new Exception("Duplikat linku do produkut: ".var_dump($row));
                unset($data[$key]);
             }
              else
              {
                  $newArray[] = $row['link'];
              }
        }

        return $data;
    }




    public function getProduct($link)
    {

        $page = file_get_contents($link['link']);
		
		if(!$page)
		{
		   return true;
		}



        $dom =  new DOMDocument();
        $dom->loadHTML($page);

        // tytuł
        $finder = new DomXPath($dom);
        $classname="pro-title";
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        $item = $nodes->item(0);

        if(!is_object($item))
        {
            return true;
        }
        // tytuł
        $title = $nodes->item(0)->nodeValue;

        $tmp = explode('CODE',$title);

        $title = array_shift($tmp);

        $title_pl = $this->translateTitle($title,$this->polish_id,'regenerated',$link);

        $good = 1;

        // jesli cos nie tak z tlumaczeniem to wrzucamy produkt ale jako nie aktywny
        if(!$title_pl)
        {
            $title_pl = $title;
            $good = 0;
        }

        $title_de = $this->translateTitle($title,$this->german_id,'regenerated',$link);

        if(!$title_de)
        {
            $title_de = $title;
            $good = 0;
        }

        // tytul wersja nowa


        $title_pl_new = $this->translateTitle($title,$this->polish_id,'new',$link);

        // jesli cos nie tak z tlumaczeniem to wrzucamy produkt ale jako nie aktywny
        if(!$title_pl_new)
        {
            $title_pl_new = $title;

        }

        $title_de_new = $this->translateTitle($title,$this->german_id,'new',$link);

        if(!$title_de_new)
        {
            $title_de_new = $title;

        }



        // obrazek
        $classname="mainbox-body";
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        $item = $nodes->item(0);

        if(!is_object($item))
        {
            return true;
        }

        $imgs = $nodes->item(0)->getElementsByTagName('img');

        $image_link = $this->url.$imgs->item(0)->getAttribute('src');
		
		$size = $this->getFileSize($image_link);
		

        // zassanie obrazka
        if(in_array($size,array_keys($this->downloaded_images)))
        {
            $product_image = $this->downloaded_images[$size];
        }
        else
        {
            $product_image = $this->dowloadImage($image_link);
            $this->downloaded_images[$size] = $product_image;
        }

        // opis
        $classname="product-details";
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        $item = $nodes->item(0);

        if(!is_object($item))
        {
            return true;
        }

        $desc_node = $nodes->item(0)->getElementsByTagName('ul')->item(0)->getElementsByTagName('li')->item(0);

        // opis razem z formatowaniem //
        $newdoc = new DOMDocument();
        $cloned = $desc_node->cloneNode(TRUE);
        $newdoc->appendChild($newdoc->importNode($cloned,TRUE));
        $desc_value = $newdoc->saveHTML();


        //desc
        $desc_pl = $this->translateDesc($desc_value,$this->polish_id);

        $desc_de = $this->translateDesc($desc_value,$this->german_id);

        $desc = array();

        $keywords = $this->generateKeyword($title);

        foreach($this->langs as $key => $lang)
        {

            if($lang['language_id']==$this->polish_id)
            {
                $desc[$lang['language_id']]=array(
                    'name' => $title_pl,
                    'description' => $desc_pl,
                    'meta_description' => $this->generateMeta($this->polish_id,$title_pl),
                    'meta_keyword' => $this->generateKeyword($title_pl),
                    'tag' => '',
                );
            }
            if($lang['language_id']==$this->german_id)
            {
                $desc[$lang['language_id']]=array(
                    'name' => $title_de,
                    'description' => $desc_de,
                    'meta_description' => $this->generateMeta($this->german_id,$title_de),
                    'meta_keyword' => $this->generateKeyword($title_de),
                    'tag' => '',
                );
            }

        }

        foreach($this->langs as $key => $lang)
        {

            if($lang['language_id']==$this->polish_id)
            {
                $desc_new[$lang['language_id']]=array(
                    'name' => $title_pl_new,
                    'description' => $desc_pl,
                    'meta_description' => $this->generateMeta($this->polish_id,$title_pl_new),
                    'meta_keyword' => $this->generateKeyword($title_pl_new),
                    'tag' => '',
                );
            }
            if($lang['language_id']==$this->german_id)
            {
                $desc_new[$lang['language_id']]=array(
                    'name' => $title_de_new,
                    'description' => $desc_de,
                    'meta_description' => $this->generateMeta($this->german_id,$title_de_new),
                    'meta_keyword' => $this->generateKeyword($title_de_new),
                    'tag' => '',
                );
            }

        }
		




        // car
        $classname="rightdesctext";
        $texts = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
		
		
		
		if(!($texts->item(0)))
		{
		   
			
			$classname="rightdesctextINJ";
            $texts = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
		
			
		}
		
	    if(!$this->current_make_id)
        {
           // throw new Exception("Karta produktu, brak info o marce : ".var_dump($this->current_make_id)." model: ".var_dump($this->current_model).'  link: '. $link);
        }

        if(!$this->current_model)
        {
          //  throw new Exception("Karta produktu, brak info o modelu :  ".var_dump($this->current_model).'  link: '. $link);
        }

        $make_and_model = $this->current_make.' '.$this->current_model['name'];

		
        $car = $texts->item(0)->nodeValue;

        $type = str_ireplace($make_and_model,'',$car);

        $type = trim($type);
		
		if(isset($texts->item(2)->nodeValue))
		{
		     $kw = $texts->item(2)->nodeValue;
		}
		else
		{
		     $kw = NULL;
		}

		

        // years

        $years = $texts->item(5)->nodeValue;

        if(strpos($years,'onwards')===false)
        {

            if(strpos($years,'-')!==false)
            {
                $tmp = explode('-',$years);
            }

            if(strpos($years,'>')!==false)
            {
                $tmp = explode('>',$years);
            }

            $year_start = array_shift($tmp);

            $tmp2 = explode('.',$year_start);

            $year_start = array_pop($tmp2).'-'.array_shift($tmp2).'-01';

            $year_stop = array_pop($tmp);

            $tmp2 = explode('.',$year_stop);

            $year_stop = array_pop($tmp2).'-'.array_shift($tmp2).'-01';
        }
        else
        {
            // @todo
            $tmp = explode(' ',$years);

            $year_start = array_shift($tmp);

            $tmp2 = explode('.',$year_start);

            $year_start = array_pop($tmp2).'-'.array_shift($tmp2).'-01';

            $year_stop =  date("Y-m-d");
        }

        // jest rok, model i typ , okreslam id modelu i typu


        $model_id = $this->model_tool_cars->getModelIdByName($this->current_model['name'],$year_start,$year_stop,false,$this->current_model['image']);

        if(!$model_id)
        {

            $data = array(
                'make_id' => $this->current_make_id,
                'model_name' => $this->current_model['name'],
                'year_start' => $year_start,
                'year_stop' => $year_stop,
                'second_image' => $this->current_model['image'],
            );


            $model_id = $this->model_tool_cars->addModel($data);

        }
		
		if($kw)
		{
		    $type = str_ireplace($kw,'',$type);
		}

		$type = trim($type);

        if(!$type OR $type==''){
               $type = trim($texts->item(1)->nodeValue);
        }

        $type_id = $this->model_tool_cars->getTypeIdByNameAndModelId($model_id,$type,$kw);

        if(!$type_id)
        {
		    if($type AND $type!=''){
            $data= array(
                'model_id' => $model_id,
                'type_name' => $type,
                'kw' => $kw,
            );

            $type_id = $this->model_tool_cars->addType($data);

			}else{
			  $type_id = NULL;
			}
        }

        // tu moze byc problem
        if(!$type_id)
        {
          //  throw new Exception("Nie udało się ustalić typu, make: ".$this->current_make_id.'  model: '.$model_id.' link: '.var_dump($link));

        }



       /* $car_and_years = str_ireplace($kw,'',$car).$years;


        $tmp = str_ireplace($car_and_years,'',$title);


        if($tmp == $title)
        {


            $tmp = str_ireplace(str_ireplace($kw,'',$car),'',$title);

            $tmp = trim($tmp);
        }

        $tmp = trim($tmp);



        $tmp2 = explode(' ',$tmp);



        $manufacturer_name = array_shift($tmp2); */

        $manufacturers = array('Bosch','Denso','Delphi','Siemens');

        $manufacturer_name = NULL;

        foreach($manufacturers as $manufacturer)
        {
            if(stripos($title,$manufacturer)!==false)
            {
                $manufacturer_name = $manufacturer;
            }
        }



        if($manufacturer_name)
        {
            $data = array(
                'name' => $manufacturer_name,
            );

            $allowed_manufacturers = array();
            $manufacturer_id = $this->getManufacturersId($data);
        }
        else
        {
            $manufacturer_id = NULL;
        }






        // kategorie
        $category_id = $this->current_category_id;

        // part code

        $model = $code = $link['code'];

        if(array_key_exists($code,$this->product_codes))
        {

            // główny problem lezy tutaj, tu sa problemy z make_id oraz product_id i type_id
            // w tablicy jest id wersji nowej trzeba tez zapisac wersje regenrowana

            if(!isset($this->product_codes[$code]) OR !$this->product_codes[$code])
            {
              //   throw new Exception("Brak info o id produktu, kod: ".$code.' link: '.var_dump($link)." tablica kodów: ".var_dump($this->product_codes));
            }

            if(!$this->current_make_id)
            {
             //    throw new Exception("Problem z make_id przy zpaisywaniu do samochodów, link: ".var_dump($link).' , kod: '.var_dump($code));
            }

            $car = array(
                'make_id' => (int)$this->current_make_id,
                'model_id' => (int)$model_id,
                'type_id'  => (int)$type_id,
                'product_id' => (int)$this->product_codes[$code],
                'alt_desc' => array('desc' => $desc),
            );

            $this->model_tool_cars->productToCarInsert($car);

            $product_regenerated_id = $this->new_to_regenerated[$this->product_codes[$code]];

            if(!$product_regenerated_id)
            {
             //   throw new Exception("Brak info o id produktu powiazanego, kod: ".$code.' link: '.var_dump($link)." tablica kodów: ".var_dump($this->product_codes),' tablica powiazanych: '.var_dump($this->new_to_regenerated) );
            }
            // wersja renerowana takze zostaje uaktualniona
            $car = array(
                'make_id' => (int)$this->current_make_id,
                'model_id' => (int)$model_id,
                'type_id'  => (int)$type_id,
                'product_id' => (int)$product_regenerated_id,
                'alt_desc' => array('desc' => $desc),
            );

            $this->model_tool_cars->productToCarInsert($car);

        }
        else
        {

            $keyword = str_ireplace(' ','-',trim($this->cleanKeyword($title)));

            // tutaj tylko czasem problemy z type_id, pewnie wina parsowania

            $product = array(
                'product_category' => array($category_id),
                'model' => $model,
                'status' => $good,
                'product_description' => $desc,

                'sku'    =>  NULL,
                'upc'    =>  NULL,
                'location'    =>  NULL,
                // standardowo 0, on sobie to potem zmieni
                'quantity'    =>  999,
                'image'    =>  $product_image,

                'manufacturer_id'    =>  $manufacturer_id,
                'price'    =>  0,
                'points'    =>  NULL,
                'ean' =>  NULL,
                'jan' =>  NULL,
                'isbn' =>  NULL,
                'mpn' =>  NULL,

                'date_available'    =>  date('Y-m-d'),
                'weight_class'    =>  NULL,
                'length'    =>  NULL,
                'height'    =>  NULL,
                'height_class'    =>  NULL,

                'minimum'    =>  NULL,
                'sort_order' => 0,
                'subtract' => 1,
                'stock_status_id' => 0,
                'shipping' => 1,
                'weight' => 0,
                'weight_class_id' => 0,
                'width' => 0,
                'length_class_id' => 0,
                'tax_class_id' => 0,
                'product_tag' => 0,
                'keyword' => array('pl' => $this->generateSeoAlias('new',$code,$this->current_make['name'],$this->current_model['name'],'2',$type,$this->current_category_id), 'de' => $this->generateSeoAlias('new',$code,$this->current_make['name'],$this->current_model['name'],'2',$type,$this->current_category_id)),
                'product_store' => array(0,1),
                'type' => 'regenerated',

            );

           $product_regenerated_id = $this->model_catalog_product->addProduct($product);


            $car = array(
                'make_id' => $this->current_make_id,
                'model_id' => $model_id,
                'type_id'  => $type_id,
                'product_id' => $product_regenerated_id
            );

            $this->model_tool_cars->productToCarInsert($car);

            // teraz dodajemy nuwke
            $product['type'] = 'new';
            $product['regenerate_or_new_id'] = $product_regenerated_id;

            $product['quantity'] = 0;

            $product['product_description'] = $desc_new;

            $product['keyword'] = array('pl' => $this->generateSeoAlias('regenerated',$code,$this->current_make['name'],$this->current_model['name'],'2',$type,$this->current_category_id), 'de' => $this->generateSeoAlias('regenerated',$code,$this->current_make['name'],$this->current_model['name'],'2',$type,$this->current_category_id));
;

            $product_id = $this->model_catalog_product->addProduct($product);

            $car = array(
                'make_id' => $this->current_make_id,
                'model_id' => $model_id,
                'type_id'  => $type_id,
                'product_id' => $product_id
            );

            $this->model_tool_cars->productToCarInsert($car);

            if(!$product_id)
            {
              //   throw new Exception("Nie duało się odzyskać id nowo zapisanego prudktu");
            }

            if(!$product_regenerated_id)
            {
             //   throw new Exception("Nie duało się odzyskać id nowo zapisanego regenerowanego prudktu");
            }


            $this->product_codes[$code] = $product_id;

            $this->new_to_regenerated[$product_id] = $product_regenerated_id;
        }


    }


    private function getLanguageCodes()
    {
        $this->load->model('localisation/language');

        $this->langs = $this->model_localisation_language->getLanguages();


    }

    private function dowloadImage($data)
    {

        $ch = curl_init();

        curl_setopt ($ch, CURLOPT_URL, $data);

        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 0);

        $fileContents = curl_exec($ch);

        curl_close($ch);
        if($fileContents AND strpos($data,'no_image')===false){
            $newImg = imagecreatefromstring($fileContents);

            $tmp = explode('/',$data);



            $newshort="data/".array_pop($tmp);
            $newname=DIR_IMAGE.$newshort;

            imagejpeg($newImg,$newname ,100);

            return $newshort;
        }else{
            return 'no_image.jpg';
        }



    }


    public function getManufacturersId($data) {

        $query = $this -> db -> query("SELECT * FROM manufacturer WHERE LCASE(name)='".strtolower($data['name'])."' ");

        if(isset($query->row['manufacturer_id'])){
            return $query->row['manufacturer_id'];
        }else{
            $query = $this -> db -> query("INSERT INTO manufacturer SET name='".$data['name']."', sort_order=0  ");
            $id=$this->db->getLastId();
            $query = $this -> db -> query("INSERT INTO manufacturer_to_store SET manufacturer_id='".$id."', store_id='0' ");
            return $id;
        }


    }

    public function cleanKeyword($words)
    {
        return str_ireplace(array(',','.','/','\\','(',')',';',':'),'',$words);
    }
	
	private function generateMeta($lang,$title)
	{
	     $title= trim($title);

	     if($lang==2)
		 {
		     return str_ireplace('{title}',$title,$this->meta_description_pl);
		 }
		 if($lang==3)
		 {
		     return str_ireplace('{title}',$title,$this->meta_description_de);
		 }
		 
		  return $title;
	}
	
	private function generateKeyword($title)
	{
	  
	     $title = trim($title);
		 
		 return str_ireplace(' ',',',$title);
	
	}
	
	private function getFileSize($link)
	{
	    
		 // URL to file (link)
    $file = $link;

    $ch = curl_init($file);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $data = curl_exec($ch);
    curl_close($ch);

    if (preg_match('/Content-Length: (\d+)/', $data, $matches)) {

        // Contains file size in bytes
        return (int)$matches[1];

    }
	
	return false;
	
	}



    public function translateDesc($desc,$key)
    {

        $remove = '<p>This <strong>Diesel Pump</strong> is repaired to Bosch specifications we are a full <strong>Bosch Diesel agent</strong></p>';
        $desc = str_ireplace($remove,'',$desc);

        $remove = "<p>This <strong>Common Rail Diesel Injectors</strong> is a repaired to Bosch Standards, Using state of the art <strong>Diesel test equipment</strong>, is sold on a exchange price. Interested in purchasing a <strong>Common Rail Injecto</strong>r outright&nbsp; Call for details</p>";
        $desc = str_ireplace($remove,'',$desc);

        $remove ="<p>This <strong>Common Rail Diesel Pump</strong> is repaired to Bosch specifications we are a full <strong>Bosch Diesel agent</strong></p>";
        $desc = str_ireplace($remove,'',$desc);


        foreach($this->translateArray[$key] as $fraza => $tlumaczenie)
        {
            if(!is_array($tlumaczenie)){
                $desc = str_ireplace($fraza,$tlumaczenie,$desc);
            }

        }

        return $desc;
    }

    private function checkTranslatePatter($title,$key,$type)
    {

    }

    public function translateTitle($title,$key,$type,$link)
    {

        // zabezpieczenie jesli nie wejdzie chociaz raz do tej petli, to znaczy ze nic nie przetlumaczyl i mamy kwiatka
        $translated = false;

        // wersja regenrowana
        if($type=='regenerated')
        {
            foreach($this->translateArray[$key] as $fraza => $tlumaczenie)
            {
                if(!is_array($tlumaczenie)){
                    $title = str_ireplace($fraza,$tlumaczenie,$title);
                    $translated = true;
                }

            }
        }

        // wersja nowa
        if($type=='new')
        {
            foreach($this->translateArrayNew[$key] as $fraza => $tlumaczenie)
            {
                if(!is_array($tlumaczenie)){
                    $title = str_ireplace($fraza,$tlumaczenie,$title);
                    $translated = true;
                }

            }
        }



        if($translated)
        {
            return $title;
        }
        else
        {
            throw new Exception("Nie udało się dokonać tłumaczenia: ".var_dump($title).$link);
            return false;
        }

    }

    private function generateSeoAlias($type,$code,$make_name,$model_name,$lang,$type_name,$category_id)
    {
        /*
         * patter:
         * stan - kategoria - numer - marka - model - model - typ
         */


        $state = NULL;

        if($type=='regenerated' AND $lang == 2 AND $this->current_category_id == 59)
        {
            $state = 'Regenerowana';
        }
        if($type=='new' AND $lang == 2 AND $this->current_category_id == 59)
        {
            $state = 'Nowa';
        }

        if($type=='regenerated' AND $lang == 2 AND $this->current_category_id == 60)
        {
            $state = 'Regenerowany';
        }
        if($type=='new' AND $lang == 2 AND $this->current_category_id == 60)
        {
            $state = 'Nowy';
        }

        if($type=='regenerated' AND $lang == 3 AND $this->current_category_id == 59)
        {
            $state = 'Regeneriert';
        }
        if($type=='new' AND $lang == 3 AND $this->current_category_id == 59)
        {
            $state = 'Neu';
        }

        if($type=='regenerated' AND $lang == 3 AND $this->current_category_id == 60)
        {
            $state = 'Regeneriert';
        }
        if($type=='new' AND $lang == 3  AND $this->current_category_id == 60)
        {
            $state = 'Neu';
        }



        // nazwa kategorii
        $category = $this->model_catalog_category->getCategory($category_id);

        //

        $words = $state.'-'.$category['name'].'-'.$code.'-'.$make_name.'-'.$model_name.'-'.$type_name;


        $keyword = str_ireplace(array(',','.','/','\\','{','}','(',')',':',';',' ','  ','   ','     '),'',trim($words));
        $keyword = str_ireplace(' ','-',trim($keyword));

        return $keyword;
    }




}