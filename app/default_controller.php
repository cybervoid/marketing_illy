<?php

use Klein\App;
use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;

$klein->respond(function(Request $request, Response $response, ServiceProvider $service, App $app) use ($klein)
{
    // Handle exceptions => flash the message and redirect to the referrer
    $klein->onError(function ($klein, $err_msg)
    {
        $klein->service()->flash($err_msg);
        $klein->service()->back();
    });

    $app->register('auth', function() use ($request, $app)
    {
        return new \AuthService($request, $app);
    });

    // The fourth parameter can be used to share scope and global objects
    $app->register('db', function ()
    {
        return new PDO(sprintf(getenv('DSN'), ROOT));
    });

    $loader = new Twig_Loader_Filesystem(ROOT . '/templates');

    $cache = false;
    if (getenv('CACHE'))
    {
        $cache = sys_get_temp_dir() . getenv('CACHE');
    }

    $twig = new Twig_Environment($loader, array('cache' => $cache, 'debug' => getenv('DEV')));
    $app->view = $twig;
});