<?php
/*
Plugin Name: CP Easy Form Builder
Plugin URI: http://wordpress.dwbooster.com/forms/cp-easy-form-builder
Description: This plugin allows you to easily insert forms into your website and get them via email.
Version: 1.01
Author: CodePeople.net
Author URI: http://codepeople.net
License: GPL
*/


/* initialization / install / uninstall functions */


define('CP_EASYFORM_TABLE_NAME_NO_PREFIX', "cp_easy_forms");
define('CP_EASYFORM_TABLE_NAME', @$wpdb->prefix . CP_EASYFORM_TABLE_NAME_NO_PREFIX);


// CP Easy Form constants

define('CP_EASYFORM_DEFAULT_DEFER_SCRIPTS_LOADING', (get_option('CP_EFB_LOAD_SCRIPTS',"1") == "1"?true:false));

define('CP_EASYFORM_DEFAULT_form_structure', '[[{"name":"email","index":0,"title":"Email","ftype":"femail","userhelp":"","csslayout":"","required":true,"predefined":"","size":"medium"},{"name":"subject","index":1,"title":"Subject","required":true,"ftype":"ftext","userhelp":"","csslayout":"","predefined":"","size":"medium"},{"name":"message","index":2,"size":"large","required":true,"title":"Message","ftype":"ftextarea","userhelp":"","csslayout":"","predefined":""}],[{"title":"Contact Form","description":"You can use the following form to contact us. <br />","formlayout":"top_aligned"}]]');

define('CP_EASYFORM_DEFAULT_fp_subject', 'Contact from the blog...');
define('CP_EASYFORM_DEFAULT_fp_inc_additional_info', 'true');
define('CP_EASYFORM_DEFAULT_fp_return_page', get_site_url());
define('CP_EASYFORM_DEFAULT_fp_message', "The following contact message has been sent:\n\n<%INFO%>\n\n");

define('CP_EASYFORM_DEFAULT_cu_enable_copy_to_user', 'true');
define('CP_EASYFORM_DEFAULT_cu_user_email_field', '');
define('CP_EASYFORM_DEFAULT_cu_subject', 'Confirmation: Message received...');
define('CP_EASYFORM_DEFAULT_cu_message', "Thank you for your message. We will reply you as soon as possible.\n\nThis is a copy of the data sent:\n\n<%INFO%>\n\nBest Regards.");

define('CP_EASYFORM_DEFAULT_vs_use_validation', 'true');

define('CP_EASYFORM_DEFAULT_vs_text_is_required', 'This field is required.');
define('CP_EASYFORM_DEFAULT_vs_text_is_email', 'Please enter a valid email address.');

define('CP_EASYFORM_DEFAULT_vs_text_datemmddyyyy', 'Please enter a valid date with this format(mm/dd/yyyy)');
define('CP_EASYFORM_DEFAULT_vs_text_dateddmmyyyy', 'Please enter a valid date with this format(dd/mm/yyyy)');
define('CP_EASYFORM_DEFAULT_vs_text_number', 'Please enter a valid number.');
define('CP_EASYFORM_DEFAULT_vs_text_digits', 'Please enter only digits.');
define('CP_EASYFORM_DEFAULT_vs_text_max', 'Please enter a value less than or equal to {0}.');
define('CP_EASYFORM_DEFAULT_vs_text_min', 'Please enter a value greater than or equal to {0}.');


define('CP_EASYFORM_DEFAULT_cv_enable_captcha', 'true');
define('CP_EASYFORM_DEFAULT_cv_width', '180');
define('CP_EASYFORM_DEFAULT_cv_height', '60');
define('CP_EASYFORM_DEFAULT_cv_chars', '5');
define('CP_EASYFORM_DEFAULT_cv_font', 'font-1.ttf');
define('CP_EASYFORM_DEFAULT_cv_min_font_size', '25');
define('CP_EASYFORM_DEFAULT_cv_max_font_size', '35');
define('CP_EASYFORM_DEFAULT_cv_noise', '200');
define('CP_EASYFORM_DEFAULT_cv_noise_length', '4');
define('CP_EASYFORM_DEFAULT_cv_background', 'ffffff');
define('CP_EASYFORM_DEFAULT_cv_border', '000000');
define('CP_EASYFORM_DEFAULT_cv_text_enter_valid_captcha', 'Please enter a valid captcha code.');

define('CP_EASYFORM_FORMS_TABLE', 'cp_easy_form_settings');

// end CP Easy Form constants


register_activation_hook(__FILE__,'cp_easyform_install');
register_deactivation_hook( __FILE__, 'cp_easyform_remove' );


function cp_easyform_install($networkwide)  {
	global $wpdb;
    
	if (function_exists('is_multisite') && is_multisite()) {
		// check if it is a network activation - if so, run the activation function for each blog id
		if ($networkwide) {
	                $old_blog = $wpdb->blogid;
			// Get all blog ids
			$blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				_cp_easyform_install();
			}
			switch_to_blog($old_blog);
			return;
		}
	}
	_cp_easyform_install();
}

function _cp_easyform_install() {
    global $wpdb;

    define('CP_EASYFORM_DEFAULT_fp_from_email', get_the_author_meta('user_email', get_current_user_id()) );
    define('CP_EASYFORM_DEFAULT_fp_destination_emails', CP_EASYFORM_DEFAULT_fp_from_email);

    $table_name = $wpdb->prefix.CP_EASYFORM_FORMS_TABLE;

    $sql = "CREATE TABLE $table_name (
         id mediumint(9) NOT NULL AUTO_INCREMENT,

         form_name VARCHAR(250) DEFAULT '' NOT NULL,

         form_structure text,

         fp_from_email VARCHAR(250) DEFAULT '' NOT NULL,
         fp_destination_emails text,
         fp_subject VARCHAR(250) DEFAULT '' NOT NULL,
         fp_inc_additional_info VARCHAR(10) DEFAULT '' NOT NULL,
         fp_return_page VARCHAR(250) DEFAULT '' NOT NULL,
         fp_message text,

         cu_enable_copy_to_user VARCHAR(10) DEFAULT '' NOT NULL,
         cu_user_email_field VARCHAR(250) DEFAULT '' NOT NULL,
         cu_subject VARCHAR(250) DEFAULT '' NOT NULL,
         cu_message text,

         vs_use_validation VARCHAR(10) DEFAULT '' NOT NULL,
         vs_text_is_required VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_is_email VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_datemmddyyyy VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_dateddmmyyyy VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_number VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_digits VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_max VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_min VARCHAR(250) DEFAULT '' NOT NULL,

         cv_enable_captcha VARCHAR(20) DEFAULT '' NOT NULL,
         cv_width VARCHAR(20) DEFAULT '' NOT NULL,
         cv_height VARCHAR(20) DEFAULT '' NOT NULL,
         cv_chars VARCHAR(20) DEFAULT '' NOT NULL,
         cv_font VARCHAR(20) DEFAULT '' NOT NULL,
         cv_min_font_size VARCHAR(20) DEFAULT '' NOT NULL,
         cv_max_font_size VARCHAR(20) DEFAULT '' NOT NULL,
         cv_noise VARCHAR(20) DEFAULT '' NOT NULL,
         cv_noise_length VARCHAR(20) DEFAULT '' NOT NULL,
         cv_background VARCHAR(20) DEFAULT '' NOT NULL,
         cv_border VARCHAR(20) DEFAULT '' NOT NULL,
         cv_text_enter_valid_captcha VARCHAR(200) DEFAULT '' NOT NULL,

         UNIQUE KEY id (id)
         );";
    $wpdb->query($sql);

    $count = $wpdb->get_var( "SELECT COUNT(id) FROM ".$table_name  );
    if (!$count)
    {                
        $wpdb->insert( $table_name, array( 'id' => 1,
                                      'form_name' => 'Form 1',

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
    }

    add_option("cp_easyform_data", 'Default', '', 'yes'); // Creates new database field    
}

function cp_easyform_remove() {
    delete_option('cp_easyform_data'); // Deletes the database field
}


/* Filter for placing the maps into the contents */

function cp_easyform_filter_content($atts) {
    global $wpdb;    
    extract( shortcode_atts( array(
		'id' => '',
	), $atts ) );

    ob_start();
    define('CP_AUTH_INCLUDE', true);
    cp_easyform_get_public_form(); 
    $buffered_contents = ob_get_contents();
    ob_end_clean();
    
    return $buffered_contents;
}

function cp_easyform_get_public_form() {
    global $wpdb; 
    
    if (defined('CP_EASYFORM_ID'))
        $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_EASYFORM_FORMS_TABLE." WHERE id=".CP_EASYFORM_ID );
    else
        $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_EASYFORM_FORMS_TABLE );
    define ('CP_EASYFORM_ID',$myrows[0]->id);    
    if (CP_EASYFORM_DEFAULT_DEFER_SCRIPTS_LOADING)
    {
        wp_deregister_script('query-stringify');
        wp_register_script('query-stringify', plugins_url('/js/jQuery.stringify.js', __FILE__));
        
        wp_deregister_script('cp_contactformpp_validate_script');
        wp_register_script('cp_easyform_validate_script', plugins_url('/js/jquery.validate.js', __FILE__));
        
        wp_enqueue_script( 'cp_easyform_builder_script', 
        plugins_url('/js/fbuilderf.jquery.js', __FILE__),array("jquery","jquery-ui-core","jquery-ui-datepicker","query-stringify","cp_easyform_validate_script"), false, true );
            
        
        wp_localize_script('cp_easyform_builder_script', 'cp_easyform_fbuilder_config', array('obj'  	=>
        '{"pub":true,"messages": {
        	                	"required": "'.str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_is_required', CP_EASYFORM_DEFAULT_vs_text_is_required)).'",
        	                	"email": "'.str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_is_email', CP_EASYFORM_DEFAULT_vs_text_is_email)).'",
        	                	"datemmddyyyy": "'.str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_datemmddyyyy', CP_EASYFORM_DEFAULT_vs_text_datemmddyyyy)).'",
        	                	"dateddmmyyyy": "'.str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_dateddmmyyyy', CP_EASYFORM_DEFAULT_vs_text_dateddmmyyyy)).'",
        	                	"number": "'.str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_number', CP_EASYFORM_DEFAULT_vs_text_number)).'",
        	                	"digits": "'.str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_digits', CP_EASYFORM_DEFAULT_vs_text_digits)).'",
        	                	"max": "'.str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_max', CP_EASYFORM_DEFAULT_vs_text_max)).'",
        	                	"min": "'.str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_min', CP_EASYFORM_DEFAULT_vs_text_min)).'"
        	                }}'
        ));        
    }    
    else
    {
        wp_enqueue_script( "jquery" );
        wp_enqueue_script( "jquery-ui-core" );
        wp_enqueue_script( "jquery-ui-datepicker" );
    }
?>    
<script type="text/javascript">     
 function cp_easyform_pform_doValidate(form)
 {
    document.cp_easyform_pform.cp_ref_page.value = document.location;
    <?php if (cp_easyform_get_option('cv_enable_captcha', CP_EASYFORM_DEFAULT_cv_enable_captcha) != 'false') { ?>  $dexQuery = jQuery.noConflict();    
    if (document.cp_easyform_pform.hdcaptcha.value == '') { setTimeout( "cp_easyform_cerror()", 100); return false; }      
    var result = $dexQuery.ajax({ type: "GET", url: "<?php echo cp_easyform_get_site_url(); ?>?cp_easyform_pform_process=2&hdcaptcha="+document.cp_easyform_pform.hdcaptcha.value, async: false }).responseText;
    if (result == "captchafailed") {
        $dexQuery("#captchaimg").attr('src', $dexQuery("#captchaimg").attr('src')+'&'+Date());
        setTimeout( "cp_easyform_cerror()", 100);
        return false;
    } else <?php } ?>
        return true;
 }
 function cp_easyform_cerror(){$dexQuery = jQuery.noConflict();$dexQuery("#hdcaptcha_error").css('top',$dexQuery("#hdcaptcha").outerHeight());$dexQuery("#hdcaptcha_error").css("display","inline");}
</script>
<?php    
    @include dirname( __FILE__ ) . '/cp_easyform_public_int.inc.php';
    if (!CP_EASYFORM_DEFAULT_DEFER_SCRIPTS_LOADING)
    {              
        // This code won't be used in most cases. This code is for preventing problems in wrong WP themes and conflicts with third party plugins.
?>
     <?php $plugin_url = plugins_url('', __FILE__); ?>
     <script type='text/javascript' src='<?php echo $plugin_url.'/../../../wp-includes/js/jquery/jquery.js'; ?>'></script>
     <script type='text/javascript' src='<?php echo $plugin_url.'/../../../wp-includes/js/jquery/ui/jquery.ui.core.min.js'; ?>'></script>
     <script type='text/javascript' src='<?php echo $plugin_url.'/../../../wp-includes/js/jquery/ui/jquery.ui.datepicker.min.js'; ?>'></script>    
     <script type='text/javascript' src='<?php echo plugins_url('js/jQuery.stringify.js', __FILE__); ?>'></script>
     <script type='text/javascript' src='<?php echo plugins_url('js/jquery.validate.js', __FILE__); ?>'></script>
     <script type='text/javascript'>
     /* <![CDATA[ */
     var cp_easyform_fbuilder_config = {"obj":"{\"pub\":true,\"messages\": {\n    \t                \t\"required\": \"<?php echo str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_is_required', CP_EASYFORM_DEFAULT_vs_text_is_required));?>\",\n    \t                \t\"email\": \"<?php echo str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_is_email', CP_EASYFORM_DEFAULT_vs_text_is_email));?>\",\n    \t                \t\"datemmddyyyy\": \"<?php echo str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_datemmddyyyy', CP_EASYFORM_DEFAULT_vs_text_datemmddyyyy));?>\",\n    \t                \t\"dateddmmyyyy\": \"<?php echo str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_dateddmmyyyy', CP_EASYFORM_DEFAULT_vs_text_dateddmmyyyy));?>\",\n    \t                \t\"number\": \"<?php echo str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_number', CP_EASYFORM_DEFAULT_vs_text_number));?>\",\n    \t                \t\"digits\": \"<?php echo str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_digits', CP_EASYFORM_DEFAULT_vs_text_digits));?>\",\n    \t                \t\"max\": \"<?php echo str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_max', CP_EASYFORM_DEFAULT_vs_text_max));?>\",\n    \t                \t\"min\": \"<?php echo str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_min', CP_EASYFORM_DEFAULT_vs_text_min));?>\"\n    \t                }}"};
     /* ]]> */
     </script>     
     <script type='text/javascript' src='<?php echo plugins_url('js/fbuilderf.jquery.js', __FILE__); ?>'></script>
<?php        
    }
}



function cp_easyform_show_booking_form($id = "")
{
    if ($id != '')
        define ('CP_EASYFORM_ID',$id);
    define('CP_AUTH_INCLUDE', true);
    @include dirname( __FILE__ ) . '/cp_easyform_public_int.inc.php';    
}

/* Code for the admin area */

if ( is_admin() ) {
    add_action('media_buttons', 'set_cp_easyform_insert_button', 100);
    add_action('admin_enqueue_scripts', 'set_cp_easyform_insert_adminScripts', 1);
    add_action('admin_menu', 'cp_easyform_admin_menu');    

    $plugin = plugin_basename(__FILE__);
    add_filter("plugin_action_links_".$plugin, 'cp_easyform_customAdjustmentsLink');
    add_filter("plugin_action_links_".$plugin, 'cp_easyform_settingsLink');
    add_filter("plugin_action_links_".$plugin, 'cp_easyform_helpLink');

    function cp_easyform_admin_menu() {
        add_options_page('CP Easy Form Builder Options', 'CP Easy Form Builder', 'manage_options', 'cp_easy_form_builder', 'cp_easyform_html_post_page' );
    }
} else { // if not admin
    add_shortcode( 'CP_EASY_FORM_WILL_APPEAR_HERE', 'cp_easyform_filter_content' );        
}

function cp_easyform_settingsLink($links) {
    $settings_link = '<a href="options-general.php?page=cp_easy_form_builder">'.__('Settings').'</a>';
	array_unshift($links, $settings_link);
	return $links;
}

function cp_easyform_helpLink($links) {
    $help_link = '<a href="http://wordpress.dwbooster.com/forms/cp-easy-form-builder">'.__('Help').'</a>';
	array_unshift($links, $help_link);
	return $links;
}

function cp_easyform_customAdjustmentsLink($links) {
    $customAdjustments_link = '<a href="http://wordpress.dwbooster.com/contact-us">'.__('Request custom changes').'</a>';
	array_unshift($links, $customAdjustments_link);
	return $links;
}

function cp_easyform_html_post_page() {
    if (isset($_GET["cal"]) && $_GET["cal"] != '')
        @include_once dirname( __FILE__ ) . '/cp_easyform_admin_int.php';
    else
        @include_once dirname( __FILE__ ) . '/cp_easyform_admin_int_list.inc.php';        
}

function set_cp_easyform_insert_button() {
    print '<a href="javascript:cp_easyform_insertForm();" title="'.__('Insert CP Easy Form').'"><img hspace="5" src="'.plugins_url('/images/cp_form.gif', __FILE__).'" alt="'.__('Insert CP Easy Form').'" /></a>';
}

function set_cp_easyform_insert_adminScripts($hook) {
    if (isset($_GET["page"]) && $_GET["page"] == 'cp_easy_form_builder')
    {
        wp_deregister_script('query-stringify');
        wp_register_script('query-stringify', plugins_url('/js/jQuery.stringify.js', __FILE__));
        wp_enqueue_script( 'cp_easyform_buikder_script', plugins_url('/js/fbuilderf.jquery.js', __FILE__),array("jquery","jquery-ui-core","jquery-ui-sortable","jquery-ui-tabs","jquery-ui-droppable","jquery-ui-button","query-stringify") );
    }
    if( 'post.php' != $hook  && 'post-new.php' != $hook )
        return;
    wp_enqueue_script( 'cp_easyform_script', plugins_url('/cp_easyform_scripts.js', __FILE__) );
}

function cp_easyform_get_site_url()
{
    $url = parse_url(get_site_url());
    $url = rtrim($url["path"],"/");
    if ($url == '')
        $url = 'http://'.$_SERVER["HTTP_HOST"];
    return $url;
}

function cp_easyform_cleanJSON($str)
{
    $str = str_replace('&qquot;','"',$str);
    $str = str_replace('	',' ',$str);
    $str = str_replace("\n",'\n',$str);
    $str = str_replace("\r",'',$str);      
    return $str;
}
/* hook for checking posted data for the admin area */

add_action( 'init', 'cp_easy_form_check_posted_data', 11 );

function cp_easy_form_check_posted_data() {

    global $wpdb;
    
    if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['cp_easyform_post_options'] ) && is_admin() )
    {
        cp_easyform_save_options();
        return;
    }    
    
    @session_start(); 
    if ( isset( $_GET['cp_easyform_pform_process'] ) && $_GET['cp_easyform_pform_process'] == "2")
    {
        if ($_GET['hdcaptcha'] != $_SESSION['rand_code'] || $_SESSION['rand_code'] == '')
        {
            echo 'captchafailed';
            exit;
        }
        else
        {
            echo 'OK';
            exit;
        }
    }    

	if ( 'POST' != $_SERVER['REQUEST_METHOD'] || ! isset( $_POST['cp_easyform_pform_process'] ) )
		return;

    define("CP_EASYFORM_ID",$_POST["cp_easyform_id"]);    
    if ( (cp_easyform_get_option('cv_enable_captcha', CP_EASYFORM_DEFAULT_cv_enable_captcha) != 'false') && ($_POST['hdcaptcha'] != $_SESSION['rand_code']))
    {
        echo 'captchafailed';
        exit;
    }
    $_SESSION['rand_code'] = '';


    // get form info
    //---------------------------
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    $form_data = json_decode(cp_easyform_cleanJSON(cp_easyform_get_option('form_structure', CP_EASYFORM_DEFAULT_form_structure)));
    $fields = array();
    foreach ($form_data[0] as $item)
    {
        $fields[$item->name] = $item->title;
        if ($item->ftype == 'fPhone' || $item->ftype == 'fcheck' || $item->ftype == 'fcheck' || $item->ftype == 'fdropdown' || $item->ftype == 'fdate') // join fields for phone fields                       
        {
            echo "Phone, radio, checkboxes, date and dropdown fields aren't supported in this version. Please check at <a href=\"http://wordpress.dwbooster.com/forms/cp-easy-form-builder\">http://wordpress.dwbooster.com/forms/cp-easy-form-builder</a>";exit;
        }    
    } 

    // grab posted data
    //---------------------------

    $buffer = "";
    foreach ($_POST as $item => $value)
        if (isset($fields[$item]))
            $buffer .= $fields[$item] . ": ". (is_array($value)?(implode(", ",$value)):($value)) . "\n\n";
    $attachments = array();     
    foreach ($_FILES as $item => $value)  
        if (isset($fields[$item]))
        {           
            echo "File uploads aren't supported in this version. Please check at <a href=\"http://wordpress.dwbooster.com/forms/cp-easy-form-builder\">http://wordpress.dwbooster.com/forms/cp-easy-form-builder</a>";exit;
        }  
                    
    $buffer_A = $buffer;
    if ('true' == cp_easyform_get_option('fp_inc_additional_info', CP_EASYFORM_DEFAULT_fp_inc_additional_info))
    {
        $buffer .="ADDITIONAL INFORMATION\n"
              ."*********************************\n"
              ."IP: ".$_SERVER['REMOTE_ADDR']."\n"
              ."Referer: ".$_SERVER["HTTP_REFERER"]."\n"
              ."Server Time:  ".date("Y-m-d H:i:s")."\n"
              ."User Agent: ".$_SERVER['HTTP_USER_AGENT']."\n";

    }

    // 1- Send email
    //---------------------------
    $message = str_replace('<%INFO%>',$buffer,cp_easyform_get_option('fp_message', CP_EASYFORM_DEFAULT_fp_message));
    
    $subject = cp_easyform_get_option('fp_subject', CP_EASYFORM_DEFAULT_fp_subject);
    $from = cp_easyform_get_option('fp_from_email', CP_EASYFORM_DEFAULT_fp_from_email);
    $to = explode(",",cp_easyform_get_option('fp_destination_emails', CP_EASYFORM_DEFAULT_fp_destination_emails));

    foreach ($to as $item)
        if (trim($item) != '')
        {
            wp_mail(trim($item), $subject, $message,
                "From: \"$from\" <".$from.">\r\n".
                "Content-Type: text/plain; charset=utf-8\n".
                "X-Mailer: PHP/" . phpversion(), $attachments);
        }  

    header('Location:'.cp_easyform_get_option('fp_return_page', CP_EASYFORM_DEFAULT_fp_return_page));
    exit;
}

function cp_easyform_save_options() 
{
    global $wpdb;
    if (!defined('CP_EASYFORM_ID'))
        define ('CP_EASYFORM_ID',$_POST["cp_easyform_id"]);
    

    foreach ($_POST as $item => $value)    
        $_POST[$item] = stripcslashes($value);

    $data = array(
                  'form_structure' => $_POST['form_structure'],

                  'fp_from_email' => $_POST['fp_from_email'],
                  'fp_destination_emails' => $_POST['fp_destination_emails'],
                  'fp_subject' => $_POST['fp_subject'],
                  'fp_inc_additional_info' => $_POST['fp_inc_additional_info'],
                  'fp_return_page' => $_POST['fp_return_page'],
                  'fp_message' => $_POST['fp_message'],

                  'cu_enable_copy_to_user' => $_POST['cu_enable_copy_to_user'],
                  'cu_user_email_field' => $_POST['cu_user_email_field'],
                  'cu_subject' => $_POST['cu_subject'],
                  'cu_message' => $_POST['cu_message'],

                  'vs_use_validation' => $_POST['vs_use_validation'],
                  'vs_text_is_required' => $_POST['vs_text_is_required'],
                  'vs_text_is_email' => $_POST['vs_text_is_email'],
                  'vs_text_datemmddyyyy' => $_POST['vs_text_datemmddyyyy'],
                  'vs_text_dateddmmyyyy' => $_POST['vs_text_dateddmmyyyy'],
                  'vs_text_number' => $_POST['vs_text_number'],
                  'vs_text_digits' => $_POST['vs_text_digits'],
                  'vs_text_max' => $_POST['vs_text_max'],
                  'vs_text_min' => $_POST['vs_text_min'],

                  'cv_enable_captcha' => $_POST['cv_enable_captcha'],
                  'cv_width' => $_POST['cv_width'],
                  'cv_height' => $_POST['cv_height'],
                  'cv_chars' => $_POST['cv_chars'],
                  'cv_font' => $_POST['cv_font'],
                  'cv_min_font_size' => $_POST['cv_min_font_size'],
                  'cv_max_font_size' => $_POST['cv_max_font_size'],
                  'cv_noise' => $_POST['cv_noise'],
                  'cv_noise_length' => $_POST['cv_noise_length'],
                  'cv_background' => $_POST['cv_background'],
                  'cv_border' => $_POST['cv_border'],
                  'cv_text_enter_valid_captcha' => $_POST['cv_text_enter_valid_captcha']
	);
    $wpdb->update ( $wpdb->prefix.CP_EASYFORM_FORMS_TABLE, $data, array( 'id' => CP_EASYFORM_ID ));    
}

// cp_easyform_get_option:
$cp_easyform_option_buffered_item = false;
$cp_easyform_option_buffered_id = -1;

function cp_easyform_get_option ($field, $default_value)
{
    if (!defined("CP_EASYFORM_ID"))
        define ("CP_EASYFORM_ID", 1);
    global $wpdb, $cp_easyform_option_buffered_item, $cp_easyform_option_buffered_id;
    if ($cp_easyform_option_buffered_id == CP_EASYFORM_ID)
        $value = $cp_easyform_option_buffered_item->$field;
    else
    {
       $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_EASYFORM_FORMS_TABLE." WHERE id=".CP_EASYFORM_ID );
       $value = $myrows[0]->$field;       
       $cp_easyform_option_buffered_item = $myrows[0];
       $cp_easyform_option_buffered_id  = CP_EASYFORM_ID;
    }
    return $value;
}



// WIDGET CODE BELOW
// ***********************************************************************

class CP_EasyForm_Widget extends WP_Widget
{
  function CP_EasyForm_Widget()
  {
    $widget_ops = array('classname' => 'CP_EasyForm_Widget', 'description' => 'Displays a form' );
    $this->WP_Widget('CP_EasyForm_Widget', 'CP Easy Form Builder', $widget_ops);
  }

  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
    ?><p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p><?php
  }

  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }

  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);

    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

    if (!empty($title))
      echo $before_title . $title . $after_title;;

    // WIDGET CODE GOES HERE
    define('CP_AUTH_INCLUDE', true);
    cp_easyform_get_public_form();

    echo $after_widget;
  }

}

add_action( 'widgets_init', create_function('', 'return register_widget("CP_EasyForm_Widget");') );

?>