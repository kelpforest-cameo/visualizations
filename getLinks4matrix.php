<?php
	// load in mysql server configuration (connection string, user/pw, etc)
	include '../db/connect.php';
	// connect to the database
	$con=mysql_connect("$DB_host", "$DB_user", "$DB_pass")or die("cannot connect");
	mysql_select_db("$DB_dbName") or die("cannot select DB");

	$query1 ="
SELECT id, working_name as name, functional_group_id as group_id
FROM nodes";

	$query2 = "
SELECT  distinct nodes1.id AS source, nodes2.id AS target, 2 as value
	FROM trophic_interactions AS links
	LEFT JOIN (stages AS stages1,nodes AS nodes1) ON (stages1.id=links.stage_1_id AND nodes1.id=stages1.node_id)
	LEFT JOIN (stages AS stages2,nodes AS nodes2) ON (stages2.id=links.stage_2_id AND nodes2.id=stages2.node_id)
UNION
SELECT distinct nodes1.id AS source, nodes2.id AS target, 2 as value	
FROM facilitation_interactions AS links
	LEFT JOIN (stages AS stages1,nodes AS nodes1) ON (stages1.id=links.stage_1_id AND nodes1.id=stages1.node_id)
	LEFT JOIN (stages AS stages2,nodes AS nodes2) ON (stages2.id=links.stage_2_id AND nodes2.id=stages2.node_id)
UNION
SELECT distinct nodes1.id AS source, nodes2.id AS target, 2 as value
FROM parasitic_interactions AS links
	LEFT JOIN (stages AS stages1,nodes AS nodes1) ON (stages1.id=links.stage_1_id AND nodes1.id=stages1.node_id)
	LEFT JOIN (stages AS stages2,nodes AS nodes2) ON (stages2.id=links.stage_2_id AND nodes2.id=stages2.node_id)
UNION
SELECT distinct nodes1.id AS source, nodes2.id AS target, 2 as value
FROM competition_interactions AS links
	LEFT JOIN (stages AS stages1,nodes AS nodes1) ON (stages1.id=links.stage_1_id AND nodes1.id=stages1.node_id)
	LEFT JOIN (stages AS stages2,nodes AS nodes2) ON (stages2.id=links.stage_2_id AND nodes2.id=stages2.node_id)";
	
	$result1 = mysql_query($query1);
	$result2 = mysql_query($query2);

	$out = array();

	if(mysql_num_rows($result1)){
		while($row=mysql_fetch_row($result1)){
			$name=$row[1];
			$group=$row[2]; 
			$out['nodes'][] = array(name => $name, group=> $group);
		}
	}
	if(mysql_num_rows($result2)){
		while($row=mysql_fetch_row($result2)){
			$source=$row[0];
			$target=$row[1]; 
			$value=$row[2];
			$out['links'][]  = array(source => $source, target=> $target, value=> $value);
		}
	}

mysql_close();
	
echo json_encode($out);
?>
