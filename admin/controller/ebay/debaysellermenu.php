<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 22.07.13
 * Time: 13:05
 * To change this template use File | Settings | File Templates.
 */

class ControllerEbayDebaySellerMenu extends Controller{


    public function index()
    {

        $url = '';

        $this->data['sold'] = $this->url->link('ebay/debayseller/sold', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $this->data['unsold'] = $this->url->link('ebay/debayseller/unsold', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $this->data['active'] = $this->url->link('ebay/debayseller/active', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $this->data['messages'] = $this->url->link('ebay/debayseller/messages', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $this->load->model('ebay/debay');

        $response =  $this->model_ebay_debay->getSalesfromEbay('summary');


        if(isset($response->Summary))
        {
            $sum = $response->Summary;
             $this->data['summary'] = array(
                 "ActiveAuctionCount" => isset($sum->ActiveAuctionCount) ? $sum->ActiveAuctionCount : NULL,
                 "AuctionSellingCount" => isset($sum->AuctionSellingCount) ? $sum->AuctionSellingCount : NULL,
                 "AuctionBidCount" => isset($sum->AuctionBidCount) ? $sum->AuctionBidCount : NULL,

                 "TotalAuctionSellingValue" => isset($sum->TotalAuctionSellingValue) ? $sum->TotalAuctionSellingValue->_.' '.$sum->TotalAuctionSellingValue->currencyID : NULL,
                 "TotalSoldCount" => isset($sum->TotalSoldCount) ? $sum->TotalSoldCount : NULL,
                 "TotalSoldValue" => isset($sum->TotalSoldValue) ? $sum->TotalSoldValue->_.' '.$sum->TotalSoldValue->currencyID : NULL,

                 "ClassifiedAdCount" => isset($sum->ClassifiedAdCount) ? $sum->ClassifiedAdCount : NULL,
                 "TotalLeadCount" => isset($sum->TotalLeadCount) ? $sum->TotalLeadCount : NULL,
                 "ClassifiedAdOfferCount" => isset($sum->ClassifiedAdOfferCount) ? $sum->ClassifiedAdOfferCount : NULL,
                 "TotalListingsWithLeads" => isset($sum->TotalListingsWithLeads) ? $sum->TotalListingsWithLeads : NULL,
             );
        }




        $this->template = 'ebay/debay_seller_menu.tpl';


        $this->response->setOutput($this->render());
    }


}