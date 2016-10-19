<?php
global $current_user, $pagenow;
get_currentuserinfo();
/* add CSS from DIVI parent theme*/
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

/**********************************************************************************************/
/*********** ADD ANY CUSTOM FUNCTIONS AND EDITS BETWEEN THESE COMMENT BRACKETS*****************/
/**********************************************************************************************/



/**********************************************************************************************/
/**********THIS MAKES FOR EASY UPGRADES AND TRACKING OF CUSTOM CHANGES************************/
/**********************************************************************************************/
/*Adds WC add to cart buttons back to the divi layout*/
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 1 );

 /*Remove WP Logo*/
add_action( 'admin_bar_menu', 'remove_admin_bar_items', 999 );
function remove_admin_bar_items($wp_admin_bar) {
	
	$wp_admin_bar->remove_node( 'wp-logo' );
}
/*Remove WooCommerce Breadcrumbs*/
add_action( 'init', 'wits_remove_wc_breadcrumbs' );
function wits_remove_wc_breadcrumbs() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}

 /* Change Footer Text */
 function remove_footer_admin () {
    echo "Thank you for choosing <a href='http://wilkinsit.ca/' target='_blank'>Wilkins IT</a>";
}
add_filter('admin_footer_text', 'remove_footer_admin');

/*Warning on plugin page about editing items*/
if ($pagenow == 'plugins.php' ){
function my_admin_error_notice() {
	$class = "update-nag";
	$message = "These plugins and functions are here for a reason! Do not touch unless you know what your doing!";
        echo "<div class=\"$class\"> <p>$message</p></div>"; 
}
add_action( 'admin_notices', 'my_admin_error_notice' ); 
}

/*
Plugin Name: Divi Login
Plugin URI: http://www.gritty-social.com/
Description: Custom Login for Divi Users
Version: 1.0
Author: Gritty Social
Author URI: http://www.gritty-social.com/
*/

function gs_mylogincss() {
    $dir = get_stylesheet_directory_uri().'/css/login-style.css';
    echo "<link rel='stylesheet' href='{$dir}' type='text/css' media='screen' />\n";
}
add_action( 'login_enqueue_scripts', 'gs_mylogincss' );

function gs_myloginlogo() {
    $logo = ($user_logo = get_stylesheet_directory_uri().'/images/logo.png' );
?>
    <style type="text/css">
        h1 a {
            background-image: url(<?php echo $logo;?>) !important;
        }
    </style>
<?php
}
add_action( 'login_enqueue_scripts', 'gs_myloginlogo' );

function gs_swapURL() {
    return '/';
}
add_filter('login_headerurl', 'gs_swapURL');

function gs_loginmeta() {
    return 'Home';
}
add_filter('login_headertitle', 'gs_loginmeta');
/* FINISH DIVI LOGIN*/


/* BEGIN MASTER DISABLE AND ALTERATION SECTION IF LOGGED IN USER IS NOT US "WILKINS IT"*/
   IF('WilkinsIT' == $user_login){
	   add_action( 'admin_bar_menu', 'append_nodes_to_admin_bar',1000 );
		function append_nodes_to_admin_bar($wp_admin_bar) {
			 //Adds Tools Menu
        	 $wp_admin_bar->add_node( array(
         		'parent' => 'custom',
         		'id'     => 'SuperAdmin', // If you reuse media library in two locations then the id name of the second link needs to be different.
         		'title'  => 'Administrative Tools',
         		'href'   => '#',
         		'meta'   => false		
        	));
				$wp_admin_bar->add_node( array(
					'parent' => 'SuperAdmin',
					'id'     => 'Ghoster',
					'title'  => 'Divi Ghoster',
					'href'   => esc_url( admin_url( 'admin.php?page=divi_ghoster' ) ),
					'meta'   => false		
				 ));   
				 $wp_admin_bar->add_node( array(
					'parent' => 'SuperAdmin',
					'id'     => 'DiviRole',
					'title'  => 'Divi Role Editor',
					'href'   => esc_url( admin_url( 'admin.php?page=et_divi_role_editor' ) ),
					'meta'   => false		
				 )); 
				$wp_admin_bar->add_node( array(
					'parent' => 'SuperAdmin',
					'id'     => 'DiviSwitch',
					'title'  => 'Divi Switch',
					'href'   => esc_url( admin_url( 'admin.php?page=divi-switch-settings' ) ),
					'meta'   => false		
				 ));
				$wp_admin_bar->add_node( array(
					'parent' => 'SuperAdmin',
					'id'     => 'DiviBooster',
					'title'  => 'Divi Booster',
					'href'   => esc_url( admin_url( 'admin.php?page=wtfdivi_settings' ) ),
					'meta'   => false		
				 )); 	
		}
   }   
   else{
	/* Load custom CSS to hide the other menu sections that are in the way*/
	add_action('admin_head', 'hide_admin_menus');
	function hide_admin_menus() {
	  echo '<style>
		#toplevel_page_Wordfence {
			display:none
		}
		#toplevel_page_et_bloom_options{
			display:none
		}
		#toplevel_page_MainWPChildServerInformation{
			display:none
		}
		#wp-admin-bar-wp-rocket{
			display:none
		}
		#wp-admin-bar-updraft_admin_node{
			display:none
		}
		#wp-admin-bar-updates{
			display:none;
		}
		#menu-settings.wp-has-submenu.wp-not-current-submenu.menu-top.menu-icon-settings {
			display: none;
		}';
	  echo'</style>';
	  
	}
	
/*
Plugin Name: Divi Client Safe
Plugin URI: http://divi.space/client-ready
Description: Stop dastardly clients messing with your mad design skills.
Version: 1.0
Author: Gritty Social
Author URI: http://www.gritty-social.com/
*/
/* --------- Remove themes, plugins and WordPress updates ----------- */

function remove_core_updates(){
    global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
}
add_filter('pre_site_transient_update_core','remove_core_updates');
add_filter('pre_site_transient_update_plugins','remove_core_updates');
add_filter('pre_site_transient_update_themes','remove_core_updates');
/* --------- Remove dangerous menu tabs ------------- */
	function divi_remove_menus () {
    remove_menu_page('index.php', ‘update-core.php’); // Updates
    remove_menu_page('plugins.php'); // Plugins
//    remove_menu_page('options-general.php'); // Settings
    remove_menu_page('DiviModuleEditor'); //Divi Module Editor
    remove_menu_page('tools.php'); //Tools	
}
add_action('admin_menu', 'divi_remove_menus', 9999);
} /* CLOSE OF IFELSE STATEMENT*/
/*-----------------------------------------------------------------------------------*/
/* Add Wilkins IT Menu */
/*-----------------------------------------------------------------------------------*/
add_action( 'admin_bar_menu', 'add_nodes_to_admin_bar',999 );
 function add_nodes_to_admin_bar($wp_admin_bar) {
         // adds a top level WITS MGMT Node 
        $wp_admin_bar->add_node( array(
         		'id'    => 'custom',
         		'title' => 'WITS MGMT',
         		'href'   =>  esc_url( admin_url() ), // Top level link Custom Made links to an external web site.
         ));         
         // adds the Performance Menu 	
         $wp_admin_bar->add_node( array(
         		'parent' => 'custom',
         		'id'     => 'Performance', // If you reuse media library in two locations then the id name of the second link needs to be different.
         		'title'  => 'Performance Menu',
         		'href'   => '#',
         		'meta'   => false		
         ));
			 $wp_admin_bar->add_node( array(
					'parent' => 'Performance',
					'id'     => 'WPRocket',
					'title'  => 'WP Rocket Settings',
					'href'   => esc_url( admin_url( 'options-general.php?page=wprocket' ) ),
					'meta'   => false		
				));
			  $wp_admin_bar->add_node( array(
					'parent' => 'Performance',
					'id'     => 'LinkChecker',
					'title'  => 'Broken Link Checker',
					'href'   => esc_url( admin_url( 'options-general.php?page=link-checker-settings' ) ),
					'meta'   => false		
				));  
         // Adds Updraft Plus Backups 	
         $wp_admin_bar->add_node( array(
         		'parent' => 'custom',
         		'id'     => 'Updraft', // If you reuse media library in two locations then the id name of the second link needs to be different.
         		'title'  => 'Updraft Plus',
         		'href'   => esc_url( admin_url( 'options-general.php?page=updraftplus') ),
         		'meta'   => false		
         ));
         // Adds the Social Menu
         $wp_admin_bar->add_node( array(
         		'parent' => 'custom',
         		'id'     => 'Social', // If you reuse media library in two locations then the id name of the second link needs to be different.
         		'title'  => 'Social Menu',
         		'href'   => '#',
         		'meta'   => false		
         ));
			 $wp_admin_bar->add_node( array(
					'parent' => 'Social',
					'id'     => 'Monarch',
					'title'  => 'Monarch Social Bar',
					'href'   => esc_url( admin_url( 'tools.php?page=et_monarch_options' ) ),
					'meta'   => false		
				));  
			 $wp_admin_bar->add_node( array(
         		'parent' => 'Social',
         		'id'     => 'Bloom',
         		'title'  => 'Bloom Marketing Tool',
         		'href'   => esc_url( admin_url( 'admin.php?page=et_bloom_options' ) ),
         		'meta'   => false		
         	)); 
        	//Add Bloom Sub-Menu
        	$wp_admin_bar->add_node( array(
         		'parent' => 'Bloom',
         		'id'     => 'EmailAccts',
         		'title'  => 'Email Accounts',
         		'href'   => esc_url( admin_url( 'admin.php?page=et_bloom_options#tab_et_dashboard_tab_content_header_accounts' ) ),
         		'meta'   => false		
         	)); 
				$wp_admin_bar->add_node( array(
					'parent' => 'Bloom',
					'id'     => 'Statistics',
					'title'  => 'Statistics',
					'href'   => esc_url( admin_url( 'admin.php?page=et_bloom_options#tab_et_dashboard_tab_content_header_stats' ) ),
					'meta'   => false		
				));  	 		
				$wp_admin_bar->add_node( array(
					'parent' => 'Bloom',
					'id'     => 'ImportExport',
					'title'  => 'Import & Export',
					'href'   => esc_url( admin_url( 'admin.php?page=et_bloom_options#tab_et_dashboard_tab_content_header_importexport' ) ),
					'meta'   => false		
				));  	
         	
         	//Adds Tools Menu
        	 $wp_admin_bar->add_node( array(
         		'parent' => 'custom',
         		'id'     => 'Tools', // If you reuse media library in two locations then the id name of the second link needs to be different.
         		'title'  => 'WP Tools',
         		'href'   => '#',
         		'meta'   => false		
        	));
				$wp_admin_bar->add_node( array(
					'parent' => 'Tools',
					'id'     => 'Import',
					'title'  => 'Import',
					'href'   => esc_url( admin_url( 'import.php' ) ),
					'meta'   => false		
				 ));   
				 $wp_admin_bar->add_node( array(
					'parent' => 'Tools',
					'id'     => 'Export',
					'title'  => 'Export',
					'href'   => esc_url( admin_url( 'export.php' ) ),
					'meta'   => false		
				));            	
         	//Adds Security Menu
         	$wp_admin_bar->add_node( array(
         		'parent' => 'custom',
         		'id'     => 'WordFenceTop', // If you reuse media library in two locations then the id name of the second link needs to be different.
         		'title'  => 'Wordfence Premium',
         		'href'   => esc_url( admin_url('admin.php?page=Wordfence') ),
         		'meta'   => false		
         	));
				$wp_admin_bar->add_node( array(
					'parent' => 'WordFenceTop',
					'id'     => 'BIPS',
					'title'  => 'Blocked IPs',
					'href'   => esc_url( admin_url( 'admin.php?page=WordfenceBlockedIPs' ) ),
					'meta'   => false		
				));   
				$wp_admin_bar->add_node( array(
					'parent' => 'WordFenceTop',
					'id'     => 'PWAudit',
					'title'  => 'Password Audit',
					'href'   => esc_url( admin_url( 'admin.php?page=WordfencePasswdAudit' ) ),
					'meta'   => false		
				));			
			//Adds Framework Menu
         	$wp_admin_bar->add_node( array(
         		'parent' => 'custom',
         		'id'     => 'DiviWITS', // If you reuse media library in two locations then the id name of the second link needs to be different.
         		'title'  => 'Framework',
         		'href'   => '#',
         		'meta'   => false		
         	));
				$wp_admin_bar->add_node( array(
					'parent' => 'DiviWITS',
					'id'     => 'DisclaimerFramework',
					'title'  => 'EDIT OPTIONS IN THIS SECTION AT YOUR OWN RISK!!!',
					'href'   => '#',
					'meta'   => false		
				));   
				$wp_admin_bar->add_node( array(
					'parent' => 'DiviWITS',
					'id'     => 'ThemeCustomizerWITS',
					'title'  => 'Framework Theme Customizer',
					'href'   => esc_url( admin_url( 'customize.php?et_customizer_option_set=theme' ) ),
					'meta'   => false		
				));  
				$wp_admin_bar->add_node( array(
					'parent' => 'DiviWITS',
					'id'     => 'OptionsGeneral',
					'title'  => 'General WP Options',
					'href'   => esc_url( admin_url( 'options-general.php' ) ),
					'meta'   => false		
				));
				$wp_admin_bar->add_node( array(
					'parent' => 'DiviWITS',
					'id'     => 'DiviLibrary',
					'title'  => 'Customizer Library',
					'href'   => esc_url( admin_url( 'edit.php?post_type=et_pb_layout' ) ),
					'meta'   => false		
				));  
				$wp_admin_bar->add_node( array(
					'parent' => 'DiviWITS',
					'id'     => 'PluginsMenu',
					'title'  => 'Plugins',
					'href'   => esc_url( admin_url( 'plugins.php' ) ),
					'meta'   => false		
				));
				$wp_admin_bar->add_node( array(
					'parent' => 'DiviWITS',
					'id'     => 'WidgetsMenu',
					'title'  => 'Widgets',
					'href'   => esc_url( admin_url( 'widgets.php' ) ),
					'meta'   => false		
				));
				$wp_admin_bar->add_node( array(
					'parent' => 'DiviWITS',
					'id'     => 'Menumenu',
					'title'  => 'WP Menu\'s',
					'href'   => esc_url( admin_url( 'nav-menus.php' ) ),
					'meta'   => false		
				));
 } 
 add_action('admin_head', 'white_label_it',10000);
function white_label_it() {
    echo '<style>
				#et_pb_layout h2.hndle:before {
				font-family: "etmodules";
				content: "";
				/* Building Alternate icon */
			}
			#et_settings_meta_box h2.hndle:before {
				content: "g";
				/* Adjustment Vertical icon */
			}
			/* Change The Divi Builder to The Layout Builder */
			#et_pb_layout h2.hndle span, div.et_pb_prompt_modal h3{
				color: #59B447;
				/* Make font color match background to hide it */
			}
			#et_pb_layout h2.hndle span:before, div.et_pb_prompt_modal h3:before {
				content: "The Layout Builder";
				display: block;
				position: absolute;
				left: 80px;
				top: 25px;
				font-size: 22px;
				color: #fff;
			}
			/* Change Divi Page Settings to Page Settings */
			#et_settings_meta_box h2.hndle span {
				color: #59B447;
				/* Make font color match background to hide it */
			}
			#et_settings_meta_box h2.hndle span:before {
				content: "Page Settings";
				display: block;
				position: absolute;
				left: 44px;
				top: 8px;
				font-size: 14px;
				color: #fff;
			}
			#et_pb_layout_controls .et-pb-layout-buttons,h1#epanel-title,#epanel-header .defaults-button,#epanel-header,#epanel-mainmenu, #et_pb_layout_controls, #et_pb_layout .hndle, #et_settings_meta_box .hndle,.et-pb-settings-heading,.et-pb-preview-screensize-switcher, .et-pb-options-tabs-links,.et-pb-modal-close, .et-pb-options-tabs-links,.et_pb_prompt_modal h3 {
				background-color:#59B447;
			}
			#et_pb_layout_controls .et-pb-layout-buttons:hover,#epanel-header .defaults-button:hover,.et-pb-preview-screensize-switcher li a:hover, .et-pb-options-tabs-links li a:hover,.et-pb-preview-screensize-switcher,.et-pb-preview-screensize-switcher li a.active, .et-pb-options-tabs-links li.et-pb-options-tabs-links-active a,.et-pb-modal-close:hover,#epanel-mainmenu a:hover, #epanel-mainmenu li.ui-state-active a {
				background-color:#addba4;
			}
			.et-pb-app-portability-button {
				display:none;
			}
			</style>';			
} 
?>
