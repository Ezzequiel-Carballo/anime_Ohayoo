<?php

require_once 'models/directorio.php';
require_once 'models/newEpisode.php';

class episodeController{

    public function new(){
        $dirAnime = new dirAnime;
        $anime = $dirAnime->getAll();

        require_once 'views/formulario/newEpisode.php';
    }

    public function save(){
        
        if(isset($_POST)){
            $name = isset($_POST['name']) ? $_POST['name'] : '';
            $episode = isset($_POST['episode']) ? $_POST['episode'] : '';

            if ($name && $episode){

                //consulta iD
                $consult = new newEpisode;
                $consult->setName($name);

                $episod = 'Episode '.$episode;
                
                $idObject = $consult->consultId(); // Suponiendo que $consult es una instancia de newAnime
                $id = $idObject->id; // Acceder a la propiedad "id" del objeto devuelto

                //consutla imagen de portada
                $consult->setId($id);
                $imgObject = $consult->consultImg();

                $img = $imgObject->image_port;

                $animes = new newEpisode;
                
                $animes->setCatalogo_id($id);
                $animes->setName($name);

                //Creacion de Ruta

                $ruta = 'views/series/'.$name;

                $ruta_exist = 'Episode '.$episode;                
                
                $archivos = scandir($ruta);
                
                if (in_array($ruta_exist, $archivos)) {
                    
                    $_SESSION['episode'] = "ya existe este episode";
                    header('location:'.base_URL.'episode/new');
                    die();

                }else{
                    
                    $EpiRuta = $ruta.'/Episode '.$episode;
                    mkdir($EpiRuta,0777,true);
                    print 'Se creo correctamente';
                    $animes->setRuta($EpiRuta);
                }

                $animes->setEpisode($episod);
                $animes->setAcapite($episode);
                $animes->setImage_port($img);

                //Guardar video
                $videofile = $_FILES['video'];                
                $videoname = $videofile['name'];  
                
                $videoTempPath = $videofile['tmp_name'];  // Temporary path of the uploaded video file                   
                
                $videoPath = $EpiRuta. '/' . $videoname;                    

                
                if(move_uploaded_file($videoTempPath, $videoPath)){
                    
                    $animes->setVideo_path($videoname);
                    $save = $animes->save();
                    
                }    
            }
        header('location:'.base_URL);
        }
    }
}