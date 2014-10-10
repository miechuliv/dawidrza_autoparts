<?php
/*
 * klasa pozwalajaca na wyświetalanie sczegułowych błędów MUSI byc wyłączona w trybie produkcyjnym,
 * sledzeenie:
 *  sesji
 *  get
 *  post
 *  dane kontrollera
 *  zapytania sql
 *
 *  mozna wyswietlić na ekranie lub za pomocą firePHP
 *  @todo zapisywanie do loga
 *  @todo wyswietlanie czasów wykonania
 *  @todo dodanie sledzenia zdarzeń
 */

class Debugger {

    private $request;
    private $session;
    private $queries = array();
    private $controller;
    private $log;
    private $firePHP;
    private $customVars = array();
    private $excludeVars = array('countries');
    private static $events = array();
    private static $baseTime;
    private $show;

    function __construct($time = false, $show = false)
    {

        $this->show = $show;

        if($time)
        {
            self::$baseTime =  $time;
        }
        else
        {
           self::$baseTime = microtime(true);
        }

    }

    function __destruct()
    {
        if($this->show)
        {

           echo  $this->displayError();
        }

    }


    public function addCustomVar($name,&$customVar)
    {
        $this->customVars[$name] = &$customVar;
    }

    public static function addEvent($name)
    {
        self::$events[$name] = microtime(true) - (float)self::$baseTime;
    }

    /*
     * pozwala okreslić ktore z danych kontrollera nie mają się wyświetlać
     * param string
     */
    public function addExcludedVar($name)
    {
        $this->excludeVars[] = $name;
    }

    public function setFirePHP($firePHP)
    {
        $this->firePHP = $firePHP;
    }



    public function setLog($log)
    {
        $this->log = $log;
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function setSession($session)
    {
        $this->session = $session;
    }

    public function addQuery($info)
    {
        $this->queries[] = $info;
    }


    /*
     * po stworzeniu kontrollera można go tutaj przypisć
     * param Controller
     */
    public function addController($controller)
    {
        $this->controller = $controller;
    }

    /*
     * generujemy display z całym opisem i zmiennymi
     * return string
     */
    public function displayError($error = false)
    {
            $html = '<style>.d_row{ color:blue; }</style>';
            $html .= '<style>.d_head{ color:green; }</style>';
            $html .= '<style>.d_main{ color:red; font-size: 20px; }</style>';
            $html .= '<div>';
            $html .= '<div>'.$error.'</div>';
            $html .= '<table>';

            if($this->request instanceof Request AND !empty($this->request->get))
            {
                $html .= '<tr class="d_main"><td>Tablica GET</td><td>wartości:</td></tr>';

                $html .= $this->displayRecursiveArray($this->request->get);
            }

        if($this->request instanceof Request AND !empty($this->request->post))
        {
            $html .= '<tr class="d_main"><td>Tablica POST</td><td>wartości:</td></tr>';

            $html .= $this->displayRecursiveArray($this->request->post);
        }

        if($this->session instanceof Session AND !empty($this->session->data))
        {
            $html .= '<tr class="d_main"><td>Sesja</td><td>wartości:</td></tr>';

            $html .= $this->displayRecursiveArray($this->session->data);
        }





        $e = new Exception;
        $s = $e->getTraceAsString();
        $c = explode('#',$s);

        $html .= '<tr class="d_main"><td>Stack</td><td>wartości:</td></tr>';
        foreach($c as $key => $func)
        {
            $html .= '<tr><td>'.$key.'</td><td>'.$func.'</td></tr>';
        }

        $html .= '<tr class="d_main"><td>Zapytnia sql</td><td></td></tr>';
        foreach($this->queries as $key => $q)
        {
            $html .= '<tr><td>'.$key.'</td><td>'.$q.'</td></tr>';
        }

        $html .= '<tr class="d_main"><td>Dane dodatkowe</td><td></td></tr>';
        if(!empty($this->customVars))
        {

                $html .= $this->displayRecursiveArray($this->customVars);

        }



        if($this->controller instanceof Controller)
        {
            $data = $data = $this->controller->getAllData();
            $this->excludeControllerData($data);
            $html .= '<tr class="d_main"><td>Dane kontrollera</td><td>wartości:</td></tr>';

            $html .= $this->displayRecursiveArray($data);
        }

        if(!empty(self::$events))
        {
            $html .= '<tr class="d_main"><td>Zdarzenia</td><td></td></tr>';
            $html .= $this->displayRecursiveArray(self::$events);

        }

        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }

    private function excludeControllerData(&$data)
    {
        foreach($data as $key => $val)
        {
             if(in_array($key,$this->excludeVars))
             {
                 unset($data[$key]);
             }
        }
    }



    /*
     * param array
     * return string
     */
    private function displayRecursiveArray($arr,$padding = 0)
    {

        $html = '<tr><td><table style="margin-left:'.$padding.'px;">';

        foreach($arr as $key => $ar)
        {
            if(is_array($ar))
            {
                 $html .= '<tr class="d_head" ><td>tablica </td><td>'.$key.'</td></tr>';
                 $html .= $this->displayRecursiveArray($ar,$padding+10);
            }
            else
            {
                 $html .= '<tr class="d_row" ><td>'.$key.' => </td><td>'.$ar.'</td></tr>';
            }
        }

        $html .= '</table></td><td></td></tr>';

        return $html;

    }

    /*
     * zapisujemy do loga
     */
    public function log($error)
    {

    }

    public function toFirebug($error = false)
    {

        ob_start();

        if($error)
        {
            $this->firePHP->log($error,'Błąd');
        }

        if($this->request instanceof Request AND !empty($this->request->get))
        {
            $this->firePHP->log($this->request->get,'tablica GET');
        }

        if($this->request instanceof Request AND !empty($this->request->post))
        {
            $this->firePHP->log($this->request->post,'tablica POST');
        }

        if($this->session instanceof Session AND !empty($this->session->data))
        {
            $this->firePHP->log($this->session->data,'Sesja');
        }

        //@todo nie wiedzić czemu nieda sie wyświetlić zaptań sql
       /* if(!empty($this->queries))
        {
            $this->firePHP->log($this->queries,'Zapytania SQL');
        } */

        if(!empty($this->customVars))
        {
            $this->firePHP->log($this->customVars,'Dodatkowe dane');
        }



        $e = new Exception;
        $s = $e->getTraceAsString();
        $c = explode('#',$s);

        if(!empty($c))
        {
            $this->firePHP->log($c,'Stack');
        }

        if(!empty(self::$events))
        {
            $this->firePHP->log(self::$events,'Zdarzenia');

        }

        if($this->controller instanceof Controller)
        {
            $data = $this->controller->getAllData();
            $this->excludeControllerData($data);

            $this->firePHP->log($data,'Dane kontrollera');
        }




    }



}