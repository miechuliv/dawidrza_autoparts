<?php
/**
 * Created by James Allsup
 */
class ModelamazonusPatch extends Model
{ 
    public function runPatch($manual = true){
        /*
         * Manual flag to true is set when the user runs the patch method manually
         * false is when the module is updated using the update system
         */
        $this->load->model('setting/setting');

        $settings = $this->model_setting_setting->getSetting('openbay_amazonus');
        
        if($settings) {
            
        }
        
        /*
         * Always return true
         */
        return true;
    } 
}