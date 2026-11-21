<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transliteration extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        
    }

    public function json() {
        $txt = (string) $this->input->post('src-txt');
        $lang = (string) $this->input->post('lang');
        $srcType = (string) $this->input->post('srcType');
        $destType = (string) $this->input->post('destType');

        $this->writeTransliterationLog('POST', $srcType, $destType, $lang, $txt);

        $json = $this->thaisans_adapter->jsonOutput($txt, $lang, $destType);
        $this->output->set_content_type('application/json')->set_output($json);
    }

    public function jsons() {
        $txt = (string) $this->input->get('src-txt');
        $lang = (string) $this->input->get('lang');
        $srcType = (string) $this->input->get('srcType');
        $destType = (string) $this->input->get('destType');

        $this->writeTransliterationLog('GET', $srcType, $destType, $lang, $txt);

        $json = $this->thaisans_adapter->jsonOutput($txt, $lang, $destType);
        $this->output->set_content_type('application/json')->set_output($json);
    }

    private function writeTransliterationLog($method, $srcType, $destType, $lang, $txt) {
        $ip = $this->input->ip_address();
        $cleanPreview = preg_replace('/\s+/u', ' ', trim($txt));
        $preview = mb_substr($cleanPreview, 0, 100, 'UTF-8');
        if (mb_strlen($cleanPreview, 'UTF-8') > 100) {
            $preview .= '...';
        }
        $msg = sprintf(
            '[Transliteration] method=%s ip=%s src=%s dest=%s lang=%s len=%d text="%s"',
            $method,
            $ip,
            $srcType !== '' ? $srcType : '-',
            $destType !== '' ? $destType : '-',
            $lang !== '' ? $lang : '-',
            mb_strlen($txt, 'UTF-8'),
            $preview
        );
        log_message('info', $msg);
    }

}
