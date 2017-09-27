<?php
namespace App\Simplex;

use App\Config;
use App\Simplex\Framework;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class Simplex
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return RequestStack
     */
    public function getRequestStack(){
        return new RequestStack();
    }

    /**
     * @return Routing\RouteCollection
     */
    public function getRoutes(){
        $locator = new FileLocator();
        $loader = new YamlFileLoader($locator);
        $routes = $loader->load(Config\Path::getPath(3));

        return $routes;
    }

    /**
     * @return Routing\RequestContext
     */
    public function getContext(){
        return new Routing\RequestContext();
    }

    /**
     * @param $routes
     * @param $context
     * @return Routing\Matcher\UrlMatcher
     */
    public function getMatcher($routes, $context){
        return  new Routing\Matcher\UrlMatcher($routes, $context);
    }

    /**
     * @return HttpKernel\Controller\ControllerResolver
     */
    public function getControllerResolver(){
        return new HttpKernel\Controller\ControllerResolver();
    }

    /**
     * @return HttpKernel\Controller\ArgumentResolver
     */
    public function getArgumentResolver(){
        return new HttpKernel\Controller\ArgumentResolver();
    }

    /**
     * @param $matcher
     * @param $requestStack
     * @return EventDispatcher
     */
    public function getDispatcher($matcher, $requestStack){
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new HttpKernel\EventListener\RouterListener($matcher, $requestStack));
        return $dispatcher;
    }

    /**
     * @param $dispatcher
     * @param $controllerResolver
     * @param $requestStack
     * @param $argumentResolver
     * @return \App\Simplex\Framework|HttpKernel\HttpCache\HttpCache
     */
    public function Application($dispatcher, $controllerResolver, $requestStack, $argumentResolver){
        $framework = new Framework($dispatcher, $controllerResolver, $requestStack, $argumentResolver);
        $framework = new HttpKernel\HttpCache\HttpCache(
            $framework,
            new HttpKernel\HttpCache\Store(__DIR__.'/../../cache')
        );

        return $framework;
    }

    /**
     * @return Simplex|Response
     */
    public function handle(){
        $app = $this->Application(
            $this->getDispatcher($this->getMatcher($this->getRoutes(), $this->getContext()), $this->getRequestStack()),
            $this->getControllerResolver(),
            $this->getRequestStack(),
            $this->getArgumentResolver())->handle($this->request)->send();

        return $app;
    }
}