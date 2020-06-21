<?php

session_start();
// if (!isset($_SESSION['sonm_username']))
// {
//     include('logout.php');
// }
$station ="";
include 'lib/dbfunctions_extra_jam.php';
$dbobject = new myDbObject();
$program_sel = $dbobject->pickStation($station);


?>
<!doctype html>
<html class="no-js" lang="">


<!-- Mirrored from www.radiustheme.com/demo/html/academics/academics/about1.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 23 Sep 2019 17:50:18 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Olivet College of Health Technology</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
    <!-- Normalize CSS -->
    <link rel="stylesheet" href="css/normalize.css">
    <!-- Main CSS -->
    <link rel="stylesheet" href="css/main.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="css/animate.min.css">
    <!-- Font-awesome CSS-->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Owl Caousel CSS -->
    <link rel="stylesheet" href="vendor/OwlCarousel/owl.carousel.min.css">
    <link rel="stylesheet" href="vendor/OwlCarousel/owl.theme.default.min.css">
    <!-- Main Menu CSS -->
    <link rel="stylesheet" href="css/meanmenu.min.css">
    <!-- nivo slider CSS -->
    <link rel="stylesheet" href="vendor/slider/css/nivo-slider.css" type="text/css" />
    <link rel="stylesheet" href="vendor/slider/css/preview.css" type="text/css" media="screen" />
    <!-- Datetime Picker Style CSS -->
    <link rel="stylesheet" href="css/jquery.datetimepicker.css">
    <!-- Magic popup CSS -->
    <link rel="stylesheet" href="css/magnific-popup.css">
    <!-- Switch Style CSS -->
    <link rel="stylesheet" href="css/hover-min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <!-- Modernizr Js -->
    <script src="js/modernizr-2.8.3.min.js"></script>
</head>

<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<!-- Add your site or application content here -->
<script>

    function checkForm(form)
    {

        if(form.Surname.value == "") {
            form.Surname.focus();
            form.Surname.style.border="2px solid #F00";
            return false;
        }else{
            form.Surname.style.border="";
        }
        if(form.fname.value == "") {
            form.fname.focus();
            form.fname.style.border="2px solid #F00";
            return false;
        }else{
            form.fname.style.border="";
        }
        if(form.phoneno.value == "") {
            form.phoneno.focus();
            form.phoneno.style.border="2px solid #F00";
            return false;
        }else{
            form.phoneno.style.border="";
        }
        if(form.Email.value == "") {
            form.Email.focus();
            form.Email.style.border="2px solid #F00";
            return false;
        }else{
            form.phoneno.style.border="";
        }//password
        if(form.password.value == "") {
            form.password.focus();
            form.password.style.border="2px solid #F00";
            return false;
        }else{
            form.password.style.border="";
        }
        if(form.cpassword.value == "") {
            form.cpassword.focus();
            form.cpassword.style.border="2px solid #F00";
            return false;
        }else{
            form.cpassword.style.border="";
        }
        return false;
    }

</script>
<div id="wrapper">
    <?php include "header.php"; ?>
    <div class="inner-page-banner-area" style="background-image: url('img/banner/5.jpg'); height: 3px">
        <div class="container">
            <div class="pagination-area">
                <h1>Applicant Account Setup</h1>

            </div>
        </div>
    </div>
    <!-- Inner Page Banner Area End Here -->
    <!-- About Page 1 Area Start Here -->
    <div class="about-page1-area">
        <div class="container">
            <div class="row about-page1-inner">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="about-page-content-holder">

                        <div class="content-box">
                            <div class="registration-page-area bg-secondary">
                                <div class="container">
                                    <div class="row" id="test">
                                        <div class="col-xs-12 col-sm-8 custom_right">
                                            <div class="single_content_left">

                                                <h6>All fields marked <font color="red">*</font> are required</h6>
                                                <div class="registration-details-area inner-page-padding">
                                                    <form name="form1" id="regform"  onsubmit="return checkForm(this); return false">

                                                        <div class="row">
                                                            <div class="col-xs-12 col-sm-6">
                                                                <div class="form-group">
                                                                    <label>Surname <span class="error">*</span></label>
                                                                    <input type="text" title="Surname" class="form-control required-text" name="Surname">
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6">
                                                                <div class="form-group">
                                                                    <label>First Name <span class="error">*</span></label>
                                                                    <input type="text" title="fname" class="form-control required-text" name="fname" id="fname" >


                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xs-12 col-sm-6">
                                                                <div class="form-group">
                                                                    <label>Other Name <span class="">(optional)</span></label>
                                                                    <input type="text" title="Othername" class="form-control " name="Othername" id="Othername">
                                                                </div>
                                                            </div>




                                                            <div class="col-xs-12 col-sm-6">
                                                                <div class="form-group">
                                                                    <label>Gender <span class="error">*</span></label>
                                                                    <div class="custom-select">
                                                                    <select class="form-control required-text" title="Gender"  name="Gender" id="Gender">
                                                                        <option value="">:Select Gender:</option>
                                                                        <option ="Male">Male </option>
                                                                        <option ="Female">Female </option>
                                                                    </select></div>
                                                                </div>
                                                            </div>


                                                        </div>
                                                        <!--end row-->

                                                        <div class="row">
                                                            <div class="col-xs-12 col-sm-6">
                                                                <div class="form-group">
                                                                    <label>Phone Number <span class="error">*</span></label>
                                                                    <input type="phone" title="Phone Number"  class="form-control required-text" name="phoneno" id="phoneno">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-12 col-sm-6">
                                                                <div class="form-group">
                                                                    <label>Email <span class="error">*</span></label>
                                                                    <input type="email" title="Email" class="form-control required-text" name="email" id="Email">
                                                                </div>
                                                            </div>


                                                        </div><!--end row-->
                                                        <div class="row">
                                                            <div class="col-xs-12 col-sm-12">
                                                                <div class="form-group">
                                                                    <label>Program <span class="error">*</span></label>
                                                                    <div class="custom-select">


                                                                        <select class="form-control required-text" title="Program"  name="Program" id="program">
                                                                            <?php echo $program_sel; ?>
                                                                    </select></div>
                                                                </div>
                                                            </div>

                                                        </div>


                                                        <div class="row">



                                                            <div class="col-xs-12 col-sm-6">
                                                                <div class="form-group">
                                                                    <label>Password <span class="error">*</span></label>
                                                                    <input type="password" title="Password" class="form-control required-text " name="password">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-12 col-sm-6">
                                                                <div class="form-group">
                                                                    <label> Confirm Password <span class="error">*</span></label>
                                                                    <input type="password" title="cPassword" class="form-control required-text" name="cpassword">
                                                                </div>
                                                            </div>
                                                        </div><!--end row-->
                                                        <div class="row">
                                                            <div id="display_message"  >
                                                            </div>
                                                        </div><!--end row-->
                                                        <button class="sidebar-search-btn disabled" type="submit" value="Submit" name="subbtn" id="subbtn" onclick="javascript:callpage('apply_now')" >Submit</button>


                                                         </form>
                                                </div>
                                            </div><!--end single content left-->
                                        </div>

                                        <div class="col-xs-12 col-sm-4 custom_left">
                                            <div class="sidebar">

                                                <div class="sidebar-box">
                                                    <div class="sidebar-box-inner">
                                                        <h3 class="sidebar-title">ADMISSION PROCEDURE</h3>


                                                        <ul type="i">
                                                            <li>
                                                                i.) Setup your Account
                                                            </li>
                                                            <li>
                                                                ii.) Check your Email to confirm the Account Setup
                                                            </li>
                                                            <li>
                                                                iii.) Print payment slip and take it to the Bank
                                                            </li>
                                                            <li>
                                                                iv.) Return to the portal to continue application
                                                            </li>
                                                            <li>

                                                            </li>
                                                            <li>

                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <?php
                                                //      include '_side_login.php';
                                                ?>
                                            </div>
                                        </div><!--end row-->
                                    </div>
                                </div><!--end custom content-->
                                <script>




                                </script>


                        </div>

                    </div>
                </div>

            </div>
        </div>
        </div></div>

    <?php include "footer.php"; ?>
    <!-- Footer Area End Here -->
</div>
<!-- Preloader Start Here -->
<div id="preloader"></div>
    <script src="js/jquery-2.2.4.min.js" type="text/javascript"></script>
    <!-- Plugins js -->
    <script src="js/plugins.js" type="text/javascript"></script>
    <!-- Bootstrap js -->
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <!-- WOW JS -->
    <script src="js/wow.min.js"></script>
    <!-- Nivo slider js -->
    <script src="vendor/slider/js/jquery.nivo.slider.js" type="text/javascript"></script>
    <script src="vendor/slider/home.js" type="text/javascript"></script>
    <!-- Owl Cauosel JS -->
    <script src="vendor/OwlCarousel/owl.carousel.min.js" type="text/javascript"></script>
    <!-- Meanmenu Js -->
    <script src="js/jquery.meanmenu.min.js" type="text/javascript"></script>
    <!-- Srollup js -->
    <script src="js/jquery.scrollUp.min.js" type="text/javascript"></script>
    <!-- jquery.counterup js -->
    <script src="js/jquery.counterup.min.js"></script>
    <script src="js/waypoints.min.js"></script>
    <!-- Countdown js -->
    <script src="js/jquery.countdown.min.js" type="text/javascript"></script>
    <!-- Isotope js -->
    <script src="js/isotope.pkgd.min.js" type="text/javascript"></script>
    <!-- Magic Popup js -->
    <script src="js/jquery.magnific-popup.min.js" type="text/javascript"></script>
    <!-- Custom Js -->
<script src="js/jquery.blockUI.js" type="text/javascript"></script>
<script src="js/main.js" type="text/javascript"></script>
<script src="js/main_.js" type="text/javascript"></script>
</body>

</body>


<!-- Mirrored from www.radiustheme.com/demo/html/academics/academics/about1.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 23 Sep 2019 17:50:20 GMT -->
</html>
