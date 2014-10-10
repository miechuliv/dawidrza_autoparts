<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 17.07.13
 * Time: 16:32
 * To change this template use File | Settings | File Templates.
 */

class ModelEbayDebayAttribute extends model{

      public function generatefields($data)
      {


           if(!isset($data->Recommendations->NameRecommendation) OR  !$data->Recommendations->NameRecommendation)
           {
                throw new Exception('Nie udało sie pozsykać informacji o atrybutach wybranej kategorii');


           }

           $attributes = $data->Recommendations->NameRecommendation;



           $fields = array();

           if(is_array($attributes))
           {
               foreach($attributes as $attribute)
               {
                   $field = new attribute($attribute);
                   if(isset($this->session->data['debay_inputs'][$field->Name]))
                   {
                       $field->setDefault($this->session->data['debay_inputs'][$field->Name]);
                   }

                   $fields[] = $field->getInput();
               }
           }
           else
           {
               $field = new attribute($attributes);
               if(isset($this->session->data['debay_inputs'][$field->Name]))
               {
                   $field->setDefault($this->session->data['debay_inputs'][$field->Name]);
               }

               $fields[] = $field->getInput();
           }



           return $fields;

      }

}

class attribute
{
    public $Name;
    public $ValueType;
    public $MaxValues;
    public $SelectionMode;
    public $values = array();


    private $output='';
    private $defaultValue=null;

    public function __construct($data)
    {

            $this->Name = $data->Name;
            $this->ValueType = $data->ValidationRules->ValueType;
            $this->MaxValues = $data->ValidationRules->MaxValues;
            $this->SelectionMode = $data->ValidationRules->SelectionMode;
            if(isset($data->ValueRecommendation))
            {
                $this->values = $data->ValueRecommendation;
            }

    }

    public function setDefault($value)
    {
        $this->defaultValue = $value;
    }

    public function getInput()
    {
          if($this->SelectionMode=='FreeText')
          {

              if(!empty($this->values))
              {
                  $i=1;
                  $suggested = '';
                  foreach($this->values as $key => $value)
                  {
                      if($i>4)
                      {
                          break;
                      }

                      $i++;
                      $suggested .= $value->Value.', ';
                  }

                  $suggested .= ' itp.';

                  $this->output.= '<label class="debay-input-label" for="attribute_'.$this->Name.'" >'.$this->Name.'</br>
                  Przykładowo: '.$suggested.'</label>';
              }
              else
              {
                  $this->output.= '<label class="debay-input-label" for="attribute_'.$this->Name.'" >'.$this->Name.'</label>';
              }

                 $this->output.= '<input class="debay-input" type="text" value="'.$this->defaultValue.'" name="attribute_'.$this->Name.'" id="id-'.$this->Name.'" />';

          }
          elseif($this->SelectionMode=='SelectionOnly')
          {
              $this->output.= '<label class="debay-input-label" for="attribute_'.$this->Name.'" >'.$this->Name.'</label>';
              $this->output.= '<select class="debay-input-select"  name="attribute_'.$this->Name.'" id="id-'.$this->Name.'" >';

              foreach($this->values as $value)
              {

                 if(isset($value->Value))
                 {
                    if($value == $this->defaultValue)
                    {
                        $this->output.='<option value="'.$value->Value.'" selected="selected" >'.$value->Value.'</option>';
                    }
                   else
                   {
                       $this->output.='<option value="'.$value->Value.'"  >'.$value->Value.'</option>';
                   }
                 }
              }

              $this->output.= '</select>';
          }

          return $this->output;
    }

}