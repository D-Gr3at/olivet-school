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
<body>
<?php if ($admissionstatus == "1" && $second_status != "25" && $lock != 0) {?>
	<div class="card">
		<div class="card-header">
			<h5 class="card-title mb-0">School Fees Payment</h5>
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
									Welcome! ... You can now proceed to school fees payment
									<br/>
									The school fees cost NGN50,000 (fifty Thousand Naira only).
									<br/>
									Please note that transaction fees may apply
									<br/>
									To pay Online now via the application portal, click on 
									<a href="online_school_fees_payment.php" target="_blank" class="commonBtn" title="Proceed to pay your school fees using our online platform">School fees Online Payment</a>
									</td>
								</tr>
								<tr>
									<td class = "ui-helper-center">
											To pay to any Nigerian Bank of Choice via REMITA, click 
											<a href="javacript:void(0)" class="commonBtn" onClick="getpage('generste_bank_payment_school_fees.php','page');" title="Proceed to pay your school fees Via Remiter.">
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
</body>
</html>

						