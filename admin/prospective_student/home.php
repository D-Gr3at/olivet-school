<?php
error_reporting(0);
session_start();
include '../../lib/dbfunctions_extra.php';
$dbobject = new myDbObject();

//$Program = cryptoJsAesDecrypt(SONMPASSWORDKEY, $Program);
$email = '';
$reg_id = $_SESSION['reg_id'];

if(!isset($_SESSION['reg_id'])){
    header("Location:../index.php");
}

if (isset($reg_id)) {
    $result = $dbobject->getrecordset('app_applicant_account_setup', 'reg_id', $reg_id);
    $numrows = mysql_num_rows($result);

    //reg_id, session, email, surname, othernaame, phone_number, program, userpassword, created, rrr, reg_status, rrr_status, linkCode, date_of_birth, gender, marital_status, Nationality, state_of_origin, local_Gov_Area, District_word, tribe, religion, postal_address, exam_center, ip, gname, gaddress, statusCode, pay_status, pbirth, passport, fname, rrr_acceptance, rrr_acceptance_date, rrr_acceptance_status, application_lock, educational_status, admissionstatus
    if ($numrows > 0) {
		$row = mysql_fetch_array($result);
        $surname = $row['surname'];
        $othernaame = $row['othernaame'];
        $phone_number = $row['phone_number'];
        $program = $row['program'];
        $rrr = $row['rrr'];
        $program = $row['program'];
        $reg_status = $row['reg_status'];
        $rrr_status = $row['rrr_status'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['vcode'] = $row['linkCode'];
        $vcode = $row['linkCode'];
        $rr_es  = $row['rrr_acceptance'];
        $second_status = $row['rrr_acceptance_status'];
        $admissionstatus = $row['admissionstatus'];
        $lock = $row['application_lock'];
		$exam_center = $row['exam_center_id'];
		
		if ($admissionstatus == "1" && $second_status != "25" && $lock != 0) {
			header("Location:acceptance_page.php");
		 }

    }else{
        //header("Location:../../index.php");
    }
}else{
    header("Location:../index.php");
}
$reg_status = $dbobject->getitemlabel('app_applicant_account_setup', 'reg_id', $reg_id, 'reg_status');
$rrr_status = $dbobject->getitemlabel('app_applicant_account_setup', 'reg_id', $reg_id, 'rrr_status');
//reg_id, session, email, surname, othernaame, phone_number, program, userpassword, created, rrr, reg_status, rrr_status, linkCode, date_of_birth, gender, marital_status, Nationality, state_of_origin, local_Gov_Area, District_word, tribe, religion, postal_address, exam_center, ip, gname, gaddress, statusCode, pay_status, pbirth, passport, fname, rrr_acceptance, rrr_acceptance_date, rrr_acceptance_status, application_lock, educational_status, admissionstatus, date_adm, exam_center_id

?>
<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from appstack.bootlab.io/dashboard-default.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 26 Jul 2019 15:56:51 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
	<meta name="author" content="Bootlab">

	<title>Olivet College of Health Technology</title>

    <link rel="preconnect" href="http://fonts.gstatic.com/" crossorigin>
    <link rel="icon" href="img/icon.png" sizes="32x32" />
    <link rel="stylesheet" href="codebase/dhtmlxcalendar.css" />
    <link rel="stylesheet" href="css/owl.carousel.css" />
    <link rel="stylesheet" href="css/owl.theme.css" />

	<!-- PICK ONE OF THE STYLES BELOW -->
	<!-- <link href="css/classic.css" rel="stylesheet"> -->
	<!-- <link href="css/corporate.css" rel="stylesheet"> -->
	<!-- <link href="css/modern.css" rel="stylesheet"> -->

	<!-- BEGIN SETTINGS -->
	<!-- You can remove this after picking a style -->
	<style>
		body {
			opacity: 0;
		}
		/* -- Timeline --*/
.timeline-container {
  width: 100%;
  /* background: #f9f9f9; */
  border-radius: 5px;
  padding-top:10px;
  /* padding: 15px; */
}
.timeline{
	position: relative;
}

/*Line*/
.timeline>li::before{
	content:'';
	position: absolute;
	width: 1px;
	background-color: #E7E7E7;
	top: 0;
	bottom: 0;
	left:-19px;
}


/*Circle*/
.timeline>li::after{
    text-align: center;
    padding-top:10px;
	z-index: 10;
	content:counter(item);
	position: absolute;
	width: 50px;
	height: 50px;
	border:3px solid white;
	background-color: #E7E7E7;
	border-radius: 50%;
	top:0;
	left:-43px;
}

/*Content*/
.timeline>li{
	counter-increment: item;
	padding: 15px 15px;
	margin-left: 0px;
	min-height:65px;
	position: relative;
	background-color: #f5a065;
  list-style: circle;
  margin-bottom: 0;
  text-transform: uppercase;
  border-bottom: 1px solid #f3f3f3;
}
.timeline>li.active { background: #dd8243; color: #dd8243;}
.timeline>li:nth-last-child(1)::before{
	width: 0px;
}
.timeline-container>.timeline>li>a{
	color:#000;
	font-weight:bold;
	display:block;
}
.timeline-container>.timeline>li>a:hover{
	text-decoration:none;
}

	</style>
	<script src="js/settings.js"></script>
	<!-- END SETTINGS -->
<!-- Global site tag (gtag.js) - Google Analytics -->

    <script src="js/app.js"></script>
    <script src="js/jquery.blockUI.js"></script>
	<script src="js/parsely.js"></script>

	<script src="js/sweet_alerts.js"></script>
    <script src="../../js/jquery.blockUI.js"></script>
	<script src="../../js/main_.js"></script>
	<script src="codebase/dhtmlxcalendar.js"></script>
</head>

<body style="background:#fff">
	<div class="wrapper">
		<div class="main">
			<nav class="navbar navbar-expand navbar-light bg-white" style="height:150px; position:inherit">
				<h3><img src="img/logo.jpg" height="50" width="37" alt=""> Olivet College</h3>
				<div class="navbar-collapse collapse">
					<ul class="navbar-nav ml-auto">


						<li class="nav-item dropdown">
                        <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings align-middle"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        </a>

							<a class="nav-link  d-none d-sm-inline-block" href="#" data-toggle="">
							<h3>Prospective Student Portal</h3>
              </a>

						</li>
					</ul>
				</div>
			</nav>
			<input type="hidden" id="reg_id" name="reg_id" value="<?php echo($reg_id); ?>">

			<main class="content" style="background:url(img/college_banner.jpg) no-repeat;  ">
				<div class="container-fluid" style="background:#fff; padding:17px; width:90%;z-index:999999; margin-top:-70px; border: 1px solid #e5e9f2;  ">
					<div class="row">
						<div class="col-sm-12">
							<h1 class="h3 mt-0 mb-0" align="center"></h1>
							<h1 class="h3 mb-3">Welcome back, <?php  echo $surname."!"; ?></h1>
							<?php if ($admissionstatus == "1" && $second_status != "25" && $lock != 0) {?>
							<p>You have been offered provisional admission into Olivet Make payment to accept admission offer and Print out you admission letter</p>
							<?php }?>
						</div>
					</div>
					

					<div class="row">
						<div class="col-md-3 col-xl-3">
							<div class="card" style="border:none">
								<div class="card-header" style="border: 1px solid #e5e9f2; background:#40c7d0;">
									<h5 class="card-title mb-0" style="color:#fff; font-weight:bold">Navigation Menu</h5>
								</div>
								<div class="card-body" style="border:none;padding:0">
									<div class="timeline-container">
										<ol class="timeline">
                                            <?php if($lock !='1'){  ?>
                                                <li id="payment" class="active">
                                                    <a href="javascript:void(0)" onclick="getpage('personal_data.php','page','personal')">Make Payment</a>
                                                </li>
                                                <li id="biodata">
                                                    <a href="javascript:void(0)" onclick="getpage('apply_now_step_one.php','page','bank')">Biodata</a>
                                                </li>

                                                <li id="education">
                                                    <a href="javascript:void(0)" onclick="getpage('apply_now_step_two.php','page','employment')">
                                                        Educational History
                                                    </a>
                                                </li>
                                                <li id="submitbiodata"><a href="javascript:void(0)" onclick="getpage('apply_now_step_three.php','page','medical')">Submit Application</a>   </li>


                                            <?php }else if ($admissionstatus == "1"){ ?>

                                                <li id="personal" class="active">
                                                    <a href="javascript:void(0)" onclick="getpage('acceptance_page.php','page','employment')"  >Acceptance Fee Payment</a>
												</li>
												<!-- <li id="personal" class=" ">
                                                    <a href="home.php">School Fees Payment</a>
												</li> -->
											<?php } else {?>
                                                <li id="bank">
                                                    <a href="javascript:void(0)" onclick="getpage('bank_details.php','page','bank')">Get payment Code</a>
                                                </li>

                                                <li id="employment">
                                                    <a href="javascript:void(0)" onclick="getpage('employment.php','page','employment')">
                                                        Confirm Payment
                                                    </a>
                                                </li>
                                                <li id="medical"><a href="javascript:void(0)" onclick="getpage('medical_details.php','page','medical')">Complete Registration</a>   </li>
                                                <li id="dependants"> <a href="javascript:void(0)" onclick="getpage('change_password.php','page','dependants')"> Change Password</a> </li>

                                            <?php  } ?>

										</ol>
										<a href="javascript:window.location='logout.php'" class="btn btn-block bg-danger text-white">Logout</a>
									</div>
								</div>



								<!-- <ol class="list-group list-group-flush" role="tablist">
									<li class=" list-group-item-action active" data-toggle="list" role="tab">
										<a class="list-group-item"  href="#account" >
										Make Payment
										</a>
									</li>

									<a class="list-group-item list-group-item-action" data-toggle="list" href="#password" role="tab">
									Get payment Code
									</a>
									<a class="list-group-item list-group-item-action" data-toggle="list" href="#" role="tab">
									Confirm Payment
									</a>
									<a class="list-group-item list-group-item-action" data-toggle="list" href="#" role="tab">
									Complete Registration
									</a>
									<a class="list-group-item list-group-item-action" data-toggle="list" href="#" role="tab">
									Change Password
									</a>
									<a class="list-group-item list-group-item-action bg-danger text-white" data-toggle="list" href="#" role="tab">
									Logout
									</a>
								</ol> -->
							</div>
						</div>

						<div class="col-md-9 col-xl-9">
							<div class="tab-content">
								<div class="tab-pane fade show active" id="page" role="tabpanel">
								<?php if ($admissionstatus == "1" && $second_status != "25" && $lock != 0) {?>
										<div class="card">
											<div class="card-header">
												<h5 class="card-title mb-0">Acceptance Form Purchace</h5>
											</div>
											<div class="card-body">
												<div class="row">
													<div class="col-md-8">
														<form name="form1" id="form1" onSubmit="return false">
															<input type="hidden" name="vcode" value="<?php $vcode; ?>">
															<br/>
															<br/>
															<table>
																<tbody>
																	<tr>
																		<td class = "ui-helper-center">
																		Congratulations! ... You have been Offered a Provisional Admission into 
																		Olivet College. 
																		<br/>
																		The acceptance form cost NGN10,000 (Ten Thousand Naira only).
																		<br/>
																		Please note that transaction fees may apply
																		<br/>
																		To pay Online now via the application portal, click on 
																		<a href="online_payment.php" target="_blank" class="commonBtn">Acceptance Online Payment</a>
																		</td>
																	</tr>
																	<tr>
																		<td class = "ui-helper-center">
																				To pay to any Nigerian Bank of Choice via REMITA, click 
																				<a href="javacript:void(0)" class="commonBtn" onClick="getpage('generste_bank_payment.php','page');">
																					Pay at any Branch
																				</a>
																		</td>
																	</tr>
																</tbody>
															</table>
														</form>
													</div>
												</div>
												<br/>
												<br/>
												<div class="row">
													<div class="col d-flex justify-content-center">
															<div>
																<img src="img/remita-logo.png" width="500" height="96">
															</div>
													</div>
												</div>
											</div>
										</div>
								<?php }?>
									<!-- <div class="card">
										<div class="card-header">
											<div class="card-actions float-right">
												<div class="dropdown show">
													<a href="#" data-toggle="dropdown" data-display="static">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
													</a>

													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item" href="#">Action</a>
														<a class="dropdown-item" href="#">Another action</a>
														<a class="dropdown-item" href="#">Something else here</a>
													</div>
												</div>
											</div>
											<h5 class="card-title mb-0">Public info</h5>
										</div>
										<div class="card-body">
											<form>
												<div class="row">
													<div class="col-md-8">
														<div class="form-group">
															<label for="inputUsername">Username</label>
															<input type="text" class="form-control" id="inputUsername" placeholder="Username">
														</div>
														<div class="form-group">
															<label for="inputUsername">Biography</label>
															<textarea rows="2" class="form-control" id="inputBio" placeholder="Tell something about yourself"></textarea>
														</div>
													</div>
													<div class="col-md-4">
														<div class="text-center">
															<img alt="Chris Wood" src="img/avatars/avatar.jpg" class="rounded-circle img-responsive mt-2" width="128" height="128">
															<div class="mt-2">
																<span class="btn btn-primary"><i class="fas fa-upload"></i> Upload</span>
															</div>
															<small>For best results, use an image at least 128px by 128px in .jpg format</small>
														</div>
													</div>
												</div>

												<button type="submit" class="btn btn-primary">Save changes</button>
											</form>

										</div>
									</div> -->

									<!-- <div class="card">
										<div class="card-header">
											<div class="card-actions float-right">
												<div class="dropdown show">
													<a href="#" data-toggle="dropdown" data-display="static">
                  										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                									</a>

													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item" href="#">Action</a>
														<a class="dropdown-item" href="#">Another action</a>
														<a class="dropdown-item" href="#">Something else here</a>
													</div>
												</div>
											</div>
											<h5 class="card-title mb-0">Private info</h5>
										</div>
										<div class="card-body">
											<form>
												<div class="form-row">
													<div class="form-group col-md-6">
														<label for="inputFirstName">First name</label>
														<input type="text" class="form-control" id="inputFirstName" placeholder="First name">
													</div>
													<div class="form-group col-md-6">
														<label for="inputLastName">Last name</label>
														<input type="text" class="form-control" id="inputLastName" placeholder="Last name">
													</div>
												</div>
												<div class="form-group">
													<label for="inputEmail4">Email</label>
													<input type="email" class="form-control" id="inputEmail4" placeholder="Email">
												</div>
												<div class="form-group">
													<label for="inputAddress">Address</label>
													<input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
												</div>
												<div class="form-group">
													<label for="inputAddress2">Address 2</label>
													<input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
												</div>
												<div class="form-row">
													<div class="form-group col-md-6">
														<label for="inputCity">City</label>
														<input type="text" class="form-control" id="inputCity">
													</div>
													<div class="form-group col-md-4">
														<label for="inputState">State</label>
														<select id="inputState" class="form-control">
															<option selected="">Choose...</option>
															<option>...</option>
														</select>
													</div>
													<div class="form-group col-md-2">
														<label for="inputZip">Zip</label>
														<input type="text" class="form-control" id="inputZip">
													</div>
												</div>
												<button type="submit" class="btn btn-primary">Save changes</button>
											</form>

										</div>
									</div> -->

								</div>
								<div class="tab-pane fade" id="password" role="tabpanel">
									<div class="card">
										<div class="card-body">
											<h5 class="card-title">Password</h5>

											<form>
												<div class="form-group">
													<label for="inputPasswordCurrent">Current password</label>
													<input type="password" class="form-control" id="inputPasswordCurrent">
													<small><a href="#">Forgot your password?</a></small>
												</div>
												<div class="form-group">
													<label for="inputPasswordNew">New password</label>
													<input type="password" class="form-control" id="inputPasswordNew">
												</div>
												<div class="form-group">
													<label for="inputPasswordNew2">Verify password</label>
													<input type="password" class="form-control" id="inputPasswordNew2">
												</div>
												<button type="submit" class="btn btn-primary">Save changes</button>
											</form>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</main>

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-left">
							<ul class="list-inline">

								<li class="list-inline-item">
									<a class="text-muted" href="#">Help Center</a>
								</li>

							</ul>
						</div>
						<div class="col-6 text-right">
							<p class="mb-0">
								&copy; <?php echo date('Y'); ?> - <a target="_blank" href="https://www.tlccrm.org/" class="text-muted">Olivet College</a>
							</p>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>
	<style>
        .dhtmlxcalendar_material
        {
            z-index: 99999 !important;
        }
    </style>
<link rel="stylesheet" href="codebase/dhtmlxcalendar.css" />
<script src="codebase/dhtmlxcalendar.js"></script>
<script src="js/owl.carousel.js"></script>
    <script src="../../js/main_.js"></script>


	<script>
		$(function() {
			$("#datatables-dashboard-projects").DataTable({
				pageLength: 6,
				lengthChange: false,
				bFilter: false,
				autoWidth: false
			});
		});
	</script>

<div class="modal fade" id="defaultModalPrimary" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="modal_div">
            <div class="modal-header">
                <h5 class="modal-title">Default modal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body m-3">
                <p class="mb-0">Use Bootstrap’s JavaScript modal plugin to add dialogs to your site for lightboxes, user notifications, or completely custom content.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
</body>
<script>
	function generateRRR(){
		var reg_id = $('#reg_id').val();
        $.post("utilities.php",{op:"Payments.acceptanceRRR",reg_id:reg_id},function(rr){
					if(rr.response_code == "0"){
						alert(rr.response_message);
						getpage('generste_bank_payment.php','page');
					} else {
						alert(rr.response_message);
						// getpage('generste_bank_payment.php','page');
					}
        
                },'json');
    }
</script>

<!-- Mirrored from appstack.bootlab.io/dashboard-default.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 26 Jul 2019 15:57:08 GMT -->
</html>
