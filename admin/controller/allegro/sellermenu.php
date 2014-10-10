<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Robert
 * Date: 21.06.13
 * Time: 12:49
 * To change this template use File | Settings | File Templates.
 */

class ControllerAllegroSellerMenu extends Controller{

public function index(){

    $this->data['sell'] = $this->url->link('catalog/product','token=' . $this->session->data['token']);

    $this->data['current'] = $this->url->link('allegro/seller','token=' . $this->session->data['token']);

    $this->data['sold'] = $this->url->link('allegro/seller/sold','token=' . $this->session->data['token']);

    $this->data['unsold'] = $this->url->link('allegro/seller/unsold','token=' . $this->session->data['token']);

    $this->data['incoming_payment'] = $this->url->link('allegro/seller/incomingpay','token=' . $this->session->data['token']);

    $this->data['outgoing_payment'] = $this->url->link('allegro/seller/outgoingpay','token=' . $this->session->data['token']);

    $this->data['kontrahent'] = $this->url->link('allegro/seller/kontrahent','token=' . $this->session->data['token']);

    $this->data['comments_in'] = $this->url->link('allegro/seller/comments','token=' . $this->session->data['token'].'&mode=fb_recvd');

    $this->data['comments_out'] = $this->url->link('allegro/seller/comments','token=' . $this->session->data['token'].'&mode=fb_gave');

    $this->template = 'allegro/sellermenu.tpl';

    $this->render();
}



}