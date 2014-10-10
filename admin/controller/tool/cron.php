<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 18.09.14
 * Time: 11:25
 */

class ControllerToolCron extends Controller{

    public function syncCatalog()
    {
        $this->load->model('import/csv');
        $this->load->model('localisation/language');
        $l = $this->model_localisation_language->getLanguages();
        //$this->model_import_csv->start(DIR_APPLICATION.'../cennik.csv',$l);
        $this->load->model('import/synchro_xml');
        $this->model_import_synchro_xml->synchro('http://stocks.royaldesign.pl/products.xml?query=*',"dawidrz:Dawid.Rz43",false);

        // sprawdzamy czy istnieja obrazy produktów //
        /*$q = $this->db->query("SELECT * FROM product_image ");

        foreach($q->rows as $row)
        {
            if(!file_exists(DIR_IMAGE.$row['image']))
            {
                $this->db->query("DELETE FROM product_image WHERE product_image_id = '".(int)$row['product_image_id']."' ");
            }
        }*/

        /* foreach(glob(DIR_IMAGE.'data/import_royal_design/*') as $file) {
             $t = explode('/',$file);
             $last = array_pop($t);
             /*$last[0] = 'R';
             array_push($t,$last);
             $new = implode('/',$t);

             rename($file,$new);*/
        /*$t = explode('.',$file);
        $ext = array_pop($t);


        if($ext == 'png')
        {
            $last1 = str_ireplace('png','jpg',$last);
            $q = $this->db->query("SELECT * FROM product WHERE image = 'data/import_royal_design/".$last1."' ");


            if($q->num_rows)
            {
                $this->db->query("UPDATE product SET image = REPLACE(image,'jpg','png') WHERE product_id = '".(int)$q->row['product_id']."' ");
            }

            $q = $this->db->query("SELECT * FROM product_image WHERE image = 'data/import_royal_design/".$last1."' ");

            if($q->num_rows)
            {
                $this->db->query("UPDATE product_image SET image = REPLACE(image,'jpg','png') WHERE product_image_id = '".(int)$q->row['product_image_id']."' ");
            }

            $q = $this->db->query("SELECT * FROM product_option_value WHERE image = 'data/import_royal_design/".$last1."' ");

            if($q->num_rows)
            {
                $this->db->query("UPDATE product_option_value  SET image = REPLACE(image,'jpg','png') WHERE product_option_value_id = '".(int)$q->row['product_option_value_id']."' ");
            }

            // dla opcji ta sama podmianka
        }
    }*/
    }
}