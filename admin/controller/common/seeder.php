<?php 
class ControllerCommonSeeder extends Controller { 


	public function index() {

        $this->db->query("DELETE FROM product");
        $this->db->query("DELETE FROM product_description");
        $this->db->query("DELETE FROM product_to_category");
        $this->db->query("DELETE FROM product_to_store");

        $this->db->query("DELETE FROM category");
        $this->db->query("DELETE FROM category_description");
        $this->db->query("DELETE FROM category_to_store");
        $this->db->query("DELETE FROM category_path");

        $this->db->query("DELETE FROM url_alias");

        $this->load->model('catalog/category');

        $this->load->model('catalog/product');

        $liczba_kat = 10;
        $liczba_kat_pod = 10;
        $poziomy = 3;
        $produkty =100;

        for($i=0;$i<$liczba_kat;$i++)
        {
            $this->seedCategories(0,$liczba_kat_pod, $poziomy, $produkty);
        }




  	}

    public function seedCategories($cat_id = 0, $sub_cats, $poziom , $produkty)
    {

            // dodajmey kategorie

            $data = array(
                'parent_id' => $cat_id,

                'top' => 1,
                'column' => 0,
                'sort_order' => 0,
                'status' => 1,
                'category_store' => array(0),
                'category_description' => array(
                    2 => array(
                        'name' => 'dfdfhdhf',
                        'meta_keyword' => 'gfgfjgfj',
                        'meta_description' => 'gdsgdsg',
                        'description' => 'dfhdfh',
                        'tag' => 'dgdsgsd',
                    ),
                )
            );

            $cat_id = $this->model_catalog_category->addCategory($data);

            // seed products in this cat
            $this->seedProduct($cat_id,$produkty);



            if($poziom)
            {
                for($i=0;$i<$sub_cats;$i++)
                {
                    $this->seedCategories($cat_id,$sub_cats,$poziom-1);
                }

            }


            return $cat_id;

    }

    public function seedProduct($cat_id,$liczba)
    {
        $data = array(
            'model' => 'fdhdfh',
            'upc' => '',
            'sku' => '',
            'ean' => '',
            'isbn' => '',
            'location' => '',
            'minimum' => '',
            'jan' => 'fgdf',
            'mpn' => 'dfhdf',


            'substract' => 1,
            'quantity' => 1000,
            'price' => 90,
            'weight' => 0,
            'width' => 0,
            'legth_class_id' => 0,
            'tax_class_id' => 0,
            'stock_status_id' => 0,
            'date_available' => 0,
            'manufacturer_id' => 0,
            'shipping' => 0,
            'points' => 0,
            'weight_class_id' => 0,
            'length' => 0,
            'height' => 0,
            'status' => 1,
            'sort_order' => 0,
            'product_store' => array(0),
            'kaucja_zw' => 0,
            'kaucja_bzw' => 0,
            'delivery_time' => 0,
            'product_category' => array(0 => $cat_id),
            'kaucja_zw_pl' => 0,
            'kaucja_bzw_pl' => 0,
            'delivery_time_pl' => 0,
            'price_pl' => 0,
            'image' => 'data/dummy.jpg',
            'product_description' => array(
                2 => array(
                    'name' => 'dfdfhdhf',
                    'meta_keyword' => 'gfgfjgfj',
                    'meta_description' => 'gdsgdsg',
                    'description' => 'dfhdfh',
                    'tag' => 'dgdsgsd',
                ),
            ),
            'keyword' => 'dfgdfdf',
        );

        $id = $this->model_catalog_product->addProduct($data);

        for($p=0;$p<$liczba;$p++)
        {
            $this->model_catalog_product->copyProduct($id);
        }
    }
}
?>