<?php
class Amazon_category_template {
    
    private $xmlURL;
    private $simpleXML;

    public function load($data, $isUrl = true) {
        
        //if it is URL, we download XML. If not, interpret $data as XML
        if($isUrl) {
            $url = $data;
        } else {
            
            /* V2 */
            if(($this->simpleXML = simplexml_load_string($data)) == false) {
                return false;
            } else { 
                return true;
            }
            return;
            /*---*/
        }
        
        $this->xmlURL = $url;
        
        $defaults = array(
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $this->xmlURL,
            CURLOPT_USERAGENT => 'OpenBay Pro for Amazon/Opencart', 
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
        );
        $ch = curl_init();
        curl_setopt_array($ch, $defaults);
        $response = curl_exec($ch);
        curl_close($ch);

        if(!$response) {
            return false;
        }
           
        if(($this->simpleXML = simplexml_load_string($response)) == false) {
            return false;
        } else { 
            return true;
        }
    }
    
    //TODO: sorting may be useful if using methods invidualy but not getAllFields()
    public function getRequiredFields($sort=false) {
        return $this->getFields("required");
    }
    
     public function getDesiredFields($sort=false) {
        return $this->getFields("desired");
    }   
    
    public function getOptionalFields($sort=false) {
        return $this->getFields("optional");
    }
    
    public function getAllFields() {
        $merged = array_merge($this->getRequiredFields(), $this->getDesiredFields(), $this->getOptionalFields());
        
        foreach($merged as $index => $field) {
            $merged[$index]['unordered_index'] = $index;
        }
        usort($merged, array('Amazon_category_template','compareFields'));
        return $merged;
    } 
    
    public function getCategoryName() {
        return (string)$this->simpleXML->filename;
    }
    
    private function getFields($name) {
        $fields = array();
        
        foreach($this->simpleXML->fields->$name->field as $field) {
            $attributes = $field->attributes();
            $fields[] = array(
                'name' => (string)$attributes['name'], 
                'title' => (string)$field->title,
                'definition' => (string)$field->definition,
                'accepted' => $field->accepted,
                'type' => (string)$name,
                'child' => false,
                'order' => isset($attributes['order']) ? (string)$attributes['order'] : '',
                );
        }
        foreach($this->simpleXML->fields->$name->childfield as $field) {
            $attributes = $field->attributes();
            $fields[] = array(
                'name' => (string)$attributes['name'],
                'title' => (string)$field->title,
                'definition' => (string)$field->definition,
                'accepted' => (array)$field->accepted,
                'type' => (string)$name,
                'child' => true,
                'parent' => (array)$field->parent,
                'order' => isset($attributes['order']) ? (string)$attributes['order'] : '',
                );
        }
        
        return $fields;
    }
    
    //Used to sort fields array
    private static function compareFields($field1, $field2) {
        if($field1['order'] == $field2['order']) {
            return ($field1['unordered_index'] < $field2['unordered_index']) ? -1 : 1;
        } else if(!empty($field1['order']) && empty($field2['order'])) {
            return -1;
        } else if(!empty($field2['order']) && empty($field1['order'])) {
            return 1;
        } else {
            return ($field1['order'] < $field2['order']) ? -1 : 1;
        }
    }
}