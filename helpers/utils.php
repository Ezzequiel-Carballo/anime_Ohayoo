<?php

class Utils{

    public static function isAdmin(){
        if(!isset($_SESSION['admin'])){
            header('location:'.base_URL);
        }else{
            return true;
        }
    }

    public static function deleteSession($name){
		if(isset($_SESSION[$name])){
			$_SESSION[$name] = null;
			unset($_SESSION[$name]);
		}
		
		return $name;
	}

    public static function dirAll(){
        require_once 'models/directorio.php';
        $direc = new dirAnime;
        $dir = $direc-> getAll();

        return $dir;
    }

    public static function show_error(){
        $error = new errorController();
        $error->index();
    }
}