<div align='center'>
<table width='100%' border='0' cellpadding='0' cellspacing='0'>
<tr>
	<th>Profile</th>
	<th>Context</th>
        <th>State</th>
	<th>Enabled</th>
	<th>Description</th>
<td align='right' width='42'>
  <a href='v_profile_edit.php' alt='add'><?php echo $v_link_label_add; ?></a>
</td>
</tr>
<?php
foreach($profiles_array as $profile){
?>
<tr>
	<td><?php echo $profile['profile_name']; ?></td>
	<td><?php echo $profile['context']; ?></td>
        <td><?php echo $profile['status']; ?></td>
	<td><?php echo $profile['enabled']; ?></td>
	<td><?php echo $profile['description']; ?></td>
<td align='right' width='42'>
	<a href='v_profile_edit.php?id=<?php echo $profile['xmpp_profile_id']; ?>' alt='edit'><?php echo $v_link_label_edit; ?></a>
	<a href='v_profile_delete.php?id=<?php echo $profile['xmpp_profile_id']; ?>' onclick="return confirm('Do you really want to delete this?')" 
		alt='delete'><?php echo $v_link_label_delete; ?></a>
</td>
</tr>
<?php 
}
?>
</table>
</div>
