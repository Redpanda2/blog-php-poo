<?php

namespace App;

use Twig\Environment;

class Application
{
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Environment $twig;
    public static Application $app;

    public function __construct(Environment $twig)
    {
        self::$app = $this;
        $this->request = new Request();
        $this->session = new Session();
        $this->response = new Response();
        $this->twig = $twig;
        $this->twig->addGlobal('session', $_SESSION);
        $this->router = new Router($this->request, $this->response);
    }

    public function run()
    {
        try {
            $this->router->resolve();
        }
        catch (\Exception $exception)
        {
            if($_ENV['ENV'] === 'dev'){
                echo $exception->getMessage();
                die();
            }
            else if($_ENV['ENV'] === 'prod')
            {
//                Session::setFlash('error', "Une erreur interne s'est produite");
                $this->twig->render('/general/_500.html.twig');

            }
        }
    }
}