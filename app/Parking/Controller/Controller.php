<?php
namespace App\Parking\Controller;

use App\Handler\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;

abstract class Controller implements EngineInterface
{
    /**
     * @return mixed
     */
    public function getPath(){
        return Request::getConfig(1);
    }

    /**
     * @return FilesystemLoader
     */
    public function getFilesystemLoader(){
        return new FilesystemLoader($this->getPath()['main']['template']);
    }

    /**
     * @param string|\Symfony\Component\Templating\TemplateReferenceInterface $name
     * @param array $parameters
     * @return Response
     */
    public function render($name, array $parameters = array())
    {
        $template = new PhpEngine(new TemplateNameParser(), $this->getFilesystemLoader());
        return new Response($template->render($name, $parameters));
    }

    /**
     * @param string|\Symfony\Component\Templating\TemplateReferenceInterface $name
     * @return bool
     */
    public function exists($name)
    {
        try {
            $this->load($name);
        } catch (\InvalidArgumentException $e) {
            return false;
        }

        return true;
    }

    /**
     * @param string|\Symfony\Component\Templating\TemplateReferenceInterface $name
     * @return bool
     */
    public function supports($name)
    {
        $template = $this->parser->parse($name);

        return 'php' === $template->get('engine');
    }


}