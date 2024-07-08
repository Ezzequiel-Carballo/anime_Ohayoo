<?php

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciando Session
session_start();

// Iniciar el buffer de salida
ob_start();

require_once 'autoload.php';
require_once 'config/db.php';
require_once 'config/parameters.php';
require_once 'helpers/utils.php';

if (isset($_GET['controller'])) {
    $nombre_controller = $_GET['controller'] . 'Controller';
}elseif (!isset($_GET['controller']) && !isset($_GET['action'])) {
    $nombre_controller = controller_default;
}else{
    Utils::show_error();
    exit();
}

if (class_exists($nombre_controller)) {
    $controller = new $nombre_controller;

    if (isset($_GET['action']) && method_exists($controller, $_GET['action'])) {
        $action = $_GET['action'];
        $controller->$action();
    } elseif (!isset($_GET['controller']) && !isset($_GET['action'])) {
        $action_default = action_default;
        $controller->$action_default();
    } else {
        Utils::show_error();
    }
} else {
    Utils::show_error();
}

// Terminar el buffer de salida y enviar la salida
ob_end_flush();

require_once 'views/layout/footer.php';
