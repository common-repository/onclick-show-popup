<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_OnclickShowPopup_display']) && $_POST['frm_OnclickShowPopup_display'] == 'yes')
{
	$did = isset($_GET['did']) ? sanitize_text_field($_GET['did']) : '0';
	if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }
	
	$OnclickShowPopup_success = '';
	$OnclickShowPopup_success_msg = FALSE;
	
	// First check if ID exist with requested ID
	$sSql = $wpdb->prepare(
		"SELECT COUNT(*) AS `count` FROM ".WP_OnclickShowPopup_TABLE."
		WHERE `OnclickShowPopup_id` = %d",
		array($did)
	);
	$result = '0';
	$result = $wpdb->get_var($sSql);
	
	if ($result != '1')
	{
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist', 'onclick-show-popup'); ?></strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('OnclickShowPopup_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".WP_OnclickShowPopup_TABLE."`
					WHERE `OnclickShowPopup_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$OnclickShowPopup_success_msg = TRUE;
			$OnclickShowPopup_success = __('Selected record was successfully deleted.', 'onclick-show-popup');
		}
	}
	
	if ($OnclickShowPopup_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $OnclickShowPopup_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php _e('Onclick show popup', 'onclick-show-popup'); ?>
	<a class="add-new-h2" href="<?php echo WP_OnclickShowPopup_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'onclick-show-popup'); ?></a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".WP_OnclickShowPopup_TABLE."` order by OnclickShowPopup_id desc";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<form name="frm_OnclickShowPopup_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
			<th scope="col"><?php _e('Popup Title', 'onclick-show-popup'); ?></th>
            <th scope="col"><?php _e('Group', 'onclick-show-popup'); ?></th>
			<th scope="col"><?php _e('Display', 'onclick-show-popup'); ?></th>
			<th scope="col"><?php _e('Expiration', 'onclick-show-popup'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
			<th scope="col"><?php _e('Popup Title', 'onclick-show-popup'); ?></th>
            <th scope="col"><?php _e('Group', 'onclick-show-popup'); ?></th>
			<th scope="col"><?php _e('Display', 'onclick-show-popup'); ?></th>
			<th scope="col"><?php _e('Expiration', 'onclick-show-popup'); ?></th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			if(count($myData) > 0 )
			{
				foreach ($myData as $data)
				{
					?>
					<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
					<td><?php echo stripslashes($data['OnclickShowPopup_title']); ?>
					<div class="row-actions">
					<span class="edit"><a title="Edit" href="<?php echo WP_OnclickShowPopup_ADMIN_URL; ?>&amp;ac=edit&amp;did=<?php echo $data['OnclickShowPopup_id']; ?>"><?php _e('Edit', 'onclick-show-popup'); ?></a> | </span>
					<span class="trash"><a onClick="javascript:OnclickShowPopup_delete('<?php echo $data['OnclickShowPopup_id']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'onclick-show-popup'); ?></a></span> 
					</div>
					</td>
					<td><?php echo esc_html(stripslashes($data['OnclickShowPopup_group'])); ?></td>
					<td><?php echo esc_html(stripslashes($data['OnclickShowPopup_status'])); ?></td>
					<td><?php echo substr($data['OnclickShowPopup_date'],0,10); ?></td>
					</tr>
					<?php 
					$i = $i+1; 
				}
			}
			else
			{
				?><tr><td colspan="4" align="center"><?php _e('No records available.', 'onclick-show-popup'); ?></td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('OnclickShowPopup_form_show'); ?>
		<input type="hidden" name="frm_OnclickShowPopup_display" value="yes"/>
      </form>	
	  <div class="tablenav bottom">
	  <a href="<?php echo WP_OnclickShowPopup_ADMIN_URL; ?>&amp;ac=add"><input class="button action" type="button" value="<?php _e('Add New', 'onclick-show-popup'); ?>" /></a>
	  <a href="<?php echo WP_OnclickShowPopup_ADMIN_URL; ?>&amp;ac=set"><input class="button action" type="button" value="<?php _e('Setting', 'onclick-show-popup'); ?>" /></a>
	  <a target="_blank" href="<?php echo WP_OnclickShowPopup_FAV; ?>"><input class="button action" type="button" value="<?php _e('Help', 'onclick-show-popup'); ?>" /></a>
	  <a target="_blank" href="<?php echo WP_OnclickShowPopup_FAV; ?>"><input class="button button-primary" type="button" value="<?php _e('Short Code', 'onclick-show-popup'); ?>" /></a>
	  </div>
	</div>
</div>