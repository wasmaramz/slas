/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 * Check "https://jqueryvalidation.org/" for jquery validation
 * Check "public/assets/js/page/modules-sweetalert.js" for sweet alert pop up
 */

"use strict";

function confirm_logout() {
	swal({
		title: 'Logout!',
		text: 'Are you sure you want to logout?',
		icon: 'warning',
		buttons: ["Cancel", "Logout"],
		dangerMode: true,
	})
	.then((willLogout) => {
		if (willLogout) {
			window.location.href = "/logout";
		}
	});
} 

// modify default setting for jquery validation
$.validator.setDefaults({
	errorElement: "div",
    errorPlacement: function (error, element) {
        // Add the `invalid-feedback` class to the error element
        error.addClass("invalid-feedback");
        if ($($(element).parent()).hasClass('input-group')) {
            error.insertAfter($(element).parent());
        }
        else {
            error.insertAfter(element);
        }
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("is-invalid");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass("is-invalid");
    }
});

// additional custom for jquery validation method
$.validator.addMethod("regex", function(value, element, regexp){
	var reg = new RegExp(regexp);
	return this.optional(element) || reg.test(value);
});

// auto sweet alert pop up after form submitted using ajax
$(document).on('ajaxSuccess', function(event, xhr, settings, data){
	if(settings.dataType == "json"){
		if((typeof data === 'object' && data !== null) && 'success' in data && 'msg' in data){
			if(data.success){
				swal('Success!!', data.msg, 'success');
			}
			else{
				swal('Failed!', data.msg, 'warning');
			}
		}
		else{
			swal('Error!', data, 'error');
		}
	}
});
