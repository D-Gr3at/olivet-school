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

	$query = "SELECT * FROM app_applicant_account_setup appl INNER JOIN student_information si ON si.reg_id = appl.reg_id WHERE si.reg_id=$reg_id";
	$student_details_resource = mysql_query($query);
	$details = mysql_fetch_array($student_details_resource);
	$student_id = $details['student_id'];
	$department_option_id = $dbobject->getitemlabel('student_information', 'student_id', $student_id, 'program');
	$department_id = $dbobject->getitemlabel('programme_setup', 'programme_id', $department_option_id, 'department_id');
	$level = $dbobject->getitemlabel('student_information', 'student_id', $student_id, 'level');

	$session_id = $dbobject->getitemlabel('session_setup', 'status', 1, 'session_id');
	$session_name = $dbobject->getitemlabel('session_setup', 'session_id', $session_id, 'session_name');
	$today = date('Y-m-d H:i:s');
	$semester_number = 0;
	$first_semester = $dbobject->db_query("SELECT * FROM semester_setup WHERE semester_name = 'First Semester' AND academic_session = ".$session_id);
	$first_semester = $first_semester[0];
	if($today >= $first_semester['semester_start'] && $today <= $first_semester['semester_end']){
		$semester_number = 1;
	}
	$second_semester = $dbobject->db_query("SELECT * FROM semester_setup WHERE semester_name = 'Second Semester' AND academic_session = ".$session_id);
	$second_semester = $second_semester[0];
	if($today >= $second_semester['semester_start'] && $today <= $second_semester['semester_end']){
		$semester_number = 2;
	}

	if($department_option_id != NULL){
		$course_reg_closure_query = "SELECT closure_date FROM curriculum_setup_tbl WHERE semester = ".$semester_number." AND level = ".$level." AND department_id = ".$department_id." AND programme_id = ".$department_option_id;
	}
	$registration_closure = $dbobject->db_query($course_reg_closure_query);
	$registration_closure = $registration_closure[0];
	$format = 'Y-m-d H:i:s';
	$date = $registration_closure['closure_date'];
	$date_obj = DateTime::createFromFormat($format, $date);
	$deadline = date_format($date_obj, 'l, F d, Y');
	if($date >= $today){
		if($department_option_id != NULL){
			$compulsory_query = "SELECT course_id, course_title, course_code, course_unit FROM course_setup_tbl AS cst
			INNER JOIN curriculum_courses_tbl AS cct ON cct.selected_course_id = cst.course_id
			INNER JOIN curriculum_setup_tbl AS cur ON cct.curriculum_setup_fk = cur.curriculum_id
									WHERE cur.semester = ".$semester_number." AND cct.is_elective = 0 AND cur.level =".$level." AND cur.department_id =".$department_id." AND cur.programme_id = ".$department_option_id;
		// echo $compulsory_query;
	
			$elective_query = "SELECT course_id, course_title, course_code, course_unit FROM course_setup_tbl AS cst
					INNER JOIN curriculum_courses_tbl AS cct ON cct.selected_course_id = cst.course_id
					INNER JOIN curriculum_setup_tbl AS cur ON cct.curriculum_setup_fk = cur.curriculum_id
					WHERE cur.semester = ".$semester_number." AND cct.is_elective = 1 AND cur.level =".$level." AND cur.department_id =".$department_id." AND cur.programme_id = ".$department_option_id;
			
			// var_dump($compulsory);
		}
		
?>
	<script>
		swal({
			title: "Notice!",
			icon: "warning",
			text: `Course Registration ends: <?php echo  $deadline; ?>`,
			button:{
				text: "OK",
				value: true,
				visible: true,
				className: "btn-primary",
				closeModal: true,
			},
			closeOnClickOutside: false
		});
	</script>
	<?php 
	} else {
		?>
		<script>
			swal({
				title: "Notice!",
				icon: "error",
				text: "Course registration closed.",
				button:{
					text: "OK",
					value: true,
					visible: true,
					className: "btn-primary",
					closeModal: true,
				},
				closeOnClickOutside: false
			});
		</script>
		<?php
	}
	
	$compulsory = $dbobject->db_query($compulsory_query);
	$elective = $dbobject->db_query($elective_query);

	// var_dump($compulsory);
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

    }else{
        //header("Location:../../index.php");
    }
}else{
    header("Location:../index.php");
}
$reg_status = $dbobject->getitemlabel('app_applicant_account_setup', 'reg_id', $reg_id, 'reg_status');
$rrr_status = $dbobject->getitemlabel('app_applicant_account_setup', 'reg_id', $reg_id, 'rrr_status');

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
		input{
			outline: none;
			border: none;
			background-color: #fff;
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
			<h5 class="card-title mb-0">Course Registration</h5>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-sm-3">
					<span class="display-5">Session:</span><br/>
					<span><?php echo $session_name; ?></span>
				</div>
				<div class="col-sm-3">
					<span class="display-5">Semester:</span><br/>
					<span><?php echo $semester_number == 1? "FIRST SEMESTER": $semester_number == 2? "SECOND SEMESTER": "BREAK"; ?></span>
				</div>
				<div class="col-sm-3">
					<span class="display-5">Level:</span><br/>
					<span><?php echo $level; ?></span>
				</div>
				<div class="container d-flex justify-content-end" id="closure_date"></div>
				<div class="col-sm-12">
					<img src="./olivet/admin/img/avatars/avatar-2.jpg" alt="" width="100" height="100" class="avatar avatar-100 photo lazy-loaded">
				</div>
				<div class="col-md-12">
					<form name="courseRegForm" id="courseRegForm" onSubmit="return false">
						<input type="hidden" name="department_id" id="department_id" value="<?php echo $department_id; ?>">
						<input type="hidden" name="department_option_id" id="department_option_id" value="<?php echo $department_option_id; ?>">
						<input type="hidden" name="student_id" id="student_id" value="<?php echo $student_id; ?>">
						<input type="hidden" name="level" id="level" value="<?php echo $level; ?>">
						<input type="hidden" name="semester" id="semester" value="<?php echo $semester_number; ?>">
						<fieldset>
							<!-- <div class="row bg-light">
								<div class="col-sm-4"></div>
								<legend class="col-sm-4 text-center text-uppercase">
									Course Registration
								</legend>
								<div class="col-sm-4"></div>
							</div> -->
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
													<tbody id="compulsory_courses_section">
														<?php if($compulsory != NULL){ $serial_number = 0; $total_credit_load = 0; foreach($compulsory as $key => $value){ $serial_number++; $total_credit_load += (int) $value['course_unit'] ?>
															<tr class="d-flex">
																<td class="col-1"><?php echo $serial_number; ?></td>
																<input type="hidden" name="compulsory_course_id[]" id="course_id" value="<?php echo $value['course_id'] ?>" class="form-control text-center"/>
																<td class="col-6"><input type="text" name="course_title[]" id="course_title${index}" value="<?php echo $value['course_title'] ?>" class="form-control" readonly/></td>
																<td class="col-2"><input type="text" name="course_code[]" id="course_code${index}" value="<?php echo $value['course_code'] ?>" class="form-control text-center" readonly/></td>
																<td class="col-2"><input type="text" name="course_unit[]" id="course_unit${index}" value="<?php echo $value['course_unit'] ?>" class='form-control text-center' readonly/></td>
																<td class="col-1"></td>
															</tr>
														<?php } } else {?>
															<tr class="d-flex">
																<td class="col-12">
																	<div class='d-flex justify-content-center'><span class='display-4 mt-3' style='font-size: 1.5em'>No available courses </span></div>
																</td>
															</tr>														
														<?php } ?>
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
															<th class="col-1">Select</th>
														</tr>
													</thead>
													<tbody id="elective_courses_section">
													<?php if($elective != NULL){ foreach($elective as $key => $value){ $serial_number++;?>
														<tr class="d-flex">
															<td class="col-1"><?php echo $serial_number; ?></td>
															<input type="hidden" name="elected_course_id[]" id="elected_course_id" value="<?php echo $value['course_id'] ?>" class="form-control text-center"/>
															<td class="col-6"><input type="text" name="elected_course_title[]" id="elected_course_title${index}" value="<?php echo $value['course_title'] ?>" class="form-control" readonly/></td>
															<td class="col-2"><input type="text" name="elected_course_code[]" id="elected_course_code${index}" value="<?php echo $value['course_code'] ?>" class="form-control text-center" readonly/></td>
															<td class="col-2"><input type="text" name="elected_course_unit[]" id="elected_course_unit${index}" value="<?php echo $value['course_unit'] ?>" class='form-control text-center' readonly/></td>
															<td class="col-1"><input type="checkbox" id="elected_course<?php echo $key; ?>" class="elected_course" onclick="changeUnit(<?php echo $value['course_unit']; ?>,<?php echo $key ?>)"/></td>
															<input type="hidden" name="elected_course[]" id="elected_course_value<?php echo $key; ?>" value="No">
														</tr>
														<?php } } else {?>
															<tr class="d-flex">
																<td class="col-12">
																	<div class='d-flex justify-content-center'><span class='display-4 mt-3' style='font-size: 1.5em'>No available courses </span></div>
																</td>
															</tr>
														<?php } ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="container">
									<div class="row">
										<div id="total_unit" class="col-sm-12 d-flex justify-content-end">
											<span style='font-size: 1.2em'>Total Credit Unit: <span id='tcl' class=""><?php echo $total_credit_load !=NULL? $total_credit_load: 0; ?></span></span>
										</div>
									</div>
								</div>
							</div>
						</fieldset>
						<?php if($compulsory != NULL){ ?>
							<div class="container d-flex justify-content-end mt-5" id="submitBtn">
								<input type='submit' value='Register' class='btn btn-md btn-primary'>
							</div>
							<div class="container d-flex justify-content-center" id="message">
								<em class="text-danger">Total credit load should not exceed 24.</em>
							</div>
						<?php } ?>
					</form>
				</div>
			</div>
			<br/>
			<br/>
		</div>
	</div>
<?php }?>
<script>	
	$total_credit_load = $('#tcl').text();
	function changeUnit(element, id){
		if($("#elected_course"+id).prop('checked')){
			$('#elected_course_value'+id).val("Yes");
			$total_credit_load = parseInt($total_credit_load, 10);
			$total_credit_load += parseInt(element, 10);
		}else{
			$('#elected_course_value'+id).val("No");
			$total_credit_load -= parseInt(element, 10);
		}
		$('#tcl').html($total_credit_load);
	}
	</script>
<script src="js/main.js"></script>
</body>
</html>

						