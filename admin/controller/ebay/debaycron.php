<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 23.07.13
 * Time: 12:05
 * To change this template use File | Settings | File Templates.
 */

class ControllerEbayDebayCron extends Controller {

      public function checkauctions()
      {
          $this->load->model('ebay/debayauctions');

          $this->model_ebay_debayauctions->checkauctions();

      }
}