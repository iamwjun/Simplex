<?php
namespace App\Controller;

use App\Handler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        return $this->render('index.php', array(
            'first' => 'Hello Simplex!'
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