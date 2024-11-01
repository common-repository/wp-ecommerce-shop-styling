
	<style>
		.wp-editor-wrap{
			max-width:800px;
		}
		
		#products-table{
			width:100%;
			border-collapse:collapse;
			padding-bottom:1px;
			border-bottom:0.1pt solid #606060;
		}
		#products-table th{
			text-align:right;
			border-bottom:2px solid #606060;
		}
		#products-table .product-line td{
			text-align:right;
			border-top:1px solid #606060;
		}

		#products-table .product-line .product_name,#products-table .personalization{
			text-align:left;
		}
		
		.upgrademessage{
				margin: 5px 0 15px;
				background-color: #FFFFE0;
				border: 1px solid #E6DB55;
				padding: 0 0.6em;
		}

		.mce_invoicefieldsList .mceFirst a{
				width: 126px !important;
		}

		.mce_checkoutformfieldsList .mceFirst a{
				width: 150px !important;
		}

		table.invoice-footer {
			max-width:800px;
		}

		td.footerleft,td.footerright {
			width:25%;
		}

		td.footercenter {
			width:50%;
		}

		table.invoice-footer textarea {
			height:60px;
			min-height:60px;
			max-height:60px;
			width:100%;
			max-width:100%;
			min-width:100%;
		}

		#haetshopstylingfooterleft,.footerleft{
			text-align:left;
		}
		#haetshopstylingfooterright,.footerright{
			text-align:right;
		}
		#haetshopstylingfootercenter,.footercenter{
			text-align:center;
		}
		.haetshopstylingdocumentation{
			text-align:right;
			float:right;
		}
	</style>
<div class=wrap>
	
	<div>
		<div class="haetshopstylingdocumentation">
			<?php _e('Need some help?','haetshopstyling'); ?>
			<a href="http://wpshopstyling.com/documentation/" target="_blank"><?php _e('Documentation','haetshopstyling'); ?></a>
			 | 
			<a href="http://wpshopstyling.com/forum/wp-shop-styling/" target="_blank"><?php _e('Forum','haetshopstyling'); ?></a>
			 | 
			<a href="http://wpshopstyling.com/support/" target="_blank"><?php _e('Support','haetshopstyling'); ?></a>
		</div>
		<img src="<?php echo HAET_SHOP_STYLING_URL;?>images/icon.png">
		<div class="clear"></div>
	</div>
	<h2 class="nav-tab-wrapper">
	<?php
		foreach( $tabs as $el => $name ){
			$class = ( $el == $tab ) ? ' nav-tab-active' : '';
			echo "<a class='nav-tab$class' href='?page=wp-ecommerce-shop-styling.php&tab=$el'>$name</a>";
		}
	?>
	</h2>
	
		

<?php 
		switch ( $tab ){
			case 'invoicetemplate' :
				if(!$this->isAllowed('invoice')){
					?>
					<div class="upgrademessage">
						<div style="float:left">
							<h4><?php _e('You have not unlocked this feature yet','haetshopstyling'); ?></h4>
							<p><?php _e('You can edit and even preview the invoice but it will not be published to your customers.','haetshopstyling'); ?></p>
							<p><a href="http://wpshopstyling.com/" target="_blank"><?php _e('read more &raquo;','haetshopstyling'); ?></a></p>
						</div>
						<div style="margin-left:50px; float:left">
							<h4><?php _e('Get this feature!','haetshopstyling'); ?></h4>
							<a href="http://wpshopstyling.com/download/" target="_blank" class="button"><?php _e('Upgrade Now &raquo;','haetshopstyling'); ?></a>
						</div>
						
						<div style="clear:both"> </div>
					</div>
					<?php
				}
				?>
				<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
				<?php
				global $wpdb;
				$sql = "SELECT id
						FROM `".$wpdb->prefix."wpsc_purchase_logs` 
						ORDER BY id DESC
						LIMIT 1";                    
				$latest_purchase_id = $wpdb->get_var($sql);
				if($latest_purchase_id){
					?>
					<h2><?php _e('preview invoice','haetshopstyling'); ?></h2>
					<p>
					<?php _e("This preview shows the invoice layout. It uses data from the latest purchase, but shipping or sum values may be incorrect or empty.",'haetshopstyling'); ?>
					</p>
					<?php
					echo '<a class="button" id="invoice-preview-link" href="?page=wp-ecommerce-shop-styling.php&tab=previewinvoice"> '.__("preview invoice",'haetshopstyling').'</a><br/><br/>';
				}
				?>
				 
				<h2><?php _e('Invoice Template','haetshopstyling'); ?></h2>
								
				 <?php 
				 if(ini_get('allow_url_fopen')==0){
					 _e('The PHP setting "allow_url_fopen" is disabled so you can\'t include external images. You can use images from this webserver or contact your hosting provider to change the setting.','haetshopstyling');
					 echo "<br/><br/>";
				 }

				 wp_editor(stripslashes(str_replace('\\&quot;','',$options['template'])),'haetshopstylingtemplate',array(
						'media_buttons'=>true,
						'tinymce' => array(
								'theme_advanced_buttons3' => 'invoicefields,checkoutformfields',
							)
						)
					 );
 
				 ?>
				<br/><h2><?php _e('Invoice Footer','haetshopstyling'); ?></h2>
				<p class="warning"><?php _e('WARNING: The footer has been changed in version 1.10. Your previous footer text will not appear on the invoice any more. <a href="http://haet.at/new-pdf-invoice-footer-wp-e-commerce/" target="blank">read more</a>','haetshopstyling'); ?></p>					
				<p class="description"><?php _e('Copy any placeholder from above to your footer or use the special placeholders {PAGE_NUM} and {PAGE_COUNT} only available in footer','haetshopstyling'); ?></p>
				<table class="form-table invoice-footer">
					<tbody>
						<tr valign="top">
							<th class="footerleft" scope="column"><label for="haetshopstylingfooterleft"><?php _e('Left','haetshopstyling'); ?></label></th>
							<th class="footercenter" scope="column"><label for="haetshopstylingfootercenter"><?php _e('Center','haetshopstyling'); ?></label></th>
							<th class="footerright" scope="column"><label for="haetshopstylingfooterright"><?php _e('Right','haetshopstyling'); ?></label></th>
						</tr>
						<tr>
							<td class="footerleft"><textarea id="haetshopstylingfooterleft" name="haetshopstylingfooterleft"><?php echo $options['footerleft']; ?></textarea></td>
							<td class="footercenter"><textarea id="haetshopstylingfootercenter" name="haetshopstylingfootercenter"><?php echo $options['footercenter']; ?></textarea></td>
							<td class="footerright"><textarea id="haetshopstylingfooterright" name="haetshopstylingfooterright"><?php echo $options['footerright']; ?></textarea></td>
						</tr>
						<tr>
							<td class="footerleft">
								<?php $this->printFooterStyles($options,'left'); ?>
							</td>
							<td class="footercenter">
								<?php $this->printFooterStyles($options,'center'); ?>
							</td>
							<td class="footerright">
								<?php $this->printFooterStyles($options,'right'); ?>
							</td>
						</tr>
					</tbody>
				</table>

				<br/><br/>

				 
		 <table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="haetshopstylingpaper"><?php _e('Paper','haetshopstyling'); ?></label></th>
					<td>
						<select  id="haetshopstylingpaper" name="haetshopstylingpaper">
						  <option value="a4" <?php echo ($options['paper']=="a4"?"selected":""); ?>>a4</option>
						  <option value="letter" <?php echo ($options['paper']=="letter"?"selected":""); ?>>letter</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="haetshopstylingfilename"><?php _e('Filename','haetshopstyling'); ?></label></th>
					<td>
						<input type="text" class="regular-text" id="haetshopstylingfilename" name="haetshopstylingfilename" value="<?php echo $options['filename']; ?>">
						<span class="description"><?php _e('&lt;filename&gt;&lt;invoicenumber&gt;.pdf','haetshopstyling'); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="haetshopstylingsendpdftoadmin"><?php _e('Attach PDF to the transaction report','haetshopstyling'); ?></label></th>
					<td>
						<select  id="haetshopstylingsendpdftoadmin" name="haetshopstylingsendpdftoadmin">
						  <option value="enable" <?php echo ($options['send_pdf_to_admin']=="enable"?"selected":""); ?>><?php _e('enable','haetshopstyling'); ?></option>
						  <option value="disable" <?php echo ($options['send_pdf_to_admin']=="disable"?"selected":""); ?>><?php _e('disable','haetshopstyling'); ?></option>
						</select>
					</td>
				</tr>
				<!--<tr valign="top">
					<th scope="row"><label for="haetshopstylingsendpdfafterpayment"><?php _e('Send the after accepted payment only','haetshopstyling'); ?></label></th>
					<td>
						<select  id="haetshopstylingsendpdfafterpayment" name="haetshopstylingsendpdfafterpayment">
						  <option value="enable" <?php echo ($options['send_pdf_after_payment']=="enable"?"selected":""); ?>><?php _e('enable','haetshopstyling'); ?></option>
						  <option value="disable" <?php echo ($options['send_pdf_after_payment']=="disable"?"selected":""); ?>><?php _e('disable','haetshopstyling'); ?></option>
						</select>
						<span class="description"><?php _e('invoice and invoicenumber are generated after successful payment only.','haetshopstyling'); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="haetshopstylinginvoicenumbersystem"><?php _e('Invoice number system','haetshopstyling'); ?></label></th>
					<td>
						<select  id="haetshopstylinginvoicenumbersystem" name="haetshopstylinginvoicenumbersystem">
						  <option value="ordernumber" <?php echo ($options['invoice_number_system']=="ordernumber"?"selected":""); ?>><?php _e('Order number','haetshopstyling'); ?></option>
						  <option value="invoicenumber" <?php echo ($options['invoice_number_system']=="invoicenumber"?"selected":""); ?>><?php _e('Auto incrementing invoice number','haetshopstyling'); ?></option>
						  <option value="manual" <?php echo ($options['invoice_number_system']=="manual"?"selected":""); ?>><?php _e('Manual number','haetshopstyling'); ?></option>
						</select>
						<span class="description"><?php _e('The option "manual number" will only work with manual payment, because you have to enter an invoice number, when you change the transaction state to "payment accepted".','haetshopstyling'); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="haetshopstylingsendpdfafterpayment"><?php _e('Send the invoice after accepted payment only','haetshopstyling'); ?></label></th>
					<td>
						<select  id="haetshopstylingsendpdfafterpayment" name="haetshopstylingsendpdfafterpayment">
						  <option value="enable" <?php echo ($options['send_pdf_after_payment']=="enable"?"selected":""); ?>><?php _e('enable','haetshopstyling'); ?></option>
						  <option value="disable" <?php echo ($options['send_pdf_after_payment']=="disable"?"selected":""); ?>><?php _e('disable','haetshopstyling'); ?></option>
						</select>
						<span class="description"><?php _e('invoice and invoicenumber are generated after successful payment only.','haetshopstyling'); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="haetshopstylinginvoicenumber"><?php _e('Current invoice number','haetshopstyling'); ?></label></th>
					<td>
						<input type="text" class="regular-text" id="haetshopstylinginvoicenumber" name="haetshopstylinginvoicenumber" value="<?php echo $options['invoice_number']; ?>">
						<span class="description"><?php _e('this number will be used in the next invoice and incremented afterwards','haetshopstyling'); ?></span>
					</td>
				</tr>-->
				<tr valign="top">
					<th scope="row"><label for="haetshopstylingdisablepdf"><?php _e('Send PDF invoice','haetshopstyling'); ?></label></th>
					<td>
						<select  id="haetshopstylingdisablepdf" name="haetshopstylingdisablepdf">
						  <option value="enable" <?php echo ($options['disablepdf']=="enable"?"selected":""); ?>><?php _e('send with order confirmation','haetshopstyling'); ?></option>
						  <option value="success" <?php echo ($options['disablepdf']=="success"?"selected":""); ?>><?php _e('only on successful payment','haetshopstyling'); ?></option>
						  <option value="admin" <?php echo ($options['disablepdf']=="admin"?"selected":""); ?>><?php _e('only to the store admin','haetshopstyling'); ?></option>
						  <option value="disable" <?php echo ($options['disablepdf']=="disable"?"selected":""); ?>><?php _e('disable','haetshopstyling'); ?></option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
				 
<?php 
			break;
			case 'products':
?>

			<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">

	<h2><?php _e('Products table','haetshopstyling'); ?></h2>   
	<p><?php _e('Customize the fields and headlines for your products table. This table will show up on your invoice and your mails instead of the according placeholder.','haetshopstyling'); ?></p>
	<p><?php _e('At the moment your table content will look like this, but the formatting will be different because of your stylesheets.','haetshopstyling'); ?></p>
	<?php
			$products_table = '<table id="products-table" style="width:600px">';
			$products_table .= '<tr>';

			for ($col=1;$col < count($options["columnfield"]); $col++){
				if($options["columnfield"][$col]!='')
					$products_table .= "<th  class='".$options["columnfield"][$col]."'>".$options["columntitle"][$col]."</th>";
			}
			$products_table .= '</tr>';
			$row=0;


			$items=array();
			$items[0]=array(
				'item_number'       => '1',
				'product_name'      => 'beautiful red apple',
				'product_quantity'  => '3',
				'product_price'     => '$ 2.20',
				'product_pnp'       => '$ 1.00',
				'price_without_tax' => '$ 2.00',
				'price_sum'         => '$ 6.60',
				'price_sum_without_tax' => '$ 6.00',
				'product_gst'       => '10 %',
				'product_tax_charged'=>'$ 0.60',
				'tax_single'        =>'$ 0.20',
				'custom_message'    =>'write my name on the apple',
				'download'          => '<img src="'.HAET_SHOP_STYLING_URL.'images/download.png" style="margin-bottom: -5px;" alt="download">'
			); 
			$items[1]=array(
				'item_number'       => '2',
				'product_name'      => 'banana',
				'product_quantity'  => '2',
				'product_price'     => '$ 1.10',
				'product_pnp'       => '$ 0.50',
				'price_without_tax' => '$ 1.00',
				'price_sum'         => '$ 2.20',
				'price_sum_without_tax' => '$ 2.00',
				'product_gst'       => '10 %',
				'product_tax_charged'=>'$ 0.20',
				'tax_single'        =>'$ 0.10',
				'custom_message'    =>'write "For Daisy, yours Donald" on the package',
				'download'          => '<img src="'.HAET_SHOP_STYLING_URL.'images/download.png" style="margin-bottom: -5px;" alt="download">'
			); 
			$num_columns=0;
			foreach ($options["columnfield"] AS $field)
				if($field!='')
					$num_columns++;
					
			foreach ($items AS $item){
				$products_table .= '<tr class="product-line">'; 
				foreach ($options["columnfield"] AS $field){
					if($field!='')
						$products_table .= "<td class='$field'>".$item[$field]."</td>";
					
				}
				$products_table .= '</tr>';    
				if($options['personalizationbelow']=="enable"){
					
					if($options['personalizationto']>$num_columns)
						$options['personalizationto']=$num_columns;
					if($options['personalizationto']>=$options['personalizationfrom']){
						$products_table .= '<tr class="personalization-line">';
						for($i=1;$i<$options['personalizationfrom'];$i++)
							$products_table .= '<td class="blank"></td>';
								
						$products_table .= '<td colspan="'.($options['personalizationto']-$options['personalizationfrom']+1).'" class="personalization">'.nl2br($item['custom_message']).'</td>';	
						for($i=$options['personalizationto']+1;$i<=$num_columns;$i++)
							$products_table .= '<td class="blank"></td>';
						$products_table .= '</tr>';
					}
				} 
			}
			$products_table .= '</table><p>&nbsp;</p>';
			echo $products_table; 
?>
	
	<table style="border:1px solid #ccc; border-collapse: collapse">
			<tbody>
				<tr valign="top">
					<th></th>
					<th><?php _e('Title','haetshopstyling'); ?></th>
					<th><?php _e('Field','haetshopstyling'); ?></th>
				</tr>
				<?php for($col=1;$col<=10;$col++): ?>
				<tr valign="top">
					 <th><?php echo __('Column','haetshopstyling')." ".$col; ?></th>
					 <td><input type="text" class="multilanguage-input" name="columntitle[<?php echo $col; ?>]" id="title_col<?php echo $col; ?>" value="<?php echo $options["columntitle"][$col];?>"></td>
					 <td><?php echo $this->productsFieldSelect('columnfield['.$col.']',$options["columnfield"][$col]); ?></td>
				</tr>
				<?php endfor; ?>
			</tbody>
		</table>
		<p class="description">
			<strong>*</strong> SKU is only visible in the transaction report and wont be sent to the customer.<br/> 
			<strong>**</strong> The download link will be visible after the payment has been accepted, but it is not visible in the PDF invoice.<br/>
		</p>
		<h3><?php _e('Personalization','haetshopstyling'); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="haetshopstylingpersonalizationbelow"><?php _e('Show Personalization in seperate row','haetshopstyling'); ?></label></th>
					<td>
						<select  id="haetshopstylingpersonalizationbelow" name="haetshopstylingpersonalizationbelow">
						  <option value="enable" <?php echo ($options['personalizationbelow']=="enable"?"selected":""); ?>><?php _e('enable','haetshopstyling'); ?></option>
						  <option value="disable" <?php echo ($options['personalizationbelow']=="disable"?"selected":""); ?>><?php _e('disable','haetshopstyling'); ?></option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="haetshopstylingpersonalizationfrom"><?php _e('from column to column','haetshopstyling'); ?></label></th>
					<td>
						<select  id="haetshopstylingpersonalizationfrom" name="haetshopstylingpersonalizationfrom">
						  <?php for($i=1;$i<=10;$i++)
							echo '<option value="'.$i.'" '.($options['personalizationfrom']==$i?"selected":"").'>'.$i.'</option>';
						  ?>
						</select>
						<select  id="haetshopstylingpersonalizationto" name="haetshopstylingpersonalizationto">
						  <?php for($i=1;$i<=10;$i++)
							echo '<option value="'.$i.'" '.($options['personalizationto']==$i?"selected":"").'>'.$i.'</option>';
						  ?>
						</select>

					</td>
				</tr>
			</tbody>
		</table> 
<?php 
			break;
			case 'mailcontent' :
?>

			<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">

				<h2><?php _e('Email Content - Payment Successful','haetshopstyling'); ?></h2>
				<table class="form-table">
				  <tbody>
					  <tr valign="top">
						  <th scope="row"><label for="haetshopstylingsubject_payment_successful"><?php _e('Email subject','haetshopstyling'); ?></label></th>
						  <td>
							  <input type="text" class="multilanguage-input regular-text" id="haetshopstylingsubject_payment_successful" name="haetshopstylingsubject_payment_successful" value="<?php echo $options['subject_payment_successful']; ?>">
						  </td>
					  </tr>
				  </tbody>
				</table>               
				<?php 

				wp_editor(stripslashes(str_replace('\\&quot;','',$options['body_payment_successful'])),'haetshopstylingbody_payment_successful',array(
					'media_buttons'=>false,
					'tinymce' => array(
							'theme_advanced_buttons3' => 'invoicefields,checkoutformfields',
							'remove_linebreaks' => false
						)
					)
					);
				?>
				
				<hr/>
				
				<h2><?php _e('Email Content - Payment Incomplete (or manual payment)','haetshopstyling'); ?></h2>
				<table class="form-table">
				  <tbody>
					  <tr valign="top">
						  <th scope="row"><label for="haetshopstylingsubject_payment_incomplete"><?php _e('Email subject','haetshopstyling'); ?></label></th>
						  <td>
							  <input type="text" class="multilanguage-input regular-text" id="haetshopstylingsubject_payment_incomplete" name="haetshopstylingsubject_payment_incomplete" value="<?php echo $options['subject_payment_incomplete']; ?>">
						  </td>
					  </tr>
				  </tbody>
				</table>               
				<?php 

				wp_editor(stripslashes(str_replace('\\&quot;','',$options['body_payment_incomplete'])),'haetshopstylingbody_payment_incomplete',array(
					'media_buttons'=>false,
					'tinymce' => array(
							'theme_advanced_buttons3' => 'invoicefields,checkoutformfields',
							'remove_linebreaks' => false
						)
					)
					);
				?>
			  
				<hr/>
				
				<h2><?php _e('Email Content - Payment failed','haetshopstyling'); ?></h2>
				<table class="form-table">
				  <tbody>
					  <tr valign="top">
						  <th scope="row"><label for="haetshopstylingsubject_payment_failed"><?php _e('Email subject','haetshopstyling'); ?></label></th>
						  <td>
							  <input type="text" class="multilanguage-input regular-text" id="haetshopstylingsubject_payment_failed" name="haetshopstylingsubject_payment_failed" value="<?php echo $options['subject_payment_failed']; ?>">
						  </td>
					  </tr>
				  </tbody>
				</table>               
				 <?php 
				 
				 wp_editor(stripslashes(str_replace('\\&quot;','',$options['body_payment_failed'])),'haetshopstylingbody_payment_failed',array(
						'media_buttons'=>false,
						'tinymce' => array(
								'theme_advanced_buttons3' => 'invoicefields,checkoutformfields',
								'remove_linebreaks' => false
							)
						)
					 );
				 ?>  
				
				<hr/>
				
				<h2><?php _e('Email Content - Track and Trace Email','haetshopstyling'); ?></h2>
				<table class="form-table">
				  <tbody>
					  <tr valign="top">
						  <th scope="row"><label for="haetshopstylingsubject_tracking"><?php _e('Email subject','haetshopstyling'); ?></label></th>
						  <td>
							  <input type="text" class="multilanguage-input regular-text" id="haetshopstylingsubject_tracking" name="haetshopstylingsubject_tracking" value="<?php echo $options['subject_tracking']; ?>">
						  </td>
					  </tr>
				  </tbody>
				</table>               
				 <?php 
				 
				 wp_editor(stripslashes(str_replace('\\&quot;','',$options['body_tracking'])),'haetshopstylingbody_tracking',array(
						'media_buttons'=>false,
						'tinymce' => array(
								'theme_advanced_buttons3' => 'invoicefields,checkoutformfields',
								'remove_linebreaks' => false
							)
						)
					 );
				 ?>  
				 

				<hr/>
				
				<h2><?php _e('Email Content - Admin Transaction Report','haetshopstyling'); ?></h2>
				<table class="form-table">
				  <tbody>
					  <tr valign="top">
						  <th scope="row"><label for="haetshopstylingsubject_adminreport"><?php _e('Email subject','haetshopstyling'); ?></label></th>
						  <td>
							  <input type="text" class="multilanguage-input regular-text" id="haetshopstylingsubject_adminreport" name="haetshopstylingsubject_adminreport" value="<?php echo $options['subject_adminreport']; ?>">
						  </td>
					  </tr>
				  </tbody>
				</table>               
				 <?php 
				 
				 wp_editor(stripslashes(str_replace('\\&quot;','',$options['body_adminreport'])),'haetshopstylingbody_adminreport',array(
						'media_buttons'=>false,
						'tinymce' => array(
								'theme_advanced_buttons3' => 'invoicefields,checkoutformfields',
								'remove_linebreaks' => false
							)
						)
					 );
				 ?>  
<?php 
			break;
			case 'invoicecss':
				if(!$this->isAllowed('invoice')){
					?>
					<div class="upgrademessage">
						<div style="float:left">
							<h4><?php _e('You have not unlocked this feature yet','haetshopstyling'); ?></h4>
							<p><?php _e('You can edit and even preview the invoice but it will not be published to your customers.','haetshopstyling'); ?></p>
							<p><a href="http://wpshopstyling.com/" target="_blank"><?php _e('read more &raquo;','haetshopstyling'); ?></a></p>
						</div>
						<div style="margin-left:50px; float:left">
							<h4><?php _e('Get this feature!','haetshopstyling'); ?></h4>
							<a href="http://wpshopstyling.com/download/" target="_blank" class="button"><?php _e('Upgrade Now &raquo;','haetshopstyling'); ?></a>
						</div>
						
						<div style="clear:both"> </div>
					</div>
					<?php
				}
?>

	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
				
	<h2><?php _e('Style your invoice','haetshopstyling'); ?></h2>
	<textarea rows="30" cols="40" class="widefat" id="haetshopstylingcss" name="haetshopstylingcss" style="font-family:'Courier New'"><?php echo stripslashes(str_replace('\\&quot;','',$options['css'])); ?></textarea>

<?php 
			break;
			case 'previewinvoice':
?>
	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">


	<h3><?php _e('generating pdf invoice...','haetshopstyling'); ?></h3>
	<a href="#" onclick="window.history.back()"><?php _e('back','haetshopstyling'); ?></a>
	<?php 
		echo '<!--';
		$this->previewInvoice(); 
		echo '-->';
		echo '<script>window.location.href="'.HAET_SHOP_STYLING_URL.'includes/download.php?filename=preview.pdf";</script>';
	?>
	

<?php 
			break;            
			case 'mailtemplate':

				if(!is_plugin_active( 'wp-html-mail/wp-html-mail.php' )):?>
					<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
						<table class="form-table">
							<tbody>
								<tr valign="top">
									<th scope="row"><label for="haetshopstylingshopsendername"><?php _e('Shop email sender name','haetshopstyling'); ?></label></th>
									<td>
										<input type="text" class="regular-text" id="haetshopstylingshopsendername" name="haetshopstylingshopsendername" value="<?php echo esc_attr( get_option( 'return_name' ) ); ?>">
										<span class="description"><?php _e('Sender name for mails from your store','haetshopstyling'); ?></span>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><label for="haetshopstylingshopfromaddress"><?php _e('From adress','haetshopstyling'); ?></label></th>
									<td>
										<input type="text" class="regular-text" id="haetshopstylingshopfromaddress" name="haetshopstylingshopfromaddress" value="<?php echo esc_attr( get_option( 'return_email' ) ); ?>">
										<span class="description"><?php _e('From address for mails from your store','haetshopstyling'); ?></span>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><label for="haetshopstylingcustomsender"><?php _e('Use custom sender for non wpsc mails','haetshopstyling'); ?></label></th>
									<td>
										<select  id="haetshopstylingcustomsender" name="haetshopstylingcustomsender">
										  <option value="enable" <?php echo ($options['customsender']=="enable"?"selected":""); ?>><?php _e('enable','haetshopstyling'); ?></option>
										  <option value="disable" <?php echo ($options['customsender']=="disable"?"selected":""); ?>><?php _e('disable','haetshopstyling'); ?></option>
										</select>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><label for="haetshopstylingsendername"><?php _e('Email sender name','haetshopstyling'); ?></label></th>
									<td>
										<input type="text" class="regular-text" id="haetshopstylingsendername" name="haetshopstylingsendername" value="<?php echo get_option('haet_mail_from_name'); ?>">
										<span class="description"><?php _e('Sender name for mails outside your store','haetshopstyling'); ?></span>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><label for="haetshopstylingfromaddress"><?php _e('From adress','haetshopstyling'); ?></label></th>
									<td>
										<input type="text" class="regular-text" id="haetshopstylingfromaddress" name="haetshopstylingfromaddress" value="<?php echo get_option('haet_mail_from'); ?>">
										<span class="description"><?php _e('From address for mails outside your store','haetshopstyling'); ?></span>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><label for="haetshopstylingstylenonwpscmails"><?php _e('Apply HTML template to non wpsc mails','haetshopstyling'); ?></label></th>
									<td>
										<select  id="haetshopstylingstylenonwpscmails" name="haetshopstylingstylenonwpscmails">
										  <option value="enable" <?php echo ($options['stylenonwpscmails']=="enable"?"selected":""); ?>><?php _e('enable','haetshopstyling'); ?></option>
										  <option value="disable" <?php echo ($options['stylenonwpscmails']=="disable"?"selected":""); ?>><?php _e('disable','haetshopstyling'); ?></option>
										</select>
									</td>
								</tr>
							</tbody>
						</table>

					<h2><?php _e('Global HTML Mail Template','haetshopstyling'); ?></h2>
					<p><?php _e('Enter your custom HTML code here. Use the placeholder {#mailcontent#} where you want your content to show up. You can also show the subject in the email body using the placeholder {#mailsubject#}','haetshopstyling'); ?></p>
					<textarea rows="30" cols="40" class="widefat" id="haetshopstylingmailtemplate" name="haetshopstylingmailtemplate" style="font-family:'Courier New'"><?php echo stripslashes(str_replace('\\&quot;','',$options['mailtemplate'])); ?></textarea>
					<br/><br/><a id="previewmail" class="button" href='#' ><?php _e('preview Email template','haetshopstyling'); ?></a><br/><br/>
					<iframe id="mailtemplatepreview" style="width:800px; height:480px; border:1px solid #ccc;" ></iframe>
					<p>
						<?php _e('you can find a few more templates here:','haetshopstyling'); ?>
						<a href="http://wpshopstyling.com/wp-e-commerce-html-mail-templates/wpsc-html-mail-templates/" target="_blank">http://wpshopstyling.com/wp-e-commerce-html-mail-templates/wpsc-html-mail-templates/</a>
					</p>
					<script>
						jQuery("#previewmail").click(function(){
							jQuery("#mailtemplatepreview").contents().find("html").html(jQuery("#haetshopstylingmailtemplate").val()); 
							return false;    
						});
					</script>
				<?php else: ?> 
					<p class="description"><br><?php _e('Please configure your mail template on the "Email template" settings page.','haetshopstyling'); ?>
				<?php endif; 
			break;        
			case 'resultspage' :
				if(!$this->isAllowed('resultspage')){
					?>
					<div class="upgrademessage">
						<div style="float:left">
							<h4><?php _e('You have not unlocked this feature yet','haetshopstyling'); ?></h4>
							<p><?php _e('You can edit and even preview the invoice but it will not be published to your customers.','haetshopstyling'); ?></p>
							<p><a href="http://wpshopstyling.com/" target="_blank"><?php _e('read more &raquo;','haetshopstyling'); ?></a></p>
						</div>
						<div style="margin-left:50px; float:left">
							<h4><?php _e('Get this feature!','haetshopstyling'); ?></h4>
							<a href="http://wpshopstyling.com/download/" target="_blank" class="button"><?php _e('Upgrade Now &raquo;','haetshopstyling'); ?></a>
						</div>
						
						<div style="clear:both"> </div>
					</div>
					<?php
				}
?>
			<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
				<h2><?php _e('Results Page - payment successful','haetshopstyling'); ?></h2>
								
				<?php 
						
				wp_editor(stripslashes(str_replace('\\&quot;','',$options['resultspage_successful'])),'resultspage_successful',array(
					'media_buttons'=>true,
					'tinymce' => array(
							'theme_advanced_buttons3' => 'invoicefields,checkoutformfields',
						)
					)
				);
				
				?>
				<h2><?php _e('Results Page - payment incomplete (or manual payment)','haetshopstyling'); ?></h2>
				<?php 
				 
				wp_editor(stripslashes(str_replace('\\&quot;','',$options['resultspage_incomplete'])),'resultspage_incomplete',array(
						'media_buttons'=>true,
						'tinymce' => array(
								'theme_advanced_buttons3' => 'invoicefields,checkoutformfields',
							)
						)
					);
				?>

				 <h2><?php _e('Results Page - payment failed','haetshopstyling'); ?></h2>
								
				<?php 
				 
				wp_editor(stripslashes(str_replace('\\&quot;','',$options['resultspage_failed'])),'resultspage_failed',array(
						'media_buttons'=>true,
						'tinymce' => array(
								'theme_advanced_buttons3' => 'invoicefields,checkoutformfields',
							)
						)
					 );
				?>
<?php 
			break;        
			
}//switch
			
?>
		
		<?php if(is_plugin_active('qtranslate/qtranslate.php')): ?>
			<p class="description">
				<?php
				_e('qtranslate was detected on your store. You can use qtranslate shortcodes in all of the shop styling editors. <br>e.g. &#91;:en&#93;english text&#91;:de&#93;german text&#91;:nl&#93;dutch text&#91;:es&#93;spanish text...','haetshopstyling');
				if(is_plugin_active('qtranslate-extended/qtranslate-extended.php')){
					echo '<br/>';
					_e('Please also install the free plugin ','haetshopstyling');
					echo '<a href="http://wordpress.org/extend/plugins/qtranslate-extended/" target="_blank">qtranslate-extended</a>';
				}
				?>
			</p> 
		<?php endif;?>
		
		<div class="submit">
			<input type="submit" name="update_haetshopstylingSettings" class="button-primary" value="<?php _e('Update Settings', 'haetshopstyling') ?>" />
		</div>
	</form>
</div>

