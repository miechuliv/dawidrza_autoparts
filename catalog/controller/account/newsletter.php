<?php 
class ControllerAccountNewsletter extends Controller {

    public function confirm()
    {
        if (!$this->customer->isLogged()) {
            $this->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load->language('account/newsletter_confirm');
        $success  = false;
        $error = false;

        if(isset($this->request->get['key']))
        {
                $this->load->model('account/customer');

                $checkIsKeyCorrect = $this->model_account_customer->checkConfirmKey($this->request->get['key'],$this->customer->getId());

                if(!empty($checkIsKeyCorrect))
                {
                    // confirm newsletter
                    $this->model_account_customer->editNewsletter(1);
                    // remove key
                    $this->model_account_customer->removeConfirmKey($this->customer->getId());

                    $success = $this->language->get('text_newsletter_success');
                }
                else
                {
                    $error = $this->language->get('error_key_mismatch');
                }
        }
        else
        {
            $error = $this->language->get('error_no_key');
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_account'),
            'href'      => $this->url->link('account/account', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['success'] = $success;
        $this->data['error'] = $error;

        $this->data['heading_title'] = $this->language->get('heading_title');


        $this->data['button_back'] = $this->language->get('button_back');


        $this->data['back'] = $this->url->link('account/account', '', 'SSL');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/newsletter_confirm.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/newsletter_confirm.tpl';
        } else {
            $this->template = 'default/template/account/newsletter_confirm.tpl';
        }

        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );

        $this->response->setOutput($this->render());
    }

	public function index() {
		if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->url->link('account/newsletter', '', 'SSL');
	  
	  		$this->redirect($this->url->link('account/login', '', 'SSL'));
    	} 
		
		$this->language->load('account/newsletter');
    	
		$this->document->setTitle($this->language->get('heading_title'));
				
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->load->model('account/customer');

            // jesli ma wlaczyc newsletter to go nie wysyła tylko wysyla mail z linkiem do aktywacji newslettera

            if($this->request->post['newsletter'])
            {
                // mail
                $this->language->load('mail/customer');

                $this->load->model('account/customer_group');

               /* $customer_id =
                $customer_group_id = $this->config->get('config_customer_group_id');
                $customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id); */

                $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

                //$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";
                $message = '';


                    // wysyłamy link potwierdzajacy do customera
                    $key = substr(sha1(uniqid(mt_rand(), true)), 0, 10);
                    $this->db->query("UPDATE ".DB_PREFIX."customer SET newsletter_confirm_key = '".$this->db->escape($key)."'  ");

                    $message .= $this->language->get('text_newsletter_confirm_link') ."\n";
                    $message .= $this->url->link('account/newsletter/confirm','&key='.$key) ."\n\n";


                $message .= $this->language->get('text_thanks') . "\n";
                $message .= $this->config->get('config_name');

                $mail = new Mail();
                $mail->protocol = $this->config->get('config_mail_protocol');
                $mail->parameter = $this->config->get('config_mail_parameter');
                $mail->hostname = $this->config->get('config_smtp_host');
                $mail->username = $this->config->get('config_smtp_username');
                $mail->password = $this->config->get('config_smtp_password');
                $mail->port = $this->config->get('config_smtp_port');
                $mail->timeout = $this->config->get('config_smtp_timeout');
                $mail->setTo($this->customer->getEmail());
                $mail->setFrom($this->config->get('config_email'));
                $mail->setSender($this->config->get('config_name'));
                $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
                $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
                $mail->send();

            }
            else
            {
                $this->model_account_customer->editNewsletter($this->request->post['newsletter']);
            }

			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('account/account', '', 'SSL'));
		}

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),       	
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_newsletter'),
			'href'      => $this->url->link('account/newsletter', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');

    	$this->data['action'] = $this->url->link('account/newsletter', '', 'SSL');
		
		$this->data['newsletter'] = $this->customer->getNewsletter();
		
		$this->data['back'] = $this->url->link('account/account', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/newsletter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/newsletter.tpl';
		} else {
			$this->template = 'default/template/account/newsletter.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);
						
		$this->response->setOutput($this->render());			
  	}
}
?>