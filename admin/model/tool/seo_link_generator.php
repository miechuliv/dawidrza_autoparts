<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 24.09.14
 * Time: 08:47
 */

/**
 * Class ModelToolSeoLinkGenerator
 * Klasa odpowiada za masowa generacje seo linkow produktow na podstawie nazwy ( lub innych parametrow )
 */
class ModelToolSeoLinkGenerator extends Model{

    private $_seoLinkTemplate;
    private $_products;
    private $_categories;
    private $_language;
    private $_language_id;
    private $_productsProcessedCount = 0;
    private $_categoryProcessedCount = 0;

    public function setLanguage($language_iso_2)
    {
        $this->_language = $language_iso_2;

        $result = $this->db->query("SELECT * FROM ".DB_PREFIX."language WHERE code = '".$language_iso_2."' ");

        $this->_language_id = $result->row['language_id'];

    }

    public function setSeoLinkTemplate($template)
    {
        $this->_seoLinkTemplate = $template;
    }

    public function getAllProducts()
    {
        $this->load->model('catalog/product');

        $this->_products = $this->model_catalog_product->getProducts();

    }

    public function getAllCategories()
    {
        $this->load->model('catalog/category');

        $this->_categories = $this->model_catalog_category->getCategories();

    }

    public function generateProductsSeoLinks()
    {
        $this->getAllProducts();

        foreach($this->_products as $product)
        {
            $link = $this->generateProductSeoLink($product);

            $this->removeProductSeoLink($product);

            $link = $this->makeLinkUniqe($link);

            $this->saveProductSeoLink($product,$link);
        }

        return true;
    }

    public function generateCategorySeoLinks()
    {
        $this->getAllCategories();

        foreach($this->_categories as $category)
        {
            $link = $this->generateCategorySeoLink($category);

            $this->removeCategorySeoLink($category);

            $link = $this->makeLinkUniqe($link);

            $this->saveCategorySeoLink($category,$link);
        }

        return true;
    }

    public function generateProductSeoLink($product)
    {
            $elements = array();
            $template = $this->_seoLinkTemplate;
            preg_match('|{([a-zA-Z]+)}|',$this->_seoLinkTemplate,$elements);

            foreach($elements as $element)
            {
                $functionName = 'getProduct'.ucfirst($element);

                if(is_callable(array($this,$functionName)))
                {
                    $value = $this->$functionName($product);
                    $template = str_ireplace('{'.$element.'}',$value,$template);
                }




            }

            return $template;
    }


    public function generateCategorySeoLink($product)
    {
        $elements = array();
        $template = $this->_seoLinkTemplate;
        preg_match('|{([a-zA-Z]+)}|',$this->_seoLinkTemplate,$elements);

        foreach($elements as $element)
        {
            $functionName = 'getCategory'.ucfirst($element);

            if(is_callable(array($this,$functionName)))
            {
                $value = $this->$functionName($product);
                $template = str_ireplace('{'.$element.'}',$value,$template);
            }




        }

        return $template;
    }

    public function getProductName($product)
    {
            $descriptions = $this->model_catalog_product->getProductDescriptions($product['product_id']);

            if(isset($descriptions[$this->_language_id]['name']))
            {

                return $this->encodeForUrl($descriptions[$this->_language_id]['name']);
            }

            return false;

    }

    public function getCategoryName($category)
    {
        $descriptions = $this->model_catalog_category->getCategoryDescriptions($category['category_id']);

        if(isset($descriptions[$this->_language_id]['name']))
        {

            return $this->encodeForUrl($descriptions[$this->_language_id]['name']);
        }

        return false;

    }

    public function encodeForUrl($string)
    {


        $accents = array('ü' => 'u', 'Š' => 'S', 'š' => 's', 'Ð' => 'Dj','Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss','à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', 'ƒ' => 'f');

        $string = strtr($string, $accents);
        $string = html_entity_decode($string);
        $string = strtolower($string);
        $string = preg_replace('/[^a-zA-Z0-9\s]/', '', $string);
        $string = preg_replace('{ +}', ' ', $string);
        $string = trim($string);
        $string = str_replace(' ', '-', $string);

        return $string;
    }


    public function removeProductSeoLink($product)
    {
        $this->db->query("DELETE FROM ".DB_PREFIX."url_alias WHERE query = 'product_id=".$product['product_id']."' ");
    }

    public function removeCategorySeoLink($category)
    {
        $this->db->query("DELETE FROM ".DB_PREFIX."url_alias WHERE query = 'category_id=".$category['category_id']."' ");
    }

    public function saveProductSeoLink($product,$seoLink)
    {

        $this->db->query("INSERT INTO ".DB_PREFIX."url_alias SET query = 'product_id=".$product['product_id']."' , keyword = '".$this->db->escape($seoLink)."' , language = '".$this->db->escape($this->_language)."'  ");

        $this->_productsProcessedCount++;
    }

    public function saveCategorySeoLink($category,$seoLink)
    {

        $this->db->query("INSERT INTO ".DB_PREFIX."url_alias SET query = 'category_id=".$category['category_id']."' , keyword = '".$this->db->escape($seoLink)."' , language = '".$this->db->escape($this->_language)."'  ");

        $this->_categoryProcessedCount++;
    }


    public function makeLinkUniqe($link)
    {
        $limit = 1000;
        $original_link = $link;
        $i = 0;
        do
        {
            $result = $this->db->query("SELECT * FROM ".DB_PREFIX."url_alias WHERE keyword = '".$this->db->escape($link)."' ");

            if(empty($result->row))
            {
                break;
            }
            else
            {
                if($link == $original_link)
                {
                    $link .= '_';
                }

                $link .= rand(0,9);
            }

            $i++;

        }
        while($i < $limit);

        return $link;
    }


} 