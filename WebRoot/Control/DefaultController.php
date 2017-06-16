<?PHP

class DefaultController{
    public static function index(){
        include(DOCROOT."/View/main.phtml");
    }
}