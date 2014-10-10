<?php

class ControllerAllegroAllegrocron extends Controller{

    private $allegro;

    public function index()
    {

        error_reporting(E_ALL);

        ini_set('display_errors', '1');


          $this->allegro = $this->getAllegro();

          // pobieramy scaigniete z allegro
        $soldItems = $this->allegro->getSoldItems();

        $this->load->model('allegro/auctions');

        $items = array();





        foreach($soldItems['sold-items-list'] as $soldItem)
        {
               $item = new stdClass();
            $item->item_id = $soldItem->{'item-id'};
            $item->quantity = $soldItem->{'item-sold-quantity'};
            $item->user_id = $soldItem->{'item-highest-bidder'}->{'user-id'};


            $item->ended = false;

            if($soldItem->{'item-end-time-left'}=='ended')
            {
                $item->ended = true;
            }

            $items[] = $item;
        }

        // @todo trzeba zapisac jakos juz sprawdzone, u umiec odzielic nowe niespardzone od sprawdzonych


        foreach($items as $key => $item)
        {
            /*
             * sprawdza czy został oznaczony juz jako sprzedany
             *
             */
            $result = $this->model_allegro_auctions->checkIfSold($item->item_id);
            /*
             * jesli tak wyrzuca go z listy
             */
             if(!$result)
             {
                 unset($items[$key]);
             }
             else
             {
                 // jesli nie to przypisuje product_id
                 $items[$key]->product_id = $result;
             }

            // dodatkowo trzeba jescze sprawdzac nie zakonczone sprzedaze ale juz sprawdzone np: dany item_id jest caly czas w sprzedazy
            // ale dzis sprzedalo sie jedno, wczoraj dwa itp, sprawdzam  item_id + item-sold-quantity + user_id (ten co kupil ) zeby wyruznic juz sprawdzone

            $result = $this->model_allegro_auctions->findOrderByItemIdAndQuantityAndUser($item->item_id,$item->quantity,$item->user_id);

            if($result)
            {
                unset($items[$key]);
            }


        }

        // @todo trzeba obnizyc stan produktu, item_id == product_id
        foreach($items as $key => $item)
        {
            $this->model_allegro_auctions->lowerProductQuantity($item->product_id,$item->quantity);



            $this->model_allegro_auctions->addOrder($item->item_id,$item->quantity,$item->user_id);





            if($item->ended)
            {
                $this->model_allegro_auctions->markAsSold($item->item_id);
            }
        }


        // zapisz transakcje allegro

        $transactions_payu = $this->allegro->getTransactions(1);

        $transactions_bank_transfer = $this->allegro->getTransactions(2);

        $transactions_cod = $this->allegro->getTransactions(4);

        $transactions = array_merge($transactions_payu->{'transaction-ids'},$transactions_bank_transfer->{'transaction-ids'});

        $transactions = array_merge($transactions,$transactions_cod->{'transaction-ids'});

        /*
         * @todo jak odruznic stare transakcje od nowych ?
         */
        foreach($transactions as $transaction)
        {
            /*
             * sprawdza czy ta transakcja jest juz w bazie danych
             */
            $query = $this->db->query("SELECT * FROM `allegro_transactions` WHERE `transaction_id`='".$transaction."' ");

            if(!$query->row)
            {
                $data = $this->allegro->getTransactionsData(array($transaction));



                  if(!empty($data))
                  {

                  $result = $this->model_allegro_auctions->saveTransactionAsOrder($data[0],$this->allegro);

                  if($result)
                  {
                       $this->db->query("INSERT INTO `allegro_transactions` SET `transaction_id`='".$transaction."', checked = '1' ");
                  }

                  }

              }


        }




    }


    private function getAllegro(){

        include_once(DIR_APPLICATION.'controller/allegro//allegro.php');

        // konfiguracja testowa
        $config = array(
            'key' => '61602d03',
            'login' => 'testerski3',
            'password' => 'Episode666',
            'country' => 228,
            'version' => '57324469',
        );

        // drugie testowe
        $config3 = array(
            'key' => '61602d03',
            'login' => 'testerski4',
            'password' => 'tester123',
            'country' => 228,
            'version' => '57324469',
        );

// konfiguracja prawdziwego allegro
        $config2 = array(
            'key' => '61602d03',
            'login' => 'miechuliv@tlen.pl',
            'password' => 'Episode666',
            'country' => 1,
            'version' => '52437458',
        );

        $config4 = array(
            'key' => '97c6a3da59',
            'login' => 'dawidrza',
            'password' => 'kociu19910930',
            'country' => 1,
            'version' => '10327111',
        );

        $config_docelowy =array(
            'key' => '3fff13b7',
            'login' => 'gerdus',
            'password' => 'Dominika1',
            'country' => 1,
            'version' => '60540373',
        );


        $allegro = new allegro($config_docelowy);

        $allegro->login();

        return $allegro;
    }
}
