<?php

class Main extends MY_Controller {

    public $lng;
    public $limbi;

    function __construct() {
        parent::__construct();

        $this->load->library('cart');
        $this->load->helper('url');
        $this->limbi = array(
            'default' => 'ru',
            'ro' => 'romanian',
            'ru' => 'russian',
            'en' => 'english'
        );
        if ($this->session->userdata('limba') == '') {
            $this->session->set_userdata(array('limba' => $this->limbi['default']));
        }

        $this->lng = $this->session->userdata('limba');
    }

    public function limba($limba) {

        if ($limba != NULL) {
            $this->session->set_userdata(array('limba' => $limba));
            //echo $limba.$this->session->userdata('limba');
            redirect(base_url() . $limba, 'location', 301);
        } else {
            redirect(base_url() . $this->limbi['default'], 'location', 301);
        }
    }

    public function _check_lang() {
        if (isset($this->limbi[$this->uri->segment(1)]) && $this->session->userdata('limba') != $this->uri->segment(1)) {
            $this->limba($this->uri->segment(1));
        } else if (!isset($this->limbi[$this->uri->segment(1)])) {
            $this->limba($this->limbi['default']);
        }
    }

    public function index() {
        $this->_check_lang();
        //echo $this->lng;

        $data['mesaj1'] = FALSE;
        $data['slider'] = $this->mysql->get_All('slider', array(), '', '', 'ord', 'asc');

        $data['categorii'] = $this->mysql->get_All('catalog', array('parent' => 0), '', '', 'ord', 'asc');
        /* all pages */
        $data['pages'] = $this->mysql->get_All('pages', array('parent' => 0), '', '', 'ord', 'asc');
        $data['setting'] = $this->mysql->get_row('settings', array('id' => 1));
        $data['infos'] = $this->mysql->get_All('text', array('parent' => 0), '', '', 'ord', 'asc');
        $data['arhiva'] = $this->mysql->get_All('news', array('parent' => 0), '', '', 'data', 'desc');
        $data['parteneri'] = $this->mysql->get_All('banners', array('parent' => 0), '3', '0', 'ord', 'asc');
        $data['arhiva_f'] = $this->mysql->get_All('news', array('parent' => 0), '4', '0', 'data', 'desc');
        $data['last_products'] = $this->mysql->get_All('catalog', array('parent >' => 0), '12', '', 'ord', 'asc');
        /* all pages */


        if ($this->input->post('send1') == 'send') {
            $setting = $this->mysql->get_row('settings', array('id' => 1));
            $msg = "Nume : " . $this->input->post('nume') . "<br />";
            $msg .= "Email" . $this->input->post('email') . "<br />";
            $msg .= "Mesaj : " . $this->input->post('msg') . "<br />";

            $this->send_mail($setting['mail'], 'Biless.md: Contact', $msg);
            $data['mesaj1'] = true;
        }


        $this->display->frontend('home', $data);
    }

    public function search() {
        $this->_check_lang();
        $data['mesaj1'] = FALSE;
        
        $data['search_products'] = $this->mysql->search_products($_GET['word'],  $this->lng);
        $data['search_news'] = $this->mysql->search_news($_GET['word'],  $this->lng);
//      / echo '<pre>'; print_r($data['recomandari']); echo '</pre>';
       /* all pages */
        $data['pages'] = $this->mysql->get_All('pages', array('parent' => 0), '', '', 'ord', 'asc');
        $data['setting'] = $this->mysql->get_row('settings', array('id' => 1));
        $data['categorii'] = $this->mysql->get_All('catalog', array('parent' => 0), '', '', 'ord', 'asc');
        $data['infos'] = $this->mysql->get_All('text', array('parent' => 0), '', '', 'ord', 'asc');
        $data['arhiva'] = $this->mysql->get_All('news', array('parent' => 0), '', '', 'data', 'desc');
        $data['parteneri'] = $this->mysql->get_All('banners', array('parent' => 0), '3', '0', 'ord', 'asc');
        $data['arhiva_f'] = $this->mysql->get_All('news', array('parent' => 0), '4', '0', 'data', 'desc');
        $data['last_products'] = $this->mysql->get_All('catalog', array('parent >' => 0), '12', '', 'ord', 'asc');
        /* all pages */


        if ($this->input->post('send1') == 'send') {
            $setting = $this->mysql->get_row('settings', array('id' => 1));
            $msg = "Nume : " . $this->input->post('nume') . "<br />";
            $msg .= "Email" . $this->input->post('email') . "<br />";
            $msg .= "Mesaj : " . $this->input->post('msg') . "<br />";

            $this->send_mail($setting['mail'], 'Biless.md: Contact', $msg);
            $data['mesaj1'] = true;
        }


        $this->display->frontend('search', $data);
    }

    public function about() {
        $this->_check_lang();
        $data['mesaj1'] = FALSE;
        /* all pages */
        $data['categorii'] = $this->mysql->get_All('catalog', array('parent' => 0), '', '', 'ord', 'asc');
        $data['pages'] = $this->mysql->get_All('pages', array('parent' => 0), '', '', 'ord', 'asc');
        $data['setting'] = $this->mysql->get_row('settings', array('id' => 1));
        $data['infos'] = $this->mysql->get_All('text', array('parent' => 0), '', '', 'ord', 'asc');
        $data['parteneri'] = $this->mysql->get_All('banners', array('parent' => 0), '', '', 'ord', 'asc');
        $data['arhiva_f'] = $this->mysql->get_All('news', array('parent' => 0), '4', '0', 'data', 'desc');
        $data['last_products'] = $this->mysql->get_All('catalog', array('parent >' => 0), '12', '', 'ord', 'asc');
        /* all pages */

        $data['page'] = $this->mysql->get_row('pages', array('id_pages' => 1));

        if ($this->input->post('send1') == 'send') {
            $setting = $this->mysql->get_row('settings', array('id' => 1));
            $msg = "Nume : " . $this->input->post('nume') . "<br />";
            $msg .= "Email" . $this->input->post('email') . "<br />";
            $msg .= "Mesaj : " . $this->input->post('msg') . "<br />";

            $this->send_mail($setting['mail'], 'Biless.md: Contact', $msg);
            $data['mesaj1'] = true;
        }


        $this->display->frontend('about', $data);
    }

    public function catalog() {
        $this->_check_lang();
        $data['mesaj1'] = FALSE;

        /* all pages */
        $data['categorii'] = $this->mysql->get_All('catalog', array('parent' => 0), '', '', 'ord', 'asc');
        $data['pages'] = $this->mysql->get_All('pages', array('parent' => 0), '', '', 'ord', 'asc');
        $data['setting'] = $this->mysql->get_row('settings', array('id' => 1));
        $data['infos'] = $this->mysql->get_All('text', array('parent' => 0), '', '', 'ord', 'asc');
        $data['arhiva_p'] = $this->mysql->get_All('news', array('parent' => 0), '2', '0', 'data', 'desc');
        $data['arhiva_f'] = $this->mysql->get_All('news', array('parent' => 0), '4', '0', 'data', 'desc');
        $data['parteneri'] = $this->mysql->get_All('banners', array('parent' => 0), '', '', 'ord', 'asc');
        $data['last_products'] = $this->mysql->get_All('catalog', array('parent >' => 0), '12', '', 'ord', 'asc');
        /* all pages */
        $data['produse_cats'] = $this->mysql->get_All('catalog', array('parent' => 0), '', '', 'ord', 'asc');
        $data['page'] = $this->mysql->get_row('pages', array('id_pages' => 2));


        if ($this->input->post('send1') == 'send') {
            $setting = $this->mysql->get_row('settings', array('id' => 1));
            $msg = "Nume : " . $this->input->post('nume') . "<br />";
            $msg .= "Email" . $this->input->post('email') . "<br />";
            $msg .= "Mesaj : " . $this->input->post('msg') . "<br />";

            $this->send_mail($setting['mail'], 'Biless.md: Contact', $msg);
            $data['mesaj1'] = true;
        }


        $this->display->frontend('catalog', $data);
    }

    public function products($url) {
        $this->_check_lang();
        $data['mesaj1'] = FALSE;
        /* all pages */
        $data['categorii'] = $this->mysql->get_All('catalog', array('parent' => 0), '', '', 'ord', 'asc');
        $data['pages'] = $this->mysql->get_All('pages', array('parent' => 0), '', '', 'ord', 'asc');
        $data['setting'] = $this->mysql->get_row('settings', array('id' => 1));
        $data['infos'] = $this->mysql->get_All('text', array('parent' => 0), '', '', 'ord', 'asc');
        $data['arhiva_p'] = $this->mysql->get_All('news', array('parent' => 0), '2', '0', 'data', 'desc');
        $data['arhiva_f'] = $this->mysql->get_All('news', array('parent' => 0), '4', '0', 'data', 'desc');
        $data['parteneri'] = $this->mysql->get_All('banners', array('parent' => 0), '', '', 'ord', 'asc');
        $data['last_products'] = $this->mysql->get_All('catalog', array('parent >' => 0), '12', '', 'ord', 'asc');
        /* all pages */


        $data['produse_cats'] = $this->mysql->get_All('catalog', array('parent' => 0), '', '', 'ord', 'asc');
        $data['page'] = $this->mysql->get_row('pages', array('id_pages' => 2));


        $data['produs'] = $this->mysql->get_row('catalog', array('url' => $url));
        $data['produs_parent'] = $this->mysql->get_row('catalog', array('id' => $data['produs']['parent']));
        if ($this->input->post('send1') == 'send') {
            $setting = $this->mysql->get_row('settings', array('id' => 1));
            $msg = "Nume : " . $this->input->post('nume') . "<br />";
            $msg .= "Email" . $this->input->post('email') . "<br />";
            $msg .= "Mesaj : " . $this->input->post('msg') . "<br />";

            $this->send_mail($setting['mail'], 'Biless.md: Contact', $msg);
            $data['mesaj1'] = true;
        }


        $this->display->frontend('product', $data);
    }

    public function info($url) {

        $this->_check_lang();
        $data['mesaj1'] = FALSE;
        /* all pages */
        $data['categorii'] = $this->mysql->get_All('catalog', array('parent' => 0), '', '', 'ord', 'asc');
        $data['pages'] = $this->mysql->get_All('pages', array('parent' => 0), '', '', 'ord', 'asc');
        $data['setting'] = $this->mysql->get_row('settings', array('id' => 1));
        $data['infos'] = $this->mysql->get_All('text', array('parent' => 0), '', '', 'ord', 'asc');
        $data['arhiva'] = $this->mysql->get_All('news', array('parent' => 0), '7', '0', 'data', 'desc');
        $data['parteneri'] = $this->mysql->get_All('banners', array('parent' => 0), '', '', 'ord', 'asc');
        $data['arhiva_f'] = $this->mysql->get_All('news', array('parent' => 0), '4', '0', 'data', 'desc');
        $data['last_products'] = $this->mysql->get_All('catalog', array('parent >' => 0), '12', '', 'ord', 'asc');
        /* all pages */
        $data['info'] = $this->mysql->get_row('text', array('url' => $url));


        if ($this->input->post('send1') == 'send') {
            $setting = $this->mysql->get_row('settings', array('id' => 1));
            $msg = "Nume : " . $this->input->post('nume') . "<br />";
            $msg .= "Email" . $this->input->post('email') . "<br />";
            $msg .= "Mesaj : " . $this->input->post('msg') . "<br />";

            $this->send_mail($setting['mail'], 'Biless.md: Contact', $msg);
            $data['mesaj1'] = true;
        }


        $this->display->frontend('info', $data);
    }

    public function news($url = null) {

        $this->_check_lang();
        $data['mesaj1'] = FALSE;
        /* all pages */
        $data['categorii'] = $this->mysql->get_All('catalog', array('parent' => 0), '', '', 'ord', 'asc');
        $data['pages'] = $this->mysql->get_All('pages', array('parent' => 0), '', '', 'ord', 'asc');
        $data['arhiva'] = $this->mysql->get_All('news', array('parent' => 0), '7', '0', 'data', 'desc');
        $data['setting'] = $this->mysql->get_row('settings', array('id' => 1));
        $data['infos'] = $this->mysql->get_All('text', array('parent' => 0), '', '', 'ord', 'asc');
        $data['parteneri'] = $this->mysql->get_All('banners', array('parent' => 0), '', '', 'ord', 'asc');
        $data['arhiva_f'] = $this->mysql->get_All('news', array('parent' => 0), '4', '0', 'data', 'desc');
        $data['last_products'] = $this->mysql->get_All('catalog', array('parent >' => 0), '12', '', 'ord', 'asc');
        /* all pages */

        if ($this->input->post('send1') == 'send') {
            $setting = $this->mysql->get_row('settings', array('id' => 1));
            $msg = "Nume : " . $this->input->post('nume') . "<br />";
            $msg .= "Email" . $this->input->post('email') . "<br />";
            $msg .= "Mesaj : " . $this->input->post('msg') . "<br />";

            $this->send_mail($setting['mail'], 'Biless.md: Contact', $msg);
            $data['mesaj1'] = true;
        }


        $data['page'] = $this->mysql->get_row('pages', array('id_pages' => 3));
        if ($url == null || is_numeric($url)) {

            $data['news'] = $this->mysql->get_All('news', array('parent' => 0), $data['setting']['per_page_site'], $this->uri->segment(3));
            $this->load->library('pagination');

            $config['base_url'] = '/' . $this->lng . '/news/';
            $config['total_rows'] = count($this->mysql->get_All('news', array('parent' => 0)));
            $config['per_page'] = $data['setting']['per_page_site'];
            $config['full_tag_open'] = '<ul>';
            $config['full_tag_close'] = '</ul>';
            $config['next_link'] = '>>';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['prev_link'] = '<<';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li><a class="active">';
            $config['cur_tag_close'] = '</a></li>';
            $this->pagination->initialize($config);

            $data['pagination'] = $this->pagination->create_links();
            $this->display->frontend('noutati', $data);
        } else if (!is_int($url)) {
            $data['new'] = $this->mysql->get_row('news', array('url' => $url));
            $this->display->frontend('noutati_inside', $data);
        }
    }

    public function contacts() {
        $this->_check_lang();
        $data['mesaj'] = FALSE;
        $data['mesaj1'] = FALSE;
        /* all pages */
        $data['categorii'] = $this->mysql->get_All('catalog', array('parent' => 0), '', '', 'ord', 'asc');
        $data['pages'] = $this->mysql->get_All('pages', array('parent' => 0), '', '', 'ord', 'asc');
        $data['setting'] = $this->mysql->get_row('settings', array('id' => 1));
        $data['infos'] = $this->mysql->get_All('text', array('parent' => 0), '', '', 'ord', 'asc');
        $data['arhiva_f'] = $this->mysql->get_All('news', array('parent' => 0), '4', '0', 'data', 'desc');
        $data['parteneri'] = $this->mysql->get_All('banners', array('parent' => 0), '', '', 'ord', 'asc');
        /* all pages */

        $data['page'] = $this->mysql->get_row('pages', array('id_pages' => 4));


        if ($this->input->post('send') == 'send') {
            $setting = $this->mysql->get_row('settings', array('id' => 1));
            $msg = "Nume : " . $this->input->post('nume') . "<br />";
            $msg .= "Email : " . $this->input->post('email') . "<br />";
            $msg .= "Mesaj : " . $this->input->post('msg') . "<br />";

            $this->send_mail($setting['mail'], 'Biless.md: Pagina de contact', $msg);
            $data['mesaj'] = true;
        }

        if ($this->input->post('send1') == 'send') {
            $setting = $this->mysql->get_row('settings', array('id' => 1));
            $msg = "Nume : " . $this->input->post('nume') . "<br />";
            $msg .= "Email" . $this->input->post('email') . "<br />";
            $msg .= "Mesaj : " . $this->input->post('msg') . "<br />";

            $this->send_mail($setting['mail'], 'Biless.md: Contact', $msg);
            $data['mesaj1'] = true;
        }

        $this->display->frontend('contacte', $data);
    }

}
