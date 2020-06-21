<?php  require_once('admin/libs/dbfunctions.php');
$dbobject = new dbobject();
$sql = "SELECT * FROM app_slider WHERE status = '1'";
$slider_row = $dbobject->db_query($sql);

$sql2 = "SELECT * FROM app_course WHERE status = '1'";
$course_row = $dbobject->db_query($sql2);


$sql3 = "SELECT * FROM app_staff WHERE status = '1'";
$staff_row = $dbobject->db_query($sql3);

?>
<!doctype html>
<html class="no-js" lang="">


<!-- Mirrored from www.radiustheme.com/demo/html/academics/academics/ by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 23 Sep 2019 17:49:27 GMT -->
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
    <!-- ReImageGrid CSS -->
    <link rel="stylesheet" href="css/reImageGrid.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <!-- Modernizr Js -->
    <script src="js/modernizr-2.8.3.min.js"></script>
</head>
<style>
    p{
        text-align: justify !important;
        margin: 0 0 10px 0 !important;
    }
</style>

<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<!-- Add your site or application content here -->
<!-- Preloader Start Here -->
<div id="preloader"></div>
<!-- Preloader End Here -->
<!-- Main Body Area Start Here -->
<div id="wrapper">
    <!-- Header Area Start Here -->
  <?php include "header.php"; ?>
    <!-- Header Area End Here -->
    <!-- Slider 1 Area Start Here -->
    <div class="slider1-area overlay-default">
        <div class="bend niceties preview-1">
            <div id="ensign-nivoslider-3" class="slides">
                <?php
                //slider_id, slider_title, slider_imageurl, status, posteddate, postedby
                foreach($slider_row as $row){
                ?>
                <img src="<?php echo $row['slider_imageurl']  ?>" alt="slider" title="<?php echo "#".$row['slider_id']  ?>" />
               <?php } ?>
            </div>
            <?php $count =0;
            //slider_id, slider_title, slider_imageurl, status, posteddate, postedby
            foreach($slider_row as $row2){
                $count++;
                ?>
                <div id="<?php echo $row2['slider_id']  ?>" class="t-cn slider-direction">
                    <div class="slider-content s-tb slide-<?php echo $count;  ?>">
                        <div class="title-container s-tb-c">
                            <div class="title1"><?php echo $row2['slider_title']  ?></div>
                            <p style="font-size: 30px !important;"><?php echo $row2['slider_msg']  ?> </p>

                        </div>
                    </div>
                </div>
                <?php } ?>


        </div>
    </div>
    <!-- Slider 1 Area End Here -->
    <!-- Service 1 Area Start Here -->
    <!-- <div class="service1-area">
        <div class="service1-inner-area">
            <div class="container">
                <div class="row service1-wrapper">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 service-box1">
                        <div class="service-box-content">
                            <h3><a href="#">Well Equipped Facilities</a></h3>
                            <p>Eimply dummy text printing ypese tting industry.</p>
                        </div>
                        <div class="service-box-icon">
                            <i class="fa fa-home" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 service-box1">
                        <div class="service-box-content">
                            <h3><a href="#">Skilled Lecturers</a></h3>
                            <p>Eimply dummy text printing ypese tting industry.</p>
                        </div>
                        <div class="service-box-icon">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 service-box1">
                        <div class="service-box-content">
                            <h3><a href="#">Book Library & Store</a></h3>
                            <p>Eimply dummy text printing ypese tting industry.</p>
                        </div>
                        <div class="service-box-icon">
                            <i class="fa fa-book" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- Service 1 Area End Here -->
    <!-- About 1 Area Start Here -->
    <div class="about1-area">
        <div class="container">
            <div class="col-lg-8">
            <h5 class="about-title wow fadeIn" data-wow-duration="1s" data-wow-delay=".2s">WELCOME TO OLIVET COLLEGE</h5>

             <p style="text-align: justify !important;">   It is with great delight that I welcome all most heartily to our great College,
                    where we enforce academic excellence with godliness where we enforce our core value. </p>
                <p> Olivet College of Health is on the path of becoming world-class and various mechanisms have been put in place to attain this fact. I assure you parents/ guardians that your children/wards are in safe hands.</p>
<p>
    The college at the management has its programmes fully accredited by CHPRBN and NBTL council. The College was established 2012 with (Community Health Department & Medical Laboratory Department). The Olivet College of Health in this administration led by me as the provost of the college. I envision a college that is world class, which is run on a business template to achieve the standard/height that we aim for,
    we are going to achieve this feat as a team and are not ready to ‘catch’ this vision, would be left behind...<a href="">Read More</a>
</p>

            </div>
            <div class="col-lg-4">
                <div class="about-img-holder wow fadeIn" data-wow-duration="2s" data-wow-delay=".2s">
                <img src="img/about/1.jpg" width="650" height="428" alt="about" class="img-responsive" />
            </div>
            </div>
        </div>
    </div>
    <!-- About 1 Area End Here -->
    <!-- Courses 1 Area Start Here -->
    <?php if(count($course_row)>0){ ?>
    <div class="courses1-area">
        <div class="container">
            <h2 class="title-default-left">Featured Courses</h2>
        </div>
        <div id="shadow-carousel" class="container">
            <div class="rc-carousel" data-loop="true" data-items="4" data-margin="20" data-autoplay="false" data-autoplay-timeout="10000" data-smart-speed="2000" data-dots="false" data-nav="true" data-nav-speed="false" data-r-x-small="1" data-r-x-small-nav="true" data-r-x-small-dots="false" data-r-x-medium="2" data-r-x-medium-nav="true" data-r-x-medium-dots="false" data-r-small="2" data-r-small-nav="true" data-r-small-dots="false" data-r-medium="3" data-r-medium-nav="true" data-r-medium-dots="false" data-r-large="4" data-r-large-nav="true" data-r-large-dots="false">
                <?php
                //course_id, course_title, course_msg, course_image, status, apply_status
                foreach($course_row as $row_course){
                ?>
                <div class="courses-box1">
                    <div class="single-item-wrapper">
                        <div class="courses-img-wrapper hvr-bounce-to-bottom">
                            <img class="img-responsive" src="<?php echo $row_course['course_image']  ?>" alt="courses">
                            <a href="single-courses1.html"><i class="fa fa-link" aria-hidden="true"></i></a>
                        </div>
                        <div class="courses-content-wrapper">
                            <h3 class="item-title"><a href="#"><?php echo $row_course['course_title']  ?></a></h3>
                            <p class="item-content"><?php echo $row_course['course_msg']  ?></p>
                            <ul class="courses-info">
                                <li>
                                    <?php if($row_course['apply_status'] =="1"){ ?>
                                        <button type="button" class="btn btn-lg btn-success btn-block"  >Apply Now</button>


                                    <?php }else{  ?>
                                        <button type="button" class="btn btn-lg btn-danger btn-block"  >Coming Soon</button>


                                    <?php } ?>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                <?php } ?>


            </div>
        </div>
    </div>
    <?php } ?>
    <?php
    //course_id, course_title, course_msg, course_image, status, apply_status
    if(count($staff_row)>0){
    ?>
    <div class="lecturers-area">
        <div class="container">
            <h2 class="title-default-left">Our Skilled Lecturers</h2>
        </div>
        <div class="container">
            <div class="rc-carousel" data-loop="true" data-items="4" data-margin="30" data-autoplay="false" data-autoplay-timeout="10000" data-smart-speed="2000" data-dots="false" data-nav="true" data-nav-speed="false" data-r-x-small="1" data-r-x-small-nav="true" data-r-x-small-dots="false" data-r-x-medium="2" data-r-x-medium-nav="true" data-r-x-medium-dots="false" data-r-small="3" data-r-small-nav="true" data-r-small-dots="false" data-r-medium="4" data-r-medium-nav="true" data-r-medium-dots="false" data-r-large="4" data-r-large-nav="true" data-r-large-dots="false">
                <?php
                //staff_id, staff_name, designation, status, staff_imageurl
                foreach($staff_row as $row_staff){
                ?>
                <div class="single-item">
                    <div class="lecturers1-item-wrapper">
                        <div class="lecturers-img-wrapper">
                            <a href="#"><img class="img-responsive" src="<?php echo $row_staff['staff_imageurl']  ?>" alt="team"></a>
                        </div>
                        <div class="lecturers-content-wrapper">
                            <h3 class="item-title"><a href="#"><?php echo $row_staff['staff_name']  ?></a></h3>
                            <span class="item-designation"><?php echo $row_staff['designation']  ?></span>

                        </div>
                    </div>
                </div>
               <?php  } ?>
            </div>
        </div>
    </div>

<?php } ?>

    <!-- Footer Area Start Here -->
<?php include "footer.php"; ?>
    <!-- Footer Area End Here -->
</div>
<!-- Main Body Area End Here -->
<!-- jquery-->
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
<!-- Gridrotator js -->
<script src="js/jquery.gridrotator.js" type="text/javascript"></script>
<!-- Custom Js -->
<script src="js/main.js" type="text/javascript"></script>
</body>


<!-- Mirrored from www.radiustheme.com/demo/html/academics/academics/ by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 23 Sep 2019 17:50:06 GMT -->
</html>
