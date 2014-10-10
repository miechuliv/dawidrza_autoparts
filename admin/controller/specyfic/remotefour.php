<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 28.08.13
 * Time: 13:58
 * To change this template use File | Settings | File Templates.
 */

class ControllerSpecyficRemotefour extends Controller{

    private $url = 'http://sklep.moto-car.net/clientcatalogue/{number},23-1--{number}----{number}.aspx';
    private $base_url = 'http://sklep.moto-car.net';
    private $snoopy;

    private $codes = array();

    private $example = '0414720310';

    private $current_code;

    private $current_product_id;

    private $meta_description_pl = "Najlepszy sklep z nowymi oraz regenerowanymi injektorami / wtryskiwaczami, pompami paliwa i turbosprężarkami {title} auto-gatzka.pl ";

    private $meta_description_de = "Bester Online Shop fuer Injektoren / Einspritzduesen, Dieselpumpen und Turbolader {title} diesel-land.de ";

    private $category_id = 62;

    private $current_cars;

    private $current_regenerate_id;

    private $current_category_id;

    private $ids_list = array();

    public function index()
    {


        set_time_limit(30000);

        error_reporting(1);

        ini_set('display_errors','1');

        $this->snoopy = new Snoopy();

           $this->getProductCodes();

           // wyrzucam duplikaty
           array_unique($this->codes);

           $this->load->model('tool/cars');



           foreach($this->codes as $product_id => $data)
           {


                 $link = $this->getProductPage($data['code']);

                 $this->current_code = $data['code'];

                 $this->current_regenerate_id = $data['regenerated_id'];

                 $this->current_product_id = $product_id;

                 $this->current_category_id = $data['category_id'];

                  // get make, model , type
                 $this->current_cars = $this->model_tool_cars->getAllCarsByProductId($product_id,true);

                 $this->current_cars = $this->model_tool_cars->filterCarsDuplicates($this->current_cars);


               if($link)
               {
                   $this->getRelatedList($link);
               }

           }




    }

    private function getProductCodes()
    {
             $this->load->model('catalog/product');

        // biore tylko wersja nowa
             $filters = array(
                 'type' => 'new',
             );

             $results = $this->model_catalog_product->getProducts($filters);

             foreach($results as $key => $result)
             {

                 $this->ids_list[] = $result['product_id'];

                 $product_categories = $this->model_catalog_product->getProductCategories($result['product_id']);

                 $category_id = array_shift($product_categories);

                  $this->codes[$result['product_id']] = array(
                      'code' => $result['model'],
                      'regenerated_id' => $result['regenerate_or_new_id'],
                      'category_id' => $category_id ,
                  );

                  unset($results[$key]);
             }



    }

    private function getProductPage($code)
    {
            $url = str_ireplace('{number}',$code,$this->url);

            $this->snoopy->fetch($url);



        $dom =  new DOMDocument();
        $dom->loadHTML($this->snoopy->results);





        $node = $dom->getElementById('partscatalogoue_article_tab');





            if(is_object($node) AND $node->hasChildNodes())
            {

                $text = $node->nodeValue;

                if(strpos($text,'Towary powiązane'))
                {
                    //var_dump($this->snoopy->results);

                    $as = $node->getElementsByTagName('a');


                    $href = $as->item(0)->getAttribute('href');


                    return $href;
                }
                else
                {
                    return false;
                }

            }
            else
            {

                return false;
            }


    }

    private function getRelatedList($link)
    {
           $url = $this->base_url.$link;



           $this->snoopy->fetch($url);

        $dom =  new DOMDocument();
        $dom->loadHTML($this->snoopy->results);

        $finder = new DomXPath($dom);
        $classname="clickable";
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        $product = array();

        foreach($nodes as $key => $node)
        {
            $as = $node->getElementsByTagName('a');


            $href = $as->item(0)->getAttribute('href');
            $url = $this->base_url.$href;

            $this->snoopy->fetch($url);

            $from_page_data = new stdClass();



             $dom =  new DOMDocument();
             $dom->loadHTML($this->snoopy->results);

            // kod produktu
            $finder = new DomXPath($dom);
            $classname="ofpCode";
            $node = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

            $from_page_data->code = $node->item(0)->nodeValue;

            // nazwa
            $finder = new DomXPath($dom);
            $classname="ofpName";
            $node = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

            $from_page_data->name = $node->item(0)->nodeValue;

            // cena
            $finder = new DomXPath($dom);
            $classname="ofpClientNetPrice";
            $node = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

            $from_page_data->price = $node->item(0)->nodeValue;



            $this->saveProduct($from_page_data);

        }


    }

    private function saveProduct($from_page_data)
    {
           /*
            * @todo zapisac produkt, zaciagnac mapowanie z produktu
            */

           if(!in_array($this->current_product_id,$this->ids_list))
           {
                return false;
           }

           $description = '<p>'.$from_page_data->name.'</p></br>'.$this->getMatchingCarsTable($this->current_cars);


            $desc[2]= array(
                'name' => $from_page_data->name,
                'description' => $description ,
                'meta_description' => $this->generateMeta('2',$from_page_data->name),
                'meta_keyword' => $this->generateKeyword($from_page_data->name),
                'tag' => '',
            );


            $desc[3]=array(
                'name' => $from_page_data->name,
                'description' => $description,
                'meta_description' => $this->generateMeta('3',$from_page_data->name),
                'meta_keyword' => $this->generateKeyword($from_page_data->name),
                'tag' => '',
            );


        $product = array(
            'product_category' => array($this->category_id),
            'model' => $from_page_data->code,
            'status' => 1,
            'product_description' => $desc,

            'sku'    =>  NULL,
            'upc'    =>  NULL,
            'location'    =>  NULL,
            // standardowo 0, on sobie to potem zmieni
            'quantity'    =>  0,
            'image'    =>  'no_image.jpg',

            'manufacturer_id'    =>  NULL,
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
            'keyword' => array('pl' => $this->generateSeoAlias($from_page_data->name,$from_page_data->code), 'de' => $this->generateSeoAlias($from_page_data->name,$from_page_data->code)),
            'product_store' => array(0,1),
            'type' => 'new',

        );


            $related_id = $this->model_catalog_product->addProduct($product);


            // tworze powiązania

            $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$this->current_product_id . "' AND related_id = '" . (int)$related_id . "'");
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$this->current_product_id . "', related_id = '" . (int)$related_id . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$this->current_product_id . "'");
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$this->current_product_id . "'");

            // trzeba też jakoś powiązać wersje do regeneracji, ale tyko w jedna strone

        $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$this->current_regenerate_id . "' AND related_id = '" . (int)$related_id . "'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$this->current_regenerate_id . "', related_id = '" . (int)$related_id . "'");



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



    private function generateSeoAlias($part_name,$part_code)
    {
        /*
         * patter:
         * marka - model - model - typ
         */

        /*
         * pompy - 59 injectory - 60
         */
        $type='';

        if($this->current_category_id == 59)
        {
            $type = "Pompa";
        }

        if($this->current_category_id == 60)
        {
            $type = "Wtryskiwacz";
        }



        $words = $part_code.'-'.$part_name.'-'.$type.'-'.$this->current_code;


        $keyword = str_ireplace(array(',','.','/','\\','{','}','(',')',':',';','  ','   ','     '),'',trim($words));
        $keyword = str_ireplace(' ','-',trim($keyword));

        return $keyword;
    }

    private function getMatchingCarsTable($cars)
    {
        if(!empty($cars))
        {
            $html = '<p class="cars-related-p">Ta część pasuje do:</p><br/>';

            $html .= '<table class="cars-related" >';

            $html .= '<tr>';
            $html .= '<td><span class="cars-title">Marka: </span></td>';
            $html .= '<td><span class="cars-title">Model: </span></td>';

            $html .= '<td><span class="cars-title">Typ: </span></td>';
            $html .= '</tr>';

            foreach($this->current_cars as $car)
            {
                $html .= '<tr>';
                $html .= '<td><span class="car-value">'.$car["make_name"].'</span></td>';
                $html .= '<td><span class="car-value">'.$car["model_name"].'</span></td>';

                if($car["type_id"])
                {
                    $html .= '<td><span class="car-value">'.$car["type_name"].'</span></td>';
                }

                $html .= '</tr>';
            }

            $html.= '</table>';

            return $html;
        }

        return '';

    }



}