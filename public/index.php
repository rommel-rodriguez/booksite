<?php
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
## TODO: Move Router to its own file

### Testing Autoloading
#echo "Testing Autoloading: {$_SERVER['DOCUMENT_ROOT']}";
#$obj = new \Malaz\Booksite\Dummy\Test();
#$obj->just_hello();
### End Of Testing Autoloading

## Should the router work using a variable inside the get request like: /?url=/path/to/somthing 
## then just read the $_GET variable appropiately

## require_once '../App/init.php';
### $app = new App();
### Router Testing
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
### Annotation Loader particular imports:
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Routing\AnnotatedRouteControllerLoader;
use Symfony\Component\Routing\Loader\AnnotationDirectoryLoader;
 
try
{
    $fileLocator = new FileLocator(array(__DIR__));
 
    $requestContext = new RequestContext();
    $requestContext->fromRequest(Request::createFromGlobals());
## Using YAML Loader
#    $routesLoader = new YamlFileLoader($fileLocator);
#    $loaderConfig = 'routes.yaml';
## Using Annotations Loader
    $routesLoader = new AnnotationDirectoryLoader(
      new FileLocator($_SERVER['DOCUMENT_ROOT'] .'/App/Controller/'),
      new AnnotatedRouteControllerLoader(
          new AnnotationReader()
      )
    );
    $loaderConfig = $_SERVER['DOCUMENT_ROOT'] . '/App/Controller/';
    $router = new Router(
        $routesLoader,
        $loaderConfig,
        array('cache_dir' => __DIR__.'/cache'),
        $requestContext
    );
 
#    $router = new Router(
#        new YamlFileLoader($fileLocator),
#        'routes.yaml',
#        array('cache_dir' => __DIR__.'/cache'),
#        $requestContext
#    );
 
    # echo $requestContext->getPathInfo();
    // Find the current route
    $parameters = $router->match($requestContext->getPathInfo());
    error_log("[SHADOW LOG] " . $requestContext->getPathInfo());
    error_log("[SHADOW LOG] Path Info: " . $_SERVER['PATH_INFO'] );
    #var_dump($parameters);
    #$controllerClass = $parameters['_controller'][0];
    #$controllerAction = $parameters['_controller'][1];
    
    ### Load controller for YAML Loader
    # $cont= explode("::",$parameters['controller']);
    # $controllerClass = "\\Malaz\\Booksite\\Controller\\{$cont[0]}";
    # $controllerAction= $cont[1];
    # // Instance the controller
    # //$controller = new \Malaz\Booksite\Controller\$controllerClass();
    # $controller = new $controllerClass(); 
    # // Execute it
    # call_user_func([$controller, $controllerAction], array());
    ### End Of Load controller for YAML Loader
    ### Load controller for Annotation Loader
    $controllerAction =$parameters['_controller'];
    $controllerAction();
    ### End Of Load controller for Annotation Loader
}
catch (ResourceNotFoundException $e)
{
  echo $e->getMessage();
}
?>