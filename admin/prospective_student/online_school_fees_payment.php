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
    if ($_REQUEST['MerchantTransactionID']){

        $result = $dbobject->onePayRequeryCart($_REQUEST['MerchantTransactionID']);
        if ($result == '0'){
            $update = "UPDATE app_applicant_account_set_up SET rrr_acceptance_status='1' WHERE reg_id = '$reg_id'";
            $query_update = mysql_query($update);
            $num = mysql_affected_rows();
            if ($num > 0){
                header("Location:home.php");
            }
        } else if($result == '02'){
            $code = $_REQUEST['MerchantTransactionID'];
        }
        
    }
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

        if(!$code){
            $time = time();
            $code = $time;
            for($i = 0; $i < 2; $i++) { $code .= mt_rand(0, 9); }
            @$now = date('Y-m-d H:i:s');
            $amount = "10000.00";
            $email= $row['email'];
            $str = "INSERT INTO app_onepay_transactions(amount_paid,merch_trans_id,product_desc,merchant_reg_id,client_name,client_email,client_phone,created)VALUE('$amount','$code','Acceptance Fee Payment','ACC-OPMHT000000235','$surname $othernaame','$email','$phone_number','$now')";
            $result =  mysql_query($str) or die(mysql_error());   
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
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
    <meta name="author" content="Bootlab">

    <title>Olivet College of Health Technology</title>

    <link rel="preconnect" href="http://fonts.gstatic.com/" crossorigin="">
    <link rel="icon" href="img/icon.png" sizes="32x32">
    <link rel="stylesheet" href="codebase/dhtmlxcalendar.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/owl.theme.css">
<style>
	.lds-ripple {
	  display: inline-block;
	  position: relative;
	  width: 64px;
	  height: 64px;
	}
	.lds-ripple div {
	  position: absolute;
	  border: 4px solid #07705b;
	  opacity: 1;
	  border-radius: 50%;
	  animation: lds-ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
	  text-align: center;
	  margin: auto;
	}
	.lds-ripple div:nth-child(2) {
	  animation-delay: -0.5s;
	}
	@keyframes lds-ripple {
	  0% {
	    top: 28px;
	    left: 28px;
	    width: 0;
	    height: 0;
	    opacity: 1;
	  }
	  100% {
	    top: -1px;
	    left: -1px;
	    width: 58px;
	    height: 58px;
	    opacity: 0;
	  }
	}
	#onepay_frame_loading {
		margin-top: 50px;
	}
	.btn {
		outline: 0;
	    background: #1C2440;
	    color: #fff;
	    background: #6F90FF;
	    border: 1px solid #6F90FF;
	    font-family: 'GothamMedium';
	    cursor: pointer;
	    padding: 10px 20px;
	}
</style>
</head>
<body><div style="margin:auto; text-align: center;">

        <div id="onepay_frame_loading">
            <h3 id="waiting_msg" style="text-align:center;"> Communicating Payment Gateway to Process Transaction. Please Wait...</h3>
            <div style=" -webkit-box-align:center;-webkit-box-pack:center;display:-webkit-box;">
                <div class="lds-ripple"><div></div><div></div></div>
            </div>
	</div>
    <div class="card">
        <div class="card-header">

            <div class="row">
                <div class="col-xs-12 col-sm-4 custom_left">
                    <h5 class="card-title mb-1"><p>Online School Fees Payment</p></h5>
                </div>
            </div>

        </div>
	<!-- <p class="text-center text danger">This Service is still under development</p> -->
	<form action="https://www.onepay.com.ng/api/live/main" method="POST" id="upay" target="onepay_frame" name="upay_form">
	    <input name="product_desc" id="product_desc" type="hidden" value="School Fees Payment">
	    <input name="merch_trans_id" id="merch_trans_id" type="hidden" value="<?php echo ($code); ?>">
	    <input name="merchant_reg_id" id="merchant_reg_id" type="hidden" value="ACC-OPMHT000000235">
	    <input name="client_email" id="client_email" type="hidden" value="<?php echo ($_SESSION['email']); ?>">
	    <input name="client_name" id="client_name" type="hidden" value="<?php echo ($surname." ".$othernaame); ?>">
	    <input name="client_phone" id="client_phone" type="hidden" value="<?php echo ($phone_number); ?>">
	    <input name="amt_paid" id="amt_paid" type="hidden" value="50000.00">
	</form>

	<div style=" -webkit-box-align:center;-webkit-box-pack:center;display:-webkit-box;">
		<iframe name="onepay_frame" id="onepay_frame" scrolling="no" width="500" height="650" style="border:none;z-index:9999;" align="center"></iframe>
    </div></div>
	</div>



<script>
	var loaded =  document.getElementById("onepay_frame_loading").innerHTML;

	document.querySelector('iframe').onload = function(){
	  frame_loaded();
	};

    document.getElementById("upay").submit();

    function frame_loaded() {
		document.getElementById("onepay_frame_loading").innerHTML = "";
    }

    function printReceipt() {
	    var prtContent = document.getElementById("receiptContent").innerHTML;
	    var WinPrint = window.open('', '', 'left=50,top=50,width=800,height=900,toolbar=0,scrollbars=0,status=0');
	    WinPrint.document.write(prtContent);
	    WinPrint.document.close();
	    WinPrint.focus();
	    WinPrint.print();
	    WinPrint.close();
	}
</script>
</body>
</html>