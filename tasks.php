<?php

session_start();

if (empty($_SESSION['user'])) {
	header('Location: ./templates/index.twig');
}

if (isset($_POST['exit'])) {
    session_destroy();
    header('Location: ./templates/index.twig');
}

$loader = new Twig_Loader_Filesystem('./templates');

$twig = new Twig_Environment($loader, array(
	'cache' => './tmp/cache',
	'auto_reload' => true,
));

$config = include 'config.php';

include 'connect.php';

$db = DataBase::connect(
    $config['mysql']['host'],
    $config['mysql']['dbname'],
    $config['mysql']['user'],
    $config['mysql']['pass']
);

$users = DataBase::show($db);

$users_id = [];
$users_login = [];

while ($data = $users->fetch()) {
	$users_id[] = $data['id'];
	$users_login[] = $data['login'];
}

$user_id = DataBase::session_user($db, $_SESSION['user']);
$user = $user_id->fetch();

if (!empty($_POST)) {
	if (isset($_POST['add']) && $_POST['adding'] !== '') {
		DataBase::adding($db, $user['id'], $_POST['adding']);
		header('Location: tasks.php');
	}

	foreach ($_POST as $key => $value) {
		if ($key[0] === 'c' && $value != '') {
			$i = substr($key, 1);
			DataBase::main_update($db, $i);
			header('Location: ./templates/tasks.twig');
		}

		if ($key[0] === 'd' && $value != '') {
			$i = substr($key, 1);
			DataBase::del_item($db, $i);
			header('Location: ./templates/tasks.twig');
		}

		if ($key[0] === 'r' && $value != '') {
			$i = substr($key, 1);
			for ($j = 0; $j < count($users_login); $j++) {
				if ($_POST['user_rep'] === $users_login[$j]) {
					$new_user = $j;
				}
			}
			DataBase::rep_update($db, $users_id[$new_user], $i);
			header('Location: ./templates/tasks.twig');
		}
	}
}

$res = DataBase::main_join($db, $_SESSION['user']);
$res_rep = DataBase::rep_join($db, $_SESSION['user']);

$params = array(
	'user' => $user['login'],
	'users' => $users_login,
	'main_table' => $res,
	'table' => $res_rep
);

echo $twig->render(
	'tasks.twig', $params); 

?>