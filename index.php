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
<button id="send_button">INSERT</button>
<div id="node_area"></div>
<div id="tree_area"></div>
<table border="1" id="file_table"></table>
<style>
	.file_over{
		user-select: none;
	}
	.file_over:hover{
		background-color: #eee;
		border-radius: 5px;
	}
</style>
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
				console.log(data);
				data = JSON.parse(data);
				data.sort(function(a,b) {
					return a.path>b.path;
				});
				var filesystem = {};
				for(var row of data) {
					filesystem[row.path] = row;
				}
				var tree_area = $("#tree_area");
				var files = {};
				files["children"] = {};
				for(var row of data) {
					var key = row.path;
					var filename = filesystem[key].name;
					var path = filesystem[key].path;
					var index = -1;
					var indent = 0;
					var parent = files;
					while((index = path.indexOf("g"))>=0) {
						parent_path = path.substring(0,index);
						path = path.substring(index+1);
						parent = parent.children[parent_path];
						indent++;
					}
					row["children"] = {};
					parent.children[path] = row;
				}
				var stack = {};
				for(var file in files.children) {
					stack[file] = files.children[file];
				}
				var saiki = function(parent,list,indent) {
					for(var i in list) {
						var img_src = "";
						var file_type = "";
						if(list[i].type==1) {
							img_src="file.png";
							file_type="file";
						}else {
							img_src="folder.png";
							file_type="folder";
						}
						var div = $(`<div>`);
						var span = $(`<span style="margin-left:${indent*16}" class="file_over">`);
						span.append(`<img src="${img_src}">`);
						span.append($(`<span>`).html(list[i].name));
						saiki(div,list[i].children,indent+1);
						span.on("click",function() {
							$(this).next().toggle()
						});
						parent.append($(`<div class="${file_type}" data-path="${list[i].path}">`).append(span).append(div));
					}
				};
				saiki($("#node_area"),stack,0);

				$(".folder").on("click",function(e) {
					$("#path").val($(this).data("path"));
					return false;
				});

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
