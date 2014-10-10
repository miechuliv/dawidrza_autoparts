<?php
abstract class Controller {
	protected $registry;	
	protected $id;
	protected $layout;
	protected $template;
	protected $children = array();
	protected $data = array();
	protected $output;
	
	public function __construct($registry) {
		$this->registry = $registry;

                $this->load->library('play');
                $play = new Play($registry);
                $registry->set('play', $play);
            

        if($registry->has('storage'))
        {
        $storage = $this->registry->get('storage');

        $globalData = $storage::getStorage();


        foreach($globalData as $key => $value)
        {
             $this->updateData($key,$value);
        }
        }

        if($registry->has('debugger'))
        {
            $debugger = $registry->get('debugger');

            $debugger->addController($this);
        }
	}



    public function getAllData()
    {
         return $this->data;
    }
	
	public function __get($key) {
        return $this->registry->get($key);
		/*$lib =  $this->registry->get($key);
        $in = new Interceptor($lib);

        return $in;*/
	}



    public function __set($key, $value) {
		$this->registry->set($key, $value);
	}
			
	protected function forward($route, $args = array()) {
		return new Action($route, $args);
	}

	protected function redirect($url, $status = 302) {
		header('Status: ' . $status);
		header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url));
		exit();				
	}
	
	protected function getChild($child, $args = array()) {

		$action = new Action($child, $args);
	
		global $vqmod;
		if (file_exists($vqmod->modCheck($action->getFile()))) {
			require_once($vqmod->modCheck($action->getFile()));

			$class = $action->getClass();

			$controller = new $class($this->registry);

			$controller->{$action->getMethod()}($action->getArgs());
			
			return $controller->output;
		} else {
			trigger_error('Error: Could not load controller ' . $child . '!');
			exit();					
		}		
	}
	
	protected function render() {
		foreach ($this->children as $child) {
			$this->data[basename($child)] = $this->getChild($child);
		}
		
		
		global $vqmod;
		$file = $vqmod->modCheck(DIR_TEMPLATE . $this->template);
		if (file_exists($file)) {
		
			extract($this->data);
			
      		ob_start();
      
	  		require($file);
      
	  		//$this->output = ob_get_contents();
            $this->output = ob_get_contents();

      		ob_end_clean();
      		
			return $this->output;
    	} else {
			trigger_error('Error: Could not load template ' . DIR_TEMPLATE . $this->template . '!');
			exit();				
    	}
	}

    public function isCategory($request)
    {


        if(isset($request->get['route']))
        {
            if(strpos($request->get['route'],'category'))
            {
                return true;
            }
            else
            {
                return false;
            }

        }
        else
        {
            return false;
        }
    }

    public function isProduct($request)
    {


        if(isset($request->get['route']))
        {
            if(strpos($request->get['route'],'product'))
            {
                return true;
            }
            else
            {
                return false;
            }

        }
        else
        {
            return false;
        }
    }



    protected function updateData($key,$data){


      if(is_array($data))
      {
          if(!isset($this->data[$key]) OR !is_array($this->data[$key]))
          {
              $this->data[$key] = array();
          }
          $this->data[$key] = array_merge($this->data[$key],$data);
      }
      else
      {
          $this->data[$key] = $data;
      }

    }

    protected function updateGlobalData($key,$data,$updateThis = true){


        $storage = $this->registry->get('storage');

        $storage::addItem($key,$data);

        if($updateThis)
        {
            $this->updateData($key,$data);
        }


    }

    public function setFlashMessenger($msg)
    {
           $this->session->data['flash'] = $msg;
    }

    public function isFlashMessenger()
    {
        if(isset($this->session->data['flash']))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public  function clearFlashMessenger()
    {
        if(isset($this->session->data['flash']))
        {
            unset($this->session->data['flash']);
        }
    }

    public function fastTranslate($string)
    {
         return  $this->language->get($string);
    }

    public function getPath()
    {

         if(isset($this->request->get['path']))
         {
             return $this->request->get['path'];
         }
         else
         {
             return false;
         }
    }

    public function isHome()
    {
         $path = $this->getPath();

         if($path)
         {
             return false;
         }
         else
         {
             return true;
         }


    }

    protected function setFields($fields,$data,$request_type = 'post')
    {
        foreach($fields as $field)
        {
            if (isset($this->request->$request_type[$field])) {
                $this->data[$field] = $this->request->$request_type[$field];
            } elseif (!empty($data)) {
                $this->data[$field] = $data[$field];
            } else {
                $this->data[$field] = '';
            }
        }
    }




}

class Interceptor
{
    private $lib;

    function __construct($lib)
    {
        $this->lib = $lib;
    }

    function __call($func,$args)
    {

        // trzeba zamienić liste argumentów na string <- rekusrywnie , a następnie spróbowąć wcisnąc do funkcji za pomocą eval...
        // zamist tego można tez użyć http://docs.php.net/manual/en/reflectionfunction.invokeargs.php
        //$r = new ReflectionClass($this->lib);
        $method = new ReflectionFunction($func);

        return $method->invokeArgs($this->lib,$args);

    }

    function __get($key)
    {

        return $this->lib->$key;
    }

    function __set($key,$val)
    {

        $this->lib->$key = $val;
    }

}
?>