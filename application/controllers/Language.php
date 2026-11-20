<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Language extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
	}
	
	public function en() {
		$current_url = $this->input->get('url');
		$this->switch_lang('en', $current_url);
	}
	
	public function th() {
		$current_url = $this->input->get('url');
		$this->switch_lang('th', $current_url);
	}

	private function switch_lang($lang_short, $current_url) {
		$this->template->save_lang($this, $lang_short);

		// Prevent Open Redirect: only allow relative paths or URLs on our own host
		$target = site_url();
		if (is_string($current_url) && $current_url !== '') {
			$current_url = trim($current_url);
			$base = rtrim(base_url(), '/');
			if (strpos($current_url, $base) === 0) {
				$after = substr($current_url, strlen($base));
				if ($after === '' || $after[0] === '/' || $after[0] === '?') {
					$target = $current_url;
				}
			} elseif ($current_url[0] === '/' && (strlen($current_url) === 1 || ($current_url[1] !== '/' && $current_url[1] !== '\\'))) {
				$target = $current_url;
			}
		}

		redirect($target);
	}
}
