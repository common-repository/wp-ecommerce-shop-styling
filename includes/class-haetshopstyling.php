<?php

require HAET_SHOP_STYLING_PATH . 'includes/class-haetinvoice.php';
class HaetShopStyling {
	
	
	function HaetShopStyling() { }
	
	/**
	 * initialize the plugin on activation
	 *  activate options and create invoice folder 
	 */
	function init() {
        $this->createTables();
		$this->getOptions();

		wp_mkdir_p( HAET_INVOICE_PATH );
		@ chmod( HAET_INVOICE_PATH, 0775 );
		if ( !is_file( HAET_INVOICE_PATH . ".htaccess" ) ) {
			$htaccess = "order deny,allow\n\r";
			$htaccess .= "deny from all\n\r";
			$htaccess .= "allow from none\n\r";
			$filename = HAET_INVOICE_PATH . ".htaccess";
			$file_handle = @ fopen( $filename, 'w+' );
			@ fwrite( $file_handle, $htaccess );
			@ fclose( $file_handle );
			@ chmod( $file_handle, 0665 );
		}
		update_option('haet_mail_from_name',get_bloginfo('name'));
		update_option('haet_mail_from',get_bloginfo('admin_email'));
	}
	
	function getOptions() {
	 $options = array(
			'template' => stripslashes("<p>&nbsp;</p><p><img class=\"alignnone size-full wp-image-68\" title=\"logo\" src=\"".HAET_SHOP_STYLING_URL."images/logo.jpg\" alt=\"\" width=\"250\" height=\"49\" style=\"border: 0px none;\"/></p><p style=\"text-align: right;\">Companyname </p><p style=\"text-align: right;\">adressline 1</p><p style=\"text-align: right;\">12345 city</p><p style=\"text-align: right;\"> </p><p style=\"text-align: left;\">{billingfirstname} {billinglastname}</p><p style=\"text-align: left;\">{billingaddress}</p><p style=\"text-align: left;\">{billingpostcode} {billingcity}</p><p style=\"text-align: left;\"> </p><p style=\"text-align: right;\">Invoice: {purchase_id}</p><p style=\"text-align: right;\">Date: {date}</p><p style=\"text-align: right;\"> </p><h1 style=\"text-align: left;\">Invoice</h1><p style=\"text-align: left;\">{#productstable#}</p><p style=\"text-align: left;\"> </p><p style=\"text-align: right;\">Products total: {total_product_price}</p><p style=\"text-align: right;\">Shipping total: {total_shipping}</p><p style=\"text-align: right;\">Tax: {total_tax}</p><p style=\"text-align: right;\">Discount: {coupon_amount}</p><p style=\"text-align: right;\"><strong>Total: {cart_total}</strong></p><p style=\"text-align: right;\"> </p><p style=\"text-align: right;\"> </p><p style=\"text-align: center;\">Thank you for your purchase</p><p>&nbsp;</p>"),
			'footercenter' => "your company | adressline 1 | 12345 city | office@yourcompany.net\nBank account no.: 0000000000000000000000",
            'footerright' => "Page {PAGE_NUM} of {PAGE_COUNT}",
            'footerleft'  => "",
            'footerleftfont' => 'dejavu serif',
            'footerleftstyle' => 'normal',
            'footerleftcolor' => '#999999',
            'footerleftsize' => 6,
            'footercenterfont' => 'dejavu serif',
            'footercenterstyle' => 'normal',
            'footercentercolor' => '#999999',
            'footercentersize' => 6,
            'footerrightfont' => 'dejavu serif',
            'footerrightstyle' => 'normal',
            'footerrightcolor' => '#999999',
            'footerrightsize' => 6,
			'css' => "body {\nmargin: 30px;\n}\n/* included unicode fonts:\n*  serif: 'dejavu serif'\n*  sans: 'devavu sans'\n* add your own fonts: http://code.google.com/p/dompdf/wiki/CPDFUnicode#Load_a_font_supporting_your_characters_into_DOMPDF\n*/\nbody, td, th {\nfont-family: 'dejavu serif';\nfont-size: 10px;\n}\np{\nheight:1em;\n}\n\n#products-table{\nwidth:100%;\nborder-collapse:collapse;\npadding-bottom:1px;\nborder-bottom:0.1pt solid #606060;\n}\n#products-table th{\ntext-align:right;\nborder-bottom:0.2pt solid #606060;\n}\n#products-table .product-line td{\ntext-align:right;\nborder-top:0.1pt solid #606060;\n}\n#products-table .product-line .product_name,#products-table .personalization{\ntext-align:left;\n}\n/* fix for displaying prices with EURO sign */\n.pricedisplay{\nmargin-right:5px;\n}\n",
			'paper' => 'a4',
			'filename' => __('invoice','haetshopstyling'),
			'subject_payment_successful' => __('Your purchase at ','haetshopstyling').get_bloginfo('name'),
			'body_payment_successful' =>  "<p>Thank you for your purchase</p><p>We received your payment. Your order {purchase_id} will be processed immediately by our team.</p><p><strong> Your Products</strong></p><p>{#productstable#}</p>",
			'subject_payment_incomplete' => __('Your purchase at ','haetshopstyling').get_bloginfo('name'),
			'body_payment_incomplete' =>  "<p>Thank you for your purchase.</p><p>Please transfer the <strong>amount of</strong> {cart_total} to the following account and please mention your <strong>order number {purchase_id} </strong>in the field as reason:</p><p>Account owner<br />Bank<br />IBAN: XX 000000000000000000000<br />BIC/SWIFT: XX00XX00</p><p>Your articles will be delivered immediately after your payment.</p><p><strong> Your Products</strong></p><p>{#productstable#}</p><p>&nbsp;</p>",
			'subject_payment_failed' => __('Your purchase at ','haetshopstyling').get_bloginfo('name'),
			'body_payment_failed' =>  "<p><strong>Your payment could not be processed.</strong></p><p>Please transfer the <strong>amount of</strong> {cart_total} to the following account and please mention your <strong>order number {purchase_id} </strong>in the field as reason:</p><p>Account owner<br />Bank<br />IBAN: XX 000000000000000000000<br />BIC/SWIFT: XX00XX00</p><p>Your articles will be delivered immediately after your payment.</p><p><strong> Your Products</strong></p><p>{#productstable#}</p><p>&nbsp;</p>",
			'subject_tracking' =>  __('Product Tracking Email','haetshopstyling'),
            'body_tracking' => "<p>Track &amp; Trace means you may track the progress of your parcel with our online parcel tracker, just login to our website and enter the following Tracking ID to view the status of your order.<br /><br /><strong>Tracking ID: {tracking_id}</strong><br /><br /></p>",
            'subject_adminreport' => __('Transaction Report','haetshopstyling'),
            'body_adminreport' => "<h2>Transaction Details</h2><p>Purchase ID: {purchase_id}</p><p>&nbsp;</p><p>Subtotal: {total_product_price}</p><p>Tax: {total_tax}</p><p>Shipping: {total_shipping}</p><p>Discount: {coupon_amount}</p><p>Total: {cart_total}</p><p>&nbsp;</p><h2>Items</h2><p>{#productstable#}</p><p>&nbsp;</p><p>Payment gateway: {payment_gateway}</p><p>&nbsp;</p><p><strong>Billing Details</strong></p><p>First Name : {billingfirstname}</p><p>Last Name : {billinglastname}</p><p>Address : {billingaddress}</p><p>City : {billingcity}</p><p>State : {billingstate}</p><p>Country : {billingcountry}</p><p>Postal Code : {billingpostcode}</p><p>Email : {billingemail}</p><p>Phone : {billingphone}</p><p>&nbsp;</p><p><strong>Shipping Details</strong></p><p>First Name : {shippingfirstname}</p><p>Last Name : {shippinglastname}</p><p>Address : {shippingaddress}</p><p>City : {shippingcity}</p><p>State : {shippingstate}</p><p>Country : AT</p><p>Postal Code : {shippingpostcode}</p><p>&nbsp;</p>",
			'columntitle' => array(
								'',
								'#',
								__('Product','haetshopstyling'),
								__('Quantity','haetshopstyling'),
								__('Price single','haetshopstyling'),
								__('Tax %','haetshopstyling'),
								__('Tax','haetshopstyling'),
								__('Price','haetshopstyling'),
								__('Download','haetshopstyling'),
								"",
								""
							),
			'columnfield' => array(
								'',
								'item_number',
								"product_name",
								"product_quantity",
								"product_price",
								"product_gst",
								"product_tax_charged",
								"price_sum",
								"download",
								"",
								""
							),
			 'mailtemplate' => "<html><head>\n<style type='text/css'>\n/* Mobile-specific Styles */\n@media only screen and (max-device-width: 480px) {\ntable[class=w15], td[class=w15], img[class=w15] { width:5px !important; }\ntable[class=w30], td[class=w30], img[class=w30] { width:10px !important; }\ntable[class=w640], td[class=w640], img[class=w640] { width:300px !important; }\np[class=footer-content-left] { text-align: center !important; }\n#headline p { font-size: 30px !important; }\n.article-content, #left-sidebar{ -webkit-text-size-adjust: 90% !important; -ms-text-size-adjust: 90% !important; }\n.header-content, .footer-content-left {-webkit-text-size-adjust: 80% !important; -ms-text-size-adjust: 80% !important;}\nimg { height: auto; line-height: 100%;}\n}\n/* Add 100px so mobile switch bar doesn't cover street address. */\nbody { background-color: #dedede; margin: 0; padding: 0; }\nimg { outline: none; text-decoration: none; display: block;}\nbr, strong br, b br, em br, i br { line-height:100%; }\nh1, h2, h3, h4, h5, h6 { line-height: 100% !important; -webkit-font-smoothing: antialiased; }\nh1 a, h2 a, h3 a, h4 a, h5 a, h6 a { color: blue !important; }\nh1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {  color: red !important; }\ntable td, table tr { border-collapse: collapse; }\n.yshortcuts, .yshortcuts a, .yshortcuts a:link,.yshortcuts a:visited, .yshortcuts a:hover, .yshortcuts a span {\ncolor: black; text-decoration: none !important; border-bottom: none !important; background: none !important;\n}   /* Body text color for the New Yahoo.  This example sets the font of Yahoo's Shortcuts to black. */\n\n#background-table { background-color: #dedede; }\n/* Webkit Elements */\n#top-bar { border-radius:6px 6px 0px 0px; -moz-border-radius: 6px 6px 0px 0px; -webkit-border-radius:6px 6px 0px 0px; -webkit-font-smoothing: antialiased; background-color: #c7c7c7; color: #ededed; }\n#top-bar a { font-weight: bold; color: #ffffff; text-decoration: none;}\n#footer { border-radius:0px 0px 6px 6px; -moz-border-radius: 0px 0px 6px 6px; -webkit-border-radius:0px 0px 6px 6px; -webkit-font-smoothing: antialiased; }\n/* Fonts and Content */\nbody, td { font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif; }\n.header-content, .footer-content-left, .footer-content-right { -webkit-text-size-adjust: none; -ms-text-size-adjust: none; }\n/* Prevent Webkit and Windows Mobile platforms from changing default font sizes on header and footer. */\n.header-content { font-size: 12px; color: #ededed; }\n.header-content a { font-weight: bold; color: #ffffff; text-decoration: none; }\n#headline p { color: #444444; font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif; font-size: 36px; text-align: center; margin-top:0px; margin-bottom:30px; }\n#headline p a { color: #444444; text-decoration: none; }\n.article-title { font-size: 18px; line-height:24px; color: #b0b0b0; font-weight:bold; margin-top:0px; margin-bottom:18px; font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif; }\n\n.footer-content-left { font-size: 12px; line-height: 15px; color: #ededed; margin-top: 0px; margin-bottom: 15px; }\n.footer-content-left a { color: #ffffff; font-weight: bold; text-decoration: none; }\n.footer-content-right { font-size: 11px; line-height: 16px; color: #ededed; margin-top: 0px; margin-bottom: 15px; }\n.footer-content-right a { color: #ffffff; font-weight: bold; text-decoration: none; }\n#footer { background-color: #c7c7c7; color: #ededed; }\n#footer a { color: #ffffff; text-decoration: none; font-weight: bold; }\n#permission-reminder { white-space: normal; }\n#street-address { color: #b0b0b0; white-space: normal; }\n#products-table{\n width:100%;\n border-collapse:collapse;\n padding-bottom:1px;\n border-bottom:1px solid #606060;\n }\n #products-table th{\n text-align:right;\n border-bottom:2px solid #606060!important;\n }\n #products-table td{\n text-align:right;\n border-bottom:1px solid #606060;\n padding:0 3px;\n }\n #products-table .product_name{\n text-align:left;\n }\n</style>\n</head><body><table id='background-table' border='0' cellpadding='0' cellspacing='0' width='100%'>\n    <tbody><tr>\n        <td align='center' bgcolor='#dedede'>\n            <table class='w640' style='margin:0 10px;' border='0' cellpadding='0' cellspacing='0' width='640'>\n                <tbody><tr><td class='w640' height='20' width='640'></td></tr>\n \n                <tr>\n                    <td class='w640' width='640'>\n                        <table id='top-bar' class='w640' bgcolor='#ffffff' border='0' cellpadding='0' cellspacing='0' width='640'>\n    <tbody><tr>\n        <td class='w15' width='15'></td>\n        <td class='w325' align='left' valign='middle' width='350'>\n            <table class='w325' border='0' cellpadding='0' cellspacing='0' width='350'>\n                <tbody><tr><td class='w325' height='8' width='350'></td></tr>\n            </tbody></table>\n            <div class='header-content'></div>\n            <table class='w325' border='0' cellpadding='0' cellspacing='0' width='350'>\n                <tbody><tr><td class='w325' height='8' width='350'></td></tr>\n            </tbody></table>\n        </td>\n        <td class='w30' width='30'></td>\n        <td class='w255' align='right' valign='middle' width='255'>\n            <table class='w255' border='0' cellpadding='0' cellspacing='0' width='255'>\n                <tbody><tr><td class='w255' height='8' width='255'></td></tr>\n            </tbody></table>\n            <table border='0' cellpadding='0' cellspacing='0'>\n    <tbody><tr>\n \n    </tr>\n</tbody></table>\n            <table class='w255' border='0' cellpadding='0' cellspacing='0' width='255'>\n                <tbody><tr><td class='w255' height='8' width='255'></td></tr>\n            </tbody></table>\n        </td>\n        <td class='w15' width='15'></td>\n    </tr>\n</tbody></table>\n \n                    </td>\n                </tr>\n                <tr>\n                <td id='header' class='w640' align='center' bgcolor='#ffffff' width='640'>\n \n    <table class='w640' border='0' cellpadding='0' cellspacing='0' width='640'>\n        <tbody><tr><td class='w30' width='30'></td><td class='w580' height='30' width='580'></td><td class='w30' width='30'></td></tr>\n        <tr>\n            <td class='w30' width='30'></td>\n            <td class='w580' width='580'>\n                <div id='headline' align='center'>\n<!--- HERE COMES THE HEADER -->\n                    <p>\n                        <strong><a href='".home_url()."'><singleline label='Title'>".get_bloginfo('name')."</singleline></a></strong>\n                    </p>\n<!--- HERE WAS THE HEADER -->\n                </div>\n            </td>\n            <td class='w30' width='30'></td>\n        </tr>\n    </tbody></table>\n \n</td>\n                </tr>\n \n                <tr><td class='w640' height='30' bgcolor='#ffffff' width='640'></td></tr>\n                <tr id='simple-content-row'><td class='w640' bgcolor='#ffffff' width='640'>\n    <table class='w640' border='0' cellpadding='0' cellspacing='0' width='640'>\n        <tbody><tr>\n            <td class='w30' width='30'></td>\n            <td class='w580' width='580'>\n<!--- HERE COMES THE CONTENT -->\n                {#mailcontent#}\n<!--- HERE WAS THE CONTENT -->\n            </td>\n            <td class='w30' width='30'></td>\n        </tr>\n    </tbody></table>\n</td></tr>\n                <tr><td class='w640' height='15' bgcolor='#ffffff' width='640'></td></tr>\n \n                <tr>\n                <td class='w640' width='640'>\n    <table id='footer' class='w640' bgcolor='#c7c7c7' border='0' cellpadding='0' cellspacing='0' width='640'>\n        <tbody><tr><td class='w30' width='30'></td><td class='w580 h0' height='30' width='360'></td><td class='w0' width='60'></td><td class='w0' width='160'></td><td class='w30' width='30'></td></tr>\n        <tr>\n            <td class='w30' width='30'></td>\n            <td class='w580' valign='top' width='360'>\n            <span class='hide'><p id='permission-reminder' class='footer-content-left' align='left'></p></span>\n            <p class='footer-content-left' align='left'>".get_bloginfo('name')."<br/>".get_bloginfo('admin_email')."</p>\n            </td>\n            <td class='hide w0' width='60'></td>\n            <td class='hide w0' valign='top' width='160'>\n            <p id='street-address' class='footer-content-right' align='right'></p>\n            </td>\n            <td class='w30' width='30'></td>\n        </tr>\n        <tr><td class='w30' width='30'></td><td class='w580 h0' height='15' width='360'></td><td class='w0' width='60'></td><td class='w0' width='160'></td><td class='w30' width='30'></td></tr>\n    </tbody></table>\n</td>\n                </tr>\n                <tr><td class='w640' height='60' width='640'></td></tr>\n            </tbody></table>\n        </td>\n    </tr>\n</tbody></table></body></html>",
			 'customsender' => 'enable',
			 'resultspage_successful' => "<p>Thank you for your purchase</p><p>We received your payment. Your order {purchase_id} will be processed immediately by our team.</p><p><strong> Your Products</strong></p><p>{#productstable#}</p>",
			 'resultspage_incomplete' => "<p>Thank you for your purchase.</p><p>Please transfer the <strong>amount of</strong> {cart_total} to the following account and please mention your <strong>order number {purchase_id} </strong>in the field as reason:</p><p>Account owner<br />Bank<br />IBAN: XX 000000000000000000000<br />BIC/SWIFT: XX00XX00</p><p>Your articles will be delivered immediately after your payment.</p><p>&nbsp;</p>",
			 'resultspage_failed' => "<p><strong>Your payment could not be processed.</strong></p><p>Please transfer the <strong>amount of</strong> {cart_total} to the following account and please mention your <strong>order number {purchase_id} </strong>in the field as reason:</p><p>Account owner<br />Bank<br />IBAN: XX 000000000000000000000<br />BIC/SWIFT: XX00XX00</p><p>Your articles will be delivered immediately after your payment.</p><p>&nbsp;</p>",
			 'disablepdf' => "enable",
			 'send_pdf_to_admin' => 'enable',
             'send_pdf_after_payment' => 'disable',
             'invoice_number_system' => 'ordernumber',
             'invoice_number' => '1',
             'personalizationbelow' => 'disabled',
             'personalizationfrom' => 2,
             'personalizationto' => 8,
             'stylenonwpscmails' => 'enable'
		);
		 
		$haetshopstyling_options = get_option('haetshopstyling_options');
		if (!empty($haetshopstyling_options)) {
			foreach ($haetshopstyling_options as $key => $option)
				$options[$key] = $option;
		}				
		update_option('haetshopstyling_options', $options);
		return $options;
	}
	
	function sendInvoiceMail($invoice_params){
		//$invoice_params  Array containing (int)purchase_id, (object)cart_item and (object)purchase_log
		if(!$this->isAllowed('invoice') || $options['disablepdf']=="disable")
			return false;
		
		$purchase_id=$invoice_params['purchase_id'];
		$sessionid = $invoice_params['purchase_log']['sessionid']; 
		//global $purchase_log;
		//$sessionid = $purchase_log['sessionid']; 
		$options = $this->getOptions();
		
		set_transient( "{$sessionid}_pending_email_sent", true, 60 * 60 * 24 * 30);
 
		$filename = $options['filename'].'-'.$purchase_id.'.pdf';
		$params = $this->getBillingData($purchase_id,$options);
		if( count($params) >0 ){
            $invoice = new HaetInvoice($options,$params);
            $invoice->generate($filename);
		}
		error_reporting(E_ERROR); //avoid "is_a(): Deprecated" warning in PHP-Versions between 5.0 and 5.3
		
		if ( !version_compare( WPSC_VERSION, '3.8.9', '>=' ) ){
			$email = wpsc_get_buyers_email($invoice_params['purchase_log']['id']);
			if ( !empty($email) && !get_transient( "{$purchase_id}_invoice_email_sent") ) {
				add_filter( 'wp_mail_from', 'wpsc_replace_reply_address', 0 );
				add_filter( 'wp_mail_from_name', 'wpsc_replace_reply_name', 0 );
				if(stripos('x'.$headers, 'Content-Type: text/html;')==false) {
                    $headers .= "\r\nContent-Type: text/html; charset=".get_bloginfo( 'charset' ).";\r\n";
                }
				$attachments=array(HAET_INVOICE_PATH.$filename);

				if($invoice_params['purchase_log']['processed']==2){ // payment incomplete or manual payment
					$body =  stripslashes(str_replace('\\&quot;','',$options['body_payment_incomplete'])) ;
					$subject = stripslashes(str_replace('\\&quot;','',$options['subject_payment_incomplete'])) ;
				}else if($invoice_params['purchase_log']['processed']==1){ // payment failed
					$body =  stripslashes(str_replace('\\&quot;','',$options['body_payment_failed'])) ;
					$subject = stripslashes(str_replace('\\&quot;','',$options['subject_payment_failed'])) ;
				}else if($invoice_params['purchase_log']['processed']==3){ // payment successful
					$body =  stripslashes(str_replace('\\&quot;','',$options['body_payment_successful'])) ;
					$subject = stripslashes(str_replace('\\&quot;','',$options['subject_payment_successful'])) ;
				}

				foreach ($params AS $param){
					$body = str_replace('{'.$param["unique_name"].'}', $param['value'], $body);
				}
				//$body = stripslashes(str_replace('\\&quot;','',$options['emailbody']));

				wp_mail( $email, $subject, $body,'',$attachments);
				wp_mail( get_bloginfo('admin_email'), $subject, $body,'',$attachments);
				set_transient( "{$purchase_id}_invoice_email_sent", true, 60 * 60 * 24 * 30 );

				add_filter('wp_mail_content_type',create_function('', 'return "text";'));        
			} 
		
			if($this->isAllowed('resultspage'))
				$this->transactionResultsPage($options,$params);
		}
	}
	
    /**
     * add the Invoice Number column to the purchase logs
     * 
     */
    function addPurchaseLogColumnHead( $columns ){
        if(is_plugin_active('qtranslate/qtranslate.php')){
            $columns['locale']=__('Language','haetshopstyling');
        }
        //$columns['invoicenumber']=__('Invoice Number','haetshopstyling');
        return $columns;
    }

    /**
     * add the Invoice Number column content to the purchase logs
     * 
     */
    function addPurchaseLogColumnContent( $default, $column_name, $item ){
        global $wpdb;
        if(is_plugin_active('qtranslate/qtranslate.php') && $column_name=='locale'){
            $locale = $wpdb->get_var("SELECT `locale` FROM ".HAET_TABLE_PURCHASE_DETAILS." WHERE purchase_log_id=".$item->id);
            if ($locale) {
                $locale = substr($locale, 0,2);
                $flags = get_option('qtranslate_flags');
                echo '<img src="'.plugins_url().'/qtranslate/flags/'.$flags[$locale].'" title="'.strtoupper($locale).'">';
            }
        }
        /*if($column_name=='invoicenumber'){
            global $wpdb;
            $sql = $wpdb->prepare('
                SELECT invoice_number,filename,invoice_sent 
                FROM '.HAET_TABLE_PURCHASE_DETAILS.'
                WHERE purchase_log_id=%d
                ',$item->id);

            $invoice = $wpdb->get_results($sql);
            
            print_r($invoice);   
        }*/
    }

	/**
	 * You found the heart of the "licence management"! ;-)
	 * Be fair and don't "hack" it, you'd feel guilty for the rest of your life!
	 * @param string $action
	 * @return boolean 
	 */
	function isAllowed($action){
		if( in_array($action,array('resultspage','invoice'))){
			$keys = get_option('haetshopstyling_keys');
			if(isset($keys[$action])){
				if($action=='resultspage' && md5($keys[$action])=='569b0149663dfa296da905ed6f1a5faf')
					return true;
				if($action=='invoice' && md5($keys[$action])=='49cd08f4b9675b3d99c6dd53022cd29e')
					return true;
			}        
		}
		return false;
	}
	
    function adminPageScriptsAndStyles(){
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script('haet_admin_script',  HAET_SHOP_STYLING_URL.'/js/admin_script.js', array( 'wp-color-picker','jquery'));

    }

	function printAdminPage(){    
        if(isset( $_GET['dismiss_wp_html_mail_notice'] ) ){
            update_option('haetshopstyling_dismiss_wp_html_mail_notice',1);
        }

		if ( isset ( $_GET['tab'] ) ) 
			$tab=$_GET['tab']; 
		else 
			$tab='mailcontent'; 

		$options = $this->getOptions();

		if (isset($_POST['update_haetshopstylingSettings'])) { 
			if ($tab=='invoicetemplate'){
							if (isset($_POST['haetshopstylingtemplate'])) {
									$options['template'] = $_POST['haetshopstylingtemplate'];
									$options['footerleft'] = $_POST['haetshopstylingfooterleft'];
                                    $options['footercenter'] = $_POST['haetshopstylingfootercenter'];
                                    $options['footerright'] = $_POST['haetshopstylingfooterright'];

                                    $options['footerleftfont'] = $_POST['haetshopstylingfooterleftfont'];
                                    $options['footercenterfont'] = $_POST['haetshopstylingfootercenterfont'];
                                    $options['footerrightfont'] = $_POST['haetshopstylingfooterrightfont'];

                                    $options['footerleftstyle'] = $_POST['haetshopstylingfooterleftstyle'];
                                    $options['footercenterstyle'] = $_POST['haetshopstylingfootercenterstyle'];
                                    $options['footerrightstyle'] = $_POST['haetshopstylingfooterrightstyle'];

                                    $options['footerleftcolor'] = $_POST['haetshopstylingfooterleftcolor'];
                                    $options['footercentercolor'] = $_POST['haetshopstylingfootercentercolor'];
                                    $options['footerrightcolor'] = $_POST['haetshopstylingfooterrightcolor'];

                                    $options['footerleftsize'] = $_POST['haetshopstylingfooterleftsize'];
                                    $options['footercentersize'] = $_POST['haetshopstylingfootercentersize'];
                                    $options['footerrightsize'] = $_POST['haetshopstylingfooterrightsize'];

							}	
							if (isset($_POST['haetshopstylingpaper'])) {
									$options['paper'] = $_POST['haetshopstylingpaper'];
							}
							if (isset($_POST['haetshopstylingfilename'])) {
									$options['filename'] = $_POST['haetshopstylingfilename'];
							}
							if (isset($_POST['haetshopstylingdisablepdf'])) {
									$options['disablepdf'] = $_POST['haetshopstylingdisablepdf'];
							}
						 	if (isset($_POST['haetshopstylingsendpdftoadmin'])) {
									$options['send_pdf_to_admin'] = $_POST['haetshopstylingsendpdftoadmin'];
							}
                            if (isset($_POST['haetshopstylingsendpdfafterpayment'])) {
                                    $options['send_pdf_after_payment'] = $_POST['haetshopstylingsendpdfafterpayment'];
                            }
                            if (isset($_POST['haetshopstylinginvoicenumbersystem'])) {
                                    $options['invoice_number_system'] = $_POST['haetshopstylinginvoicenumbersystem'];
                            }
                            if (isset($_POST['haetshopstylinginvoicenumber'])) {
                                    $options['invoice_number'] = $_POST['haetshopstylinginvoicenumber'];
                            }
			}else if ($tab=='products'){
							if (isset($_POST['columntitle'])) {
									$options['columntitle'] = $_POST['columntitle'];
									for($i=1; $i<=count($options['columntitle']); $i++){
										if (!isset($options['columntitle'][$i]) || $options['columntitle'][$i]=='')
											$options['columntitle'][$i]=' ';
										
									}
									$options['columnfield'] = $_POST['columnfield'];
							}
                            $options['personalizationbelow'] = $_POST['haetshopstylingpersonalizationbelow'];
                            $options['personalizationfrom'] = $_POST['haetshopstylingpersonalizationfrom'];
                            $options['personalizationto'] = $_POST['haetshopstylingpersonalizationto'];	
                            if($options['personalizationto']<$options['personalizationfrom'])  
                                $options['personalizationto'] = $options['personalizationfrom'];
			}else if ($tab=='mailcontent'){
							if (isset($_POST['haetshopstylingsubject_payment_successful'])) {
									$options['subject_payment_successful'] = $_POST['haetshopstylingsubject_payment_successful'];
							}	
							if (isset($_POST['haetshopstylingbody_payment_successful'])) {
									$options['body_payment_successful'] = $_POST['haetshopstylingbody_payment_successful'];
							}
							if (isset($_POST['haetshopstylingsubject_payment_incomplete'])) {
									$options['subject_payment_incomplete'] = $_POST['haetshopstylingsubject_payment_incomplete'];
							}	
							if (isset($_POST['haetshopstylingbody_payment_incomplete'])) {
									$options['body_payment_incomplete'] = $_POST['haetshopstylingbody_payment_incomplete'];
							}
							if (isset($_POST['haetshopstylingsubject_payment_failed'])) {
									$options['subject_payment_failed'] = $_POST['haetshopstylingsubject_payment_failed'];
							}	
							if (isset($_POST['haetshopstylingbody_payment_failed'])) {
									$options['body_payment_failed'] = $_POST['haetshopstylingbody_payment_failed'];
							}
							if (isset($_POST['haetshopstylingsubject_tracking'])) {
									$options['subject_tracking'] = $_POST['haetshopstylingsubject_tracking'];
							}	
							if (isset($_POST['haetshopstylingbody_tracking'])) {
									$options['body_tracking'] = $_POST['haetshopstylingbody_tracking'];
							}
                            if (isset($_POST['haetshopstylingsubject_adminreport'])) {
                                    $options['subject_adminreport'] = $_POST['haetshopstylingsubject_adminreport'];
                            }   
                            if (isset($_POST['haetshopstylingbody_tracking'])) {
                                    $options['body_adminreport'] = $_POST['haetshopstylingbody_adminreport'];
                            }
			}else if ($tab=='invoicecss'){
							if (isset($_POST['haetshopstylingcss'])) {
									$options['css'] = $_POST['haetshopstylingcss'];
							}	
			}else if ($tab=='mailtemplate'){
							if (isset($_POST['haetshopstylingsendername'])) {
									update_option('haet_mail_from_name', $_POST['haetshopstylingsendername']);
							}
							if (isset($_POST['haetshopstylingfromaddress'])) {
									update_option('haet_mail_from', $_POST['haetshopstylingfromaddress']);
							}
							if (isset($_POST['haetshopstylingshopsendername'])) {
									update_option('return_name', $_POST['haetshopstylingshopsendername']);
							}
							if (isset($_POST['haetshopstylingshopfromaddress'])) {
									update_option('return_email', $_POST['haetshopstylingshopfromaddress']);
							}
							if (isset($_POST['haetshopstylingmailtemplate'])) {
									$options['mailtemplate'] = $_POST['haetshopstylingmailtemplate'];
							}
							if (isset($_POST['haetshopstylingcustomsender'])) {
									$options['customsender'] = $_POST['haetshopstylingcustomsender'];
							}
							if (isset($_POST['haetshopstylingstylenonwpscmails'])) {
									$options['stylenonwpscmails'] = $_POST['haetshopstylingstylenonwpscmails'];
							}
			}else if ($tab=='resultspage'){
							if (isset($_POST['resultspage_incomplete'])) {
									$options['resultspage_incomplete'] = $_POST['resultspage_incomplete'];
							}	
							if (isset($_POST['resultspage_successful'])) {
									$options['resultspage_successful'] = $_POST['resultspage_successful'];
							}
							if (isset($_POST['resultspage_failed'])) {
									$options['resultspage_failed'] = $_POST['resultspage_failed'];
							}
			}
			update_option('haetshopstyling_options', $options);
			

			echo '<div class="updated"><p><strong>';
					_e("Settings Updated.", "haetshopstyling");
			echo '</strong></p></div>';	
		} 
		
		
		$tabs = array( 
					'mailcontent' => __('Email Content','haetshopstyling'),
					'mailtemplate' => __('Email Template','haetshopstyling'),
					'products' => __('Products Table','haetshopstyling'), 
					'invoicetemplate' => __('Invoice Template','haetshopstyling'), 
					'invoicecss' => __('Invoice CSS','haetshopstyling'),
					'resultspage' => __('Transaction Results Page','haetshopstyling')
			);
		include HAET_SHOP_STYLING_PATH.'views/admin/settings.php';
	
	}

	function productsFieldSelect($id,$active){
		$fields = array(
					array('item_number',      __('item number','haetshopstyling')),
					array('product_name',      __('product name','haetshopstyling')),
					array('product_quantity',      __('quantity','haetshopstyling')),
					array('product_price',      __('single price incl. tax','haetshopstyling')),
					array('product_pnp',      __('product shipping','haetshopstyling')),
					array('price_without_tax',      __('single price without tax','haetshopstyling')),
					array('price_sum',      __('price incl. tax','haetshopstyling')),
					array('price_sum_without_tax',      __('price without tax','haetshopstyling')),
					array('product_gst',      __('tax rate','haetshopstyling')),
					array('tax_single',      __('tax per product','haetshopstyling')),
					array('product_tax_charged',      __('tax sum','haetshopstyling')),
                    array('custom_message',      __('personalization','haetshopstyling')),
                    array('sku',      __('SKU','haetshopstyling').' *'),
					array('download',      __('download link','haetshopstyling').' **'),
                    array('baseprice_sum',      __('base price','haetshopstyling')) //customization for Ivan
			);
		$select = '<select class="products-field-select" id="'.$id.'" name="'.$id.'">';
		$select .= '<option value="" >'.__('hide column','haetshopstyling').'</option>';
		foreach( $fields AS $field){
			$select .= '<option value="'.$field[0].'" '.($field[0]==$active?'selected':'').'>'.$field[1].'</option>';
		}
		$select .= '</select>';
		return $select;
	}
	

    function addEditorButton(){
        // check user permissions
        if ( !current_user_can( 'edit_posts' ) || !current_user_can( 'edit_pages' ) ) {
            return;
        }
        // check if WYSIWYG is enabled
        if ( 'true' == get_user_option( 'rich_editing' ) ) {
            add_filter( 'mce_external_plugins', array(&$this,'registerEditorPlugins') );
            add_filter( 'mce_buttons', array(&$this,'registerEditorButtons'),100 );
            add_filter( 'tiny_mce_before_init', array(&$this,'customizeEditor'),200);
        }
    }

    function registerEditorButtons($buttons){
        array_push( $buttons, 'haet_shopstyling_placeholder' );
        return $buttons;
    }

    function registerEditorPlugins($plugin_array) {
        $plugin_array['haet_shopstyling_placeholder'] = HAET_SHOP_STYLING_URL . 'js/editor.js.php';
        return $plugin_array;
    }

	function customizeEditor($in) {
		$in['remove_linebreaks']=false;
		$in['remove_redundant_brs'] = false;
		$in['wpautop']=false;
        if( FALSE === strpos($in['toolbar1'], 'haet_shopstyling_placeholder')
            && FALSE === strpos($in['toolbar2'], 'haet_shopstyling_placeholder') )
            $in['toolbar1'] .=',haet_shopstyling_placeholder'; 
		return $in;
	}
	

	function showLogInvoiceLink(){
		$options = $this->getOptions();
		
		$purchase_id=$_GET['id'];
		if ( file_exists(HAET_INVOICE_PATH.$options['filename'].'-'.$purchase_id.'.pdf') )
			echo '
				<img src="'.HAET_SHOP_STYLING_URL.'../wp-e-commerce/wpsc-core/images/download.gif'.'">&nbsp;
				<a href="'.HAET_SHOP_STYLING_URL.'includes/download.php?filename='.$options['filename'].'-'.$purchase_id.'.pdf"> '.__("Show Invoice",'haetshopstyling').'</a><br><br class="small">
				';
	}
	
	function currencyDisplay($number){
		return wpsc_currency_display( $number );
	}
	
	function translate($text){
		
	}
	
	function getBillingData($purchase_id,$options,$preview=false){
		global $wpdb;
		$decimal_separator = get_option('wpsc_decimal_separator');
		$params=get_transient("haet_cart_params_{$purchase_id}");

		if($params===false  || $preview){
            if(isset($params['debug'])){
    			$params['debug'] .= '['.date(DATE_ATOM).'] cart_item_count:'.wpsc_cart_item_count().'<br>';
    			$params['debug'] .= '['.date(DATE_ATOM).']<pre>'.print_r($checkout_fields).'</pre><br>';                        
            }
			$params[]= array('unique_name'=>'purchase_id','value'=>$purchase_id);

			$sql = "SELECT date,base_shipping,gateway,totalprice,billing_region,shipping_region,processed
							FROM `".$wpdb->prefix."wpsc_purchase_logs` 
							WHERE  `id` = ".(int)$purchase_id;
			$params2 = $wpdb->get_results($sql,ARRAY_A);
			
			$params[]= array('unique_name'=>'date','value'=>date_i18n(get_option('date_format'),$params2[0]['date']));
			$params[]= array('unique_name'=>'base_shipping','value'=>$this->currencyDisplay($params2[0]['base_shipping']));
			$params[]= array('unique_name'=>'total_shipping','value'=>wpsc_cart_shipping());
			$params[]= array('unique_name'=>'shipping_option','value'=>$this->wpsc_cart_shipping_option()); // added by Matej Rokos - adds shipping_option
			$params[]= array('unique_name'=>'total_product_price','value'=>wpsc_cart_total_widget(false,false,false));
			$params[]= array('unique_name'=>'total_tax','value'=>str_replace( '(','',str_replace(')','',wpsc_cart_tax())) );
            $params[]= array('unique_name'=>'total_without_tax','value'=>$this->currencyDisplay( $params2[0]['totalprice']-wpsc_cart_tax(false)) );
			$params[]= array('unique_name'=>'coupon_amount','value'=>wpsc_coupon_amount());
			$params[]= array('unique_name'=>'cart_total','value'=>wpsc_cart_total());

			$params[]= array('unique_name'=>'total_numeric','value'=>$params2[0]['totalprice']);
            $params[]= array('unique_name'=>'tax_numeric','value'=>wpsc_cart_tax(false));
			$params[]= array('unique_name'=>'total_numeric_without_tax','value'=>$params2[0]['totalprice']-wpsc_cart_tax(false));

			if($params2[0]['processed']==1)
				$params[]= array('unique_name'=>'order_status','value'=>__('Incomplete Sale','wp-e-commerce') );
			else if($params2[0]['processed']==2)
				$params[]= array('unique_name'=>'order_status','value'=>__('Order Received','wp-e-commerce') );
			else if($params2[0]['processed']==3)
				$params[]= array('unique_name'=>'order_status','value'=>__('Accepted Payment','wp-e-commerce') );
			else if($params2[0]['processed']==4)
				$params[]= array('unique_name'=>'order_status','value'=>__('Job Dispatched','wp-e-commerce') );
			else if($params2[0]['processed']==5)
				$params[]= array('unique_name'=>'order_status','value'=>__('Closed Order','wp-e-commerce') );
			else if($params2[0]['processed']==6)
				$params[]= array('unique_name'=>'order_status','value'=>__('Payment Declined','wp-e-commerce') );




			$gateway_names = get_option('payment_gateway_names');
			if( isset( $gateway_names[$params2[0]['gateway']] ))
				$params[]= array('unique_name'=>'payment_gateway','value'=>strip_tags( stripslashes($gateway_names[$params2[0]['gateway']])));
			else
				$params[]= array('unique_name'=>'payment_gateway','value'=>'-');

			$params[]= array('unique_name'=>'payment_instructions','value'=>strip_tags( stripslashes( get_option( 'payment_instructions' ) ) ));

            if(is_numeric($params2[0]['billing_region'])){
                $billingstate= $wpdb->get_var($wpdb->prepare("SELECT `name` FROM ".WPSC_TABLE_REGION_TAX." WHERE id=%d",(int)$params2[0]['billing_region']));
                $params[]= array('unique_name'=>'billingstate','value'=>$billingstate);
            }
            if(is_numeric($params2[0]['shipping_region'])){
                $shippingstate= $wpdb->get_var($wpdb->prepare("SELECT `name` FROM ".WPSC_TABLE_REGION_TAX." WHERE id=%d",(int)$params2[0]['shipping_region']));
                $params[]= array('unique_name'=>'shippingstate','value'=>$shippingstate);
            }
			set_transient( "haet_cart_params_{$purchase_id}", $params, 60 * 60 * 24 * 30 );
		}

		if(!isset($params['checkout_fields_loaded']) || !$params['checkout_fields_loaded']){
			$form_sql = $wpdb->prepare('SELECT IF (unique_name = "",CONCAT("field_",CAST(form_id AS CHAR(2))),unique_name) as unique_name,value
				FROM '.$wpdb->prefix.'wpsc_submited_form_data 
				LEFT JOIN '.$wpdb->prefix.'wpsc_checkout_forms ON '.$wpdb->prefix.'wpsc_submited_form_data.form_id = '.$wpdb->prefix.'wpsc_checkout_forms.id 
				WHERE  log_id = %d AND active = 1
				ORDER BY checkout_order',(int)$purchase_id);

			$checkout_fields = $wpdb->get_results($form_sql,ARRAY_A);            

			$params=array_merge($params,$checkout_fields);
			
			$params['checkout_fields_loaded']=(count($checkout_fields)!=0);
			set_transient( "haet_cart_params_{$purchase_id}", $params, 60 * 60 * 24 * 30 );
		}
		
		$trackingid = $wpdb->get_var("SELECT `track_id` FROM ".WPSC_TABLE_PURCHASE_LOGS." WHERE `id`={$purchase_id} LIMIT 1");
		$params[]= array('unique_name'=>'tracking_id','value'=>$trackingid);


		//GENERATE PRODUCTS TABLE outside the if statement
		// this cannot be cached in a transient because of the download links
		$items = $this->getCartItems($purchase_id);   
		$products_table = '<table id="products-table">';
		$products_table .= "<tr>\n";
		for ($col=1;$col <= count($options["columnfield"]); $col++){
			if($options["columnfield"][$col]!='' && !($options["columnfield"][$col]=='download' && $this->getProcessedState($purchase_id)!=3))
				$products_table .= "<th class='".$options["columnfield"][$col]."'>".__($options["columntitle"][$col])."</th>";
		}
		$products_table .= "</tr>\n";
		$row=0;

        $num_columns=0;
        foreach ($options["columnfield"] AS $field)
            if($field!='' AND !($field=='download' && $this->getProcessedState($purchase_id)!=3 ))
                $num_columns++;

        $total_product_baseprice_numeric=0;
        $total_product_price_numeric=0;
		foreach ($items AS $item){
			$item['item_number']=$row+1;
			$item['price_without_tax']= $this->currencyDisplay( ($item['product_price']*$item['product_quantity']-$item['product_tax_charged'])/$item['product_quantity'] );


			
			$item['product_name']= apply_filters('the_title',$item['product_name']);			
			$item['product_pnp']= wpsc_currency_display($item['product_pnp'], array('display_as_html' => false) );
 
			//item tax
			if($item['product_tax_charged']==0.0){ //Attention tax is exclusive OR 0
				$tax = $this->getExcludedProductTax($item['prodid'],false);
				$item['tax_single']= wpsc_currency_display($tax, array('display_as_html' => false) );
				$item['product_tax_charged']= wpsc_currency_display($tax*$item['product_quantity'], array('display_as_html' => false) );
				$item['product_gst']= number_format($this->getExcludedProductTax($item['prodid'],true), 2, $decimal_separator,'');
				$item['price_sum']= ($item['product_price']+$tax)*$item['product_quantity'];
				$item['price_sum_without_tax']= $this->currencyDisplay( $item['product_price']*$item['product_quantity'] );
				$item['product_price']= $this->currencyDisplay( $item['product_price'] + $tax ) ;
			}else{
				$item['price_sum']= $item['product_price']*$item['product_quantity'];
				$item['price_sum_without_tax']= $this->currencyDisplay( $item['price_sum']-$item['product_tax_charged'] );
				$item['tax_single']= wpsc_currency_display($item['product_tax_charged']/$item['product_quantity'],array('display_as_html' => false) );
				$item['product_tax_charged']= wpsc_currency_display($item['product_tax_charged'], array('display_as_html' => false) );
				$item['product_gst']= number_format($item['product_gst'], 2, $decimal_separator ,'');
				$item['product_price']= $this->currencyDisplay( $item['product_price'] ) ;
			}
            $item['baseprice_sum']= $this->currencyDisplay( $item['baseprice']*$item['product_quantity'] ); 
            $total_product_baseprice_numeric += $item['baseprice']*$item['product_quantity'];
            $total_product_price_numeric += $item['price_sum'];

			$item['price_sum']= $this->currencyDisplay($item['price_sum']);

			$products_table .= "<tr class='product-line'>\n";
			for ($col=1; $col <= count($options["columnfield"]); $col++){
				if($options["columnfield"][$col]=='download' ){
					if($this->getProcessedState($purchase_id)==3 && isset($item['download'])){
						$products_table .= "<td class='".$options["columnfield"][$col]."'>";
						foreach($item['download'] AS $download)
							$products_table .= '<a href="'.$download.'"><img src="'.HAET_SHOP_STYLING_URL.'images/download.png" style="margin-bottom: -5px;" alt="download"></a><br/>';
						$products_table .= "</td>\n"; 
					}
				}else if($options["columnfield"][$col]!='')
					$products_table .= "<td class='".$options["columnfield"][$col]."'>".$item[$options["columnfield"][$col]]."</td>\n";
			}
			$products_table .= "</tr>\n";   
            if($options['personalizationbelow']=="enable"){
                if($options['personalizationto']>$num_columns)
                    $options['personalizationto']=$num_columns;
                if($options['personalizationto']>=$options['personalizationfrom']){
                    $products_table .= '<tr class="personalization-line">';
                    for($i=1;$i<$options['personalizationfrom'];$i++)
                        $products_table .= '<td class="blank"></td>';
                            
                    $products_table .= '<td colspan="'.($options['personalizationto']-$options['personalizationfrom']+1).'" class="personalization">'.str_replace(array("\r\n", "\r", "\n"), "<br />", $item['custom_message'])."</td>\n";   
                    for($i=$options['personalizationto']+1;$i<=$num_columns;$i++)
                        $products_table .= '<td class="blank"></td>';
                    $products_table .= '</tr>';
                }
            }  

			$row++;
		}
        $params[]= array('unique_name'=>'total_product_baseprice','value'=>$this->currencyDisplay($total_product_baseprice_numeric));
        $params[]= array('unique_name'=>'total_product_saving','value'=>$this->currencyDisplay($total_product_baseprice_numeric - $total_product_price_numeric));

		$products_table .= '</table>';//<pre>'.print_r($options["columnfield"],true).'</pre><pre>'.print_r($items,true).'</pre>';
		$params[]= array('unique_name'=>'#productstable#','value'=>$products_table);

		$params = apply_filters( 'shopstyling_placeholders', $params );
		return $params;
	}

	/**
	 * Run this function once before the cart is cleared to compute and save all sum values 
	**/
	function generateBillingData($purchase_log){
        global $wpdb;
		//$purchase_log = new WPSC_Purchase_Log( $_GET['sessionid'], 'sessionid' );
		$options = $this->getOptions();
        $purchase_id = $purchase_log->get('id');

        $customer_locale = $wpdb->get_var("SELECT `locale` FROM ".HAET_TABLE_PURCHASE_DETAILS." WHERE purchase_log_id=".$purchase_id);
		if(!$customer_locale){
			$this->getBillingData($purchase_id,$options);

	        $locale = get_locale();
	        $wpdb->query($wpdb->prepare(  "
	            INSERT INTO ".HAET_TABLE_PURCHASE_DETAILS." (
	              `purchase_log_id`,
	              `locale`
	            ) VALUES (
	                %d,
	                %s
	            )
	            "
	        ,$purchase_id,$locale));
	    }
	}
	

	function getExcludedProductTax($product_id,$rate=false){

		$wpec_taxes_controller = new wpec_taxes_controller();
		//check if tax is enabled
		if ( $wpec_taxes_controller->wpec_taxes->wpec_taxes_get_enabled() ) {
			//run tax logic and calculate tax
			if ( $wpec_taxes_controller->wpec_taxes_run_logic() ) {
				//check if this product has tax disabled
				$meta = get_post_meta($product_id,'_wpsc_product_metadata',true);

				if (!isset($meta['wpec_taxes_taxable']) || $meta['wpec_taxes_taxable']=='on'){
					return 0;
				}
				$wpec_selected_country = $wpec_taxes_controller->wpec_taxes_retrieve_selected_country();
				$region = $wpec_taxes_controller->wpec_taxes_retrieve_region();
				$tax_rate = $wpec_taxes_controller->wpec_taxes->wpec_taxes_get_rate( $wpec_selected_country, $region );

				if($rate)
					return $tax_rate['rate'];

				$taxable_price=wpsc_calculate_price($product_id);
				$tax = $wpec_taxes_controller->wpec_taxes_calculate_tax( $taxable_price, $tax_rate['rate'] );
				
				return $tax;
			}
		}

		return 0;
	}

	function getCartItems($purchase_id){ 
		global $wpdb;

		$form_sql = "SELECT DISTINCT id,
							prodid,
							name AS product_name,
							price AS product_price,
							pnp AS product_pnp,
							no_shipping,
							tax_charged AS product_tax_charged,
							gst AS product_gst,
							quantity AS product_quantity,
                            custom_message,
                            price.meta_value AS baseprice,
                            sku_meta.meta_value AS sku
						FROM `".$wpdb->prefix."wpsc_cart_contents` 
                        INNER JOIN ".$wpdb->prefix."postmeta price ON prodid=price.post_id AND price.meta_key='_wpsc_price'
                        INNER JOIN ".$wpdb->prefix."postmeta sku_meta ON prodid=sku_meta.post_id AND sku_meta.meta_key='_wpsc_sku'
						WHERE  `purchaseid` = ".(int)$purchase_id;
		$cartitems = $wpdb->get_results($form_sql,ARRAY_A);
		if($this->getProcessedState($purchase_id)==3){
			for($i=0;$i<count($cartitems);$i++){
				$cartitems[$i]['download']=$this->getDownloadLinks($purchase_id,$cartitems[$i]['prodid']);
			}
		}
		
		return $cartitems;
	}
	
	function getProcessedState($purchase_id){
		global $wpdb;
		return $wpdb->get_var("SELECT `processed` FROM ".WPSC_TABLE_PURCHASE_LOGS." WHERE id=".$purchase_id);
	}
	
	function getDownloadLinks($purchase_id,$product_id){
		global $wpdb;
		$sql = "SELECT * FROM `" . WPSC_TABLE_DOWNLOAD_STATUS . "` WHERE `purchid` = ".$purchase_id." AND product_id = " . $product_id . " AND `active` IN ('1') ORDER BY `datetime` DESC";
		$products = $wpdb->get_results( $sql, ARRAY_A );
		$products = apply_filters( 'wpsc_has_downloads_products', $products );

		$downloads=array();
		foreach ( (array)$products as $key => $product ) {
			if( empty( $product['uniqueid'] ) ) { // if the uniqueid is not equal to null, its "valid", regardless of what it is
						$downloads[] = site_url() . "/?downloadid=" . $product['id'];
				} else {
						$downloads[] = site_url() . "/?downloadid=" . $product['uniqueid'];
				}
		}
		return $downloads;
	}
	
	/* use this function up to wpsc 3.8.5 */ 
	function transactionResultsPage($options, $params){
		global $message_html;
		global $purchase_log;
		
		if($purchase_log['processed']==2) // payment incomplete or manual payment
			$message_html = stripslashes(str_replace('\\&quot;','',$options['resultspage_incomplete'])) ;
		else if($purchase_log['processed']==1) // payment failed
			$message_html = stripslashes(str_replace('\\&quot;','',$options['resultspage_failed'])) ;
		else if($purchase_log['processed']==3) // payment successful
			$message_html = stripslashes(str_replace('\\&quot;','',$options['resultspage_successful'])) ;
		
		foreach ($params AS $param){
			$message_html = __(str_replace('{'.$param["unique_name"].'}', $param['value'], $message_html));
		}		
	}
	
	/* needed for qtranslate */
	function curPageURL() {
        $pageURL = 'http';
         if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
         $pageURL .= "://";
         if ($_SERVER["SERVER_PORT"] != "80") {
              $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
         } 
        else {
              $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
         }
        return $pageURL;
    }

	/* use this function for wpsc 3.8.9 and later */
	function transactionResultsFilter($output){
		if ( array_key_exists('sessionid', $_GET))
			$sessionid = $_GET['sessionid'];
		else if (function_exists('decrypt_dps_response'))
			$sessionid = decrypt_dps_response();

		$purchase_log = new WPSC_Purchase_Log( $sessionid, 'sessionid' );
		$options = $this->getOptions();
		$params = $this->getBillingData($purchase_log->get('id'),$options);
		
//        WPSC_Purchase_Log::INCOMPLETE_SALE  //= 1;
//	const ORDER_RECEIVED   = 2;
//	const ACCEPTED_PAYMENT = 3;
//	const JOB_DISPATCHED   = 4;
//	const CLOSED_ORDER     = 5;
//	const PAYMENT_DECLINED = 6;
//	const REFUNDED         = 7;
//	const REFUND_PENDING   = 8;
		if($purchase_log->get('processed')==2) // payment incomplete or manual payment
			$message_html = stripslashes(str_replace('\\&quot;','',$options['resultspage_incomplete'])) ;
		else if($purchase_log->get('processed')==1) // payment failed
			$message_html = stripslashes(str_replace('\\&quot;','',$options['resultspage_failed'])) ;
		else if($purchase_log->get('processed')==3) // payment successful
			$message_html = stripslashes(str_replace('\\&quot;','',$options['resultspage_successful'])) ;
		
		foreach ($params AS $param){
            if (gettype ( $param ) == 'array') {
                if (array_key_exists('value', $param) && array_key_exists('unique_name', $param)) {
                    $message_html = __(str_replace('{'.$param["unique_name"].'}', $param['value'], $message_html));
                }
            }
                
		}
        //remove "SKU" column for customers
        $message_html = preg_replace('#\<t[d|h] class=\'sku\'>.*</t[d|h]>#Uis', '', $message_html);
		//translate (in case of qtranslate)
        if(is_plugin_active('qtranslate/qtranslate.php')){
            $current_locale = get_locale();
            global $wpdb;
            $customer_locale = $wpdb->get_var("SELECT `locale` FROM ".HAET_TABLE_PURCHASE_DETAILS." WHERE purchase_log_id=".$purchase_log->get('id'));
            if ( $current_locale != $customer_locale && strlen($customer_locale)>0 ){
                $message_html = __($message_html).'<script>window.location.href="'.qtrans_convertURL($this->curPageURL(), substr($customer_locale, 0,2)).'";</script>';
            }else{
                $message_html = __($message_html);
            }
        }
        return $message_html;
	}
	


	function styleMail($vars){

        
		$options = $this->getOptions();
		extract($vars);

		if(isset($_POST['id'])){ //if state is changed on sales log overview page
			$purchase_id=$_POST['id'];
		} else if(isset($_GET['id'])){ 
			$purchase_id=$_GET['id'];  
		}else if(isset($_POST['log_id'])){ //tracking mail
			$purchase_id=$_POST['log_id'];
		}else if ( version_compare( WPSC_VERSION, '3.8.9', '>=' ) && isset($_GET['sessionid'])){ //transaction results 
			$purchase_log = new WPSC_Purchase_Log( $_GET['sessionid'], 'sessionid' );
			$purchase_id=$purchase_log->get('id');
		}else {
			global $haet_purchase_id; //custom global for transaction result mail
			$purchase_id = $haet_purchase_id;
		}
		
        

		if($purchase_id){
        
			$params = $this->getBillingData($purchase_id,$options);
			if(isset($_GET['email_buyer_id']) || !get_transient( "{$purchase_id}_invoice_email_sent") ){ //if "resend receipt to buyer"
				$filename = $options['filename'].'-'.$purchase_id.'.pdf';
				if ( $this->isAllowed('invoice') 
                        && (
                            $options['disablepdf']=="enable"
                            || ( $options['disablepdf']=="success" && $subject==__( 'Purchase Receipt', 'wp-e-commerce' ) )
                        )){// && file_exists(HAET_INVOICE_PATH.$filename ) ){
					if( count($params) >0 ){
                        $invoice = new HaetInvoice($options,$params);
                        $invoice->generate($filename); 
					}
					if (file_exists(HAET_INVOICE_PATH.$filename ) ){
						$attachments=array(HAET_INVOICE_PATH.$filename);
						set_transient( "{$purchase_id}_invoice_email_sent", true, 60 * 60 * 24 * 30 );
					}
				}
			}
		}

		$is_shop_mail=false;
        $email_type='';
		/*
		 * the idea of the following switch statement is taken from http://schwambell.com/wp-e-commerce-style-email-plugin/ by Jakob Schwartz
		 */
		switch($subject) {
		case __( 'Transaction Report', 'wp-e-commerce' ): 
					$filename = $options['filename'].'-'.$purchase_id.'.pdf';
					if ( $this->isAllowed('invoice') && $options['send_pdf_to_admin']=="enable" && $options['disablepdf']!="disable" && file_exists(HAET_INVOICE_PATH.$filename ) ){
						$attachments=array(HAET_INVOICE_PATH.$filename);
					}
                    $message =  stripslashes(str_replace('\\&quot;','',$options['body_adminreport'])) ;
                    $subject = stripslashes(str_replace('\\&quot;','',$options['subject_adminreport'])) ;
					$is_shop_mail=true;
                    $email_type='wpsc_report';
					break;
		case __( 'Purchase Receipt', 'wp-e-commerce' ): //sent when changing state to "accepted payment"
					$message =  stripslashes(str_replace('\\&quot;','',$options['body_payment_successful'])) ;
					$subject = stripslashes(str_replace('\\&quot;','',$options['subject_payment_successful'])) ;
					$is_shop_mail=true;
                    $email_type='wpsc_accepted';
					break;
		//case __( 'Order Pending', 'wp-e-commerce' ): // when is this message sent!?
		case __( 'Order Pending: Payment Required', 'wp-e-commerce' ):
					$message =  stripslashes(str_replace('\\&quot;','',$options['body_payment_incomplete'])) ;
					$subject = stripslashes(str_replace('\\&quot;','',$options['subject_payment_incomplete']));
					$is_shop_mail=true;
                    $email_type='wpsc_incomplete';
					break;
		case get_option( 'wpsc_trackingid_subject' ): 
					$message =  stripslashes(str_replace('\\&quot;','',$options['body_tracking'])) ;
					$subject = stripslashes(str_replace('\\&quot;','',$options['subject_tracking']));
					$is_shop_mail=true;
                    $email_type='wpsc_tracking';
					break;
		case __( 'The administrator has unlocked your file', 'wp-e-commerce' ):
					$is_shop_mail=true;
                    $email_type='wpsc_unlock';
					break;		
		}
		/*
		$message.='<pre>=====POST:'.print_r($_POST,true).'</pre>';
		$message.='<pre>=====GET:'.print_r($_GET,true).'</pre>';
		$message.='<pre>=====PARAMS:'.print_r($params,true).'</pre>';
		$message.='<pre>=====purchase_id:'.$purchase_id.'</pre>';
		$message.='DEBUG: '.$params['debug'];
		*/  


		if($purchase_id){
			foreach ($params AS $param){
				if( is_array($param) && array_key_exists('unique_name', $param) && array_key_exists('value', $param)){
					$message = str_replace('{'.$param["unique_name"].'}', $param['value'], $message);
					$subject = str_replace('{'.$param["unique_name"].'}', $param['value'], $subject);
				}
			}
            //remove SKU from products table except for the transaction report
            if( $email_type!='wpsc_report' )
                $message = preg_replace('#\<t[d|h] class=\'sku\'>.*</t[d|h]>#Uis', '', $message);
		}

		$style_this_mail = ($is_shop_mail || $options['stylenonwpscmails']!='disable');

        if(!is_plugin_active( 'wp-html-mail/wp-html-mail.php' )){
    		if($style_this_mail){
    			$message = preg_replace('/\<http(.*)\>/', '<a href="http$1">http$1</a>', $message); //replace links like <http://... with <a href="http://..."
    	        if($is_shop_mail)
    	            $message = str_replace('{#mailcontent#}',$message,$options['mailtemplate']);
    	        else
    	            $message = str_replace('{#mailcontent#}',nl2br($message),$options['mailtemplate']);

    			$message = str_replace('{#mailsubject#}',$subject,$message);
    			$message = stripslashes(str_replace('\\&quot;','',$message));

    	        if(stripos('x'.$headers, 'Content-Type: text/html;')==false) {
    	            $headers .= "\r\nContent-Type: text/html; charset=".get_bloginfo( 'charset' ).";\r\n";
    	        }
    	    }
    		//add_filter( 'wp_mail_content_type', create_function('', 'return "text/html";'));
    		
    		if ($is_shop_mail){
    			add_filter( 'wp_mail_from', 'wpsc_replace_reply_address', 0 );
    			add_filter( 'wp_mail_from_name', 'wpsc_replace_reply_name', 0 );
    		}else if($options['customsender']=='enable'){
    			add_filter( 'wp_mail_from', array($this,'setMailFromAddress'), 0 );
    			add_filter( 'wp_mail_from_name', array($this,'setMailSenderName'), 0 );
    		}
        }else{
            $message.='<!--haetshopstyling-->'; //add a flag for WP HTML MAIL
        }
		
		//translate (in case of qtranslate)
        if(is_plugin_active('qtranslate/qtranslate.php')){
            $current_locale = get_locale();
            global $wpdb;
            $customer_locale = $wpdb->get_var("SELECT `locale` FROM ".HAET_TABLE_PURCHASE_DETAILS." WHERE purchase_log_id=".$purchase_id);
            if ( $current_locale != $customer_locale && strlen($customer_locale)>0 ){
                $subject = qtrans_use(substr($customer_locale, 0,2),$subject);
                $message = qtrans_use(substr($customer_locale, 0,2),$message);
            }else{
                $subject = __($subject);
                $message = __($message);
            }
        }
        //$message=array('text/html'=>$message,'text/plain'=>'--dummy content--');
        //wp_mail_extended($to,$subject,$message,$headers);
        //
        //$message ="<h1>Hello World!</h1><p>this is an example text</p>";
		return compact( 'to', 'subject', 'message', 'headers', 'attachments' );
	}
	
	function setMailSenderName($name){ return get_option('haet_mail_from_name'); }
	
	function setMailFromAddress($email){ return get_option('haet_mail_from'); }

	/**
	 * preview the invoice
	 * @global $wpdb
	 * @param int $purchase_id 
	 */
	function previewInvoice($purchase_id=null){
        global $wpdb;

        if($purchase_id)
            $sql = "SELECT id,sessionid
                        FROM `".$wpdb->prefix."wpsc_purchase_logs` 
                        WHERE  `id` = ".(int)$purchase_id;
        else
            $sql = "SELECT id,sessionid
                    FROM `".$wpdb->prefix."wpsc_purchase_logs` 
                    ORDER BY id DESC
                    LIMIT 1";                    
        $result = $wpdb->get_row($sql);
        
        $purchase_id = $result->id; 
        $options = $this->getOptions();
        $params = $this->getBillingData($purchase_id,$options,true);

		$invoice = new HaetInvoice($options,$params);
        $invoice->generate('preview.pdf');
	}
	
	/**
	 * define a global variable for the transation result mail
	 * @global type $haet_purchase_id
	 * @param type $id
	 * @param type $status
	 * @param type $old_status
	 * @param type $purchase_log 
	 */
	function setGlobalPurchaseId( $id, $status, $old_status, $purchase_log ) {
		global $haet_purchase_id; 
		$haet_purchase_id = $id;
	}
	
	function translateCartitemName($name,$id){
		return stripslashes(__($name));
	}
	  
	function translateUrl($url, $original_url, $_context){
		return qtrans_convertURL($url);
	}
	

    /**
     * creates or updates the currency-country table
     * @global object $wpdb
     */
    function createTables(){
        global $wpdb;
        if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s',HAET_TABLE_PURCHASE_DETAILS ) )){
            //$wpdb->query('DROP TABLE `'.HAET_TABLE_PURCHASE_DETAILS.'`');
            if ( !$wpdb->get_var('SHOW COLUMNS FROM '.HAET_TABLE_PURCHASE_DETAILS.' LIKE "locale"')){
                $wpdb->query('ALTER TABLE '.HAET_TABLE_PURCHASE_DETAILS.' ADD locale VARCHAR( 10 ) NULL ');
            }
        }else{
            $wpdb->query(  '
                CREATE TABLE IF NOT EXISTS '.HAET_TABLE_PURCHASE_DETAILS.' (
                  purchase_log_id int(10) unsigned NOT NULL,
                  invoice_number varchar(20) NOT NULL DEFAULT "",
                  filename varchar(255) NOT NULL DEFAULT "",
                  invoice_sent datetime DEFAULT NULL,
                  PRIMARY KEY (purchase_log_id)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
                '
            );

            //write old invoicenumbers and invoice filenames
            //TODO: only if not exists
            $options = $this->getOptions();
            $purchase_ids = $wpdb->get_col('SELECT id FROM '.WPSC_TABLE_PURCHASE_LOGS.' ORDER BY id DESC');
            foreach ($purchase_ids as $purchase_id) {

                if ( file_exists(HAET_INVOICE_PATH.$options['filename'].'-'.$purchase_id.'.pdf') )
                    $wpdb->query($wpdb->prepare(  "
                        INSERT INTO ".HAET_TABLE_PURCHASE_DETAILS." (
                          `purchase_log_id`,
                          `invoice_number`,
                          `filename`, 
                          `invoice_sent`
                        ) VALUES (
                            %d,
                            %s,
                            %s,
                            NULL
                        )
                        "
                    ,$purchase_id,$purchase_id,$options['filename'].'-'.$purchase_id.'.pdf'));
            }
        

            
        }
    }

    function printFooterStyles($options,$footer){
        ?>
        <select  id="haetshopstylingfooter<?php echo $footer; ?>font" name="haetshopstylingfooter<?php echo $footer; ?>font">
            <option value="dejavu sans" <?php echo ($options['footer'. $footer .'font']=="dejavu sans"?"selected":""); ?>>DejaVu Sans</option>   
            <option value="dejavu sans condensed" <?php echo ($options['footer'. $footer .'font']=="dejavu sans condensed"?"selected":""); ?>>DejaVu Sans Condensed</option>
            <option value="dejavu serif" <?php echo ($options['footer'. $footer .'font']=="dejavuserif"?"selected":""); ?>>DejaVu Serif</option> 
            <option value="dejavu serif condensed" <?php echo ($options['footer'. $footer .'font']=="dejavu serif condensed"?"selected":""); ?>>DejaVu Serif Condensed</option>
            <option value="dejavu sans mono" <?php echo ($options['footer'. $footer .'font']=="dejavu sans mono"?"selected":""); ?>>DejaVu Sans Monospace</option>
            <option value="courier" <?php echo ($options['footer'. $footer .'font']=="courier"?"selected":""); ?>>Courier</option>
            <option value="helvetica" <?php echo ($options['footer'. $footer .'font']=="helvetica"?"selected":""); ?>>Helvetica</option>
            <option value="times" <?php echo ($options['footer'. $footer .'font']=="times"?"selected":""); ?>>Times</option>
        </select><br/>
        <select  id="haetshopstylingfooter<?php echo $footer; ?>size" name="haetshopstylingfooter<?php echo $footer; ?>size">
            <option value="4" <?php echo ($options['footer'. $footer .'size']==4?"selected":""); ?>>4pt</option>   
            <option value="5" <?php echo ($options['footer'. $footer .'size']==5?"selected":""); ?>>5pt</option>
            <option value="6" <?php echo ($options['footer'. $footer .'size']==6?"selected":""); ?>>6pt</option>
            <option value="7" <?php echo ($options['footer'. $footer .'size']==7?"selected":""); ?>>7pt</option>
            <option value="8" <?php echo ($options['footer'. $footer .'size']==8?"selected":""); ?>>8pt</option>
            <option value="9" <?php echo ($options['footer'. $footer .'size']==9?"selected":""); ?>>9pt</option>
            <option value="10" <?php echo ($options['footer'. $footer .'size']==10?"selected":""); ?>>10pt</option>
        </select>
        <select  id="haetshopstylingfooter<?php echo $footer; ?>style" name="haetshopstylingfooter<?php echo $footer; ?>style">
            <option value="normal" <?php echo ($options['footer'. $footer .'style']=="normal"?"selected":""); ?>>-</option>  
            <option value="bold" <?php echo ($options['footer'. $footer .'style']=="bold"?"selected":""); ?>><?php _e('bold','haetshopstyling'); ?></option>
            <option value="italic" <?php echo ($options['footer'. $footer .'style']=="italic"?"selected":""); ?>><?php _e('italic','haetshopstyling'); ?></option>   
            <option value="bolditalic" <?php echo ($options['footer'. $footer .'style']=="bolditalic"?"selected":""); ?>><?php _e('bold+italic','haetshopstyling'); ?></option>
        </select><br/>
        <input type="text" value="<?php echo $options['footer'. $footer .'color']; ?>" name="haetshopstylingfooter<?php echo $footer; ?>color" id="haetshopstylingfooter<?php echo $footer; ?>color" data-default-color="#999999" />

        <?php
    }   

    /**
	 * cart shipping option function, no parameters
	 * added by Matej Rokos
	 * @return string the shipping option of the cart
	 */
	function wpsc_cart_shipping_option() {
	   global $wpsc_cart;
	   return $wpsc_cart->selected_shipping_option;
	}
}
?>