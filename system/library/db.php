<?php
class DB {
	private $driver;

    private $log_state = false;
    private $loger = false;
    private $timing = false;
    private $registry;
    private $total_time = 0;
    private $counter = 0;

	
	public function __construct($driver, $hostname, $username, $password, $database,$registry = false) {
		if (file_exists(DIR_DATABASE . $driver . '.php')) {
			require_once(DIR_DATABASE . $driver . '.php');
		} else {
			exit('Error: Could not load database file ' . $driver . '!');
		}
				
		$this->driver = new $driver($hostname, $username, $password, $database);
        $this->registry = $registry;
	}

    public function setDebugger($debugger)
    {
        $this->driver->setDebugger($debugger);

    }
		
  	public function query($sql) {

        if($this->loger AND $this->log_state){
            $start=$this->timing->getElapsedTime();
            $retruned_data=$this->driver->query($sql);

            $backtrace = debug_backtrace();


            if(isset($backtrace[0]["file"])){
                $file=explode('/', $backtrace[0]["file"]);
                $file=array_pop($file);
                $line=$backtrace[0]["line"];
                $stop=(float)$this->timing->getElapsedTime()-(float)$start;
                $this->total_time+=$stop;
                $this->loger->info('połączenie! funkcja wywołująca: '.$file.' na lini: '.$line.' czas od początku: '.$this->total_time.' czas egzekucji zapytania: '.$stop.'  numer: '.$this->counter.'  zapytanie:'.$sql);
            }else{
                $this->loger->info('połączenie!   numer: '.$this->counter.'  zapytanie:'.$sql);
            }



            $this->counter++;

            return $retruned_data;
        }else{
            return $this->driver->query($sql);
        }
  	}
	
	public function escape($value) {
		return $this->driver->escape($value);
	}
	
  	public function countAffected() {
		return $this->driver->countAffected();
  	}

  	public function getLastId() {
		return $this->driver->getLastId();
  	}

    public function setLog() {

        $this->loger=$this->registry->get('logger');
        $this->timing=$this->registry->get('timing');
        $this->log_state=true;

    }
}
?>