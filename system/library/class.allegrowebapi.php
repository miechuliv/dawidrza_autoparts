<?php
  /***************************************************************************************************************
   
   * allegrowebapi-php-class v1.1
   * Klasa zosta&#322;a w ca&#322;o&#347;ci wykonana przez firm&#281;:
   * NLDS-Group - Marketing & Promotion Agency
   * ul Potulicka 40/47, 70-234 Szczecinie
   * http://www.nlds-group.com
   * NIP: 9471841382, REGON: 320797634
   * Klasa PHP zosta&#322;a wykonana na potrzeby nowej platformy obs&#322;ugi sprzeda&#380;y allegro Sipoint.pl,
   * która jest w&#322;a&#347;cicielem praw autorskich i udost&#281;pnia kod w serwisie http://code.google.com/p/allegrowebapi-php-class/
   * na licencji: Creative Commons - Attribution-ShareAlike 3.0 - CC BY-SA 3.0
   * http://creativecommons.org/licenses/by-sa/3.0/pl/
   *
   + *************************************************************************************************************
   
   * WARUNKI KORZYSTANIA Z KLASY AllegroWebAPI.Class.PHP
   + *************************************************************************************************************
   *   Wolno:
   * ---------------------------------
   * - kopiowa&#263;, rozpowszechnia&#263;, odtwarza&#263; i wykonywa&#263; utwór
   * - tworzy&#263; utwory zale&#380;ne
   *   Na nast&#281;puj&#261;cych warunkach:
   * ---------------------------------
   * - Uznanie autorstwa - Utwór nale&#380;y oznaczy&#263; w sposób okre&#347;lony przez Twórc&#281; lub Licencjodawc&#281;.
   * - Na tych samych warunkach - Je&#347;li zmienia si&#281; lub przekszta&#322;ca niniejszy utwór, lub tworzy inny na jego podstawie,
   *   mo&#380;na rozpowszechnia&#263; powsta&#322;y w ten sposób nowy utwór tylko na podstawie takiej samej licencji.
   *   Ze &#347;wiadomo&#347;ci&#261;, &#380;e:
   * ---------------------------------
   * - Zrzeczenie - Ka&#380;dy z tych warunków mo&#380;e zosta&#263; uchylony, je&#347;li uzyska si&#281; zezwolenie w&#322;a&#347;ciciela praw autorskich.
   * - Public Domain - Je&#380;eli utwór lub jakiekolwiek jego elementy, zgodnie z prawem w&#322;a&#347;ciwym, nale&#380;&#261; do domeny publicznej,
   *   to licencja w &#380;aden sposób nie wp&#322;ywa na ten status prawny.
   *   http://wiki.creativecommons.org/Public_domain
   *
   *  Inne prawa - Licencja nie wp&#322;ywa w &#380;aden sposób na nast&#281;puj&#261;ce prawa:
   * ---------------------------------
   *  * Uprawnienia wynikaj&#261;ce z dozwolonego u&#380;ytku ani innych obowi&#261;zuj&#261;cych ogranicze&#324; lub wyj&#261;tków prawa autorskiego.
   *     http://wiki.creativecommons.org/Frequently_Asked_Questions#Do_Creative_Commons_licenses_affect_fair_use.2C_fair_dealing_or_other_exceptions_to_copyright.3F
   *  * Autorskie prawa osobiste autora;
   *     http://wiki.creativecommons.org/Frequently_Asked_Questions#I_don.E2.80.99t_like_the_way_a_person_has_used_my_work_in_a_derivative_work_or_included_it_in_a_collective_work.3B_what_can_I_do.3F
   *  * Ewentualne prawa osób trzecich do utworu lub sposobu wykorzystania utworu, takie jak prawo do wizerunku lub prawo do prywatno&#347;ci.
   *     http://wiki.creativecommons.org/Frequently_Asked_Questions#When_are_publicity_rights_relevant.3F
   *
   + *************************************************************************************************************
   * Uwaga - W celu ponownego u&#380;ycia utworu lub rozpowszechniania utworu nale&#380;y wyja&#347;ni&#263; innym warunki licencji, na której udost&#281;pnia si&#281; utwór.
   + *************************************************************************************************************
   * Klasa do obs&#322;ugi Allegro.pl
   * Dokumentacja oraz szczegó&#322;owe opisy
   * metod dost&#281;pnych w WebAPI: http://allegro.pl/webapi/
   + *************************************************************************************************************
   *
   * U&#380;ycie:
   * Aby rozpocz&#261;&#263; u&#380;ytkowanie klasy nale&#380;y sta&#322;e konfiguracyjne uzupe&#322;ni&#263; poprawnymi danymi.
   * Nazwy metod klasy AllegroWebAPI odpowiadaj&#261; nazwom metod dost&#281;pnych w WebAPI bez przedrostka "do"
   * (aby uzycka&#263; dost&#281;p do metody "doLogin" nale&#380;y pos&#322;u&#380;y&#263; si&#281; metod&#261; "Login").
   * Je&#347;li dana metoda wymaga podania takich parametrów jak: session-handle, country-id, webapi-key lub local-version
   * nale&#380;y te parametry pomin&#261;&#263; (zostan&#261; przes&#322;ane automatycznie).
   *
   * Zalogowanie do systemu i wyw&#322;anie przyk&#322;adowej metody:
   *
   * try {
   *    $allegro = new AllegroWebAPI();
   *    $allegro->Login();
   *    $cats_list = $allegro->GetCatsData();
   *    print_r($allegro->objectToArray($cats_list));
   * }
   * catch(SoapFault $fault) {
   *    print($fault->faultstring);
   * }
   *
   * W powy&#380;szym przyk&#322;adzie oast&#261;pi&#322;o logowanie do systemu oraz pobrana zosta&#322;a lista wszystkich kategorii aukcji.
   * Poniewa&#380; WebAPI w odpowiedzi zwraca obiekt w prosty sposób mo&#380;emy go przekonwertowa&#263; na tablic&#281; dzi&#281;ki
   * metodzie objectToArray(). Potrzebne parametry takie jak klucz WebAPI oraz kod kraju zosta&#322;y pobrane automatycznie.
   *
   +*************************************************************************************************************/



  class AllegroWebAPI {
      protected $_instance;
      protected $_config;
      protected $_session;
      protected $_client;
      protected $_local_version;
      /* Okre&#347;lenie kraju (1 = Polska) */
      const COUNTRY_CODE = 1;
	  
      /**
       * Zapis ustawie&#324; oraz po&#322;&#261;czenie z WebAPI
       */
      public function __construct() {
          
		  
		 // define('ALLEGRO_ID', '5818856');
         // define('ALLEGRO_LOGIN', 'miechowy');
         // define('ALLEGRO_PASSWORD', 'Episode666');
         // define('ALLEGRO_KEY', '61602d03');
         // define('ALLEGRO_COUNTRY', 1);
		  
		  
      }
	  
	  public function setInitial($allegro_id=0,$login=0,$pass=0,$webapi=0){
	  	
		  define('ALLEGRO_ID', $allegro_id);
          define('ALLEGRO_LOGIN', $login);
          define('ALLEGRO_PASSWORD', $pass);
          define('ALLEGRO_KEY', $webapi);
          // production
          define('ALLEGRO_COUNTRY', 1);
          // testing - i tak nie dziala nie odpowiednich kategorii itp ;)
          //define('ALLEGRO_COUNTRY', 228);


		  
		  $this->_config = array('allegro_id' => ALLEGRO_ID, 'allegro_key' => ALLEGRO_KEY, 'allegro_login' => ALLEGRO_LOGIN, 'allegro_password' => ALLEGRO_PASSWORD);
          $this->_client = new SoapClient('https://webapi.allegro.pl/uploader.php?wsdl');
		  
		  
		    
	  	
	  }
      
      public function GetSellFormFieldsForCategory($Cat) {
          set_time_limit(600) ;
          return $this->_client->doGetSellFormFieldsForCategory($this->_config['allegro_key'], self::COUNTRY_CODE, $Cat);
      }
      
      public function GetCategoryPath($Options) {
          return $this->_client->doGetCategoryPath($this->_config['allegro_key'], self::COUNTRY_CODE, $Options['category-id']);
      }

      /**********************************************************************************************************
       * Czarna lista (http://allegro.pl/webapi/documentation.php/theme/id,21)
       *********************************************************************************************************/
      /**
       *
       * Metoda pozwala na dodanie wskazanych u&#380;ytkowników do czarnej listy zalogowanego u&#380;ytkownika.
       * U&#380;ytkownicy dodani do czarnej listy nie mog&#261; kupowa&#263; &#380;adnych przedmiotów od u&#380;ytkownika,
       * na którego czarnej li&#347;cie si&#281; znajduj&#261;.
       * (http://allegro.pl/webapi/documentation.php/show/id,21)
       *
       * @param array $Users
       * @return array
       */
      public function AddToBlackList($Users) {
          $this->checkConnection();
          return $this->_client->doAddToBlackList($this->_session['session-handle-part'], $Users);
      }
      /**
       * Metoda pozwala na pobranie listy u&#380;ytkowników, którzy znajduj&#261; si&#281; na czarnej li&#347;cie zalogowanego u&#380;ytkownika.
       * (http://allegro.pl/webapi/documentation.php/show/id,45)
       *
       * @return array
       */
      public function GetBlackListUsers() {
          $this->checkConnection();
          return $this->_client->doGetBlackListUsers($this->_session['session-handle-part']);
      }
      /**
       
       * Metoda pozwala na usuni&#281;cie wskazanych u&#380;ytkowników z czarnej listy zalogowanego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,114)
       
       *
       
       * @param array $Users
       
       * @return array
       
       */
      public function RemoveFromBlackList($Users) {
          $this->checkConnection();
          return $this->_client->doRemoveFromBlackList($this->_session['session-handle-part'], $Users);
      }
      /**********************************************************************************************************
       
       * Dane kontrahentów (http://allegro.pl/webapi/documentation.php/theme/id,67)
       
       *********************************************************************************************************/
      /**
       
       * Metoda pozwala na pobranie pe&#322;nych danych kontaktowych kontrahentów z danej aukcji.
       
       * Metoda zwraca ró&#380;ne dane - w zale&#380;no&#347;ci od tego, czy zalogowany u&#380;ytkownik by&#322; sprzedaj&#261;cym (user-data, user-sent-to-data),
       
       * czy kupuj&#261;cym (user-data, user-bank-accounts, company-second-address) w danej aukcji.
       
       * W przypadku podania niepoprawnego identyfikatora aukcji, zostanie dla niego zwrócona pusta struktura.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,89)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetPostBuyData($Options) {
          $this->checkConnection();
          return $this->_client->doGetPostBuyData($this->_session['session-handle-part'], $Options);
      }
      /**
       
       * Metoda pozwala na pobranie wszystkich danych z wype&#322;nionych przez kupuj&#261;cych formularzy pozakupowych.
       
       * Metoda zwraca tak&#380;e szczegó&#322;owe informacje dot. p&#322;atno&#347;ci (realizowanych przez PzA),
       
       * powi&#261;zanych ze wskazanymi transakcjami, informacje nt. wybranego punktu odbioru oraz dane identyfikacyjne
       
       * dot. przesy&#322;ki zawieraj&#261;cej produkty sk&#322;adaj&#261;ce si&#281; na wskazane transakcje.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,141)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetPostBuyFormsData($Options) {
          $this->checkConnection();
          return $this->objectToArray($this->_client->doGetPostBuyFormsData($this->_session['session-handle-part'], $Options));
      }
      /**
       
       * Metoda pozwala na pobranie wszystkich danych z wype&#322;nionych przez kupuj&#261;cych (gdy metod&#281; wywo&#322;uje sprzedaj&#261;cy)
       
       * lub zalogowanego u&#380;ytkownika (gdy metod&#281; wywo&#322;uje kupuj&#261;cy) Formularzy Opcji Dostawy.
       
       * W przypadku gdy dla danej aukcji nie zosta&#322; wype&#322;niony FOD - zwracana jest pusta struktura.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,96)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetShipmentOptionsFormData($Options) {
          $this->checkConnection();
          return $this->_client->doGetShipmentOptionsFormData($this->_session['session-handle-part'], $Options['sof-user-type'], $Options['sof-items-id']);
      }
      /**
       
       * Metoda pozwala na pobranie warto&#347;ci identyfikatorów transakcji (zakupów sfinalizowanych wype&#322;nieniem formularza
       
       * pozakupowego przez kupuj&#261;cego) na podstawie przekazanych identyfikatorów aukcji. Uzyskane identyfikatory
       
       * transakcji mog&#261; by&#263; nast&#281;pnie wykorzystane np. do pobierania wype&#322;nionych formularzy pozakupowych za pomoc&#261;
       
       * metody doGetPostBuyFormsData. Metoda zwraca jedynie identyfikatory transakcji,
       
       * dla których - w ramach danej aukcji - wype&#322;nione zosta&#322;y przez kupuj&#261;cych formularze pozakupowe
       
       * (http://allegro.pl/webapi/documentation.php/show/id,121)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetTransactionsIDs($Options) {
          $this->checkConnection();
          return $this->_client->doGetTransactionsIDs($this->_session['session-handle-part'], $Options['items-id-array'], $Options['user-role']);
      }
      /**
       
       * Metoda pozwala na pobranie danych kontaktowych kupuj&#261;cych w aukcjach zalogowanego u&#380;ytkownika.
       
       * W przypadku podania b&#322;&#281;dnego identyfikatora aukcji, struktura jej odpowiadaj&#261;ca nie zostanie zwrócona.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,110)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function MyContact($Options) {
          $this->checkConnection();
          return $this->_client->doMyContact($this->_session['session-handle-part'], $Options['auction-id-list'], $Options['offset']);
      }
      /**********************************************************************************************************
       
       * Drzewo kategorii (http://allegro.pl/webapi/documentation.php/theme/id,43)
       
       *********************************************************************************************************/
      /**
       
       * Metoda pozwala na pobranie pe&#322;nego drzewa kategorii dost&#281;pnych we wskazanym kraju.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,46)
       
       *
       
       * @return array
       
       */
      public function GetCatsData() {
          return $this->_client->doGetCatsData(self::COUNTRY_CODE, '0', $this->_config['allegro_key']);
      }
      /**
       
       * Metoda pozwala na pobranie licznika kategorii dost&#281;pnych we wskazanym kraju.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,47)
       
       *
       
       * @return array
       
       */
      public function GetCatsDataCount() {
          return $this->_client->doGetCatsDataCount(self::COUNTRY_CODE, '0', $this->_config['allegro_key']);
      }
      /**
       
       * Metoda pozwala na pobranie w porcjach pe&#322;nego drzewa kategorii dost&#281;pnych we wskazanym kraju.
       
       * Domy&#347;lnie zwracanych jest 50 pierwszych kategorii. Rozmiar porcji pozwala regulowa&#263; parametr package-element,
       
       * a sterowanie pobieraniem kolejnych porcji danych umo&#380;liwia parametr offset.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,48)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetCatsDataLimit($Options) {
          return $this->_client->doGetCatsDataLimit(self::COUNTRY_CODE, '0', $this->_config['allegro_key'], $Options['offset'], $Options['package-element']);
      }
      /**********************************************************************************************************
       
       * Dziennik zdarze&#324; (http://allegro.pl/webapi/documentation.php/theme/id,63)
       
       *********************************************************************************************************/
      /**
       
       * Metoda pozwala na pobranie informacji z dziennika zdarze&#324; na temat zmian stanów (rozpocz&#281;cie,
       
       * zako&#324;czenie, z&#322;o&#380;enie oferty w aukcji z licytacj&#261;, zakup przez Kup Teraz!, zmiana w opisie)
       
       * aukcji zalogowanego u&#380;ytkownika lub wszystkich aukcji w serwisie. Zwracanych jest zawsze
       
       * 100 najnowszych informacji o zmianach (zaczynaj&#261;c od punktu podanego w parametrze starting-point),
       
       * posortowanych rosn&#261;co po czasie ich wyst&#261;pienia. W przypadku przekazania w parametrze starting-point
       
       * warto&#347;ci 0, zwróconych zostanie 100 chronologicznie najwcze&#347;niejszych zmian, do których dost&#281;p ma
       
       * jeszcze dziennik zdarze&#324; (zazwyczaj s&#261; to dane z ostatnich 8-9 dni). Aby sterowa&#263; pobieraniem kolejnych
       
       * porcji danych (tak aby dotrze&#263; do danych naj&#347;wie&#380;szych), nale&#380;y w parametrze starting-point przekazywa&#263;
       
       * warto&#347;&#263; row-id ostatniego (setnego) elementu, zwracanego w ramach danego wywo&#322;ania i robi&#263; to sukcesywnie,
       
       * dopóki w wyniku wywo&#322;ania nie otrzyma si&#281; porcji danych mniejszej ni&#380; 100 elementów (co b&#281;dzie &#347;wiadczy&#322;o,
       
       * &#380;e otrzymane dane s&#261; danymi naj&#347;wie&#380;szymi).
       
       * (http://allegro.pl/webapi/documentation.php/show/id,65)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetSiteJournal($Options) {
          $this->checkConnection();
          return $this->_client->doGetSiteJournal($this->_session['session-handle-part'], $Options['starting-point'], $Options['info-type']);
      }
      /**
       
       * Metoda pozwala na pobranie informacji z dziennika zdarze&#324; na temat liczby zmian w aukcjach zalogowanego
       
       * u&#380;ytkownika lub we wszystkich aukcjach w serwisie, od zdefiniowanego (w parametrze starting-point)
       
       * momentu (bierze po uwag&#281; chronologicznie najstarsze 10000 aukcji - zaczynaj&#261;c od podanego punktu startu).
       
       * Aby sterowa&#263; momentem rozpocz&#281;cia pobierania informacji o liczbie zmian (tak aby dotrze&#263; do danych naj&#347;wie&#380;szych),
       
       * nale&#380;y w parametrze starting-point przekazywa&#263; odpowiedni&#261; warto&#347;&#263; row-id,
       
       * zwracan&#261; w ramach wywo&#322;ania metody doGetSiteJournal.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,66)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetSiteJournalInfo($Options) {
          $this->checkConnection();
          return $this->_client->doGetSiteJournalInfo($this->_session['session-handle-part'], $Options['starting-point'], $Options['info-type']);
      }
      /**********************************************************************************************************
       
       * Informacje o u&#380;ytkowniku (http://allegro.pl/webapi/documentation.php/theme/id,64)
       
       *********************************************************************************************************/
      /**
       
       * Metoda pozwala na pobranie prywatnych danych (wraz z dodatkowymi danymi dla konta Firma)
       
       * z konta zalogowanego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,84)
       
       *
       
       * @return array
       
       */
      public function GetMyData() {
          $this->checkConnection();
          return $this->_client->doGetMyData($this->_session['session-handle-part']);
      }
      /**
       
       * Metoda pozwala na sprawdzenie identyfikatora u&#380;ytkownika za pomoc&#261; jego nazwy.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,102)
       
       *
       
       * @param string $Username
       
       * @return array
       
       */
      public function GetUserID($Username) {
          return $this->_client->doGetUserID(self::COUNTRY_CODE, $Username, '', $this->_config['allegro_key']);
      }
      /**
       
       * Metoda pozwala na pobranie listingu wszystkich aukcji wystawianych obecnie przez danego u&#380;ytkownika.
       
       * Domy&#347;lnie zwracanych jest 25 aukcji posortowanych rosn&#261;co po czasie zako&#324;czenia. Rozmiar porcji
       
       * pozwala regulowa&#263; parametr limit, a sterowanie pobieraniem kolejnych porcji danych umo&#380;liwia parametr offset.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,103)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetUserItems($Options) {
          return $this->_client->doGetUserItems($this->_config['allegro_id'], $this->_config['allegro_key'], self::COUNTRY_CODE, $Options['offset'], $Options['limit']);
      }
      /**
       
       * Metoda pozwala na sprawdzenie nazwy u&#380;ytkownika za pomoc&#261; jego identyfikatora.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,104)
       
       *
       
       * @param int $UserID
       
       * @return array
       
       */
      public function GetUserLogin($UserID) {
          return $this->_client->doGetUserLogin(self::COUNTRY_CODE, $UserID, $this->_config['allegro_key']);
      }
      /**
       
       * Metoda pozwala na pobranie publicznie dost&#281;pnych informacji o dowolnym u&#380;ytkowniku serwisu.
       
       * U&#380;ytkownik mo&#380;e by&#263; wskazany za pomoc&#261; jego identyfikatora lub nazwy - w przypadku przekazania
       
       * warto&#347;ci w obu wymienionych parametrach, zwrócone zostan&#261; informacje o u&#380;ytkowniku wskazanym w parametrze user-id.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,341)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function ShowUser($Options) {
          return $this->_client->doShowUser($this->_config['allegro_key'], self::COUNTRY_CODE, $Options['user-id'], $Options['user-login']);
      }
      /**
       
       * Metoda pozwala na podgl&#261;d zawarto&#347;ci strony "O mnie" dowolnego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,302)
       
       *
       
       * @param int $Username
       
       * @return array
       
       */
      public function ShowUserPage($UserID) {
          return $this->_client->doShowUserPage($this->_config['allegro_key'], self::COUNTRY_CODE, $UserID);
      }
      /**********************************************************************************************************
       
       * Komentarze i ocena sprzeda&#380;y (http://allegro.pl/webapi/documentation.php/theme/id,42)
       
       *********************************************************************************************************/
      /**
       
       * Metoda pozwala na wystawienie komentarza u&#380;ytkownikowi b&#281;d&#261;cemu stron&#261; transakcji.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,42)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function Feedback($Options) {
          $this->checkConnection();
          return $this->_client->doFeedback($this->_session['session-handle-part'], $Options['fe-item-id'], $Options['fe-from-user-id'], $Options['fe-to-user-id'], $Options['fe-comment'], $Options['fe-comment-type'], $Options['fe-op'], $Options['fe-rating']);
      }
      /**
       
       * Metoda pozwala na wystawienie wielu komentarzy na raz u&#380;ytkownikom b&#281;d&#261;cym stronami transakcji.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,43)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function FeedbackMany($Options) {
          $this->checkConnection();
          return $this->_client->doFeedbackMany($this->_session['session-handle-part'], $Options);
      }
      /**
       
       * Metoda pozwala na pobranie informacji o komentarzach dowolnego u&#380;ytkownika. Domy&#347;lnie zwracane
       
       * s&#261; wszystkie komentarze (ew. ograniczone typem), posortowane malej&#261;co po czasie ich dodania.
       
       * Miejsce rozpocz&#281;cia pobierania listy komentarzy pozwala regulowa&#263; parametr feedback-offset.
       
       * Nale&#380;y poda&#263; identyfikator u&#380;ytkownika tylko w jednym z parametrów: feedback-from lub feedback-to.
       
       * W pierwszym - gdy pobrane maj&#261; zosta&#263; informacje o komentarzach, które wskazany u&#380;ytkownik wystawi&#322;.
       
       * W drugim - gdy pobrane maj&#261; zosta&#263; informacje o komentarzach, które wskazanemu u&#380;ytkownikowi zosta&#322;y wystawione.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,51)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetFeedback($Options) {
          $this->checkConnection();
          return $this->_client->doGetFeedback($this->_session['session-handle-part'], $Options['feedback-from'], $Options['feedback-to'], $Options['feedback-offset'], $Options['feedback-kind-list']);
      }
      /**
       
       * Metoda pozwala na pobranie szczegó&#322;owych informacji na temat oceny sprzeda&#380;y zalogowanego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,422)
       
       *
       
       * @return array
       
       */
      public function GetMySellRating() {
          $this->checkConnection();
          return $this->_client->doGetMySellRating($this->_session['session-handle-part']);
      }
      /**
       
       * Metoda pozwala na pobranie listy powodów niezadowolenia z transakcji oraz listy obszarów podlegaj&#261;cych ocenie sprzeda&#380;y.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,442)
       
       *
       
       * @return array
       
       */
      public function GetMySellRatingReasons() {
          $this->checkConnection();
          return $this->_client->doGetMySellRatingReasons($this->_session['session-handle-part'], self::COUNTRY_CODE);
      }
      /**
       
       * Metoda pozwala na pobranie informacji o komentarzach oczekuj&#261;cych na wystawienie przez zalogowanego u&#380;ytkownika.
       
       * Domy&#347;lnie zwracanych jest 25 elementów. Rozmiar porcji danych pozwala regulowa&#263; parametr package-size,
       
       * a sterowanie pobieraniem kolejnych porcji umo&#380;liwia parametr offset.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,105)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetWaitingFeedbacks($Options) {
          $this->checkConnection();
          return $this->_client->doGetWaitingFeedbacks($this->_session['session-handle-part'], $Options['offset'], $Options['package-size']);
      }
      /**
       
       * Metoda pozwala na pobranie informacji o liczbie komentarzy oczekuj&#261;cych na wystawienie przez zalogowanego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,106)
       
       *
       
       * @return array
       
       */
      public function GetWaitingFeedbacksCount() {
          $this->checkConnection();
          return $this->_client->doGetWaitingFeedbacksCount($this->_session['session-handle-part']);
      }
      /**
       
       * Metoda pozwala na pobranie informacji o komentarzach zalogowanego u&#380;ytkownika. Domy&#347;lnie zwracanych jest 25 ostatnich
       
       * komentarzy (wystawionych lub otrzymanych), posortowanych malej&#261;co po czasie ich dodania. Miejsce rozpocz&#281;cia
       
       * pobierania listy komentarzy pozwala regulowa&#263; parametr offset.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,111)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function MyFeedback2($Options) {
          $this->checkConnection();
          return $this->_client->doMyFeedback2($this->_session['session-handle-part'], $Options['feedback-type'], $Options['offset'], $Options['desc'], $Options['items-array']);
      }
      /**
       
       * Metoda pozwala na pobranie w porcjach informacji o komentarzach zalogowanego u&#380;ytkownika.
       
       * Domy&#347;lnie zwracana jest lista wszystkich (wystawionych lub otrzymanych) komentarzy, posortowanych malej&#261;co
       
       * po czasie ich dodania. Miejsce rozpocz&#281;cia pobierania listy komentarzy pozwala regulowa&#263; parametr offset.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,112)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function MyFeedback2Limit($Options) {
          $this->checkConnection();
          return $this->_client->doMyFeedback2Limit($this->_session['session-handle-part'], $Options['feedback-type'], $Options['offset'], $Options['desc'], $Options['items-array'], $Options['package-element']);
      }
      /**********************************************************************************************************
       
       * Komponenty i klucze wersji (http://allegro.pl/webapi/documentation.php/theme/id,61)
       
       *********************************************************************************************************/
      /**
       
       * Metoda pozwala na pobranie warto&#347;ci wszystkich wersjonowanych komponentów oraz umo&#380;liwia
       
       * podgl&#261;d kluczy wersji dla wszystkich krajów.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,62)
       
       *
       
       * @return array
       
       */
      public function QueryAllSysStatus() {
          return $this->_client->doQueryAllSysStatus(self::COUNTRY_CODE, $this->_config['allegro_key']);
      }
      /**
       
       * Metoda pozwala na pobranie warto&#347;ci jednego z wersjonowanych komponentów (program, drzewo kategorii, us&#322;uga,
       
       * parametry, pola formularza sprzeda&#380;y, serwisy) oraz umo&#380;liwia podgl&#261;d klucza wersji dla wskazanego krajów.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,61)
       
       *
       
       * @param int $Component
       
       *    1 - us&#322;uga Allegro WebAPI,
       
       *    2 - aplikacja,
       
       *    3 - struktura drzewa kategorii,
       
       *    4 - pola formularza sprzeda&#380;y,
       
       *    5 - serwisy
       
       *
       
       * @return array
       
       */
      public function QuerySysStatus($Component) {
          return $this->_client->doQuerySysStatus($Component, self::COUNTRY_CODE, $this->_config['allegro_key']);
      }
      /**********************************************************************************************************
       
       * Kupuj&#261;cy (http://allegro.pl/webapi/documentation.php/theme/id,101)
       
       *********************************************************************************************************/
      /**
       
       * Tylko w pakiecie Profesjonalnym!
       
       *
       
       * Metoda pozwala na z&#322;o&#380;enie oferty kupna w aukcji.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,382)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function BidItem($Options) {
          $this->checkConnection();
          return $this->_client->doBidItem($this->_session['session-handle-part'], $Options['bid-it-id'], $Options['bid-user-price'], $Options['bid-quantity'], $Options['bid-buy-now']);
      }
      /**
       
       * Tylko w pakiecie Profesjonalnym!
       
       *
       
       * Metoda pozwala na wys&#322;anie pro&#347;by o wycofanie oferty kupna z&#322;o&#380;onej
       
       * w aukcji przez zalogowanego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,304)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function RequestCancelBid($Options) {
          $this->checkConnection();
          return $this->_client->doRequestCancelBid($this->_session['session-handle-part'], $Options['request-item-id'], $Options['request-cancel-reason']);
      }
      /**********************************************************************************************************
       
       * Licencjonowanie (http://allegro.pl/webapi/documentation.php/theme/id,62)
       
       *********************************************************************************************************/
      /**
       
       * Tylko w pakiecie Profesjonalnym!
       
       *
       
       * Metoda pozwala na pobranie przez w&#322;a&#347;ciciela klucza daty wa&#380;no&#347;ci licencji,
       
       * udzielonej u&#380;ytkownikowi o wskazanej nazwie.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,63)
       
       *
       
       * @param string $User
       
       * @return array
       
       */
      public function GetAdminUserLicenceDate($User) {
          $this->checkConnection();
          return $this->_client->doGetAdminUserLicenceDate($this->_session['session-handle-part'], $User);
      }
      /**
       
       * Tylko w pakiecie Profesjonalnym!
       
       *
       
       * Metoda pozwala na pobranie przez zalogowanego u&#380;ytkownika daty wa&#380;no&#347;ci licencji,
       
       * która zosta&#322;a mu udzielona dla klucza podanego przy logowaniu.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,161)
       
       *
       
       * @return array
       
       */
      public function GetUserLicenceDate() {
          $this->checkConnection();
          return $this->_client->doGetUserLicenceDate($this->_session['session-handle-part']);
      }
      /**
       
       * Tylko w pakiecie Profesjonalnym!
       
       *
       
       * Metoda pozwala na ustawienie przez w&#322;a&#347;ciciela klucza daty wa&#380;no&#347;ci licencji u&#380;ytkownika o wskazanej nazwie.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,64)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function SetUserLicenceDate($Options) {
          $this->checkConnection();
          return $this->_client->doSetUserLicenceDate($this->_session['session-handle-part'], $Options['user-lic-login'], self::COUNTRY_CODE, $Options['user-lic-date']);
      }
      /**********************************************************************************************************
       
       * Logowanie (http://allegro.pl/webapi/documentation.php/theme/id,22)
       
       *********************************************************************************************************/
      /**
       
       * Metoda pozwala na uwierzytelnienie i autoryzacj&#281; u&#380;ytkownika za pomoc&#261; danych dost&#281;powych do konta
       
       * (podaj&#261;c has&#322;o w postaci zakodowanej SHA-256 a nast&#281;pnie base64 lub has&#322;o w wersji tekstowej).
       
       * Po pomy&#347;lnym uwierzytelnieniu, u&#380;ytkownik otrzymuje identyfikator sesji, którym nast&#281;pnie mo&#380;e
       
       * pos&#322;u&#380;y&#263; si&#281; przy wywo&#322;ywaniu metod wymagaj&#261;cych autoryzacji. Identyfikator sesji zachowuje
       
       * wa&#380;no&#347;&#263; przez 3 godziny od momentu jego utworzenia.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,82)
       
       *
       
       * @param bool $Encode
       
       */
      public function Login($Encode = false) {
          $version = $this->QuerySysStatus(1);
          $this->_local_version = $version['ver-key'];
          if (!$Encode) {
              $session = $this->_client->doLogin($this->_config['allegro_login'], $this->_config['allegro_password'], ALLEGRO_COUNTRY, $this->_config['allegro_key'], $version['ver-key']);
          } else {
              if (function_exists('hash') && in_array('sha256', hash_algos())) {
                  $pass = hash('sha256', $this->_config['allegro_password'], true);
              } elseif (function_exists('mhash') && is_int(MHASH_SHA256)) {
                  $pass = mhash(MHASH_SHA256, $this->_config['allegro_password']);
              }
              $password = base64_encode($pass);
              $session = $this->_client->doLoginEnc($this->_config['allegro_login'], $password, ALLEGRO_COUNTRY, $this->_config['allegro_key'], $version['ver-key']);
          }
          $this->_session = $session;
          unset($password);
          unset($this->_config['allegro_password']);
      }
      /**********************************************************************************************************
       
       * Modyfikacja aukcji (http://allegro.pl/webapi/documentation.php/theme/id,1)
       
       *********************************************************************************************************/
      /**
       
       * Metoda pozwala na dodanie wspólnego, dodatkowego tekstu do opisów aukcji wystawionych przez zalogowanego
       
       * u&#380;ytkownika. Tre&#347;&#263; dodanego tekstu pojawi si&#281; pod w&#322;a&#347;ciwym opisem, z przypisem Dodano oraz dat&#261; i godzin&#261; jego dodania.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,1)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function AddDescToItems($Options) {
          $this->checkConnection();
          return $this->_client->doAddDescToItems($this->_session['session-handle-part'], $Options['items-id-array'], $Options['it-description']);
      }
      /**
       
       * Tylko w pakiecie Profesjonalnym!
       
       *
       
       * Metoda pozwala na odwo&#322;anie ofert kupna z&#322;o&#380;onych w aukcji zalogowanego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,303)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function CancelBidItem($Options) {
          $this->checkConnection();
          return $this->_client->doCancelBidItem($this->_session['session-handle-part'], $Options['cancel-item-id'], $Options['cancel-bids-array'], $Options['cancel-bids-reason'], $Options['cancel-add-to-black-list']);
      }
      /**
       
       * Tylko w pakiecie Profesjonalnym!
       
       *
       
       * Metoda pozwala na zmian&#281; cen dost&#281;pnych w aukcji. Konieczne jest podanie oczekiwanych warto&#347;ci
       
       * wszystkich trzech cen (nawet je&#380;eli np. tylko jedna ma ulec zmianie, w parametrach reprezentuj&#261;cych
       
       * pozosta&#322;e dwie ceny znale&#378;&#263; powinna si&#281; ich aktualna warto&#347;&#263;). Przekazanie warto&#347;ci 0
       
       * w danym parametrze dezaktywuje wskazana cen&#281; w aukcji.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,223)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function ChangePriceItem($Options) {
          $this->checkConnection();
          return $this->_client->doChangePriceItem($this->_session['session-handle-part'], $Options['item-id'], $Options['new-starting-price'], $Options['new-reserve-price'], $Options['new-buy-now-price']);
      }
      /**
       
       * Tylko w pakiecie Profesjonalnym!
       
       *
       
       * Metoda pozwala na zmian&#281; liczby przedmiotów dost&#281;pnych na aukcji zalogowanego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,222)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function ChangeQuantityItem($Options) {
          $this->checkConnection();
          return $this->_client->doChangeQuantityItem($this->_session['session-handle-part'], $Options['item-id'], $Options['new-item-quantity']);
      }
      /**
       
       * Metoda pozwala na ko&#324;czenie przed czasem (z lub bez odwo&#322;ania ofert) aukcji zalogowanego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,221)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function FinishItem($Options) {
          $this->checkConnection();
          return $this->_client->doFinishItem($this->_session['session-handle-part'], $Options['finish-item-id'], $Options['finish-cancel-all-bids'], $Options['finish-cancel-reason']);
      }
      /**
       
       * Metoda pozwala na ko&#324;czenie przed czasem (bez lub z odwo&#322;aniem ofert) wielu aukcji zalogowanego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,623)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function FinishItems($Options) {
          $this->checkConnection();
          return $this->_client->doFinishItems($this->_session['session-handle-part'], $Options);
      }
      /**********************************************************************************************************
       
       * Moje Allegro (http://allegro.pl/webapi/documentation.php/theme/id,44)
       
       *********************************************************************************************************/
      /**
       
       * Metoda pozwala na pobranie listy ulubionych kategorii zalogowanego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,49)
       
       *
       
       * @return array
       
       */
      public function GetFavouriteCategories() {
          $this->checkConnection();
          return $this->_client->doGetFavouriteCategories($this->_session['session-handle-part']);
      }
      /**
       
       * Metoda pozwala na pobranie listy ulubionych sprzedaj&#261;cych zalogowanego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,50)
       
       *
       
       * @return array
       
       */
      public function GetFavouriteSellers() {
          $this->checkConnection();
          return $this->_client->doGetFavouriteSellers($this->_session['session-handle-part']);
      }
      /**
       
       * Metoda pozwala na pobranie listy aukcji z poszczególnych zak&#322;adek Mojego Allegro (licytowane, kupione, niekupione,
       
       * obserwowane: trwaj&#261;ce, obserwowane: zako&#324;czone, sprzedawane, sprzedane, niesprzedane, do wystawienia)
       
       * zalogowanego u&#380;ytkownika. Domy&#347;lnie zwracanych jest pierwszych 25 aukcji z danej zak&#322;adki, posortowanych
       
       * malej&#261;co po czasie ich zako&#324;czenia. Mo&#380;liwe jest tak&#380;e pobranie informacji o wskazanych aukcjach z danej
       
       * zak&#322;adki (items-array). Pe&#322;en podgl&#261;d nazw oraz identyfikatorów kupuj&#261;cych mo&#380;liwy jest tylko dla zak&#322;adek typu
       
       * 'sell' i 'sold' - dla pozosta&#322;ych typów wspomniane dane zwrócone zostan&#261; w formie zanonimizowanej.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,107)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function MyAccount2($Options) {
          $this->checkConnection();
          return $this->_client->doMyAccount2($this->_session['session-handle-part'], $Options['account-type'], $Options['offset'], $Options['items-array'], $Options['limit']);
      }
      /**
       
       * Metoda pozwala na pobranie informacji o liczbie aukcji z poszczególnych zak&#322;adek Mojego Allegro
       
       * (licytowane, kupione, niekupione, obserwowane: trwaj&#261;ce, obserwowane: zako&#324;czone, sprzedawane, sprzedane,
       
       * niesprzedane, do wystawienia) zalogowanego u&#380;ytkownika. Mo&#380;liwe jest tak&#380;e pobranie informacji
       
       * o liczbie aukcji znajduj&#261;cych si&#281; we wskazanej zak&#322;adce, z listy przekazanej w items-array.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,108)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function MyAccountItemsCount($Options) {
          $this->checkConnection();
          return $this->_client->doMyAccountItemsCount($this->_session['session-handle-part'], $Options['account-type'], $Options['items-array']);
      }
      /**
       
       * Metoda pozwala na usuwanie wskazanych aukcji z listingu aukcji obserwowanych
       
       * (trwaj&#261;cych oraz zako&#324;czonych) zalogowanego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,115)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function RemoveFromWatchList($Options) {
          $this->checkConnection();
          return $this->_client->doRemoveFromWatchList($this->_session['session-handle-part'], $Options['items-id-array']);
      }
      /**********************************************************************************************************
       
       * Nowo&#347;ci i komunikaty (http://allegro.pl/webapi/documentation.php/theme/id,69)
       
       *********************************************************************************************************/
      /**
       
       * Metoda pozwala na pobranie listy komunikatów serwisowych ze strony Nowo&#347;ci i komunikaty dla wskazanego kraju.
       
       * Zwróconych mo&#380;e by&#263; maks. 50 ostatnich komunikatów dla danej kategorii - ich lista posortowana
       
       * jest malej&#261;co po czasie dodania. W przypadku nie podania daty (an-it-date) lub identyfikatora (ani-it-id)
       
       * komunikatu, zwrócony zostanie jeden najnowszy komunikat ze wskazanej kategorii.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,93)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetServiceInfo($Options) {
          return $this->_client->doGetServiceInfo(self::COUNTRY_CODE, $Options['an-cat-id'], $Options['an-it-date'], $Options['an-it-id'], $this->_config['allegro_key']);
      }
      /**
       
       * Metoda pozwala na pobranie listy kategorii komunikatów serwisowych ze strony Nowo&#347;ci i komunikaty dla wskazanego kraju.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,94)
       
       *
       
       * @return array
       
       */
      public function GetServiceInfoCategories() {
          return $this->_client->doGetServiceInfoCategories(self::COUNTRY_CODE, $this->_config['allegro_key']);
      }
      /**********************************************************************************************************
       
       * Op&#322;aty i prowizje (http://allegro.pl/webapi/documentation.php/theme/id,66)
       
       *********************************************************************************************************/
      /**
       
       * Metoda pozwala na pobranie informacji o op&#322;atach zwi&#261;zanych z korzystaniem z serwisu odpowiedniego dla wskazanego kraju.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,88)
       
       *
       
       * @return array
       
       */
      public function GetPaymentData() {
          return $this->_client->doGetPaymentData(self::COUNTRY_CODE, $this->_config['allegro_key']);
      }
      /**
       
       * Metoda pozwala na pobranie bie&#380;&#261;cego salda z konta zalogowanego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,109)
       
       *
       
       * @return array
       
       */
      public function MyBilling() {
          return $this->_client->doMyBilling(self::COUNTRY_CODE);
      }
      /**
       
       * Tylko w pakiecie Profesjonalnym!
       
       *
       
       * Metoda pozwala na sprawdzenie kosztów zwi&#261;zanych z wystawieniem aukcji oraz prowizj&#261; za zrealizowan&#261;
       
       * w jej ramach sprzeda&#380;. Sprawdzenie kosztów mo&#380;liwe jest jedynie dla aukcji zalogowanego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,301)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function MyBillingItem($Options) {
          $this->checkConnection();
          return $this->_client->doMyBillingItem($this->_session['session-handle-part'], $Options['item-id'], $Options['option']);
      }
      /**********************************************************************************************************
       
       * P&#322;ac&#281; z Allegro (http://allegro.pl/webapi/documentation.php/theme/id,65)
       
       *********************************************************************************************************/
      /**
       
       * Metoda pozwala na pobranie listy wp&#322;at od kupuj&#261;cych (dokonanych za po&#347;rednictwem PzA) za transakcje
       
       * w ramach aukcji zalogowanego u&#380;ytkownika. Domy&#347;lnie (w przypadku nie zdefiniowana zakresu czasu)
       
       * pobierana jest lista wp&#322;at z przeci&#261;gu ostatniego tygodnia (domy&#347;lnie 25 ostatnio dokonanych wp&#322;at),
       
       * posortowana malej&#261;co po czasie ich realizacji. List&#281; mo&#380;na filtrowa&#263; po u&#380;ytkowniku dokonuj&#261;cym wp&#322;aty
       
       * (buyer-id), po aukcji której wp&#322;aty dotycz&#261; (item-id) oraz po zakresie czasu, w którym wp&#322;aty zosta&#322;y
       
       * dokonane. W przypadku gdy za dat&#281; pocz&#261;tkow&#261; zakresu czasu (trans-recv-date-from) podstawiona zostanie
       
       * konkretna warto&#347;&#263;, a dla daty ko&#324;cowej zakresu czasu (trans-recv-date-to) przekazane zostanie 0, zwrócona
       
       * zostanie lista wp&#322;at od daty podanej do daty podanej + 7 dni. W przypadku odwrotnym (gdy dla daty pocz&#261;tkowej
       
       * zakresu czasu przekazane zostanie 0, a dla daty ko&#324;cowej zakresu czasu podstawiona zostanie konkretna warto&#347;&#263;),
       
       * zwrócona zostanie lista wp&#322;at od daty podanej - 7 dni do daty podanej. Przy podaniu konkretnych warto&#347;ci
       
       * zakresu czasu zarówno dla daty pocz&#261;tkowej, jak i dla daty ko&#324;cowej, zwrócona zostanie lista wp&#322;at zrealizowanych
       
       * w podanym zakresie (ustalony zakres nie mo&#380;e jednak przekracza&#263; 90 dni). Poszczególne filtry mo&#380;na ze sob&#261; &#322;&#261;czy&#263;.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,85)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetMyIncomingPayments($Options) {
          $this->checkConnection();
          return $this->_client->doGetMyIncomingPayments($this->_session['session-handle-part'], $Options['buyer-id'], $Options['item-id'], $Options['trans-recv-date-from'], $Options['trans-recv-date-to'], $Options['trans-page-limit'], $Options['trans-offset']);
      }
      /**
       
       * Metoda pozwala na pobranie listy zwrotów (wycofanych wp&#322;at dokonanych za po&#347;rednictwem PzA)
       
       * za transakcje zrealizowane przez kupuj&#261;cych w ramach aukcji zalogowanego u&#380;ytkownika.
       
       * Okres czasu, dla jakiego metoda zwraca dane to ok. 90 dni.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,522)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetMyIncomingPaymentsRefunds($Options) {
          $this->checkConnection();
          return $this->_client->doGetMyIncomingPaymentsRefunds($this->_session['session-handle-part'], $Options['buyer-id'], $Options['item-id'], $Options['limit'], $Options['offset']);
      }
      /**
       
       * Metoda pozwala na pobranie listy wp&#322;at (dokonanych za po&#347;rednictwem PzA) za transakcje
       
       * zrealizowane przez zalogowanego u&#380;ytkownika. Domy&#347;lnie (w przypadku nie zdefiniowana zakresu czasu)
       
       * pobierana jest lista wp&#322;at z przeci&#261;gu ostatniego tygodnia (domy&#347;lnie 25 ostatnio dokonanych wp&#322;at),
       
       * posortowana malej&#261;co po czasie ich realizacji. List&#281; mo&#380;na filtrowa&#263; po u&#380;ytkowniku, któremu
       
       * dokonywane by&#322;y wp&#322;aty (seller-id), po aukcji której wp&#322;aty dotycz&#261; (item-id) oraz po zakresie czasu,
       
       * w którym wp&#322;aty zosta&#322;y dokonane. W przypadku gdy za dat&#281; pocz&#261;tkow&#261; zakresu czasu (trans-create-date-from)
       
       * podstawiona zostanie konkretna warto&#347;&#263;, a dla daty ko&#324;cowej zakresu czasu (trans-create-date-to)
       
       * przekazane zostanie 0, zwrócona zostanie lista wp&#322;at od daty podanej do daty podanej + 7 dni.
       
       * W przypadku odwrotnym (gdy dla daty pocz&#261;tkowej zakresu czasu przekazane zostanie 0, a dla daty ko&#324;cowej
       
       * zakresu czasu podstawiona zostanie konkretna warto&#347;&#263;), zwrócona zostanie lista wp&#322;at od daty
       
       * podanej - 7 dni do daty podanej. Przy podaniu konkretnych warto&#347;ci zakresu czasu zarówno dla daty pocz&#261;tkowej,
       
       * jak i dla daty ko&#324;cowej, zwrócona zostanie lista wp&#322;at zrealizowanych w podanym zakresie (ustalony zakres
       
       * nie mo&#380;e jednak przekracza&#263; 90 dni). Poszczególne filtry mo&#380;na ze sob&#261; &#322;&#261;czy&#263;.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,86)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetMyPayments($Options) {
          $this->checkConnection();
          return $this->_client->doGetMyPayments($this->_session['session-handle-part'], $Options['seller-id'], $Options['item-id'], $Options['trans-create-date-from'], $Options['trans-create-date-to'], $Options['trans-page-limit'], $Options['trans-offset']);
      }
      /**
       
       * Metoda pozwala na pobranie listy zwrotów (wycofanych wp&#322;at dokonanych za po&#347;rednictwem PzA) za transakcje
       
       * zrealizowane przez zalogowanego u&#380;ytkownika. Okres czasu, dla jakiego metoda zwraca dane to ok. 90 dni.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,502)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetMyPaymentsRefunds($Options) {
          $this->checkConnection();
          return $this->_client->doGetMyPaymentsRefunds($this->_session['session-handle-part'], $Options['seller-id'], $Options['item-id'], $Options['limit'], $Options['offset']);
      }
      /**
       
       * Metoda pozwala na pobranie listy wyp&#322;at &#347;rodków (wp&#322;aconych przez kupuj&#261;cych za po&#347;rednictwem PzA) za transakcje
       
       * w ramach aukcji zalogowanego u&#380;ytkownika. Domy&#347;lnie (w przypadku nie zdefiniowana zakresu czasu) pobierana
       
       * jest lista wyp&#322;at z przeci&#261;gu ostatniego tygodnia (domy&#347;lnie 50 ostatnio dokonanych wyp&#322;at), posortowana
       
       * malej&#261;co po czasie ich realizacji. List&#281; mo&#380;na filtrowa&#263; po zakresie czasu, w którym wyp&#322;aty zosta&#322;y dokonane.
       
       * W przypadku gdy za dat&#281; pocz&#261;tkow&#261; zakresu czasu (trans-create-date-from) podstawiona zostanie konkretna warto&#347;&#263;,
       
       * a dla daty ko&#324;cowej zakresu czasu (trans-create-date-to) przekazane zostanie 0, zwrócona zostanie lista wyp&#322;at
       
       * od daty podanej do daty podanej + 7 dni. W przypadku odwrotnym (gdy dla daty pocz&#261;tkowej zakresu czasu przekazane
       
       * zostanie 0, a dla daty ko&#324;cowej zakresu czasu podstawiona zostanie konkretna warto&#347;&#263;), zwrócona zostanie lista
       
       * wyp&#322;at od daty podanej - 7 dni do daty podanej. Przy podaniu konkretnych warto&#347;ci zakresu czasu zarówno dla
       
       * daty pocz&#261;tkowej, jak i dla daty ko&#324;cowej, zwrócona zostanie lista wyp&#322;at zrealizowanych w podanym zakresie
       
       * (ustalony zakres nie mo&#380;e jednak przekracza&#263; 30 dni).
       
       * (http://allegro.pl/webapi/documentation.php/show/id,87)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetMyPayouts($Options) {
          $this->checkConnection();
          return $this->_client->doGetMyPayouts($this->_session['session-handle-part'], $Options['trans-create-date-from'], $Options['trans-create-date-to'], $Options['trans-page-limit'], $Options['trans-offset']);
      }
      /**
       
       * Metoda pozwala na wnioskowanie o dop&#322;at&#281; do transakcji, za któr&#261; p&#322;atno&#347;&#263; jest niekompletna.
       
       * Dla ka&#380;dej transakcji mo&#380;na wys&#322;a&#263; tylko jeden wniosek o dop&#322;at&#281;.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,662)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function RequestSurcharge($Options) {
          $this->checkConnection();
          return $this->_client->doRequestSurcharge($this->_session['session-handle-part'], $Options['surcharge-trans-id'], $Options['surcharge-value'], $Options['surcharge-message']);
      }
      /**********************************************************************************************************
       
       * Produkty w Allegro (http://allegro.pl/webapi/documentation.php/theme/id,141)
       
       *********************************************************************************************************/
      /**
       
       * Metoda pozwala obs&#322;u&#380;y&#263; mechanizm wyszukiwarki Produktów w Allegro i wyszuka&#263; produkty po kodzie EAN
       
       * (ISBN/ISSN/etc.). Podczas wywo&#322;ywania metody mo&#380;liwe jest okre&#347;lenie kategorii, do której ma by&#263;
       
       * zaw&#281;&#380;one wyszukiwanie, dzi&#281;ki czemu zostanie zwi&#281;kszona celno&#347;&#263; wyników. Metoda zwraca: ID znalezionego
       
       * produktu, jego nazw&#281;, opis, zdj&#281;cia oraz parametry. Cz&#281;&#347;&#263; tych danych mo&#380;na potem wykorzysta&#263; podczas
       
       * wystawiania nowej aukcji za pomoc&#261; metody doNewAuctionExt.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,643)
       
       *
       
       * @param string $Code
       
       * @return array
       
       */
      public function FindProductByCode($Code) {
          $this->checkConnection();
          return $this->_client->doFindProductByCode($this->_session['session-handle-part'], $Code);
      }
      /**
       
       * Metoda pozwala obs&#322;u&#380;y&#263; mechanizm wyszukiwarki Produktów Allegro i wyszuka&#263; produkty po ich nazwie
       
       * lub cz&#281;&#347;ci nazwy. Podczas wywo&#322;ywania metody mo&#380;liwe jest okre&#347;lenie kategorii, do której ma
       
       * by&#263; zaw&#281;&#380;one wyszukiwanie, dzi&#281;ki czemu zostanie zwi&#281;kszona celno&#347;&#263; wyników.
       
       * Metoda zwraca: ID znalezionych produktów, ich nazw&#281;, opis, zdj&#281;cia oraz parametry.
       
       * Cz&#281;&#347;&#263; tych danych mo&#380;na potem wykorzysta&#263; podczas wystawiania nowej aukcji za pomoc&#261; metody doNewAuctionExt.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,642)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function FindProductByName($Options) {
          $this->checkConnection();
          return $this->_client->doFindProductByName($this->_session['session-handle-part'], $Options['product-name'], $Options['category-id']);
      }
      /**
       
       * Metoda pozwala na pobranie danych na temat konkretnego produktu z katalogu Produktów w Allegro.
       
       * Do wywo&#322;ania metody wymagany jest identyfikator produktu oraz hash - obie warto&#347;ci mog&#261; by&#263;
       
       * pobrane za pomoc&#261; metod doShowItemInfoExt oraz doGetItemsInfo (dla aukcji zintegrowanych z produktem).
       
       * (http://allegro.pl/webapi/documentation.php/show/id,644)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function ShowProductInfo($Options) {
          $this->checkConnection();
          return $this->_client->doShowProductInfo($this->_session['session-handle-part'], $Options['product-id'], $Options['category-hash']);
      }
      /**********************************************************************************************************
       
       * Rabaty (http://allegro.pl/webapi/documentation.php/theme/id,121)
       
       *********************************************************************************************************/
      /**
       
       * Metoda pozwala na pobranie pojedynczych aktów zakupowych zrealizowanych przez danego kupuj&#261;cego we
       
       * wskazanej aukcji (w której sprzedaj&#261;cym by&#322; zalogowany u&#380;ytkownik). Uzyskane dane mog&#261; by&#263; nast&#281;pnie
       
       * wykorzystane np. do udzielania rabatów za pomoc&#261; metody doMakeDiscount. Metoda zwraca tylko te akty zakupowe,
       
       * na które w chwili jej wywo&#322;ania jest mo&#380;liwo&#347;&#263; na&#322;o&#380;enia rabatu (nie s&#261; one jeszcze op&#322;acone).
       
       * Wyj&#261;tkiem od powy&#380;szego jest sytuacja, w której akt zakupowy zosta&#322; op&#322;acony, ale p&#322;atno&#347;&#263; zosta&#322;a
       
       * anulowana - jest on wtedy traktowany jak nieop&#322;acony i informacja o nim zostanie zwrócona.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,462)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetDeals($Options) {
          $this->checkConnection();
          return $this->_client->doGetDeals($this->_session['session-handle-part'], $Options['item-id'], $Options['buyer-id']);
      }
      /**
       
       * Metoda pozwala na udzielenie rabatu kupuj&#261;cemu w ramach danego aktu zakupowego.
       
       * Mo&#380;liwe jest udzielenie naraz tylko jednego rodzaju rabatu - albo kwotowego, albo procentowego
       
       * (wyj&#261;tkiem jest mo&#380;liwo&#347;&#263; wyzerowania obu parametrów w celu zdj&#281;cia istniej&#261;cego rabatu).
       
       * Ka&#380;de kolejne wywo&#322;anie metody dla tego samego aktu zakupowego nadpisuje rabat ustawiony wcze&#347;niej.
       
       * Za ka&#380;dym razem rabat jest udzielany od kwoty pierwotnej (bez rabatu) za dany akt zakupowy,
       
       * nie za&#347; od kwoty uprzednio "zrabatowanej". Na&#322;o&#380;ony rabat obni&#380;a pierwotn&#261; kwot&#281; do zap&#322;acenia za
       
       * dany akt zakupowy, proporcjonalnie obni&#380;aj&#261;c kwot&#281; jednostkow&#261; do zap&#322;acenia za ka&#380;dy
       
       * z przedmiotów zakupionych w ramach danego aktu zakupowego.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,482)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function MakeDiscount($Options) {
          $this->checkConnection();
          return $this->_client->doMakeDiscount($this->_session['session-handle-part'], $Options['deal-it'], $Options['discount-amount'], $Options['discount-percentage']);
      }
      /**********************************************************************************************************
       
       * Ró&#380;ne (http://allegro.pl/webapi/documentation.php/theme/id,25)
       
       *********************************************************************************************************/
      /**
       
       * Metoda pozwala na sprawdzenie czy hash weryfikuj&#261;cy poprawno&#347;&#263; odno&#347;nika, wys&#322;anego do danego
       
       * kupuj&#261;cego w danej aukcji jest poprawny (zosta&#322; faktycznie wygenerowany za pomoc&#261; taga [EXT_LINK_xxx]).
       
       * Odpowiednie odno&#347;niki wygenerowa&#263; mo&#380;na na podstawie trzech unikalnych tagów, dost&#281;pnych dla ka&#380;dego
       
       * u&#380;ytkownika us&#322;ugi na stronie: Moje Allegro > WebAPI, w bloku Konfiguracja zewn&#281;trznych linków.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,23)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function CheckExternalKey($Options) {
          return $this->_client->doCheckExternalKey($this->_config['allegro_key'], $Options['user-id'], $Options['item-id'], $Options['hash-key']);
      }
      /**
       
       * Metoda pozwala na sprawdzenie jaka jest warto&#347;&#263; identyfikatora wystawionej aukcji za pomoc&#261;
       
       * identyfikatora aukcji planowanej do wystawienia. W przypadku przekazania numeru planowanej
       
       * aukcji, która jeszcze si&#281; nie rozpocz&#281;&#322;a, metoda zwróci 0.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,24)
       
       *
       
       * @param int $Auction
       
       * @return array
       
       */
      public function CheckItemIdByFutureItemId($Auction) {
          return $this->_client->doCheckItemIdByFutureItemId($this->_config['allegro_key'], self::COUNTRY_CODE, $Auction);
      }
      /**
       
       * Metoda pozwala na pobranie listy wszystkich krajów dost&#281;pnych w serwisie.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,25)
       
       *
       
       * @return array
       
       */
      public function GetCountries() {
          return $this->_client->doGetCountries(self::COUNTRY_CODE, $this->_config['allegro_key']);
      }
      /**
       
       * Metoda pozwala na pobranie listy graficznych, systemowych szablonów aukcji dost&#281;pnych dla wskazanego kraju.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,95)
       
       *
       
       * @return array
       
       */
      public function GetServiceTemplates() {
          return $this->_client->doGetServiceTemplates(self::COUNTRY_CODE, $this->_config['allegro_key']);
      }
      /**
       
       * Metoda pozwala na pobranie pe&#322;nej listy sposobów dostawy dost&#281;pnych we wskazanym kraju.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,624)
       
       *
       
       * @return array
       
       */
      public function GetShipmentData() {
          return $this->_client->doGetShipmentData(self::COUNTRY_CODE, $this->_config['allegro_key']);
      }
      /**
       
       * Metoda pozwala na pobranie szczegó&#322;owych informacji (nazwa, adres WWW, kod kraju,
       
       * u&#380;ywana strona kodowa, logo, flaga kraju) o dost&#281;pnych serwisach aukcyjnych.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,98)
       
       *
       
       * @return array
       
       */
      public function GetSitesFlagInfo() {
          return $this->_client->doGetSitesFlagInfo(self::COUNTRY_CODE, $this->_config['allegro_key']);
      }
      /**
       
       * Metoda pozwala na pobranie szczegó&#322;owych informacji (nazwa, adres WWW, kod kraju,
       
       * u&#380;ywana strona kodowa, logo) o dost&#281;pnych serwisach aukcyjnych.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,99)
       
       *
       
       * @return array
       
       */
      public function GetSitesInfo() {
          return $this->_client->doGetSitesInfo(self::COUNTRY_CODE, $this->_config['allegro_key']);
      }
      /**
       
       * Metoda pozwala na pobranie listy regionów (dla Polski - województw) dla danego kraju.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,101)
       
       *
       
       * @return array
       
       */
      public function GetStatesInfo() {
          return $this->_client->doGetStatesInfo(self::COUNTRY_CODE, $this->_config['allegro_key']);
      }
      /**
       
       * Metoda pozwala na pobranie aktualnego (dla danego kraju) czasu z serwera Allegro.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,81)
       
       *
       
       * @return array
       
       */
      public function GetSystemTime() {
          return $this->_client->doGetSystemTime(self::COUNTRY_CODE, $this->_config['allegro_key']);
      }
      /**********************************************************************************************************
       
       * Sklepy Allegro (http://allegro.pl/webapi/documentation.php/theme/id,70)
       
       *********************************************************************************************************/
      /**
       
       * Metoda pozwala na pobranie pe&#322;nego drzewa kategorii utworzonych przez zalogowanego u&#380;ytkownika w jego Sklepie Allegro.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,97)
       
       *
       
       * @return array
       
       */
      public function GetShopCatsData() {
          $this->checkConnection();
          return $this->_client->doGetShopCatsData($this->_session['session-handle-part']);
      }
      /**
       
       * Metoda pozwala na wystawienie aukcji w Sklepie Allegro na podstawie aukcji istniej&#261;cych.
       
       * Z uwagi na specyfik&#281; dzia&#322;ania mechanizmu ponownego wystawiania aukcji - identyfikatory aukcji
       
       * zwracane na wyj&#347;ciu, to identyfikatory aukcji na podstawie których nowe aukcje zosta&#322;y/mia&#322;y zosta&#263;
       
       * wystawione - nie identyfikatory nowo wystawionych aukcji.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,322)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function SellSomeAgainInShop($Options) {
          return $this->_client->doSellSomeAgainInShop($this->_config['allegro_key'], $Options['sell-items-array'], $Options['sell-starting-time'], $Options['sell-ahop-duration'], $Options['sell-shop-options'], $Options['sell-prolong-options'], $Options['sell-shop-category']);
      }
      /**********************************************************************************************************
       
       * System zwrotu prowizji (http://allegro.pl/webapi/documentation.php/theme/id,81)
       
       *********************************************************************************************************/
      /**
       
       * Tylko w pakiecie Profesjonalnym!
       
       *
       
       * Metoda pozwala na anulowanie procedury zwrotu prowizji. Po anulowaniu procedury zwrotu dot.
       
       * danej transakcji, nie ma mo&#380;liwo&#347;ci ponownego wyst&#261;pienia o zwrot prowizji dla niej.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,263)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function CancelRefundForms($Options) {
          $this->checkConnection();
          return $this->_client->doCancelRefundForms($this->_session['session-handle-part'], $Options);
      }
      /**
       
       * Tylko w pakiecie Profesjonalnym!
       
       *
       
       * Metoda pozwala na anulowanie ostrze&#380;e&#324;. Po anulowaniu ostrze&#380;enia dot. danej transakcji,
       
       * nie ma mo&#380;liwo&#347;ci ponownego wyst&#261;pienia o zwrot prowizji dla niej. Anulowanie ostrze&#380;enia
       
       * jest równoznaczne z ponownym naliczeniem prowizji za sprzedany przedmiot.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,264)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function CancelRefundWarnings($Options) {
          $this->checkConnection();
          return $this->_client->doCancelRefundWarnings($this->_session['session-handle-part'], $Options);
      }
      /**
       
       * Tylko w pakiecie Profesjonalnym!
       
       *
       
       * Metoda pozwala na pobieranie statusów formularzy zwrotu prowizji dla transakcji,
       
       * w których sprzeda&#380; nast&#261;pi&#322;a z konta zalogowanego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,262)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetRefundFormsStatuses($Options) {
          $this->checkConnection();
          return $this->_client->doGetRefundFormsStatuses($this->_session['session-handle-part'], $Options);
      }
      /**
       
       * Tylko w pakiecie Profesjonalnym!
       
       *
       
       * Metoda pozwala na pobranie listy dost&#281;pnych w danym kraju powodów ubiegania si&#281; o zwrot prowizji.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,202)
       
       *
       
       * @return array
       
       */
      public function GetRefundReasons() {
          return $this->_client->doGetRefundReasons($this->_config['allegro_key'], self::COUNTRY_CODE);
      }
      /**
       
       * Tylko w pakiecie Profesjonalnym!
       
       *
       
       * Metoda pozwala na pobranie listy transakcji dla których trwa lub mo&#380;e trwa&#263; procedura zwrotu
       
       * prowizji (listing zawiera aukcje, nieprzeniesione do archiwum, z zak&#322;adek Sprzedane oraz Sprzedaj&#281;).
       
       * Domy&#347;lnie pobierana jest lista wszystkich dost&#281;pnych transakcji, posortowana rosn&#261;co po czasie
       
       * zako&#324;czenia aukcji. Rozmiar porcji danych pozwala regulowa&#263; parametr limit, a sterowanie pobieraniem
       
       * kolejnych porcji danych umo&#380;liwia parametr offset.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,261)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetRefundTransactions($Options) {
          $this->checkConnection();
          return $this->_client->doGetRefundTransactions($this->_session['session-handle-part'], $Options['offset'], $Options['limit']);
      }
      /**
       
       * Tylko w pakiecie Profesjonalnym!
       
       *
       
       * Metoda pozwala na wys&#322;anie formularzy zwrotu prowizji (wype&#322;nia&#263; je mo&#380;na nie wcze&#347;niej ni&#380; 7 dni
       
       * i nie pó&#378;niej ni&#380; 45 dni od dnia zako&#324;czenia sprzeda&#380;y), dot. niezrealizowanych przez
       
       * kupuj&#261;cych transakcji na aukcjach zalogowanego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,201)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function SendRefundForms($Options) {
          $this->checkConnection();
          return $this->_client->doSendRefundForms($this->_session['session-handle-part'], $Options);
      }
      /**
       
       * Tylko w pakiecie Profesjonalnym!
       
       *
       
       * Metoda pozwala na wys&#322;anie przypomnie&#324; o zawarciu transakcji (wype&#322;nia&#263; je mo&#380;na nie wcze&#347;niej
       
       * ni&#380; 3 dni i nie pó&#378;niej ni&#380; 30 dni od dnia zako&#324;czenia sprzeda&#380;y), do kupuj&#261;cych którzy
       
       * dokonali zakupu na aukcjach zalogowanego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,241)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function SendReminderMessages($Options) {
          $this->checkConnection();
          return $this->_client->doSendReminderMessages($this->_session['session-handle-part'], $Options);
      }
      /**********************************************************************************************************
       
       * Widok i opcje aukcji (http://allegro.pl/webapi/documentation.php/theme/id,23)
       
       *********************************************************************************************************/
      /**
       
       * Metoda pozwala na dodanie wskazanych aukcji do listingu aukcji obserwowanych zalogowanego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,22)
       
       *
       
       * @param array $Items
       
       * @return array
       
       */
      public function AddWatchList($Items) {
          $this->checkConnection();
          return $this->_client->doAddWatchList($this->_session['session-handle-part'], $Items);
      }
      /**
       
       * Metoda pozwala na pobranie publicznie dost&#281;pnych informacji na temat wszystkich u&#380;ytkowników,
       
       * którzy dokonali zakupu w danej aukcji. Pe&#322;en podgl&#261;d nazw oraz identyfikatorów u&#380;ytkowników
       
       * mo&#380;liwy jest tylko dla u&#380;ytkowników, którzy wystawili dan&#261; aukcj&#281; - pozostali u&#380;ytkownicy
       
       * otrzymaj&#261; wspomniane dane w formie zanonimizowanej.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,44)
       
       *
       
       * @param int $Auction
       
       * @return array
       
       */
      public function GetBidItem2($Auction) {
          $this->checkConnection();
          return $this->objectToArray($this->_client->doGetBidItem2($this->_session['session-handle-part'], $Auction));
      }
      /**
       
       * Metoda pozwala na pobranie wszystkich dost&#281;pnych informacji (m.in. opis, kategoria,
       
       * zdj&#281;cia, parametry, dost&#281;pne sposoby dostawy i formy p&#322;atno&#347;ci, etc.) o wskazanych aukcjach.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,52)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetItemsInfo($Options) {
          $this->checkConnection();
          return $this->_client->doGetItemsInfo($this->_session['session-handle-part'], $Options['items-id-array'], $Options['get-desc'], $Options['get-image-url'], $Options['get-attribs'], $Options['get-postage-options'], $Options['get-company-info']);
      }
      /**
       
       * Tylko w pakiecie Profesjonalnym!
       
       *
       
       * Metoda pozwala na pobranie kompletnych informacji o aukcji - wraz z list&#261; i danymi kupuj&#261;cych.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,402)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetItemTransaction($Options) {
          $this->checkConnection();
          return $this->_client->doGetItemTransaction($this->_session['session-handle-part'], $Options['item-id'], $Options['item-options']);
      }
      /**
       
       * Tylko w pakiecie Profesjonalnym!
       
       *
       
       * Metoda pozwala na wys&#322;anie okre&#347;lonego rodzaju wiadomo&#347;ci do wybranego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,281)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function SendEmailToUser($Options) {
          $this->checkConnection();
          return $this->_client->doSendEmailToUser($this->_session['session-handle-part'], $Options['mail-to-user-item-id'], $Options['mail-to-user-receiver-id'], $Options['mail-to-user-subject-id'], $Options['mail-to-user-option'], $Options['mail-to-user-message']);
      }
      /**
       
       * Metoda pozwala na pobranie wszystkich dost&#281;pnych informacji (m.in. opis, kategoria, zdj&#281;cia,
       
       * parametry, dost&#281;pne sposoby dostawy i formy p&#322;atno&#347;ci, etc.) o wskazanej aukcji.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,342)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function ShowItemInfoExt($Options) {
          $this->checkConnection();
          return $this->_client->doShowItemInfoExt($this->_session['session-handle-part'], $Options['item-id'], $Options['get-desc'], $Options['get-image-url'], $Options['get-attribs'], $Options['get-postage-options'], $Options['get-company-info']);
      }
      /**********************************************************************************************************
       
       * Wystawianie aukcji (http://allegro.pl/webapi/documentation.php/theme/id,41)
       
       *********************************************************************************************************/
      /**
       
       * Metoda pozwala na sprawdzenie ogólnych oraz szczegó&#322;owych kosztów zwi&#261;zanych z wystawieniem
       
       * aukcji przed jej faktycznym wystawieniem. Metoda mo&#380;e s&#322;u&#380;y&#263; tak&#380;e jako symulator poprawno&#347;ci
       
       * wystawienia aukcji, poniewa&#380; struktura pól jak&#261; przyjmuje jako jeden z parametrów jest
       
       * identyczn&#261; z t&#261; przyjmowan&#261; przez doNewAuctionExt.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,41)
       
       *
       
       * @param array $Fields
       
       * @return array
       
       */
      public function CheckNewAuctionExt($Fields) {
          $this->checkConnection();
          return $this->_client->doCheckNewAuctionExt($this->_session['session-handle-part'], $Fields);
      }
      /**
       
       * Metoda pozwala na pobranie listy pól formularza sprzeda&#380;y dost&#281;pnych we wskazanym kraju.
       
       * Wybrane pola mog&#261; nast&#281;pnie pos&#322;u&#380;y&#263; np. do zbudowania i wype&#322;nienia formularza
       
       * wystawienia nowej aukcji z poziomu metody doNewAuctionExt.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,91)
       
       *
       
       * @return array
       
       */
      public function GetSellFormFieldsExt() {
          return $this->_client->doGetSellFormFieldsExt(self::COUNTRY_CODE, '0', $this->_config['allegro_key']);
      }
      /**
       
       * Metoda pozwala na pobranie w porcjach listy pól formularza sprzeda&#380;y dost&#281;pnych we wskazanym kraju.
       
       * Wybrane pola mog&#261; nast&#281;pnie pos&#322;u&#380;y&#263; np. do zbudowania i wype&#322;nienia formularza wystawienia
       
       * nowej aukcji z poziomu metody doNewAuctionExt. Domy&#347;lnie zwracanych jest 50 pierwszych pól.
       
       * Rozmiar porcji pozwala regulowa&#263; parametr package-element, a sterowanie pobieraniem
       
       * kolejnych porcji danych umo&#380;liwia parametr offset.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,92)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetSellFormFieldsExtLimit($Options) {
          return $this->_client->doGetSellFormFieldsExtLimit(self::COUNTRY_CODE, '0', $this->_config['allegro_key'], $Options['offset'], $Options['package-element']);
      }
      /**
       
       * Metoda pozwala na wystawienie nowej aukcji w serwisie. Aby sprawdzi&#263; poprawno&#347;&#263; wystawienia aukcji,
       
       * nale&#380;y nada&#263; jej dodatkowy, lokalny identyfikator (local-id), a nast&#281;pnie zweryfikowa&#263; aukcj&#281; za
       
       * pomoc&#261; metody doVerifyItem (warto&#347;&#263; local-id jest zawsze unikalna w ramach konta danego u&#380;ytkownika).
       
       * Aby przetestowa&#263; poprawno&#347;&#263; wype&#322;nienia kolejnych pól formularza sprzeda&#380;y i/lub sprawdzi&#263; koszta zwi&#261;zane
       
       * z wystawieniem aukcji, bez jej faktycznego wystawiania w serwisie, nale&#380;y skorzysta&#263; z metody doCheckNewAuctionExt.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,113)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function NewAuctionExt($Options) {
          $this->checkConnection();
          return $this->_client->doNewAuctionExt($this->_session['session-handle-part'], $Options['fields'], $Options['private'], $Options['local-id']);
      }
      /**
       
       * Metoda pozwala na wystawienie aukcji w serwisie na podstawie aukcji istniej&#261;cych. Z uwagi na specyfik&#281;
       
       * dzia&#322;ania mechanizmu ponownego wystawiania aukcji - identyfikatory aukcji zwracane na wyj&#347;ciu, to identyfikatory
       
       * aukcji na podstawie których nowe aukcje zosta&#322;y/mia&#322;y zosta&#263; wystawione - nie identyfikatory nowo wystawionych aukcji.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,321)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function SellSomeAgain($Options) {
          $this->checkConnection();
          return $this->_client->doSellSomeAgain($this->_session['session-handle-part'], $Options['sell-items-array'], $Options['sell-starting-time'], $Options['sell-auction-duration'], $Options['sell-option']);
      }
      /**
       
       * Metoda pozwala na sprawdzenie poprawno&#347;ci wystawienia aukcji (utworzonej za pomoc&#261; metody
       
       * doNewAuctionExt, w przypadku gdy przekazano przy jej wywo&#322;aniu warto&#347;&#263; w parametrze local-id)
       
       * z konta zalogowanego u&#380;ytkownika. Warto&#347;&#263; local-id jest zawsze unikalna w ramach konta danego u&#380;ytkownika.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,181)
       
       *
       
       * @param int $LocalID
       
       * @return array
       
       */
      public function VerifyItem($LocalID) {
          $this->checkConnection();
          return $this->_client->doVerifyItem($this->_session['session-handle-part'], $LocalID);
      }
      /**********************************************************************************************************
       
       * Wyszukiwarka i listingi (http://allegro.pl/webapi/documentation.php/theme/id,68)
       
       *********************************************************************************************************/
      /**
       
       * Metoda pozwala na pobranie listy parametrów dost&#281;pnych dla danej kategorii we wskazanym kraju.
       
       * Wybrane parametry mog&#261; nast&#281;pnie pos&#322;u&#380;y&#263; np. do budowy filtra przy listowaniu
       
       * zawarto&#347;ci kategorii z poziomu metody doShowCat.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,90)
       
       *
       
       * @param int $Cat
       
       * @return array
       
       */
      public function GetSellFormAttribs($Cat) {
        set_time_limit(60) ;
          return $this->_client->doGetSellFormAttribs(self::COUNTRY_CODE, $this->_config['allegro_key'], '0', $Cat);
      }
      /**
       
       * Metoda pozwala na pobranie listingu wszystkich aukcji promowanych obecnie w kategoriach specjalnych
       
       * (1000 najnowszych, ko&#324;cz&#261;ce si&#281;, promowane na stronie g&#322;ównej serwisu, promowane na stronach
       
       * poszczególnych kategorii, aukcje Eko-U&#380;ytkowników). Zwracanych jest zawsze 50 aukcji posortowanych
       
       * rosn&#261;co po czasie zako&#324;czenia. Sterowanie pobieraniem kolejnych porcji danych umo&#380;liwia parametr offset.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,100)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function GetSpecialItems($Options) {
          $this->checkConnection();
          return $this->_client->doGetSpecialItems($this->_session['session-handle-part'], $Options['special-type'], $Options['special-group'], $Options['offset']);
      }
      /**
       
       * Metoda pozwala na obs&#322;ug&#281; mechanizmu wyszukiwarki (wraz z opcjami wyszukiwarki zaawansowanej).
       
       * Domy&#347;lnie zwracanych jest 50 pasuj&#261;cych do zapytania aukcji, posortowanych rosn&#261;co po czasie
       
       * zako&#324;czenia (najpierw listowane s&#261; przedmioty z wykupion&#261; opcj&#261; promowania, nast&#281;pnie te niepromowane).
       
       * Dodatkowo zwracana jest równie&#380; informacja o &#322;&#261;cznej liczbie znalezionych aukcji. Rozmiar porcji
       
       * pozwala regulowa&#263; parametr search-limit, a sterowanie pobieraniem kolejnych porcji danych umo&#380;liwia
       
       * parametr search-offset. Metoda zapewnia tak&#380;e obs&#322;ug&#281; mechanizmu s&#322;ów pomijanych przez
       
       * wyszukiwark&#281; - w przypadku gdy s&#322;owo takie b&#281;dzie cz&#281;&#347;ci&#261; zapytania, informacja o tym zwrócona
       
       * zostanie w tablicy search-excluded-words.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,116)
       
       *
       
       * @param array $Query
       
       * @return array
       
       */
      public function Search($Query) {
          $this->checkConnection();
          return $this->_client->doSearch($this->_session['session-handle-part'], $Query);
      }
      /**
       
       * Metoda pozwala na pobranie listingu wszystkich aukcji trwaj&#261;cych obecnie we wskazanej kategorii
       
       * (wraz z dodatkowymi informacjami o kategoriach spokrewnionych z dan&#261; kategori&#261;). Domy&#347;lnie zwracanych
       
       * jest 50 aukcji posortowanych rosn&#261;co po czasie zako&#324;czenia (najpierw listowane s&#261; przedmioty z
       
       * wykupion&#261; opcj&#261; promowania, nast&#281;pnie te niepromowane). Rozmiar porcji pozwala regulowa&#263; parametr
       
       * cat-items-limit, a sterowanie pobieraniem kolejnych porcji danych umo&#380;liwia parametr cats-items-offset.
       
       * (http://allegro.pl/webapi/documentation.php/show/id,362)
       
       *
       
       * @param array $Options
       
       * @return array
       
       */
      public function ShowCat($Options) {
          $this->checkConnection();
          return $this->_client->doShowCat($this->_session['session-handle-part'], $Options['cat-id'], $Options['cat-item-state'], $Options['cat-item-option'], $Options['cat-item-duration-option'], $Options['cat-attrib-fields'], $Options['cat-sort-options'], $Options['cat-items-price'], $Options['cat-items-offset'], $Options['cat-items-limit']);
      }
      /**********************************************************************************************************
       
       * Przydatne funkcje
       
       *********************************************************************************************************/
      /**
       
       * Sprawdzanie po&#322;&#261;czenia oraz poprawnego zalogowania do allegro
       
       */
      private function checkConnection() {
          if (!$this->_session) {
              throw new userException('Nie utworzono po&#322;&#261;czenia z kontem allegro. Nale&#380;y u&#380;y&#263; metody <strong>Login()</strong>');
          }
      }
      /**
       
       * Wywo&#322;anie dowolnej metody przez SOAP
       
       *
       
       * @param string $Method
       
       * @param string/int/array $Data
       
       * @return array
       
       */
      public function getMethod($Method, $Data = array()) {
          return $this->_client->__soapCall($Method, $Data);
      }
      /**
       
       * Metoda pozwala na pobranie identyfikatora sesji po zalogowaniu.
       
       * Do wykorzystania z metod&#261; getMethod
       
       *
       
       * @return string
       
       */
      public function getSession() {
          $this->checkConnection();
          return $this->_session['session-handle-part'];
      }
      /**
       
       * Metoda pozwala na pobranie u&#380;ywanego kodu kraju.
       
       * Do wykorzystania z metod&#261; getMethod
       
       *
       
       * @return int
       
       */
      public function getCountry() {
          return self::COUNTRY_CODE;
      }
      /**
       
       * Metoda pozwala na pobranie aktualnie uzywanego klucza WebAPI
       
       * Do wykorzystania z metod&#261; getMethod
       
       *
       
       * @return string
       
       */
      public function getKey() {
          return $this->_config['allegro_key'];
      }
      /**
       
       * Metoda pozwala na pobranie klucza wersji WebAPI
       
       *
       
       * @return int
       
       */
      public function getVersion() {
          $version = $this->QuerySysStatus(1);
          return $version['ver-key'];
      }
      /**
       
       * Metoda pozwala na pobranie wszystkich aktualnie u&#380;ywanych
       
       * danych konfiguracyjnych
       
       *
       
       * @return array
       
       */
      public function getConfig() {
          return $this->_config;
      }
      /**
       
       * Konwersja obietu na tablic&#281;
       
       *
       
       * @param object $object
       
       * @return array
       
       */
      public function objectToArray($object) {
          if (!is_object($object) && !is_array($object))
              return $object;
          if (is_object($object))
              $object = get_object_vars($object);
          return array_map(array('AllegroWebAPI', 'objectToArray'), $object);
      }
      function object_to_array($object) {

          if (is_array($object) || is_object($object)) {
              $array = array();
              foreach ($object as $key => $value) {
                  $array[$key] = $this->object_to_array($value);
              }
              return $array;
          }
          return $object;
      }
      /**
       
       * Konwertowanie sekund na czas
       
       *
       
       * @param int $Secounds
       
       * @return string
       
       */
      public function Sec2Time($Secounds) {
          $Time = new DateTime('@' . $Secounds, new DateTimeZone('UTC'));
          $GetTime = array('dni' => $Time->format('z'), 'godzin' => $Time->format('G'), 'minut' => $Time->format('i'), 'sekund' => $Time->format('s'));
          if ($GetTime['dni'] > 1) {
              $TimeLeft = $GetTime['dni'] . " dni";
          } elseif ($GetTime['dni'] == 1) {
              $TimeLeft = $GetTime['dni'] . " dzie&#324;";
          } elseif ($GetTime['godzin'] > 1) {
              $TimeLeft = $GetTime['godzin'] . " godzin";
          } elseif ($GetTime['godzin'] == 1) {
              $TimeLeft = $GetTime['godzin'] . " godzina";
          } elseif ($GetTime['minut'] > 1) {
              $TimeLeft = $GetTime['minut'] . " minut";
          } elseif ($GetTime['minut'] == 1) {
              $TimeLeft = $GetTime['minut'] . " minuta";
          } elseif ($GetTime['sekund'] > 1) {
              $TimeLeft = $GetTime['sekund'] . " sekund";
          } elseif ($GetTime['sekund'] == 1) {
              $TimeLeft = $GetTime['sekund'] . " sekunda";
          }
          return $TimeLeft;
      }
      /**
       
       * Pozosta&#322;y czas do ko&#324;ca aukcji
       
       *
       
       * @param int $Secounds
       
       * @return string
       
       */
      public function EndDate($Secounds) {
          $GetDay = date("N", time() + $Secounds);
          $num = array("1", "2", "3", "4", "5", "6", "7");
          $pl = array("Poniedzia&#322;ek", "Wtorek", "&#346;roda", "Czwartek", "Pi&#261;tek", "Sobota", "Niedziela");
          $GetDay = str_replace($num, $pl, $GetDay);
          $GetDate = date("d-m-Y, H:i:s", time() + $Secounds);
          return $GetDay . " " . $GetDate;
      }
      /**
       
       * Punktacja u&#380;ytkowników
       
       *
       
       * @param int $Stars
       
       * @return string
       
       */
      public function UserStars($Stars) {
          $IconHost = "http://static.allegrostatic.pl/site_images/1/0/stars/";
          if ($Stars > 12500) {
              $Star = "star3125";
              $While = 4;
          } elseif ($Stars > 12499) {
              $Star = "star3125";
              $While = 4;
          } elseif ($Stars > 9374) {
              $Star = "star3125";
              $While = 3;
          } elseif ($Stars > 6249) {
              $Star = "star3125";
              $While = 2;
          } elseif ($Stars > 3124) {
              $Star = "star3125";
              $While = 1;
          } elseif ($Stars > 2499) {
              $Star = "star625";
              $While = 4;
          } elseif ($Stars > 1874) {
              $Star = "star625";
              $While = 3;
          } elseif ($Stars > 1249) {
              $Star = "star625";
              $While = 2;
          } elseif ($Stars > 624) {
              $Star = "star625";
              $While = 1;
          } elseif ($Stars > 499) {
              $Star = "star125";
              $While = 4;
          } elseif ($Stars > 374) {
              $Star = "star125";
              $While = 3;
          } elseif ($Stars > 249) {
              $Star = "star125";
              $While = 2;
          } elseif ($Stars > 124) {
              $Star = "star125";
              $While = 1;
          } elseif ($Stars > 99) {
              $Star = "star25";
              $While = 4;
          } elseif ($Stars > 74) {
              $Star = "star25";
              $While = 3;
          } elseif ($Stars > 49) {
              $Star = "star25";
              $While = 2;
          } elseif ($Stars > 24) {
              $Star = "star25";
              $While = 1;
          } elseif ($Stars > 19) {
              $Star = "star5";
              $While = 4;
          } elseif ($Stars > 14) {
              $Star = "star5";
              $While = 3;
          } elseif ($Stars > 9) {
              $Star = "star5";
              $While = 2;
          } elseif ($Stars > 4) {
              $Star = "star5";
              $While = 1;
          } elseif ($Stars > 3) {
              $Star = "star1";
              $While = 4;
          } elseif ($Stars > 2) {
              $Star = "star1";
              $While = 3;
          } elseif ($Stars > 1) {
              $Star = "star1";
              $While = 2;
          } elseif ($Stars > 0) {
              $Star = "star1";
              $While = 1;
          } elseif ($Stars > -1) {
              $Star = "star1";
              $While = 0;
          }
          for ($i = 1; $i <= $While; $i++) {
              $GetStars .= "<img src='" . $IconHost . $Star . ".gif' title='" . $Stars . " pkt. allegro' style='vertical-align:middle' alt='' />";
          }
          return $GetStars;
      }
  }
?>