<?php

require_once './vendor/autoload.php';

session_start();

$loader = new Twig_Loader_Filesystem('./templates');

$twig = new Twig_Environment($loader, array(
	'cache' => './tmp/cache',
	'auto_reload' => true,
));

if (!empty($_SESSION['user'])) {
    header('Location: ./templates/tasks.twig');
}

$config = include 'config.php';

include 'connect.php';

$db = DataBase::connect(
    $config['mysql']['host'],
    $config['mysql']['dbname'],
    $config['mysql']['user'],
    $config['mysql']['pass']
);

if (!empty($_POST)) {
    if (isset($_POST['input'])) {
        $res = DataBase::show($db);
        while ($data = $res->fetch()) {
            if ($data['login'] === $_POST['login'] && $data['password'] === $_POST['password']) {
                $_SESSION['user'] = $data['id'];
                header('Location: ./templates/tasks.twig');
            }
        }
    }

    if (isset($_POST['reg'])) {
        $res = DataBase::show($db);
        while ($data = $res->fetch()) {
            if ($data['login'] === $_POST['login']) {
                $err = 'Пользователь с таким логином уже существует';
                break;
            } else if ($_POST['login'] === '' || $_POST['password'] === '') {
                $err = 'Не все поля заполнены';
                break;
            }
        }
        
        if (isset($err)) {
            echo $twig->render('index.twig', ['err' => $err]);
        } else {
            DataBase::insert($db, $_POST['login'], $_POST['password']);
            echo $twig->render('index.twig', ['err' => 'Вы успешно зарегистрированы. Войдите под своим логином и паролем']);
        }
    }
}

?>