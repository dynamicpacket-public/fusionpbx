<?php
/*
	FusionPBX
	Version: MPL 1.1

	The contents of this file are subject to the Mozilla Public License Version
	1.1 (the "License"); you may not use this file except in compliance with
	the License. You may obtain a copy of the License at
	http://www.mozilla.org/MPL/

	Software distributed under the License is distributed on an "AS IS" basis,
	WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
	for the specific language governing rights and limitations under the
	License.

	The Original Code is FusionPBX

	The Initial Developer of the Original Code is
	Mark J Crane <markjcrane@fusionpbx.com>
	Portions created by the Initial Developer are Copyright (C) 2008-2010
	the Initial Developer. All Rights Reserved.

	Contributor(s):
	Mark J Crane <markjcrane@fusionpbx.com>
*/
require_once "root.php";
require_once "includes/config.php";
require_once "includes/checkauth.php";
if (permission_exists('virtual_tables_data_view')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

if (strlen($_GET["id"]) > 0) {
	$virtual_table_id = check_str($_GET["id"]);
	if (strlen($_GET["virtual_data_parent_row_id"])>0) {
		$virtual_data_parent_row_id = $_GET["virtual_data_parent_row_id"];
	}
	$search_all = check_str($_GET["search_all"]);
}

//used for changing the order
	$orderby = $_GET["orderby"];
	$order = $_GET["order"];    

//used to alternate colors when paging 
	$c = 0;
	$rowstyle["0"] = "rowstyle0";
	$rowstyle["1"] = "rowstyle1";

//show the header
	require_once "includes/header.php";

//get the information about the virtual table by using the id
	$sql = "";
	$sql .= "select * from v_virtual_tables ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and virtual_table_id = '$virtual_table_id' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll(PDO::FETCH_ASSOC);
	foreach ($result as &$row) {
		$virtual_table_category = $row["virtual_table_category"];
		$virtual_table_label = $row["virtual_table_label"];
		$virtual_table_name = $row["virtual_table_name"];
		$virtual_table_auth = $row["virtual_table_auth"];
		$virtual_table_captcha = $row["virtual_table_captcha"];
		$virtual_table_parent_id = $row["virtual_table_parent_id"];
		$virtual_table_desc = $row["virtual_table_desc"];
		break; //limit to 1 row
	}
	unset ($prepstatement);

//get the field information
	$db_field_name_array = array();
	$db_value_array = array();
	$db_names .= "<tr>\n";
	$sql = "select * from v_virtual_table_fields ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and virtual_table_id = '$virtual_table_id' ";
	$sql .= "order by virtual_field_order asc ";
	$prepstatement = $db->prepare($sql);
	$prepstatement->execute();
	$result_names = $prepstatement->fetchAll(PDO::FETCH_ASSOC);
	$resultcount = count($result);
	foreach($result_names as $row) {
		$virtual_field_label = $row["virtual_field_label"];
		$virtual_field_name = $row["virtual_field_name"];
		$virtual_field_type = $row["virtual_field_type"];
		$virtual_field_value = $row["virtual_field_value"];
		$virtual_field_list_hidden = $row["virtual_field_list_hidden"];
		$virtual_field_column = $row["virtual_field_column"];
		$virtual_field_required = $row["virtual_field_required"];
		$virtual_field_order = $row["virtual_field_order"];
		$virtual_field_order_tab = $row["virtual_field_order_tab"];
		$virtual_field_desc = $row["virtual_field_desc"];

		$name_array[$virtual_field_name]['virtual_field_label'] = $row["virtual_field_label"];
		$name_array[$virtual_field_name]['virtual_field_type'] = $row["virtual_field_type"];
		$name_array[$virtual_field_name]['virtual_field_list_hidden'] = $row["virtual_field_list_hidden"];
		$name_array[$virtual_field_name]['virtual_field_column'] = $row["virtual_field_column"];
		$name_array[$virtual_field_name]['virtual_field_required'] = $row["virtual_field_required"];
		$name_array[$virtual_field_name]['virtual_field_order'] = $row["virtual_field_order"];
		$name_array[$virtual_field_name]['virtual_field_order_tab'] = $row["virtual_field_order_tab"];
		$name_array[$virtual_field_name]['virtual_field_desc'] = $row["virtual_field_desc"];
	}
	unset($sql, $prepstatement, $row);
	$fieldcount = count($name_array);

//get the data
	$sql = "";
	$sql .= "select * from v_virtual_table_data ";
	$sql .= "where v_id = '".$v_id."' ";
	if (strlen($search_all) == 0) {
		$sql .= "and virtual_table_id = '$virtual_table_id' ";
		if (strlen($virtual_data_parent_row_id) > 0) {
			$sql .= " and virtual_data_parent_row_id = '$virtual_data_parent_row_id' ";
		}
	}
	else {
		$sql .= "and virtual_data_row_id in (";
		$sql .= "select virtual_data_row_id from v_virtual_table_data \n";
		$sql .= "where v_id = '".$v_id."' ";
		$sql .= "and virtual_table_id = '$virtual_table_id' ";
		if (strlen($virtual_data_parent_row_id) == 0) {
			$tmp_digits = preg_replace('{\D}', '', $search_all);
			if (is_numeric($tmp_digits) && strlen($tmp_digits) > 5) {
				if (strlen($tmp_digits) == '11' ) {
					$sql .= "and virtual_data_field_value like '%".substr($tmp_digits, -10)."%' \n";
				}
				else {
					$sql .= "and virtual_data_field_value like '%$tmp_digits%' \n";
				}
			}
			else {
				$sql .= "and virtual_data_field_value like '%$search_all%' \n";
			}
		}
		else {
			$sql .= "and virtual_data_parent_row_id = '$virtual_data_parent_row_id' ";
		}
		$sql .= ")\n";
	}
	$prepstatement = $db->prepare($sql);
	$prepstatement->execute();
	$result_values = $prepstatement->fetchAll(PDO::FETCH_ASSOC);
	foreach($result_values as $row) {

		//set a php variable
			$virtual_field_name = $row[virtual_field_name];
			$virtual_data_row_id = $row[virtual_data_row_id];

		//restructure the data by setting it the value_array
			$value_array[$virtual_data_row_id][$virtual_field_name] = $row[virtual_data_field_value];
			$value_array[$virtual_data_row_id]['virtual_table_id'] = $row[virtual_table_id];
			$value_array[$virtual_data_row_id]['virtual_data_row_id'] = $row[virtual_data_row_id];
			$value_array[$virtual_data_row_id]['virtual_table_parent_id'] = $row[virtual_table_parent_id];
			$value_array[$virtual_data_row_id]['virtual_data_parent_row_id'] = $row[virtual_data_parent_row_id];
	}
	$num_rows = count($value_array);

//create the connection to the memory dbase_add_record
	try {
		$db_memory = new PDO('sqlite::memory:'); //sqlite 3
	}
	catch (PDOException $error) {
		print "error: " . $error->getMessage() . "<br/>";
		die();
	}

//create a memory database and add the fields to the table
	$sql = "CREATE TABLE memory_table ";
	$sql .= "(";
	$sql .= "'id' INTEGER PRIMARY KEY, ";
	$sql .= "'virtual_table_id' NUMERIC, ";
	$sql .= "'virtual_data_row_id' NUMERIC, ";
	$sql .= "'virtual_table_parent_id' NUMERIC, ";
	$sql .= "'virtual_data_parent_row_id' NUMERIC, ";
	foreach($result_names as $row) {
		if ($row["virtual_field_type"] != "label") {
			if ($row["virtual_field_name"] != "v_id") {
				//$row["virtual_field_label"];
				//$row["virtual_field_name"]
				//$row["virtual_field_type"];
				if ($row["virtual_field_name"] == "number") {
					$sql .= "'".$row["virtual_field_name"]."' NUMERIC, ";
				}
				else {
					$sql .= "'".$row["virtual_field_name"]."' TEXT, ";
				}
			}
		}
	}
	$sql .= "'v_id' NUMERIC ";
	$sql .= ");";
	//echo "$sql<br /><br />\n";
	$prepstatement = $db_memory->prepare($sql);
	$prepstatement->execute();
	unset ($prepstatement, $sql);

//list the values from the array
	foreach($value_array as $array) {
		//insert the data into the memory table
			$sql = "insert into memory_table ";
			$sql .= "(";
			$sql .= "'virtual_table_id', ";
			$sql .= "'virtual_data_row_id', ";
			$sql .= "'virtual_table_parent_id', ";
			$sql .= "'virtual_data_parent_row_id', ";
			//foreach($array as $key => $value) {
			//	$sql .= "'$key', ";
			foreach($result_names as $row) {
				$virtual_field_name = $row["virtual_field_name"];
				$sql .= "'$virtual_field_name', ";
			}
			$sql .= "'v_id' ";
			$sql .= ")";
			$sql .= "values ";
			$sql .= "(";
			$sql .= "'".$array['virtual_table_id']."', ";
			$sql .= "'".$array['virtual_data_row_id']."', ";
			$sql .= "'".$array['virtual_table_parent_id']."', ";
			$sql .= "'".$array['virtual_data_parent_row_id']."', ";
			//foreach($array as $key => $value) {
			//	$sql .= "'$value', ";
			foreach($result_names as $row) {
				$virtual_field_name = $row["virtual_field_name"];
				$sql .= "'".check_str($array[$virtual_field_name])."', ";
			}
			$sql .= "'$v_id' ";
			$sql .= ");";
			//echo "$sql <br /><br />\n";
			$db_memory->exec(check_sql($sql));
			unset($sql);
	}

//set the title and description of the virtual table
	echo "<br />\n";
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"6\" cellspacing=\"0\">\n";
	echo "  <tr>\n";
	echo "	<td align='left' valign='top'><strong>$virtual_table_label</strong><br>\n";
	echo "		$virtual_table_desc\n";
	echo "	</td>\n";
	echo "	<td align='right' valign='top'>\n";
	if (strlen($virtual_data_parent_row_id) == 0) {
		$search_all = str_replace("''", "'", $search_all);
		echo "<form method='GET' name='frm_search' action=''>\n";
		echo "	<input class='formfld' type='text' name='search_all' value=\"$search_all\">\n";
		echo "	<input type='hidden' name='id' value='$virtual_table_id'>\n";
		echo "	<input type='hidden' name='virtual_data_parent_row_id' value='$virtual_data_parent_row_id'>\n";
		echo "	<input class='btn' type='submit' name='submit' value='Search All'>\n";
		echo "</form>\n";
	}
	echo "	</td>\n";
	echo "  </tr>\n";
	echo "</table>\n";
	echo "<br />";

//prepare for paging the results
	require_once "includes/paging.php";
	$rows_per_page = 100;
	$param = "";
	$page = $_GET['page'];
	if (strlen($page) == 0) { $page = 0; $_GET['page'] = 0; }
	if (strlen($virtual_table_parent_id) > 0) {
		$param = "&id=$virtual_table_parent_id&virtual_data_row_id=$virtual_data_row_id";
	}
	else {
		$param = "&id=$virtual_table_id&virtual_data_row_id=$virtual_data_row_id";
	}
	list($paging_controls, $rows_per_page, $var3) = paging($num_rows, $param, $rows_per_page); 
	$offset = $rows_per_page * $page;

//list the data in the database
	$sql = "";
	$sql .= "select * from memory_table \n";
	$sql .= "where v_id = '$v_id' \n";
	$sql .= "limit $rows_per_page offset $offset \n";
	//$sql .= "order by virtual_field_order asc \n";
	//echo "<pre>\n";
	//echo $sql;
	//echo "</pre>\n";
	$prepstatement = $db_memory->prepare($sql);
	$prepstatement->execute();
	$result = $prepstatement->fetchAll(PDO::FETCH_ASSOC);

//begin the list
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";

	echo "<tr>\n";
	foreach($result_names as $row) {
		if ($row['virtual_field_list_hidden'] != "hide") {
			echo "<th valign='top' nowrap>&nbsp; ".$row['virtual_field_label']." &nbsp;</th>\n";
		}
	}
	echo "<td align='right' width='42'>\n";
	if (permission_exists('virtual_tables_data_add')) {
		echo "	<a href='v_virtual_table_data_edit.php?virtual_table_id=".$virtual_table_id."&virtual_data_parent_row_id=$virtual_data_parent_row_id' alt='add'>$v_link_label_add</a>\n";
	}
	echo "</td>\n";
	echo "</tr>\n";

	$db_values = '';
	$x = 0;
	foreach ($result as &$row) {
		echo "<tr>\n";
		foreach($result_names as $row2) {
			$virtual_field_name = $row2[virtual_field_name];

			//get the values from the array and set as php variables
				$virtual_field_label = $name_array[$virtual_field_name]['virtual_field_label'];
				$virtual_field_type = $name_array[$virtual_field_name]['virtual_field_type'];
				$virtual_field_list_hidden = $name_array[$virtual_field_name]['virtual_field_list_hidden'];
				$virtual_field_column = $name_array[$virtual_field_name]['virtual_field_column'];
				$virtual_field_required = $name_array[$virtual_field_name]['virtual_field_required'];
				$virtual_field_order = $name_array[$virtual_field_name]['virtual_field_order'];
				$virtual_field_order_tab = $name_array[$virtual_field_name]['virtual_field_order_tab'];
				$virtual_field_desc = $name_array[$virtual_field_name]['virtual_field_desc'];

			if ($virtual_field_list_hidden != "hide") {
				switch ($virtual_field_type) {
					case "textarea":
						$tmp_value = str_replace("\n", "<br />\n", $row[$virtual_field_name]);
						echo "<td valign='top' class='".$rowstyle[$c]."'>".$tmp_value."&nbsp;</td>\n";
						unset($tmp_value);
						break;
					case "email":
						echo "<td valign='top' class='".$rowstyle[$c]."'><a href='mailto:".$row[$virtual_field_name]."'>".$row[$virtual_field_name]."</a>&nbsp;</td>\n";
						break;
					case "phone":
						$tmp_phone = $row[$virtual_field_name];
						$tmp_phone = format_phone($tmp_phone);
						echo "<td valign='top' class='".$rowstyle[$c]."'>".$tmp_phone."&nbsp;</td>\n";
						break;
					case "url":
						$url = $row[$virtual_field_name];
						if (substr($url,0,4) != "http") {
							$url = 'http://'.$url;
						}
						echo "<td valign='top' class='".$rowstyle[$c]."'><a href='".$url."' target='_blank'>".$row[$virtual_field_name]."</a>&nbsp;</td>\n";
						break;
					default:
						echo "<td valign='top' class='".$rowstyle[$c]."'>".$row[$virtual_field_name]."&nbsp;</td>\n";
						break;
				}
			}
		}

		echo "<td valign='top' align='right' nowrap='nowrap'>\n";
		if (permission_exists('virtual_tables_data_edit')) {
			if (strlen($virtual_data_parent_row_id) == 0) {
				echo "	<a href='v_virtual_table_data_edit.php?virtual_table_id=".$row[virtual_table_id]."&virtual_data_parent_row_id=$virtual_data_parent_row_id&virtual_data_row_id=".$row['virtual_data_row_id']."&search_all=$search_all' alt='edit'>$v_link_label_edit</a>\n";
			}
			else {
				echo "	<a href='v_virtual_table_data_edit.php?virtual_table_id=".$row[virtual_table_id]."&virtual_data_parent_row_id=$virtual_data_parent_row_id&virtual_data_row_id=".$row['virtual_data_row_id']."' alt='edit'>$v_link_label_edit</a>\n";
			}
		}
		if (permission_exists('virtual_tables_data_delete')) {
			echo"	<a href='v_virtual_table_data_delete.php?virtual_data_row_id=".$row['virtual_data_row_id']."&virtual_data_parent_row_id=$virtual_data_parent_row_id&virtual_table_id=".$virtual_table_id."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
		}
		echo "</td>\n";

		echo "</tr>\n";
		if ($c==0) { $c=1; } else { $c=0; }
	}

//show the paging tools and final add button
	echo "<tr>\n";
	echo "<td colspan='999' align='left'>\n";
	echo "	<table border='0' width='100%' cellpadding='0' cellspacing='0'>\n";
	echo "	<tr>\n";
	echo "		<td width='33.3%' nowrap>&nbsp;</td>\n";
	echo "		<td width='33.3%' align='center' nowrap>$paging_controls</td>\n";
	echo "		<td width='33.3%' align='right'>\n";
	if (permission_exists('virtual_tables_data_add')) {
		echo "			<a href='v_virtual_table_data_edit.php?virtual_table_id=".$virtual_table_id."&virtual_data_parent_row_id=$virtual_data_parent_row_id' alt='add'>$v_link_label_add</a>\n";
	}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	</table>\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "</table>\n";
	echo "<br><br>\n";
    echo "</div>";

//show the header
    echo "<br><br>";
    require_once "includes/footer.php";

?>
