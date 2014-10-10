<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 22.11.13
 * Time: 11:41
 * To change this template use File | Settings | File Templates.
 */

class ControllerCommonOneSlashSeo extends Controller{

    public function index() {
        // Add rewrite to url class
        if ($this->config->get('config_seo_url')) {
            $this->url->addRewrite($this);
        }


        // Decode URL
        if (isset($this->request->get['_route_'])) {


            $this->oneSlashRoute($this->request->get['_route_']);

            $parts = explode('/', $this->request->get['_route_']);

            foreach ($parts as $part) {


                $part = trim($part,'&');

                // szukamy wg jezyka, zalozenie ze jeden jezy na stronie
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "' AND language='".$this->config->get('config_language')."'   ");


                // jesli nie znajdziemy dla tego języka
                if(!$query->num_rows)
                {
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'   ");
                }



                if ($query->num_rows) {


                    // miechu extra params in url
                    if(strpos($query->row['query'],'&')!==false)
                    {
                        $params = explode('&', $query->row['query']);
                        $url = explode('=', array_shift($params));

                    }
                    else
                    {
                        $url = explode('=', $query->row['query']);

                    }



                    if(!empty($params))
                    {
                        foreach($params as $param)
                        {
                            $tmp = explode('=',$param);

                            if(isset($tmp[0]) AND isset($tmp[1]))
                            {
                                $this->request->get[$tmp[0]] = $tmp[1];
                            }

                        }
                    }


                    if ($url[0] == 'product_id') {
                        $this->request->get['product_id'] = $url[1];
                    }

                    if ($url[0] == 'category_id') {
                        if (!isset($this->request->get['path'])) {
                            $this->request->get['path'] = $url[1];
                        } else {
                            $this->request->get['path'] .= '_' . $url[1];
                        }
                    }

                    if ($url[0] == 'manufacturer_id') {
                        $this->request->get['manufacturer_id'] = $url[1];
                    }

                    if ($url[0] == 'information_id') {
                        $this->request->get['information_id'] = $url[1];
                    }


                } else {
                    $this->request->get['route'] = 'error/not_found';
                }
            }

            if (isset($this->request->get['product_id'])) {
                $this->request->get['route'] = 'product/product';
            } elseif (isset($this->request->get['path'])) {
                $this->request->get['route'] = 'product/category';
            } elseif (isset($this->request->get['manufacturer_id'])) {
                $this->request->get['route'] = 'product/manufacturer/info';
            } elseif (isset($this->request->get['information_id'])) {
                $this->request->get['route'] = 'information/information';
            }

            if (isset($this->request->get['route'])) {
                return $this->forward($this->request->get['route']);
            }
        }
    }

    /*
     * pozwala na przepisywanie linków w stylu:
     * kategoria-kategoria-produkt_cxxx_cxxxx_pxxx
     */
    public function oneSlashRoute($route)
    {
        $words = explode('_',$route);

        /*
         * wyszukujemy wsród słów w linku czy są jakieś kategorie albo produkt
         */
        foreach($words as $word)
        {
            // kategoria
            if(preg_match('/^c+[0-9]*$/',$word)!=false)
            {

                $this->request->get['route'] = 'product/category';

                if(!isset($this->request->get['path']) OR $this->request->get['path'] == '' )
                {
                    $this->request->get['path'] = str_ireplace('c','',$word);
                }
                else
                {
                    $this->request->get['path'] .= '_'.str_ireplace('c','',$word);
                }
            }

            // produckt
            if(preg_match('/^p+[0-9]*$/',$word)!=false)
            {
                $this->request->get['route'] = 'product/product';
                $this->request->get['product_id'] = str_ireplace('p','',$word);;

            }

        }


        if (isset($this->request->get['route'])) {
            return $this->forward($this->request->get['route']);
        }
    }

    public function rewrite($link) {



        $url_info = parse_url(str_replace('&amp;', '&', $link));

        $url = '';

        $data = array();

        parse_str($url_info['query'], $data);


        $url_ending = '';




        foreach ($data as $key => $value) {
            if (isset($data['route'])) {

                if ($key == 'path') {

                    $categories = explode('_', $value);





                    foreach ($categories as $category) {

                        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "' AND language='".$this->config->get('config_language')."' ");

                        if(!$query->num_rows)
                        {
                            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "'");
                        }

                        if ($query->num_rows) {
                            $url .= '-' . $query->row['keyword'];
                            $url_ending .= '_c' . $category;
                        }
                    }

                    unset($data[$key]);
                }


                if ((($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id')) {



                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "' AND language='".$this->config->get('config_language')."' ");

                    if(!$query->num_rows)
                    {
                        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
                    }

                    if ($query->num_rows) {
                        $url .= '/' . $query->row['keyword'];

                        unset($data[$key]);
                    }

                    /*
                     * przepysiwanie dla produktu
                     */
                } elseif($data['route'] == 'product/product' && $key == 'product_id')
                {

                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "' AND language='".$this->config->get('config_language')."' ");


                    if(!$query->num_rows)
                    {
                        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
                    }

                    if ($query->num_rows) {
                        $url .= '-' . $query->row['keyword'];
                        $url_ending .= '_p' . $value;

                        unset($data[$key]);
                    }


                }



            }
        }




        if ($url) {
            unset($data['route']);

            $query = '';

            // if ($data AND $url_ending == '') {
            if ($data) {
                foreach ($data as $key => $value) {
                    if(is_array($value))
                    {
                        foreach($value as $skey => $sval)
                        {
                            $query .= '&' . $key . '['.$skey.']=' . $sval;
                        }
                    }
                    else
                    {
                        $query .= '&' . $key . '=' . $value;
                    }

                }

                if ($query) {
                    $query = '?' . trim($query, '&');
                }
            }



            // dodajemy pierwszego slasha jesli jescze go nie ma
            if($url[0]!='/')
            {
                $url[0] = '/';
            }


            return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url .$url_ending . $query;
        } else {
            return $link;
        }
    }

}