<?php
/*
Plugin Name: WP Flying Notes
Plugin URI:  http://www.grupomayanguides.com/wp-flying-notes/
Description: Lets you to put flying messages in your blog. 
Author: Adam Jones
Version: 1.0
Author URI: http://www.grupomayanguides.com/
*/


// *********** PARSER *********** //

register_activation_hook(__FILE__,'wpflyingnotes_install');

$wpflyingnotes_table_name = "flyingnotes";

function wpflyingnotes_install () {
   global $wpdb;
   $wpflyingnotes_table_name = "flyingnotes";
   
   $table_name = $wpdb->prefix . $wpflyingnotes_table_name;
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
      
      $sql = "CREATE TABLE " . $table_name . " (
	  id_message mediumint(9) NOT NULL AUTO_INCREMENT,
	  message_date  DATE NOT NULL,
	  message_title varchar(100) NOT NULL,
	  message_body  TEXT NOT NULL,
	  message_left  varchar(5) NOT NULL,
	  message_top   varchar(5) NOT NULL,
	  UNIQUE KEY id_message (id_message)
	);";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
	
    // **** Insert first note **** 
    $query = "INSERT INTO " . $wpdb->prefix . $wpflyingnotes_table_name  .  
				   					 "( message_title, message_body, message_date, message_left, message_top )  " . 
		" VALUES ('Welcome','Hi, this is the first message from WP Flying Notes. You can drag this note where you want. ', '" 
    	. date("Y-m-d") ."','20','20') "; 
	$wpdb->query($query);
	
			   		 
   }
}


$wpflyingnotes_plugin_dir = get_option("siteurl") . '/wp-content/plugins/' . 
						     basename(dirname(__FILE__));
function wpflyingnotes_setfiles()
{	
	global $wpflyingnotes_plugin_dir;
	echo '<script type="text/javascript" src="' . $wpflyingnotes_plugin_dir . '/wp-flyingnotes.js">' .
		 '</script>';
}




function wpflyingnotes_attachnotes()
{	
	?>
		<script type="text/javascript">
	<?php
	global $wpdb;
	global $wpflyingnotes_table_name;
	$table = $wpdb->prefix .  $wpflyingnotes_table_name;
	
	$notes = $wpdb->get_results("SELECT id_message FROM " . $table);
	foreach ($notes as $note)
	{
  ?>
		DragHandler.attach(document.getElementById('flyingnote<?php echo $note->id_message ?>'));
 <?php
	}
 ?> 
	</script>

<?php
	
}



function wpflyingnotes_createnotes()
{	
	global $wpdb;
	global $wpflyingnotes_plugin_dir;
	global $wpflyingnotes_table_name;
	$table = $wpdb->prefix .  $wpflyingnotes_table_name;
	
	$notes = $wpdb->get_results("SELECT * FROM " . $table);
	foreach ($notes as $note)
	{
		
		
  ?>
		        
	<div id="flyingnote<?php echo $note->id_message ?>" 
			style="position: absolute; top: <?php echo $note->message_top ?>px; left: <?php echo $note->message_left ?>px; width: 120px; background: silver;">
		<table border="0" width="250" style="border: 2px solid #CCCC00" bgcolor="#FFFF66" cellspacing="0" cellpadding="5">
		<tr>
			<td width="100%">
  				<table border="0" width="100%" cellspacing="0" cellpadding="0" height="36">
  				<tr>
  					<td align="left" style="cursor:move; color:black; font-weight:bold;" width="100%">
  						<?php echo $note->message_date ?> - <?php echo $note->message_title ?>  
  					</td>
  					<td style="cursor:hand" valign="top">
  						<a href="#" onClick="hideMe('flyingnote<?php echo $note->id_message ?>');return false">
  							<img src="<?php echo $wpflyingnotes_plugin_dir ?>/close_icon.gif" border="0">
  						</a>
  					</td>
  				</tr>
  				<tr>
  					<td width="100%" bgcolor="#FFFFFF" style="padding:4px" colspan="2">
						<?php echo $note->message_body ?>
						
			   
  					</td>
  				</tr>
  				<tr>
  					<td align="left">
	  					<font style="font-size:6px;">Created by  
					  		<a href="http://www.grupomayanguides.com/" target="_TOP" title="grupo mayan">grupo mayan</a>
					  	</font>
				  	</td>
				  	
  				</tr>
  				</table> 
			</td>
		</tr>
	</table>

	</div>		        				
<?php
	}	
}


include ("wp-flyingnotes-list.php");
include ("wp-flyingnotes-addedit.php");
function wpflyingnotes_add_pages() {
    
	// Add a new submenu under Options:
	 if ($_REQUEST["wpflyingnotes_id_message"] || $_REQUEST["wpflyingnotes_addnew"] )
   		add_options_page('WP Flying Notes', 'WP Flying Notes', 8, 'wpflyingnotes', 'wpflyingnotes_edit_page');
     else
		add_options_page('WP Flying Notes', 'WP Flying Notes', 8, 'wpflyingnotes', 'wpflyingnotes_list_page');
}

add_action('admin_menu',     "wpflyingnotes_add_pages");
add_action("wp_head",        "wpflyingnotes_setfiles");
add_action("wp_footer",      "wpflyingnotes_createnotes");
add_action("wp_footer",      "wpflyingnotes_attachnotes");


?>