<?php
/*
Plugin Name: WP E-Commerce shop styling
Plugin URI: http://wpshopstyling.com/
Description: Send customized HTML mails, custom transaction results and PDF invoices with placeholders from your WP-Ecommerce store 
Version: 2.9.1
Author: haet webdevelopment
Author URI: http://haet.at
License: GPLv2 or later
*/

/*  Copyright 2013 haet (email : contact@haet.at) */


define( 'HAET_SHOP_STYLING_PATH', plugin_dir_path(__FILE__) );
define( 'HAET_SHOP_STYLING_URL', plugin_dir_url(__FILE__) );

$wp_upload_dir_data = wp_upload_dir();
// Upload Path
if ( isset( $wp_upload_dir_data['basedir'] ) )
        $upload_path = $wp_upload_dir_data['basedir'];

// Upload DIR
if ( isset( $wp_upload_dir_data['baseurl'] ) )
        $upload_url = $wp_upload_dir_data['baseurl'];

// SSL Check for URL
if ( is_ssl() )
        $upload_url = str_replace( 'http://', 'https://', $upload_url );

define( 'HAET_INVOICE_PATH',$upload_path.'/wpsc-invoices/');
define( 'HAET_INVOICE_URL',$upload_url.'/wpsc-invoices/');

global $wpdb;
define( 'HAET_TABLE_PURCHASE_DETAILS',$wpdb->prefix.'wpsc_haet_purchase_details');

require HAET_SHOP_STYLING_PATH . 'includes/class-haetshopstyling.php';
load_plugin_textdomain('haetshopstyling', false, dirname( plugin_basename( __FILE__ ) ) . '/translations' );




if (class_exists("HaetShopStyling")) {
	$wp_haetshopstyling = new HaetShopStyling();
}

//Actions and Filters	
if (isset($wp_haetshopstyling)) {
	add_action('admin_menu', 'add_haetshopstyling_adminpage');
    add_filter('wpsc_purchlogitem_links_start',array(&$wp_haetshopstyling, 'showLogInvoiceLink'));
    add_filter( 'wpsc_purchase_log_save',array(&$wp_haetshopstyling, 'generateBillingData'));
    //wpsc_submit_checkout returns correct form values, but 0 for sum values
    //wpsc_purchase_log_save returns correct sum values but no form data
    //add_action('wpsc_transaction_result_cart_item', array(&$wp_haetshopstyling, 'sendInvoiceMail'));
    add_filter('wp_mail',array(&$wp_haetshopstyling, 'styleMail'),12,1);
    //if ( version_compare( WPSC_VERSION, '3.8.9', '>=' ) )
    if($wp_haetshopstyling->isAllowed('resultspage'))
        add_filter('wpsc_get_transaction_html_output',array(&$wp_haetshopstyling, 'transactionResultsFilter'),10,1);
    add_action('wpsc_update_purchase_log_status', array(&$wp_haetshopstyling, 'setGlobalPurchaseId'), 9, 4 );
    
    //Fix for qtranslate
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if(is_plugin_active('qtranslate/qtranslate.php')){
        //cart widget 
        add_filter('wpsc_cart_item_name',array(&$wp_haetshopstyling, 'translateCartitemName'),20,2);
        //cart widget  and cart page
        add_filter('clean_url',array(&$wp_haetshopstyling, 'translateUrl'),20,3);
    }

    //Add Column to purchase logs
    add_filter( 'manage_dashboard_page_wpsc-purchase-logs_columns', array(&$wp_haetshopstyling, 'addPurchaseLogColumnHead') );
    add_filter( 'wpsc_manage_purchase_logs_custom_column', array(&$wp_haetshopstyling, 'addPurchaseLogColumnContent'),10,3 );

    add_action( 'admin_init', array(&$wp_haetshopstyling, 'adminPageScriptsAndStyles'));
    add_action( 'admin_init', array(&$wp_haetshopstyling, 'addEditorButton'));

    //create or update tables
    add_action( 'plugins_loaded', array(&$wp_haetshopstyling, 'createTables') );
}


function haetshopstyling_init(){
    if(!isset($wp_haetshopstyling)) 
        $wp_haetshopstyling = new HaetShopStyling();
    $wp_haetshopstyling->init();
}
register_activation_hook( __FILE__, 'haetshopstyling_init');



function add_haetshopstyling_adminpage() {
		global $wp_haetshopstyling;
		if (!isset($wp_haetshopstyling)) {
			return;
		}
		if (function_exists('add_options_page')) {
            add_options_page(__('style your store','haetshopstyling'), __('Shop styling','haetshopstyling'), 'manage_options', basename(__FILE__), array(&$wp_haetshopstyling, 'printAdminPage'));
		}
}
	

include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
function suggest_wp_html_mail() {
    ?>
    <div class="updated">
        <p><?php printf( 
                    __( '<strong>Notice:</strong> For advanced responsive email styling we recommend to install the free WP HTML MAIL plugin. <a href="%s">Install Plugin</a> <br><a href="options-general.php?page=wp-ecommerce-shop-styling.php&dismiss_wp_html_mail_notice">Dismiss this notice</a> ', 'haetshopstyling' ), 
                    wp_nonce_url( network_admin_url( 'update.php?action=install-plugin&plugin=wp-html-mail' ), 'install-plugin_wp-html-mail' )
            ); ?></p>
    </div>
    <?php
}
if(!is_plugin_active( 'wp-html-mail/wp-html-mail.php' ) && !get_option('haetshopstyling_dismiss_wp_html_mail_notice') && !isset($_GET['dismiss_wp_html_mail_notice']) )
    add_action( 'admin_notices', 'suggest_wp_html_mail' );




