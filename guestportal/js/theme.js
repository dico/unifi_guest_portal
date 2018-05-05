function login_form(form_type)
{
	if(form_type == 'mobile') {
		$('#login-selection').hide();
		$('#login-mobile').fadeIn();
	}

	/*else if(form_type == 'employee') {
		$('#login-selection').hide();
		$('#login-employee').fadeIn();
	}*/

	/*else if(form_type == 'school') {
		$('#login-selection').hide();
		$('#login-school').fadeIn();
	}*/

	else if(form_type == 'room') {
		$('#login-selection').hide();
		$('#login-room').fadeIn();
	}

	else if(form_type == 'select') {
		$('.login-form-container').hide();
		$('#login-selection').fadeIn();
	}
}



$(document).ready(function() {

	$(".terms-btn").click(function() {
		$(".terms").slideToggle();
	});
});