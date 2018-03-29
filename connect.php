<?php

class DataBase
{
	public static function connect($host, $dbname, $user, $pass)
	{
		$db = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $pass);
		return $db;
	}

	public function show($db,)
	{
		return $db->query("select * from user");
	}

	public function insert($db, $login, $pass)
	{
		return $db->query("insert into `user`(`login`, `password`) values ('".$login."','".$pass."')");
	}

	public function session_user($db, $user)
	{
		return $db->query("select * from user where id = ".$user);
	}

	public function adding($db, $user_id, $add)
	{
		return $db->query("insert into `task`(`user_id`, `assigned_user_id`, `description`) values ('".$user_id."', '".$user_id."', '".$add."')");
	}

	public function main_update($db, $i)
	{
		return $db->query("update task set is_done = 1 where id = ".$i);
	}

	public function rep_update($db, $new_user, $i)
	{
		return $db->query("update task set assigned_user_id = ".$new_user." where id = ".$i);
	}

	public function del_item($db, $i)
	{
		return $db->query("delete from task where id = ".$i);
	}

	public function main_join($db, $user)
	{
		return $db->query("select * from task left join user on user.id=task.assigned_user_id where task.user_id = ".$user);
	}

	public function rep_join($db, $user)
	{
		return $db->query("select * from task left join user on user.id=task.user_id where task.assigned_user_id = ".$user." and task.user_id <> ".$user);
	}
}

?>