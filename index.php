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

$connect = include 'config.php';
$sql = "select * from user";

if (!empty($_POST)) {
    if (isset($_POST['input'])) {
        $res = mysqli_query($connect, $sql);
        while ($data = mysqli_fetch_array($res)) {
            if ($data['login'] === $_POST['login'] && $data['password'] === $_POST['password']) {
                $_SESSION['user'] = $data['id'];
                header('Location: ./templates/tasks.twig');
            }
        }
    }

    if (isset($_POST['reg'])) {
        $res = mysqli_query($connect, $sql);
        while ($data = mysqli_fetch_array($res)) {
            if ($data['login'] === $_POST['login']) {
                $err = 'Пользователь с таким логином уже существует';
                break;
            } else if ($_POST['login'] === '' || $_POST['password'] === '') {
                $err = 'Не все поля заполнены';
                break;
            }
        }
        
        if (isset($err)) {
            // echo $err;
            echo $twig->render('index.twig', ['err' => $err]);
        } else {
            mysqli_query($connect, "insert into `user`(`login`, `password`) values ('".$_POST['login']."','".$_POST['password']."')");
            echo 'Вы успешно зарегистрированы. Войдите под своим логином и паролем';
        }
    }
}

?>