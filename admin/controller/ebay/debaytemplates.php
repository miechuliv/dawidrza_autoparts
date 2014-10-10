<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 18.07.13
 * Time: 11:29
 * To change this template use File | Settings | File Templates.
 */

class ControllerEbayDebayTemplates extends Controller {

    private $error = array() ;

    public function index() {


        $this->document->setTitle('Szablony Ebay');
        $this->load->model( 'ebay/debaytemplates' ) ;
        $this->getList() ;
    }

    public function insert() {


        $this->load->model( 'ebay/debaytemplates' ) ;

        if ( $this->request->server['REQUEST_METHOD'] == 'POST' ) {

            $this->model_ebay_debaytemplates->addSzablon( $this->request->post ) ;
            $this->session->data['success'] = 'Szablon został dodany' ;
            $url = '' ;
            $this->redirect( HTTPS_SERVER . 'index.php?route=ebay/debaytemplates&token=' . $this->session->data['token'] . $url ) ;
        }

        $this->getForm() ;
    }

    public function update() {


        $this->load->model( 'ebay/debaytemplates' ) ;

        if ( $this->request->server['REQUEST_METHOD'] == 'POST' ) {

            $this->model_ebay_debaytemplates->editSzablon( $this->request->get['debay_template_id'], $this->request->post ) ;
            $this->session->data['success'] = 'Szablon został zedytowany' ;
            $url = '' ;
            $this->redirect( HTTPS_SERVER . 'index.php?route=ebay/debaytemplates&token=' . $this->session->data['token'] . $url ) ;
        }

        $this->getForm() ;
    }

    public function delete() {

        $this->load->model( 'ebay/debaytemplates' ) ;

        if ( isset( $this->request->post['selected'] ) ) {

            foreach ( $this->request->post['selected'] as $szablon_id ) {

                $this->model_ebay_debaytemplates->deleteSzablon( $szablon_id ) ;
            }

            $this->session->data['success'] = 'Szablon został usunięty' ;
            $url = '' ;
            $this->redirect( HTTPS_SERVER . 'index.php?route=ebay/debaytemplates&token=' . $this->session->data['token'] . $url ) ;
        }

        $this->getList() ;
    }

    private function getList() {

        $url = '' ;


        $this->data['insert'] = HTTPS_SERVER . 'index.php?route=ebay/debaytemplates/insert&token=' . $this->session->data['token'] . $url ;
        $this->data['delete'] = HTTPS_SERVER . 'index.php?route=ebay/debaytemplates/delete&token=' . $this->session->data['token'] . $url ;

        $results = $this->model_ebay_debaytemplates->getSzablony() ;

        foreach ( $results as $result ) {

            $action = array() ;
            $action[] = array( 'text' => 'Edytuj', 'href' => HTTPS_SERVER . 'index.php?route=ebay/debaytemplates/update&token=' . $this->session->data['token'] . '&debay_template_id=' . $result['debay_template_id'] . $url ) ;
            $this->data['szablony'][] = array( 'debay_template_id' => $result['debay_template_id'], 'name' => $result['name'], 'title' => $result['title'], 'selected' => isset( $this->request->post['selected'] ) && in_array( $result['szablon_id'], $this->request->post['selected'] ), 'action' => $action ) ;
        }

        $this->data['heading_title'] = 'Ebay - Szablony' ;
        $this->data['text_no_results'] = 'Nie ma dodanych szablonów' ;
        $this->data['button_insert'] = 'Dodaj' ;
        $this->data['button_delete'] = 'Usuń' ;

        if ( isset( $this->error['warning'] ) ) {

            $this->data['error_warning'] = $this->error['warning'] ;
        }
        else {

            $this->data['error_warning'] = '' ;
        }

        if ( isset( $this->session->data['success'] ) ) {

            $this->data['success'] = $this->session->data['success'] ;
            unset( $this->session->data['success'] ) ;
        }
        else {

            $this->data['success'] = '' ;
        }

        $url = '' ;

        if ( isset( $this->request->get['page'] ) ) {

            $url .= '&page=' . $this->request->get['page'] ;
        }

        $url = '' ;
        $this->template = 'ebay/debay_templates_list.tpl' ;
        $this->children = array( 'common/header', 'common/footer' ) ;
        $this->response->setOutput( $this->render( true ), $this->config->get( 'config_compression' ) ) ;
    }

    private function getForm() {

        $this->data['heading_title'] = 'Allegro - Szablony' ;
        $this->data['text_enabled'] = 'Aktywny' ;
        $this->data['text_disabled'] = 'Wyłączony' ;
        $this->data['button_save'] = 'Zapisz' ;
        $this->data['button_cancel'] = 'Anuluj' ;
        $this->data['tab_general'] = 'Podsumowanie' ;

        if ( isset( $this->error['warning'] ) ) {

            $this->data['error_warning'] = $this->error['warning'] ;
        }
        else {

            $this->data['error_warning'] = '' ;
        }

        $url = '' ;

        if ( isset( $this->request->get['page'] ) ) {

            $url .= '&page=' . $this->request->get['page'] ;
        }

        $this->document->breadcrumbs = array() ;
        $this->document->breadcrumbs[] = array( 'href' => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'], 'text' => $this->language->get( 'text_home' ), 'separator' => false ) ;
        $this->document->breadcrumbs[] = array( 'href' => HTTPS_SERVER . 'index.php?route=ebay/debaytemplates&token=' . $this->session->data['token'] . $url, 'text' => '>', 'separator' => ' :: ' ) ;

        if ( !isset( $this->request->get['debay_template_id'] ) ) {

            $this->data['action'] = HTTPS_SERVER . 'index.php?route=ebay/debaytemplates/insert&token=' . $this->session->data['token'] . $url ;
        }
        else {

            $this->data['action'] = HTTPS_SERVER . 'index.php?route=ebay/debaytemplates/update&token=' . $this->session->data['token'] . '&debay_template_id=' . $this->request->get['debay_template_id'] . $url ;
        }

        $this->data['cancel'] = HTTPS_SERVER . 'index.php?route=ebay/debaytemplates&token=' . $this->session->data['token'] . $url ;

        if ( isset( $this->request->get['debay_template_id'] ) && ( $this->request->server['REQUEST_METHOD'] != 'POST' ) ) {

            $this->data['szablon'] = $this->model_ebay_debaytemplates->getSzablon( $this->request->get['debay_template_id'] ) ;
        }

        $this->template = 'ebay/debay_templates_form.tpl' ;
        $this->children = array( 'common/header', 'common/footer' ) ;
        $this->response->setOutput( $this->render( true ), $this->config->get( 'config_compression' ) ) ;
    }






}