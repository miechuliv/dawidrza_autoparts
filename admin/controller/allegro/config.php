<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 26.11.13
 * Time: 15:06
 * To change this template use File | Settings | File Templates.
 */

class ControllerAllegroConfig extends Controller{

    public function index()
    {

        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        $this->language->load('setting/setting');



        $this->data['entry_config_allegro_id'] = $this->language->get('entry_config_allegro_id');
        $this->data['entry_config_allegro_login'] = $this->language->get('entry_config_allegro_login');
        $this->data['entry_config_allegro_pass'] = $this->language->get('entry_config_allegro_pass');
        $this->data['entry_config_allegro_webapi'] = $this->language->get('entry_config_allegro_webapi');

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

            foreach($this->request->post as $key => $value)
            {
                $this->model_setting_setting->editSettingValue('config',$key,$value,0);
            }

        }

        // miechu koniec
      /*  if (isset($this->request->post['config_allegro_id'])) {
            $this->data['config_allegro_id'] = $this->request->post['config_allegro_id'];
        } else {
            $this->data['config_allegro_id'] = $this->config->get('config_allegro_id');
        } */

        if (isset($this->request->post['config_allegro_login'])) {
            $this->data['config_allegro_login'] = $this->request->post['config_allegro_login'];
        } else {
            $this->data['config_allegro_login'] = $this->config->get('config_allegro_login');
        }

        if (isset($this->request->post['config_allegro_pass'])) {
            $this->data['config_allegro_pass'] = $this->request->post['config_allegro_pass'];
        } else {
            $this->data['config_allegro_pass'] = $this->config->get('config_allegro_pass');
        }

        if (isset($this->request->post['config_allegro_webapi'])) {
            $this->data['config_allegro_webapi'] = $this->request->post['config_allegro_webapi'];
        } else {
            $this->data['config_allegro_webapi'] = $this->config->get('config_allegro_webapi');
        }

        $this->data['action'] = $this->url->link('allegro/config', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['cancel'] = $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL');

        $this->template = 'allegro/config.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());

    }
}
