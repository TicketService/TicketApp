<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModelVest
 *
 * @author Korisnik
 */
class ModelVest extends CI_Model{

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function dohvatiVesti($autor=NULL, $limit=1000,$pocetak=0){
        if($autor!=NULL)
            $this->db->where("autor",$autor);
        $query=$this->db->get('Vest',$limit,$pocetak);//prikazujem prvih deset vesti
        $result=$query->result_array();//vraca niz vesti
        return $result;
    }
    
    public function dohvatiVest($idvesti){
        $this->db->where("id",$idvesti);
        $query=$this->db->get('Vest');
        $result=$query->row_array();//vraca jednu vest
        return $result;
    }
    
    public function brojvesti($autor=NULL){
        if($autor!=NULL)
            $this->db->where("autor",$autor);
        $this->db->from("vest");
        return $this->db->count_all_results();
    }
    
    public function pretraga($naziv){
       // $query=$this->db->query("select * from Vest where naslov like '%$naziv%'");
       // $query=$this->db->get_where('vest',"naslov like '%$naziv%' "
         //       . "OR sadrzaj like '%$naziv%'");
        
        $this->db->like("naslov", $naziv);
        $this->db->or_like("sadrzaj", $naziv);
        $this->db->from("vest");
        $this->db->select("naslov, sadrzaj");
        
        $query=$this->db->get();

        return $query->result_array();
    }
    
    public function info(){
        $this->db->from("vest");
        $this->db->group_by("naslov");
        $this->db->select("naslov, count(*) as  broj");
        $query=$this->db->get();
        return $query->result_array();
    }
    
    public function dodaj($naslov, $sadrzaj,$autor){
        $this->db->set("autor", $autor);
        $this->db->set("naslov", $naslov);
        $this->db->set("sadrzaj",$sadrzaj);
        $this->db->set("datum", mdate("%Y-%m-%d"));
        $this->db->insert("vest");
        $id=$this->db->insert_id();
        return $id;
    }
    public function izmeniVest($idVest, $naslov, $sadrzaj,$autorId){
        $this->db->set("naslov", $naslov);
        $this->db->set("sadrzaj",$sadrzaj);
        $this->db->set("datum", mdate("%Y-%m-%d"));
        $this->db->where("autor",$autorId);
        $this->db->where("id",$idVest);
        $this->db->update("vest");
    }
    public function obrisiVest($idVest, $autorId){
        $this->db->where("autor",$autorId);
        $this->db->where("id",$idVest);
        $this->db->delete("vest");
    }
}

