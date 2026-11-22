<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sample extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        
    }

    public function rigveda() {
        $this->template->set_page_title("ฤคเวท (Rigveda) - ตัวอย่างการปริวรรต | ไทย-สันสคริปท์");
        $this->template->append_page_section('sample/rigveda');
        $this->template->render_sample_template($this);
    }

    public function ramayana() {
        $this->template->set_page_title("รามายณะ (Ramayana) - ตัวอย่างการปริวรรต | ไทย-สันสคริปท์");
        $this->template->append_page_section('sample/ramayana');
        $this->template->render_sample_template($this);
    }

    public function vajrasutra() {
        $this->template->set_page_title("วัชรปรัชญาปารมิตาสูตร (Vajracchedika Prajnaparamita) - ตัวอย่างการปริวรรต | ไทย-สันสคริปท์");
        $this->template->append_page_section('sample/vajrasutra');
        $this->template->render_sample_template($this);
    }

    public function pratimoksa() {
        $this->template->set_page_title("มหาสังฆิกะปราติโมกษ์ (Pratimoksa) - ตัวอย่างการปริวรรต | ไทย-สันสคริปท์");
        $this->template->append_page_section('sample/pratimoksa');
        $this->template->render_sample_template($this);
    }

    public function lalitavistara() {
        $this->template->set_page_title("ลลิตวิสตระ (Lalitavistara) - ตัวอย่างการปริวรรต | ไทย-สันสคริปท์");
        $this->template->append_page_section('sample/lalitavistara');
        $this->template->render_sample_template($this);
    }

    public function buddhacarita() {
        $this->template->set_page_title("พุทธจริต (Buddhacarita) - ตัวอย่างการปริวรรต | ไทย-สันสคริปท์");
        $this->template->append_page_section('sample/buddhacarita');
        $this->template->render_sample_template($this);
    }

    public function mahabharat() {
        $this->template->set_page_title("มหาภารตะ ภควัทคีตา (Mahabharata) - ตัวอย่างการปริวรรต | ไทย-สันสคริปท์");
        $this->template->append_page_section('sample/mahabharat');
        $this->template->render_sample_template($this);
    }

}
