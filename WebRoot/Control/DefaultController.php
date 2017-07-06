<?PHP

class DefaultController{
	
	//Function that is clled by default when no path
	// or invlid pth is specified
	public static function index(){
		//Display main page
		include(DOCROOT."/View/main.phtml");
	}
}