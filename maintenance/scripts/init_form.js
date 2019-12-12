var options = { 
	beforeSubmit:  validate,  // pre-submit callback 
	success:       showResponse,  // post-submit callback 
	resetForm: true        // reset the form after successful submit 
}; 
				
$('#form_newsletter').ajaxForm(options); 
				
function showResponse(responseText, statusText){
	$('#form_newsletter').slideUp({ opacity: "hide" }, "normal")
	$('#success').slideDown({ opacity: "show" }, "slow")
	
}
				
function validate(formData, jqForm, options) {
	$("p.error").slideUp({ opacity: "hide" }, "fast");
			 
	var emailValue = $('input[name=email]').fieldValue();
	
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	var correct = true;
	

	if (!emailValue[0]) {
		$("p.error.wrong_email").slideDown({ opacity: "show" }, "slow");
		correct = false;
	} else if(!emailReg.test(emailValue[0])) {
		$("p.error.wrong_email").slideDown({ opacity: "show" }, "slow");
		correct = false;
	}
	

	if (!correct) {return false;}
} 	
								 