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

$klein->get('/auth', function (Request $request, Response $response, $service, $app)
{
    return $response->redirect('/');
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

$klein->post('/resetpwd', function (Request $request, Response $response, $service, $app)
{

    $msg = '';

    $result = $app->auth->userNameValidator($request->param('username'), $request->param('old_pass'));

    if ($result)
    {
        $changePassword = $app->auth->changePassword($request->param('username'), $request->param('new_pass'));
        if ($changePassword)
        {
            $msg = 'Password changed successfully';
        }
        else
        {
            $msg = 'Error trying to change password';
        }
    }
    else
    {
        $msg = 'Please verify your old password';
    }

    return $app->view->render('admin.html', ['user' => getenv('USERNAME'), 'admin' => getenv('ADMIN-USERNAME'),
        'msg_div' => $request->param('req_type'), 'msg' => $msg]);

});

$klein->post('/upload', function (Request $request, Response $response, $service, $app)
{


    if (isset($_FILES["upload_csv"]))
    {
        if ($_FILES["upload_csv"]["error"] >= 0)
        {
            //Print file details
            echo "Upload: " . $_FILES["upload_csv"]["name"] . "<br />";
            echo "Type: " . $_FILES["upload_csv"]["type"] . "<br />";
            echo "Size: " . ($_FILES["upload_csv"]["size"] / 1024) . " Kb<br />";
            echo "Temp file: " . $_FILES["upload_csv"]["tmp_name"] . "<br />";


            //Store file in directory "upload" with the name of "uploaded_file.txt"
            $exportFile = "export.csv";
            move_uploaded_file($_FILES["upload_csv"]["tmp_name"], ROOT . "/storage/" . $exportFile);

            $row = 1;
            if (($handle = fopen(ROOT . "/storage/" . $exportFile, "r")) !== false)
            {
                while (($data = fgetcsv($handle, 1000, ",")) !== false)
                {

                    $num = count($data);
                    echo "<p> $num fields in line $row: <br /></p>\n";
                    $row++;
                    for ($c = 0; $c < $num; $c++)
                    {
                        echo $data[$c] . "<br />\n";
                    }
                }
                fclose($handle);
            }


        }
        else
        {
            echo "Return Code: " . $_FILES["upload_csv"]["error"] . "<br />";
        }
    }


});


$klein->get('/test', function (Request $request, Response $response, $service, $app){
    //return $app->view->render(ROOT . '/templates/', []);
    return $app->view->render("test.html", []);
});


$klein->dispatch();

// todo en el admin page verificar con javascript que el old and new passwords are the same
// todo hacer un footer en el template con el logo de illy
// todo check why if you don't type in any password it works
// todo if redirected to any url doesn't exist make it forward to home page