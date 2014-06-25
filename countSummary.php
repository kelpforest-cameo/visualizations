<?php
// load in mysql server configuration (connection string, user/pw, etc)
	include '../db/connect.php';
// connect to the database
	$con=mysql_connect("$DB_host", "$DB_user", "$DB_pass")or die("cannot connect");
	mysql_select_db("$DB_dbName") or die("cannot select DB");
	
//count number of lines in each table
	$query = "select * from users"; 
	$result = mysql_query($query);
	$num_users = mysql_num_rows($result);

	$query = "select * from nodes";
	$result = mysql_query($query);
	$num_nodes = mysql_num_rows($result);

	$query = "select * from citations";
	$result = mysql_query($query);
	$num_cites = mysql_num_rows($result);

	$query = "select * from competition_interactions";
	$result = mysql_query($query);
	$num_compet_int = mysql_num_rows($result);
	
	$query = "select * from competition_interaction_observation";
	$result = mysql_query($query);
	$num_compet_obs = mysql_num_rows($result);

	$query = "select * from trophic_interactions";
	$result = mysql_query($query);
	$num_trophic_int = mysql_num_rows($result);
	
	$query = "select * from trophic_interaction_observation";
	$result = mysql_query($query);
	$num_trophic_obs = mysql_num_rows($result);

	$query = "select * from parasitic_interactions";
	$result = mysql_query($query);
	$num_parasit_int = mysql_num_rows($result);
	
	$query = "select * from parasitic_interaction_observation";
	$result = mysql_query($query);
	$num_parasit_obs = mysql_num_rows($result);

	$query = "select * from facilitation_interactions";
	$result = mysql_query($query);
	$num_facilit_int = mysql_num_rows($result);
	
	$query = "select * from facilitation_interaction_observation";
	$result = mysql_query($query);
	$num_facilit_obs = mysql_num_rows($result);



	mysql_close();
	
	echo "The kelpforest database has:<br />\n
		$num_users registered users. <br />\n 
		<br />\t It currently contains: <br />\n
		$num_cites citations  <br />\n 
		$num_nodes nodes  <br />\n
		
		--- $num_trophic_obs observations of $num_trophic_int trophic interactions <br />\n 
		--- $num_parasit_obs observations of $num_parasit_int parasitic interactions <br />\n 
		--- $num_facilit_obs observations of $num_facilit_int facilitative interactions <br />\n 
		--- $num_compet_obs observations of $num_compet_int competetive interactions";
?>
