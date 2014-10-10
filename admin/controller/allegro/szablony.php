<?php

class ControllerAllegroSzablony extends Controller {

	private $error = array() ;

	public function index() {

        $this->document->setTitle($this->language->get('Allegro - Szablony'));
		$this->load->model( 'allegro/szablony' ) ;
		$this->getList() ;
	}

	public function insert() {

		$this->document->setTitle($this->language->get('Allegro - Szablony - Dodawanie'));
        $this->load->model( 'allegro/szablony' ) ;

		if ( $this->request->server['REQUEST_METHOD'] == 'POST' ) {

			$this->model_allegro_szablony->addSzablon( $this->request->post ) ;
			$this->session->data['success'] = 'Szablon został dodany' ;
			$url = '' ;
			$this->redirect( HTTPS_SERVER . 'index.php?route=allegro/szablony&token=' . $this->session->data['token'] . $url ) ;
		}

		$this->getForm() ;
	}

	public function update() {

		$this->document->setTitle($this->language->get('Allegro - Szablony - Edycja'));
        $this->load->model( 'allegro/szablony' ) ;

		if ( $this->request->server['REQUEST_METHOD'] == 'POST' ) {

			$this->model_allegro_szablony->editSzablon( $this->request->get['szablon_id'], $this->request->post ) ;
			$this->session->data['success'] = 'Szablon został zedytowany' ;
			$url = '' ;
			$this->redirect( HTTPS_SERVER . 'index.php?route=allegro/szablony&token=' . $this->session->data['token'] . $url ) ;
		}

		$this->getForm() ;
	}

	public function delete() {

		$this->document->setTitle($this->language->get('Allegro - Szablony - Usuwanie'));
		$this->load->model( 'allegro/szablony' ) ;

		if ( isset( $this->request->post['selected'] ) ) {

			foreach ( $this->request->post['selected'] as $szablon_id ) {

				$this->model_allegro_szablony->deleteSzablon( $szablon_id ) ;
			}

			$this->session->data['success'] = 'Szablon został usunięty' ;
			$url = '' ;
			$this->redirect( HTTPS_SERVER . 'index.php?route=allegro/szablony&token=' . $this->session->data['token'] . $url ) ;
		}

		$this->getList() ;
	}

	private function getList() {

		$url = '' ;
		$this->document->breadcrumbs = array() ;
		$this->document->breadcrumbs[] = array( 'href' => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'], 'text' => $this->language->get( 'text_home' ), 'separator' => false ) ;
		$this->document->breadcrumbs[] = array( 'href' => HTTPS_SERVER . 'index.php?route=allegro/szablony&token=' . $this->session->data['token'] . $url, 'text' => 'Allegro Szablony', 'separator' => ' :: ' ) ;

		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=allegro/szablony/insert&token=' . $this->session->data['token'] . $url ;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=allegro/szablony/delete&token=' . $this->session->data['token'] . $url ;

		$results = $this->model_allegro_szablony->getSzablony() ;

		foreach ( $results as $result ) {

			$action = array() ;
			$action[] = array( 'text' => 'Edytuj', 'href' => HTTPS_SERVER . 'index.php?route=allegro/szablony/update&token=' . $this->session->data['token'] . '&szablon_id=' . $result['szablon_id'] . $url ) ;
			$this->data['szablony'][] = array( 'szablon_id' => $result['szablon_id'], 'name' => $result['name'], 'title' => $result['title'], 'selected' => isset( $this->request->post['selected'] ) && in_array( $result['szablon_id'], $this->request->post['selected'] ), 'action' => $action ) ;
		}

		$this->data['heading_title'] = 'Allegro - Szablony' ;
		$this->data['text_no_results'] = 'Nie ma dodanych szablonów' ;
		$this->data['button_insert'] = $this->language->get( 'button_insert' ) ;
		$this->data['button_delete'] = $this->language->get( 'button_delete' ) ;

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
		$this->template = 'allegro/szablony_list.tpl' ;
		$this->children = array( 'common/header', 'common/footer' ) ;
		$this->response->setOutput( $this->render( true ), $this->config->get( 'config_compression' ) ) ;
	}

	private function getForm() {

		$this->data['heading_title'] = 'Allegro - Szablony' ;
		$this->data['text_enabled'] = $this->language->get( 'text_enabled' ) ;
		$this->data['text_disabled'] = $this->language->get( 'text_disabled' ) ;
		$this->data['button_save'] = $this->language->get( 'button_save' ) ;
		$this->data['button_cancel'] = $this->language->get( 'button_cancel' ) ;
		$this->data['tab_general'] = $this->language->get( 'tab_general' ) ;

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
		$this->document->breadcrumbs[] = array( 'href' => HTTPS_SERVER . 'index.php?route=allegro/szablony&token=' . $this->session->data['token'] . $url, 'text' => $this->language->get( 'heading_title' ), 'separator' => ' :: ' ) ;

		if ( !isset( $this->request->get['szablon_id'] ) ) {

			$this->data['action'] = HTTPS_SERVER . 'index.php?route=allegro/szablony/insert&token=' . $this->session->data['token'] . $url ;
		}
		else {

			$this->data['action'] = HTTPS_SERVER . 'index.php?route=allegro/szablony/update&token=' . $this->session->data['token'] . '&szablon_id=' . $this->request->get['szablon_id'] . $url ;
		}

		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=allegro/szablony&token=' . $this->session->data['token'] . $url ;

		if ( isset( $this->request->get['szablon_id'] ) && ( $this->request->server['REQUEST_METHOD'] != 'POST' ) ) {

			$this->data['szablon'] = $this->model_allegro_szablony->getSzablon( $this->request->get['szablon_id'] ) ;
		}

		$this->template = 'allegro/szablony_form.tpl' ;
		$this->children = array( 'common/header', 'common/footer' ) ;
		$this->response->setOutput( $this->render( true ), $this->config->get( 'config_compression' ) ) ;
	}
}

?>