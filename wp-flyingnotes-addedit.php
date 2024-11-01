<?php

function wpflyingnotes_edit_page() {
	global $wpdb;
	global $wpflyingnotes_table_name;
  
	// *** Post-It Info
	$id_message      	 = $_REQUEST["wpflyingnotes_id_message"];
	$message_title		 = "";
	$message_body 	 	 = "";
	$message_date    	 = date("Y-m-d");
	$message_top		 = "10";
	$message_left		 = "10";
	
	// *******  SAVE POST-IT ********
	if ( !($_POST["wpflyingnotes_submit"] == "ok") )
	{
		if ($id_message)
		{   $query = "SELECT * FROM " . $wpdb->prefix . $wpflyingnotes_table_name .  
							 			     " WHERE id_message = $id_message ";
			
		    $messageInfo = $wpdb->get_results($query);
			
			$message_title		 = $messageInfo[0]->message_title;
			$message_body 	 	 = $messageInfo[0]->message_body;
			$message_date    	 = $messageInfo[0]->message_date;
			$message_top		 = $messageInfo[0]->message_top;
			$message_left		 = $messageInfo[0]->message_left;
			
		}
	}
	else
	{
		   // *** Postits Data
		   $message_title       = str_replace("\'"," ",$_POST["wpflyingnotes_message_title"]);
		   $message_body 	    = str_replace("\'"," ",$_POST["wpflyingnotes_message_body"]);
		   $message_date    	= str_replace("\'"," ",$_POST["wpflyingnotes_message_date"]);
		   $message_top         = str_replace("\'"," ",$_POST["wpflyingnotes_message_top"]);
		   $message_left        = str_replace("\'"," ",$_POST["wpflyingnotes_message_left"]);
		   
		   if ($id_message)
		   {
			    	 $query = " UPDATE " . $wpdb->prefix . $wpflyingnotes_table_name .  
				   				  " SET message_title   	= '$message_title', " . 
				   				      " message_body 		= '$message_body', " .
			    	 			      " message_top 		= '$message_top', " .
			    	 			      " message_left 		= '$message_left', " .  
				   				      " message_date  		= '$message_date'  "  . 
			    	 		 " WHERE id_message = $id_message ";
			    	 
			    	$wpdb->query($query);
		   }
		   else
		   {
				    $query = "INSERT INTO " . $wpdb->prefix . $wpflyingnotes_table_name  .  
				   					 "( message_title, message_body, message_date, message_top, message_left)  " . 
				   		  "VALUES ('$message_title', '$message_body', '$message_date', '$message_top', '$message_left') "; 
			    
			     
			   		 $wpdb->query($query);
			    	 $lastID = $wpdb->get_results("SELECT MAX(id_message) as lastid_message " .
			    		  						   " FROM " . $wpdb->prefix . $wpflyingnotes_table_name .
			    							 	  " WHERE message_title = '$message_title'");
			    	 
			    	 $id_message = $lastID[0]->lastid_message;
		  }
			    
	}
	 

?>
<div class="wrap">
<script type="text/javascript">
	function validateInfo(forma)
	{
		if (forma.wpflyingnotes_message_title.value == "")
		{
			alert("You must type a title");
			forma.wpflyingnotes_message_title.focus();
			return false;
		}
		
		if (forma.wpflyingnotes_message_body.value == "")
		{
			alert("Enter the message body");
			forma.wpflyingnotes_message_body.focus();
			return false;
		}
		
		if (forma.wpflyingnotes_message_date.value == "")
		{
			alert("The date cannot be empty");
			forma.wpflyingnotes_message_date.focus();
			return false;
		}
		
		
	return true;
}
</script>

<form name="wpflyingnotes_form" method="post" onsubmit="return validateInfo(this);" 
	  action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	  

<?php
    // Now display the options editing screen

    // header
	if ($id_message)
    	echo "<h2>" . __( 'Edit Flying Note',    'mt_trans_domain' ) . "</h2>";
    else
       	echo "<h2>" . __( 'Add New Flying Note', 'mt_trans_domain' ) . "</h2>";

    // options form
    
 ?>
    <?php if ( $_POST["wpflyingnotes_submit"] == "ok" ) { ?>
    <div class="updated"><p><strong><?php _e('Note information saved.', 'mt_trans_domain' ); ?></strong></p></div><br>	
    <? }; ?>

 	
 	<span class="stuffbox" >
 		
		 <label for="wpflyingnotes_message_date">Date</label>
		 <span class="inside">	
		 	<input type="text" size="10" maxlength="11" id="wpflyingnotes_message_date" name="wpflyingnotes_message_date"
		 		   value="<?php echo $message_date ?>"> 
	     </span>
	     
	     <br>
	     
	     
	     <br>
	     Position in Screen:&nbsp;&nbsp; 
		 	Left<input type="text" size="4" maxlength="4"  id="wpflyingnotes_message_left" name="wpflyingnotes_message_left"
		 		   value="<?php echo $message_left ?>">px&nbsp;&nbsp;&nbsp;
		 		   
		    Top<input type="text" size="4" maxlength="4" id="wpflyingnotes_message_top" name="wpflyingnotes_message_top"
		 		   value="<?php echo $message_top ?>">px<br>
	     
	  	      
	     Message Title<br>
		 <span class="inside">
		 	<input type="text" size="60" maxlength="80" id="wpflyingnotes_message_title" name="wpflyingnotes_message_title"
		 		   value="<?php echo $message_title ?>"> 
	     </span>
	     
	     Message Body<br>
		 <span class="inside">	
			<textarea id="wpflyingnotes_message_body" name="wpflyingnotes_message_body" 
		 	           rows="6" cols="60"><?php echo $message_body ?></textarea>		 	
	     </span>
	     
	     <br><br>
	     
	     
	     <br>
	     
 	</span>
 

<p class="submit">
	<input type="hidden" name="wpflyingnotes_submit" value="ok">
	<input type="hidden" name="wpflyingnotes_id_message" value="<?php echo $id_message ?>">
	<input type="submit" name="Submit" value="<?php _e('Save Note Information', 'mt_trans_domain' ) ?>" />&nbsp;
	<input type="button" name="Return" value="<?php _e('Return to Flying Notes List', 'mt_trans_domain' ) ?>"
		   onclick="document.location='options-general.php?page=wpflyingnotes' " />
</p>

</form>

</div> <!-- **** DIV WRAPPER *** -->

<?php } ?>