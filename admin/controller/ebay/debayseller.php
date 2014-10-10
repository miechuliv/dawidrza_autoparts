<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 22.07.13
 * Time: 10:34
 * To change this template use File | Settings | File Templates.
 */

class ControllerEbayDebaySeller extends Controller{


    public function index()
    {



        $this->load->model('ebay/debay');

        $this->model_ebay_debay->getSalesfromEbay();


        $this->template = 'ebay/debay_seller.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
            'ebay/debayjs'
        );

        $this->response->setOutput($this->render());
    }

    public function sold()
    {



        $this->load->model('ebay/debay');

        $url = '';

        $this->data['date_sort'] = $this->url->link('ebay/debayseller/sold', 'token=' . $this->session->data['token'] . '&sort=EndTime' .$url, 'SSL');
        $this->data['price_sort'] = $this->url->link('ebay/debayseller/sold', 'token=' . $this->session->data['token'] . '&sort=CurrentPrice' .$url, 'SSL');

        if(isset($this->request->get['sort']))
        {
            $sort = $this->request->get['sort'];

            if($sort == 'EndTime')
            {
                $this->data['date_sort'] = $this->url->link('ebay/debayseller/sold', 'token=' . $this->session->data['token'] . '&sort=EndTimeDescending' .$url, 'SSL');
            }

            if($sort == 'EndTimeDescending')
            {
                $this->data['date_sort'] = $this->url->link('ebay/debayseller/sold', 'token=' . $this->session->data['token'] . '&sort=EndTime' .$url, 'SSL');
            }

            if($sort == 'CurrentPrice')
            {
                $this->data['price_sort'] = $this->url->link('ebay/debayseller/sold', 'token=' . $this->session->data['token'] . '&sort=CurrentPriceDescending' .$url, 'SSL');;
            }

            if($sort == 'CurrentPriceDescending')
            {
                $this->data['price_sort'] = $this->url->link('ebay/debayseller/sold', 'token=' . $this->session->data['token'] . '&sort=CurrentPrice' .$url, 'SSL');
            }

        }
        else
        {


            $sort = false;
        }

        $response = $this->model_ebay_debay->getSalesfromEbay('sold',$sort);


        $this->data['sold'] = array();

        $this->data['orders'] = array();

        if(!isset($response->SoldList))
        {
            ob_start();
            var_dump($response);
            $contents = ob_get_contents();
            ob_end_clean();
            $this->logger->warning("Nie można uzyskac listy sprzedanych: ".$contents,'ebay');


        }


        if(isset($response->SoldList->OrderTransactionArray->OrderTransaction))
        {
            $transactions = $response->SoldList->OrderTransactionArray->OrderTransaction;






            $this->load->model('tool/image');

            foreach($transactions as $transaction)
            {

               if(isset($transaction->Transaction))
               {
                   $trans = $transaction->Transaction;

                   $item = $trans->Item;

                   $buyer = $trans->Buyer;

                   $buyitnow = $this->isbuyitnow($item);

                   $url = '';

                   $this->data['sold'][] = array(
                       'Title' => $item->Title,
                       'ViewItemURL' => $item->ListingDetails->ViewItemURL,
                       'Email' => $buyer->Email,
                       'UserID' => $buyer->UserID,

                       'CurrentPrice' => $item->SellingStatus->CurrentPrice->_.' '.$item->SellingStatus->CurrentPrice->currencyID,


                       'QuantityPurchased' => $trans->QuantityPurchased,
                       'SellerPaidStatus' => isset($trans->SellerPaidStatus) ? $trans->SellerPaidStatus : NULL,
                       'ShippedTime' => isset($trans->ShippedTime) ? $trans->ShippedTime : NULL,
                       'FeedbackLeft' => isset($trans->FeedbackLeft) ? $trans->FeedbackLeft : NULL,
                       'FeedbackReceived' => isset($trans->FeedbackReceived) ? $trans->FeedbackReceived : NULL,
                       'buyitnow' => $buyitnow,
                       'ShippingServiceCost' => isset($item->ShippingDetails->ShippingServiceOptions->ShippingServiceCost) ? $item->ShippingDetails->ShippingServiceOptions->ShippingServiceCost->_ . ' '.$item->ShippingDetails->ShippingServiceOptions->ShippingServiceCost->currencyID : 'Darmowa dostawa',
                       'resell' => $this->url->link('ebay/debayproduct/resell', 'token=' . $this->session->data['token'] . '&item_id='.$item->ItemID .$url, 'SSL'),

                   );
               }

                /*
                 * pełne zamowienie po przejściu przez kasę
                 */
                if(isset($transaction->Order))
                {
                    $order = $transaction->Order;



                    $items = array();

                    foreach($order->TransactionArray->Transaction as $trans)
                    {



                    $item = $trans->Item;

                    $buyer = $trans->Buyer;

                    $buyitnow = $this->isbuyitnow($item);

                    $url = '';

                        if(!isset($item->Title))
                        {
                            ob_start();
                            var_dump($response);
                            $contents = ob_get_contents();
                            ob_end_clean();
                            $this->logger->warning("Aktywna aukcja nie posiada tytułu: ".$contents,'ebay');
                            continue;

                        }

                    $items[] = array(
                        'Title' => $item->Title,
                        'ViewItemURL' => $item->ListingDetails->ViewItemURL,
                        'Email' => $buyer->Email,
                        'UserID' => $buyer->UserID,

                        'CurrentPrice' => $item->SellingStatus->CurrentPrice->_.' '.$item->SellingStatus->CurrentPrice->currencyID,


                        'QuantityPurchased' => $trans->QuantityPurchased,
                        'SellerPaidStatus' => isset($trans->SellerPaidStatus) ? $trans->SellerPaidStatus : NULL,
                        'ShippedTime' => isset($trans->ShippedTime) ? $trans->ShippedTime : NULL,
                        'FeedbackLeft' => isset($trans->FeedbackLeft) ? $trans->FeedbackLeft : NULL,
                        'FeedbackReceived' => isset($trans->FeedbackReceived) ? $trans->FeedbackReceived : NULL,
                        'buyitnow' => $buyitnow,
                        'ShippingServiceCost' => isset($item->ShippingDetails->ShippingServiceOptions->ShippingServiceCost) ? $item->ShippingDetails->ShippingServiceOptions->ShippingServiceCost->_ . ' '.$item->ShippingDetails->ShippingServiceOptions->ShippingServiceCost->currencyID : 'Darmowa dostawa',
                        'resell' => $this->url->link('ebay/debayproduct/resell', 'token=' . $this->session->data['token'] . '&item_id='.$item->ItemID .$url, 'SSL'),

                    );


                    }

                    $this->data['orders'][] = array(
                       'items' => $items,
                       'Total' => $order->Total->_.' '.$order->Total->currencyID,
                       'Total_items' => count($items),
                       'order_details' => $this->url->link('ebay/debayseller/orderdetails', 'token=' . $this->session->data['token'] . '&order_id='.$order->OrderID .$url, 'SSL'),

                    );

                }

            }
        }
        else
        {
            $this->data['error']['no_data'] = 'Brak danych';

            $this->logger->warning("Brak aukcji sprzedanych",'ebay');
        }


        $this->template = 'ebay/debay_sold.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
            'ebay/debaysellermenu',
        );

        $this->response->setOutput($this->render());
    }

    public function active()
    {


        $this->load->model('ebay/debay');

        $url = '';

        $this->data['date_sort'] = $this->url->link('ebay/debayseller/active', 'token=' . $this->session->data['token'] . '&sort=EndTime' .$url, 'SSL');
        $this->data['price_sort'] = $this->url->link('ebay/debayseller/active', 'token=' . $this->session->data['token'] . '&sort=CurrentPrice' .$url, 'SSL');


        if(isset($this->request->get['sort']))
        {
            $sort = $this->request->get['sort'];

            if($sort == 'EndTime')
            {
                $this->data['date_sort'] = $this->url->link('ebay/debayseller/active', 'token=' . $this->session->data['token'] . '&sort=EndTimeDescending' .$url, 'SSL');
            }

            if($sort == 'EndTimeDescending')
            {
                $this->data['date_sort'] = $this->url->link('ebay/debayseller/active', 'token=' . $this->session->data['token'] . '&sort=EndTime' .$url, 'SSL');
            }

            if($sort == 'CurrentPrice')
            {
                $this->data['price_sort'] = $this->url->link('ebay/debayseller/active', 'token=' . $this->session->data['token'] . '&sort=CurrentPriceDescending' .$url, 'SSL');;
            }

            if($sort == 'CurrentPriceDescending')
            {
                $this->data['price_sort'] = $this->url->link('ebay/debayseller/active', 'token=' . $this->session->data['token'] . '&sort=CurrentPrice' .$url, 'SSL');
            }

        }
        else
        {


            $sort = false;
        }

        $response =  $this->model_ebay_debay->getSalesfromEbay('active',$sort);



        if(isset($response->ActiveList->ItemArray->Item))
        {
             $items = $response->ActiveList->ItemArray->Item;



             $this->data['active'] = array();


             $this->load->model('tool/image');



             foreach($items as $item)
             {



                    if(isset($item->TimeLeft))
                    {

                        $time_left = $this->decodetime($item->TimeLeft);
                    }
                    else{
                        $time_left = '';
                    }

                    $buyitnow = $this->isbuyitnow($item);

                    // @todo coś z tymi wiews, watch i bids nie dziła jak trzeba
                    $url = '';

                    if(!isset($item->Title))
                    {
                        ob_start();
                        var_dump($item);
                        $contents = ob_get_contents();
                        ob_end_clean();
                        $this->logger->warning("Aktywna aukcja nie posiada tytulu: ".$contents,'ebay');
                        continue;

                    }

                    $this->data['active'][] = array(
                        'Title' => $item->Title,
                        'ViewItemURL' => $item->ListingDetails->ViewItemURL,
                        'Viewers'  => '',
                        'Watchers' => isset($item->WatchCount) ? $item->WatchCount : NULL,
                        'Bids' => '', isset($item->SellingStatus->BidCount) ? $item->SellingStatus->BidCount : NULL,
                        'ReservePrice' => isset($item->ReservePrice) ? $item->ReservePrice->_.' '.$item->ReservePrice->currencyID : NULL,
                        'ReserveMet' => isset($item->SellingStatus->ReserveMet) ? $item->SellingStatus->ReserveMet : NULL,
                        'CurrentPrice' => $item->SellingStatus->CurrentPrice->_.' '.$item->SellingStatus->CurrentPrice->currencyID,
                        'StartPrice' => isset($item->StartPrice) ? $item->StartPrice->_.' '. $item->StartPrice->currencyID : NULL,
                        'TimeLeft' => $time_left,
                        'QuantityAvailable' => $item->QuantityAvailable,
                        'buyitnow' => $buyitnow,
                        'HighBidder' => isset($item->SellingStatus->HighBidder) ? $item->SellingStatus->HighBidder->UserID . '( '.$item->SellingStatus->HighBidder->FeedbackScore.' ) ' : NULL,
                        'ShippingServiceCost' => isset($item->ShippingDetails->ShippingServiceOptions->ShippingServiceCost) ? $item->ShippingDetails->ShippingServiceOptions->ShippingServiceCost->_ . ' '.$item->ShippingDetails->ShippingServiceOptions->ShippingServiceCost->currencyID  : 'Darmowa dostawa',
                        'end_action' => $this->url->link('ebay/debayseller/end', 'token=' . $this->session->data['token'] . '&item_id='.$item->ItemID .$url, 'SSL'),


                    );
             }
        }
        else
        {
              $this->data['error']['no_data'] = 'Brak danych';
        }


        $this->template = 'ebay/debay_active.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
            'ebay/debaysellermenu',
        );

        $this->response->setOutput($this->render());
    }

    public function unsold()
    {



        $this->load->model('ebay/debay');

        $url = '';

        $this->data['date_sort'] = $this->url->link('ebay/debayseller/unsold', 'token=' . $this->session->data['token'] . '&sort=EndTime' .$url, 'SSL');
        $this->data['price_sort'] = $this->url->link('ebay/debayseller/unsold', 'token=' . $this->session->data['token'] . '&sort=Price' .$url, 'SSL');

        if(isset($this->request->get['sort']))
        {
            $sort = $this->request->get['sort'];

            if($sort == 'EndTime')
            {
                $this->data['date_sort'] = $this->url->link('ebay/debayseller/unsold', 'token=' . $this->session->data['token'] . '&sort=EndTimeDescending' .$url, 'SSL');
            }

            if($sort == 'EndTimeDescending')
            {
                $this->data['date_sort'] = $this->url->link('ebay/debayseller/unsold', 'token=' . $this->session->data['token'] . '&sort=EndTime' .$url, 'SSL');
            }

            if($sort == 'Price')
            {
                $this->data['price_sort'] = $this->url->link('ebay/debayseller/unsold', 'token=' . $this->session->data['token'] . '&sort=PriceDescending' .$url, 'SSL');;
            }

            if($sort == 'PriceDescending')
            {
                $this->data['price_sort'] = $this->url->link('ebay/debayseller/unsold', 'token=' . $this->session->data['token'] . '&sort=Price' .$url, 'SSL');
            }

        }
        else
        {


            $sort = false;
        }

        $response = $this->model_ebay_debay->getSalesfromEbay('unsold',$sort);



        if(isset($response->UnsoldList->ItemArray->Item))
        {
            if(is_array($response->UnsoldList->ItemArray->Item))
            {
                $items =   $response->UnsoldList->ItemArray->Item;
            }
            else
            {
                $items = array( 0 => $response->UnsoldList->ItemArray->Item);
            }



            $this->data['unsold'] = array();


            $this->load->model('tool/image');

            foreach($items as $item)
            {

                if(!isset($item->Title))
                {
                    ob_start();
                    var_dump($response);
                    $contents = ob_get_contents();
                    ob_end_clean();
                    $this->logger->warning("Nie można uzyskac listy sprzedanych: ".$contents,'ebay');
                    continue;

                }


                $tmp = explode('T',$item->ListingDetails->EndTime);

                $end_time = $tmp[0];
                $buyitnow = $this->isbuyitnow($item);


                $url = '';

                $this->data['unsold'][] = array(
                    'Title' => $item->Title,
                    'ViewItemURL' => $item->ListingDetails->ViewItemURL,
                    'Viewers'  => '',
                    'Watchers' => isset($item->WatchCount) ? $item->WatchCount : NULL,
                    'Bids' => '', isset($item->SellingStatus->BidCount) ? $item->SellingStatus->BidCount : NULL,
                    'ReservePrice' => isset($item->ReservePrice) ? $item->ReservePrice->_.' '.$item->ReservePrice->currencyID : NULL,
                    'ReserveMet' => isset($item->SellingStatus->ReserveMet) ? $item->SellingStatus->ReserveMet : NULL,
                    'CurrentPrice' => $item->SellingStatus->CurrentPrice->_.' '.$item->SellingStatus->CurrentPrice->currencyID,
                    'StartPrice' => isset($item->StartPrice) ? $item->StartPrice->_.' '. $item->StartPrice->currencyID : NULL,
                    'EndTime' => $end_time,
                    'QuantityAvailable' => $item->QuantityAvailable,
                    'buyitnow' => $buyitnow,
                    'HighBidder' => isset($item->SellingStatus->HighBidder) ? $item->SellingStatus->HighBidder->UserID . '( '.$item->SellingStatus->HighBidder->FeedbackScore.' ) ' : NULL,
                    'ShippingServiceCost' => isset($item->ShippingDetails->ShippingServiceOptions->ShippingServiceCost) ? $item->ShippingDetails->ShippingServiceOptions->ShippingServiceCost->_ . ' '.$item->ShippingDetails->ShippingServiceOptions->ShippingServiceCost->currencyID  : 'Darmowa dostawa',
                    'resell' => $this->url->link('ebay/debayproduct/resell', 'token=' . $this->session->data['token'] . '&item_id='.$item->ItemID .$url, 'SSL'),


                );
            }
        }
        else
        {
            $this->data['error']['no_data'] = 'Brak danych';
        }


        $this->template = 'ebay/debay_unsold.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
            'ebay/debaysellermenu',
        );

        $this->response->setOutput($this->render());
    }

    public function all()
    {



        $this->load->model('ebay/debay');

        $this->model_ebay_debay->getSalesfromEbay('all');


        $this->template = 'ebay/debay_seller.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
            'ebay/debayjs'
        );

        $this->response->setOutput($this->render());
    }

    private function decodetime($time)
    {
        $tmp = preg_split('/[a-zA-Z]+/',$time);



        if(isset($tmp[0]) AND isset($tmp[1]) AND isset($tmp[2]))
        {

            if(strpos($time,'PT')===false)
            {
                if(strpos($time,'H')===false)
                {
                    $time_left = $tmp[1].' d 00 h';
                }
                else
                {

                    $time_left = $tmp[1].' d '.$tmp[2].' h';
                }

            }
            else
            {
                $time_left = '<span style="color:red;" >'.$tmp[1].' h '.$tmp[2].' m</span>';
            }

        }
        else
        {
            $time_left = '';
        }

        return $time_left;
    }

    private function isbuyitnow($item)
    {
        $buyitnow = FALSE;

        if(isset($item->ListingType) AND ($item->ListingType=='FixedPriceItem' OR $item->ListingType=='Chinese'))
        {

            $buyitnow = $this->model_tool_image->resize('buynow.gif',54,15);
        }

        return $buyitnow;
    }

    public function end()
    {

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

            $this->load->model('ebay/debay');

            if(!isset($this->request->post['ItemID']))
            {
                ob_start();
                var_dump($this->request->post);
                $contents = ob_get_contents();
                ob_end_clean();
                $this->logger->warning("Brak id aukcji przy probie usunięcia: ".$contents,'ebay');

                $this->data['success'] = false;

            }
            elseif(!isset($this->request->post['EndingReason']))
            {
                ob_start();
                var_dump($this->request->post);
                $contents = ob_get_contents();
                ob_end_clean();
                $this->logger->warning("Brak przyczny aukcji przy probie usunięcia: ".$contents,'ebay');

                $this->data['success'] = false;

            }
            else
            {
                $response = $this->model_ebay_debay->getEndAuction($this->request->post['ItemID'],$this->request->post['EndingReason']);

                $this->data['success'] = true;
            }




        }
        else
        {

            $this->data['EndingReason'] = array(
                'Incorrect' => "Bład przy wystawianiu aukcji",
                'LostOrBroken' => "Przedmiot uległ zniesczeniu lub uszkodzeniu",
                'NotAvailable' => "przedmiot nie jest już dostepny",
            );

            $this->data['ItemID'] = $this->request->get['item_id'];

            $url = '';
            $this->data['action'] = $this->url->link('ebay/debayseller/end', 'token=' . $this->session->data['token'] .$url, 'SSL');

            $this->data['success'] = false;
        }


        $this->template = 'ebay/debay_end.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
            'ebay/debaysellermenu',
        );

        $this->response->setOutput($this->render());

    }

    public function orderdetails()
    {


           if(isset($this->request->get['order_id']))
           {
               $order_id = $this->request->get['order_id'];

               $params = array(
                   'Version' => 831,
                   'OrderIDArray' => array('OrderID' => $order_id),

               );

               $method = 'GetOrderTransactions';

               try{

                   $resp =  debay::sendRequest($method,$params);

                   if(isset($resp->OrderArray->Order)){

                   $order = $resp->OrderArray->Order;

                   $ship_addres = $order->ShippingAddress;

                   $ship_service = $order->ShippingServiceSelected;

                   $paid_time = explode('T',$order->PaidTime);

                   $items =  array();

                   foreach($order->TransactionArray->Transaction as $transaction)
                   {

                       $items[] = array(
                           'ItemID' => $transaction->Item->ItemID,
                           'QuantityPurchased' => $transaction->QuantityPurchased,
                           'Price' => ($transaction->TransactionPrice->_).' '.$transaction->TransactionPrice->currencyID,
                           'Total' => ((int)$transaction->TransactionPrice->_ * (int)$transaction->QuantityPurchased).' '.$transaction->Item->SellingStatus->CurrentPrice->currencyID,




                       );

                   }

                   $this->data['order'] = array(
                       'PaymentMethod' => $order->CheckoutStatus->PaymentMethod,
                       'PaidTime' => $paid_time[0],
                       'SellerEmail' => $order->SellerEmail,
                       'ShippingAddress' => array(
                             'Name' => $ship_addres->Name,
                           'Street1' => $ship_addres->Street1,
                           'Street2' => $ship_addres->Street2,
                           'CityName' => $ship_addres->CityName,
                           'StateOrProvince' => $ship_addres->StateOrProvince,
                           'Country' => $ship_addres->Country,
                           'CountryName' => $ship_addres->CountryName,

                           'Phone' => $ship_addres->Phone,
                           'PostalCode' => $ship_addres->PostalCode,
                           'AddressID' => $ship_addres->AddressID,
                           'CountryName' => $ship_addres->CountryName,
                       ),

                       'ShippingServiceSelected' => array(
                            'ShippingService' => $ship_service->ShippingService,
                            'Cost' => $ship_service->ShippingServiceCost->_.' '.$ship_service->ShippingServiceCost->currencyID,
                       ),

                       'Subtotal' => $order->Subtotal->_.' '.$order->Subtotal->currencyID,
                       'Handling' => ((int)$order->Total->_ - (int)$order->Subtotal->_).' '.$order->Subtotal->currencyID,
                       'Total' => $order->Total->_.' '.$order->Total->currencyID,

                       'BuyerUserID' => $order->BuyerUserID,
                       'OrderID' => $order->OrderID,

                       'items' => $items,


                   );



                   }else{

                       ob_start();
                       var_dump($resp);
                       $contents = ob_get_contents();
                       ob_end_clean();
                       $this->logger->warning("Nie udało sie uzyskac infomacji o zmuwieniu: ".$contents,'ebay');


                   }

               }catch (Exception $e)
               {
                   $this->data['error'] = $e->getMessage();
               }
           }
           else
           {

               ob_start();
               var_dump($this->request->post);
               $contents = ob_get_contents();
               ob_end_clean();
               $this->logger->warning("Brak id zamówienia: ".$contents,'ebay');

               $this->data['error'] = 'Brak ID zamówienia';
           }

        $this->template = 'ebay/debay_orderdetails.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
            'ebay/debaysellermenu',
        );

        $this->response->setOutput($this->render());





    }

    public function messages()
    {
        $this->load->model('ebay/debay');



        $url = '';




        if(isset($this->request->get['page']))
        {
            $page = $this->request->get['page'];
        }
        else
        {
            $page  = 1;
        }

        $limit = 15;

        $type = 'ReturnSummary';

        $response = $this->model_ebay_debay->getMessagesFromEbay($type);


        $product_total = (int)$response->Summary->FolderSummary[0]->NewMessageCount;

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('ebay/debayseller/messages', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();

        $type = 'ReturnHeaders';

        $response = $this->model_ebay_debay->getMessagesFromEbay($type,true,$page,$limit);

        $messages = $response->Messages->Message;

        $this->data['messages'] =  array();

        $url = '';

        if(!empty($messages))
        {
              foreach($messages as $message)
              {
                    // data otrzymania, od kogo i nagłówek, załącznik
                  $tmp = explode('T',$message->ReceiveDate);

                  $receive_date = array_shift($tmp);

                  $tmp2 = explode('T',$message->ExpirationDate);

                  $exp_date = array_shift($tmp2);

                  if(!isset($message->Sender))
                  {
                      ob_start();
                      var_dump($message);
                      $contents = ob_get_contents();
                      ob_end_clean();
                      $this->logger->warning("Brak nadawcy w wiadomości: ".$contents,'ebay');
                  }

                  $this->data['messages'][] = array(
                       'Sender' => $message->Sender,
                       'Subject' => $message->Subject,
                       'MessageID' => $message->MessageID,
                       'Read' => $message->Read,
                       'ReceiveDate' => $receive_date,
                       'ExpirationDate' => $exp_date,
                       'ResponseURL' =>  isset($message->ResponseDetails->ResponseURL) ? $message->ResponseDetails->ResponseURL : NULL,
                       'MessageType' => isset($message->MessageType) ? $message->MessageType : NULL,
                       'Replied' => $message->Replied,
                       'view' => $this->url->link('ebay/debayseller/messageview', 'token=' . $this->session->data['token'].'&message_id='.$message->MessageID .$url, 'SSL'),

                  );
              }




        }
        else
        {
            $this->data['error'] = 'Brak wiadomości do wyswietlenia';
        }



        $this->template = 'ebay/debay_messages.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
            'ebay/debaysellermenu',
        );

        $this->response->setOutput($this->render());
    }

    public function messageview()
    {

        error_reporting(E_ALL);
        ini_set('display_errors', '1');



           if(isset($this->request->get['message_id']))
           {
               $message_id = $this->request->get['message_id'];

               $this->load->model('ebay/debay');

               $response = $this->model_ebay_debay->getSingleMessageFromEbay($message_id);

               if(!isset($response->Messages->Message))
               {
                   ob_start();
                   var_dump($response);
                   $contents = ob_get_contents();
                   ob_end_clean();
                   $this->logger->warning("Nie udalo sie odzyskac wiadomosci: ".$contents,'ebay');
               }

               $message = $response->Messages->Message;

               $tmp = explode('T',$message->ReceiveDate);
               $date = array_shift($tmp);

               $this->data['message'] = array(
                   'Sender' => $message->Sender,
                   'Subject' => $message->Subject,
                   'Text' => $message->Text,
                   'ReceiveDate' => $date,
                   'Content' => $message->Content,
               );


           }
           else
           {
               $this->data['error'] = "Brak wiadomości do wyświetlenia";
           }

        $this->template = 'ebay/debay_message_view.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
            'ebay/debaysellermenu',
        );

        $this->response->setOutput($this->render());
    }
}