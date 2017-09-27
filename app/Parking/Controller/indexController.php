<?php
namespace App\Parking\Controller;

use App\Handler;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;

class indexController extends Controller
{
    const tips = [
        ['status' => '404', 'message' => 'The interface does not exist'],
        ['status' => '10005', 'message' => 'Missing request parameters']
     ];

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('error/404.php', array(
            'title'=> 'hello',
            'name' => ' World!',
            'list' => ['4', '0', '4'],
            'staticAddress' => 'http://192.168.1.54:8089/park/parkingfs/src',
            'desc'=> 'hello/world',
            'indexUrl'=> 'hello/world',
            'helpUrl'=> 'hello/world'
        ));
    }

    /**
     * @param string $name
     * @return Response
     */
    public function helloAction($name = 'World')
    {
        $response = new Response('Hello '.$name.rand(1000, 10000));

        $response->setTtl(10);

        return $response;
    }

    /**
     * @desc Access-Control-Allow-Origin * / Content-Type Content-Type
     * @param Request $request
     * @param null $name
     * @return Response
     */
    public function apiAction(Request $request, $name = null){
        $parse = $request->request->all() ? $name ? Handler\Request::getResponse($name, $request->request->all()) : self::tips[0] : self::tips[1] ;
        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($parse));
        return $response;
        //return new Response(json_encode($parse));
    }
}