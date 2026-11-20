<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C extends CI_Controller {

    public function p($title = "", $section = "", $file = "") {
        // Strict whitelist to prevent Path Traversal
        $pattern = '/\A[a-zA-Z0-9_-]+\z/';
        if (!preg_match($pattern, (string) $title) ||
            !preg_match($pattern, (string) $section) ||
            !preg_match($pattern, (string) $file)) {
            redirect(site_url("errors"));
            return;
        }

        $baseRepo = realpath(FCPATH . 'repo');
        $candidate = FCPATH . "repo/" . $title . "/" . $section . "/" . $file . ".s";
        $realCandidate = is_file($candidate) ? realpath($candidate) : FALSE;

        if ($baseRepo !== FALSE && $realCandidate !== FALSE && strpos($realCandidate, $baseRepo . DIRECTORY_SEPARATOR) === 0) {
            $this->trans($realCandidate);
        } else {
            redirect(site_url("errors"));
        }
    }

    private function trans($repoSanskrit = "") {
        $dataTxt = file_get_contents($repoSanskrit);
        $dat = explode("@", $dataTxt);
        if (count($dat) == 3) {
            $data['title'] = $dat[0];
            $data['detail'] = $dat[1];
            $dataSanskrit = $dat[2];

            /* @var $thaisans_adapter Thaisans_adapter */
            $thaisans_adapter = $this->thaisans_adapter;

            $dataIast = $thaisans_adapter->sanscript($dataSanskrit);
            $sansArray = $thaisans_adapter->txtLineToArray($dataSanskrit);
            $lastArray = $thaisans_adapter->txtLineToArray($dataIast);
            $thaiArray = $thaisans_adapter->toThai($dataIast);

            $data['line_sanskrit'] = $sansArray;
            $data['line_iast'] = $lastArray;
            $data['line_thaiform'] = $thaiArray[0];
            $data['line_thai'] = $thaiArray[1];
            $data['meta_title'] = trim($data['title']);
            $data['meta_description'] = trim($data['detail']);

            $this->template->set_page_title($data['title']);
            $this->template->append_page_section('compare/content');
            $this->template->render_compare_template($this, $data);
        }
    }

}
