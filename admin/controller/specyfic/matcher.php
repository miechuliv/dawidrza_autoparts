<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 01.11.13
 * Time: 15:19
 * To change this template use File | Settings | File Templates.
 */

class ControllerSpecyficMatcher extends Controller{

    public function index()
    {
        return false;
        // implementacja funkcji levenstein
        $sql = "DELIMITER $$
CREATE FUNCTION levenshtein( s1 VARCHAR(255), s2 VARCHAR(255) )
RETURNS INT
DETERMINISTIC
BEGIN
DECLARE s1_len, s2_len, i, j, c, c_temp, cost INT;
DECLARE s1_char CHAR;
-- max strlen=255
DECLARE cv0, cv1 VARBINARY(256);
SET s1_len = CHAR_LENGTH(s1), s2_len = CHAR_LENGTH(s2), cv1 = 0x00, j = 1, i = 1, c = 0;
IF s1 = s2 THEN
RETURN 0;
ELSEIF s1_len = 0 THEN
RETURN s2_len;
ELSEIF s2_len = 0 THEN
RETURN s1_len;
ELSE
WHILE j <= s2_len DO
    SET cv1 = CONCAT(cv1, UNHEX(HEX(j))), j = j + 1;
END WHILE;
WHILE i <= s1_len DO
    SET s1_char = SUBSTRING(s1, i, 1), c = i, cv0 = UNHEX(HEX(i)), j = 1;
WHILE j <= s2_len DO
    SET c = c + 1;
IF s1_char = SUBSTRING(s2, j, 1) THEN
SET cost = 0; ELSE SET cost = 1;
END IF;
SET c_temp = CONV(HEX(SUBSTRING(cv1, j, 1)), 16, 10) + cost;
IF c > c_temp THEN SET c = c_temp; END IF;
SET c_temp = CONV(HEX(SUBSTRING(cv1, j+1, 1)), 16, 10) + 1;
IF c > c_temp THEN
SET c = c_temp;
END IF;
SET cv0 = CONCAT(cv0, UNHEX(HEX(c))), j = j + 1;
END WHILE;
SET cv1 = cv0, i = i + 1;
END WHILE;
END IF;
RETURN c;
END$$
DELIMITER ;";

        error_reporting(E_ALL);

ini_set('display_errors', '1');

      //  $this->db->query($sql);

        $old_makes = $this->db->query("SELECT * FROM make_old");

        foreach($old_makes->rows as $row)
        {
              $result = $this->db->query("SELECT *,levenshtein('".$this->db->escape($row['make_name'])."',make_name) as distance FROM make ORDER BY distance ASC LIMIT 1 ");

              if($result->num_rows  > 0)
              {
                  $this->db->query("INSERT INTO make_old_to_new SET make_id_old = '".(int)$row['make_id']."', make_id_new = '".(int)$result->row['make_id']."'   ");
              }

            // stare modele tej starej marki
            $old_models = $this->db->query("SELECT * FROM model_old WHERE make_id = '".(int)$row['make_id']."' ");

            foreach($old_models->rows as $old_model)
            {
                $model_result = $this->db->query("SELECT *,levenshtein('".$this->db->escape($old_model['model_name'])."',model_name) as distance FROM model WHERE make_id = '".(int)$result->row['make_id']."' ORDER BY distance ASC LIMIT 1 ");

                if($model_result->num_rows  > 0)
                {
                    $this->db->query("INSERT INTO model_old_to_new SET model_id_old = '".(int)$old_model['model_id']."', model_id_new = '".(int)$model_result->row['model_id']."'   ");
                }

                $old_types = $this->db->query("SELECT * FROM type_old WHERE model_id = '".(int)$old_model['model_id']."' ");

                foreach($old_types->rows as $old_type)
                {
                    if(!isset($old_type['type_name']) OR !isset($old_type['type_id']))
                    {
                         var_dump($old_type);
                        die();
                    }

                    $type_result = $this->db->query("SELECT *,levenshtein('".$this->db->escape($old_type['type_name'])."',type_name) as distance FROM type WHERE model_id = '".(int)$model_result->row['model_id']."' ORDER BY distance ASC LIMIT 1 ");

                    if($type_result->num_rows  > 0)
                    {
                        $this->db->query("INSERT INTO type_old_to_new SET type_id_old = '".(int)$old_type['type_id']."', type_id_new = '".(int)$type_result->row['type_id']."'   ");
                    }



                }

            }





        }


    }

    public function resolvePTC()
    {
        $this->load->model('tool/cars');


        $ptcs = $this->model_tool_cars->getAllOldCars();

        foreach($ptcs as $ptc)
        {

            if(!$ptc['type_id'])
            {
                 continue;
            }


            $make_new = $this->db->query("SELECT *,levenshtein('".$this->db->escape($ptc['make_name'])."',make_name) as distance FROM make ORDER BY distance ASC LIMIT 1 ");

            $make_new = $make_new->row;



            // jesli year_start = NULL to distance też NULL
            $model_result = $this->db->query("SELECT *,levenshtein('".$this->db->escape($ptc['model_name'])."',model_name) as distance

            , TIMESTAMPDIFF(SECOND, year_start , '".$ptc['year_start']."') as time_diff FROM model WHERE make_id = '".(int)$make_new['make_id']."' ORDER BY distance ASC, time_diff ASC ");

            $model_new = $model_result->row;

            $type_result = $this->db->query("SELECT *,levenshtein('".$this->db->escape($ptc['type_name'])."',type_name) as distance FROM type WHERE model_id = '".(int)$model_new['model_id']."' ORDER BY distance ASC LIMIT 1 ");

            $type_new = $type_result->row;

            $data = array(
                'product_id' => $ptc['product_id'],
                'make_id' => $make_new['make_id'],
                'model_id' => $model_new['model_id'],
                'type_id' => $type_new['type_id'],
            );

            $this->model_tool_cars->productToCarInsert($data);


        }
    }

    /*
     * kombinacja tysiaclecia: trzeba z produktów danego typu odtworzyć zakres rocznikowy tego typu,  mając roczniki mozna rozbic typy na nowe modele podzielone na platfromy( rocznikowo)
     */
    public function getTypesProductsYears()
    {
           $types = $this->db->query("SELECT * FROM type_old ");

            foreach($types->rows as $type)
            {

              /*  $new_type_id = $this->db->query("SELECT * FROM type_old_to_new WHERE type_id_old = '".(int)$type['type_id']."' ");

                if(!isset($new_type_id->row['type_id_old']))
                {
                    continue;
                }

                $new_type_id = $new_type_id->row['type_id_old']; */

                 //$products = $this->db->query("SELECT * FROM product_description pd LEFT JOIN product_to_car ptc ON(pd.product_id=ptc.product_id)  WHERE pd.language_id = '2' AND ptc.type_id = '".(int)$new_type_id."' ");

                $products = $this->db->query("SELECT * FROM product_description pd LEFT JOIN product_to_car ptc ON(pd.product_id=ptc.product_id)  WHERE pd.language_id = '2' AND ptc.type_id = '".(int)$type['type_id']."' ");

                 $type_year_start = false;

                $type_year_stop = false;

                if(empty($products->rows))
                {
                    continue;
                }

                foreach($products->rows as $product)
                {
                     $desc = $product['description'];

                    $dom = new DOMDocument();

                    $dom->loadHTML($desc);

                    // po obrazkach
                    $ps = $dom->getElementsByTagName('p');

                    foreach($ps as $p)
                    {
                        $spans = $p->getElementsByTagName('span');

                        foreach($spans as $key => $span)
                        {



                            if(stripos($span->nodeValue,'Data produkcji:')!==false)
                            {
                                $t = $spans->item($key+1);
                                if(isset($t))
                                {
                                    $products_date = trim($spans->item($key+1)->nodeValue);
                                    $products_date = str_ireplace('onwards','',$products_date);
                                }

                            }


                        }


                    }


                    // prosztszy opis
                    $data = explode('<br>',$desc);


                    foreach($data as $row)
                    {
                        if(strpos($row,'Data produkcji:&nbsp;')!==false)
                        {
                            $products_date = trim(str_ireplace('Data produkcji:&nbsp;','',$row));
                        }

                    }


                    try{
                        if(isset($products_date) AND strlen($products_date) > 3 AND strlen($products_date) < 16)
                        {
                            $year_start = false;
                            $year_stop = false;

                            if($product['product_id'] < 561)
                            {
                                $products_date = str_ireplace(array('amp','gt','&',';'),'',$products_date);
                                $t = explode('-',$products_date);

                                if(count($t) < 2)
                                {
                                    $t = explode('>',$products_date);
                                }

                                $year_start = new DateTime(trim(str_ireplace('&nbsp;','',$t[0])).'-01-01');

                             //   echo 'start';
                            //    var_dump($year_start->format('Y-m-d'));

                                if(isset($t[1]) AND strlen($t[1]) > 3)
                                {
                                    $year_stop = new DateTime(trim(str_ireplace('&nbsp;','',$t[1])).'-01-01');

                               //     echo 'stop';
                           //         var_dump($year_stop->format('Y-m-d'));
                                }
                            }
                            else
                            {
                                $products_date = str_ireplace(array('amp','gt','&',';'),'',$products_date);

                                $t = explode('-',$products_date);

                                if(count($t) < 2)
                                {
                                    $t = explode('>',$products_date);
                                }



                                if(strpos($t[0],'.'))
                                {
                                    $year_start = new DateTime('01.'.trim(str_ireplace('&gt;','',$t[0])));
                                }
                                else
                                {
                                    $year_start = new DateTime('01.01.'.trim(str_ireplace('&gt;','',$t[0])));
                                }


                             //   echo 'start';
                            //    var_dump($year_start->format('Y-m-d'));

                                if(isset($t[1]) AND strlen($t[1]) > 3)
                                {
                                    if(strpos($t[1],'.'))
                                    {
                                        $year_stop = new DateTime('01.'.trim(str_ireplace('&gt;','',$t[1])));
                                    }
                                    else
                                    {
                                        $year_stop = new DateTime('01.01.'.trim(str_ireplace('&gt;','',$t[1])));
                                    }
                                 //   echo 'stop';
                                //    var_dump($year_stop->format('Y-m-d'));
                                }
                            }

                            if($year_start instanceof DateTime)
                            {
                                 if(!($type_year_start instanceof DateTime))
                                 {
                                      $type_year_start = clone $year_start;
                                 }
                                 else
                                 {
                                     if($year_start < $type_year_start)
                                     {
                                         $type_year_start = clone $year_start;
                                     }
                                 }
                            }
                            else
                            {
                                 throw new Exception('dfgdfg');
                            }


                            if($year_stop instanceof DateTime)
                            {
                                if(!($type_year_stop instanceof DateTime))
                                {
                                    $type_year_stop = clone $year_stop;
                                }
                                else
                                {
                                    if($year_stop > $type_year_stop)
                                    {
                                        $type_year_stop = clone $year_stop;
                                    }
                                }
                            }




                        }
                    }
                    catch(Exception $e)
                    {
                       continue;
                    }

                }

           /*     echo 'typ start ';
                var_dump($type_year_start->format('Y-m-d'));

                echo 'typ stop ';
                var_dump($type_year_stop->format('Y-m-d')); */

                if(($type_year_start instanceof DateTime))
                {
                    $this->db->query("UPDATE type_old SET year_start = '".$type_year_start->format('Y-m-d')."' WHERE type_id = '".(int)$type['type_id']."' ");



                }

                if($type_year_stop instanceof DateTime)
                {
                    $this->db->query("UPDATE type_old SET  year_end = '".$type_year_stop->format('Y-m-d')."' WHERE type_id = '".(int)$type['type_id']."' ");
                }


             /*   else
                {
                    echo 'start:';
                     var_dump($type_year_start);
                    echo 'stop:';
                     var_dump($type_year_stop);

                } */







            }
    }

    public function matchCars()
    {
        return false;
            $cars = $this->db->query("SELECT * FROM product_to_car");

            foreach($cars->rows as $car)
            {
                  $new_make_row = $this->db->query("SELECT * FROM make_old_to_new WHERE make_id_old = '".(int)$car['make_id']."' ");

                if(!isset($new_make_row->row['make_id_new']))
                {
                    continue;
                }

                  $new_make_id = $new_make_row->row['make_id_new'];

                $new_model_row = $this->db->query("SELECT * FROM model_old_to_new WHERE model_id_old = '".(int)$car['model_id']."' ");

                if(!isset($new_model_row->row['model_id_new']))
                {
                    continue;
                }
                $new_model_id = $new_model_row->row['model_id_new'];

                $new_type_id = 0;

                if($car['type_id'])
                {
                    $new_type_row = $this->db->query("SELECT * FROM type_old_to_new WHERE type_id_old = '".(int)$car['type_id']."' ");

                    if(isset($new_type_row->row['type_id_new']))
                    {
                        $new_type_id = $new_type_row->row['type_id_new'];
                    }

                }

                $this->db->query("UPDATE product_to_car SET make_id = '".(int)$new_make_id."', model_id = '".(int)$new_model_id."', type_id = '".(int)$new_type_id."' WHERE product_to_car_id = '".(int)$car['product_to_car_id']."' ");
            }
    }

    public function getCodes()
    {
        // na 2519 zaczynaja sie te srubki i inne pierdoly ,  2  -polski

            $products = $this->db->query("SELECT * FROM product_description WHERE language_id = '2' AND product_id  < 2519 ");

            foreach($products->rows as $product)
            {
                 $desc = $product['description'];



                if($product['product_id'] < 561)
                {
                    $this->simplePage($product);
                }
                else
                {
                    $this->richPage($product);
                }
            }

    }

    private function simplePage($product)
    {
        $desc = $product['description'];

        $dom = new DOMDocument();

        $dom->loadHTML($desc);

        $uls = $dom->getElementsByTagName('ul');

        $normal_numbers = array();

        $alt_numbers = array();

        $engine_codes = array();

        foreach($uls as $key => $ul)
        {
                if($key == 0)
                {
                    $lis = $ul->getElementsByTagName('li');

                    if(count($lis)>1)
                    {
                        foreach($lis as $li)
                        {
                            $codes =  explode(' ',$li->nodeValue);

                            foreach($codes as $code)
                            {
                                $normal_numbers[] = trim($code);
                            }
                        }
                    }
                    else
                    {
                        $normal_numbers[] = trim($ul->nodeValue);
                    }


                }

            if($key == 1)
            {
                $lis = $ul->getElementsByTagName('li');

                if(count($lis)>1)
                {
                foreach($lis as $li)
                {
                    $codes =  explode(' ',$li->nodeValue);

                    foreach($codes as $code)
                    {
                        $alt_numbers[] = trim($code);
                    }
                }
                }
                else
                {
                    $alt_numbers[] = trim($ul->nodeValue);
                }

            }

            if($key == 2)
            {
                $lis = $ul->getElementsByTagName('li');

                if(count($lis)>1)
                {
                foreach($lis as $li)
                {
                    $codes =  explode(' ',$li->nodeValue);

                    foreach($codes as $code)
                    {
                        $engine_codes[] = trim($code);
                    }
                }
                }
                else
                {
                    $engine_codes[] = trim($ul->nodeValue);
                }

            }
        }

        // wylapanie reszty poprzez rozbicie brów

        $data = explode('<br>',$desc);

        $products_date = false;
        $capacity = false;
        $engine_power = false;
        $engine_power_type = false;
        $number_of_cylinders = false;

        foreach($data as $row)
        {
                if(strpos($row,'Data produkcji:&nbsp;')!==false)
                {
                        $products_date = trim(str_ireplace('Data produkcji:&nbsp;','',$row));
                }

            if(strpos($row,'Moc:&nbsp;')!==false)
            {
                $t = trim(str_ireplace('Moc:&nbsp;','',$row));

                $t = explode('&nbsp;',$t);

                $engine_power = trim($t[0]);

                if(isset($t[1]))
                {
                    $engine_power_type = trim($t[1]);
                }

            }

            if(strpos($row,'Pojemność silnika (l/ccm):&nbsp;')!==false)
            {
                $capacity = trim(str_ireplace('Pojemność silnika (l/ccm):&nbsp;','',$row));
            }

            if(strpos($row,'Liczba cylindrów:&nbsp;')!==false)
            {
                $number_of_cylinders = trim(str_ireplace('Liczba cylindrów:&nbsp;','',$row));
            }
        }

        $data = array(
            'number_of_cylinders' => $number_of_cylinders,
            'normal_numbers' => $normal_numbers,
            'alt_numbers' => $alt_numbers,
            'engine_codes' => $engine_codes,
            'products_date' => $products_date,
            'capacity' => $capacity,
            'engine_power' => $engine_power,
            'engine_power_type' => $engine_power_type,

        );




        $this->updateProduct($product['product_id'],$data);

        $this->saveCodes($product['product_id'],$data);


    }

    private function richPage($product){

        $desc = $product['description'];




        $dom = new DOMDocument();

        $dom->loadHTML($desc);

        $ps = $dom->getElementsByTagName('p');


        $normal_numbers = array();
        $alt_numbers = array();

        $car_type = false;
        $capacity = false;
        $engine_power = false;
        $engine_type = false;
        $engine_power_type = false;
        $export_market = false;
        $products_date = false;
        $special_cases = false;
        $number_of_cylinders = false;




        foreach($ps as $p)
        {
            $spans = $p->getElementsByTagName('span');

            foreach($spans as $key => $span)
            {
                 if(stripos($span->nodeValue,'Model samochodu:')!==false)
                 {
                     $t = $spans->item($key+1);
                     if(isset($t))
                     {
                         $car_type = trim($spans->item($key+1)->nodeValue);
                     }

                 }

                if(stripos($span->nodeValue,'Pojemno')!==false)
                {
                    $t = $spans->item($key+1);
                    if(isset($t))
                    {
                        $capacity = trim($spans->item($key+1)->nodeValue);
                    }

                }

                if(stripos($span->nodeValue,'Moc silnika w KW:')!==false)
                {
                    $t = $spans->item($key+1);
                    if(isset($t))
                    {
                        $engine_power = trim($spans->item($key+1)->nodeValue);
                    }

                }

                $engine_power_type = 'KW';

                if(stripos($span->nodeValue,'Model silnika:')!==false)
                {
                    $t = $spans->item($key+1);
                    if(isset($t))
                    {
                        $engine_type = trim($spans->item($key+1)->nodeValue);
                    }

                }

                if(stripos($span->nodeValue,'Rynek zbytu:')!==false)
                {
                    $t = $spans->item($key+1);
                    if(isset($t))
                    {
                        $export_market = trim($spans->item($key+1)->nodeValue);
                    }

                }

                if(stripos($span->nodeValue,'Data produkcji:')!==false)
                {
                    $t = $spans->item($key+1);
                    if(isset($t))
                    {
                        $products_date = trim($spans->item($key+1)->nodeValue);
                        $products_date = str_ireplace('onwards','',$products_date);
                    }

                }

                if(stripos($span->nodeValue,'Specjalne zastosowanie:')!==false)
                {
                    $t = $spans->item($key+1);
                    if(isset($t))
                    {
                        $special_cases = trim($spans->item($key+1)->nodeValue);
                    }

                }

                if(stripos($span->nodeValue,'Liczba wtryskiwaczy:')!==false)
                {
                    $t = $spans->item($key+1);
                    if(isset($t))
                    {
                        $number_of_cylinders = trim($spans->item($key+1)->nodeValue);
                    }

                }

                $img = $span->getElementsByTagName('img');

                $t =$img->item(0);

                if(isset($t) AND $img->item(0)->getAttribute('src') == 'http://demo.stronazen.pl/gackade/image/ikonki/diesel-injector-number.jpg')
                {

                    $t = $spans->item($key+1);
                    if(isset($t))
                    {
                        $normal_numbers[] = trim($spans->item($key+1)->nodeValue);
                    }

                }

                if(isset($t) AND $img->item(0)->getAttribute('src') == 'http://demo.stronazen.pl/gackade/image/ikonki/distrubutor.jpg')
                {
                    $t = $spans->item($key+1);
                    if(isset($t))
                    {
                        $normal_numbers[] = trim($spans->item($key+1)->nodeValue);
                    }

                }

                if(isset($t) AND $img->item(0)->getAttribute('src') == 'http://demo.stronazen.pl/gackade/image/ikonki/exchange-number.jpg')
                {

                    $t = $spans->item($key+1);
                    if(isset($t))
                    {
                        $t = trim($spans->item($key+1)->nodeValue);

                        $c = explode(' ',$t);

                        foreach($c as $code)
                        {
                            $alt_numbers[] = $code;
                        }
                    }


                }





            }


        }

        $data = array(
         'normal_numbers' =>   $normal_numbers,
        'alt_numbers' => $alt_numbers,

        'car_type' => $car_type,
        'capacity' => $capacity,
        'engine_power' => $engine_power,
        'engine_type' => $engine_type,
        'engine_power_type' => $engine_power_type,
        'export_market' => $export_market,
        'products_date' => $products_date,
        'special_cases' => $special_cases,
        'number_of_cylinders' => $number_of_cylinders,
        );


        $this->updateProduct($product['product_id'],$data);

        $this->saveCodes($product['product_id'],$data);

    }

    private function updateProduct($product_id,$data)
    {

        $sql = "UPDATE product SET ";

        $no_update = true;

        if(isset($data['car_type']) AND $data['car_type'])
        {
            if(!$no_update)
            {
                $sql .= ' , ';
            }

             $no_update = false;
             $sql.= " car_type= '".$this->db->escape($data['car_type'])."'  ";
        }

        if(isset($data['capacity']) AND $data['capacity'])
        {
            if(!$no_update)
            {
                $sql .= ' , ';
            }

            $no_update = false;
            $sql.= " capacity= '".$this->db->escape($data['capacity'])."'  ";
        }

        if(isset($data['engine_power']) AND $data['engine_power'])
        {
            if(!$no_update)
            {
                $sql .= ' , ';
            }

            $no_update = false;
            $sql.= " engine_power= '".$this->db->escape($data['engine_power'])."'  ";
        }

        if(isset($data['engine_type']) AND $data['engine_type'])
        {
            if(!$no_update)
            {
                $sql .= ' , ';
            }

            $no_update = false;
            $sql.= " engine_type= '".$this->db->escape($data['engine_type'])."'  ";
        }

        if(isset($data['engine_power_type']) AND $data['engine_power_type'])
        {
            if(!$no_update)
            {
                $sql .= ' , ';
            }

            $no_update = false;
            $sql.= " engine_power_type= '".$this->db->escape($data['engine_power_type'])."'  ";
        }

        if(isset($data['export_market']) AND $data['export_market'])
        {
            if(!$no_update)
            {
                $sql .= ' , ';
            }

            $no_update = false;
            $sql.= " export_market= '".$this->db->escape($data['export_market'])."'  ";
        }


        if(isset($data['products_date']) AND $data['products_date'])
        {
            if(!$no_update)
            {
                $sql .= ' , ';
            }

            $no_update = false;
            $sql.= " products_date= '".$this->db->escape($data['products_date'])."'  ";
        }

        if(isset($data['special_cases']) AND $data['special_cases'])
        {
            if(!$no_update)
            {
                $sql .= ' , ';
            }

            $no_update = false;
            $sql.= " special_cases = '".$this->db->escape($data['special_cases'])."'  ";
        }

        if(isset($data['number_of_cylinders']) AND $data['number_of_cylinders'])
        {
            if(!$no_update)
            {
                $sql .= ' , ';
            }

            $no_update = false;
            $sql.= " number_of_cylinders = '".$this->db->escape($data['number_of_cylinders'])."'  ";
        }

        if(!$no_update)
        {
            $sql .=" WHERE product_id = '".(int)$product_id."' ";
            $this->db->query($sql);
        }

    }

    private function saveCodes($product_id,$data)
    {
            if(isset($data['normal_numbers']) AND !empty($data['normal_numbers']))
            {
                 foreach($data['normal_numbers'] as $code)
                 {
                      $this->db->query(" INSERT INTO product_normal_code SET product_id = '".(int)$product_id."', code = '".$this->db->escape($code)."' ");
                 }
            }

        if(isset($data['alt_numbers']) AND !empty($data['alt_numbers']))
        {
            foreach($data['alt_numbers'] as $code)
            {
                $this->db->query(" INSERT INTO product_alt_code SET product_id = '".(int)$product_id."', code = '".$this->db->escape($code)."' ");
            }
        }

        if(isset($data['engine_codes']) AND !empty($data['engine_codes']))
        {
            foreach($data['engine_codes'] as $code)
            {
                $this->db->query(" INSERT INTO product_engine_code SET product_id = '".(int)$product_id."', code = '".$this->db->escape($code)."' ");
            }
        }
    }


    public function regenerateDescription()
    {

        return false;
       // return false;
        $products = $this->db->query("SELECT * FROM product_description_old pd LEFT JOIN product p ON(pd.product_id=p.product_id)  WHERE  pd.product_id  < 2519 ORDER BY pd.product_id DESC ");

        $this->load->model('catalog/category');

        $this->load->model('catalog/product');

        $this->load->model('catalog/manufacturer');

        $this->load->model('tool/cars');

        // tablica nazw kategorii które można użyć
        // 2 - polski, 3 -niemiecki
        // 59 - pompy, 60 - injektory
        $category_words = array(
            2 => array(
                60 => array(
                    'Wtryskiwacz',
                    'Wtryskiwacze',
                    'Wtryski',
                    'Injektory',
                    'Injektory'
                ),
                59 => array(
                    'Pompa paliwowa',
                    'Pompy paliwowe',

                )
            ),
            3 => array(
                60 => array(
                    'Einspritzdüse',
                    'Einspritzdüsen',

                    'Injektor',
                    'Injektoren'
                ),
                59 => array(
                    'Kraftstoffpumpe',
                    'Kraftstoffpumpen',

                )
            )
        );

        $states = array(
            2 => array(
                'new' => array(
                    'Nowy',
                    'Jak nowy'
                ),
                'regenerated' => 'Regenerowany',
                'for_regeneration' => 'Regneracja'

            ),

            3 => array(
                'new' => array(
                    'Neu',
                    'Wie neu'
                ),
                'regenerated' => 'Generalüberholt',
                'for_regeneration' => 'instand setzen',

            )


        );



        foreach($products->rows as $product)
        {
                $language_id = $product['language_id'];

                $categories = $this->model_catalog_category->getCategoriesByProductId($product['product_id']);

                $category =  array_shift($categories);



            if(!empty($category))
            {
                $category_id = $category['category_id'];
            }
            else
            {
                $category_id = false;
            }


                $manufacturer = $this->model_catalog_manufacturer->getManufacturer($product['manufacturer_id']);


                if(!empty($manufacturer))
                {
                    $manufacturer_name = $manufacturer['name'];
                }
                else
                {
                    $manufacturer_name = false;
                }

                $cars = $this->model_tool_cars->getAllCarsByProductId($product['product_id'],true);

                $car = array_shift($cars);

                if(!empty($car))
                {
                    $make = $car['make_name'];
                    $model = $car['model_name'].' '.$car['platform'];
                    $model_dry = $car['model_name'];
                    $type = $car['type_name'];
                    $platform = $car['platform'];
                    $capacity = $car['capacity'];
                    $power = $car['kw'].' '.$car['ps'];
                    $year = $car['year_start'].' '.$car['year_stop'];

                }
                else
                {
                    $make = false;
                    $model = false;
                    $type = false;
                }

            // numer
            $number = $this->db->query("SELECT * FROM product_normal_code WHERE product_id = '".(int)$product['product_id']."' LIMIT 1");

            if(!empty($number->row))
            {
                 $number = $number->row['code'];
            }
            else
            {
                 $number = false;
            }

            $state_name = false;

            if(isset($states[$language_id][$product['type']]))
            {
                $state_name = $states[$language_id][$product['type']];

                if(is_array($state_name))
                {
                     $state_name = $state_name[rand(0,count($state_name)-1)];
                }
            }

            $category_name = false;

            if(isset($category_words[$language_id][$category_id]))
            {
                $a = $category_words[$language_id][$category_id];

                $max = count($a);

                $category_name = $a[rand(0,count($a)-1)];
            }

            $title = '';

            if($state_name)
            {
                 $title .=  $state_name.' ';
            }

            if($category_name)
            {
                $title .=  $category_name.' ';
            }

            if($manufacturer_name)
            {
                $title .=  $manufacturer_name.' ';
            }

            if($number)
            {
                $title .=  $number.' ';
            }

            if($make)
            {
                if($language_id == 2)
                {
                    $title .=  'do '.$make.' ';
                }
                elseif($language_id == 3)
                {
                    $title .=  'fuer '.$make.' ';
                }

            }

            if($model)
            {
                $title .=  $model.' ';
            }

            if($type)
            {
                $title .=  $type.' ';
            }


            // keyword

            $keyword1  = $title;
            $keyword2 = '';
            $keyword3 = '';
            $keyword4 = '';
            $keyword5 = '';

            // keyword 2
            if($category_name)
            {
                $keyword2 .=  $category_name.' ';
            }

            if($make)
            {
                if($language_id == 2)
                {
                    $keyword2 .=  'do '.$make.' ';
                }
                elseif($language_id == 3)
                {
                    $keyword2 .=  'fuer '.$make.' ';
                }

            }

            if($model)
            {
                $keyword2 .=  $model.' ';
            }

            if($type)
            {
                $keyword2 .=  $type.' ';
            }

             // keyword 3
            if($state_name)
            {
                $keyword3 .=  $state_name.' ';
                $keyword5 .=  $state_name.' ';
            }

            if($category_name)
            {
                $keyword3 .=  $category_name.' ';
                $keyword4 .= $category_name.' ';
                $keyword5 .= $category_name.' ';
            }

            if($manufacturer_name)
            {
                $keyword3 .=  $manufacturer_name.' ';
                $keyword4 .=  $manufacturer_name.' ';
                $keyword5 .=  $manufacturer_name.' ';
            }

            if($number)
            {
                $keyword3 .=  $number.' ';
                $keyword4 .=  $number.' ';
                $keyword5 .=  $number.' ';
            }

            if($language_id == 2)
            {
                $keyword5 .=  'Sklep internetowy ';
            }
            elseif($language_id == 3)
            {
                $keyword5 .=  'Online shop ';
            }

            $keyword = implode(',',array(
                $keyword1,
                $keyword2,
                $keyword3,
                $keyword4,
                $keyword5
            ));



            if($language_id == 2)
            {
                 $meta_desc = $title.' – Gwarancja bez limitu kilometrów – Darmowa i szybka wysyłka – Fachowe doradztwo w sklepie internetowym Regeneo.pl – Zapraszamy ';
            }
            if($language_id == 3)
            {
                $meta_desc = $title.' Garantie ohne Laufleistungsbegrenzung – Schneller und kostenloser Versand – Kompetente Beratung durch unser Service Team und grandiose Preise im Diesel Land Online Shop ';
            }


            // cars table

            if($language_id == 2)
            {
                 $make_text = 'Marka';
                $model_text = 'Model';
                $type_text = 'Typ';
                $platform_text = 'Platforma';
                $capacity_text = 'Pojemność';
                $power_text = 'Moc';
                $year_text = 'Rocznik produkcji';
            }
            elseif($language_id == 3)
            {
                $make_text = 'Marke';
                $model_text = 'Modell';
                $type_text = 'Typ';
                $platform_text = 'Plattform';
                $capacity_text = 'Kapazität';
                $power_text = 'Macht';
                $year_text = 'Baujahr';
            }

            // @todo tu ordzielić opis tabeli wg języków

            $t = '';

            if(!empty($cars))
            {
                $t =  '<table class="cars-related" >
             <tr>
              <td><span class="cars-title">'.$make_text.'</span></td>
              <td><span class="cars-title">'.$model_text.'</span></td>

              <td><span class="cars-title">'.$type_text.'</span></td>

              <td><span class="cars-title">'.$platform_text.'</span></td>
              <td><span class="cars-title">'.$capacity_text.'</span></td>
              <td><span class="cars-title">'.$power_text.'</span></td>
              <td><span class="cars-title">'.$year_text.'</span></td>
              </tr>';
                foreach($cars as $car){
                    $t.=  '<tr>
              <td><span class="car-value">'.$car["make_name"].'</span></td>
             <td><span class="car-value">'.$car["model_name"].'</span></td>


              <td><span class="car-value">'.$car["type_name"].'</span></td>
              <td><span class="car-value">'.$car["platform"].'</span></td>
              <td><span class="car-value">'.$car["capacity"].' ccm</span></td>
              <td><span class="car-value">'.$car["kw"].' kw '.$car["ps"].' ps </span></td>
              <td><span class="car-value">'.$car["year_start"].' - '.$car["year_stop"].'</span></td>



              </tr>';
                }
            }


        $t .= '</table>';


        // wszelkie numery
        $normal_numbers = $this->db->query("SELECT * FROM product_normal_code WHERE product_id = '".(int)$product['product_id']."'");
            $alt_numbers = $this->db->query("SELECT * FROM product_alt_code WHERE product_id = '".(int)$product['product_id']."'");
            $engine_numbers = $this->db->query("SELECT * FROM product_engine_code WHERE product_id = '".(int)$product['product_id']."'");

            if($language_id == 2)
            {
                $normal_numbers_text = 'Numery części';
                $alt_numbers_text = 'Alternatywne kody producenta';
                $engine_numbers_text = 'Kody silnika';
                $cylinders_text = 'Liczba cylindrów';

            }
            elseif($language_id == 3)
            {
                $normal_numbers_text = 'Teilenummern';
                $alt_numbers_text = 'Alternative Hersteller-Codes';
                $engine_numbers_text = 'Motor Codes';
                $cylinders_text = 'Anzahl der Zylinder';

            }

        $d = '<div class="desc-params">';

            if(!(empty($normal_numbers))){
                $d .= '<span class="normal-text">'.$normal_numbers_text.'</span>';
                $d .= '<ul class="normal-numbers">';
                    foreach($normal_numbers->rows as $row)
                    {
                        $d .= '<li>'.$row['code'].'</li>';
                    }


                $d .= '</ul>';
            }

            if(!(empty($alt_numbers))){
                $d .= '<span class="alt-text">'.$alt_numbers_text.'</span>';
                $d .= '<ul class="alt-numbers">';
                foreach($alt_numbers->rows as $row)
                {
                    $d .= '<li>'.$row['code'].'</li>';
                }


                $d .= '</ul>';
            }

            if(!(empty($engine_numbers))){
                $d .= '<span class="engine-text">'.$engine_numbers_text.'</span>';
                $d .= '<ul class="engine-numbers">';
                foreach($engine_numbers->rows as $row)
                {
                    $d .= '<li>'.$row['code'].'</li>';
                }


                $d .= '</ul>';
            }

            $d .= '<span class="cylinders-text">'.$cylinders_text.'</span>';
            $d .= '<span class="cylinders-value"> '.$product['number_of_cylinders'].'</span>';



        $d.= '</div>';

        $description = $t.' '.$d;


        $sql = "UPDATE product_description SET name = '".$this->db->escape($title)."',
         description = '".$this->db->escape($description)."',
         meta_description = '".$this->db->escape($meta_desc)."',
         meta_keyword = '".$this->db->escape($keyword)."'
         WHERE product_id = '".(int)$product['product_id']."' AND
         language_id = '".(int)$language_id."'

         ";

            $this->db->query($sql);

        }
    }


    public function relateId()
    {
        return false;
        $products = $this->db->query("SELECT * FROM product WHERE product_id < 2519");

        $this->load->model('catalog/product');
        $this->load->model('tool/cars');

        foreach($products->rows as $product_info)
        {

            $new_id = false;
            $to_regenerate_id = false;
            $type= false;

            if(isset($product_info['type']) AND isset($product_info['regenerate_or_new_id'])){

                if($product_info['type']=='new' AND $product_info['regenerate_or_new_id']!=NULL)
                {
                    // alt link
                    $to_regenerate_id = $product_info['regenerate_or_new_id'];
                    $new_id = $product_info['product_id'];
                    $type = 'new';

                    // kopia tego produktu w wersji regenerowanej

                    $id = $this->model_catalog_product->copyProduct($product_info['product_id']);

                    // wszelkie numery
                    $normal_numbers = $this->db->query("SELECT * FROM product_normal_code WHERE product_id = '".(int)$product_info['product_id']."'");

                    foreach($normal_numbers->rows as $row)
                    {
                        $this->db->query("INSERT INTO product_normal_code SET product_id = '".(int)$id."', code = '".$row['code']."' ");
                    }
                    $alt_numbers = $this->db->query("SELECT * FROM product_alt_code WHERE product_id = '".(int)$product_info['product_id']."'");
                    foreach($alt_numbers->rows as $row)
                    {
                        $this->db->query("INSERT INTO product_alt_code SET product_id = '".(int)$id."', code = '".$row['code']."' ");
                    }

                    $engine_numbers = $this->db->query("SELECT * FROM product_engine_code WHERE product_id = '".(int)$product_info['product_id']."' ");
                    foreach($engine_numbers->rows as $row)
                    {
                        $this->db->query("INSERT INTO product_engine_code SET product_id = '".(int)$id."', code = '".$row['code']."' ");
                    }

                    $cars = $this->model_tool_cars->getAllCarsByProductId($product_info['product_id'],true);

                    foreach($cars as $row)
                    {
                        $this->db->query("INSERT INTO product_to_car SET product_id = '".(int)$id."', make_id = '".$row['make_id']."', model_id = '".$row['model_id']."', type_id = '".$row['type_id']."' ");
                    }



                    $this->db->query("UPDATE product SET new_id = '".(int)$new_id."', to_regenerate_id = '".(int)$to_regenerate_id."', regenerated_id = '".(int)$id."', type= 'regenerated' WHERE product_id = '".(int)$id."' ");

                    $this->session->data['regenerated_id'] = $id;

                }

                if($product_info['type']=='regenerated')
                {
                    // wyjmij z db nowy odpowiednik
                    // alt link
                    $result = $this->db->query("SELECT * FROM product WHERE regenerate_or_new_id = '".(int)$product_info['product_id']."' ");

                    if(isset($result->row['regenerate_or_new_id']))
                    {
                        $new_id = $result->row['product_id'];
                        $to_regenerate_id = $product_info['product_id'];
                        $type = 'for_regeneration';
                    }

                }

            }

            $regenerated_id = false;

            if(isset($this->session->data['regenerated_id']))
            {
                 $regenerated_id = $this->session->data['regenerated_id'];
            }

            $this->db->query("UPDATE product SET new_id = '".(int)$new_id."', to_regenerate_id = '".(int)$to_regenerate_id."', regenerated_id = '".(int)$regenerated_id."', type= '".$type."' WHERE product_id = '".(int)$product_info['product_id']."' ");





        }
    }



}