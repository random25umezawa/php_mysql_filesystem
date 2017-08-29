<?php
/*
	require "db_connection.php";

	$pdo = db_connect();
	$sql = "insert into files (path,name,type) values (:path,:name,:type)";
	$stmt = $pdo->prepare($sql);

	$stmt->bindValue(":path","1",PDO::PARAM_STR);
	$stmt->bindValue(":name","未設定",PDO::PARAM_STR);
	$stmt->bindValue(":type",1,PDO::PARAM_INT);

	print($stmt->execute());
*/
?>
<script src="jquery.min.js"></script>
path:<input type="text" id="path"><br>
name:<input type="text" id="name"><br>
type:<input type="text" id="type"><br>
<button id="send_button">OK</button>
<table border="1" id="file_table"></table>
<script>
	$(document).ready(function() {
		makeTable();

		$("#send_button").on("click",function() {
			var _path = $("#path").val();
			var _name = $("#name").val();
			var _type = $("#type").val();
			$.ajax({
				type:"POST",
				url:"ajax_add.php",
				dateType:"json",
				data:{
					"path":_path,
					"name":_name,
					"type":_type
				},
				success: function(data,dataType) {
					console.log(data,dataType);
					makeTable();
				},
				error: function(e) {
					console.log(e);
				}
			});
		});
	});

	function makeTable() {
		$.ajax({
			type:"POST",
			url:"ajax_get.php",
			dateType:"json",
			data:{},
			success: function(data,dataType) {
				data = JSON.parse(data);
				console.log(data,dataType);
				var _table = $("#file_table");
				_table.html("");
				var _thead = $("<tr>");
				_thead.append($("<td>").html("path"));
				_thead.append($("<td>").html("name"));
				_thead.append($("<td>").html("type"));
				_table.append(_thead);
				for(var row of data) {
					var _tr = $("<tr>");
					_tr.append($("<td>").html(row.path));
					_tr.append($("<td>").html(row.name));
					_tr.append($("<td>").html(row.type));
					_table.append(_tr);
				}
			},
			error: function(e) {
				console.log(e);
			}
		});
	}
</script>
