<?php

class dirAnime{
    private $id;
    private $name;
    private $image_path;  
    private $image_port;  
    private $type;
    private $puntaje;
    private $ruta;
    private $description;
    private $created_at;  
    private $db;
    
    public function __construct() {
        $this->db = Database::conexion();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
 
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $this->db->real_escape_string($name);

        return $this;
    }

    public function getImage_path()
    {
        return $this->image_path;
    }

    public function setImage_path($image_path)
    {
        $this->image_path = $image_path;

        return $this;
    }

    public function getImage_port()
    {
        return $this->image_port;
    }

    public function setImage_port($image_port)
    {
        $this->image_port = $image_port;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getPuntaje()
    {
        return $this->puntaje;
    }

    public function setPuntaje($puntaje)
    {
        $this->puntaje = $puntaje;

        return $this;
    }

    public function getRuta()
    {
        return $this->ruta;
    }


    public function setRuta($ruta)
    {
        $this->ruta = $ruta;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getCreated_at()
    {
        return $this->created_at;
    }

    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getExib(){
        $exib = $this->db->query("SELECT * FROM catalogo WHERE puntaje = 10  ORDER BY puntaje ASC limit 10");
        return $exib;
    }

    public function getMax(){
        $punt = $this->db->query("SELECT * FROM catalogo WHERE puntaje = 10  ORDER BY puntaje ASC limit 5");
        return $punt;
    }

    public function getCovers(){
        $covers = $this->db->query("SELECT * FROM catalogo WHERE puntaje >= 5 ORDER BY puntaje DESC limit 15");
        return $covers;
    }

    public function getOne(){
        $anime = $this->db->query("SELECT * FROM catalogo WHERE id = {$this->getId()}");
        return $anime->fetch_object();
    }

    public function getAll(){

        $list = $this->db->query("SELECT * FROM catalogo ORDER BY id DESC");
        return $list;
    }

    public function search(){
        $searchResults = array();

        $sql = $this->db->query("SELECT * FROM catalogo WHERE name LIKE '{$this->getName()}%'");   

        while ($row = $sql->fetch_object()) {
            $searchResults[] = $row;
            
        }
        return $searchResults;
    }

    public function consultRuta(){
        $ruta = $this->db->query("SELECT ruta FROM catalogo WHERE id = '{$this->getId()}'");
        return $ruta->fetch_object();
    }

    public function deleteCatalogo(){
        $delete = $this->db->query("DELETE FROM catalogo WHERE id = {$this->getId()}");
        return $delete;
        
        $result = false;
        if($delete){
            $result = true;
        }

        return $result;
    }

    public function edit(){
        $sql = "UPDATE catalogo SET name = '{$this->getName()}', image_path = '{$this->getImage_path()}', image_port = '{$this->getImage_port()}',
                                                            type = '{$this->getType()}',puntaje = '{$this->getPuntaje()}',
                                                            ruta= '{$this->getRuta()}',description = '{$this->getDescription()}'";
        $sql .= " WHERE id={$this->id};";
        $edit = $this->db->query($sql);

        $result = false;

        if($edit){
            $result = true;
        }

        return $result;
    }

    public function save(){

        $sql = "INSERT INTO catalogo VALUES (NULL,'{$this->getName()}','{$this->getImage_path()}','{$this->getImage_port()}',
                                                        '{$this->getType()}','{$this->getPuntaje()}','{$this->getRuta()}',
                                                        '{$this->getDescription()}', CURDATE())";
        $save = $this->db->query($sql);
        $result = false;

        //prueba
        /*
        var_dump($sql);
        var_dump($save);
        die();
        */

        if($save){
            $result = true;
        }

        return $result;
    }


}
