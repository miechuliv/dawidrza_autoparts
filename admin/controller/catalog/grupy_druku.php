<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 07.04.14
 * Time: 14:10
 * To change this template use File | Settings | File Templates.
 */

class ControllerCatalogGrupyDruku extends Controller{

    public function index()
    {
        $this->load->language('catalog/grupy_druku');

        $this->load->model('catalog/grupy_druku');

        $this->data['success'] = false;

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {

            $this->model_catalog_grupy_druku->deleteGrupyDruku();
            foreach($this->request->post['grupy_druku'] as $grupa)
            {
                $this->model_catalog_grupy_druku->saveGrupyDruku($grupa);
            }

            $this->data['success'] = $this->language->get('text_success');

        }
        $this->data['grupy'] = array();
        $q =     $this->db->query("SELECT * FROM `".DB_PREFIX."product_attribute` WHERE attribute_id = 2 ");

        if($q->num_rows)
        {
            foreach($q->rows as $row)
            {
                $this->data['grupy'][] = $row['text'];
            }
        }

        if (isset($this->request->post['grupy_druku'])) {
            $this->data['grupy_druku'] = $this->request->post['grupy_druku'];
        } else {
            $this->data['grupy_druku'] = $this->model_catalog_grupy_druku->getGrupyDruku();
        }

        $this->data['grupy_druku2'] = $this->data['grupy_druku'];
        $this->data['grupy_druku'] = array();
        // koncentracja
        foreach($this->data['grupy_druku2'] as $key => $grupa)
        {
            if(!isset($this->data['grupy_druku'][$grupa['grupa_druku']]))
            {
                $this->data['grupy_druku'][$grupa['grupa_druku']] = array();
            }

            $this->data['grupy_druku'][$grupa['grupa_druku']][] = $grupa;
        }

        $this->template = 'catalog/grupy_druku.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }
}