<?php


use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;

$klein = new \Klein\Klein();

require_once 'default_controller.php';

$klein->get('/', function (Request $request, Response $response, ServiceProvider $service, $app)
{

    $error = '';
    $messages = $service->flashes('error');
    if (isset($messages[0]))
    {
        $error = $messages[0];
    }

    return $app->view->render('login.html', ['error' => $error]);
});

$klein->post('/auth', function (Request $request, Response $response, $service, $app)
{
    $access = $app->auth->loginValidator($request);

    if (!$access)
    {
        $service->flash('Wrong username and/or password.', 'error');
        return $response->redirect('/');
    }
    else
    {
        return $app->view->render($access . '.html', ['user' => getenv('USERNAME'),
            'admin' => getenv('ADMIN-USERNAME')]);
    }


});

$klein->post('/resetusr', function (Request $request, Response $response, $service, $app)
{
    // averiguar que hace access

    $result = userNameValidator($request->param('username'), $request->param('user_old_pass'));

    if ($result)
    {
        changePassword($request->param('username'), $request->param('user_new_password'));
    }
    else
    {
        return $app->view->render($access . '.html', ['user' => getenv('USERNAME'),
            'admin' => getenv('ADMIN-USERNAME')]);
        $service->flash('Please verify your old password', 'error');
    }
});

function changePassword($username, $password)
{
    $db = new PDO(getenv('DSN'));
    //$result = $db->query("UPDATE username, password FROM user WHERE username='" . $username . "'", PDO::FETCH_ASSOC);
    $result = $db->query("UPDATE user SET password='" . $password . "' WHERE username='" . $username . "'", PDO::FETCH_ASSOC);
    var_dump($result);
    die;
}

function userNameValidator($username, $password)
{

    $db = new PDO(getenv('DSN'));
    $result = $db->query("SELECT username, password FROM user WHERE username='" . $username . "'", PDO::FETCH_ASSOC);
    $row = $result->fetch();

    if (($row['username'] === $username) && ($row['password'] === $password))
    {
        return true;
    }
    else
    {
        return false;
    }

}




$klein->dispatch();

// todo en el admin page verificar con javascript que el old and new passwords are the same
// todo hacer un footer en el template con el logo de illy