<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 29.07.13
 * Time: 16:23
 * To change this template use File | Settings | File Templates.
 */

class ControllerToolGenerator extends Controller{


       public function index()
       {

             $this->load->model('tool/generator');

             $this->model_tool_generator->massGenerate();

       }



}