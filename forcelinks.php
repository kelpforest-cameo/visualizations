<?php
include("../new/connect.php");

$onlylinked = false;
if (count($_GET) && $_GET['l'] == 1) {
	$onlylinked = true;
}
$node_sql = '';


if (!$onlylinked) {
	$node_sql = "select n.working_name as 'name', n.functional_group_id as 'group', g.name as 'groupname'\n"
			. "from nodes as n\n"
			. "left join functional_groups as g on (g.id = n.functional_group_id)\n"
			. "order by groupname asc";
} else {
	$node_sql = "SELECT DISTINCT n.working_name AS 'name', n.functional_group_id AS 'group', g.name AS 'groupname'\n"
			. "FROM stages AS s, nodes AS n\n"
			. "LEFT JOIN functional_groups AS g ON g.id = n.functional_group_id\n"
			. "WHERE (n.id = s.node_id) AND (\n"
			. "(EXISTS (SELECT * FROM trophic_interactions AS tix where tix.stage_1_id = s.id)) \n"
			. "OR (EXISTS (SELECT * FROM trophic_interactions AS tix2 where tix2.stage_2_id = s.id))\n"
			. ")\n"
			. "ORDER BY groupname ASC";
}
//--------------------------------------------------
// $node_sql = "SELECT DISTINCT n.working_name AS 'name', n.functional_group_id AS 'group'\n"
//     . "FROM stages AS s, nodes AS n\n"
//     . "WHERE (n.id = s.node_id) AND (\n"
//     . "(EXISTS (SELECT * FROM trophic_interactions AS tix where tix.stage_1_id = s.id)) \n"
//     . "OR (EXISTS (SELECT * FROM trophic_interactions AS tix2 where tix2.stage_2_id = s.id))\n"
//     . ")";
//-------------------------------------------------- 

$interaction_types = array('trophic','facilitation','competition','parasitic');
$interaction_type = 'trophic';
$trophic_link_sql = "
SELECT source_node.working_name AS 'source', target_node.working_name AS 'target', count(DISTINCT obs.cite_id) as 'value'
FROM {$interaction_type}_interactions AS links, {$interaction_type}_interaction_observation AS obs, nodes AS source_node, stages AS source_stage, nodes AS target_node, stages AS target_stage
WHERE 
	(obs.{$interaction_type}_interaction_id = links.id) AND
	(source_stage.id = links.stage_1_id AND source_node.id = source_stage.node_id) AND
	(target_stage.id = links.stage_2_id AND target_node.id = target_stage.node_id)
GROUP BY obs.{$interaction_type}_interaction_id
";

$nodes = $db->getAll($node_sql);
$dblinks = $db->getAll($trophic_link_sql);
$nodemap = array();
$links = array();
foreach ($nodes as $i => $node)
{
	$nodemap[$node['name']] = $i;
}
foreach ($dblinks as $link)
{
	$l = array('source' => $nodemap[$link['source']], 'target' => $nodemap[$link['target']], 'value' => $link['value']);
	$links[] = $l;

}
$json = array('nodes' => $nodes, 'links' => $links);
print json_encode($json);
?>
