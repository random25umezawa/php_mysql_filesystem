<?php
	require "db_connection.php";

	$pdo = db_connect();
	$sql = "select * from files";
	$stmt = $pdo->prepare($sql);

	$stmt->execute();

	echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
