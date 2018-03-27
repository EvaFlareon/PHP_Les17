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

$connect = include 'config.php';

$users = mysqli_query($connect, "select * from user");
$users_id = [];
$users_login = [];
while ($data = mysqli_fetch_array($users)) {
	$users_id[] = $data['id'];
	$users_login[] = $data['login'];
}

$user_id = mysqli_query($connect, "select * from user where id = ".$_SESSION['user']);
$user = mysqli_fetch_array($user_id);

$sql = "select * from task left join user on user.id=task.assigned_user_id where task.user_id = ".$_SESSION['user'];
$sql_rep = "select * from task left join user on user.id=task.user_id where task.assigned_user_id = ".$_SESSION['user']." and task.user_id <> ".$_SESSION['user'];

if (!empty($_POST)) {
	if (isset($_POST['add']) && $_POST['adding'] !== '') {
		mysqli_query($connect, "insert into `task`(`user_id`, `assigned_user_id`, `description`) values ('".$user['id']."', '".$user['id']."', '".$_POST['adding']."')");
		//header('Location: tasks.php');
	}

	foreach ($_POST as $key => $value) {
		if ($key[0] === 'c' && $value != '') {
			$i = substr($key, 1);
			mysqli_query($connect, "update task set is_done = 1 where id = ".$i);
			//header('Location: ./templates/tasks.twig');
		}

		if ($key[0] === 'd' && $value != '') {
			$i = substr($key, 1);
			mysqli_query($connect, "delete from task where id = ".$i);
			//header('Location: ./templates/tasks.twig');
		}

		if ($key[0] === 'r' && $value != '') {
			$i = substr($key, 1);
			for ($j = 0; $j < count($users_login); $j++) {
				if ($_POST['user_rep'] === $users_login[$j]) {
					$new_user = $j;
				}
			}
			mysqli_query($connect, "update task set assigned_user_id = ".$users_id[$new_user]." where id = ".$i);
			//header('Location: ./templates/tasks.twig');
		}
	}
}

$res = mysqli_query($connect, $sql);

$res_rep = mysqli_query($connect, $sql_rep);

$params = array(
	'user' => $user['login'],
	'users' => $users_login,
	'main_table' => $res,
	'table' => $res_rep
);

echo $twig->render(
	'tasks.twig', $params); 

?>