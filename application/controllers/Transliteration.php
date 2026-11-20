<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transliteration extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        
    }

    public function json() {
	
        $txt = $this->input->post('src-txt');
        $lang = $this->input->post('lang');
        $destType = $this->input->post('destType');
		log_message('error', '$destType '.$destType." ".'$lang '.$lang ." ".str_replace("\n", '', substr("$txt",0,100)));
        $json = $this->thaisans_adapter->jsonOutput($txt,$lang,$destType);
        $this->output->set_content_type('application/json')->set_output($json);
    }
	
	    public function jsons() {
	
        $txt = $this->input->get('src-txt');
        $lang = $this->input->get('lang');
        $destType = $this->input->get('destType');
		log_message('error', '$destType '.$destType." ".'$lang '.$lang ." ".str_replace("\n", '', substr("$txt",0,100)));
        $json = $this->thaisans_adapter->jsonOutput($txt,$lang,$destType);
        $this->output->set_content_type('application/json')->set_output($json);
    }

}