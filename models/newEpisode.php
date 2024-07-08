<?php

class newEpisode{

    private $id;
    private $catalogo_id;
    private $name;
    private $ruta;
    private $episode;
    private $acapite;
    private $image_port;
    private $video_path;
    private $created_at;
    private $db;

    public function __construct()
    {
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

    public function getCatalogo_id()
    {
        return $this->catalogo_id;
    }

    public function setCatalogo_id($anime_id)
    {
        $this->catalogo_id = $anime_id;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

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

    public function getEpisode()
    {
        return $this->episode;
    }

    public function setEpisode($episode)
    {
        $this->episode = $episode;

        return $this;
    }

    public function getAcapite()
    {
        return $this->acapite;
    }

    public function setAcapite($acapite)
    {
        $this->acapite = $acapite;

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

    public function getVideo_path()
    {
        return $this->video_path;
    }

    public function setVideo_path($video_path)
    {
        $this->video_path = $video_path;

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

    public function getAll(){

        $list = $this->db->query("SELECT * FROM episode ORDER BY id DESC limit 20");
        return $list;
    }

    public function consultId(){

        $sql = $this->db->query("SELECT id FROM catalogo c WHERE c.name = '{$this->getName()}'");
        return $sql->fetch_object();
    }

    public function consultName(){
        $name = $this->db->query("SELECT name FROM catalogo c WHERE c.id = '{$this->getId()}'");
        return $name ->fetch_object();
    }

    public function consultImg(){

        $img = $this->db->query("SELECT image_port FROM catalogo c WHERE c.id = '{$this->getId()}'");
        return $img->fetch_object();
    }

    public function consultCant(){
        $cant = $this->db->query("SELECT count(acapite) AS count FROM episode WHERE catalogo_id = '{$this->getId()}'");
        
        if ($cant) {
            $result = $cant->fetch_assoc();
            return $result['count']; // Retorna el valor de la cantidad
        }
        
        return 0; // Si hay un error o no se encuentran resultados, se retorna 0 (o puedes manejar el error de otra manera)
    }
    

    public function getOne(){

        $one = $this->db->query("SELECT * FROM episode WHERE catalogo_id = '{$this->getId()}' ORDER BY acapite ASC");
        return $one;
    }

    public function getUnic(){
        $unic = $this->db->query("SELECT * FROM episode WHERE acapite = '{$this->getAcapite()}' AND catalogo_id = '{$this->getId()}'");
        return $unic->fetch_object();
    }

    public function deleteEpisode(){
        $delete = $this->db->query("DELETE FROM episode WHERE catalogo_id = {$this->getId()}");
        return $delete;
        
        $result = false;
        if($delete){
            $result = true;
        }

        return $result;
    }

    public function save(){

        $sql = "INSERT INTO episode VALUES (NULL,'{$this->getCatalogo_id()}','{$this->getName()}',
                                            '{$this->getEpisode()}','{$this->getRuta()}','{$this->getAcapite()}','{$this->getImage_port()}',
                                            '{$this->getVideo_path()}', CURDATE())";
        $save = $this->db->query($sql);
        $result = false;

        if ($save){
            $result = true;

        }else{
            $error = "Error=". mysqli_error($this->db);
        }
        /*
        var_dump($sql);
        var_dump($error);
        die();
        */
        return $result;
    }

}