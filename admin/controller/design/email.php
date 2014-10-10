<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 27.11.13
 * Time: 11:46
 * To change this template use File | Settings | File Templates.
 */

class ControllerDesignEmail extends Controller{

    public function index()
    {

        $order_status_id = 1;
        $comment = 'Komentarz do zamówienia';
        $notify = true;

        $q = $this->db->query("SELECT MAX(order_id) as order_id FROM " . DB_PREFIX . "order_product ");

        $order_id = $q->row['order_id'];

        $order_info = $this->getOrder($order_id);


        $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

        foreach ($order_product_query->rows as $order_product) {
            $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");

            $order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product['order_product_id'] . "'");

            foreach ($order_option_query->rows as $option) {
                $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
            }
        }

        $this->cache->delete('product');

        // Downloads
        $order_download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "'");



        $order_voucher_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");





        // Order Totals
        $order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");

        /* foreach ($order_total_query->rows as $order_total) {
            $this->load->model('total/' . $order_total['code']);

            if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
                $this->{'model_total_' . $order_total['code']}->confirm($order_info, $order_total);
            }
        } */

        // Send out order confirmation mail
        $language = new Language('polish');
     //   $thlanguage->load($order_info['language_filename']);
        $language->load('design/email');

        $order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");

        if ($order_status_query->num_rows) {
            $order_status = $order_status_query->row['name'];
        } else {
            $order_status = '';
        }
        // HTML Mail
        $template = new Template();

        $template->data['title'] = sprintf($language->get('text_new_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);


        $template->data['text_greeting'] = sprintf($language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
        $template->data['text_link'] = $language->get('text_new_link');
        $template->data['text_download'] = $language->get('text_new_download');
        $template->data['text_order_detail'] = $language->get('text_new_order_detail');
        $template->data['text_instruction'] = $language->get('text_new_instruction');
        $template->data['text_order_id'] = $language->get('text_new_order_id');
        $template->data['text_date_added'] = $language->get('text_new_date_added');
        $template->data['text_payment_method'] = $language->get('text_new_payment_method');
        $template->data['text_shipping_method'] = $language->get('text_new_shipping_method');
        $template->data['text_email'] = $language->get('text_new_email');
        $template->data['text_telephone'] = $language->get('text_new_telephone');
        $template->data['text_ip'] = $language->get('text_new_ip');
        $template->data['text_payment_address'] = $language->get('text_new_payment_address');
        $template->data['text_shipping_address'] = $language->get('text_new_shipping_address');
        $template->data['text_product'] = $language->get('text_new_product');
        $template->data['text_model'] = $language->get('text_new_model');
        $template->data['text_quantity'] = $language->get('text_new_quantity');
        $template->data['text_price'] = $language->get('text_new_price');
        $template->data['text_total'] = $language->get('text_new_total');
        $template->data['text_footer'] = $language->get('text_new_footer');
        $template->data['text_powered'] = $language->get('text_new_powered');

        $template->data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
        $template->data['store_name'] = $order_info['store_name'];
        $template->data['store_url'] = $order_info['store_url'];
        $template->data['customer_id'] = $order_info['customer_id'];
        $template->data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id;

        if ($order_download_query->num_rows) {
            $template->data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
        } else {
            $template->data['download'] = '';
        }

        $template->data['order_id'] = $order_id;
        $template->data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));
        $template->data['payment_method'] = $order_info['payment_method'];
        $template->data['shipping_method'] = $order_info['shipping_method'];
        $template->data['email'] = $order_info['email'];
        $template->data['telephone'] = $order_info['telephone'];
        $template->data['ip'] = $order_info['ip'];

        if ($comment && $notify) {
            $template->data['comment'] = nl2br($comment);
        } else {
            $template->data['comment'] = '';
        }

        if ($order_info['payment_address_format']) {
            $format = $order_info['payment_address_format'];
        } else {
            $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
        }

        $find = array(
            '{firstname}',
            '{lastname}',
            '{company}',
            '{address_1}',
            '{address_2}',
            '{city}',
            '{postcode}',
            '{zone}',
            '{zone_code}',
            '{country}'
        );

        $replace = array(
            'firstname' => $order_info['payment_firstname'],
            'lastname'  => $order_info['payment_lastname'],
            'company'   => $order_info['payment_company'],
            'address_1' => $order_info['payment_address_1'],
            'address_2' => $order_info['payment_address_2'],
            'city'      => $order_info['payment_city'],
            'postcode'  => $order_info['payment_postcode'],
            'zone'      => $order_info['payment_zone'],
            'zone_code' => $order_info['payment_zone_code'],
            'country'   => $order_info['payment_country']
        );

        $template->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

        if ($order_info['shipping_address_format']) {
            $format = $order_info['shipping_address_format'];
        } else {
            $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
        }

        $find = array(
            '{firstname}',
            '{lastname}',
            '{company}',
            '{address_1}',
            '{address_2}',
            '{city}',
            '{postcode}',
            '{zone}',
            '{zone_code}',
            '{country}'
        );

        $replace = array(
            'firstname' => $order_info['shipping_firstname'],
            'lastname'  => $order_info['shipping_lastname'],
            'company'   => $order_info['shipping_company'],
            'address_1' => $order_info['shipping_address_1'],
            'address_2' => $order_info['shipping_address_2'],
            'city'      => $order_info['shipping_city'],
            'postcode'  => $order_info['shipping_postcode'],
            'zone'      => $order_info['shipping_zone'],
            'zone_code' => $order_info['shipping_zone_code'],
            'country'   => $order_info['shipping_country']
        );

        $template->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

        // Products
        $template->data['products'] = array();

        foreach ($order_product_query->rows as $product) {
            $option_data = array();

            $order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

            foreach ($order_option_query->rows as $option) {
                if ($option['type'] != 'file') {
                    $value = $option['value'];
                } else {
                    $value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
                }

                $option_data[] = array(
                    'name'  => $option['name'],
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                );
            }

            $template->data['products'][] = array(
                'name'     => $product['name'],
                'model'    => $product['model'],
                'option'   => $option_data,
                'quantity' => $product['quantity'],
                'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
            );
        }

        // Vouchers
        $template->data['vouchers'] = array();

        foreach ($order_voucher_query->rows as $voucher) {
            $template->data['vouchers'][] = array(
                'description' => $voucher['description'],
                'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
            );
        }

        $template->data['totals'] = $order_total_query->rows;


        $html = $template->fetch('mail/order.tpl');


       $this->data['template'] = $html;

        $this->data['token'] = $this->session->data['token'];

        $this->template = 'design/email.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    public function getOrder($order_id) {
        $order_query = $this->db->query("SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

        if ($order_query->num_rows) {
            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

            if ($country_query->num_rows) {
                $payment_iso_code_2 = $country_query->row['iso_code_2'];
                $payment_iso_code_3 = $country_query->row['iso_code_3'];
            } else {
                $payment_iso_code_2 = '';
                $payment_iso_code_3 = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

            if ($zone_query->num_rows) {
                $payment_zone_code = $zone_query->row['code'];
            } else {
                $payment_zone_code = '';
            }

            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

            if ($country_query->num_rows) {
                $shipping_iso_code_2 = $country_query->row['iso_code_2'];
                $shipping_iso_code_3 = $country_query->row['iso_code_3'];
            } else {
                $shipping_iso_code_2 = '';
                $shipping_iso_code_3 = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

            if ($zone_query->num_rows) {
                $shipping_zone_code = $zone_query->row['code'];
            } else {
                $shipping_zone_code = '';
            }

            $this->load->model('localisation/language');

            $language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

            if ($language_info) {
                $language_code = $language_info['code'];
                $language_filename = $language_info['filename'];
                $language_directory = $language_info['directory'];
            } else {
                $language_code = '';
                $language_filename = '';
                $language_directory = '';
            }

            return array(
                'order_id'                => $order_query->row['order_id'],
                'invoice_no'              => $order_query->row['invoice_no'],
                'invoice_prefix'          => $order_query->row['invoice_prefix'],
                'store_id'                => $order_query->row['store_id'],
                'store_name'              => $order_query->row['store_name'],
                'store_url'               => $order_query->row['store_url'],
                'customer_id'             => $order_query->row['customer_id'],
                'firstname'               => $order_query->row['firstname'],
                'lastname'                => $order_query->row['lastname'],
                'telephone'               => $order_query->row['telephone'],
                'fax'                     => $order_query->row['fax'],
                'email'                   => $order_query->row['email'],
                'payment_firstname'       => $order_query->row['payment_firstname'],
                'payment_lastname'        => $order_query->row['payment_lastname'],
                'payment_company'         => $order_query->row['payment_company'],
                'payment_company_id'      => $order_query->row['payment_company_id'],
                'payment_tax_id'          => $order_query->row['payment_tax_id'],
                'payment_address_1'       => $order_query->row['payment_address_1'],
                'payment_address_2'       => $order_query->row['payment_address_2'],
                'payment_postcode'        => $order_query->row['payment_postcode'],
                'payment_city'            => $order_query->row['payment_city'],
                'payment_zone_id'         => $order_query->row['payment_zone_id'],
                'payment_zone'            => $order_query->row['payment_zone'],
                'payment_zone_code'       => $payment_zone_code,
                'payment_country_id'      => $order_query->row['payment_country_id'],
                'payment_country'         => $order_query->row['payment_country'],
                'payment_iso_code_2'      => $payment_iso_code_2,
                'payment_iso_code_3'      => $payment_iso_code_3,
                'payment_address_format'  => $order_query->row['payment_address_format'],
                'payment_method'          => $order_query->row['payment_method'],
                'payment_code'            => $order_query->row['payment_code'],
                'shipping_firstname'      => $order_query->row['shipping_firstname'],
                'shipping_lastname'       => $order_query->row['shipping_lastname'],
                'shipping_company'        => $order_query->row['shipping_company'],
                'shipping_address_1'      => $order_query->row['shipping_address_1'],
                'shipping_address_2'      => $order_query->row['shipping_address_2'],
                'shipping_postcode'       => $order_query->row['shipping_postcode'],
                'shipping_city'           => $order_query->row['shipping_city'],
                'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
                'shipping_zone'           => $order_query->row['shipping_zone'],
                'shipping_zone_code'      => $shipping_zone_code,
                'shipping_country_id'     => $order_query->row['shipping_country_id'],
                'shipping_country'        => $order_query->row['shipping_country'],
                'shipping_iso_code_2'     => $shipping_iso_code_2,
                'shipping_iso_code_3'     => $shipping_iso_code_3,
                'shipping_address_format' => $order_query->row['shipping_address_format'],
                'shipping_method'         => $order_query->row['shipping_method'],
                'shipping_code'           => $order_query->row['shipping_code'],
                'comment'                 => $order_query->row['comment'],
                'total'                   => $order_query->row['total'],
                'order_status_id'         => $order_query->row['order_status_id'],
                'order_status'            => $order_query->row['order_status'],
                'language_id'             => $order_query->row['language_id'],
                'language_code'           => $language_code,
                'language_filename'       => $language_filename,
                'language_directory'      => $language_directory,
                'currency_id'             => $order_query->row['currency_id'],
                'currency_code'           => $order_query->row['currency_code'],
                'currency_value'          => $order_query->row['currency_value'],
                'ip'                      => $order_query->row['ip'],
                'forwarded_ip'            => $order_query->row['forwarded_ip'],
                'user_agent'              => $order_query->row['user_agent'],
                'accept_language'         => $order_query->row['accept_language'],
                'date_modified'           => $order_query->row['date_modified'],
                'date_added'              => $order_query->row['date_added']
            );
        } else {
            return false;
        }
    }

}