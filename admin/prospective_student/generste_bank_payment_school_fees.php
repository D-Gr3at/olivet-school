<?php
error_reporting(0);
session_start();
include '../../lib/dbfunctions_extra.php';
$dbobject = new myDbObject();

//$Program = cryptoJsAesDecrypt(SONMPASSWORDKEY, $Program);
$email = '';
$reg_id = $_SESSION['reg_id'];
//var_dump($reg_id);

if(!isset($_SESSION['reg_id'])){
    header("Location:../index.php");
}

if (isset($reg_id)) {
    $result = $dbobject->getrecordset('app_applicant_account_setup', 'reg_id', $reg_id);
    $numrows = mysql_num_rows($result);
    
    //reg_id, session, email, surname, othernaame, phone_number, program, userpassword, created, rrr, reg_status, rrr_status, linkCode, date_of_birth, gender, marital_status, Nationality, state_of_origin, local_Gov_Area, District_word, tribe, religion, postal_address, exam_center, ip, gname, gaddress, statusCode, pay_status, pbirth, passport, fname, rrr_acceptance, rrr_acceptance_date, rrr_acceptance_status, application_lock, educational_status, admissionstatus
    if ($numrows > 0) {
        $row = mysql_fetch_array($result);
        //var_dump($row);
        $surname = $row['surname'];
        $othernaame = $row['othernaame'];
        $phone_number = $row['phone_number'];
        $program = $row['program'];
        $_SESSION['rrr'] = $row['rrr'];
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
        $pay_status = $row['pay_status'];
		
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

<div class="card">
    <div class="card-header">

        <div class="row">
            <div class="col-xs-12 col-sm-4 custom_left">
                <h5 class="card-title mb-1"><p>School Fees payment</p></h5>
            </div>
        </div>
    </div>
    <br>
<div class="card-body">
<div class="row">
<div class="col-md-8">
    <form name="form1" id="form1" onsubmit="return false">
    <input type="hidden" name="vcode" value="<?php echo($_SESSION['vcode']); ?>">
    <table>

        <tr>
            <td class="ui-helper-center">
                The School Fees costs NGN50,000 (Fify Thousand  hundred Naira only)<br/>
                Please note that transaction fees may apply<br/>

                <!--                                                To pay Online now via the application portal, click on 'Online Payment' below.-->
            </td>
        </tr>
        <tr>
            <td class="ui-helper-center">
                <!--                                                  <input type="submit" value="Online Payment" class="btn btn-default btn-block commonBtn"  name="subbtn" onclick="javascript:getpage('online_payment.php','mainContent');" >-->
                <!-- <a href="javascript:void(0);" class="commonBtn" onclick="javascript: getpage('online_payment.php','mainContent');">Online Payment</a> -->
            </td>
        </tr>
        <tr>
            <td class="ui-helper-center">
                <br>
                <strong> Proccedure for making payment</strong><br>
                <ul>
                    <li>  Click "generate Payment Code" below to generate Payment RRR 
                    </li>
                    <li>  Procceed to any Nigerian Bank and request for payment Via Remitta
                    </li>
                    <li>  Fill the information provided: make sure you fill your <strong>RRR</strong> generated from the portal
                    </li>
                    <li>   Make Payment and return to Continue Application</li></ul><br>
            </td>
        </tr>
        <tr>
            <td class="ui-helper-center">
                <?php 
                if($rrr_status == "0") {
                    echo "<input type='submit' value='Generate RRR Code' class='btn btn-lg btn-info btn-block'  name='subbtn' onclick='javascript:generateSchoolFeesRRR();'' >";
                }elseif($rrr_status == "025"){
                    echo "<div>
                    Your Generated RRR is: ". $rrr."
                    </div>
                    <br/>";
                    echo "<input type='submit' value='Click to Proceed to Remita Payment Gateway' class='btn btn-lg btn-success btn-block'  name='subbtn' onclick=\"javascript:window.location.href = 'https://login.remita.net/remita/onepage/biller/".$_SESSION['rrr']."/payment.spa'\">";
                }
                ?>
            </td>
        </tr>
        <td class="ui-helper-center">

            <!-- <br/> <input type="submit" value="Application Form" class="btn commonBtn"  name="subbtn" onclick="javascript:getpagephp('appication_from.php','mainContent','banks_payment');" > -->
        </td>
        </tr>


        <tr>
            <td class="ui-helper-center">

                <br/>
            </td>
        </tr>

    </table>

</form>
</div>
</div>
</div>

</div>
