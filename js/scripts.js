// JavaScript Document

$(document).ready(function  () {
	
	$(".navlinks li").find("a").each(function() {   
	    if (this.href == window.location.href) {
        	$(this).addClass("selected");
    	}
	});
	
   $('#pagewrap').hide().fadeIn(1000);
   
   //This code block is for the accordion lists on the faq page - refactored
	function showInfo () {
		$(this).next("div").slideToggle("slow")
	  	.siblings("div:visible").slideUp("slow");
	  	$(this).toggleClass("selected");
	  	$(this).siblings("h3").removeClass("selected");
	  	return false;
	};
	
	
	//$("#questions").find("h3:first").addClass("selected");
	//	$("#questions").find("div:not(:first)").hide();
	//	$("#questions h3").click(showInfo);


	$("#menu").on('click', function () {
		//alert('menu clicked');
	$(this).siblings('ul').slideToggle("slow").toggleClass("open")
	return false;
	
	});
	
			$(window).resize(function(){
			if(window.innerWidth > 520) {
				//alert('window resized');
				$("#menu").siblings('ul').removeAttr("style");
			}
		});
	
    //contact form validation
    
    var errors = false;

    $("input:text, textarea").each(function() {

        $(this).focus(function() {
            if ($('.my_error:visible')) {
                $(this).removeClass('box-error');
                $(this).next('span').removeClass('active');
                errors = false;
            }
        });
    });

    function validateemail(inputvalue) {

        var email = inputvalue.val();
        var filter = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])$/;

        if (filter.test(email)) {
            return true;
        } else {
            return false;
        }
    }


    function handleErrors() {

        if (errors == true) {
            return false;
        }
    }


    $("form").submit(function(e) {

        e.preventDefault();

        var clikedForm = $(this); // Select Form
        var name = clikedForm.find('#name');
        var email = clikedForm.find('#email');
        var message = clikedForm.find('#message');
        var loading = clikedForm.parent().find('.loading');
        var NoErrors = clikedForm.parent().find('.no_error');

        if (name.length > 0) {
            if (name.val() == '') {
                name.addClass('box-error');
                name.next('span').addClass('active');
                errors = true
            }
        }

        if (email.length > 0) {
            if (!validateemail(email)) {
                email.addClass('box-error');
                email.next('span').addClass('active');
                errors = true
            }
        }

        if (message.length > 0) {
            if (message.val() == '') {
                message.addClass('box-error');
                message.next('span').addClass('active');
                errors = true
            }
        }
        handleErrors();

        if (errors == false) {
            //getvalues();
            var data = clikedForm.serialize();//grabs all the form fields data
            var url = clikedForm.attr('action');
            loading.addClass('active');


            $.ajax({

                type:'post',
                url: url,
                data: data,

                success: function(response){
                    //alert(data);
                    loading.removeClass('active');
                    NoErrors.addClass('active');


                }   
            })
        }
    });
    //end form validation

});