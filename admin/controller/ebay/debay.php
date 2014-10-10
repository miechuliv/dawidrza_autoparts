<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 16.07.13
 * Time: 15:50
 * To change this template use File | Settings | File Templates.
 */

class ControllerEbayDebay extends Controller{




     public function additem()
     {



         // dane niezbedne do wystawienia produktu:
         $shipping = array( 'ShippingServiceOptions' => array(

             'ShippingService' => 'UPSGround',
        'ShippingServiceCost' => 0.0,
        'ShippingServiceAdditionalCost' => 0.0,
        'ShippingServicePriority' => 1,
        'ExpeditedService' => false,


         ),
         'ShippingType' => 'Flat',
         );

         $returnPolicy = array(
            'ReturnsAcceptedOption' => 'ReturnsAccepted',
      'RefundOption' => 'MoneyBack',
      'ReturnsWithinOption' => 'Days_30',
      'Description ' =>'If you are not satisfied, return the item for refund.',
      'ShippingCostPaidByOption' => 'Buyer',
         );

         $item = array(
             'PrimaryCategory' => array('CategoryID' => 111422),  // ok
             'Title' => 'Miechu nowy',
             'Description' => 'Testowy opis',
             'StartPrice' => 100,    //
             'BuyItNowPrice' => 500,//
             'ReservePrice' => 400,//
             'Country' => 'US',//
             'Currency' => 'USD',//
             'ListingDuration' => 'Days_7',//
             'PictureDetails' => 'dummy',//
             'Site' => 'US',//
             'Location' => 'San Jose',//
             'PaymentMethods' => 'PayPal',//
             'PayPalEmailAddress' => 'test@wp.pl',//
             'ShippingDetails' => $shipping,//
             'DispatchTimeMax' => 3,//
             'ConditionID' => 1000,//
             'ReturnPolicy' => $returnPolicy,//

         );

         $params = array(
             'Version' => 831,
             'Item' => $item





         );
         $method = 'addItem';

         try{

             $resp =  debay::sendRequest($method,$params);

             $this->load->model('ebay/debay');


         }
         catch(Exception $e)
         {
             echo $e->getMessage();
         }
     }

      public function getebaydetails()
      {



          $this->load->model('ebay/debay');

          $this->data['success'] = false;

          $this->data['error'] = false;

          $url ='';

          $this->data['update_action'] =  $this->url->link('ebay/debay/getebaydetails', 'token=' . $this->session->data['token'] . $url, 'SSL');


          if($this->request->server['REQUEST_METHOD']=='POST')
          {
              try{
                  $this->model_ebay_debay->updateEbayDetails();

                  $this->data['success'] = true;

              }catch(Exception $e)
              {


                  $this->data['error'] = $e->getMessage();
              }
          }


          $this->template = 'ebay/debay_update.tpl';
          $this->children = array(
              'common/header',
              'common/footer'
          );

          $this->response->setOutput($this->render());

      }




}
