<?php
	// load in mysql server configuration (connection string, user/pw, etc)
	include '../db/connect.php';
	// connect to the database
	$con=mysql_connect("$DB_host", "$DB_user", "$DB_pass")or die("cannot connect");
	mysql_select_db("$DB_dbName") or die("cannot select DB");
	
	// trophic
	$query1 = "
	SELECT  nodes1.working_name AS node_1_working_name, nodes2.working_name AS node_2_working_name
	FROM trophic_interactions AS links
	LEFT JOIN (stages AS stages1,nodes AS nodes1) ON (stages1.id=links.stage_1_id AND nodes1.id=stages1.node_id)
	LEFT JOIN (stages AS stages2,nodes AS nodes2) ON (stages2.id=links.stage_2_id AND nodes2.id=stages2.node_id)";
	// facilitation
	$query2 = "
	SELECT  nodes1.working_name AS node_1_working_name, nodes2.working_name AS node_2_working_name
	FROM facilitation_interactions AS links
	LEFT JOIN (stages AS stages1,nodes AS nodes1) ON (stages1.id=links.stage_1_id AND nodes1.id=stages1.node_id)
	LEFT JOIN (stages AS stages2,nodes AS nodes2) ON (stages2.id=links.stage_2_id AND nodes2.id=stages2.node_id)";
	// parasitic
	$query3 = "
	SELECT  nodes1.working_name AS node_1_working_name, nodes2.working_name AS node_2_working_name
	FROM parasitic_interactions AS links
	LEFT JOIN (stages AS stages1,nodes AS nodes1) ON (stages1.id=links.stage_1_id AND nodes1.id=stages1.node_id)
	LEFT JOIN (stages AS stages2,nodes AS nodes2) ON (stages2.id=links.stage_2_id AND nodes2.id=stages2.node_id)";
	// competition
	$query4 = "
	SELECT  nodes1.working_name AS node_1_working_name, nodes2.working_name AS node_2_working_name
	FROM competition_interactions AS links
	LEFT JOIN (stages AS stages1,nodes AS nodes1) ON (stages1.id=links.stage_1_id AND nodes1.id=stages1.node_id)
	LEFT JOIN (stages AS stages2,nodes AS nodes2) ON (stages2.id=links.stage_2_id AND nodes2.id=stages2.node_id)";
	
	$result1 = mysql_query($query1);
	$result2 = mysql_query($query2);
	$result3 = mysql_query($query3);
	$result4 = mysql_query($query4);
	
	$links1 = array();
	$links2 = array();
	$links3 = array();
	$links4 = array();
	
	if(mysql_num_rows($result1)){
		while($row=mysql_fetch_row($result1)){
			$source=$row[1]; // note switched direction
			$target=$row[0]; 
			$links1[] = array(source => $source, target=> $target, type=> 'trophic');
		}
	}

	if(mysql_num_rows($result2)){
		while($row=mysql_fetch_row($result2)){
		$source=$row[0]; 
		$target=$row[1]; 
		$links2[] = array(source => $source, target=> $target, type=> 'facilitation');
		}
	}

	if(mysql_num_rows($result3)){
		while($row=mysql_fetch_row($result3)){
		$source=$row[1]; // note switched direction
		$target=$row[0]; 
		$links3[] = array(source => $source, target=> $target, type=> 'parasitic');
		}
	}
	if(mysql_num_rows($result4)){
		while($row=mysql_fetch_row($result4)){
		$source=$row[0]; 
		$target=$row[1]; 
		$links4[] = array(source => $source, target=> $target, type=> 'competition');
		}
	}

	mysql_close();
	$out = array_merge($links1,$links2,$links3,$links4);
	echo json_encode($out);
?>
