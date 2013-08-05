<?php

if ( !is_admin() ) 
{
    echo 'Direct access not allowed.';
    exit;
}

global $wpdb;
$message = "";
if (isset($_GET['a']) && $_GET['a'] == '1')
{
    $wpdb->insert( $wpdb->prefix.CP_EASYFORM_FORMS_TABLE, array( 
                                      'form_name' => stripcslashes($_GET["name"]),

                                      'form_structure' => get_option('form_structure', CP_EASYFORM_DEFAULT_form_structure),

                                      'fp_from_email' => get_option('fp_from_email', CP_EASYFORM_DEFAULT_fp_from_email),
                                      'fp_destination_emails' => get_option('fp_destination_emails', CP_EASYFORM_DEFAULT_fp_destination_emails),
                                      'fp_subject' => get_option('fp_subject', CP_EASYFORM_DEFAULT_fp_subject),
                                      'fp_inc_additional_info' => get_option('fp_inc_additional_info', CP_EASYFORM_DEFAULT_fp_inc_additional_info),
                                      'fp_return_page' => get_option('fp_return_page', CP_EASYFORM_DEFAULT_fp_return_page),
                                      'fp_message' => get_option('fp_message', CP_EASYFORM_DEFAULT_fp_message),

                                      'cu_enable_copy_to_user' => get_option('cu_enable_copy_to_user', CP_EASYFORM_DEFAULT_cu_enable_copy_to_user),
                                      'cu_user_email_field' => get_option('cu_user_email_field', CP_EASYFORM_DEFAULT_cu_user_email_field),
                                      'cu_subject' => get_option('cu_subject', CP_EASYFORM_DEFAULT_cu_subject),
                                      'cu_message' => get_option('cu_message', CP_EASYFORM_DEFAULT_cu_message),

                                      'vs_use_validation' => get_option('vs_use_validation', CP_EASYFORM_DEFAULT_vs_use_validation),
                                      'vs_text_is_required' => get_option('vs_text_is_required', CP_EASYFORM_DEFAULT_vs_text_is_required),
                                      'vs_text_is_email' => get_option('vs_text_is_email', CP_EASYFORM_DEFAULT_vs_text_is_email),
                                      'vs_text_datemmddyyyy' => get_option('vs_text_datemmddyyyy', CP_EASYFORM_DEFAULT_vs_text_datemmddyyyy),
                                      'vs_text_dateddmmyyyy' => get_option('vs_text_dateddmmyyyy', CP_EASYFORM_DEFAULT_vs_text_dateddmmyyyy),
                                      'vs_text_number' => get_option('vs_text_number', CP_EASYFORM_DEFAULT_vs_text_number),
                                      'vs_text_digits' => get_option('vs_text_digits', CP_EASYFORM_DEFAULT_vs_text_digits),
                                      'vs_text_max' => get_option('vs_text_max', CP_EASYFORM_DEFAULT_vs_text_max),
                                      'vs_text_min' => get_option('vs_text_min', CP_EASYFORM_DEFAULT_vs_text_min),

                                      'cv_enable_captcha' => get_option('cv_enable_captcha', CP_EASYFORM_DEFAULT_cv_enable_captcha),
                                      'cv_width' => get_option('cv_width', CP_EASYFORM_DEFAULT_cv_width),
                                      'cv_height' => get_option('cv_height', CP_EASYFORM_DEFAULT_cv_height),
                                      'cv_chars' => get_option('cv_chars', CP_EASYFORM_DEFAULT_cv_chars),
                                      'cv_font' => get_option('cv_font', CP_EASYFORM_DEFAULT_cv_font),
                                      'cv_min_font_size' => get_option('cv_min_font_size', CP_EASYFORM_DEFAULT_cv_min_font_size),
                                      'cv_max_font_size' => get_option('cv_max_font_size', CP_EASYFORM_DEFAULT_cv_max_font_size),
                                      'cv_noise' => get_option('cv_noise', CP_EASYFORM_DEFAULT_cv_noise),
                                      'cv_noise_length' => get_option('cv_noise_length', CP_EASYFORM_DEFAULT_cv_noise_length),
                                      'cv_background' => get_option('cv_background', CP_EASYFORM_DEFAULT_cv_background),
                                      'cv_border' => get_option('cv_border', CP_EASYFORM_DEFAULT_cv_border),
                                      'cv_text_enter_valid_captcha' => get_option('cv_text_enter_valid_captcha', CP_EASYFORM_DEFAULT_cv_text_enter_valid_captcha)
                                     )
                      );   
    
    $message = "Item added";
} 
else if (isset($_GET['u']) && $_GET['u'] != '')
{
    $wpdb->query('UPDATE `'.$wpdb->prefix.CP_EASYFORM_FORMS_TABLE.'` SET form_name="'.$wpdb->escape($_GET["name"]).'" WHERE id='.$_GET['u']);           
    $message = "Item updated";        
}
else if (isset($_GET['ac']) && $_GET['ac'] == 'st')
{   
    update_option( 'CP_EFB_LOAD_SCRIPTS', ($_GET["scr"]=="1"?"0":"1") );   
    if ($_GET["chs"] != '')
    {
        $target_charset = $_GET["chs"];
        $tables = array( $wpdb->prefix.CP_EASYFORM_FORMS_TABLE );                
        foreach ($tables as $tab)
        {  
            $myrows = $wpdb->get_results( "DESCRIBE {$tab}" );                                                                                 
            foreach ($myrows as $item)
	        {
	            $name = $item->Field;
		        $type = $item->Type;
		        if (preg_match("/^varchar\((\d+)\)$/i", $type, $mat) || !strcasecmp($type, "CHAR") || !strcasecmp($type, "TEXT") || !strcasecmp($type, "MEDIUMTEXT"))
		        {
	                $wpdb->query("ALTER TABLE {$tab} CHANGE {$name} {$name} {$type} COLLATE {$target_charset}");	            
	            }
	        }
        }
    }
    $message = "Throubleshoot settings updated";
}



if ($message) echo "<div id='setting-error-settings_updated' class='updated settings-error'><p><strong>".$message."</strong></p></div>";

?>
<div class="wrap">
<h2>CP Easy Form Builder</h2>

<script type="text/javascript">
 function cp_addItem()
 {
    var calname = document.getElementById("cp_itemname").value;
    document.location = 'options-general.php?page=cp_easy_form_builder&a=1&r='+Math.random()+'&name='+encodeURIComponent(calname);       
 }
 
 function cp_updateItem(id)
 {
    var calname = document.getElementById("calname_"+id).value;    
    document.location = 'options-general.php?page=cp_easy_form_builder&u='+id+'&r='+Math.random()+'&name='+encodeURIComponent(calname);    
 }
 
 function cp_manageSettings(id)
 {
    document.location = 'options-general.php?page=cp_easy_form_builder&cal='+id+'&r='+Math.random();
 }
 
 function cp_BookingsList(id)
 {
    document.location = 'options-general.php?page=cp_easy_form_builder&cal='+id+'&list=1&r='+Math.random();
 }
 
 function cp_updateConfig()
 {
    if (confirm('Are you sure that you want to update these settings?'))
    {        
        var scr = document.getElementById("ccscriptload").value;    
        var chs = document.getElementById("cccharsets").value;    
        document.location = 'options-general.php?page=cp_easy_form_builder&ac=st&scr='+scr+'&chs='+chs+'&r='+Math.random();
    }    
 }
 
</script>


<div id="normal-sortables" class="meta-box-sortables">


 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Form List / Items List</span></h3>
  <div class="inside">
  
  
  <table cellspacing="10"> 
   <tr>
    <th align="left">ID</th><th align="left">Form Name</th><th align="left">&nbsp; &nbsp; Options</th><th align="left">Shorttag for Pages and Posts</th>
   </tr> 
<?php  

  $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_EASYFORM_FORMS_TABLE );                                                                     
  foreach ($myrows as $item)         
  {
?>
   <tr> 
    <td nowrap><?php echo $item->id; ?></td>
    <td nowrap><input type="text" name="calname_<?php echo $item->id; ?>" id="calname_<?php echo $item->id; ?>" value="<?php echo esc_attr($item->form_name); ?>" /></td>          
    
    <td nowrap>&nbsp; &nbsp; 
                             <input type="button" name="calupdate_<?php echo $item->id; ?>" value="Update" onclick="cp_updateItem(<?php echo $item->id; ?>);" /> &nbsp; 
                             <input type="button" name="calmanage_<?php echo $item->id; ?>" value="Manage Settings" onclick="cp_manageSettings(<?php echo $item->id; ?>);" />
                             
    </td>
    <td nowrap>[CP_EASY_FORM_WILL_APPEAR_HERE id="<?php echo $item->id; ?>"]</td>          
   </tr>
<?php  
   } 
?>   
     
  </table> 
    
    
   
  </div>    
 </div> 
 

 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Throubleshoot Area</span></h3>
  <div class="inside"> 
    <p><strong>Important!</strong>: Use this area <strong>only</strong> if you are experiencing conflicts with third party plugins, with the theme scripts or with the character encoding.</p>
    <form name="updatesettings">
      Script load method:<br />
       <select id="ccscriptload" name="ccscriptload">
        <option value="0" <?php if (get_option('CP_EFB_LOAD_SCRIPTS',"1") == "1") echo 'selected'; ?>>Classic (Recommended)</option>
        <option value="1" <?php if (get_option('CP_EFB_LOAD_SCRIPTS',"1") != "1") echo 'selected'; ?>>Direct</option>
       </select><br />
       <em>* Change the script load method if the form doesn't appear in the public website.</em>
      
      <br /><br />
      Character encoding:<br />
       <select id="cccharsets" name="cccharsets">
        <option value="">Keep current charset (Recommended)</option>
        <option value="utf8_general_ci">UTF-8 (try this first)</option>
        <option value="latin1_swedish_ci">latin1_swedish_ci</option>
       </select><br />
       <em>* Update the charset if you are getting problems displaying special/non-latin characters. After updated you need to edit the special characters again.</em>
       <br />
       <input type="button" onclick="cp_updateConfig();" name="gobtn" value="UPDATE" />
      <br /><br />      
    </form>

  </div>    
 </div> 

  
</div> 


[<a href="http://wordpress.dwbooster.com/contact-us" target="_blank">Request Custom Modifications</a>] | [<a href="http://wordpress.dwbooster.com/calendars/cp-easy-form-builder" target="_blank">Help</a>]
</form>
</div>














