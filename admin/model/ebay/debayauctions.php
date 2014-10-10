<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 25.07.13
 * Time: 10:26
 * To change this template use File | Settings | File Templates.
 */

class ModelEbayDebayAuctions extends Model{

    /*
     * sprawdza zamowienia z ebay, porwnuje z tablica auction i znajduje odpowiednie product id i nastepnie obniza ich stan o przedana ilosc
     */

    public function checkauctions()
    {
        $this->load->model('ebay/debay');

        /*
         * zaciaga liste aukcji skojarzony z product_id
         */


        /*
         * zaciaga zamowienia z ebay
         */
        $response = $this->model_ebay_debay->getSalesfromEbay('sold');





        $orders = array();


        if(isset($response->SoldList->OrderTransactionArray->OrderTransaction))
        {

            $transactions = $response->SoldList->OrderTransactionArray->OrderTransaction;

            foreach($transactions as $transaction)
            {

                /*
                 * niepełne zamowienia, brac je pod uwage ? moga nie byc zakonczone i niepotrzebnie odejmie sie ze stanu
                 * i niepotrzenie doda sie do zamuwien opencart
                 */
                if(isset($transaction->Transaction))
                {

                    $trans = $transaction->Transaction;

                    $item = $trans->Item;

                 // @todo przeymslec to z Dawidem
                 //   $items[] = array( 'ItemID' => $item->ItemID,  'Quantity' => $trans->QuantityPurchased, 'TransactionID' => $trans->TransactionID );
                }

                /*
                 * pełne zamowienie po przejściu przez kasę
                 */
                if(isset($transaction->Order))
                {
                    $order = $transaction->Order;

                    $orders[] = $order->OrderID;

                }

            }


            }




            if(!empty($orders))
            {
                foreach($orders as $order)
                {



                     $this->model_ebay_debay->saveOrder($order);


                }
            }







    }
}