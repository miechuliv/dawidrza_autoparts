<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 30.07.13
 * Time: 10:22
 * To change this template use File | Settings | File Templates.
 */

class ModelToolGenerator extends Model{

      /*
       * masowae generowanie linków seo z kombinacji marek, modeli i kategorii
       */
      public function massGenerate()
      {
          error_reporting(E_ALL);

          ini_set('display_errors', '1');

          $this->load->model('tool/cars');

          $makes = $this->model_tool_cars->getMake();

          $this->load->model('catalog/category');

          // nie dudalo mi się wpełni zautomatyzować, trzeba przełączać id języka , 2 - polski 3 - niemiecki
          $categories = $this->model_catalog_category->getCategoriesExt(0,3);

          $lang = 'de';

          $cats = $this->prepareCats($categories);

          $links = array();



          foreach($makes as $make)
          {

              // z kategoriami
              foreach($cats as $cat)
              {

                  $alias = str_ireplace(array(' ','>','<','&',',','.'),'-',$make['make_name']).'-'.$cat['name'];
                  $alias = $this->prepareAlias($alias);


                  $links[] = array(
                      'query' => 'category_id='.$cat['category_id'].'&make='.$make['make_id'],
                      'alias' => $alias,
                  );
              }

              // bez kategorii
              $alias = str_ireplace(array(' ','>','<','&',',','.'),'-',$make['make_name']);
              $alias = $this->prepareAlias($alias);


              $links[] = array(
                  'query' => 'category_id=0'.'&make='.$make['make_id'],
                  'alias' => $alias,
              );

              // z modelami
              $models = $this->model_tool_cars->getModelbyMake($make['make_id'],true);

              if($models){
              foreach($models as $model)
              {
                  foreach($cats as $cat)
                  {
                      $alias = str_ireplace(' ','-',$make['make_name']).'-'.str_ireplace(' ','-',$model['model_name']).'-'.$cat['name'];
                      $alias = $this->prepareAlias($alias);

                      $links[] = array(
                          'query' => 'category_id='.$cat['category_id'].'&make='.$make['make_id'].'&model='.$model['model_id'],
                          'alias' => $alias
                      );
                  }

                  // bez kategorii
                  $alias = str_ireplace(array(' ','>','<','&',',','.'),'-',$make['make_name'].'-'.str_ireplace(' ','-',$model['model_name']));
                  $alias = $this->prepareAlias($alias);


                  $links[] = array(
                      'query' => 'category_id=0'.'&make='.$make['make_id'],
                      'alias' => $alias,
                  );



                  /*    $types = $this->model_tool_cars->getTypebyModel($model['model_id']);

                      foreach($types as $type)
                      {
                          foreach($categories as $category)
                          {
                              $links[] = array(
                                  'href' => 'category_id='.$category['category_id'].'&make='.$make['make_id'].'&model='.$model['model_id'].'&type='.$type['type_id'],
                                  'alias' => str_ireplace(' ','-',$make['make_name']).'-'.str_ireplace(' ','-',$model['model_name']).'-'.str_ireplace(' ','-',$type['type_name']).'-'.str_ireplace(' ','-',$category['name'])
                              );
                          }
                      } */
              }
              }
          }



          foreach($links as $link)
          {
              $this->saveAlias($link['query'],$link['alias'],$lang);
          }
      }

    /*
     * generuje linki seo dla jednego przedmiotu, jeden link dla każdego przedmiotu pasującego do samochodu
     */

    public function singleGenerate($car,$product,$product_id)
    {

        $this->load->model('tool/cars');

        $make = $this->model_tool_cars->getOneMakeById($car['make_id']);
        $model = $this->model_tool_cars->getOneModelById($car['model_id'],true);


        foreach ($product['product_description'] as $language_id => $value) {

            $alias = str_ireplace(array(' ','>','<','&',',','.'),'-',$make).'-'.str_ireplace(' ','-',$model).'-'.str_ireplace(' ','-',$value['name']);

            $alias = $this->prepareAlias($alias);

            $query =  'product_id='.$product_id.'&make='.$car['make_id'].'&model='.$car['model_id'];

            $this->saveAlias($query,$alias);
        }
    }

    /*
     * obrabia kategorie i genruje fromy pojedyncze
     */

    private function prepareCats($categories)
    {
        $cats = array();

        foreach($categories as $category)
        {
            $cat = html_entity_decode($category['name']);
            $cat = str_ireplace(array('>','<','&',',','.','/','\\'),'',$cat);
            $cat = str_ireplace(array(' '),'-',$cat);

            $cats[] = array(
                'name' => $cat,
                'category_id' => $category['category_id'],
            );


            // generuj formę pojedynczą
            if($cat == 'Części')
            {
                 $cat = 'cześć';
            }
            elseif($cat == 'Injektory')
            {
                $cat = 'injektor';
            }
            elseif($cat == 'Pompy-paliwowe')
            {
                $cat = 'pompa-paliwowa';
            }
            elseif($cat == 'Turbosprężarki')
            {
                $cat = 'turbosprężarka';
            }
            elseif($cat == 'Diesel-Injektoren')
            {
                $cat = 'Diesel-Injektor';
            }

            elseif($cat == 'Kraftstoffpumpen')
            {
                $cat = 'Kraftstoffpump';
            }
            elseif($cat == 'Turbos')
            {
                $cat = 'Turbo';
            }


            $cats[] = array(
                'name' => $cat,
                'category_id' => $category['category_id'],
            );
        }

        return $cats;
    }

    private function prepareAlias($alias)
    {

        $alias = strtolower($alias);
        $alias = preg_replace('/-+/', '-', $alias);
        $alias = str_ireplace(array('>','<','&',',','.','/','\\'),'',$alias);

        $aArr1 = array('ą', 'ę', 'ć' , 'ż' , 'ź' , 'ń' , 'ś' , 'ł' , 'ó');
        $aArr2 = array('a', 'e', 'c' , 'z' , 'z' , 'n' , 's' , 'l' , 'o');
        $alias = str_replace($aArr1, $aArr2, $alias);

        return $alias;
    }

    private function saveAlias($query,$alias,$language)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = '".$query."', keyword = '" . $this->db->escape($alias) . "', language = '".$language."' ");
    }
}