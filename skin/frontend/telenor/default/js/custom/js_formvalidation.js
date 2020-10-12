var formSubmit = false;
function requiredValidation(value) {
    if (value === "")
        return false;
    return true;
}

function unival(e) {
    return e.charCode ? e.charCode : e.keyCode;
}

function numbersonly(e) {
    var unicode = e.charCode ? e.charCode : e.keyCode;
    if (unicode !== 8 && unicode !== 9 && unicode !== 13 && unicode !== 27 && unicode !== 37 && unicode !== 38 && unicode !== 39 && unicode !== 40) { //if the key isn't the backspace key (which we should allow)
        if (unicode < 48 || unicode > 57) //if not a number
            return false; //disable key press
    }
    //console.log(unicode);
    return true;
}

function dataLength(value, size) {
    if (value.length >= size) {
        return false;
    }
    return true;
}

function emailValidation(value) {
    if(value.length == 0){
        return true;
    }
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(value);
}

function stopChr(chr) {
    if (chr !== 32 && chr !== 96 && chr !== 126 && chr !== 95 && chr !== 45 && chr !== 61 && chr !== 43 && chr !== 34 &&  chr !== 58 && chr !== 59 && chr !== 44 && chr !== 60 && chr !== 62 && chr !== 33 && chr !== 64 && chr !== 35 && chr !== 36 && chr !== 94 && chr !== 42 && chr !== 41 && chr !== 123 && chr !== 125 && chr !== 91 && chr !== 93 && chr !== 92 && chr !== 124 && chr !== 47 && chr !== 63) {
        return false;
    }

    return true;
}



function showError(element, msg) {
    jQuery(element).addClass('validation-failed');
    jQuery(element).closest('.center_box_row').children('.validation-advice').children('span').text(msg);
    jQuery(element).closest('.center_box_row').children('.validation-advice').show();
}

function showSelectError(element, msg) {
    jQuery(element).addClass('validation-failed');
    jQuery(element).closest('.ddOutOfVision').siblings('.dd').find(".ddTitleText").addClass('validation-failed');
    jQuery(element).closest('.center_box_row').children('.validation-advice').children('span').text(msg);
    jQuery(element).closest('.center_box_row').children('.validation-advice').show();
}

function removeError(element) {
    jQuery(element).removeClass('validation-failed');
    jQuery(element).closest('.center_box_row').children('.validation-advice').children('span').text("");
    jQuery(element).closest('.center_box_row').children('.validation-advice').hide();
}

function removeSelectError(element) {
    jQuery(element).removeClass('validation-failed');
    jQuery(element).closest('.center_box_row').children('.validation-advice').children('span').text("");
    jQuery(element).closest('.center_box_row').children('.validation-advice').hide();
}

jQuery(document).ready(function () {
    var cnicA1 = false;
    var cnicA2 = false;
    var cnicA3 = false;
    var cnicf1 = false;
    var cnicf2 = false;
    var cnicf3 = false;

    jQuery('.validation-advice').hide();
    jQuery('.validation-advice').each(function () {
        var tempVar = jQuery(this).children('span').text();
        jQuery.trim(tempVar);
        if (tempVar !== "") {
            jQuery(this).show();
        }
    });

    jQuery('.user_name').live("keypress", function (e) {
        var unicode = unival(e);
        if (!stopChr(unicode)) {
            return true;
        } else {
            return false;
        }
    });

    /* cnic jumping */


    jQuery('.cnic_1').live("keyup", function () {
        var value = jQuery(this).val();
        var length = value.length;
        if (length === 5 && !cnicf1) {
            cnicf1 = true;
            jQuery(this).siblings('.cnic_2').focus();
        }
    });

    jQuery('.cnic_1').live("blur", function () {
        cnicA1 = true;
    });
    jQuery('.cnic_2').live("blur", function () {
        cnicA2 = true;
    });
    jQuery('.cnic_3').live("blur", function () {
        cnicA3 = true;
    });

    jQuery('.cnic_2').keyup(function () {
        var value = jQuery(this).val();
        var length = value.length;
        if (length === 7 && !cnicf2) {
            cnicf2 = true;
            jQuery(this).siblings('.cnic_3').focus();
        }
    });

    jQuery('.cnic_3').keyup(function () {
        if (!cnicA2)
            cnicA2 = true;
    });

    /* cnic max size */
    function cnicMaxSize(element) {
        var tempClass = jQuery(element).attr('class');

        if (tempClass.indexOf('cnic_1') >= 0) {
            return 5;
        }
        if (tempClass.indexOf('cnic_2') >= 0) {
            return 7;
        }
        if (tempClass.indexOf('cnic_3') >= 0) {
            return 1;
        }
        return -1;
    }

    /* cnic validation end */
    function cnicValidation(element1, element2, element3) {
        if (cnicA1 && cnicA2 && cnicA3) {
            if (
                    fieldFilled(!jQuery(element1).val(), 5) ||
                    !fieldFilled(jQuery(element2).val(), 7) ||
                    !fieldFilled(jQuery(element3).val(), 1)
                    ) {

                return false;
            }
        }
        return true;
    }

    /* re-email */
    function repeatEmail(element) {
        var email = jQuery(element).closest('form').find('#email_address').val();
        var reEmail = jQuery(element).val();
        if (email === reEmail) {
            return true;
        }
        return false;
    }

    function mobileNumberValidation(value) {
        var maxSize = 11;
        if (value.length >= 1 && value.charAt(0) === "0") {
            maxSize = 11;

            var patt = /[^0-9]/g;
            if (patt.test(value)) {
                maxSize = -1;
            }

        } else if (value.length > 1) {
            maxSize = -1;
        }

        return maxSize;
    }

    function fieldSizeValidation(value, size) {
        if (value.length >= size)
            return false;
        return true;
    }

    function fieldFilled(value, size) {
        if (value === undefined)
            return false;
        if (value.length === size)
            return true;
        return false;
    }

    function containNumber(value) {
        var intRegex = new RegExp("[0-9]");
        return intRegex.test(value);
    }
    function containSmall(value) {
        var intRegex = new RegExp("[a-z]");
        return intRegex.test(value);
    }
    function containCaps(value) {
        var intRegex = new RegExp("[A-Z]");
        return intRegex.test(value);
    }
    function containChars(value) {
        var intRegex = new RegExp(/[-!$%^&*()_+|~=`{}\[\]:\";\'<>?,.\/]/);
        return intRegex.test(value);
    }

    function pwdStrength(value) {
        // Must have capital letter, numbers and lowercase letters
        var strength = 0;
        if (value.length >= 8) {
            if (containNumber(value)) {
                strength = strength + 1;
            }
            if (containSmall(value)) {
                strength = strength + 1;
            }
            if (containCaps(value)) {
                strength = strength + 1;
            }
            if (containChars(value)) {
                strength = strength + 1;
            }
        }
        if (strength < 3) {
            return "negative";
        } else if (strength === 3) {
            return "medium";
        } else {
            return "strong";
        }


    }

    function repeatPwd(element) {
        var pwd = jQuery(element).closest('form').find("#password").val();
        var rePwd = jQuery(element).val();
        if (pwd === rePwd)
            return true;
        return false;
    }

    /* num only validation */
    jQuery('.num_only').keypress(function (e) {
        var passed = numbersonly(e);
        var element = jQuery(this);
        var classes = jQuery(this).attr('class');
        var value = jQuery(this).val();
       
        if (!passed) {
            msg = "Only numbers allowed";
            showError(element, msg);
        }


        if (passed) {
            removeError(element);
        }

        var unicode = e.charCode ? e.charCode : e.keyCode;

        if (unicode !== 8 && unicode !== 9 && unicode !== 13 && unicode !== 27 && unicode !== 37 && unicode !== 38 && unicode !== 39 && unicode !== 40) {
            return passed;
        } else {
            return true;
        }

    });
    /* end num only validation */

    /* validation for input drop down */
    jQuery(".dropdown_input").change(function () {
        var passed = true;
    });
    /* required field validation */
    jQuery("input:text").blur(function () {

        var passed = true;
        var classes = jQuery(this).attr('class');
        var value = jQuery(this).val();
        var element = jQuery(this);
        var msg = "";
        if (classes.indexOf("required_entry") >= 0) {
            passed = requiredValidation(value);
            if (!passed) {
                msg = "This field is required";
                showError(element, msg);
            }
        }

        

        if (passed && classes.indexOf('mob_no') >= 0) {

            var maxSize = mobileNumberValidation(value);
            if (maxSize < 0) {
                passed = false;
                msg = "Incorrect mobile number";
                showError(element, msg);
            } else {
                passed = fieldFilled(value, maxSize);
                if (!passed) {
                    msg = "Incorrect mobile number";
                    showError(element, msg);
                }
            }
        }

        if (passed && classes.indexOf('cnic') >= 0) {

            var parent = jQuery(element).closest('.cnic');
            var element1 = jQuery(parent).find('.cnic_1');
            var element2 = jQuery(parent).find('.cnic_2');
            var element3 = jQuery(parent).find('.cnic_3');

            passed = cnicValidation(element1, element2, element3);
            if (!passed) {
                msg = "Incorrect CNIC number";
                showError(element1, msg);
                showError(element2, msg);
                showError(element3, msg);
            } else {
                removeError(element1);
                removeError(element2);
                removeError(element3);
            }
        }

        if (passed) {
            removeError(element);
        }
    });

    jQuery('input[type=email]').blur(function () {
        var passed = true;
        var classes = jQuery(this).attr('class');
        var value = jQuery(this).val();
        var element = jQuery(this);
        var msg = "";
        if (classes.indexOf('required_entry') >= 0) {
            passed = requiredValidation(value);
            if (!passed) {
                msg = "This field is required";
                showError(element, msg);
            }
        }

        if (passed) {
            passed = emailValidation(value);
            if (!passed) {
                msg = "Invalid email address";
                showError(element, msg);
            }
        }

        if (passed && jQuery(this).attr('id') === "repeat_email_address") {
            passed = repeatEmail(element);
            if (!passed) {
                msg = "Email address does not match";
                showError(element, msg);
            }
        }

        if (passed) {
            removeError(element);
        }
    });

    jQuery('.forgot_form #password').blur(function () {
        var passed = true;
        var value = jQuery(this).val();
        var element = jQuery(this);
        
        var strength = pwdStrength(value);
        if (strength === "negative") {
            passed = false;
            msg = "Password must be 8 characters long, contain UPPERCASE, LOWERCASE letters and numbers 0-9.";
            jQuery("#weak").css("background-color", "#E43333").siblings().css("background-color", "#666");
            jQuery("#weak").html("Weak").siblings().html("");
        } else if (strength == "weak") {
            passed = false;
            msg = "Password must be 8 characters long, contain UPPERCASE, LOWERCASE letters and numbers 0-9.";
            jQuery("#weak").css("background-color", "#E43333").siblings().css("background-color", "#666");
            jQuery("#weak").html("Weak").siblings().html("");
        } else if (strength === "medium") {
            passed = true;
            msg = "medium password";
            jQuery("#medium").html("Medium").siblings().html("");
            jQuery("#medium").css("background-color", "#fdbc11").siblings().css("background-color", "#666");

        } else if (strength === "strong") {
            passed = true;
            msg = "strong password";
            jQuery("#strong").html("Strong").siblings().html("");
            jQuery("#strong").css("background-color", "#cfde00").siblings().css("background-color", "#666");
        }
        if (passed) {
            removeError(element);
        } else {
            showError(element, msg);
        }
    });

    jQuery('.regiser_form #password').blur(function () {
        var passed = true;
        var value = jQuery(this).val();
        var element = jQuery(this);
        
        var strength = pwdStrength(value);
        if (strength === "negative") {
            passed = false;
            msg = "Password must be 8 characters long, contain UPPERCASE, LOWERCASE letters and numbers 0-9.";
            jQuery("#weak").css("background-color", "#E43333").siblings().css("background-color", "#666");
            jQuery("#weak").html("Weak").siblings().html("");
        } else if (strength == "weak") {
            passed = false;
            msg = "Password must be 8 characters long, contain UPPERCASE, LOWERCASE letters and numbers 0-9.";
            jQuery("#weak").css("background-color", "#E43333").siblings().css("background-color", "#666");
            jQuery("#weak").html("Weak").siblings().html("");
        } else if (strength === "medium") {
            passed = true;
            msg = "medium password";
            jQuery("#medium").html("Medium").siblings().html("");
            jQuery("#medium").css("background-color", "#fdbc11").siblings().css("background-color", "#666");

        } else if (strength === "strong") {
            passed = true;
            msg = "strong password";
            jQuery("#strong").html("Strong").siblings().html("");
            jQuery("#strong").css("background-color", "#cfde00").siblings().css("background-color", "#666");
        }
        if (passed) {
            removeError(element);
        } else {
            showError(element, msg);
        }
    });



    jQuery('.register_form #password').on("keyup", function () {
        var passed = true;
        var value = jQuery(this).val();
        var element = jQuery(this);
        var msg = "";

        if (passed && jQuery(this).attr('id') === "password") {
            var strength = pwdStrength(value);
            if (strength === "negative") {
                passed = false;
                msg = "Password must be 8 characters long, contain UPPERCASE, LOWERCASE letters and numbers 0-9.";
                jQuery("#weak").css("background-color", "#E43333").siblings().css("background-color", "#666");
                jQuery("#weak").html("weak").siblings().html("");
            } else if (strength == "weak") {
                passed = false;
                msg = "Password must be 8 characters long, contain UPPERCASE, LOWERCASE letters and numbers 0-9.";
                jQuery("#weak").css("background-color", "#E43333").siblings().css("background-color", "#666");
                jQuery("#weak").html("Weak").siblings().html("");
            } else if (strength === "medium") {
                passed = true;
                msg = "medium password";
                jQuery("#medium").html("Medium").siblings().html("");
                jQuery("#medium").css("background-color", "#fdbc11").siblings().css("background-color", "#666");

            } else if (strength === "strong") {
                passed = true;
                msg = "strong password";
                jQuery("#strong").html("Strong").siblings().html("");
                jQuery("#strong").css("background-color", "#cfde00").siblings().css("background-color", "#666");
            }
        }
        if (passed) {
            removeError(element);
        } else {
            showError(element, msg);
        }

    });

    jQuery('.forgot_form #password').on("keyup", function () {
        var passed = true;
        var value = jQuery(this).val();
        var element = jQuery(this);
        var msg = "";

        if (passed && jQuery(this).attr('id') === "password") {
            var strength = pwdStrength(value);
            if (strength === "negative") {
                passed = false;
                msg = "Password must be 8 characters long, contain UPPERCASE, LOWERCASE letters and numbers 0-9.";
                jQuery("#weak").css("background-color", "#E43333").siblings().css("background-color", "#666");
                jQuery("#weak").html("weak").siblings().html("");
            } else if (strength == "weak") {
                passed = false;
                msg = "Password must be 8 characters long, contain UPPERCASE, LOWERCASE letters and numbers 0-9.";
                jQuery("#weak").css("background-color", "#E43333").siblings().css("background-color", "#666");
                jQuery("#weak").html("Weak").siblings().html("");
            } else if (strength === "medium") {
                passed = true;
                msg = "medium password";
                jQuery("#medium").html("Medium").siblings().html("");
                jQuery("#medium").css("background-color", "#fdbc11").siblings().css("background-color", "#666");

            } else if (strength === "strong") {
                passed = true;
                msg = "strong password";
                jQuery("#strong").html("Strong").siblings().html("");
                jQuery("#strong").css("background-color", "#cfde00").siblings().css("background-color", "#666");
            }
        }
        if (passed) {
            removeError(element);
        } else {
            showError(element, msg);
        }

    });

    jQuery('input[type=password]').blur(function () {
        var passed = true;
        var classes = jQuery(this).attr('class');
        var value = jQuery(this).val();
        var element = jQuery(this);
        var msg = "";

        if (classes.indexOf('required_entry') >= 0) {
            passed = requiredValidation(value);
            if (!passed) {
                msg = "This field is required";
                showError(element, msg);
            }
        }

        var formClass = jQuery(this).closest('form').attr('class');
        if (formClass.indexOf('login_form') < 0) {
            if (passed && jQuery(this).attr('id') === "password") {
                var strength = pwdStrength(value);
                if (strength === "negative") {
                    passed = false;
                    msg = "Password must be 8 characters long, contain UPPERCASE, LOWERCASE letters and numbers 0-9.";
                    jQuery("#weak").css("background-color", "#E43333").siblings().css("background-color", "#666");
                    jQuery("#weak").html("Weak");
                } else if (strength == "weak") {
                    passed = false;
                    msg = "Password must be 8 characters long, contain UPPERCASE, LOWERCASE letters and numbers 0-9.";
                    jQuery("#weak").css("background-color", "#E43333").siblings().css("background-color", "#666");
                    jQuery("#weak").html("Weak").siblings().html("");
                } else if (strength === "medium") {
                    passed = true;
                    msg = "medium password";
                    jQuery("#medium").html("Medium").siblings().html("");
                    jQuery("#medium").css("background-color", "#fdbc11").siblings().css("background-color", "#666");

                } else if (strength === "strong") {
                    passed = true;
                    msg = "strong password";
                    jQuery("#strong").html("Strong").siblings().html("");
                    jQuery("#strong").css("background-color", "#cfde00").siblings().css("background-color", "#666");
                }
            }

            if (passed) {
                removeError(element);
            } else {
                showError(element, msg);
            }

            if (passed && jQuery(this).attr('id') === "repeat_password") {
                passed = repeatPwd(element);
                if (!passed) {
                    msg = "Password does not match";
                    showError(element, msg);
                }
            }
        }

        if (passed) {
            removeError(element);
        }
    });

    jQuery("select").change(function () {
        var passed = true;
        var classes = jQuery(this).attr('class');
        var value = jQuery(this).val();
        var element = jQuery(this);
        var msg = "";
        if (classes.indexOf('required_entry') >= 0) {
            var tempPassed = requiredValidation(value);
            if (tempPassed) {
                removeSelectError(element);
            } else {
                passed = false;
                msg = "This field is required";
                showSelectError(element, msg);
            }
        }

        if (passed && (jQuery(this).attr("name") === "dd" || jQuery(this).attr("name") === "mm" || jQuery(this).attr("name") === "yyyy")) {
            var parent = jQuery(this).closest('.valid_date');
            var date = jQuery(parent).find('.select_day');
            var month = jQuery(parent).find('.select_month');
            var year = jQuery(parent).find('.select_year');

            var tempPassed = validDate(date, month, year);
            if (tempPassed) {
                removeSelectError(element);
            } else {
                passed = false;
                msg = "Date can't be " + date + " " + month + " " + year;
                showSelectError(element, msg);
            }
        }

    });



    jQuery('input[type=submit]').focus(function () {
        //jQuery(this).closest('form').submit();
        //return false;
    });

    jQuery('input[type=submit]').mousedown(function () {
        jQuery(this).closest('form').submit();
        return false;
    });

    jQuery("form").submit(function () {
        if (!formSubmit) {
            var attr = jQuery(this).attr('class');
            if (typeof attr !== typeof undefined && attr !== false) {
                var formClass = jQuery(this).attr("class");
                var passed = true;

                if (formClass.indexOf("login_form") < 0) {
                    if (jQuery(this).find('#password').length > 0) {
                        jQuery(this).find('#password').each(function (i) {
                            var value = jQuery(this).val();
                            var element = jQuery(this);
                            var msg = "password validation";
                            var strength = pwdStrength(value);
                            if (strength == "weak" || strength == "negative") {
                                var tempPassed = false;
                                msg = "Password must be 8 characters long, contain UPPERCASE, LOWERCASE letters and numbers 0-9.";
                            } else {
                                tempPassed = true;
                            }
                            if (!tempPassed) {
                                passed = false;
                                showError(element, msg);
                            } else {
                                removeError(element);
                            }
                        });
                    }
                    
                    if (jQuery(this).find('#repeat_password').length > 0) {
                        jQuery(this).find('#repeat_password').each(function (i) {
                            var value = jQuery(this).val();
                            var element = jQuery(this);
                            var msg = "Password does not match";
                            var tempPassed = repeatPwd(element);
                            
                            if (!tempPassed) {
                                passed = false;
                                showError(element, msg);
                            } else {
                                removeError(element);
                            }
                        });
                    }
                    
                }

                if (jQuery(this).find('.cnic').length > 0) {


                    var msg = "";
                    jQuery(this).find('.cnic').each(function () {
                        var tempPassed = true;
                        var element1 = jQuery(this).find('.cnic_1');
                        var element2 = jQuery(this).find('.cnic_2');
                        var element3 = jQuery(this).find('.cnic_3');
                        var tempPassed = cnicValidation(element1, element2, element3);
                        if (tempPassed) {
                            removeError(element1);
                            removeError(element2);
                            removeError(element3);
                        } else {
                            msg = "Incorrect CNIC";
                            passed = false;
                            showError(element1, msg);
                            showError(element2, msg);
                            showError(element3, msg);
                        }
                    });
                }

                /*if (jQuery(this).find('input[type=email]').length > 0) {
                    jQuery(this).find('input[type=email]').each(function (i) {
                        var value = jQuery(this).val();
                        var element = jQuery(this);
                        var msg = "email validation";
                        var tempPassed = emailValidation(value);
                        if (!tempPassed) {
                            msg = "invalid email address";
                            passed = false;
                            showError(element, msg);
                        } else {
                            removeError(element);
                        }
                    });
                }*/
                if (jQuery(this).find('.mob_no').length >= 0) {
                    jQuery(this).find('.mob_no').each(function () {
                        var value = jQuery(this).val();
                        var element = jQuery(this);

                        var maxSize = mobileNumberValidation(value);
                        if (maxSize < 0) {
                            passed = false;
                            msg = "Incorrect mobile number";
                            showError(element, msg);
                        } else {
                            var tempPassed = fieldFilled(value, maxSize);
                            if (!tempPassed) {
                                msg = "Incorrect mobile number";
                                passed = false;
                                showError(element, msg);
                            } else {
                                removeError(element);
                            }
                        }
                    });

                }

                jQuery(this).find('.required_entry').each(function (i) {
                    var value = jQuery(this).val();
                    var element = jQuery(this);
                    var msg = "This field is required";
                    var tempClass = jQuery(this).attr("class");
                    var tempPassed = "";
                    tempPassed = requiredValidation(value);


                    if (!tempPassed) {
                        if (tempClass.indexOf("select_plugin") < 0) {
                            passed = false;
                            showError(element, msg);
                        } else {
                            passed = false;
                            showSelectError(element, msg);
                        }
                    }
                });
                if (!passed) {
                    return false;
                } else {
                    jQuery(this).find("input[type=submit]").attr("disabled", "disabled");
                    formSubmit = true;
                    return true;
                }
            }
        }
    });

});