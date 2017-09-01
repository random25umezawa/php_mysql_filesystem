<?php
	require "db_connection.php";

	$path = $_REQUEST["path"];
	$name = $_REQUEST["name"];
	$type = $_REQUEST["type"];

	/*

	$pdo = db_connect();
	$sql = "insert into files (path,name,type) values (:path,:name,:type)";
	$stmt = $pdo->prepare($sql);

	$stmt->bindValue(":path",$path,PDO::PARAM_STR);
	$stmt->bindValue(":name",$name,PDO::PARAM_STR);
	$stmt->bindValue(":type",$type,PDO::PARAM_INT);

	*/

	$sql = "select * from files where path like {$like} order by path desc limit 1";

	print($stmt->execute());
