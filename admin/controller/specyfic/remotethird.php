<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 19.08.13
 * Time: 14:40
 * To change this template use File | Settings | File Templates.
 */

class ControllerSpecyficRemoteThird extends Controller{

    private $url = 'http://sklep.moto-car.net/clientcatalogue/clientsearchresult.aspx?g={cat}6&c1=&c2=&c3=&c4=&page={page}';
    private $mapping = array(
        
        array('category_site_code' => 'FN32',
              'name' => 'Bosch CR',
              'category_opencart_id' => array(63,64,65),
              'manufacturer_opencart_id' =>11,
              'page_limit' => 66 ),

        array('category_site_code' => 'FN09',
            'name' => 'Bosch CR',
            'category_opencart_id' => array(63,64,66),
            'manufacturer_opencart_id' =>11,
            'page_limit' => 118 ),

        array('category_site_code' => 'FN13',
            'name' => 'Bosch CR',
            'category_opencart_id' => array(63,64,67),
            'manufacturer_opencart_id' =>11,
            'page_limit' => 7 ),

        array('category_site_code' => 'FN04',
            'name' => 'Bosch CR',
            'category_opencart_id' => array(63,64,68),
            'manufacturer_opencart_id' =>11,
            'page_limit' => 9 ),

        // delphi
        array('category_site_code' => 'FN26',
            'name' => 'Delphi',
            'category_opencart_id' => array(63,69),
            'manufacturer_opencart_id' =>13,
            'page_limit' => 9),

        // denso
        array('category_site_code' => 'FN27',
            'name' => 'Denso',
            'category_opencart_id' => array(63,70),
            'manufacturer_opencart_id' =>14,
            'page_limit' => 11 ),

        // siemens
        array('category_site_code' => 'FN28',
            'name' => 'Siemens',
            'category_opencart_id' => array(63,71),
            'manufacturer_opencart_id' =>12,
            'page_limit' => 5),

        // inne
        array('category_site_code' => 'FN29',
            'name' => 'inne',
            'category_opencart_id' => array(63,72),
            'manufacturer_opencart_id' =>NULL,
            'page_limit' => 2),

    );
    private $snoopy;
    private $langs;

    private $counter;

    private $new_to_regenerated = array();

    private $injectors_category_id = 60;
    private $fuel_pumps_category = 59;

    private $downloaded_images = array();

    private $product_codes = array();

    private $manufacturer_pics = array();

    private $polish_id = 2;

    private $german_id = 3;

    private $meta_description_pl = "Najlepszy sklep z nowymi oraz regenerowanymi injektorami / wtryskiwaczami, pompami paliwa i turbosprężarkami {title} auto-gatzka.pl ";

    private $meta_description_de = "Bester Online Shop fuer Injektoren / Einspritzduesen, Dieselpumpen und Turbolader {title} diesel-land.de ";


    private $translateArray = array(
        // polski
        '2' => array(
            'Reconditioned'  => array('Regenerowany','Regenerowana','Nowy','Nowa'),

            'Fuel Pump Manufacturers Part Number(s):' => 'Numer producenta/seryjny pompy paliwowej:',

            'Injector Manufacturers Part Number(s):' => 'Numer producenta/seryjny pompy wtryskiwacza:',

            'Fuel Pump' => 'Pompa paliwowa',

            'Vehicle Manufacturers Alternative Part Number(s):' => 'Alternatywny numer producenta/seryjny zamiennika samochodu:',

            'Engine Code(s):' => 'Kod silnika:',

            'Production Date:' => 'Data produkcji:',

            'Power Output:'   =>  'Moc:',

            'Number of Cylinders:'  => 'Liczba cylindrów:',

            'Injector'    =>  'Wtryskiwacz',
        ),
        // niemiecki
        '3' => array(


            'Reconditioned'  => array('Regeneriert(e)','Aufbereitet(e)','Neu','Neu'),

            'Fuel Pump Manufacturers Part Number(s):' => 'Seriennummer/Artikelnummer der Kraftstoffpumpe(n)/Dieselpumpe(n):',

            'Injector Manufacturers Part Number(s):' => 'Seriennummer(n)/Artikelnummer(n) der Einspritzdüse(n)/des Injektors:',

            'Fuel Pump' => 'Kraftstoffpumpe(n)/Dieselpumpe(n)',

            'Vehicle Manufacturers Alternative Part Number(s):' => 'Ersatzteilnummer(n):',

            'Engine Code(s):' => 'Motorenkennnummer(n) :',

            'Production Date:' => 'Baujahr(e):',

            'Power Output:'   =>  'Leistung:',

            'Number of Cylinders:'  => 'Zylinderanzahl:',

            'Injector'    =>  'Einspritzdüse(n)',

        )
    );

    public function index()
    {
        $this->test();
    }

    public function test()
    {

        set_time_limit(30000);

        error_reporting(0);

        ini_set('display_errors','0');


        $this->snoopy = new Snoopy();

        $makes = $this->getMakeLinks();

        $this->getLanguageCodes();

        $this->load->model('catalog/product');

        $this->load->model('specyfic/codes');

        foreach($makes as $make)
        {


            $models = $this->getModelLinks($make);

            $products_in_model = array();

            foreach($models as $model)
            {


                $this->getProductInModel($model,$make['make_id']);


            }


        }
    }

    public function getMakeLinks()
    {
        $this->snoopy->fetchlinks($this->url);

        $this->load->model('tool/cars');

        // 15 -53 włacznie
        $makes = array();

        for($i=15;$i<54;$i++)
        {
            $link = $this->snoopy->results[$i];

            $tmp = explode('/',$link);
            $tmp2 = explode('-c',array_pop($tmp));

            $name = str_ireplace('-',' ',array_shift($tmp2));

            $name = trim($name);

            $make_id = $this->model_tool_cars->getMakeIdByName($name);

            $makes[] = array(
                'link'  => $link,
                'make_name' => $name,
                'make_id' => $make_id,

            );

        }

        return $makes;
    }

    public function getModelLinks($make)
    {

        $this->snoopy->results = '';

        $this->snoopy->fetchlinks($make['link']);



        // 20 - fuel pomos, 21 - injectors

        foreach($this->snoopy->results as $result)
        {
            if(strpos($result,'fuel-pumps')!==false)
            {
                $fuel_pumps = $result;

            }

            if(strpos($result,'injectors')!==false)
            {
                $injectors = $result;

            }

        }

        // sciaga linki do modeli w ramach jedenj marki
        $this->snoopy->results = '';
        $this->snoopy->fetch($fuel_pumps);

        $dom =  new DOMDocument();

        $dom->loadHTML($this->snoopy->results);
        // nazwa modelu
        $finder = new DomXPath($dom);
        $classname="prods_pic_bg";
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        $models = array();

        foreach($nodes as $node)
        {

            $image = $node->getElementsByTagName('img')->item(0)->getAttribute('src');

            $link = $node->getAttribute('href');


            $models[] = array(
                'link' => $link,
                'image' => $this->dowloadImage($this->url.$image),
            );

        }


        //  to samo dla drugiej kategorii

        $this->snoopy->results = '';
        $this->snoopy->fetch($injectors);

        $dom =  new DOMDocument();

        $dom->loadHTML($this->snoopy->results);
        // nazwa modelu
        $finder = new DomXPath($dom);
        $classname="prods_pic_bg";
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");


        foreach($nodes as $node)
        {

            $image = $node->getElementsByTagName('img')->item(0)->getAttribute('src');

            $link = $node->getAttribute('href');

            $models[] = array(
                'link' => $link,
                'image' => $this->dowloadImage($this->url.$image),
            );

        }




        return $models;
    }

    public function getProductInModel($model,$make_id)
    {
        $this->snoopy->results = '';

        $this->snoopy->fetch($model['link']);

        $dom =  new DOMDocument();

        if(!$this->snoopy->results OR $this->snoopy->results=='')
        {
            return true;
        }
        $dom->loadHTML($this->snoopy->results);

        // nazwa modelu
        $finder = new DomXPath($dom);
        $classname="breadcrumb";
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        $model_name = $nodes->item(0)->getElementsByTagName('a')->item(3)->nodeValue;

        // info o produkcie
        $finder = new DomXPath($dom);
        $classname="prods_padd";
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        $products_in_model = array();

        foreach($nodes as $product)
        {
            $prod_links = $product->getElementsByTagName('a');

            $trs = $product->getElementsByTagName('tr');

            $strongs = $trs->item(2)->getElementsByTagName('strong');

            $years = $strongs->item(1)->nodeValue;

            $code_row = $trs->item(0)->getElementsByTagName('strong');

            $code = $code_row->item(1)->nodeValue;

            $tmp = explode('-',$years);

            $year_start = trim($tmp[0]);

            $year_end = trim($tmp[1]);

            $year_start = $year_start.'-01-01';

            if(strpos($year_end,"Present")!==false)
            {
                $year_end = date("Y-m-d");
            }
            else
            {
                $year_end = $year_end.'-01-01';
            }




            // dokladny model z rocznikami


            $model_id = $this->model_tool_cars->getModelIdByName($model_name,$year_start,$year_end,$model['image']);


            if(!$model_id)
            {
                $data = array(
                    'make_id' => $make_id,
                    'model_name' => $model_name,
                    'year_start' => $year_start,
                    'year_stop' => $year_end,
                    'image' => $model['image']
                );

                $model_id = $this->model_tool_cars->addModel($data);
            }


            //  sprawdzamy czy mamy juz ten produkt w tablicy po kodach
            if(in_array($code,array_keys($this->product_codes)))
            {
                // @todo dodajemy nowy cars do produktu do db
                $car = array(
                    'make_id' => $make_id,
                    'model_id' => $model_id,
                    'product_id' => $this->product_codes[$code],
                );

                $this->model_tool_cars->productToCarInsert($car);

                $product_regenerated_id = $this->new_to_regenerated[$this->product_codes[$code]];

                // wersja renerowana takze zostaje uaktualniona
                $car = array(
                    'make_id' => $this->current_make_id,
                    'model_id' => $model_id,

                    'product_id' => $product_regenerated_id,
                );

                $this->model_tool_cars->productToCarInsert($car);

            }
            else
            {
                // jesli sciagamy produkt
                $product_regenerated = $this->getProduct($prod_links->item(0)->getAttribute('href'),"regenerated");

                $product = $this->getProduct($prod_links->item(0)->getAttribute('href'));


                if($product AND $product_regenerated){


                    // wrzut produktu do bazy danych, wersja regeracja
                    $product_regenerated_id = $this->model_catalog_product->addProduct($product_regenerated);
                    //  wrzut produktu to cars do bazy



                    $car = array(
                        'make_id' => $make_id,
                        'model_id' => $model_id,
                        'product_id' =>  $product_regenerated_id,
                    );

                    $this->model_tool_cars->productToCarInsert($car);

                    $this->model_specyfic_codes->addCodes($product_regenerated_id,$product_regenerated['codes']);

                    $this->model_specyfic_codes->addEngineCodes($product_regenerated_id,$product_regenerated['engine_codes']);

                    // normalny produkt
                    $product['regenerate_or_new_id'] = $product_regenerated_id;



                    $product_id = $this->model_catalog_product->addProduct($product);
                    //  wrzut produktu to cars do bazy

                    $car = array(
                        'make_id' => $make_id,
                        'model_id' => $model_id,
                        'product_id' =>  $product_id,
                    );

                    $this->model_tool_cars->productToCarInsert($car);

                    // @todo + wrzut kodów to produkty do bazy danych

                    $this->model_specyfic_codes->addCodes($product_id,$product['codes']);

                    $this->model_specyfic_codes->addEngineCodes($product_id,$product['engine_codes']);


                    $this->product_codes[$code] = $product_id;

                    $this->new_to_regenerated[$product_id] = $product_regenerated_id;


                }

            }





        }



    }

    public function getProduct($link, $type = "new")
    {




        $this->snoopy->results = '';

        $this->snoopy->fetch($link);


        if(!$this->snoopy->results OR $this->snoopy->results=='')
        {
            return false;
        }

        $dom =  new DOMDocument();
        $dom->loadHTML($this->snoopy->results);

        // okruchy - wni ch jest info o kategorii i modelu i podstawowym numerzez katalogowym
        $finder = new DomXPath($dom);
        $classname="breadcrumb";
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        $crumbs = $nodes->item(0)->getElementsByTagName('a');

        // opis
        $classname="desc";
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        $desc = $nodes->item(0);

        $inner = $nodes->item(0)->getElementsByTagName('div')->item(0);


        // opis razem z formatowaniem //
        $newdoc = new DOMDocument();
        $cloned = $inner->cloneNode(TRUE);
        $newdoc->appendChild($newdoc->importNode($cloned,TRUE));
        $desc_raw = $newdoc->saveHTML();

        $tmp = explode('<b>',$desc_raw);

        $desc_pl= $this->translateDesc($tmp[0],2,$type);

        $desc_de= $this->translateDesc($tmp[0],3,$type);

        // obrazek
        $classname="prods_pic_bg";
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        $image = $this->url.$nodes->item(0)->getElementsByTagName('img')->item(0)->getAttribute('src');

        // nazwa :P
        $classname="info";
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        $title = $nodes->item(0)->getElementsByTagName('h2')->item(0)->nodeValue;



        $title_pl = $this->translateTitle($title,2,$type);

        $title_de = $this->translateTitle($title,3,$type);




        // producent
        $manufacturer_pic = $desc->getElementsByTagName('img')->item(0)->getAttribute('src');

        $tmp = explode('/',$manufacturer_pic);

        $tmp2 = explode('.',array_pop($tmp));

        $manufacturer_name = array_shift($tmp2);

        if(!in_array($manufacturer_pic,array_keys($this->manufacturer_pics)))
        {
            $full_path = $this->url.$manufacturer_pic;
            $manufacturer_image = $this->dowloadImage($full_path);
            $this->manufacturer_pics[$manufacturer_pic] = $manufacturer_image;
        }
        else
        {
            $manufacturer_image = $this->manufacturer_pics[$manufacturer_pic];
        }

        $data = array(
            'name' => $manufacturer_name,
            'image' => $manufacturer_image,
        );

        $manufacturer_id = $this->getManufacturersId($data);

        // koniec producent

        // kody i kody silników

        $uls = $desc->getElementsByTagName('ul');

        $codes = $uls->item(1);

        $codes_array = array();

        foreach($codes->childNodes as $child)
        {
            $codes_array[] = $child->nodeValue;
        }

        $engine_codes = $uls->item(2);

        $engine_codes_array = array();

        foreach($engine_codes->childNodes as $child)
        {
            $engine_codes_array[] = $child->nodeValue;
        }

        // opis
        $desc = array();

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

        // kategorie
        $category = $crumbs->item(2)->nodeValue;

        $category_id = 0;

        if($category=="Fuel Pumps")
        {
            $category_id = $this->fuel_pumps_category;
        }
        if($category=="Injectors")
        {
            $category_id = $this->injectors_category_id;
        }

        // zassanie obrazka
        if(in_array($image,array_keys($this->downloaded_images)))
        {
            $product_image = $this->downloaded_images[$image];
        }
        else
        {
            $product_image = $this->dowloadImage($image);
            $this->downloaded_images[$image] = $product_image;
        }




        $product = array(
            'product_category' => array($category_id),
            'model' => $crumbs->item(4)->nodeValue,
            'status' => '1',
            'product_description' => $desc,

            'sku'    =>  NULL,
            'upc'    =>  NULL,
            'location'    =>  NULL,
            'quantity'    =>  999,
            'image'    =>  $product_image,
            // @todo manufacturer
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
            //   'keyword' => str_ireplace(' ','-',$product_data['name_2']),
            'product_store' => array(0,1),
            'keyword' => array('pl' => $this->generateSeoAlias($title_pl), 'de' => $this->generateSeoAlias($title_de)),

            'codes' =>  $codes_array,
            'engine_codes' => $engine_codes_array,

        );

        if($type=="regenerated")
        {
            $product['type'] = 'regenerated';

        }
        else
        {
            $product['type'] = 'new';
        }


        return $product;



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

        if($fileContents){
            $newImg = imagecreatefromstring($fileContents);

            $tmp = explode('/',$data);



            $newshort="data/".array_pop($tmp);
            $newname=DIR_IMAGE.$newshort;

            // to sa png wszystko
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
            $query = $this -> db -> query("INSERT INTO manufacturer SET name='".$data['name']."', sort_order=0 , image='".$data['image']."' ");
            $id=$this->db->getLastId();
            $query = $this -> db -> query("INSERT INTO manufacturer_to_store SET manufacturer_id='".$id."', store_id='0' ");
            return $id;
        }


    }

    public function translateDesc($desc,$key,$type)
    {
        foreach($this->translateArray[$key] as $fraza => $tlumaczenie)
        {
            if(!is_array($tlumaczenie)){
                $desc = str_ireplace($fraza,$tlumaczenie,$desc);
            }

        }

        return $desc;
    }

    public function translateTitle($title,$key,$type)
    {


        if(strpos($title,'Reconditioned')!==false)
        {
            $target = 'Reconditioned';
        }

        if(strpos($title,'New')!==false)
        {
            $target = 'New';
        }

        if(strpos($title,'Fuel Pump')!==false)
        {
            //pompa
            // pozycja konca frazy reconditioned
            $start = strpos($title,$target)+strlen($target);
            // pozycja poczatka frazy fuel pump
            $stop = strpos($title,'Fuel Pump');

            $manufacturer = substr($title,$start,$stop-$start);

            $title = str_ireplace($manufacturer.'Fuel Pump',' Fuel Pump'.$manufacturer,$title);


            $title = str_ireplace('Fuel Pump',$this->translateArray[$key]['Fuel Pump'],$title);

            if($type=="regenerated")
            {
                $title = str_ireplace($target,$this->translateArray[$key]['Reconditioned'][1],$title);
            }
            else
            {
                $title = str_ireplace($target,$this->translateArray[$key]['Reconditioned'][3],$title);
            }


        }
        else
        {
            // wtryskiwacz
            $title = str_ireplace('Injector',$this->translateArray[$key]['Injector'],$title);
            if($type=="regenerated")
            {
                $title = str_ireplace($target,$this->translateArray[$key]['Reconditioned'][0],$title);
            }
            else
            {
                $title = str_ireplace($target,$this->translateArray[$key]['Reconditioned'][2],$title);
            }


        }



        return $title;
    }

    private function generateMeta($lang,$title)
    {

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

    private function generateSeoAlias($title)
    {
        $words = explode('-',$title);
        $codes = explode(' ',trim($words[1]));
        $keyword = str_ireplace(array(',','.','/','\\','{','}','(',')',':',';'),'',trim($words[0])).array_shift($codes);
        $keyword = str_ireplace(' ','-',trim($keyword));

        return $keyword;
    }
}