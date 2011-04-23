<?php
function sql_tables() {
	$x = 0;

	include "v_config.php";
	$sql = "";

	foreach ($apps[$x]['db'] as $new_db) {
		$sql .= "CREATE TABLE " . $new_db['table'] . " (\n";
		$fcount = 0;
		foreach ($new_db['fields'] as $field) {
			if ($fcount > 0 ) { $sql .= ",\n"; }
			$sql .= $field['name'] . " " . $field['type'];
			$fcount++;
		}
		$sql .= ");\n\n";
	}

	return $sql;
}
?>
