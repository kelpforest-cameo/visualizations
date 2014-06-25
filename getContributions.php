<?php
	// load in mysql server configuration (connection string, user/pw, etc)
	include '../db/connect.php';
	// connect to the database
	$con=mysql_connect("$DB_host", "$DB_user", "$DB_pass")or die("cannot connect");
	mysql_select_db("$DB_dbName") or die("cannot select DB");
	
	$query = "
SELECT owner_id, entries, concat_ws(' ',first_name, last_name, info) AS name 
FROM (SELECT owner_id, count(*) AS entries 
FROM (
SELECT owner_id FROM facilitation_interactions 
UNION ALL SELECT owner_id FROM trophic_interactions 
UNION ALL SELECT owner_id FROM competition_interactions 
UNION ALL SELECT owner_id FROM parasitic_interactions 
UNION ALL SELECT owner_id FROM facilitation_interaction_observation 
UNION ALL SELECT owner_id FROM trophic_interaction_observation 
UNION ALL SELECT owner_id FROM competition_interaction_observation 
UNION ALL SELECT owner_id FROM parasitic_interaction_observation
UNION ALL SELECT owner_id FROM non_itis
UNION ALL SELECT owner_id FROM nodes
UNION ALL SELECT owner_id FROM node_max_age
UNION ALL SELECT owner_id FROM node_range
UNION ALL SELECT owner_id FROM stage_biomass_change
UNION ALL SELECT owner_id FROM stage_biomass_density
UNION ALL SELECT owner_id FROM stage_consumer_strategy
UNION ALL SELECT owner_id FROM stage_consum_biomass_ratio
UNION ALL SELECT owner_id FROM stage_drymass
UNION ALL SELECT owner_id FROM stage_duration
UNION ALL SELECT owner_id FROM stage_fecundity
UNION ALL SELECT owner_id FROM stage_habitat
UNION ALL SELECT owner_id FROM stage_length
UNION ALL SELECT owner_id FROM stage_length_fecundity
UNION ALL SELECT owner_id FROM stage_length_weight
UNION ALL SELECT owner_id FROM stage_lifestyle
UNION ALL SELECT owner_id FROM stage_mass
UNION ALL SELECT owner_id FROM stage_max_depth
UNION ALL SELECT owner_id FROM stage_mobility
UNION ALL SELECT owner_id FROM stage_population
UNION ALL SELECT owner_id FROM stage_prod_biomass_ratio
UNION ALL SELECT owner_id FROM stage_prod_consum_ratio
UNION ALL SELECT owner_id FROM stage_reproductive_strategy
UNION ALL SELECT owner_id FROM stage_residency
UNION ALL SELECT owner_id FROM stage_residency_time
UNION ALL SELECT owner_id FROM stage_unassimilated_consum_ratio
) v 
group by owner_id) AS T1 , users 
where (T1.owner_id=users.id)
	";
	
	$result = mysql_query($query);

	$return = array();
	$out = array();
	if(mysql_num_rows($result)){
		while($row=mysql_fetch_row($result)){
			$name=$row[2]; 
			$entries=$row[1]; 
			$out[] = array(name => $name, value=> $entries);
		}
	}
	mysql_close();
	echo json_encode($out);
?>
