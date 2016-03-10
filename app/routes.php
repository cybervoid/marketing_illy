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
        $importedReport = getenv('REPORT_NAME');
        if(file_exists(ROOT . '/storage/' . $importedReport)) $report = $importedReport; else
            $report = false;


        return $app->view->render( $access . '.html', [
            'report' => $report,
            'user' => getenv('USERNAME'),
            'admin' => getenv('ADMIN-USERNAME')
        ]);

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
            $file_size = ($_FILES["upload_csv"]["size"] / 1024);

            if($file_size < 1 ){
                return $app->view->render( 'messages.html', [
                    'message' => 'error_upload'
                ]);
            }

            //Store file in directory "upload" with the name of "uploaded_file.txt"
            $uploadedFile = "uploaded.csv";
            move_uploaded_file($_FILES["upload_csv"]["tmp_name"], ROOT . "/storage/" . $uploadedFile);

            $csv= file_get_contents(ROOT . '/storage/' . $uploadedFile);
            $importedData = array_map("str_getcsv", explode("\n", $csv));

            $data = [];

            foreach ($importedData as $row){
                if(isset($row[1]) && isset($row[2])){
                    if($row[1]!='' && $row[2]!='') $data[]= $row;
                }
            }


            file_put_contents(ROOT . '/storage/' . 'uploaded_report.html',
                $app->view->render( 'build_report.html', [
                    'data' => $data
                ])
            );

            return $app->view->render( 'report.html', [
                'report' => getenv('REPORT_NAME')
            ]);

        }
        else
        {
            echo "Return Code: " . $_FILES["upload_csv"]["error"] . "<br />";
        }
    }
});



$klein->dispatch();

// todo en el admin page verificar con javascript que el old and new passwords are the same
// todo hacer un footer en el template con el logo de illy
// todo check why if you don't type in any password it works
// todo if redirected to any url doesn't exist make it forward to home page