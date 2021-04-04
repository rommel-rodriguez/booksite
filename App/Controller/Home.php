<?php
namespace Malaz\Booksite\Controller;
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
use Symfony\Component\Routing\Annotation\Route;
class Home{
    public function __construct(){

    }
    /**
     * @Route(
     *      "/",
     *      name="default_route"
     * )
     * @Route(
     *      "/home",
     *      name="home_route"
     * )
     */
    public static function index(){
        ## Does not work if loader and twig initialization are outside
        $loader = new \Twig\Loader\FilesystemLoader($_SERVER['DOCUMENT_ROOT'] . '/App/View/layouts');
        $twig = new \Twig\Environment($loader);
        $twigTemplate = 'index_template.html.twig';
        $bookList = array();
        $title = "NoTitle";
        echo $twig->render($twigTemplate, ['bookList' => $bookList, 'title' => $title]);
    }
}
?>