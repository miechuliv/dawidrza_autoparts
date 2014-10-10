<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Robert
 * Date: 18.06.13
 * Time: 15:06
 * To change this template use File | Settings | File Templates.
 */

class ModelToolCars extends Model{

    public function getAllMake()
    {
        $results = $this->db->query("SELECT * FROM make");

        return $results;
    }

    public function getAllModels()
    {
        $results = $this->db->query("SELECT * FROM model");

        return $results;
    }

    public function getAllTypes()
    {
        $results = $this->db->query("SELECT * FROM type");

        return $results;
    }

    public function editMake($data)
    {
           $this->db->query("UPDATE make SET make_name='".$data['make_name']."' WHERE make_id='".(int)$data['make_id']."' ");
    }

    public function editModel($data)
    {
        $this->db->query("UPDATE model SET model_name='".$data['model_name']."' WHERE model_id='".(int)$data['model_id']."' ");
    }

    public function editType($data)
    {

    }
    /*
     *  @returns tablica marek z modelami i typami
     */


    public function getAll()
    {
        $results = $this->db->query("SELECT * FROM make");

        $data=array();

        if($results->num_rows>0)
        {

             foreach($results->rows as $result)
             {

                 if(isset($result['make_id'])){


                  $models = $this->db->query("SELECT * FROM `model` WHERE make_id = '".(int)$result['make_id']."' ");
                  $model_data=array();

                  if($models->num_rows>0)
                  {

                      foreach($models->rows as $model)
                      {

                          if(isset($model['model_id'])){


                             $type_data=array();

                             $types = $this->db->query("SELECT * FROM `type` WHERE model_id = '".(int)$model['model_id']."' ");

                            if($types->num_rows>0)
                            {
                                foreach($types->rows as $type)
                                {
                                    if(isset($type['type_id'])){


                                    $type_data[]=array(
                                        'type_id' => $type['type_id'],
                                        'type_name' => $type['type_name'],
                                        'hp_min' => $type['hp_min'],
                                        'hp_max' => $type['hp_max'],
                                        'kw_start' => $type['kw_start'],
                                        'kw_end' => $type['kw_end'],
                                    );

                                    }
                                }
                            }

                          $model_data[]=array(
                              'model_id' => $model['model_id'],
                              'model_name' => $model['model_name'],
                              'year_start' => $model['year_start'],
                              'year_stop' => $model['year_stop'],
                              'types' => $type_data
                          );

                          }
                      }
                  }

                  $data[]=array(
                      'make_id' => $result['make_id'],
                      'make_name' => $result['make_name'],
                      'models' => $model_data,

                  );

                 }
             }
        }



        return $data;
    }

    public function productToCarInsert($data)
    {

        $sql = "INSERT INTO `product_to_car` SET product_id='".(int)$data['product_id']."', make_id='".(int)$data['make_id']."', model_id='".(int)$data['model_id']."' ";

        if(isset($data['type_id']) AND $data['type_id'])
        {
            $sql.= " , type_id='".(int)$data['type_id']."' ";
        }

        if(isset($data['alt_desc']))
        {
            $sql.= " , alt_desc = '1' ";
        }


        $this->db->query($sql);

        $id = $this->db->getLastId();

        /*
         * zapisz alternatywny opis
         */
        if(isset($data['alt_desc']))
        {
               foreach($data['alt_desc']['desc'] as $language_id => $value)
               {
                   $this->db->query("INSERT INTO " . DB_PREFIX . "alt_description SET product_to_car_id = '" . (int)$id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "'");
               }
        }



        return $id;
    }

    public function deleteAllCarsById($product_id)
    {
        $this->db->query("DELETE FROM `product_to_car` WHERE product_id='".(int)$product_id."' ");
    }


    public function getAllCarsByProductId($product_id,$raw = false)
    {
        $results = $this->db->query("SELECT ptc.make_id as mkd, ptc.model_id as mdd, ptc.type_id as tpd, m.make_name as mkn, md.model_name as mdn, t.type_name as tpn, md.platform as platform, t.ccm as capacity, t.kw as kw, t.ps as ps, t.year_start as year_start, t.year_end as year_stop FROM `product_to_car` ptc LEFT JOIN make m ON (ptc.make_id=m.make_id) LEFT JOIN model md ON (ptc.model_id=md.model_id) LEFT JOIN type t ON (ptc.type_id=t.type_id) WHERE product_id='".(int)$product_id."' ");
        $data=array();

        if($results->num_rows AND !$raw){

            foreach($results->rows as $row)
            {
                $data[]=array(
                    'ids' => $row['mkd'].'_'.$row['mdd'].'_'.$row['tpd'],
                    'name' => '<strong>Marka:</strong> '.$row['mkn'].' <strong>Model:</strong> '.$row['mdn'].' <strong>Typ:</strong> '.$row['tpn'],
                );
            }


        }
        elseif($results->num_rows AND $raw)
        {
            foreach($results->rows as $row)
            {
                $data[]=array(
                    'make_id' => $row['mkd'],
                    'model_id' => $row['mdd'],
                    'type_id' => $row['tpd'],
                    'make_name' => $row['mkn'],
                    'model_name' => $row['mdn'],
                    'type_name' => $row['tpn'],
                    'platform' => $row['platform'],
                    'capacity' => $row['capacity'],
                    'kw' => $row['kw'],
                    'ps' => $row['ps'],
                    'year_start' => $row['year_start'],
                    'year_stop' => $row['year_stop'],
                );
            }
        }

        foreach($data as $key1 => $ar)
        {
            foreach($data as $key2 => $ar2)
            {
                if(($ar == $ar2) AND $key1 != $key2)
                {
                    unset($data[$key2]);
                }
            }
        }

        return $data;
    }


    public function getAllOldCars()
    {
        $results = $this->db->query("SELECT ptc.product_id as product_id , ptc.make_id as mkd, ptc.model_id as mdd, ptc.type_id as tpd, m.make_name as mkn, md.model_name as mdn, t.type_name as tpn, t.year_start as year_start, t.year_end as year_stop FROM `product_to_car_old` ptc LEFT JOIN make_old m ON (ptc.make_id=m.make_id) LEFT JOIN model_old md ON (ptc.model_id=md.model_id) LEFT JOIN type_old t ON (ptc.type_id=t.type_id) ");
        $data=array();


            foreach($results->rows as $row)
            {
                $data[]=array(
                    'product_id' => $row['product_id'],
                    'make_id' => $row['mkd'],
                    'model_id' => $row['mdd'],
                    'type_id' => $row['tpd'],
                    'make_name' => $row['mkn'],
                    'model_name' => $row['mdn'],
                    'type_name' => $row['tpn'],

                    'year_start' => $row['year_start'],
                    'year_stop' => $row['year_stop'],
                );
            }


        return $data;
    }

    public function getMake() {



        $makes = $this->db->query("SELECT * FROM `make` ORDER BY make_name ASC");

        if($makes->num_rows>0)
        {
            $data=array();

            foreach ($makes->rows as $row) {
                $data[]=array(
                    'make_id' => $row['make_id'],
                    'make_name' => $row['make_name'],
                );

            }

            return $data;

            /*$json['output']=$data;

            $this->load->library('json');

            $this->response->setOutput(Json::encode($json));*/


        }

    }


    public function getModelbyMake($make_id,$clean=false) {



        $models = $this->db->query("SELECT model_id,platform,model_name,DATE_FORMAT(year_start, '%Y') as year1, DATE_FORMAT(year_stop, '%Y') as year2 FROM `model` WHERE make_id='".$make_id."' ORDER BY model_name ASC, year1 ASC");

        if($models->num_rows>0)
        {
            $data=array();

            foreach ($models->rows as $row) {



                if($row['year2']!='0000'){
                    $year = '('.$row['year1'].' - '.$row['year2'].')';
                }
                else {

                    $year = '('.$row['year1'].' - )';
                }

                if(!$clean)
                {
                    $data[]=array(
                        'model_id' => $row['model_id'],
                        'model_name' => $row['model_name'].' '.$row['platform'].' <span style="float:right;"> '.$year.' </span>',
                    );
                }
                else
                {
                    $data[]=array(
                        'model_id' => $row['model_id'],
                        'model_name' => $row['model_name'],
                    );
                }


            }

            return $data;

            /*$json['output']=$data;

            $this->load->library('json');

            $this->response->setOutput(Json::encode($json));*/


        }

    }

    public function getTypebyModel($model_id) {



        $types = $this->db->query("SELECT * FROM `type` WHERE model_id='".$model_id."' ORDER BY type_name ASC");

        if($types->num_rows>0)
        {
            $data=array();

            foreach ($types->rows as $row) {
                $data[]=array(
                    'type_id' => $row['type_id'],
                    'type_name' => $row['type_name'].' - <span style="float:right;">'.$row['kw'].' kw '.$row['ps'].' ps '.$row['ccm'].' ccm  '.$row['year_start'].'- '.$row['year_end'].'</span>',
                );

            }

            return $data;

            /* $json['output']=$data;

            $this->load->library('json');

            $this->response->setOutput(Json::encode($json)); */


        }

    }

    public function getOneMakeById($id)
    {
        $make = $this->db->query("SELECT * FROM `make` WHERE make_id='".(int)$id."'  ");

        if($make->num_rows>0)
        {
            return $make->row['make_name'];
        }
    }

    public function getOneModelById($id,$clean=false)
    {
        $model = $this->db->query("SELECT model_id,model_name,DATE_FORMAT(year_start, '%Y') as year1, DATE_FORMAT(year_stop, '%Y') as year2 FROM `model` WHERE model_id='".(int)$id."' ");

        if(!$clean)
        {
            if($model->num_rows>0)
            {
                return $model->row['model_name'].'  ('.$model->row['year1'].' - '.$model->row['year2'].')';
            }
        }
        else
        {
            if($model->num_rows>0)
            {
                return $model->row['model_name'];
            }
        }

    }

    public function getOneTypeById($id)
    {
        $type = $this->db->query("SELECT * FROM `type` WHERE type_id='".$id."'  ");

        if($type->num_rows>0)
        {
            return $type->row['type_name'].' - '.$type->row['kw_end'].' kw '.$type->row['hp_max'].' hp ';
        }
    }

    public function getMakeIdByName($name)
    {
          $name  = trim($name);

          $result = $this->db->query("SELECT * FROM `make` WHERE make_name = '".$name."'  ");

          if($result->row)
          {
               return $result->row['make_id'];
          }
          else
          {
               return false;
          }
    }

    public function getModelIdByName($model,$platform = false)
    {
        $name  = trim($model);



        $sql = "SELECT * FROM `model` WHERE  model_name = '".$this->db->escape($name)."' ";

        // nie bedzie rozbicia po rocznikach

        if($platform)
        {
             $sql .= " AND platform = '".$this->db->escape($platform)."'  ";
        }


        $sql.="  LIMIT 1 ";

        $result = $this->db->query($sql);






        if($result->row)
        {

          /*  var_dump($model);
            var_dump($platform);
            var_dump($result->row); */


            return $result->row['model_id'];
        }
        else
        {
            return false;
        }
    }

    public function getTypeIdByNameAndModelId($model_id,$name,$kw = false)
    {
        $name  = trim($name);



        $sql = "SELECT * FROM `type` WHERE  type_name = '".$this->db->escape($name)."'
        AND model_id='".(int)$model_id."' ";
		


        $result = $this->db->query($sql);

        if($result->row)
        {
            /*
             * problem pierwszy zwracało model_id zamiast type_id
             */
            return $result->row['type_id'];
        }
        else
        {
            return false;
        }
    }



    public function addMake($data)
    {
           $sql = "INSERT INTO `make` SET ";
           if(isset($data['make_id']))
           {
               $sql.=" make_id='".(int)$data['make_id']."', ";
           }
        if(isset($data['make_name']))
        {
            $sql.=" make_name='".$this->db->escape($data['make_name'])."' ";
        }

        $this->db->query($sql);

        return $this->db->getLastId();
    }

    public function addModel($data)
    {
        $sql = "INSERT INTO `model` SET ";
        $sql.=" make_id='".(int)$data['make_id']."', ";

        if(isset($data['model_name']))
        {
            $sql.=" model_name='".$this->db->escape($data['model_name'])."' ";
        }
        if(isset($data['year_start']) AND $data['year_start'] instanceof DateTime)
        {

            $sql.=" ,year_start='".$data['year_start']->format('Y-m-d')."' ";
        }
        if(isset($data['year_stop']) AND $data['year_stop'] instanceof DateTime)
        {

            $sql.=" ,year_stop='".$data['year_stop']->format('Y-m-d')."' ";
        }

        if(isset($data['image']) AND $data['image'])
        {

            $sql.=" ,image='".$this->db->escape($data['image'])."' ";
        }

        if(isset($data['second_image']) AND $data['second_image'])
        {

            $sql.=" ,second_image='".$this->db->escape($data['second_image'])."' ";
        }

        if(isset($data['platform']) AND $data['platform'])
        {

            $sql.=" ,platform='".$this->db->escape($data['platform'])."' ";
        }

        $this->db->query($sql);

        return $this->db->getLastId();
    }

    public function updateModelYearStart($model_id,DateTime $year_start)
    {
        $result = $this->db->query("SELECT * FROM model WHERE model_id = '".(int)$model_id."' AND year_start <= '".$year_start->format('Y-m-d')."' ");

        if(!$result->num_rows)
        {
            $this->db->query("UPDATE model SET year_start = '".$year_start->format('Y-m-d')."' WHERE model_id = '".(int)$model_id."'  ");
        }
    }

    public function updateModelYearStop($model_id,DateTime $year_stop)
    {
        $result = $this->db->query("SELECT * FROM model WHERE model_id = '".(int)$model_id."' AND year_stop >= '".$year_stop->format('Y-m-d')."' ");

        if(!$result->num_rows)
        {
            $this->db->query("UPDATE model SET year_stop = '".$year_stop->format('Y-m-d')."' WHERE model_id = '".(int)$model_id."'  ");
        }
    }

    public function addType($data)
    {
        if(!isset($data['type_name']) OR !$data['type_name'] OR $data['type_name']=='' )
        {
             return false;
        }

        $sql = "INSERT INTO `type` SET ";
        $sql.=" model_id='".(int)$data['model_id']."' ";

        if(isset($data['type_name']))
        {
            $sql.=", type_name='".$this->db->escape($data['type_name'])."' ";
        }

        if(isset($data['kw']))
        {

            $sql.=" ,kw='".$data['kw']."' ";

        }

        if(isset($data['ccm']))
        {

            $sql.=" ,ccm='".$data['ccm']."' ";

        }

        if(isset($data['ps']))
        {

            $sql.=" ,ps='".$data['ps']."' ";

        }

        if(isset($data['hsn']))
        {

            $sql.=" ,hsn='".$data['hsn']."' ";

        }

        if(isset($data['tsn']))
        {

            $sql.=" ,tsn='".$data['tsn']."' ";

        }

        if(isset($data['year_start']) AND $data['year_start'] instanceof DateTime)
        {

            $sql.=" ,year_start='".$data['year_start']->format('Y-m-d')."' ";

        }

        if(isset($data['year_stop']) AND $data['year_stop'] instanceof DateTime)
        {

            $sql.=" ,year_end='".$data['year_stop']->format('Y-m-d')."' ";

        }


        $this->db->query($sql);

      //  return $this->db->getLastId();
    }


    public function filterCarsDuplicates($cars)
    {
         $newArray = array();



         foreach($cars as $car)
         {
             $omnit = false ;

                 foreach($newArray as $mirror)
                 {
                      if($car['make_id']==$mirror['make_id'] AND $car['model_id']==$mirror['model_id'] AND $car['type_id']==$mirror['type_id'])
                      {
                          $omnit = true ;
                      }
                 }

             if($omnit)
             {
                 continue;
             }
             else
             {
                 $newArray[] = $car;
             }
         }


         return $newArray;
    }
}