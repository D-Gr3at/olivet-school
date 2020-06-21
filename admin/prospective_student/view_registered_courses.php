<?php
error_reporting(1);
session_start();

include '../../lib/dbfunctions_extra.php';
$dbobject = new myDbObject();

//$Program = cryptoJsAesDecrypt(SONMPASSWORDKEY, $Program);
$email = '';
$reg_id = $_SESSION['reg_id'];

var_dump($reg_id);

if(!isset($_SESSION['reg_id'])){
    header("Location:../index.php");
}

if (isset($reg_id)) {
    $result = $dbobject->getrecordset('app_applicant_account_setup', 'reg_id', $reg_id);
	$numrows = mysql_num_rows($result);
	// var_dump($numrows);

	$query = "SELECT * FROM app_applicant_account_setup appl INNER JOIN student_information si ON si.reg_id = appl.reg_id WHERE si.reg_id=$reg_id";
	$student_details_resource = mysql_query($query);
	$details = mysql_fetch_array($student_details_resource);
	$student_id = $details['student_id'];
	$department_option_id = $dbobject->getitemlabel('student_information', 'student_id', $student_id, 'program');
	$department_id = $dbobject->getitemlabel('programme_setup', 'programme_id', $department_option_id, 'department_id');
	$faculty_id = $dbobject->getitemlabel('department_setup_tbl', 'dapartment_id', $department_id, 'faculty_code');
	$level = $dbobject->getitemlabel('student_information', 'student_id', $student_id, 'level');


    //reg_id, session, email, surname, othernaame, phone_number, program, userpassword, created, rrr, reg_status, rrr_status, linkCode, date_of_birth, gender, marital_status, Nationality, state_of_origin, local_Gov_Area, District_word, tribe, religion, postal_address, exam_center, ip, gname, gaddress, statusCode, pay_status, pbirth, passport, fname, rrr_acceptance, rrr_acceptance_date, rrr_acceptance_status, application_lock, educational_status, admissionstatus
    if ($numrows > 0) {
		$faculty_names_sql = "SELECT faculty_id, faculty_name FROM faculty_settup";//here
		$faculty_names = mysql_query($faculty_names_sql) or die(mysql_error());
		$faculties = mysql_num_rows($faculty_names);
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
		
		// if ($admissionstatus == "1" && $second_status != "25" && $lock != 0) {
		// 	header("Location:acceptance_page.php");
		//  }

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
<html lang="en">
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

		.printBtn:hover{
			cursor: pointer;
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

		.display {
			border-top-style: hidden;
			border-right-style: hidden;
			border-left-style: hidden;
			border-bottom-style: hidden;
			background-color: #eee;
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
<body>
<?php if ($admissionstatus == "1" && $second_status != "25" && $lock != 0) {?>
	<div class="card">
		<div class="card-header">
			<h5 class="card-title mb-0">View Registered Courses</h5>
		</div>
		<div class="card-body">
			<div class="row">
				<!-- <div class="col-sm-3">
					<span class="display-5">Faculty:</span><br/>
					<span><?php echo $faculty_name; ?></span>
				</div>
				<div class="col-sm-3">
					<span class="display-5">Department:</span><br/>
					<span><?php echo $department_name; ?></span>
				</div>
				<div class="col-sm-3">
					<span class="display-5">Department Option:</span><br/>
					<span><?php echo $department_option_name; ?></span>
				</div>
				<div class="col-sm-3">
					<span class="display-5">Current Level:</span><br/>
					<span><?php echo $level; ?></span>
				</div> -->
				<div class="col-sm-12">
					<img src="./olivet/admin/img/avatars/avatar-2.jpg" alt="" width="100" height="100" class="avatar avatar-100 photo lazy-loaded">
				</div>
				<div class="col-md-12">
					<form name="courseRegForm" id="viewCourseForm" onSubmit="return false" method="POST">
						<input type="hidden" name="department_id" id="department_id" value="<?php echo $department_id; ?>">
						<input type="hidden" name="department_option_id" id="department_option_id" value="<?php echo $department_option_id; ?>">
						<input type="hidden" name="student_id" id="student_id" value="<?php echo $student_id; ?>">
						<input type="hidden" name="level" id="level" value="<?php echo $level; ?>">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label class="form-label">Semester<span class="asterik">*</span></label>
									<select class='form-control' name='registered_semester' id='registered_semester'>
										<option value='' selected='selected'>::SELECT SEMESTER::</option>
										<option value="1">First Semester</option>
										<option value="2">Second Semester</option>
									</select>
								</div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label">Level<span class="asterik">*</span></label>
                                    <select class='form-control' name='registered_level' id='registered_level'>
                                        <option value='' selected='selected'>::SELECT LEVEL::</option>
                                        <?php
                                            for($year = 100; $year<=$level; $year+=100){
                                                echo "<option value='".$year."'>".$year."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
								<input type='submit'  value='View' id="viewBtn" name="view" class='btn btn-md btn-primary  mt-4 ml-5'>
                            </div>
						</div>
					</form>
				</div>
				<div class="col-md-12">
					<fieldset>
						<div class="row bg-light">
							<div class="col-sm-4"></div>
							<legend class="col-sm-4 text-center text-uppercase">
								Registered Courses
							</legend>
							<div class="col-sm-4"></div>
						</div>
						<div class="row">
								<div class="col-sm-12">
									<div class="row">
										<div class="col-sm-1"></div>
										<span class="col-sm-10 font-weight-bold text-uppercase ml-2" style="font-size: 1.5em">Compulsory Courses</span>
										<div class="col-sm-1"></div>
									</div>								</div>
								<div class="col-sm-12">
									<div class="container mb-3">
										<div class="row justify-content-center">
											<div class="col-sm-12">
												<table class="table">
													<thead style="border: 1px solid #e5e9f2; background:#40c7d0;">
														<tr class="d-flex" style="color:#fff; font-weight:bold">
															<th class="col-1">#</th>
															<th class="col-6">Course Title</th>
															<th class="col-2">Course Code</th>
															<th class="col-2">Course Unit</th>
															<th class="col-1"></th>
														</tr>
													</thead>
													<tbody id="compulsory_section">
														
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="row">
										<div class="col-sm-1"></div>
										<span class="col-sm-10 font-weight-bold text-uppercase ml-2" style="font-size: 1.5em">Elective Courses</span>
										<div class="col-sm-1"></div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="container mb-3">
										<div class="row justify-content-center">
											<div class="col-sm-12">
												<table class="table">
													<thead style="border: 1px solid #e5e9f2; background:#40c7d0;">
														<tr class="d-flex" style="color:#fff; font-weight:bold">
															<th class="col-1">#</th>
															<th class="col-6">Course Title</th>
															<th class="col-2">Course Code</th>
															<th class="col-2">Course Unit</th>
															<th class="col-1"></th>
														</tr>
													</thead>
													<tbody id="elective_section">
														
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="container">
								<div class="row">
									<div id="total_unit" class="col-sm-12 d-flex justify-content-end">
										<span style='font-size: 1.2em'>Total Credit Unit: <span id='tcl'>0</span></span>
									</div>
								</div>
							</div>
						</div>
					</fieldset> 
				</div>
				<div class="container d-flex justify-content-end mt-5" id="printBtn"></div> 
			</div>
			<br/>
			<br/>
		</div>
	</div>
<?php }?>
<script src="js/main.js"></script>
</body>
</html>

						