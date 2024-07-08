<?php

require_once 'models/directorio.php';
require_once 'models/newEpisode.php';

class directorioController{

    public function catalogo(){        
        
        $anime = Utils::dirAll();

        require_once 'views/layout/directorio.php';
    }

    public function new(){

        Utils::isAdmin();

        require_once 'views/formulario/newAnime.php';    
            
    }

    public function listado(){

        if(isset($_GET['id'])){

            $id = $_GET['id'];

            $anime = new dirAnime;
            $anime->setId($id);

            $video = new newEpisode;
            $video->setId($id);
            
            $list = $anime->getOne();
            $vid = $video->getOne();
            
        }
        require_once 'views/layout/episodes.php';        
    }

    public function filtro(){

        Utils::isAdmin();

        $dirAnime = new dirAnime;
        $anime = $dirAnime->getAll();

        require_once 'views/layout/filtro.php';

    }

    public function edit(){

        Utils::isAdmin();

        if(isset($_GET['id'])){

            $id = $_GET['id'];
            $edit = true;

            $anime = new dirAnime;
            $anime->setId($id);

            $edite = $anime->getOne();

        }
        require_once 'views/formulario/newAnime.php'; 
    }

    public function option(){

        Utils::isAdmin();

        if(isset($_GET['id'])){
			$id = $_GET['id'];
			
			$anime = new dirAnime();
			$anime->setId($id);
			
			$edite = $anime->getOne();

        require_once 'views/layout/edit.php';
        }
    }
    
    public function consulta() {
        $searchResults = [];
        $browse = false;

        if(isset($_POST['enviar']) || isset($_POST['search'])) {
            $busqueda = $_POST['busqueda'];

            $consul = new dirAnime();
            $consul->setName($busqueda);

            $searchResults = $consul->search();

            if(isset($_POST['search'])) {
                $browse = true;
            }

            // Depuración
            //var_dump($searchResults);
            //error_log(print_r($searchResults, true));
        }

        require_once 'views/layout/search.php';
    }
    

    public function delete(){
    Utils::isAdmin();

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        
        $epi = new newEpisode;
        $epi->setId($id);
        
        $cat = new dirAnime;
        $cat->setId($id);
        
        // Buscando ruta de carpetas
        $rutaObject = $cat->consultRuta();
        $rutaCatalogo = $rutaObject->ruta;        
        
        // Iniciar una transacción
        $conexion = Database::conexion();
        $conexion->begin_transaction();

        try {
            // Búsqueda de contenido en subcarpetas
            $archivos = glob($rutaCatalogo . '/*/*');

            foreach ($archivos as $archivo) {
                if (!empty($archivo) && file_exists($archivo)) {
                    unlink($archivo); // Elimina archivos
                } else {
                    echo 'No hay archivos en la carpeta';
                }
            }

            $carpeta = glob($rutaCatalogo . '/*');

            foreach ($carpeta as $subcarpeta) {
                if (is_dir($subcarpeta)) {
                    // Elimina archivos en subcarpetas antes de eliminar la subcarpeta
                    $subArchivos = glob($subcarpeta . '/*');
                    foreach ($subArchivos as $subArchivo) {
                        if (file_exists($subArchivo)) {
                            unlink($subArchivo);
                        }
                    }
                    rmdir($subcarpeta); // Recursivamente elimina subdirectorios
                } else {
                    if (file_exists($subcarpeta)) {
                        unlink($subcarpeta);
                    }
                }
            }

            if (is_dir($rutaCatalogo)) {
                rmdir($rutaCatalogo); // Elimina el directorio actual después de haber eliminado su contenido
            }
            
            // Eliminar catálogo y episodios
            $deleteEpisode = $epi->deleteEpisode();
            $deleteCatalogo = $cat->deleteCatalogo();

            if($deleteEpisode && $deleteCatalogo){
                $_SESSION['delete'] = "Complete";
                $conexion->commit(); // Confirmar la transacción
            } else {
                $_SESSION['delete'] = "Failed";
                $conexion->rollback(); // Revertir la transacción
            }

        } catch (Exception $e) {
            $_SESSION['delete'] = "Failed";
            $conexion->rollback(); // Revertir la transacción en caso de error
            throw $e;
        }

    } else {
        $_SESSION['delete'] = "Failed";
    }

    header('Location:' . base_URL . 'directorio/filtro');
}


    public function save() {
        Utils::isAdmin();
    
        if (isset($_POST)) {
            $name = isset($_POST['name']) ? $_POST['name'] : 'false';
            $type = isset($_POST['type']) ? $_POST['type'] : 'false';
            $puntaje = isset($_POST['puntaje']) ? $_POST['puntaje'] : 'false';
            $description = isset($_POST['description']) ? $_POST['description'] : 'false';
            
            if ($name && $type && $puntaje && $description) {
                $new = new dirAnime;
                $ruta = 'views/series/' . $name;
    
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
    
                    $consult = new newEpisode;
                    $consult->setId($id);
    
                    $isObject = $consult->consultName();
                    $oldName = $isObject->name;
                    $oldRuta = 'views/series/' . $oldName;
    
                    if (file_exists($oldRuta) && !file_exists($ruta)) {
                        if (rename($oldRuta, $ruta)) {
                            $new->setRuta($ruta);
                        } else {
                            echo 'Error al intentar renombrar el directorio.';
                        }
                    } else {
                        echo 'El directorio de destino ya existe o el directorio de origen no existe.';
                    }
                } else {
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                        echo 'Se creó satisfactoriamente';
                        $new->setRuta($ruta);
                    } else {
                        echo 'Error al intentar crear el directorio.';
                        $_SESSION['exists'] = "Exists";
                        header('location:' . base_URL . 'directorio/filtro');
                    }
                }
    
                // Verificación y guardado
                $new->setName($name);
                $new->setType($type);
                $new->setPuntaje($puntaje);
                $new->setDescription($description);
    
                // Guardar imagen logo
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
                    $imagen = $_FILES['imagen'];
                    $fileName = $imagen['name'];
                    $fileTempPath = $imagen['tmp_name'];
                    $img_path = $ruta . '/' . $fileName;
    
                    if (move_uploaded_file($fileTempPath, $img_path)) {
                        $new->setImage_path($fileName);
                    } else {
                        echo 'Error al mover la imagen de logo.';
                    }
                }
    
                // Guardar imagen portada
                if (isset($_FILES['portada']) && $_FILES['portada']['error'] == UPLOAD_ERR_OK) {
                    $imagen_port = $_FILES['portada'];
                    $fileName_port = $imagen_port['name'];
                    $fileTempPort = $imagen_port['tmp_name'];
                    $img_port = $ruta . '/' . $fileName_port;
    
                    if (move_uploaded_file($fileTempPort, $img_port)) {
                        $new->setImage_port($fileName_port);
                    } else {
                        echo 'Error al mover la imagen de portada.';
                    }
                }
    
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $new->setId($id);
                    $save = $new->edit();

                    var_dump($save);
                    die();
                } else {
                    $save = $new->save();
                }
    
                if ($save) {
                    $_SESSION['complete'] = 'complete';
                } else {
                    $_SESSION['complete'] = 'false';
                }
            } else {
                $_SESSION['complete'] = 'false';
            }
        }
    
        header('location:' . base_URL . 'directorio/catalogo');
    }
    
    
}