<?php

namespace ThaiSanskrit;

use ThaiSanskrit\ThaiSanscriptRule;
use ThaiSanskrit\ThaiSanscriptInformRule;

class ThaiSanscriptAPI {

    /** @var ThaiSanscriptInformRule */
    public $thaiInformRule;

    /** @var ThaiSanscriptRule */
    public $thaiRule;

    private $spaceDilimiter = '@';
    private $mode = 2;

    public function __construct($mode = '') {
        mb_internal_encoding("UTF-8");
        $this->mode = $mode ? $mode : $this->mode;
        $this->thaiRule = new ThaiSanscriptRule();
        $this->thaiInformRule = new ThaiSanscriptInformRule();
    }

    public function prepareTxt($txt) {
        $txt = preg_replace('/\x20+/', $this->spaceDilimiter, (string) $txt);
        return mb_strtolower($txt, 'UTF-8');
    }

    public function line_split($txt) {
        return array_map(function ($line) {
            return explode($this->spaceDilimiter, $line);
        }, preg_split('/\r\n|\r|\n/', (string) $txt));
    }

    public function convertThaiInform($txt, $lang = "sans", $destType = "thai") {
        return $this->thaiInformRule->convert($txt, $lang, $destType);
    }

    public function convertThai($txt, $lang = "sans", $destType = "thai") {
        return $this->thaiRule->convert($txt, $lang, $destType);
    }

    public function jsonOutput($txt, $lang, $destType) {
        $txt = $this->prepareTxt($txt);
        $output = $this->convertLineTxt($txt, $lang, $destType);
        return json_encode($output);
    }

    public function convertLineTxt($txt, $lang, $destType) {
        $output = array(array(), array(), array());
        $txtPool = preg_split('/\r\n|\r|\n/', (string) $txt);

        foreach ($txtPool as $i => $line) {
            // Compute Thai formal (inform) once; convert to Lao only if requested
            $thaiInform = $this->convertThaiInform($line, $lang, 'thai');
            $destInform = ($destType == 'lao')
                ? $this->thaiInformRule->util->convertThaiToLao($thaiInform)
                : $thaiInform;

            $output[0][$i] = explode($this->spaceDilimiter, $thaiInform);
            $output[1][$i] = explode($this->spaceDilimiter, $this->convertThai($line, $lang, $destType));
            $output[2][$i] = explode($this->spaceDilimiter, $destInform);
        }
        return $output;
    }

    public function convertSyllableTxt($txt) {
        $output = array(array(), array());
        $txtPool = $this->line_split($txt);
        foreach ($txtPool as $i => $line) {
            foreach ($line as $j => $syllable) {
                $output[0][$i][$j] = $this->convertThaiInform($syllable);
                $output[1][$i][$j] = $this->convertThai($syllable);
            }
        }
        return $output;
    }

    public function transliterationTracking($romanize, $mode = 1) {
        if ($mode == 1) {
            $real = $this->thaiRule->convert($romanize, 'sans');
            $track = $this->thaiRule->convertTrackMode($romanize);
        } else {
            $real = $this->thaiInformRule->convert($romanize);
            $track = $this->thaiInformRule->convertTrackMode($romanize);
        }
        if ($real == $track) {
            return $track;
        }
        echo 'transliterationTracking not concurrent';
        return "";
    }

    public function transliterationToArray($romanize, $devanagari) {
        $romanize = mb_strtolower((string) $romanize, "UTF-8");
        $returnArray = array();
        $listRomanize = preg_split('/\r\n|\r|\n/', $romanize);
        foreach ($listRomanize as $key => $line) {
            $syllableRomanize = explode(" ", $line);
            $syllableThai = array();
            $syllableThaiInform = array();

            foreach ($syllableRomanize as $i => $syllable) {
                $syllableThai[$i] = $this->thaiRule->convert($syllable);
                $syllableThaiInform[$i] = $this->thaiInformRule->convert($syllable);
            }
            $returnArray['0'][$key] = $syllableRomanize;
            $returnArray['1'][$key] = $syllableThai;
            $returnArray['2'][$key] = $syllableThaiInform;
        }
        if (trim((string) $devanagari) != "") {
            $listDevanagari = preg_split('/\r\n|\r|\n/', $devanagari);
            foreach ($listDevanagari as $key => $line) {
                $returnArray['3'][$key] = explode(" ", $line);
            }
        }
        return $returnArray;
    }

}
