<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 01.11.13
 * Time: 11:02
 * To change this template use File | Settings | File Templates.
 */

class ControllerSpecyficXls extends Controller{

    private $_fileLocation;

    public function index()
    {



        $this->_fileLocation = DIR_APPLICATION."../baza.xlsx";

        $xlsreader = new SimpleXLSX($this->_fileLocation);


        $this->load->model('tool/cars');




        foreach($xlsreader->rows() as $key => $row)
        {
            // omijamy zerowy wiersz
            if(!$key)
            {
                continue;
            }
            else
            {

                $this->SaveRow($row);

            }

           // $this->saveRow($row,$key);

        }
    }

    private function SaveRow($row)
    {
        // 1 -make name
        // 2 - model name
        // 3 - type name
        // 4 - platfomr
        // 5 - years
        // 6 - engine
        // 7 - hsn / tsn

        // sprawdzamy czy to jest diesel ,jeśli nie ma d to nie diesel
        if(stripos($row[3],'d')===false)
        {
             return false;
        }

        // jesli ma xdrive ale nie ma drugie d to też benzyna
        if(stripos($row[3],'xdrive')!==false AND (substr_count($row[3],'d') + substr_count($row[3],'D')) < 2)
        {
            return false;
        }

        try{
            $make_id = $this->model_tool_cars->getMakeIdByName($row[1]);

            if(!$make_id)
            {
                $data = array(
                    'make_name' => $row[1],
                );
                $make_id = $this->model_tool_cars->addMake($data);
            }

            if(!$make_id)
            {
                 throw new Exception("Nie udało sie dodać marki");
            }

            $platform = $row[4];

            if($platform == '--')
            {
                $platform = false;

            }


            $model_name = $row[2];

            // szukamy po nazwie I platformie
            $model_id = $this->model_tool_cars->getModelIdByName($model_name,$row[4]);

            $years = $this->getDatesType($row[5]);

            if(!$model_id)
            {


                  $data = array(
                      'make_id' => $make_id,
                      'model_name' => $model_name,
                      'year_start' => $years['year_start'],
                      'year_stop' => $years['year_stop'],
                      'platform' => $platform,
                  );

                $model_id = $this->model_tool_cars->addModel($data);
            }
            else
            {
                // aktualizacja zakresu rocznikow modelu

                if($years['year_start'])
                {
                    $this->model_tool_cars->updateModelYearStart($model_id,$years['year_start']);
                }

                if($years['year_stop'])
                {
                    $this->model_tool_cars->updateModelYearStop($model_id,$years['year_stop']);
                }


            }


            if(!$model_id)
            {
                throw new Exception("Nie udało sie dodać modelu");
            }

             $ccm = false;
                $kw= false;
               $ps= false;
                 $hsn= false;
                $tsn= false;

            if(isset($row[6]))
            {
                $t = explode(',',$row[6]);


                $c = explode(' ',trim($t[0]));
                $ccm = array_shift($c);

                if(isset($t[1]))
                {
                    $d = explode(' ',trim($t[1]));
                    $kw = array_shift($d);
                }

                if(isset($t[2]))
                {
                    $e = explode(' ',trim($t[2]));
                    $ps = array_shift($e);
                }

            }

            if(isset($row[7]))
            {
                $t2 = explode(';',$row[7]);

                $t3 = explode('|',array_shift($t2));

                $hsn = $t3[0];

                $tsn = $t3[1];
            }




            // dodajemy typ
            $data = array(
                'model_id' => $model_id,
                'type_name' => $row[3],
                'year_start' => $years['year_start'],
                'year_stop' => $years['year_stop'],
                'ccm' => $ccm,
                'kw' => $kw,
                'ps' => $ps,
                'hsn' => $hsn,
                'tsn' => $tsn,
            );

            $this->model_tool_cars->addType($data);


        }
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }


    }

    public function getDatesType($cell)
    {
          $returned =  array();

          $dates = explode('-',$cell);

          $dates[0] = str_ireplace('/','-',$dates[0]);

          $date_start = new DateTime($dates[0].'-01');

          $returned['year_start'] = $date_start;

         if(isset($dates[1]))
         {
             $dates[1] = str_ireplace('/','-',$dates[1]);

             $date_end = new DateTime($dates[1].'-01');

             $returned['year_stop'] = $date_end;
         }
         else
         {
             $returned['year_stop'] = false;
         }

        return $returned;
    }

}