<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 02.04.14
 * Time: 08:41
 * To change this template use File | Settings | File Templates.
 */

class ModelImportCsv extends Model{

    private $location;

    private $products_group = array();

    private $languages;

    function __construct($registry,$languages = array())
    {
        parent::__construct($registry);
        $this->languages = $languages;


    }

    public function convertLine($line)
    {
        $d = array();


        foreach($line as $field)
        {
            $t = iconv('iso-8859-2','utf-8',$field);

            $d[] = str_ireplace(array('š'),array('ą'),$t);
        }



        return $d;
    }

    public function start($location,$languages)
    {
        $this->languages = $languages;

        if(!file_exists($location))
        {
            throw new Exception('brak pliku: '.$location);


        }



        $h = fopen($location,'r');

        $headers = $this->getHeaders($h,3);

        $i = 1;
        /* najpierw grupujemy produkcy ponieważ niektóre trzeba będzie połaczyćw jedno */
        while($row = fgets($h))
        {

            $row = explode(';',$row);
           // $row = $this->convertLine($row);
            $model = $this->decodeModel($row[0]);

            $this->groupProducts($model['model'],$row[3],$model['color'],trim($row[6]),$i,$row);

            $i++;

        }

        /* zgrupowane produkty można teraz zapisać */
        $this->load->model('catalog/product');
        $this->load->model('catalog/attribute');



        foreach($this->products_group as $group)
        {
            $this->saveProductGroup($group);
        }


    }

    public function saveProductGroup($group)
    {
        if(!is_array($group) OR empty($group))
        {
            throw new Exception('bad row');
        }

        if(count($group) == 1)
        {
            // tyko zapis
            $t = array_shift($group);
            $this->saveProduct($t['model'],$t['data']);
        }
        else
        {
            // zapis pierwszego i pozostałe jako opcje
            $t = array_shift($group);
            $opt = array();
            foreach($group as $product)
            {
                $opt[$product['color']] = $product['data'];
            }

            $this->saveProduct($t['model'],$t['data'],$opt);
        }
    }

    public function getCategory($parent_id,$category_name)
    {
        $this->load->model('catalog/category');

        $search_data = array(
            'filter_name' => $category_name,
        );

        $category = $this->model_catalog_category->getCategories($search_data);

        $category_id = false;

        if(!empty($category))
        {
            $category_id = $category[0]['category_id'];
        }
        else{

            $desc = array();

            foreach($this->languages as $language)
            {
                $desc[$language['language_id']] = array(
                    'name' => $category_name,
                    'meta_keyword' => '',
                    'meta_description' => '',
                    'description' => $category_name,
                );
            }

            $insert_data = array(
                'top' => 1,
                'column' => 0,
                'sort_order' => 0,
                'status' => 1,
                'virtual' => 0,
                'category_description' => $desc,
                'keyword' => $this->getMultilangKeyword($category_name),
                'parent_id' => $parent_id,
                'category_store' => array(0),
            );

            $category_id = $this->model_catalog_category->addCategory($insert_data);
        }

        return $category_id;
    }

    public function getMultilangKeyword($title)
    {
        $ar = array();

        foreach($this->languages as $language)
        {
            $res = strtolower(trim($title));
            $res = str_ireplace('   ','-',$res);
            $res = str_ireplace('  ','-',$res);
            $res = str_ireplace(' ','-',$res);
            $res = str_ireplace(array('ą','ż','ź','ć','ń','ś','ł','ę','ó'),array('a','z','z','c','n','s','l','e','o'),$res);
            $ar[$language['code']] = preg_replace('/[^a-zA-Z-]/','',$res);
        }

        return $ar;
    }

    public function saveProduct($model,$data,$optional_versions = array())
    {

        $categories = array();

        // kategoria główna
        $c = $this->getCategory(0,$data[5]);
        $categories[] = $c;
        // kategoria podrzędna
        $categories[] = $this->getCategory($c,$data[6]);

        $product_description = $this->formDescription($data[1],$data[7]);


        // atrybuty
        $map = array(
            // nr kolmny w csv => attribute_id //
            // opis cech
            8 => 'opis cech',
            // opis dodatkowy
            9 => 'opis dodatkowy',
            // podstawowy materiał wykonania
            10 => 'podstawowy materiał wykonania',
            // dodatkowy materiał wykonania
            11 => 'dodatkowy materiał wykonania',
            // kategoria zdobienia
            12  => 'kategoria zdobienia',
            // sugerowana technika zdobienia
            13 => 'sugerowana technika zdobienia',
            // liczba kolorów
            14 => 'liczba kolorów',
            // dłudość obszaru
            15 => 'dłudość obszaru',
            // szerkość obszaru
            16 => 'szerkość obszaru',
            // materiał wykonania opakowania
            20 => 'materiał wykonania opakowania',
            // rodzaj opakowana jednostowego
            21 => 'rodzaj opakowana jednostowego',
            // kolor opakowania jednostkowego
            22 => 'kolor opakowania jednostkowego',
            // baterie
            23 => 'baterie',
            // cechy specyficzne
            24 => 'cechy specyficzne',
            // kolor podstawowy
            25 => 'kolor podstawowy',
            // kolor dodatkowy
            26 => 'kolor dodatkowy',

            // opakowanie jednostkowe - waga brutto
            27 => 'opakowanie jednostkowe - waga brutto',
            // opakowanie jednostkowe - waga netto
            28 => 'opakowanie jednostkowe - waga netto',
            // opakowanie jednostkowe - długość
            29 => 'opakowanie jednostkowe - długość',
            // opakowanie jednostkowe - szerokość
            30 => 'opakowanie jednostkowe - szerokość',
            // opakowanie jednostkowe - wysokość
            31 => 'opakowanie jednostkowe - wysokość',

            // karton wewnętrzny - waga brutto
            32 => 'karton wewnętrzny - waga brutto',
            // karton wewnętrzny  - waga netto
            33 => 'karton wewnętrzny  - waga netto',
            // karton wewnętrzny  - długość
            34 => 'karton wewnętrzny  - długość',
            // karton wewnętrzny - szerokość
            35 => 'karton wewnętrzny - szerokość',
            // karton wewnętrzny  - wysokość
            36 => 'karton wewnętrzny  - wysokość',

            // karton duży - waga brutto
            37 => 'karton duży - waga brutto',
            // karton duży - waga netto
            38 => 'karton duży - waga netto',
            // karton duży - długość
            39 => 'karton duży - długość',
            // karton duży - szerokość
            40 => 'karton duży - szerokość',
            // karton duży - wysokość
            41 => 'karton duży - wysokość',

            // ilośc w jedsnotakch podstawowych
            42 => 'ilośc w jedsnotakch podstawowych',
            // ilość na palecia wysyłowej
            43 => 'ilość na palecia wysyłowej',

        );

        $attributes = array();

        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        foreach($map as $key => $atr)
        {
            if($data[$key])
            {
                $attributes[] = $this->getAttribute($atr , $data[$key]);
            }

        }


        // opcje z innych produktów... //
        $product_options = array();

        $colors = array();

        $product_options  = array();

        foreach($optional_versions as $md => $optional)
        {

            // kolor : option_id = 1;
            if(!$optional[25] OR $optional[25]=='')
            {
                continue;
            }
            if($optional[26] AND $optional[26]!='')
            {
                $kolor = $optional[25].'_'.$optional[26];
            }
            else
            {
                $kolor = $optional[25];
            }




            $option_id = 1;
            // obrazek nie do wartości opcji
            $option_value_id = $this->getOptionValueId($option_id,$kolor);

            $price = str_ireplace(',','.',$optional[3]);
            $price = (preg_replace("/[^0-9.]/","",$price));
            // obrzek do wartości opcji produktu //
           $colors[] = $this->formProductValueOption($option_id,$option_value_id,1000,0,$optional[28],$optional[0],'data/import_royal_design/'.str_ireplace('.','_',$optional[0]).'.jpg',1);

        }

        // trzeb też dodać kolor aktualnego produktu

        $kolor = false;


        if($data[26] AND $data[26]!='')
        {
            $kolor = $data[25].'_'.$data[26];
        }
        else
        {
            $kolor = $data[25];
        }

        $price = str_ireplace(',','.',$data[3]);
        $price = (preg_replace("/[^0-9.]/","",$price));

        $option_value_id = $this->getOptionValueId(1,$kolor);
        $colors[] = $this->formProductValueOption(1,$option_value_id,1000,0,$data[28],$data[0],'data/import_royal_design/'.str_ireplace('.','_',$data[0]).'.jpg',1);

        $product_options[] = $this->formProductOption($colors,1,1);








        // grawer - liczba kolorów : option_id = 2

        if($data[14] AND $data[14]!='')
        {
            $grawer = array();
            // tyle kolorów ile się max da //
            $l = (int)$data[14];

            for($i=1;$i<($l+1);$i++)
            {
                $option_value_id = $this->getOptionValueId(2,$i);
                $grawer[] = $this->formProductValueOption(2,$option_value_id,1000,0,1000,$data[0],'');
            }



            $product_options[] = $this->formProductOption($grawer,2);
        }



        // grawe tak/ nie
        $wybór = array();
        $wybór[] = $this->formProductValueOption(3,1,1000,0,1000,$data[0],'');
        $wybór[] = $this->formProductValueOption(3,2,1000,0,1000,$data[0],'');

        $product_options[] = $this->formProductOption($wybór,3,1);




        // dodatkowe obrazki //
        $product_images = array(
            1 => array('sort_order' => 0, 'image' => 'data/import_royal_design/'.str_ireplace('.','_',$data[0]).'_a.jpg'),
            2 => array('sort_order' => 0, 'image' => 'data/import_royal_design/'.str_ireplace('.','_',$data[0]).'_b.jpg'),
            3 => array('sort_order' => 0, 'image' => 'data/import_royal_design/'.str_ireplace('.','_',$data[0]).'_c.jpg'),
            4 => array('sort_order' => 0, 'image' => 'data/import_royal_design/'.str_ireplace('.','_',$data[0]).'_d.jpg'),
        );




        $product = array(
            'product_image' => $product_images,

            'category_name' => $data[5],
            'subcategory_name' => $data[6],
            'original_model' => $data[0],
            'original_price' => $price,


            'product_category' => $categories,
            'model' => $data[0],
            'status' => 1,
            'product_description' => $product_description,

            'sku'    =>  NULL,
            'upc'    =>  NULL,
            'location'    =>  NULL,
            'quantity'    =>  0,
            'image'    =>  'data/import_royal_design/'.str_ireplace('.','_',$data[0]).'.jpg',

            'buy_price' =>  0.55*(float)$price,
            'price'    =>  0.55*(float)$price,
            'points'    =>  NULL,
            'ean' =>  NULL,
            'jan' =>  NULL,
            'isbn' =>  NULL,
            'mpn' =>  NULL,

            'date_available'    =>  date('Y-m-d'),
            'weight_class'    =>  NULL,
            'length'    =>  $data[17],
            'height'    =>  $data[19],
            'height_class'    =>  NULL,

            'minimum'    =>  NULL,
            'sort_order' => 0,


            'subtract' => 1,
            'stock_status_id' => 0,
            'shipping' => 1,
            'weight' => $data[28],
            // kg
            'weight_class_id' => 1,
            'width' => $data[18],
            // mm
            'length_class_id' => 2,
            'tax_class_id' => 0,
            'product_tag' => 0,

            'product_store' => array(0),
            'manufacturer_id',

            'product_attribute' => $attributes,
            'product_option' => $product_options,
            'delivery_time' => 10,
            'delivery_price' => 0,
            'feed' => 0,
            'google_merchant' => 0,
            'manufacturer_id' => 0,
            'keyword' => $this->getMultilangKeyword($data[1]),


        );


        $this->model_catalog_product->addProduct($product);
    }

    public function formDescription($title,$description)
    {

        $product_description = array();

        foreach($this->languages as $language)
        {
            $product_description[$language['language_id']] = array(
                'name' => $title,
                'description' => $description,
                // @todo to implement
                'meta_description' => '',
                'meta_keyword' => '',
                'tag' => '',
            );
        }

        return $product_description;

    }

    /* wyszukuje wartość opcji po nazwie, jak nie ma to taka tworzy i zwraca */
    public function getOptionValueId($option_id,$value,$image = '')
    {
        $q = $this->db->query("SELECT * FROM `".DB_PREFIX."option_value_description` WHERE name = '".$this->db->escape(strtolower($value))."' ");

        if($q->num_rows)
        {
            $option_value_id = $q->row['option_value_id'];
            return $option_value_id;
        }
        else
        {
            // insert new value with descripotion //
            $this->db->query("INSERT INTO " . DB_PREFIX . "option_value SET
				 option_id = '" . (int)$option_id . "',
				 image = '" . $this->db->escape(html_entity_decode($image, ENT_QUOTES, 'UTF-8')) . "',
				  sort_order = '" . 0 . "'");

            $option_value_id = $this->db->getLastId();

            foreach ($this->languages as $language) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description SET
					option_value_id = '" . (int)$option_value_id . "',
					language_id = '" . (int)$language['language_id'] . "',
					option_id = '" . (int)$option_id . "',
					name = '" . $this->db->escape($value) . "' ");
            }

            return $option_value_id;

        }
    }

    public function formProductValueOption($option_id,$option_value_id,$quantity,$price,$weight,$model,$image,$substract = 0,$original_model = 0)
    {
        $as = array(
            'option_id' => $option_id,
            'option_value_id' => $option_value_id,
            'quantity' => (int)$quantity ,
							  'subtract' => $substract,
							  'price' => (float)$price,
							   'price_prefix' => '+',
							   'points' => 0,
							    'points_prefix' => '+',
							    'weight' => $weight,
							    'weight_prefix' => '+',
                                'original_model' => $model,
            'image' => $image,


        );


        return $as;
    }

    public function formProductOption($as,$option_id,$required = 0)
    {
        $ar = array(
            'option_id' => $option_id,
            'type' => 'select',
            'required' => $required,
            'product_option_value' => $as
        );

        return $ar;
    }



    public function getAttribute($name,$value)
    {
        // attribute_id i text
        $search_data = array(
            'filter_name' => $name
        );

        $res = $this->model_catalog_attribute->getAttributes($search_data);

        if(!empty($res))
        {
            $attribute_id = $res[0]['attribute_id'];
        }
        else
        {
            $desc = array();
            foreach($this->languages as $language)
            {
                $desc[$language['language_id']] = array(
                    'name' => $name
                );
            }
            $insert_data = array(
                'attribute_group_id' => 1,
                'sort_order' => 0,
                'attribute_description' => $desc,
            );


            $attribute_id = $this->model_catalog_attribute->addAttribute($insert_data);
        }

        $attr_desc = array();
        foreach($this->languages as $language)
        {
            $attr_desc[$language['language_id']] = array(
                'text' => $value
            );
        }

        $attribute = array(
            'attribute_id' => $attribute_id,
            'product_attribute_description' => $attr_desc,

        );

        return $attribute;
    }

    public function decodeModel($model)
    {
            $t = explode('.',$model);

            $color = false;

            if(isset($t[1]))
            {
                $color = $t[1];

                if(isset($t[2]))
                {
                    $color .= '_'.$t[2];
                }
            }

            return array(
                'model' => $t[0],
                'color' => $color,
            );

    }

    public function groupProducts($model,$price,$color,$category,$row_number,$row)
    {
        /* muszą być z tej samej kategorii */
        $key = base64_encode($model.$category.$price);

        if(isset($this->products_group[$key]))
        {
            $this->products_group[$key][$row_number] = array(
                'model' => $model,
                'color' => $color,
                'category' => $category,
                'row_number' => $row_number,
                'data' => $row,
            );
        }
        else
        {
            $this->products_group[$key] = array();
            $this->products_group[$key][$row_number] = array(
                'model' => $model,
                'color' => $color,
                'category' => $category,
                'row_number' => $row_number,
                'data' => $row,
            );
        }
    }

    public function getProductsGroup()
    {
        return $this->products_group;
    }


    public function getHeaders($handle,$offset)
    {
        for($i=0;$i < $offset;$i++)
        {
            $h1 = fgets($handle);
        }

        return fgetcsv($handle);
    }
}