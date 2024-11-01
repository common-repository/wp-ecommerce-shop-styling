jQuery(document).ready(function($) {
	function orderNumberSystem(){
		var number_system = $('#haetshopstylinginvoicenumbersystem').val();
		if(number_system=='ordernumber'){
			$('#haetshopstylinginvoicenumber').attr('disabled','disabled').addClass('disabled');
			$('#haetshopstylingsendpdfafterpayment option').removeAttr('disabled');
		}else if(number_system=='manual'){
			$('#haetshopstylinginvoicenumber').attr('disabled','disabled').addClass('disabled');
			$("#haetshopstylingsendpdfafterpayment option").filter(function() { return $(this).text() == 'enable'; }).attr('selected', true);
			$("#haetshopstylingsendpdfafterpayment option").filter(function() { return $(this).text() == 'disable'; }).attr('disabled', 'disabled');
			$('#haetshopstylingsendpdfafterpayment').attr('disabled','disabled').addClass('disabled');
		}else if(number_system=='invoicenumber'){
			$('#haetshopstylinginvoicenumber').removeAttr('disabled').removeClass('disabled');
			$('#haetshopstylingsendpdfafterpayment option').removeAttr('disabled');
		}

	}

	orderNumberSystem();
	$('#haetshopstylinginvoicenumbersystem').change(function(){ orderNumberSystem(); });

	$('#haetshopstylingfooterleftcolor,#haetshopstylingfootercentercolor,#haetshopstylingfooterrightcolor').wpColorPicker();

});