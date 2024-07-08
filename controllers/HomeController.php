<?php

require_once 'models/users.php';
require_once 'models/directorio.php';
require_once 'models/newEpisode.php';

class homeController{

    public function index(){
        $dirAnime = new dirAnime;
        $epiAnime = new newEpisode;

        $anime = $dirAnime->getAll();
        $list = $epiAnime->getAll();
        $ex = $dirAnime->getExib();
        $max = $dirAnime->getMax();
        $cov = $dirAnime->getCovers();

        require_once 'views/layout/header.php';
        require_once 'views/layout/covers.php';
        require_once 'views/layout/conteiner.php';
        require_once 'views/layout/indice.php';
    }

    public function registro(){

        require_once 'views/formulario/registro.php';
    }

    public function ver(){

        if(isset($_GET['acapite']) && isset($_GET['id'])){
            $acapite = $_GET['acapite'];
            $id = $_GET['id'];

            $maxPuntaje = new dirAnime;
            $max = $maxPuntaje->getMax();

            $watch = new newEpisode;
            $watch->setAcapite($acapite);
            $watch->setId($id);

            $cant = $watch->consultCant();
            $one = $watch->getUnic();

        }
        require_once 'views/layout/watch.php';
    }


    public function save(){

        if(isset($_POST)){

            //Recibir datos del formulario

            $name = isset($_POST['name']) ? $_POST['name'] : 'false';
            $surname = isset($_POST['surname']) ? $_POST['surname'] : 'false';
            $email = isset($_POST['email']) ? $_POST['email'] : 'false';
            $password = isset($_POST['password']) ? $_POST['password'] : 'false';
            $passAdmin = isset($_POST['pass_admin']) ? $_POST['pass_admin'] : 'false';

            //Validacion 

            $errores = array();

            if(!empty($name) && !is_numeric($name) && !preg_match("/[0-9]/", $name)){
                $val_name = true;                
            }else{
                $val_name = false;
                $errores['name'] = "El nombre es incorrecto";
            }
            
            if(!empty($surname) && !is_numeric($surname) && !preg_match("/[0-9]/", $surname)){
                $val_surname = true;                
            }else{
                $val_surname = false;
                $errores['surname'] = "El apellido es incorrecto";
            }
            
            if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) ){
                $val_email = true;                
            }else{
                $val_email = false;
                $errores['email'] = "El email es incorrecto";
            }
            
            if(!empty($password)){
                $val_password = true;                
            }else{
                $val_password = false;
                $errores['password'] = "La contraseña esta incompleta";
            }

            
            if(count($errores) == 0){
                
                $user = new Users;
                $user ->setName($name);
                $user ->setSurname($surname);
                $user ->setEmail($email);


                //Password Administrador
                
                if($passAdmin == 'admin'){

                    $user ->setRole('admin');
                }else{
                    
                    $user ->setRole('user');
                }
                
                $user ->setPassword($password);

                $save = $user->save();          
                
                if($save){
                    $_SESSION['register'] = 'complete';
                
                }else{
                    $_SESSION['register'] = 'falled';
                }           
            }else{
                $_SESSION['register'] = 'falled';
            }
            header('location:'.base_URL.'home/index');
        }
    }

    public function login(){

        if(isset($_POST)){

            $usuario = new Users;
            $usuario-> setEmail($_POST['email']);
            $usuario-> setPassword($_POST['password']);

            $identity = $usuario->login();

            if($identity && is_object($identity)){
                $_SESSION['identity'] = $identity;
                
                if('admin' == $identity->role){
                    $_SESSION['admin'] = true;
                }else{
                    $_SESSION['admin'] = false;
                }

            }else{
                $_SESSION['error_login'] = "Identificacion Fallida";
            }
        }
        header('location:'.base_URL);
    }
    
    public function logout(){
        if(isset($_SESSION['identity'])){
            unset($_SESSION['identity']);
        }

        if(isset($_SESSION['admin'])){
            unset($_SESSION['admin']);
        }

        header('location:'.base_URL);
    }
}