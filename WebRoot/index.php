<?php

//Define shorthand constant for the server doc root
define("DOCROOT", $_SERVER['DOCUMENT_ROOT']);

//Autoload functions for different folders
function load_controller($class_name){
    @include DOCROOT."/Control/".$class_name.".php";
}
function load_application($class_name){
    @include DOCROOT."/Application/".$class_name.".php";
}
function load_model($class_name){
    @include DOCROOT."/Model/".$class_name.".php";
}

//Register autoload functions
spl_autoload_register("load_controller");
spl_autoload_register("load_application");
spl_autoload_register("load_model");

//Get URI, remove "/index.php" if necessary
$uri = str_replace("/index.php", "", $_SERVER['REQUEST_URI']);
//Split URL into different path sections
$path_array = explode("/", $uri, 4);

//Init variables
$controller = $action = $data = "";

//Tracking variable
$valid_path = false;

if(isset($path_array[1]) && isset($path_array[2])){
    $controller = ucfirst($path_array[1]) . "Controller";
    if(class_exists($controller)){
        $action = $path_array[2];
        if(method_exists($controller, $action)){
            $valid_path = true;
            if(isset($path_array[3])){
                $data = $path_array[3];
            }
        }
    }
}

if(!$valid_path){
    $controller = "DefaultController";
    $action = "index";
}

$controller::$action($data);