<?php

class Gost extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model("ModelAutor");
        $this->load->model("ModelVest");
        $this->load->library('session');
        if(($this->session->userdata('autor'))!=NULL)
            redirect("Korisnik");
    }
        
    private function loadView($data,$glavniDeo){
        $this->load->view("sablon/header_gost.php", $data);
        $this->load->view($glavniDeo, $data);
        $this->load->view("sablon/footer.php");
    }
    
    public function index($trazi=NULL){
        if($trazi==NULL)
            $vesti=$this->ModelVest->dohvatiVesti();
        else
            $vesti=$this->ModelVest->pretraga($trazi);
        $data['vesti']=$vesti;
        $data['controller']="Gost";
        $data['metoda']="pretraga";
        $this->loadView($data, "vesti.php");     
    }
    
    public function pretraga(){
        $trazi=$this->input->get('pretraga');
        $this->index($trazi);
    }
    
    public function info(){
        $info_vesti=$this->ModelVest->info();
        $podaci['info']=$info_vesti;
        $this->loadView($podaci, "info.php");
    }
    
    public function  autori(){
        // TODO 
    }
            
    public function registracija(){
        // TODO
    }
    
    public function login($poruka=NULL)
    {
        $podaci=array();
        if($poruka)
            $podaci['poruka']=$poruka;
        $this->loadView( $podaci, 'login.php');
    }
    
    public function ulogujse(){
        $this->form_validation->set_rules("username", "Usermane", "required");
        $this->form_validation->set_rules("password", "Password", "required");
        $this->form_validation->set_message("required","Polje {field} je ostalo prazno.");
        if($this->form_validation->run())
        {
            $this->ModelAutor->username=$this->input->post('username');
            if(!$this->ModelAutor->postojiUsername())
                $this->login("Neispravan username!");
            else if(!$this->ModelAutor->ispravanpassword($this->input->post('password')))
                $this->login("Neispravan password!");
            else {
                $this->load->library('session');
                $this->session->set_userdata('autor', $this->ModelAutor);
                redirect("Korisnik/index");
            }     
        }
        else
            $this->login();
        
    }
    
    public function prikazivest($idvest){
        $vest=$this->ModelVest->dohvatiVest($idvest);
        $data['vest']=$vest;
        $data['controller']="Gost";
        $this->loadView($data, "prikazvesti.php");
    }
    
    public function preuzmi($idvest)
    {
        $this->load->helper('download');
        force_download("./uploads/vest_$idvest.jpg", NULL);
    }
}
