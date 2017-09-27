<?php
namespace App\Handler;

use App\Config;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleHttp;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class Request
{
    const tips = [
        'The interface does not exist',
        'The path does not exist',
        'Unable to parse the YAML string',
        'Unknown exception'
    ] ;

    /**
     * @param null $key
     * @return mixed|string
     */
    public static function getConfig($key = null){
        $path = Config\Path::getPath($key) ? file_get_contents(Config\Path::getPath($key)) : false;
        return $path ? Yaml::parse($path) : self::tips[1];
    }

    /**
     *
     * @param null $key
     * @return mixed
     */
    public static function getParse($key = null){
        try{
            $api = self::getConfig(0);
            return is_array($api) && array_key_exists($key, $api) ? $api[$key] : self::tips[0];
        }catch (ParseException $e){
            return self::tips[3].': '.$e->getMessage();
        }
    }

    /**
     * @return Client
     */
    public static function initializeClient(){
        $main = self::getConfig(1);
        return new Client([
            'base_uri' => $main['main']['interface'],
            'timeout'  => 8.0,
            'curl' => array( CURLOPT_SSL_VERIFYPEER => false, )
        ]);
    }

    /**
     * @param null $type
     * @param null $url
     * @param null $parse
     * @return mixed|string
     */
    public static function initiateRequest($type = null, $url = null, $parse = null){
        try {
            $client = self::initializeClient();
            $request = $type == 'get'? $client->request( $type, $url.'?'.http_build_query($parse)) : $request = $client->request( $type, $url, [
                'json' => $parse
            ]);
            return json_decode($request->getBody()->getContents());
        } catch (RequestException $e) {
            return $e->hasResponse() ? Psr7\str($e->getResponse()) : self::tips[3];
        }
    }

    /**
     *
     * @param null $key
     * @return mixed|string
     */
    public static function getResponse($key = null, $parse = null){
        return $key ? self::initiateRequest(self::getParse($key)['type'], self::getParse($key)['url'], $parse) : self::tips[0];
    }
}