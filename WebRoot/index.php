<?php
//index.php will always be the first thing loaded when any page is accessed,
// this will be ensured by the .htaccess file which I will be adding soon

//Error reporting
ini_set('display_errors',1);
error_reporting(E_ALL);

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

//Set default page title
$GLOBALS['title'] = "Cool Game";

//Get URI, remove "/index.php" if necessary
$uri = str_replace("/index.php", "", $_SERVER['REQUEST_URI']);

//Split URI into different path sections
//The first section will simply be the domain name,
// the rest is the actul path, for example:
// "ourdomain.com/user/view/fred" will become {"ourdomain.com", "user", "view", "fred"}
$path_array = explode("/", $uri, 4);

//Init variables
$controller = $action = $data = "";

//Whether the address bar contains a valid path
$is_valid_path = false;

//Check that the path has at least the controller name and method
if(count($path_array) >= 3){
	//Get controller clss name from first path part,
	// in the above example, would be "UserController"
	$controller = ucfirst($path_array[1]) . "Controller";
	//Get method to cll on the controller class, simply the second pth section,
	// in the above example, would be "view"
	$action = $path_array[2];
	//Check that the specified controller and method exist
	if( class_exists($controller) && method_exists($controller, $action) ){
		//They exist, so the path is valid
		$is_valid_path = true;
		//Check if there is any extra data in the address bar
		if(isset($path_array[3])){
			//Get data from path
			$data = $path_array[3];
		}
	}
}

//Check if path was invalid
if(!$is_valid_path){
	//Invalid, so set controller to default,
	// and default to displaying index
	$controller = "DefaultController";
	$action = "index";
}

//Call the method on the controller and pass the data as parameter
//This is possible because PHP allows string variables to be used
// as class and function names,
// in the above example would resut in the following function call:
//UserController::view("fred");
// the default will be the following function call:
//DefaultController::index("");
$controller::$action($data);

//Also, notice that the index method in the DefaultController does
// not actually take any parameters, but it still gets called,
// so PHP will simply discard the parameter when it calls the function