<?php

$loader = new Twig_Loader_Filesystem(ROOT . '/templates');
$twig = new Twig_Environment($loader, array('cache' => sys_get_temp_dir() . '/marketing/cache',));


$app = new \Klein\Klein();


$app->get('/', function () use ($twig)
{
    echo $twig->render('index.html', ['name' => 'Fabien']);
});

$app->get('/resetpassword', function () use ($twig)
{
    echo $twig->render('index.html', ['name' => 'Fabien']);
});

$app->dispatch();