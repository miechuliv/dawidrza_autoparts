<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mieszko Malawski
 * Date: 20.06.13
 * Time: 17:08
 * To change this template use File | Settings | File Templates.
 */

class ControllerAllegroSeller extends Controller{


    private function getAllegro(){
        include_once(DIR_APPLICATION . '../admin/controller/allegro/allegro.php');

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

    public function index(){

        try{

            $allegro = $this->getAllegro();


            $currentItems = $allegro->getCurrentSell();

            $this->data['currentItems'] = $currentItems['sell-items-list'];

            $this->data['typy_licytacji']=array(
                1 => 'Kup Teraz!' , 2 => 'ostateczna cena w licytacji' , 3 => 'cena wywoławcza w licytacji' , 4 => 'cena minimalna w licytacji',
            );
        }
        catch(Exception $e)
        {
            $this->data['error_msg'] = $e->getMessage();
        }

        $this->template = 'allegro/seller.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
            'allegro/sellermenu',
        );

        $this->response->setOutput($this->render());


    }

    public function sold(){



        try{

            $allegro = $this->getAllegro();

            $soldItems = $allegro->getSoldItems();

            $this->data['soldItems']= $soldItems["sold-items-list"];

            $this->data['resell'] = $this->url->link('allegro/seller/resell','token=' . $this->session->data['token']);

            $this->data['typy_licytacji']=array(
                1 => 'Kup Teraz!' , 2 => 'ostateczna cena w licytacji' , 3 => 'cena wywoławcza w licytacji' , 4 => 'cena minimalna w licytacji',
            );


        }
        catch(Exception $e){
            $this->data['error_msg'] = $e->getMessage();
        }

        $this->template = 'allegro/sold.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
            'allegro/sellermenu',
        );

        $this->response->setOutput($this->render());
    }

    public function unsold()
    {


        try{

            $allegro = $this->getAllegro();

            $unsoldItems = $allegro->getNotSold();



            $unsoldItemsCount = $unsoldItems["not-sold-items-counter"];

            $unsoldItems = $unsoldItems["not-sold-items-list"];

            $this->data['unsoldItems'] = $unsoldItems;

            $this->data['resell'] = $this->url->link('allegro/seller/resell','token=' . $this->session->data['token']);

            $this->data['typy_licytacji']=array(
                1 => 'Kup Teraz!' , 2 => 'ostateczna cena w licytacji' , 3 => 'cena wywoławcza w licytacji' , 4 => 'cena minimalna w licytacji',
            );


        }
        catch(Exception $e){
            $this->data['error_msg'] = $e->getMessage();
        }

        $this->template = 'allegro/unsold.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
            'allegro/sellermenu',
        );

        $this->response->setOutput($this->render());
    }

    public function resell()
    {
        $item_id = $this->request->get['item_id'];



        try{

            $allegro = $this->getAllegro();

            $item_ids = array();

            $item_ids[] = $item_id;

            $duration = 7;

            $result = $allegro->resell($item_ids,$duration);

           // var_dump($result);

            if(!empty($result['items-sell-failed']) OR !empty($result['items-sell-not-found']))
            {

                $this->data['msg_resell'] = 'Bład podczas ponownego wystawiania, aukcja nie została wystawiona';
            }
            else
            {
                $this->data['msg_resell'] = 'Aukcja została wystawiona!';

                if(isset($result['items-sell-again'][0]->{'sell-item-info'}))
                {
                    $this->data['auction_cost'] = $result['items-sell-again'][0]->{'sell-item-info'};
                }


            }




        }
        catch(Exception $e)
        {
            $this->data['error_msg'] = $e->getMessage();
        }



        $this->template = 'allegro/resell.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
            'allegro/sellermenu',
        );

        $this->response->setOutput($this->render());


    }

    public function incomingpay()
    {
        try{

            $allegro = $this->getAllegro();

            $data = $allegro->getIncomingPAYU();

            $this->data['results'] = $data;

        }
        catch(Exception $e){
            $this->data['error_msg'] = $e->getMessage();
        }

        $this->template = 'allegro/incomingpay.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
            'allegro/sellermenu',
        );

        $this->response->setOutput($this->render());
    }

    public function outgoingpay()
    {
        try{

            $allegro = $this->getAllegro();

            $data = $allegro->getMyPAYU();



        }
        catch(Exception $e){
            $this->data['error_msg'] = $e->getMessage();
        }

        $this->template = 'allegro/outgoingpay.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
            'allegro/sellermenu',
        );

        $this->response->setOutput($this->render());
    }

    public function comments()
    {
        try{

            $mode = $this->request->get['mode'];

            $allegro = $this->getAllegro();

            $data = $allegro->getComments($mode);

            $comments = array();



            foreach($data as $feed)
            {
                 $row = $feed->{'feedback-array'};


                 $comments[] = array(
                     'commenting_user_id' => $row[0],
                     'commented_user_id' => $row[1],
                     'date' => $row[2],
                     'type' => $row[3],
                     'content' => $row[4],
                     'offer_id' => $row[5],
                     'comment_id' => $row[6],
                     'respond_date' => $row[7],
                     'respond_content' => $row[8],
                     'side' => $row[9],
                     'commenter_name' => $row[10],
                     'commenter_pts' => $row[11],
                     'commenter_seller' => $row[12],
                 );


            }

            $this->data['mode'] = $mode;

            $this->data['comment_types'] = array(
                1 => 'Pozytywny',
                2 => 'Neutralny',
                3 => 'Negatywny',
            );

            $this->data['sides'] = array(
               'SELLER' => 'Sprzedający',
                'BUYER' => 'Kupujący'
            );

            $this->data['comments'] = $comments;


        }
        catch(Exception $e){
            $this->data['error_msg'] =  $e->getMessage();
        }

        $this->template = 'allegro/comments.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
            'allegro/sellermenu',
        );

        $this->response->setOutput($this->render());
    }


    public function kontrahent()
    {

        if($this->request->server['REQUEST_METHOD'] == 'POST')
        {

            if($this->validKontrahent($this->request->post)){

                try{



                    $allegro = $this->getAllegro();

                    $auction=array();

                    $auction[]=$this->request->post['auction_id'];

                    $result = $allegro->getKontrahent($auction);

                    if(isset($result[0]->{'users-post-buy-data'}[0]))
                    {


                        $user_data = $result[0]->{'users-post-buy-data'}[0]->{"user-data"};

                        $user_send_to = $result[0]->{'users-post-buy-data'}[0]->{"user-sent-to-data"};

                       // $user_bank_accounts = $result[0]->{'users-post-buy-data'}[0]->{"user-bank-accounts"};

                        $this->data['results']=array(
                            'dane' => $user_data,
                            'wysylka' => $user_send_to,
                        //    'konto' => $user_bank_accounts
                        );
                    }

                }
                catch(Exception $e){

                    $this->data['error_msg'] =  $e->getMessage();
                }
            }

        }

        if(!empty($this->form_error))
        {
            $this->data['form_error'] = $this->form_error;
        }

        $this->data['form_action'] = $this->url->link('allegro/seller/kontrahent','token=' . $this->session->data['token']);

        $this->template = 'allegro/kontrahent.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
            'allegro/sellermenu',
        );

        $this->response->setOutput($this->render());
    }

    private $form_error = array();

    private function validKontrahent($data)
    {

        if(!isset($data['auction_id']))
        {
            $this->form_error[]="Proszę podać numer aukcji";
        }

        if(empty($this->form_error))
        {
            return true;
        }
        else
        {
            return false;
        }

    }

}