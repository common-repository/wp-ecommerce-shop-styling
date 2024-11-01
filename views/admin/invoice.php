<?php

	
	
	$body=  __(stripslashes(str_replace('\\&quot;','',$options['template']))) ;
	$footer=  __(stripslashes(str_replace('\\&quot;','',$options['footer']))) ;


	$url = $_SERVER['HTTP_HOST'];
	$url1 = "http://" . $_SERVER['HTTP_HOST'];
	$url2 = "https://" . $_SERVER['HTTP_HOST'];
	$rel_address = str_replace($url2, '', str_replace($url1, '',HAET_SHOP_STYLING_URL) );
	$base_path = str_replace($rel_address,'',HAET_SHOP_STYLING_PATH);

	$body = preg_replace('#\<img(.*)src=\".*'.$url.'(.*)\"(.*)\>#Uis', '<img$1src="'.$base_path.'$2"$3\>', $body);
	$body = preg_replace('#\<img(.*)src=\'.*'.$url.'(.*)\'(.*)\>#Uis', '<img$1src="'.$base_path.'$2"$3\>', $body);
	$footer = preg_replace('#\<img(.*)src=\".*'.$url.'(.*)\"(.*)\>#Uis', '<img$1src="'.$base_path.'$2"$3\>', $footer);
	$footer = preg_replace('#\<img(.*)src=\'.*'.$url.'(.*)\'(.*)\>#Uis', '<img$1src="'.$base_path.'$2"$3\>', $footer);
	
	foreach ($params AS $param){
		if( is_array($param) && array_key_exists('unique_name', $param) && array_key_exists('value', $param)){
			$body = str_replace('{'.$param["unique_name"].'}', $param['value'], $body);
			$footer = str_replace('{'.$param["unique_name"].'}', $param['value'], $footer);
		}
	}
	
	
	//remove "downloads" column in PDF
	$body = preg_replace('#\<t[d|h] class=\'download\'>.*</t[d|h]>#Uis', '', $body);
	
	$html='<html><head>
			<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
			<style type="text/css">
				'.stripslashes($options['css']).'
			</style>
			</head>
			<body>
				<table id="content-table" width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td id="invoice-content">
							'.$body.'
						</td>
					</tr>
					<tr>
						<td id="invoice-footer">
							'.$footer.'
						</td>
					</tr>
				</table>
			</body>
		  </html>';
	
	
   