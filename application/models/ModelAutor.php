<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModelAutor
 *
 * @author Korisnik
 */
class ModelAutor extends CI_Model {
    public $username;
    public $ime;
    public $prezime;
    public $id;
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function postojiUsername(){
        $this->db->where('username',$this->username);
        $result=$this->db->get('autor');
        if($result->result())
            return TRUE;
        else
            return false;
    }
    public function ispravanpassword($password){
        $this->db->where('username',$this->username);
        $this->db->where('password',$password);
        $result=$this->db->get('autor');
        $autor=$result->row_array();
       
        if($autor!=NULL){
            $this->ime=$autor['ime'];
            $this->prezime=$autor['prezime'];
            $this->id=$autor['id'];
            return TRUE;
        }
        else
            return false;
        
    }
    
}
