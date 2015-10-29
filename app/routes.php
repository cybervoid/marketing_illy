<?php


use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;

$klein = new \Klein\Klein();

$klein->respond(function ($request, $response, $service, $app) use ($klein)
{
    // Handle exceptions => flash the message and redirect to the referrer
    $klein->onError(function ($klein, $err_msg)
    {
        $klein->service()->flash($err_msg);
        $klein->service()->back();
    });

    // The fourth parameter can be used to share scope and global objects
    $app->register('db', function()
    {
        return new PDO(getenv('DSN'));
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

$klein->get('/', function (Request $request, Response $response, ServiceProvider $service, $app)
{

    $error = '';
    $messages = $service->flashes('error');
    if(isset($messages[0])) $error = $messages[0];

    return $app->view->render('login.html', ['error' => $error]);
});

$klein->post('/auth', function (Request $request, Response $response, $service, $app)
{

//    var_dump($request->params());
    $db = new PDO(getenv('DSN'));
    $result = $db->query("SELECT * FROM user",PDO::FETCH_ASSOC);
    $row = $result->fetch();

    if(($row['username']===$request->param('username')) && ($row['password']===$request->param('password')))
        return $app->view->render('report.html', []); else{
        $service->flash('Wrong username and/or password.', 'error');
        $response->redirect('/');
    }
});

$klein->get('/resetpassword', function (Request $request, Response $response, ServiceProvider $service, $app)
{
    echo $app->view->render('index.html', ['name' => 'Fabien']);
});

$klein->dispatch();