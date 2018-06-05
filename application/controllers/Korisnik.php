<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Korisnik
 *
 * @author Korisnik
 */
class Korisnik extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model("ModelVest");
        $this->load->model("ModelAutor");
        $this->load->library('session');
        if(($this->session->userdata('autor'))==NULL)
            redirect("Gost");
            
    }
    
    private function loadView($data,$glavniDeo){
        $this->load->view("sablon/header_korisnik.php", $data);
        $this->load->view($glavniDeo, $data);
        $this->load->view("sablon/footer.php");
    }
    
    public function index($trazi=NULL){
        if($trazi==NULL)
            $vesti=$this->ModelVest->dohvatiVesti();
        else
            $vesti=$this->ModelVest->pretraga($trazi);
        $data['vesti']=$vesti;
        $data['controller']="Korisnik";
        $data['metoda']="pretraga";
        $this->loadView($data, "vesti.php");     
    }
    
    public function pretraga(){
        $trazi=$this->input->get('pretraga');
        $this->index($trazi);
    }
    
    public function dodajvest(){
        $this->loadView(array(), "dodavanjevesti.php");
    }


    public function dodavanjeVesti(){
        $this->form_validation->set_rules('naziv','Naziv',
                'required|min_length[10]|max_length[20]'
                . '|callback_testNaslov');
        $this->form_validation->set_rules('sadrzaj','Sadrzaj','required');
        if($this->form_validation->run()==FALSE){
            $this->dodajvest();// ne treba redirect jer na refresh treba da proba da opet nesto doda
        }
        else{
            //ispravno
            $naslov=$this->input->post("naziv");
            $sadrzaj=$this->input->post("sadrzaj");
            $autorId=$this->session->userdata("autor")->id;
            $id=$this->ModelVest->dodaj($naslov, $sadrzaj,$autorId);
            
            $config['upload_path']          = './uploads/';
            $config['allowed_types']        = 'gif|jpg|png';
            $config['max_size']             = 1000;
            $config['max_width']            = 1024;
            $config['max_height']           = 768;
            $config['file_name']            = "vest_".$id;
            
            
            $this->load->library('upload');
            $this->upload->initialize($config);
            
            $this->load->library('upload', $config);
            $this->upload->do_upload('slika');

            redirect("Korisnik/index");
        }
    }
    
    public function izmenivest($idvesti){
        $vest=$this->ModelVest->dohvatiVest($idvesti);
        $data['vest']=$vest;
        $this->loadView($data, "izmenavesti.php");
    }


    public function testNaslov($naziv){
        if(preg_match("/^[A-Z](\w|\s)+$/", $naziv))
                return true;
        else
        {
           $this->form_validation->set_message('testNaslov', '{field} nije u ispravnom obliku!');
           return FALSE;
        }
    }
    
    public function logout(){
        $this->session->unset_userdata("autor");
        $this->session->sess_destroy();
        redirect("Gost");
    }

    public function mojevesti(){ // $pocetni_index=0 treci segment mozemo i ovde da dohvatimo kao prvi arg
        $idAutor=$this->session->userdata("autor")->id;
        
        if($this->uri->segment(3))
            $pocetni_index=$this->uri->segment(3);
        else
            $pocetni_index=0;
        //$pocetni_index=($this->uri->segment(3))?$this->uri->segment(3):0;
        
        $limit=LIMIT_PO_STRANICI;
        $ukupanBrVesti=$this->ModelVest->brojvesti($idAutor);   
        $vesti=$this->ModelVest->dohvatiVesti($idAutor,$limit,$pocetni_index);
        $data['vesti']=$vesti;
       
        $this->load->library('pagination');// ovo moze i u  config/autoload.php da se doda
        
        $this->config->load('bootstrap_pagination'); //moze i u autoload.php
        
        $config_pagination=$this->config->item('pagination');
        $config_pagination['base_url']= site_url("Korisnik/mojevesti");
        $config_pagination['total_rows']=$ukupanBrVesti;
        $config_pagination['per_page']=$limit;
        $config_pagination['next_link'] = 'Next';
        $config_pagination['prev_link'] = 'Prev';
        
        
        $this->pagination->initialize($config_pagination);
        $data['links']=$this->pagination->create_links();
        
        $this->loadView($data, "mojevesti.php");
    }
    
    public function menjajvest($idVest){
        $this->form_validation->set_rules('naslov','Naslov',
                'required|min_length[10]|max_length[20]'
                . '|callback_testNaslov');
        $this->form_validation->set_rules('sadrzaj','Sadrzaj','required');
        if($this->form_validation->run()==FALSE){
            $this->izmenivest($idVest);// ne treba redirect jer na refresh treba da proba da opet nesto doda
        }
        else{
            //ispravno
            $naslov=$this->input->post("naslov");
            $sadrzaj=$this->input->post("sadrzaj");
            $autorId=$this->session->userdata("autor")->id;
            $this->ModelVest->izmeniVest($idVest, $naslov, $sadrzaj,$autorId);

            redirect("Korisnik/mojevesti");
        }
    }
    
    public function obrisivest($idvest){
        $autorId=$this->session->userdata("autor")->id;
        $this->ModelVest->obrisiVest($idvest,$autorId);
        redirect("Korisnik/mojevesti");
    }
    
    public function prikazivest($idvest){
        $vest=$this->ModelVest->dohvatiVest($idvest);
        $data['vest']=$vest;
        $data['controller']="Korisnik";
        $this->loadView($data, "prikazvesti.php");
    }
    
    public function preuzmi($idvest)
    {
        $this->load->helper('download');
        force_download("./uploads/vest_$idvest.jpg", NULL);
    }
    //put your code here
}
