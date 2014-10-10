<?php  
class ControllerModuleExtendedFilter extends Controller {
	protected function index($setting) {
		$this->language->load('module/category');


        $this->data['heading_title'] = $this->language->get('heading_title');


        if (isset($this->request->get['path'])) {
            $path = $this->request->get['path'];
            $parts = explode('_', (string)$this->request->get['path']);
        } else {
            $parts = array();
            $path = '';
        }

        if ($parts) {
            $this->data['category_id'] = array_pop($parts);
        } else {
            $this->data['category_id'] = 0;
        }

        $url = '';

        if($path)
        {
            $url = '&path='.$path;
        }

        if (isset($this->request->get['filter'])) {
            $url .= '&filter=' . $this->request->get['filter'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        $this->data['action'] = $this->url->link('product/category',$url);


       /* if (isset($this->request->get['filter_price_min'])) {
            $url .= '&filter_price_min=' . $this->request->get['filter_price_min'];
        }
        if (isset($this->request->get['filter_price_max'])) {
            $url .= '&filter_price_max=' . $this->request->get['filter_price_max'];
        }
        if (isset($this->request->get['filter_attribute'])) {
            $url .= '&filter_attribute=' . $this->request->get['filter_attribute'];
        }
        if (isset($this->request->get['filter_option'])) {
            $url .= '&filter_option=' . $this->request->get['filter_option'];
        }*/



        $this->load->model('catalog/category');
        $this->load->model('catalog/option');
        $this->load->model('catalog/attribute');
        $this->load->model('catalog/extended_filter');

        $min = 0;

        $query = $this->db->query("SELECT MAX(price) as max, MIN(price) as min FROM product");


        if(isset($query->row['max']) AND (int)$query->row['max'] >= 100)
        {
            $max = $query->row['max'];

        }
        else
        {
            $max = 2000;

        }

        $max = (int)$this->currency->convert($max,$this->config->get('config_currency'),$this->currency->getCode());
        $max = round($max);

        $step = 20;
        $zeros = 0;

        $f = function($max)use(&$f,&$zeros)
        {
            // reszta jest liczbą całkowitą

            if(round( ($max/10), 0, PHP_ROUND_HALF_DOWN) > 0)
            {
                $zeros ++;
                return $f(round($max/10));
            }
            else
            {
                $tail = '';
                for($i=0;$i<$zeros;$i++)
                {
                    $tail .= '0';
                }

                return $max.$tail;
            }
        };

        $max = $f($max);

        $filters = $this->model_catalog_extended_filter->getExtendedFiltersByCategory($this->data['category_id']);

        $this->data['filters'] = array();

        foreach($filters as $filter)
        {
            if($filter['type'] == 'option')
            {
                $option_values = $this->model_catalog_option->getOptionValuesByCategory($filter['attribute_or_option_id'],$this->data['category_id']);

                $name = isset($filter['description'][$this->config->get('config_language_id')]['name'])?$filter['description'][$this->config->get('config_language_id')]['name']:'';

                if(isset($this->request->get['filter_option'][$filter['attribute_or_option_id']]))
                {
                    $selected_value = $this->request->get['filter_option'][$filter['attribute_or_option_id']];
                }
                else
                {
                    $selected_value = array();
                }

                $this->data['filters'][] = array(
                   'type' => $filter['type'],
                   'name' => $name,
                   'values' => $option_values,
                    'id' => $filter['attribute_or_option_id'],
                    'selected' => $selected_value,
                );
            }
            elseif($filter['type'] == 'attribute')
            {
                $atr_values = $this->model_catalog_attribute->getAttributeValuesByCategory($filter['attribute_or_option_id'],$this->data['category_id']);

                $name = isset($filter['description'][$this->config->get('config_language_id')]['name'])?$filter['description'][$this->config->get('config_language_id')]['name']:'';

                if(isset($this->request->get['filter_attribute'][$filter['attribute_or_option_id']]))
                {
                    $selected_value = $this->request->get['filter_attribute'][$filter['attribute_or_option_id']];
                }
                else
                {
                    $selected_value = array();
                }

                $this->data['filters'][] = array(
                    'type' => $filter['type'],
                    'name' => $name,
                    'values' => $atr_values,
                    'id' => $filter['attribute_or_option_id'],
                    'selected' => $selected_value,
                );
            }
            elseif($filter['type'] == 'price')
            {
                $name = isset($filter['description'][$this->config->get('config_language_id')]['name'])?$filter['description'][$this->config->get('config_language_id')]['name']:'';

                $this->data['filters'][] = array(
                    'type' => 'price',
                    'name' => $name,
                    'min_current' => isset($this->request->get['filter_price_min'])?$this->request->get['filter_price_min']:$min,
                    'max_current' => isset($this->request->get['filter_price_max'])?$this->request->get['filter_price_max']:$max,
                    'min' => $min,
                    'max' => $max,

                );
            }
        }






        $step = $this->currency->convert($step,$this->config->get('config_currency'),$this->currency->getCode());
        // $step = round($step);
        $filtering = false;
        $price_min_values = array();

        $price_max_values = array();

        for($i=0; $i < $max ; $i+=$step) {
            array_push($price_min_values,(string)$i);
            array_push($price_max_values,(string)($i+$step));
        }
        $filters = array();

        $filters['price_min_value'] = $price_min_values;
        $filters['price_max_value'] = $price_max_values;

        $filters['default_current_price_min']=0;


        $filters['default_current_price_max']=$max;

        $filters['number_price_sections'] = count($price_max_values)-1;

							





		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/extended_filter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/extended_filter.tpl';
		} else {
			$this->template = 'default/template/module/extended_filter.tpl';
		}
		
		$this->render();
  	}
}
?>