<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 26.07.13
 * Time: 13:52
 * To change this template use File | Settings | File Templates.
 */

class ControllerEbayDebayUpdate extends Controller{

      public function index(){

          if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

              $this->load->model('ebay/debay');

              $this->model_ebay_debay->updateEbayDetails($this->request->post);

          }

          $url = '';
          $this->data['action'] = $this->url->link('ebay/debayupdate', 'token=' . $this->session->data['token'] .$url, 'SSL');

          $this->template = 'ebay/debay_update.tpl';
          $this->children = array(
              'common/header',
              'common/footer',

          );

          $this->response->setOutput($this->render());
      }
}