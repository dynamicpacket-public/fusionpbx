<?php
require_once "root.php";
require_once "includes/config.php";

if (count($_GET)>0) {
	$id = check_str($_GET["id"]);
	$contact_id = check_str($_GET["contact_id"]);
}

if (strlen($id)>0) {
	$sql = "";
	$sql .= "delete from v_contact_notes ";
	$sql .= "where contacts_note_id = '$id' ";
	$prep_statement = $db->prepare(check_sql($sql));
	$prep_statement->execute();
	unset($sql);
}

require_once "includes/header.php";
echo "<meta http-equiv=\"refresh\" content=\"2;url=v_contacts_edit.php?id=$contact_id\">\n";
echo "<div align='center'>\n";
echo "Delete Complete\n";
echo "</div>\n";

require_once "includes/footer.php";
return;

?>

