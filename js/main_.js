//////added by Turbo
function showHide(currdiv) {
    if ($('#override_wh').attr('checked')) {
        $('#extend_div').show('slow');
    } else {
        $('#extend_div').hide('slow');
    }
}


/////////////// Generic Script ////////////////////////

function chkpasswordExp(opt) {
    //alert('yes');
    if ($("#userpassword").val() != $("#confirm_userpassword").val()) {
        $('#error_label_login').html('');
        $('#error_label_login').show('fast');
        $("#display_message").html('Passwords do not match');
        $("#display_message").show('slow');
        $("#display_message").click();
        //$('#postbtn').attr("disabled","disabled");
        return false;
    } else {
        $("#display_message").html('');
        $("#display_message").show('slow');
        callpage(opt);
        $('#error_label_login').html('');
        $('#error_label_login').show('fast');
        return true;
    }
}

function getValuetoHidden(str) {
    var data = $('#' + str).val();
    $('#' + str + '-fd').attr('value', data);
    if (data == 'Others') {
        //alert(data);
        $('#' + str + '-div').attr('style', 'display:;');
    } else {
        $('#' + str + '-div').attr('style', 'display:none;');
    }
}

function callPageEdit(str, pgload, divd) {
    //alert("TORO");
    var operation = $("#operation").html();
    //alert(operation);
    var i = 0;
    var inpname = [];
    $("#form1").serialize();
    $.each($("input, select, textarea"), function(i, v) {
        var theElement = $(v);
        var theName = escape(theElement.attr('name'));
        inpname[i] = theName;
        i += 1;
    });
    var data = getdata();
    //alert(data);
    if (data != 'error') {
        $.ajax({
            async: true,
            type: "POST",
            url: "utilities.php",
            data: "op=editTrans&operation=" + operation + "&tableName=" + str + '&' + data + "&inputs=" + inpname,
            success: function(msg) {
                //alert(msg);
                var myMsgTest = msg.split("::||::");
                if (myMsgTest[0] == '1') {
                    $('#alertmsg').removeClass('alert-success');
                    $('#alertmsg').removeClass('alert-error');
                    $('#alertmsg').addClass('alert-success');
                    $("#hhdd").html('Success!');
                    $("#display_message").html(myMsgTest[1]);
                    $("#display_message").show('fast');
                    $("#alertmsg").show('fast');
                    $("#opt").hide('fast');
                    $("#addopt").show('fast');

                    sessionStorage.setItem("in_page_err", str);
                } else {
                    $('#alertmsg').removeClass('alert-error');
                    $('#alertmsg').removeClass('alert-success');
                    $('#alertmsg').addClass('alert-error');
                    $("#hhdd").html('Error !');
                    $("#display_message").html(msg);
                    $("#display_message").show('fast');
                    $("#alertmsg").show('fast');
                    $("#opt").hide('fast');
                    $("#addopt").show('fast');
                }

                msgarray = msg.split(":");
                if (pgload == 'getTransCol' && msgarray[0] == 'Successful') {
                    setTimeout("getTbRows('getTransCol')", 500);
                } else if (pgload == 'getItemlist' && msgarray[0] == 'Successful') {
                    var data = "merchant_id-whr=" + $("#merchant_id-whr").val();
                    getGenid(data);
                    setTimeout("getTbRows('getItemlist')", 500);
                } else if (pgload != '' && msgarray[0] == 'Successful') {
                    setTimeout("getpage('" + pgload + "','" + divd + "')", 3000);
                    //timer
                }
            }

        });
    }
}

function checkboxVal(obj) {
    if (obj.checked) {
        //alert('yes');
        $('#subbtn').attr('disabled', '');
        $('#subbtn').attr('value', 'Register').show('slow');
    } else {
        $('#subbtn').attr('disabled', 'disabled');
        $('#subbtn').attr('value', 'Read the Terms Before Proceeding!').show('slow');
    }

}

function checklogin(formurl, formurlconvert) {
    console.log("herrrr");
    $('#error_label_login').ajaxStart(function() {
        //$('#error_label_login').css({background-image: "url(../images/progress_bar.gif)"});
        $('#error_label_login').html('<img src="img/loading.gif" alt="" />loading please wait . . .');
    });

    var data = $("#email").val();
    var data2 = $("#Password").val();
    var url = $("#log").val();

    if (url == "out") {
        url = "admin/utilities.php";
    } else {
        url = "utilities.php";
    }
    //console.log(data + " :::: " + data2);
    $.ajax({
        type: "POST",
        url: url,
        data: "op=checklogin&username=" + data + "&password=" + data2,
        success: function(msg) {
            msg = jQuery.trim(msg);
            console.log(msg);
            $("#error_label_login").html('logging you in ...').show();
            if (msg == '00') {
                $("#error_label_login").html('<div class=\' alert alert-error \'>Login ... </div>').show();

                $("#form1").attr("action", formurl);
                $("#form1").submit();
            } else if (msg == '11') {
                //autopointe/confirm_user.php

                $("#error_label_login").html('<div class=\'alert alert-error\'> Invalid username or password <br>try again</div>').show();
            } else if (msg == '3') {

                // getpage('confirm_user.php', 'page');
                $("#error_label_login").html('<div class=\'alert alert-error\'> Invalid username or password <br>try again</div>').show();
            } else if (msg == '1') {
                //	alert(msg);
                $("#form1").attr("action", formurl);
                $("#form1").submit();
            } else if (msg == '2') {
                $("#error_label_login").html('Your user profile has been disabled').show();
            } else if (msg == '3') {
                $("#error_label_login").html('Your user profile has been locked').show();
            } else if (msg == '4') {
                $("#error_label_login").html('You are not allowed to login on Sunday').show();
            } else if (msg == '5') {
                $("#error_label_login").html('You are not allowed to login on Monday').show();
            } else if (msg == '6') {
                $("#error_label_login").html('You are not allowed to login on Tuesday').show();
            } else if (msg == '7') {
                $("#error_label_login").html('You are not allowed to login on Wednesday').show();
            } else if (msg == '8') {
                $("#error_label_login").html('You are not allowed to login on Thursday').show();
            } else if (msg == '9') {
                $("#error_label_login").html('You are not allowed to login on Friday').show();
            } else if (msg == '10') {
                $("#error_label_login").html('You are not allowed to login on Saturday').show();
            } else if (msg == '11') {
                $("#error_label_login").html('You are not allowed to login at this time <br> The time is not within the working hours').show();
            } else if (msg == '12') {
                $("#error_label_login").html('Your profile has been Locked, please contact Administrator').show();
            } else if (msg == '13') {
                $("#error_label_login").html("Your password has expired, <br><a href='change_password_exp.php?id=" + data + "'> click here to change password </a>").show();
            } else if (msg == '14') {
                $("#error_label_login").html("You are required to change your password, <br><a href='change_password_logon.php?id=" + data + "'> click here to change password </a>").show();
            } else if (msg == '15') {
                $("#error_label_login").html("You did not logout the last time you logged in. Pls Login again .. ").show();
            } else {
                $("#error_label_login").html('Invalid username or password <br><a href="forget_password.php">Click Here to Recover Password</a>' + msg).show();
            }
        }
    });
    //alert(data);
    return false;
}


function applylogin(formurl, formurlconvert) {
    $('#error_label_login').ajaxStart(function() {
        //$('#error_label_login').css({background-image: "url(../images/progress_bar.gif)"});
        $('#error_label_login').html('<img src="img/loading.gif" alt="" />loading please wait . . .');
    });

    var data = $("#loginemail").val();
    var data2 = $("#loginpass").val();
    var url = $("#log").val();

    if (url == "out") {
        url = "admin/utilities.php";
    } else {
        url = "utilities.php";
    }
    //console.log(data + " :::" + data2);
    $.ajax({
        type: "POST",
        url: url,
        data: "op=apply_now_login&loginemail=" + data + "&loginpass=" + data2,
        success: function(msg) {
            msg = jQuery.trim(msg);
            console.log(msg);
            $("#error_label_login").html('logging you in ...').show();
            if (msg == '') {
                $("#error_label_login").html('<div class=\' alert alert-error \'>Please enter a valid Username and Password</div>').show();
            } else if (msg == '0') {
                //autopointe/confirm_user.php

                $("#error_label_login").html('<div class=\'alert alert-error\'> Invalid username or password <br>try again</div>').show();
            } else if (msg == '00') {
                $("#error_label_login").html('logging you in ...').show();
                //getpage('apply_now_home.php', 'mainContent');
                       //  window.location.href = "apply_now_home.php";
                window.location.replace("admin/prospective_student/")
//                  $("#form1").attr("action", "apply_now_home.php");
//                  $("#form1").submit();
            } else if (msg == '10') {
                //  alert(msg);
                $("#form1").attr("action", formurl);
                $("#form1").submit();
            } else if (msg == '02') {
                $("#error_label_login").addClass('alert alert-warning').html('Your profile has not been Confirm<br><a href="#" onclick="javascript: getpage(\'reconfirm.php\',\'mainContent\');">Click Here to Resend </a>').show();
            } else if (msg == '3') {
                $("#error_label_login").html('Your profile has been locked').show();
            } else if (msg == '4') {
                $("#error_label_login").html('You are not allowed to login on Sunday').show();
            } else if (msg == '5') {
                $("#error_label_login").html('You are not allowed to login on Monday').show();
            } else if (msg == '6') {
                $("#error_label_login").html('You are not allowed to login on Tuesday').show();
            } else if (msg == '7') {
                $("#error_label_login").html('You are not allowed to login on Wednesday').show();
            } else if (msg == '8') {
                $("#error_label_login").html('You are not allowed to login on Thursday').show();
            } else if (msg == '9') {
                $("#error_label_login").html('You are not allowed to login on Friday').show();
            } else if (msg == '10') {
                $("#error_label_login").html('You are not allowed to login on Saturday').show();
            } else if (msg == '11') {
                $("#error_label_login").html('You are not allowed to login at this time <br> The time is not within the working hours').show();
            } else if (msg == '12') {
                $("#error_label_login").html('Your profile has been Locked, please contact Administrator').show();
            } else if (msg == '13') {
                $("#error_label_login").html("Your password has expired, <br><a href='change_password_exp.php?id=" + data + "'> click here to change password </a>").show();
            } else if (msg == '14') {
                $("#error_label_login").html("You are required to change your password, <br><a href='change_password_logon.php?id=" + data + "'> click here to change password </a>").show();
            } else if (msg == '15') {
                $("#error_label_login").html("You did not logout the last time you logged in. Pls Login again .. ").show();
            } else {
                $("#error_label_login").html('Invalid username or password <br><a href="#" onclick="javascript: getpage(\'forget_password.php\',\'mainContent\');">Click Here to Recover Password</a>').show();
            }
        }
    });
    //alert(data);
    return false;
}
function applicantlogin(formurl, formurlconvert) {
    console.log('here app');
    $('#error_label_login').ajaxStart(function() {
        //$('#error_label_login').css({background-image: "url(../images/progress_bar.gif)"});
        $('#error_label_login').html('<img src="img/loading.gif" alt="" />loading please wait . . .');
    });

    var data = $("#email").val();
    var data2 = $("#Password").val();
    var url = $("#log").val();

    if (url == "out") {
        url = "admin/utilities.php";
    } else {
        url = "utilities.php";
    }
    //console.log(data + " :::" + data2);
    $.ajax({
        type: "POST",
        url: url,
        data: "op=applicant_now_login&loginemail=" + data + "&loginpass=" + data2,
        success: function(msg) {
            msg = jQuery.trim(msg);
            console.log(msg);
            $("#error_label_login").html('logging you in ...').show();
            if (msg == '') {
                $("#error_label_login").html('<div class=\' alert alert-error \'>Please enter a valid Username and Password</div>').show();
            } else if (msg == '0') {
                //autopointe/confirm_user.php

                $("#error_label_login").html('<div class=\'alert alert-error\'> Invalid username or password <br>try again</div>').show();
            } else if (msg == '00') {
                $("#error_label_login").html('logging you in ...').show();
                //getpage('apply_now_home.php', 'mainContent');
						 window.location.href = "apply_now_home.php";
//					$("#form1").attr("action", "apply_now_home.php");
//					$("#form1").submit();
            } else if (msg == '10') {
                //	alert(msg);
                $("#form1").attr("action", formurl);
                $("#form1").submit();
            } else if (msg == '02') {
                $("#error_label_login").addClass('alert alert-warning').html('Your profile has not been Confirm<br>').show();
            }else if (msg == '-1') {
                $("#error_label_login").addClass('alert alert-warning').html('Your are not allowed to login<br>').show();
            }


             else if (msg == '3') {
                $("#error_label_login").html('Your profile has been locked').show();
            } else if (msg == '4') {
                $("#error_label_login").html('You are not allowed to login on Sunday').show();
            } else if (msg == '5') {
                $("#error_label_login").html('You are not allowed to login on Monday').show();
            } else if (msg == '6') {
                $("#error_label_login").html('You are not allowed to login on Tuesday').show();
            } else if (msg == '7') {
                $("#error_label_login").html('You are not allowed to login on Wednesday').show();
            } else if (msg == '8') {
                $("#error_label_login").html('You are not allowed to login on Thursday').show();
            } else if (msg == '9') {
                $("#error_label_login").html('You are not allowed to login on Friday').show();
            } else if (msg == '10') {
                $("#error_label_login").html('You are not allowed to login on Saturday').show();
            } else if (msg == '11') {
                $("#error_label_login").html('You are not allowed to login at this time <br> The time is not within the working hours').show();
            } else if (msg == '12') {
                $("#error_label_login").html('Your profile has been Locked, please contact Administrator').show();
            } else if (msg == '13') {
                $("#error_label_login").html("Your password has expired, <br>").show();
            } else if (msg == '14') {
                $("#error_label_login").html("You are required to change your password, ").show();
            } else if (msg == '15') {
                $("#error_label_login").html("You did not logout the last time you logged in. Pls Login again .. ").show();
            } else {
                 $("#error_label_login").html('Invalid username or password <br><a href="#" onclick="javascript: getpage(\'forget_password.php\',\'mainContent\');">Click Here to Recover Password</a>').show();

            }
        }
    });
    //alert(data);
    return false;
}

function getRRRStaus(rrr) {
    var data = $("#checkrrr").val();

    $('#checkrr').html("");
    if (data == "") {

        $('#checkrr').addClass('alert alert-danger').html("Please Enter Your RRR");
        reture;
    }
     $('#checkrr').removeClass('alert alert-info');
    console.log(data);
    $.ajax({
        type: "post",
        url: "utilities.php",
        data: "op=getrrrstatus&rrr=" + data,
        success: function(msg) {

            $('#checkrr').addClass('alert alert-info').html(msg);
        }
    });
}


function getStateLGA(ng_event) {
    var state = $(ng_event).val();

    $.ajax({
        type: "post",
        url: "../../utilities.php",
        data: "op=getLGA&selected_lga=" + state,
        success: function(msg) {
            // console.log(msg);
            $('#lga').html(msg);
        }
    });
}


function getState1(state_event) {
    var state = $(state_event).val();
console.log(state);
    $.ajax({
        type: "post",
        url: "../../utilities.php",
        data: "op=getStateLGA&selected_state=" + state,
        success: function(msg) {
            // console.log(msg);
            $('#state').html(msg);
        }
    });
}

function validateemail(email) {

}


function checkpin(formurl, formurlconvert) {
    $('#error_label_pin').ajaxStart(function() {
        //$('#error_label_login').css({background-image: "url(../images/progress_bar.gif)"});
        $('#error_label_pin').html('<img src="img/loading.gif" alt="" />loading please wait . . .');
    });

    var data = $("#bankcode").val();
    //var data3 = $("#agent_radio").val();
    //alert(data+":"+data2);
    //error_label_login
    $.ajax({
        type: "POST",
        url: "utilities.php",
        data: "op=checkpin&pin=" + data,
        success: function(msg) {
            msg = jQuery.trim(msg);
            console.log(msg);
            $("#display_error_message").html('Checking your PIN CODE ...').show();
            // alert(msg);
            if (msg == '') {
                $("#display_error_message").html('<div class=\' alert alert-error \'>Please enter a valid PIN CODE</div>').show();
            } else if (msg == '0') {
                $("#display_error_message").html('<div class=\' alert alert-error \'>Invalid PIN CODE</div>').show();
            } else if (msg == '1') {
                getpage(formurl, 'page')
                    //$("#form1").attr("action",formurl);

                //$("#form1").submit();
            } else if (msg == '2') {
                $("#display_error_message").html('Your PIN CODE has been used').show();
            } else {
                $("#display_error_message").html('Invalid Verify PIN CODE' + msg).show();
            }
        }
    });
    //alert(data);
    return false;
}


function checkForgotemail(formurl, formurlconvert) {
    $('#error_label_email').ajaxStart(function() {
        //$('#error_label_login').css({background-image: "url(../images/progress_bar.gif)"});
        $('#error_label_email').html('<img src="img/loading.gif" alt="" />loading please wait . . .');
    });

    var url = $("#fp").val();

    if (url == "out") {
        url = "admin/utilities.php";
    } else {
        url = "utilities.php";
    }

    var data = $("#emaill").val();
    //var data3 = $("#agent_radio").val();
    //alert(data+":"+data2);
    //error_label_login
    $.ajax({
        type: "POST",
        url: url,
        data: "op=checkForgotemail&email=" + data,
        success: function(msg) {
            msg = jQuery.trim(msg);
            console.log(msg);
            $("#error_label_email").html('Checking your code ...').show();
            // alert(msg);
            if (msg == '') {
                $("#error_label_email").html('<div class=\' alert alert-error \'>Please enter a valid </div>').show();
            } else if (msg == '0') {
                $("#error_label_email").html('<div class=\' alert alert-error \'>User Email does not Exit</div>').show();
            } else if (msg == '1') {
                getpage(formurl, 'page')
                    //$("#form1").attr("action",formurl);

                //$("#form1").submit();
            } else if (msg == '2') {
                $("#error_label_email").html('Your email has been used').show();
            } else {
                $("#error_label_email").html('Invalid Verify Mail' + msg).show();
            }
        }
    });
    //alert(data);
    return false;
}


function chkpassword(opt) {
    //alert($("#userpassword").val());
    //alert($("#confirm_userpassword").val());
    if ($("#userpassword").val() != $("#confirm_userpassword").val()) {
        $("#display_message").html('Passwords do not match');
        $("#display_message").show('slow');
        $("#display_message").click();
        return false;
    } else {
        $("#display_message").html('');
        $("#display_message").show('slow');
        callpage(opt);
        return true;
    }
}



function getdata() {
    var data = "";
    //$("#form1").serialize();
    $.each($("input, select, textarea"), function(i, v) {
        var theTag = v.tagName;
        var theElement = $(v);
        var theName = theElement.attr('name');
        var theValue = escape(theElement.val());
        var classname = theElement.attr('class');


        //if(classname.contains("required-text")){
        // alert("String Found "+classname);
        //	}

        if (theElement.hasClass('required-text')) {
            //alert('name : '+theName+"   value :"+theValue+"  class :"+classname);
            if (!check_textvalues(theElement)) data = "error";
        }

        if (theElement.hasClass('required-number')) {
            if (!check_numbers(theElement)) data = "error";
        }
        if (theElement.hasClass('required-email')) {
            if (!check_email(theElement)) data = "error";
        }
        if (theElement.hasClass('not-required-email')) {
            if (!check_email(theElement)) data = "error";
        }
        if (theElement.hasClass('required-alphanumeric')) {
            if (!check_password_aplhanumeric(theElement)) data = "error";
        }
        if (theElement.hasClass('required-password')) {
            if (!check_password(theElement)) data = "error";
        }
        if (theElement.hasClass('required-captcha')) {
            if (!check_captcha(theElement)) data = "error";
        }
        if (data != 'error') {
            if (theName != undefined) {
                theValue = theValue.replace(/\s/g, "_");
                theValue1 = theValue.trim();
                    console.log(theValue1);
                data = data + theName + "=" + encodeURIComponent(theValue1) + "&";
            }
        }
    });
    return data;
}
var vall = "session.php";
$.ajax({
    type: "POST",
    url: "session.php",
    data: "op=header",

    success: function(msg) {
        vall = msg;
    }
});
$.ajaxSetup({
    beforeSend: function(xhr) {
        xhr.setRequestHeader('x-my-sec-header', 'vall');

    }
});

function callpage(page) {

    var data = getdata();
    // console.log(data);
    
    
    $("input[type='submit']", this).val("Please Wait...").attr('disabled', 'disabled');
    if (data != 'error') {
        // console.log(data != 'error');
        $.blockUI({ message: '<img src="img/loading.gif" alt="" width="50px" height= "50px" />&nbsp;&nbsp;processing request please wait . . .' });
            var uurl = "utilities.php"; //$("#name").val();
        $("#display_message").hide("fast");
            $.ajax({
                type: "POST",
                url: uurl,
                data: "op=" + page + "&" + data,
                success: function(msg) {
                  sessionStorage.setItem("in_page_err", page);
                    msg = jQuery.trim(msg);
                    //console.log(msg);
                    $.unblockUI();
                     console.log(page+' :::::::::::::::: 11');
                    console.log(msg);//apply_now5
                     if(page =='apply_now5center'){
                        if (msg == '1') {
                            $("#display_message").addClass('alert alert-success').html("Successful! Your Exam Center Has been Added");
                            $("#display_message").show("fast");
                              getpage('registration_complete.php', 'mainContent');
                        }else{
                            $("#display_message").addClass('alert alert-danger').html("Error! Failed to add exam center");
                            $("#display_message").show("fast");
                        }

                    }else
                    if(page =='recoverpasswordmain'){
                        if (msg == '1') {
                            $("#display_message").addClass('alert alert-success').html("Successful! Your Password has been recovered");
                            $("#display_message").show("fast");
                        } else  if (msg == '2') {
                            $("#display_message").addClass('alert alert-warning').html("Ooop! Your password could not be recovered please try again later");
                            $("#display_message").show("fast");
                        }else  if (msg == '0') {
                            $("#display_message").addClass('alert alert-danger').html("Error! Your password does not match");
                            $("#display_message").show("fast");
                        }else{
                            $("#display_message").addClass('alert alert-warning').html("Error! please contact  an admin");
                            $("#display_message").show("fast");
                        }
                    }else
                     if(page =='recoverpassword'){
                        if (msg == '22') {
                            $("#display_message").addClass('alert alert-success').html("Successful! Please Follow through the Link Sent to your mail to recover password");
                            $("#display_message").show("fast");

                        }else  if (msg == '0') {
                    //console.log("hi2");
                           $("#display_message").addClass('alert alert-warning').html("Error! Sorry This Email Does Not Exist");
                            $("#display_message").show("fast");
                        }else  if (msg == '1') {
                    //console.log("hi2");
                           $("#display_message").addClass('alert alert-info').html("Sorry! A Link has Already been sent to your mail");
                            $("#display_message").show("fast");
                        }else  {
                    //console.log("hi2");
                           $("#display_message").addClass('alert alert-warning').html("Error! please try again later");
                            $("#display_message").show("fast");
                        }

                     }else
                    if(page =='resendmain'){


                        if (msg == '1') {
                            $("#display_message").addClass('alert alert-success').html("Email Resend Successfully. Check Your Email To Continue");
                            $("#display_message").show("fast");
                            getpage('resendconfirmation.php', 'mainContent');
                        }else  if (msg == '0') {
                    //console.log("hi2");
                           $("#display_message").addClass('alert alert-warning').html("Error! Sorry This Email Does Not Exist");
                            $("#display_message").show("fast");
                        }
                        else  if (msg == '2') {
                    //console.log("hi2");
                           $("#display_message").addClass('alert alert-warning').html("Oooop! This Email Has Already been Verified");
                            $("#display_message").show("fast");
                        }else{
                          $("#display_message").addClass('alert alert-warning').html("Oooop! Failed Please contact an admin");
                            $("#display_message").show("fast");
                        }
                    }else
                    if (page == 'apply_now') {

                        if (msg == '44') {
                            $("#display_message").addClass('alert alert-success').html("Registration Successful");
                            $("#display_message").show("fast");
                            window.location.replace("registration_confirmation.php");
                           // getpage('registration_confirmation.php', 'mainContent');
                        }else  if (msg == '0') {
                    //console.log("hi2");
                           $("#display_message").addClass('alert alert-warning').html("Error! Sorry This Email has Already been used");
                            $("#display_message").show("fast");
                        }else if(msg == '20'){
                            $("#display_message").addClass('alert alert-danger').html("Error! Password Does not match");
                            $("#display_message").show("fast");
                        } else {  $("#display_message").addClass('alert alert-warning').html("Error! Please Contact an Admin");
                            $("#display_message").show("fast");
                        }
                    } else if (page == 'apply_now3') {
                        if (msg == '11') {
                            $("#display_message").addClass('alert alert-success').html("Biodata Submitted Successfully");
                            $("#display_message").show("fast");

							setTimeout(function() {
                            getpage('apply_now_step_two.php', 'page');
                        }, 1000);

                        }else if(msg == '0'){
                            $("#display_message").addClass('alert alert-success').html("Biodata Submitted Successfully");
                            $("#display_message").show("fast");

                            setTimeout(function() {
                            getpage('apply_now_step_two.php', 'mainContent');
                        }, 1000);
                        }else{
                             $("#display_message").addClass('alert alert-danger').html("Error! Failed To Proccess");
                            $("#display_message").show("fast");
                        }
                    } else if (page == 'apply_now4') {
                        console.log(msg);
                        if (msg == '1') {
                            $("#display_message").addClass('alert alert-success').html("Educational Record Submitted Successfully");
                            $("#display_message").show("fast");

							setTimeout(function() {
                            getpage('apply_now_step_three.php', 'mainContent');
                        }, 1000);

                        }else  if (msg == '4'){
                            $("#display_message").addClass('alert alert-danger').html("You Must Fill All Required Fields ").removeClass("alert-success alert-warning");
                            $("#display_message").show("fast");


                        }else  if (msg == '-1'){
                          $("#display_message").addClass('alert alert-danger').html("Error! Same Subjects cannot be uploaded for the same Sitting").removeClass("alert-success alert-warning");
;
                            $("#display_message").show("fast");
                        }else  if (msg == '11'){
                            $("#display_message").addClass('alert alert-danger').html("Error!  Exam year And Type must be Selected for First Sitting").removeClass("alert-success alert-warning");
;
                            $("#display_message").show("fast");
                        }else if (msg == '12'){
                             $("#display_message").addClass('alert alert-danger').html("Error!  Exam Type And Year must be Selected for Second Sitting").removeClass("alert-success alert-warning");
;
                            $("#display_message").show("fast");

                        }else{
                             $("#display_message").addClass('alert alert-danger').html("Failed!  Please try again").removeClass("alert-success alert-warning");
;
                            $("#display_message").show("fast");
                        }


                        //apply_now5
                    }else if (page == 'apply_now5') {
                         $("#display_message2").hide();
                        console.log(msg);
                        if (msg == '1') {
                            $("#display_message").addClass('alert alert-success').html("Registration Successful");
                            $("#display_message").show("fast");

                            setTimeout(function() {
                            getpage('registration_complete.php', 'mainContent');
                        }, 1000);

                        }else if (msg == '4'){
                            $("#display_message").addClass('alert alert-danger').html("You must register educational background before submitting");
                            $("#display_message").show("fast");
                            setTimeout(function() {
                            getpage('apply_now_step_two.php', 'mainContent');
                        }, 2000);

                        }else if (msg == '5'){
                            $("#display_message").addClass('alert alert-danger').html("Error! You Must Accept The Condition Before Completing Application");
                            $("#display_message").show("fast");


                        }

                        else if (msg == '0'){
$("#display_message").addClass('alert alert-danger').html("You must Upload a passport before Submission");
                            $("#display_message").show("fast");

                        }else{
                            ddClass('alert alert-warning').html("You must complete your Biodata before submitting");
                            $("#display_message").show("fast");
                        }//apply_now5
                    }


                     else if (page == 'save_role') {
                                getpage('role_list.php', 'page');
                    }  else if (page == 'save_user') {
                        getpage('user_list.php', 'page');
                    } else if (page == 'verify_user') {
                        //  getpage('home.php', 'page');
                        $("#display_message").html("Your OTP is not match");
                    }else if (page == 'save_contact') {
                        $("#name").val("");
                        $("#email").val("");
                        $("#Message").val("");
                        getpage('index.php', 'page');
                    }else  if (page == 'apply_now') {
                        $("#display_message").html("Registration Successful");
                        $("#display_message").show("fast");
                        setTimeout(function() {
                            getpage('apply_now_continue.php', 'mainContent');
                        }, 1000);

                    }else  if (page == 'save_Outuser') {
                        $("#Fullname").val("");
                        $("#Username").val("");
                        $("#userpassword").val("");

                        // $('#error_label_login2').html('<div class=\' alert alert-info \'>User Registeration Successful Check your emaill for activation code...</div>');
                        // $('#error_label_login2').show();
                        $('#myModal_info').modal();
                        // getpage('index.php', 'page');
                        // setInterval(function() {
                        //     $('#error_label_login2').html('');
                        //     $('#error_label_login2').hide();
                        // }, 4000);

                        // getpage('index.php', 'page');
                    }else  if (page == 'save_vehicle') {
                        getpage('VehRegistration_List.php', 'page');
                        window.open("recipt.php", "_blank");
                    }else  if (page == 'save_menu') {
                        getpage('menu_list.php', 'page');
                    }else if (page == 'save_shop') {
                        getpage('building_shop_list.php', 'page');
                    }else  if (page == 'save_banks') {
                        getpage('banks_list.php', 'page');
                    } else if (page == 'save_job_card') {

                        if (msg == 'Detail has been successfully saved') {
                            $("#customerName").val("");
                            $("#address").val("");
                            $("#model_make").val("");
                            $("#chessisNo").val("");
                            $("#colour").val("");
                            $("#phoneNumber").val("");
                            $("#dateReceived").val("");
                            $("#dateCompleted").val("");
                            $("#jobDoneBy").val("");
                            $("#instractionTaken").val("");
                            $("#timeStart").val("");
                            $("#timeCompleted").val("");
                            $("#job_id").val("");

                            $("#SaveButton").hide('fast');
                            $("#jobCardButton").show('fast');
                        }
                    }else  if (page == 'save_medical') {
                        getpage('medical_list.php', 'page');
                    }else  if (page == 'save_supermaket') {
                        getpage('supermarketstore_list.php', 'page');
                    }else    if (page == 'save_printing') {
                        getpage('printing_list.php', 'page');
                    }else  if (page == 'save_haulage') {
                        getpage('haulage_list.php', 'page');
                    }else  if (page == 'save_glassshop') {
                        getpage('glassshop_list.php', 'page');
                    }else  if (page == 'save_motovehicle') {
                        getpage('motovehicle_list.php', 'page');
                    }else  if (page == 'save_rcc') {
                        getpage('cycle.php', 'page');
                    }else  if (page == 'save_consultant') {
                         getpage('Consultant_list.php','page');
                    }
                },error: function(err) {
                    console.log("errrrrr");
                }
              });

      }
        //alert('yes');
    }

function callpagesubmit(page) {

    var data = getdata();
    $("input[type='submit']", this).val("Please Wait...").attr('disabled', 'disabled');
    if (data != 'error') {
        $.blockUI({ message: '<img src="img/loading.gif" alt="" width="50px" height= "50px" />&nbsp;&nbsp;processing request please wait . . .' });
        var uurl = "../../utilities.php"; //$("#name").val();
        $("#display_message").hide("fast");
        $.ajax({
            type: "POST",
            url: uurl,
            data: "op=" + page + "&" + data,
            success: function(msg) {
                sessionStorage.setItem("in_page_err", page);
                msg = jQuery.trim(msg);
                //console.log(msg);
                $.unblockUI();
                console.log(page+' :::::::::::::::: 11');
                console.log(msg);//apply_now5
                if(page =='apply_now5center'){
                    if (msg == '1') {
                        $("#display_message").addClass('alert alert-success').html("Successful! Your Exam Center Has been Added");
                        $("#display_message").show("fast");
                        getpage('registration_complete.php', 'mainContent');
                    }else{
                        $("#display_message").addClass('alert alert-danger').html("Error! Failed to add exam center");
                        $("#display_message").show("fast");
                    }

                }else
                if(page =='recoverpasswordmain'){
                    if (msg == '1') {
                        $("#display_message").addClass('alert alert-success').html("Successful! Your Password has been recovered");
                        $("#display_message").show("fast");
                    } else  if (msg == '2') {
                        $("#display_message").addClass('alert alert-warning').html("Ooop! Your password could not be recovered please try again later");
                        $("#display_message").show("fast");
                    }else  if (msg == '0') {
                        $("#display_message").addClass('alert alert-danger').html("Error! Your password does not match");
                        $("#display_message").show("fast");
                    }else{
                        $("#display_message").addClass('alert alert-warning').html("Error! please contact  an admin");
                        $("#display_message").show("fast");
                    }
                }else
                if(page =='recoverpassword'){
                    if (msg == '22') {
                        $("#display_message").addClass('alert alert-success').html("Successful! Please Follow through the Link Sent to your mail to recover password");
                        $("#display_message").show("fast");

                    }else  if (msg == '0') {
                        //console.log("hi2");
                        $("#display_message").addClass('alert alert-warning').html("Error! Sorry This Email Does Not Exist");
                        $("#display_message").show("fast");
                    }else  if (msg == '1') {
                        //console.log("hi2");
                        $("#display_message").addClass('alert alert-info').html("Sorry! A Link has Already been sent to your mail");
                        $("#display_message").show("fast");
                    }else  {
                        //console.log("hi2");
                        $("#display_message").addClass('alert alert-warning').html("Error! please try again later");
                        $("#display_message").show("fast");
                    }

                }else
                if(page =='resendmain'){


                    if (msg == '1') {
                        $("#display_message").addClass('alert alert-success').html("Email Resend Successfully. Check Your Email To Continue");
                        $("#display_message").show("fast");
                        getpage('resendconfirmation.php', 'mainContent');
                    }else  if (msg == '0') {
                        //console.log("hi2");
                        $("#display_message").addClass('alert alert-warning').html("Error! Sorry This Email Does Not Exist");
                        $("#display_message").show("fast");
                    }
                    else  if (msg == '2') {
                        //console.log("hi2");
                        $("#display_message").addClass('alert alert-warning').html("Oooop! This Email Has Already been Verified");
                        $("#display_message").show("fast");
                    }else{
                        $("#display_message").addClass('alert alert-warning').html("Oooop! Failed Please contact an admin");
                        $("#display_message").show("fast");
                    }
                }else
                if (page == 'apply_now') {

                    if (msg == '44') {
                        $("#display_message").addClass('alert alert-success').html("Registration Successful");
                        $("#display_message").show("fast");
                        window.location.replace("registration_confirmation.php");
                        // getpage('registration_confirmation.php', 'mainContent');
                    }else  if (msg == '0') {
                        //console.log("hi2");
                        $("#display_message").addClass('alert alert-warning').html("Error! Sorry This Email has Already been used");
                        $("#display_message").show("fast");
                    }else if(msg == '20'){
                        $("#display_message").addClass('alert alert-danger').html("Error! Password Does not match");
                        $("#display_message").show("fast");
                    } else {  $("#display_message").addClass('alert alert-warning').html("Error! Please Contact an Admin");
                        $("#display_message").show("fast");
                    }
                } else if (page == 'apply_now3') {
                    if (msg == '11') {
                        $("#display_message").addClass('alert alert-success').html("Biodata Submitted Successfully");
                        $("#display_message").show("fast");

                        setTimeout(function() {
                            getpage('apply_now_step_two.php', 'page');
                        }, 1000);

                    }else if(msg == '0'){
                        $("#display_message").addClass('alert alert-success').html("Biodata Submitted Successfully");
                        $("#display_message").show("fast");

                        setTimeout(function() {
                            getpage('apply_now_step_two.php', 'mainContent');
                        }, 1000);
                    }else{
                        $("#display_message").addClass('alert alert-danger').html("Error! Failed To Proccess");
                        $("#display_message").show("fast");
                    }
                } else if (page == 'apply_now4') {
                    console.log(msg);
                    if (msg == '1') {
                        $("#display_message").addClass('alert alert-success').html("Educational Record Submitted Successfully");
                        $("#display_message").show("fast");

                        setTimeout(function() {
                            getpage('apply_now_step_three.php', 'mainContent');
                        }, 1000);

                    }else  if (msg == '4'){
                        $("#display_message").addClass('alert alert-danger').html("You Must Fill All Required Fields ").removeClass("alert-success alert-warning");
                        $("#display_message").show("fast");


                    }else  if (msg == '-1'){
                        $("#display_message").addClass('alert alert-danger').html("Error! Same Subjects cannot be uploaded for the same Sitting").removeClass("alert-success alert-warning");
                        ;
                        $("#display_message").show("fast");
                    }else  if (msg == '11'){
                        $("#display_message").addClass('alert alert-danger').html("Error!  Exam year And Type must be Selected for First Sitting").removeClass("alert-success alert-warning");
                        ;
                        $("#display_message").show("fast");
                    }else if (msg == '12'){
                        $("#display_message").addClass('alert alert-danger').html("Error!  Exam Type And Year must be Selected for Second Sitting").removeClass("alert-success alert-warning");
                        ;
                        $("#display_message").show("fast");

                    }else{
                        $("#display_message").addClass('alert alert-danger').html("Failed!  Please try again").removeClass("alert-success alert-warning");
                        ;
                        $("#display_message").show("fast");
                    }


                    //apply_now5
                }else if (page == 'apply_now5') {
                    $("#display_message2").hide();
                    console.log(msg);
                    if (msg == '1') {
                        $("#display_message").addClass('alert alert-success').html("Registration Successful");
                        $("#display_message").show("fast");

                        setTimeout(function() {
                            getpage('registration_complete.php', 'mainContent');
                        }, 1000);

                    }else if (msg == '4'){
                        $("#display_message").addClass('alert alert-danger').html("You must register educational background before submitting");
                        $("#display_message").show("fast");
                        setTimeout(function() {
                            getpage('apply_now_step_two.php', 'mainContent');
                        }, 2000);

                    }else if (msg == '5'){
                        $("#display_message").addClass('alert alert-danger').html("Error! You Must Accept The Condition Before Completing Application");
                        $("#display_message").show("fast");


                    }

                    else if (msg == '0'){
                        $("#display_message").addClass('alert alert-danger').html("You must Upload a passport before Submission");
                        $("#display_message").show("fast");

                    }else{
                        ddClass('alert alert-warning').html("You must complete your Biodata before submitting");
                        $("#display_message").show("fast");
                    }//apply_now5
                }


                else if (page == 'save_role') {
                    getpage('role_list.php', 'page');
                }  else if (page == 'save_user') {
                    getpage('user_list.php', 'page');
                } else if (page == 'verify_user') {
                    //  getpage('home.php', 'page');
                    $("#display_message").html("Your OTP is not match");
                }else if (page == 'save_contact') {
                    $("#name").val("");
                    $("#email").val("");
                    $("#Message").val("");
                    getpage('index.php', 'page');
                }else  if (page == 'apply_now') {
                    $("#display_message").html("Registration Successful");
                    $("#display_message").show("fast");
                    setTimeout(function() {
                        getpage('apply_now_continue.php', 'mainContent');
                    }, 1000);

                }else  if (page == 'save_Outuser') {
                    $("#Fullname").val("");
                    $("#Username").val("");
                    $("#userpassword").val("");

                    // $('#error_label_login2').html('<div class=\' alert alert-info \'>User Registeration Successful Check your emaill for activation code...</div>');
                    // $('#error_label_login2').show();
                    $('#myModal_info').modal();
                    // getpage('index.php', 'page');
                    // setInterval(function() {
                    //     $('#error_label_login2').html('');
                    //     $('#error_label_login2').hide();
                    // }, 4000);

                    // getpage('index.php', 'page');
                }else  if (page == 'save_vehicle') {
                    getpage('VehRegistration_List.php', 'page');
                    window.open("recipt.php", "_blank");
                }else  if (page == 'save_menu') {
                    getpage('menu_list.php', 'page');
                }else if (page == 'save_shop') {
                    getpage('building_shop_list.php', 'page');
                }else  if (page == 'save_banks') {
                    getpage('banks_list.php', 'page');
                } else if (page == 'save_job_card') {

                    if (msg == 'Detail has been successfully saved') {
                        $("#customerName").val("");
                        $("#address").val("");
                        $("#model_make").val("");
                        $("#chessisNo").val("");
                        $("#colour").val("");
                        $("#phoneNumber").val("");
                        $("#dateReceived").val("");
                        $("#dateCompleted").val("");
                        $("#jobDoneBy").val("");
                        $("#instractionTaken").val("");
                        $("#timeStart").val("");
                        $("#timeCompleted").val("");
                        $("#job_id").val("");

                        $("#SaveButton").hide('fast');
                        $("#jobCardButton").show('fast');
                    }
                }else  if (page == 'save_medical') {
                    getpage('medical_list.php', 'page');
                }else  if (page == 'save_supermaket') {
                    getpage('supermarketstore_list.php', 'page');
                }else    if (page == 'save_printing') {
                    getpage('printing_list.php', 'page');
                }else  if (page == 'save_haulage') {
                    getpage('haulage_list.php', 'page');
                }else  if (page == 'save_glassshop') {
                    getpage('glassshop_list.php', 'page');
                }else  if (page == 'save_motovehicle') {
                    getpage('motovehicle_list.php', 'page');
                }else  if (page == 'save_rcc') {
                    getpage('cycle.php', 'page');
                }else  if (page == 'save_consultant') {
                    getpage('Consultant_list.php','page');
                }
            },error: function(err) {
                console.log("errrrrr");
            }
        });

    }
    //alert('yes');
}
function getTrans_count(channel, divid) {
    var url = "utilities.php";
    $.ajax({
        type: "POST",
        url: url,
        data: "op=trans_count&channel=" + channel,
        success: function(msg) {
            $('#' + divid).text('N' + accounting.formatNumber(msg));
        }
    });

}


function getTransDetails(tablee, id, sector, divid) {
    var url = "utilities.php";
    $.ajax({
        type: "POST",
        url: url,
        data: "op=reg_details&tablee=" + tablee + "&sector=" + sector + "&id=" + id,
        success: function(msg) {

            // if(msg.indexOf("reg_details")<0)
            // {
            $('#' + divid).text(msg);
            // alert(msg);
            // }
        }
    });

}





function getBalancedetails(divid) {


    var url = "utilities.php";

    $.ajax({
        type: "POST",
        url: url,
        data: "op=balance",
        success: function(msg) {

            // if(msg.indexOf("reg_details")<0)
            // {
            $('#' + divid).text(msg);
            // alert(msg);
            // }
        }
    });

}


function getNotificationCount(tablee, divid) {


    var url = "utilities.php";

    $.ajax({
        type: "POST",
        url: url,
        data: "op=notify_count&tablee=" + tablee,
        success: function(msg) {

            // if(msg.indexOf("reg_details")<0)
            // {
            $('#' + divid).text(msg);
            // alert(msg);
            // }
        }
    });

}



function search_for_rrr2(rrrid) {


    var url = "admin/utilities.php";
    $('#rrr').text("");
    $('#amount').text("");
    $('#meesage').text("");
    $('#rrrInfo').hide();
    $('#rrrError').hide();
    $('#rrrForm').removeClass("col-md-7");
    $('#rrrForm').addClass("col-md-12");


    $.ajax({
        type: "POST",
        url: url,
        data: "op=search_rrr&rrrid=" + rrrid,
        success: function(msg) {

            var data2 = JSON.parse(msg);
            console.log(msg);
            if (data2 == null) {
                $('#rrrForm').removeClass("col-md-12");
                $('#rrrForm').addClass("col-md-7");

                $('#errmdg').text("RRR or OrderId not exit ...");
                $('#rrrError').show(500);
            }
            $('#rrr').text(data2.RRR);
            $('#amount').text(data2.amount);
            $('#meesage').text(data2.message);
            $('#rrrForm').removeClass("col-md-12");
            $('#rrrForm').addClass("col-md-7");
            $('#rrrInfo').show(1000);
        },
        error: function(err) {


        }
    });

}



function callpost(page) {
    //alert(page);
    //var data = getdata();

    //	if(data!='error')
    //	{

    //$("#display_message").ajaxStart(function(){
    //$.blockUI({ message:'<img src="img/loading.gif" alt=""/><br />processing request please wait . . .'});
    //});
    $.ajax({
        type: "POST",
        url: "utilities.php",
        data: "op=" + page, //+"&"+data,

        success: function(msg) {
            //alert(msg);
            $("#ticker").html(msg);
            $("#ticker").show("fast");

        }
    });
    //}
}

function check_textvalues(formElement) {
    if (triminput(formElement.val()) == '') {
        $("#display_message").html('<div class="alert alert-danger">You Must Fill All Required Fields  </div>');
        $("#display_message").show('fast');
        formElement.focus();
        $("#display_message").click();
        return false;
    } else return true;
}

function check_numbers(formElement) {
    if (triminput(formElement.val()) == '') {
        $("#display_message").html('<div class="alert alert-danger">You Must Fill All Required Fields  </div>');
        $("#display_message").show('fast');
        formElement.focus();
        $("#display_message").click();
        return false;
    }
    if (isNaN(formElement.val())) {
        $("#display_message").html('<div class="alert alert-error">You Must Fill All Required Fields </div>');
        $("#display_message").show('fast');
        formElement.focus();
        $("#display_message").click();
        return false;
    } else return true;
}

function check_email(formElement) {
    var emails = formElement.val();
    emailRegEx = /^[^@]+@[^@]+.[a-z]{2,}$/i;
    if (emails == "") return true;
    if ((formElement.val()).search(emailRegEx) == -1) {
        $("#display_message").html('<div class="alert alert-danger">please enter valid email for : ' + formElement.attr('title') + "</div>");
        $("#display_message").show('fast');
        formElement.focus();
        $("#display_message").click();
        return false;
    } else return true;
}

function check_email2(email) {
    // $("#error_label_contact").html('<div class="alert alert-error">please enter valid email for : '+formElement.attr('title')+"</div>");
    // 	$("#error_label_contact").show('fast');

    var emails = $email.val();
    //alert("Adeniyi "+emails);
    var reg = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/";
    if (reg.test(email)) {
        return true;
    } else {
        return false;
    }
}

function check_captcha(formElement) {
    var captcha = formElement.val();
    var captcha_sess = $('#captcha_sess').val();
    //alert(captcha);
    //alert(captcha_sess);
    if (captcha != captcha_sess) {
        $("#display_message").html('<div class="alert alert-danger">Please Enter Figures Displayed in ImageCaptcha into textbox above</div>');
        $("#display_message").show('fast');
        formElement.focus();
        $("#display_message").click();
        return false;
    } else return true;
}

function check_password_aplhanumeric(formElement) {
    var f1 = /[A-Z]/
    var f2 = /[a-z]/
    var f3 = /[0-9]/

    if ((f1.test(formElement.val()) || f2.test(formElement.val())) && f3.test(formElement.val())) {
        //alert('passed');
        return true;
    } else {
        $("#display_message").html('<div class="alert alert-danger">please enter alphanumeric as password</div>');
        $("#display_message").show('fast');
        //alert('failed');
        formElement.focus();
        $("#display_message").click();
        return false;
    }

}

function check_password(formElement) {
    var password = formElement.val();
    var errorval = '';
    var passed = validatePassword(password, {
        length: [6, 8],
        lower: 0,
        upper: 0,
        numeric: 0,
        special: 0,
        badWords: ["password", "steven", "levithan"],
        badSequenceLength: 4
    });
    if ((!chkpassword()) || (!passed)) {
        $("#display_message").html(errorval);
        $("#display_message").show('fast');
        //alert('failed');
        formElement.focus();
        $("#display_message").click();
        return false;
    }
}

function triminput(inputString) {
    var removeChar = ' ';
    var returnString = inputString;

    if (removeChar.length) {
        while ('' + returnString.charAt(0) == removeChar) {
            returnString = returnString.substring(1, returnString.length);
        }

        while ('' + returnString.charAt(returnString.length - 1) == removeChar)

        {

            returnString = returnString.substring(0, returnString.length - 1);

        }

    }

    return returnString;
}

function checkOption(obj) {
    if (obj.checked) {
        obj.value = '1';
    } else {
        obj.value = '0';
        obj.checked = false;
        //alert(obj.value);
    }
}

function ttoggleOption() {
    $.each($('input:checkbox'), function(i, v) {
        if ($(this).is(':checked')) {
            $(this).val('1');
        } else {
            $(this).val('0');
        }
    });
}

function callpagepost(str, divid) {
    //	 $("#form1").attr("target","");
    // $("#form1").attr("action",returnpage);
    //$("#form1").submit();

    var data = getdata();

    if (data != 'error') {
        //$("#display_message").ajaxStart(function(){
        $.blockUI({ message: '<img src="img/loading.gif" alt=""/><br />processing request please wait . . .' });
        //});

        //alert(data);
        /*
        $(divid).ajaxStart(function(){
        $(divid).html('');
        $(divid).html('<img src="img/loading.gif" alt="" />loading please wait . . .');
        });
        */
        if (str != '#') {
            //trapSession();


            $.blockUI({ message: '<img src="img/loading.gif" alt=""/>&nbsp;&nbsp;loading please wait . . .' });
            //$("#display_message").html('<img src="img/loading.gif" alt="" />loading please wait . . .');

            $.ajax({
                headers: {
                    'CsrfToken': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: str,
                data: data,
                success: function(msg) {
                    //alert( "Data Saved: " + msg );
                    //alert(msg);


                    sessionStorage.setItem("in_page_err", str);

                    $('#' + divid).html(msg);
                    $.unblockUI();
                    //$("#display_message").html("");
                }
            });
            /*
             $(divid).ajaxComplete(function(){
            	$(divid).html("");
             });
            */
        } // end if


    }

}

function getpage(str, divid) {
    var data = getdata();

    if (str != '#') {
        $.blockUI({ message: '<img src="img/loading.gif" width="50px" height= "50px" alt=""/>&nbsp;&nbsp;loading please wait . . .' });
        //$("#display_message").html('<img src="img/loading.gif" alt="" />loading please wait . . .');

        $.ajax({
            type: "POST",
            url: str,
            data: data,
            error: function(x, t, m) {
                $.blockUI({ message: 'Error Loading Page' });
                setTimeout(function() { $.unblockUI() }, 2000);
            },
            success: function(msg) {
                //alert( "Data Saved: " + msg );
                //alert(msg);
                $('#' + divid).html(msg).animate();
                $.unblockUI();

                sessionStorage.setItem("in_page_err", str);
                //$("#display_message").html("");
            }
        });

    }
}

function getpagephp(str, divid, oop = "0") {
    var data = getdata();
    if (str != '#') {
        $.blockUI({ message: '<img src="img/loading.gif" width="50px" height= "50px" alt=""/>&nbsp;&nbsp;loading please wait . . .' });
        $.ajax({
            type: "POST",
            url: "utilities.php",
            data: "op=" + oop,
            success: function(msgg) {
                $.ajax({
                    type: "POST",
                    url: str,
                    data: data,
                    error: function(x, t, m) {
                        $.blockUI({ message: 'Error Loading Page' });
                        setTimeout(function() { $.unblockUI() }, 2000);
                    },
                    success: function(msg) {
                $('#' + divid).html(msg).animate();
                $.unblockUI();

                sessionStorage.setItem("in_page_err", str);
                //$("#display_message").html("");
                    }
                });
               // $.unblockUI();
            }
        });

    } // end if
}


function doSearch(url) {
    //alert('Got here');
    //$("#form1").submit();
    var data = getdata();
    //alert("@ Search : "+data);
    //loadpage('branch_list.php',data,'page');
    getpage(url + '?' + data, 'page');
}


function doSearch1(url, divid) {
    //alert('Got here');
    //$("#form1").submit();
    var data = getdata();
    //alert("@ Search : "+data);
    //loadpage('branch_list.php',data,'page');
    getpage(url + '?' + data, divid);
}



function goFirst(dpage) {
    var lpage = parseInt($("#tpages").val());
    var fpage = parseInt($("#fpage").val());
    if (fpage != 1) {
        $("#fpage").get(0).value = '1';
        $("#pageNo").get(0).value = 1;
        doSearch(dpage);
    } else {
        return false;
    }
}

function goLast(dpage) {
    var lpage = parseInt($("#tpages").val());
    var fpage = parseInt($("#fpage").val());
    if (lpage != fpage) {
        $("#fpage").get(0).value = lpage;
        $("#pageNo").get(0).value = lpage;
        doSearch(dpage);
    } else {
        return false;
    }

}

function goPrevious(dpage) {
    var lpage = parseInt($("#tpages").val());
    var fpage = parseInt($("#fpage").val());
    if (fpage != 1) {
        $("#fpage").get(0).value = fpage - 1;
        $("#pageNo").get(0).value = fpage - 1;
        doSearch(dpage);
    } else {
        return false;
    }

}

function goNext(dpage) {
    var lpage = parseInt($("#tpages").val());
    var fpage = parseInt($("#fpage").val());
    if ((lpage > fpage)) {
        $("#fpage").get(0).value = fpage + 1;
        $("#pageNo").get(0).value = fpage + 1;
        doSearch(dpage);
    } else {
        return false;
    }

}

function doClickAll(form) {
    var form = document.getElementById("form1");
    for (var i = 0; i < form.elements.length; i++) {
        if (form.elements[i].type == "checkbox") {
            if (!form.elements[i].checked) {
                form.elements[i].click();
            }
        }
    }
    return true;
}

function doUnClickAll(form) {
    for (var i = 0; i < form.elements.length; i++) {
        if (form.elements[i].type == "checkbox") {
            if (form.elements[i].checked) {
                form.elements[i].checked = false;
            }
        }
    }
    return true;
}


function checkSelected(form, url) {
    //var form = document.forms[0];
    var parString = "";
    var delcount = 0;
    for (var i = 0; i < form.elements.length; ++i)
        if (form.elements[i].type == "checkbox" & form.elements[i].name == 'chkopt')
            if (form.elements[i].checked == true) {
                delcount++;
                parString = parString + "-" + form.elements[i].value + "-, ";
            }

    if (parString == "") {
        window.alert("Select record(s) to continue...");
        return (false);
    } else {
        //delcount = delcount - 1;
        form.var1.value = parString;
        form.op.value = 'del';
        ans = window.confirm("You have selected " + delcount + " record(s), Are your sure ?")
        if (ans == 1) {
            doSearch(url);
            return false;
        } else return false;
    }
}

function checkSelected1(form, url, divid) {
    //var form = document.forms[0];
    var parString = "";
    var delcount = 0;
    for (var i = 0; i < form.elements.length; ++i)
        if (form.elements[i].type == "checkbox" & form.elements[i].name == 'chkopt')
            if (form.elements[i].checked == true) {
                delcount++;
                parString = parString + "-" + form.elements[i].value + "-, ";
            }

    if (parString == "") {
        window.alert("Check Friend(s) to continue...");
        return (false);
    } else {
        //delcount = delcount - 1;
        form.var1.value = parString;
        form.op.value = 'del';
        ans = window.confirm("You have selected " + delcount + " Friend(s), Are your sure ?")
        if (ans == 1) {
            doSearch1(url, divid);
            return false;
        } else return false;
    }
}

function demaniNotice(table_name) {


    var url = "dnote/index.php";

    $.ajax({
        type: "POST",
        url: url,
        data: "op=reg_details&tablee=" + table_name, //+"&stateCode="+wheref+"&id="+id,
        success: function(msg) {

            // if(msg.indexOf("reg_details")<0)
            // {
            // $('#'+divid).text(msg);
            // alert(msg);
            // }
        }
    });

}

function loadLastTrans() {
    $.ajax({
        type: "POST",
        url: "utilities.php",
        data: "op=LastTransaction",
        success: function(msg) {
            //alert( "Data Saved: " + msg );
            //alert(data);
            $("#lastTransaction").html(msg);
            //$("#display_message").show("fast");
        }
    });
}

function printDiv1(seldiv, operat) {


    document.getElementById(operat).textContent = "";
    //oper.innerHTML.toUpperCase();

    var divToPrint = document.getElementById(seldiv);
    var newWin = window.open();
    newWin.document.open();
    newWin.document.write('<html><link rel="stylesheet" type="text/css" href="assets/css/custome_printing.css"><body>' + divToPrint.innerHTML + '</body></html>');
    newWin.document.close();
    //setTimeout(function(){newWin.close();},20);
}




function printDiv(seldiv) {


    //var oper=document.getElementById(operate);
    // oper.innerHTML.toUpperCase();

    var divToPrint = document.getElementById(seldiv);
    var newWin = window.open();
    newWin.document.open();
    newWin.document.write('<html><link rel="stylesheet" type="text/css" href="css/merchant.css"><body>' + divToPrint.innerHTML + '</body></html>');
    newWin.document.close();
    //setTimeout(function(){newWin.close();},20);
}



function printReceipt(seldiv) {
    var divToPrint = document.getElementById(seldiv);
    var newWin = window.open();
    /*newWin.document.open();*/
    newWin.document.write('<html><link rel="stylesheet" type="text/css" href="css/style.css"><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
    newWin.document.close();
    //setTimeout(function(){newWin.close();},20);
}


function blockUIDiv(divid) {
    //$('#'+divid).click(function() {
    $.blockUI({ message: $('#' + divid) });

    //setTimeout($.unblockUI, 2000);
    //});
}

function calldialog(divid) {
    //$('#'+divid).dialog();
    $.blockUI({ message: $('#' + divid) });
    setTimeout($.unblockUI, 2000);
}

function loadroles() {
    var data = escape($('#menu_id').val());
    $.ajax({
        type: "POST",
        url: "utilities.php",
        data: "op=getnonexistrole&menu_id=" + data,
        success: function(msg) {
            //alert( "Data Saved: " + msg );
            //alert(data);
            $("#non_exist_role").html(msg);
            //$("#display_message").show("fast");
        }
    });
    // for existing roles
    $.ajax({
        type: "POST",
        url: "utilities.php",
        data: "op=getexistrole&menu_id=" + data,
        success: function(msg) {
            //alert( "Data Saved: " + msg );
            //alert(data);
            $("#exist_role").html(msg);
            //$("#display_message").show("fast");
        }
    });
}

function moveuprole() {
    var listField = document.getElementById('exist_role');
    if (listField.length == -1) { // If the list is empty
        alert("There are no values which can be moved!");
    } else {
        var selected = listField.selectedIndex;
        if (selected == -1) {
            alert("You must select an entry to be moved!");
        } else { // Something is selected
            if (listField.length == 0) { // If there's only one in the list
                alert("There is only one entry!\nThe one entry will remain in place.");
            } else { // There's more than one in the list, rearrange the list order
                if (selected == 0) {
                    alert("The first entry in the list cannot be moved up.");
                } else {
                    // Get the text/value of the one directly above the hightlighted entry as
                    // well as the highlighted entry; then flip them
                    var moveText1 = listField[selected - 1].text;
                    var moveText2 = listField[selected].text;
                    var moveValue1 = listField[selected - 1].value;
                    var moveValue2 = listField[selected].value;
                    listField[selected].text = moveText1;
                    listField[selected].value = moveValue1;
                    listField[selected - 1].text = moveText2;
                    listField[selected - 1].value = moveValue2;
                    listField.selectedIndex = selected - 1; // Select the one that was selected before
                } // Ends the check for selecting one which can be moved
            } // Ends the check for there only being one in the list to begin with
        } // Ends the check for there being something selected
    } // Ends the check for there being none in the list
} //endmoveuprole()



function movedownrole() {
    var listField = document.getElementById('exist_role');
    if (listField.length == -1) { // If the list is empty
        alert("There are no values which can be moved!");
    } else {
        var selected = listField.selectedIndex;
        if (selected == -1) {
            alert("You must select an entry to be moved!");
        } else { // Something is selected
            if (listField.length == 0) { // If there's only one in the list
                alert("There is only one entry!\nThe one entry will remain in place.");
            } else { // There's more than one in the list, rearrange the list order
                if (selected == listField.length - 1) {
                    alert("The last entry in the list cannot be moved down.");
                } else {
                    // Get the text/value of the one directly below the hightlighted entry as
                    // well as the highlighted entry; then flip them
                    var moveText1 = listField[selected + 1].text;
                    var moveText2 = listField[selected].text;
                    var moveValue1 = listField[selected + 1].value;
                    var moveValue2 = listField[selected].value;
                    listField[selected].text = moveText1;
                    listField[selected].value = moveValue1;
                    listField[selected + 1].text = moveText2;
                    listField[selected + 1].value = moveValue2;
                    listField.selectedIndex = selected + 1; // Select the one that was selected before
                } // Ends the check for selecting one which can be moved
            } // Ends the check for there only being one in the list to begin with
        } // Ends the check for there being something selected
    } // Ends the check for there being none in the list
} // endmovedown



function addlot() {
    return !$('#non_exist_lots option:selected').remove().appendTo('#exist_lots');
}

function addrole() {
    return !$('#non_exist_role option:selected').remove().appendTo('#exist_role');
}

function removelots() {
    return !$('#exist_lots option:selected').remove().appendTo('#non_exist_lots');
}

function removerole() {
    return !$('#exist_role option:selected').remove().appendTo('#non_exist_role');
}

function selectalldata() {
    $("#exist_role *").attr("selected", "selected");
}

function selectalldata1() {
    $("#exist_lots *").attr("selected", "selected");
}

function toggleOption() {
    $("input[type=checkbox]").each(
        function() {
            if ($(this).is(':checked')) {
                var idname = $(this).attr('id');
                $(this).val(idname);
                //alert($(this).val());
            } else {
                $(this).val('');
            }
        }
    );

}

function Resize(imgId, division_1, division_2) {
    var img = document.getElementById(imgId);
    var w = img.width,
        h = img.height;
    w /= division_1;
    h /= division_2;
    img.width = w;
    img.height = h;
}


function selectalllist(list) {
    $("#" + list + " *").attr("selected", "selected");
}
/////////////////

function pageloader(str, divid) {
    var data = getdata();
    //alert(data);
    if (data != 'error') {
        $.ajax({
            type: "POST",
            url: str,
            data: data,
            success: function(msg) {
                //alert( "Data Saved: " + msg );
                //alert(msg);
                $('#' + divid).html(msg);
                //$("#display_message").fadeIn("slow");
            }
        });
    }
}
///////////////////////////////////
function moveUpList(listField) {
    if (listField.length == -1) { // If the list is empty
        alert("There are no values which can be moved!");
    } else {
        var selected = listField.selectedIndex;
        if (selected == -1) {
            alert("You must select an entry to be moved!");
        } else { // Something is selected
            if (listField.length == 0) { // If there's only one in the list
                alert("There is only one entry!\nThe one entry will remain in place.");
            } else { // There's more than one in the list, rearrange the list order
                if (selected == 0) {
                    alert("The first entry in the list cannot be moved up.");
                } else {
                    // Get the text/value of the one directly above the hightlighted entry as
                    // well as the highlighted entry; then flip them
                    var moveText1 = listField[selected - 1].text;
                    var moveText2 = listField[selected].text;
                    var moveValue1 = listField[selected - 1].value;
                    var moveValue2 = listField[selected].value;
                    listField[selected].text = moveText1;
                    listField[selected].value = moveValue1;
                    listField[selected - 1].text = moveText2;
                    listField[selected - 1].value = moveValue2;
                    listField.selectedIndex = selected - 1; // Select the one that was selected before
                } // Ends the check for selecting one which can be moved
            } // Ends the check for there only being one in the list to begin with
        } // Ends the check for there being something selected
    } // Ends the check for there being none in the list
    return false;
}

function moveDownList(listField) {
    if (listField.length == -1) { // If the list is empty
        alert("There are no values which can be moved!");
    } else {
        var selected = listField.selectedIndex;
        if (selected == -1) {
            alert("You must select an entry to be moved!");
        } else { // Something is selected
            if (listField.length == 0) { // If there's only one in the list
                alert("There is only one entry!\nThe one entry will remain in place.");
            } else { // There's more than one in the list, rearrange the list order
                if (selected == listField.length - 1) {
                    alert("The last entry in the list cannot be moved down.");
                } else {
                    // Get the text/value of the one directly below the hightlighted entry as
                    // well as the highlighted entry; then flip them
                    var moveText1 = listField[selected + 1].text;
                    var moveText2 = listField[selected].text;
                    var moveValue1 = listField[selected + 1].value;
                    var moveValue2 = listField[selected].value;
                    listField[selected].text = moveText1;
                    listField[selected].value = moveValue1;
                    listField[selected + 1].text = moveText2;
                    listField[selected + 1].value = moveValue2;
                    listField.selectedIndex = selected + 1; // Select the one that was selected before
                } // Ends the check for selecting one which can be moved
            } // Ends the check for there only being one in the list to begin with
        } // Ends the check for there being something selected
    } // Ends the check for there being none in the list
    return false;
}

function validatePassword(pw, options) {
    // default options (allows any password)
    var o = {
        lower: 0,
        upper: 0,
        alpha: 0,
        /* lower + upper */
        numeric: 0,
        special: 0,
        length: [0, Infinity],
        custom: [ /* regexes and/or functions */ ],
        badWords: [],
        badSequenceLength: 0,
        noQwertySequences: false,
        noSequential: false
    };

    for (var property in options)
        o[property] = options[property];

    var re = {
            lower: /[a-z]/g,
            upper: /[A-Z]/g,
            alpha: /[A-Z]/gi,
            numeric: /[0-9]/g,
            special: /[\W_]/g
        },
        rule, i;

    // enforce min/max length
    if (pw.length < o.length[0] || pw.length > o.length[1])
        errorval = 'Password Minimum Length is ' + o.length[0] + ' While Maximum Lenght Should not exceed ' + o.length[1];
    return false;

    // enforce lower/upper/alpha/numeric/special rules
    for (rule in re) {
        if ((pw.match(re[rule]) || []).length < o[rule])
            errorval = 'Password Should contain lower/upper/alpha/numeric/';
        return false;
    }

    // enforce word ban (case insensitive)
    for (i = 0; i < o.badWords.length; i++) {
        if (pw.toLowerCase().indexOf(o.badWords[i].toLowerCase()) > -1)

            return false;
    }

    // enforce the no sequential, identical characters rule
    if (o.noSequential && /([\S\s])\1/.test(pw))
        return false;

    // enforce alphanumeric/qwerty sequence ban rules
    if (o.badSequenceLength) {
        var lower = "abcdefghijklmnopqrstuvwxyz",
            upper = lower.toUpperCase(),
            numbers = "0123456789",
            qwerty = "qwertyuiopasdfghjklzxcvbnm",
            start = o.badSequenceLength - 1,
            seq = "_" + pw.slice(0, start);
        for (i = start; i < pw.length; i++) {
            seq = seq.slice(1) + pw.charAt(i);
            if (
                lower.indexOf(seq) > -1 ||
                upper.indexOf(seq) > -1 ||
                numbers.indexOf(seq) > -1 ||
                (o.noQwertySequences && qwerty.indexOf(seq) > -1)
            ) {
                return false;
            }
        }
    }

    // enforce custom regex/function rules
    for (i = 0; i < o.custom.length; i++) {
        rule = o.custom[i];
        if (rule instanceof RegExp) {
            if (!rule.test(pw))
                return false;
        } else if (rule instanceof Function) {
            if (!rule(pw))
                return false;
        }
    }

    // great success!
    return true;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////js by SAMABOS//////////////////////////////////

function callpagepost2(page, opt, returnpage, divid) {
    var data = getdata();
    //alert(data);
    var poststatus = true;
    if (data != 'error') {
        //$('#error_label_login').css({background-image: "url(../images/progress_bar.gif)"});
        $.blockUI({ message: '<img src="img/loading.gif" alt=""/>&nbsp;&nbsp;loading please wait . . .' });
        //alert(poststatus);
        if (poststatus == true) {
            $.ajax({
                type: "POST",
                url: "utilities.php",
                data: "op=" + page + "&" + data,
                success: function(msg) {
                    //alert( "Data Saved: " + msg );
                    //alert(data);
                    $.unblockUI();
                    $("#display_message").html(msg);
                    $("#display_message").show("fast");
                    $("#display_message").click();

                  sessionStorage.setItem("in_page_err", page);
                    setTimeout("callresponse('" + returnpage + "','" + divid + "','" + msg + "')", 2000);

                }
            });
        } // end poststatus
    }
}

function callresponse(returnpage, divid, msg) {
    var resp = msg.split("/");
    if (resp[1] == '1') {
        $("#display_message").html(resp[0]);
        $("#display_message").show("fast");
        $.unblockUI();
        doSubmit(returnpage, divid);
    } else {
        $("#display_message").html(resp[0]);
        $("#display_message").show("fast");
        $.unblockUI();
    }
}

function doSubmit(url, pgdiv) {
    //alert('Got here');
    //$("#form1").submit();
    var data = getdata();
    //alert("@ Search : "+data);
    //loadpage('branch_list.php',data,'page');
    getpage(url + '?' + data, pgdiv);
}








function doTransEntry(page, divd) {
    //callPageEdit('transaction_extension','','');
    var trans_ext_id = $('#trans_ext_id-fd').val();
    //alert(trans_ext_id);
    //var page = page+"?transId="+trans_ext_id;
    var i = 0;
    var inpname = [];
    $("#form1").serialize();
    $.each($("input, select, textarea"), function(i, v) {
        var theElement = $(v);
        var theName = escape(theElement.attr('name'));
        inpname[i] = theName;
        i += 1;
    });

    var data = getdata();
    //alert(data);
    if (data != 'error') {
        $.blockUI({ message: '<img src="img/loading.gif" alt=""/>&nbsp;&nbsp;loading please wait . . .' });
        $.ajax({
            async: true,
            type: "POST",
            url: "utilities.php",
            data: "op=doTransEntry&" + data + "&inputs=" + inpname,
            success: function(msg) {
                var msgarr = msg.split(':');
                if (msgarr[0] == 'SUCCESSFUL' && page != '') {
                    $("#display_message").html(msg);
                    $("#display_message").show();
                    setTimeout("getpage('" + page + "','" + divd + "')", 2000);
                } else {
                    $("#display_message").html(msg);
                    $("#display_message").show();
                }
                //				setTimeout("getpage('"+pgload+"','"+divd+"')",3000);

                $.unblockUI();
            }

        });

    }

}

function updateUser(strr, str) {
    //alert('yes');
    //$("#resu").css("text-align", "center");
    if (strr == '101' && str != "") {
        $("#resu").html('Welcome ' + str + ' <font color="#009900">You are now logged in</font>');
        $(".rerun").show();
        $("#resu_recharge").show();
        $("#resu_balance").show();
        $("#error_label_loginn").hide();
        $("#sresu").hide();
        return true;
    } else {
        $("#display_message").html('Invalid Username or Password');
        $("#display_message").show('fast');
        return false;
    }
}


function getCustomerDetails(str) {
    //alert('yes');
    $("#SubmitBtn").attr("disabled", "disabled");
    var merchant_id = $('#merchant_id-fd').val();
    $("#customerinfo").html('<img src="img/loading.gif" alt="" />loading please wait . . .');
    $("#customerinfo").show("fast");
    $.ajax({
        type: "POST",
        url: "utilities.php",
        data: "op=getCustomerDetails&str=" + str + "&merchant_id=" + merchant_id,
        success: function(msg) {
            //alert(msg);
            if (msg.indexOf('NO DETAILS FOUND') < 0) {
                $("#SubmitBtn").removeAttr("disabled");
                $("#customerinfo").html(msg);
                $("#customerinfo").show("fast");
            } else {
                $("#customerinfo").html(msg);
                $("#customerinfo").show("fast");
            }

        }
    });
}

function calldownload() {
    //
    var data = $('#sql').val();
    var data2 = $('#filename').val();
    //alert(data);
    window.open("download.php?sql=" + escape(data) + "&filename=" + data2, "mydownload", "status=0,toolbar=0");
}

function callPageFormRequest(str, frmid) {
    var data = getDatta(frmid);
    //alert(data);
    if (data != 'error') {
        $.blockUI({ message: '<img src="img/loading.gif" alt=""/>&nbsp;&nbsp;processing request please wait . . .' });

        $.ajax({
            type: "POST",
            url: "utilities.php",
            data: "op=" + str + '&' + data,
            success: function(msg) {
                //alert(msg);
                var myMsgTest = msg.split("::||::");
                if (myMsgTest[0] == '1') {
                    $('#alertmsg').removeClass('alert-success');
                    $('#alertmsg').removeClass('alert-error');
                    $('#alertmsg').addClass('alert-success');
                    $("#hhdd").html('Success!');
                    $("#display_message").html(myMsgTest[1]);
                    $("#display_message").show('fast');
                    $("#alertmsg").show('fast');
                    $("#opt").hide('fast');
                    $("#addopt").show('fast');

                    sessionStorage.setItem("in_page_err", str);
                } else {
                    $('#alertmsg').removeClass('alert-error');
                    $('#alertmsg').removeClass('alert-success');
                    $('#alertmsg').addClass('alert-error');
                    $("#hhdd").html('Error !');
                    $("#display_message").html(msg);
                    $("#display_message").show('fast');
                    $("#alertmsg").show('fast');
                    $("#opt").hide('fast');
                    $("#addopt").show('fast');
                }
                $.unblockUI();
            }

        });
    }
}

function getDataRefined() {
    var data = "";
    //$("#form1").serialize();
    $.each($("input, select, textarea"), function(i, v) {
        var theTag = v.tagName;
        var theElement = $(v);
        var theName = theElement.attr('name');
        var theValue = encodeURIComponent(theElement.val());
        var classname = theElement.attr('class');
        var altVal = theElement.attr('alt');
        if (theElement.hasClass('required-text')) {
            if (!check_textvalues(theElement)) data = "error";
        }
        if (theElement.hasClass('required-number')) {
            if (!check_numbers(theElement)) data = "error";
        }
        if (theElement.hasClass('required-email')) {
            if (!check_email(theElement)) data = "error";

        }
        if (theElement.hasClass('required-alphanumeric')) {
            if (!check_password_aplhanumeric(theElement)) data = "error";
        }
        if (data != 'error') {
            data = data + theName + "=" + theValue + "&";
        }
    });
    //alert(data);
    return data;
}


function doClickAll(form) {
    var form = document.getElementById("form1");
    for (var i = 0; i < form.elements.length; i++) {
        if (form.elements[i].type == "checkbox") {
            if (!form.elements[i].checked) {
                form.elements[i].click();
            }
        }
    }
    return true;
}

function doUnClickAll(form) {
    for (var i = 0; i < form.elements.length; i++) {
        if (form.elements[i].type == "checkbox") {
            if (form.elements[i].checked) {
                form.elements[i].checked = false;
            }
        }
    }
    return true;
}


function toggleOptionn(myClass) {
    $("." + myClass).each(
        function() {
            if ($(this).is(':checked')) {
                var idname = $(this).attr('id');
                $(this).val(idname);
                //alert($(this).val());
            } else {
                $(this).val('');
            }
        }
    );

}



function doAssign(str, str2, str3) {
    //alert();
    if ($('.avn:checked').length == 0) {
        alert('No Item selected Yet, Please select Item(s) and try again.');
        return false;
    } else {
        //alert('am here');
        var numSel = $('.avn:checked').length;
        if (numSel > 1) var pls = 's';
        else var pls = '';
        confirm_operation(str + '?op=' + str2, 'page', str3 + ' the selected ' + numSel + ' Item' + pls + ' ?', 'avn');
    }
}


function confirm_operation(str, div, msg, clss) {
    $(document).ready(function() {
        $("#mssg").html(msg);
        //alert(str);
        var myClass = clss || 'stt';
        $.blockUI({ message: $('#question'), css: { width: '380px', border: '4px solid #a00' } });
        $('#yes').click(function() {
            toggleOptionn(myClass);
            posttopage(str, div);
            //$.unblockUI();
            return true;
        });

        $('#no').click(function() {
            $.unblockUI();
            return false;
        });

    });
}


function posttopage(str, divid) {
    var data = getdata();
    //alert(data);
    $.blockUI({
        message: '<h5><font color="#FF0000"><img src="img/loading.gif" />&nbsp;&nbsp;&nbsp;&nbsp;Please wait, loading page ...</font></h5>',
        css: { border: '5px solid #a00', padding: '5px' }
    });
    $.ajax({
        type: "post",
        url: str,
        data: data,
        success: function(msg) {
            //alert(str+' '+data);
            $('#' + divid).html(msg);
            $.unblockUI();
        }
    });
}

function confirmCheck(str, str2, str3) {

    if ($('.' + str + ':checked').length > 0) {
        $("#" + str2).show();
        $("#" + str2).attr('disabled', false);
        $("#" + str3).show();
        $("#" + str3).attr('disabled', false);
    } else {
        $("#" + str2).hide();
        $("#" + str3).hide();
        /*$("#"+str2).attr('disabled',true);*/
    }
}



// // When the user scrolls the page, execute myFunction
// window.onscroll = function() {
//     myFunction()
// };

// // Get the header
// var header = document.getElementById("myReportHeader");

// // Get the offset position of the navbar
// var sticky = header.offsetTop;

// // Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
// function myFunction() {
//     if (window.pageYOffset >= sticky) {
//         header.classList.add("sticky");
//     } else {
//         header.classList.remove("sticky");
//     }
// }


(function(exports) {
    "use strict";

    var XORCipher = {
        encode: function(key, data) {
            data = xor_encrypt(key, data);
            return b64_encode(data);
        },
        decode: function(key, data) {
            data = b64_decode(data);
            return xor_decrypt(key, data);
        }
    };

    var b64_table = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";

    function b64_encode(data) {
        var o1, o2, o3, h1, h2, h3, h4, bits, r, i = 0,
            enc = "";
        if (!data) { return data; }
        do {
            o1 = data[i++];
            o2 = data[i++];
            o3 = data[i++];
            bits = o1 << 16 | o2 << 8 | o3;
            h1 = bits >> 18 & 0x3f;
            h2 = bits >> 12 & 0x3f;
            h3 = bits >> 6 & 0x3f;
            h4 = bits & 0x3f;
            enc += b64_table.charAt(h1) + b64_table.charAt(h2) + b64_table.charAt(h3) + b64_table.charAt(h4);
        } while (i < data.length);
        r = data.length % 3;
        return (r ? enc.slice(0, r - 3) : enc) + "===".slice(r || 3);
    }

    function b64_decode(data) {
        var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
            result = [];
        if (!data) { return data; }
        data += "";
        do {
            h1 = b64_table.indexOf(data.charAt(i++));
            h2 = b64_table.indexOf(data.charAt(i++));
            h3 = b64_table.indexOf(data.charAt(i++));
            h4 = b64_table.indexOf(data.charAt(i++));
            bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;
            o1 = bits >> 16 & 0xff;
            o2 = bits >> 8 & 0xff;
            o3 = bits & 0xff;
            result.push(o1);
            if (h3 !== 64) {
                result.push(o2);
                if (h4 !== 64) {
                    result.push(o3);
                }
            }
        } while (i < data.length);
        return result;
    }

    function keyCharAt(key, i) {
        return key.charCodeAt(Math.floor(i % key.length));
    }

    function xor_encrypt(key, data) {
        return _.map(data, function(c, i) {
            return c.charCodeAt(0) ^ keyCharAt(key, i);
        });
    }

    function xor_decrypt(key, data) {
        return _.map(data, function(c, i) {
            return String.fromCharCode(c ^ keyCharAt(key, i));
        }).join("");
    }

    exports.XORCipher = XORCipher;

})(this);

$(document).ready(function() {
    // Configure/customize these variables.
    var showChar = 100; // How many characters are shown by default
    var ellipsestext = "...";
    var moretext = "Show more >";
    var lesstext = "Show less";

    $('.more').each(function() {
        var content = $(this).html();

        if (content.length > showChar) {
            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);
            var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
            $(this).html(html);
        }

    });

    $(".morelink").click(function() {
        if ($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
});


$('#form1').submit(function() {
    $("input[type='submit']", this)
        .val("Please Wait...")
        .attr('disabled', 'disabled');
    return true;
});
