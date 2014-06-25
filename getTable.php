<?php
	// load in mysql server configuration (connection string, user/pw, etc)
	include '../db/connect.php';
	// connect to the database
	
	$con=mysql_connect("$DB_host", "$DB_user", "$DB_pass")or die("cannot connect");
	mysql_select_db("$DB_dbName") or die("cannot select DB");
	$sql = "select * from nodes";
	$result = mysql_query($sql);
	
	
	$json = array();
	if(mysql_num_rows($result)){
		while($row=mysql_fetch_row($result)){
			$json['emp_info'][]=$row;
		}
	}
	mysql_close();
	echo json_encode($json);
?>
