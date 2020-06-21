<?php
//session_start();
include 'lib/dbfunctions_extra_jam.php';
$dbobject = new myDbObject();
//////request OP
if (!defined('SONMPASSWORDKEY')) define('SONMPASSWORDKEY', '123456');

$op = $_REQUEST['op'];

define('BASE_URL', 'http://localhost:88/autopointe/');

if ($op == 'checklogin') {
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];

    $member_details = $dbobject->getcheckdetails($username, $password);
    // echo "<br> Am here :: ".$member_details;
    //   if($member_details="00"){
    // //	echo "<br> inside :: ";
    // 	header('Location: http://localhost:88/autopointe/confirm_user.php');

    //   }
    echo trim($member_details);
} elseif ($op == 'checkpin') {
    $pin = $_REQUEST['pin'];
    $pin_details = $dbobject->getpindetails($pin);
    echo trim($pin_details);
} elseif ($op == 'verify_user') {
    $code = $_REQUEST['vcode'];
    $verify_user = $dbobject->doVerify_user($code);
    echo trim($verify_user);
} elseif ($op == 'banks_payment') {
    //$code = $_REQUEST['vcode'];
      $vcode =$_REQUEST['vcode'];
    $code = $_SESSION['vcode'];


    // $code = cryptoJsAesDecrypt(SONMPASSWORDKEY, $code);
    //echo '<br/>'.$code;
    if (isset($code)) {
        // echo '<br/>'.$code;
        $rrr = $dbobject->doGetRRR($code);
        $_SESSION['sonm_rrr'] = $rrr;
    }//resend_mail//resendmain
    echo trim($rrr);
} elseif ($op == 'banks_payment2') {
    //$code = $_REQUEST['vcode'];
     $code = $_SESSION['reg_id'];
    if (isset($code)) {
        // echo '<br/>'.$code;
        $rrr = $dbobject->doGetRRR2($code);
        $_SESSION['admission_rrr'] = $rrr;
    }//resend_mail
} elseif ($op == 'apply_now5center') {
     $centre = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['centre']);
    $resend = $dbobject->updateexamcenter($centre);
    echo trim($resend);
} else if($op=='toro')
    { echo $_REQUEST['toro'];
      $toro = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['toro']);
      echo $toro;
      $rs = $dbobject->doSaveTb($toro);
      echo $rs;
    }
elseif ($op == 'resendmain') {
     $email = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['email']);
    $resend = $dbobject->resendmain($email);
    echo trim($resend);
} //recoverpasswordmain
elseif ($op == 'recoverpassword') {
     $email = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['email']);

    $resend = $dbobject->recoverpassword($email);
    echo trim($resend);
}
else if ($op == 'recoverpasswordmain') {

    $value =0;
     $email = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['email']);
      $password = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['Password']);
       $cpassword = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['cPassword']);

       if($password != $cpassword){
        $value =0;
       }else{
        $value = $dbobject->recoverpasswordmain($email, $cpassword);
       }


    echo trim($value);
}


elseif ($op == 'balance') {
    $balance = $dbobject->DoBalance($_SESSION['sonm_username']);
    echo trim($balance);
} elseif ($op == 'resend_mail') {
    $user = trim($_REQUEST['user']);
    $resend = $dbobject->resendMail($user);
    echo trim($resend);
}
//apply_now5

elseif ($op == 'apply_now5') {
   // file_put_contents("test.txt", $_REQUEST['Surname']);

     $confrim = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['confirm2']);

     $Surname = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['userid']);
   // file_put_contents("test.txt", $_REQUEST['userid']." = ".$Surname);
  $dbobject->confirm($Surname, $confrim);

}
elseif ($op == 'apply_now') {
   // file_put_contents("test.txt", $_REQUEST['Surname']);
    $Surname = $_REQUEST['Surname'];
    $othernaame = $_REQUEST['Othername'];
    $fnaame = $_REQUEST['fname'];
    $Program =  $_REQUEST['Program'];
    $email =  $_REQUEST['email'];
    $password = $_REQUEST['password'];
    $phoneno =  $_REQUEST['phoneno'];
    $Gender = $_REQUEST['Gender'];
    $cpassword = $_REQUEST['cpassword'];
    //$dbobject->logs($Surname);

    // var_dump("====================== In Utilities ===================================");

    $_details = $dbobject->getApplyNow($Surname, $othernaame, $Program, $email, $password, $phoneno,$fnaame,$cpassword,$Gender);

    echo trim($_details);
} elseif ($op == 'apply_now3') {
    $program = trim( $_REQUEST['Program']);
    $email = trim($_REQUEST['email']);
    $aid = trim($_REQUEST['aid']);
    $Surname = trim($_REQUEST['Surname']);
    $othername = trim($_REQUEST['fname']);
    $country = trim($_REQUEST['Country']);
    $state = trim($_REQUEST['state']);
    $lga = trim($_REQUEST['lga']);
    $dob = trim($_REQUEST['dob']);
    $pbirth = trim( $_REQUEST['pbirth']);
    $tribe = trim($_REQUEST['tribe']);
    $religion = trim($_REQUEST['religion']);
    $maritial = trim($_REQUEST['maritial']);
    $dob = trim($_REQUEST['dob']);
    $postal_address = trim($_REQUEST['postal_address']);
    $Gname = trim( $_REQUEST['Gname']);
    $Gaddress = trim($_REQUEST['Gaddress']);
    $centre = trim( $_REQUEST['centre']);
    $ward = trim($_REQUEST['ward']);

    if ($Surname == '') {
        echo '0';
         exit(1);
        return 0;
    }
    if ($pbirth == '') {
        echo '0';
        exit(1);
        return 0;
    }
    if($country ==''||$state ==''||$lga ==''||$dob ==''||$tribe == '' || $religion == '' || $postal_address == '' || $Gaddress == '' || $Gname == ''||$ward == ''){
        echo '0';
        exit(1);
    }
    //getApplyNow3($aid, $email, $program, $surname, $othername, $country, $state, $lga, $dob, $pbirth, $postalAddress, $gname, $gaddress, $examCenter, $religion, $tribe, $religion1, $maritial,$centre,$ward)
    $_details = $dbobject->getApplyNow3($aid, $email, $program, $Surname, $othername, $country, $state, $lga, $dob, $pbirth, $postal_address, $Gname, $Gaddress, $centre, $religion, $tribe, $religion, $maritial,$centre,$ward);

    echo trim($_details);
}
 elseif ($op == 'apply_now4') {
    $email = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['email']);
    $aid = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['aid']);

    $pri_school_name = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['pri_school_name']);
    $pri_result = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['pri_result']);
    $prim_end_year = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['prim_end_year']);

    $jun_school_name = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['jun_school_name']);
    $jun_result = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['jun_result']);
    $jun_end_year = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['jun_end_year']);

    $sec_school_name = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['sec_school_name']);
    $sec_result = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['sec_result']);
    $sec_end_year = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['sec_end_year']);
     $second = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['passed']);
    if ($jun_school_name == '') {
        echo 'Enter Your Primary School name';
        return 0;
    }elseif($pri_result==""){
		echo 'Enter Your Primary School Result';
        return 0;
		}

	$result3 ="";
    $_details = $dbobject->getApplyNow4($aid, $email, $pri_school_name, $pri_result, $prim_end_year, $jun_school_name, $jun_result, $result3,$jun_end_year,$sec_school_name,$sec_result,$sec_end_year);

	//$dbobject->getResultIn($aid);

    $exam_type = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['exam_type']);
    $exam_year = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['exam_year']);


    $English = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['English']);
    $GradeEnglish = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['GradeEnglish']);

    $Mathematics = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['Mathematics']);
    $GradeMathematics = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['GradeMathematics']);

    $subject3 = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['subject3']);
    $GradeSubject3 = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['GradeSubject3']);

    $subject4 = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['subject4']);
    $GradeSubject4 = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['GradeSubject4']);

    $subject5 = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['subject5']);
    $GradeSubject5 = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['GradeSubject5']);

    $subject6 = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['subject6']);
    $GradeSubject6 = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['GradeSubject6']);

    $subject7 = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['subject7']);
    $GradeSubject7 = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['GradeSubject7']);

    $subject8 = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['subject8']);
    $GradeSubject8 = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['GradeSubject8']);

    $subject9 = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['subject9']);
    $GradeSubject9 = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['GradeSubject9']);
//second sitting

$exam_typesecond = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['result2']);
    $exam_yearsecond = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['exam_year2']);


    $Englishsecond = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['sub111']);
    $GradeEnglishsecond = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['gr111']);

    $Mathematicssecond = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['sub112']);
    $GradeMathematicssecond = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['gr112']);

    $subject3second = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['sub113']);
    $GradeSubject3second = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['gr113']);

    $subject4second = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['sub114']);
    $GradeSubject4second = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['gr114']);

    $subject5second = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['sub115']);
    $GradeSubject5second = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['gr115']);

    $subject6second = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['sub116']);
    $GradeSubject6second = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['gr116']);

    $subject7second = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['sub117']);
    $GradeSubject7second = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['gr117']);

    $subject8second = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['sub118']);
    $GradeSubject8second = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['gr118']);

    $subject9second = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['sub119']);
    $GradeSubject9second = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['gr119']);



    //end
try{
    if($second=="hello"){
if ($Englishsecond != '' &&  $GradeEnglishsecond !='') {
        $_details2 = $dbobject->getApplyNow41($aid, $exam_typesecond, $Englishsecond, $GradeEnglishsecond, $exam_yearsecond, $remarkssecond,"2",'1');
    }

    if ($Mathematicssecond != '' && $GradeMathematicssecond!='') {
        $_details2 = $dbobject->getApplyNow41($aid, $exam_typesecond, $Mathematicssecond, $GradeMathematicssecond, $exam_yearsecond, $remarkssecond,"2",'2');
    }

    if ($subject3second != '' && $GradeSubject3second!='') {
        $_details2 = $dbobject->getApplyNow41($aid, $exam_typesecond, $subject3second, $GradeSubject3second, $exam_yearsecond, $remarkssecond,"2",'3');
    }

    if ($subject4second != '' && $GradeSubject4second!='') {
        $_details2 = $dbobject->getApplyNow41($aid, $exam_typesecond, $subject4second, $GradeSubject4second, $exam_yearsecond, $remarkssecond,"2",'4');
    }

    if ($subject5second != '' &&  $GradeSubject5second!="") {
        $_details2 = $dbobject->getApplyNow41($aid, $exam_typesecond, $subject5second, $GradeSubject5second, $exam_yearsecond, $remarkssecond,"2",'5');
    }

    if ($subject6second != ''&&$GradeSubject6second!='' && $subject6second !='%23' && $GradeSubject6second!='%23') {
        $_details2 = $dbobject->getApplyNow41($aid, $exam_typesecond, $subject6second, $GradeSubject6second, $exam_yearsecond, $remarkssecond,"2",'6');
    }

    if ($subject7second != ''&& $GradeSubject7second!='' && $subject7second !='%23' && $GradeSubject7second!='%23') {
        $_details2 = $dbobject->getApplyNow41($aid, $exam_typesecond, $subject7second, $GradeSubject7second, $exam_yearsecond, $remarkssecond,"2",'7');
    }

    if ($subject8second != ''&& $GradeSubject8second!='' && $subject8second !='%23' && $GradeSubject8second!='%23') {
        $_details2 = $dbobject->getApplyNow41($aid, $exam_typesecond, $subject8second, $GradeSubject8second, $exam_yearsecond, $remarkssecond,"2",'8');

    }

    if ($subject9second != ''&& $GradeSubject9second!='' && $subject9second !='%23' && $GradeSubject9second!='%23') {
        $_details2 = $dbobject->getApplyNow41($aid, $exam_typesecond, $subject9second, $GradeSubject9second, $exam_yearsecond, $remarkssecond,"2",'9');
    }
}
}catch(Exception $e){}

$remarks ="";
if(($subject3!="" && $subject3 !="%23")&& ($subject4!="" && $subject4 !="%23")&&($subject5!="" && $subject5 !="%23")&&($subject6!="" && $subject6 !="%23")&&($subject7!="" && $subject7 !="%23")&&($subject8!="" && $subject8 !="%23")){
    if ($English != '') {
        $_details2 = $dbobject->getApplyNow41($aid, $exam_type, $English, $GradeEnglish, $exam_year, $remarks,"1",'1');
    }

    if ($Mathematics != '') {
        $_details2 = $dbobject->getApplyNow41($aid, $exam_type, $Mathematics, $GradeMathematics, $exam_year, $remarks,"1",'2');
    }

    if ($subject3 != '') {
        $_details2 = $dbobject->getApplyNow41($aid, $exam_type, $subject3, $GradeSubject3, $exam_year, $remarks,"1",'3');
    }

    if ($subject4 != '') {
        $_details2 = $dbobject->getApplyNow41($aid, $exam_type, $subject4, $GradeSubject4, $exam_year, $remarks,"1",'4');
    }

    if ($subject5 != '') {
        $_details2 = $dbobject->getApplyNow41($aid, $exam_type, $subject5, $GradeSubject5, $exam_year, $remarks,"1",'5');
    }

    if ($subject6 != '' && $subject6 != '%23') {
        $_details2 = $dbobject->getApplyNow41($aid, $exam_type, $subject6, $GradeSubject6, $exam_year, $remarks,"1",'6');

    }

    if ($subject7 != '' && $subject7 != '%23') {
        $_details2 = $dbobject->getApplyNow41($aid, $exam_type, $subject7, $GradeSubject7, $exam_year, $remarks,"1",'7');
    }

    if ($subject8 != '' && $subject8 != '%23') {
        $_details2 = $dbobject->getApplyNow41($aid, $exam_type, $subject8, $GradeSubject8, $exam_year, $remarks,"1",'8');

    }

    if ($subject9 != '' && $subject9 != '%23') {
        $_details2 = $dbobject->getApplyNow41($aid, $exam_type, $subject9, $GradeSubject9, $exam_year, $remarks,"1",'9');
    }
}else{
     $_details2 = "4";
}

    echo trim($_details2);
} elseif ($op == 'apply_now_save') {
    if (isset($_REQUEST['subbtn'])) {
        $Surname = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['Surname']);
        $othernaame = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['othernaame']);
        $Program = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['Program']);
        $email = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['email']);
        $password = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['password']);
        $phoneno = cryptoJsAesDecrypt(SONMPASSWORDKEY, $_REQUEST['phoneno']);

        $dbobject->logs($email);
        $_details = $dbobject->getApply_now_save($Surname, $othernaame, $Program, $email, $password, $phoneno);

        echo trim($_details);
    }
}
 elseif ($op == 'applicant_now_login') {
    // if(isset($_REQUEST['subbtn'])){

    $loginemail = $_REQUEST['loginemail'];
    $loginpass = $_REQUEST['loginpass'];

    //  $dbobject->logs("loginemail : ".$loginemail." ::loginpass  : ".$loginpass);
    // $loginemail=cryptoJsAesDecrypt(SONMPASSWORDKEY,$loginemail);

    // $loginpass=cryptoJsAesDecrypt(SONMPASSWORDKEY,$loginpass);
    //$dbobject->logs($loginemail." :: ".$loginpass);
    $_details = $dbobject->getApplicantNowLogin($loginemail, $loginpass);

    //$dbobject->logs("$_details "."  :after : ");
    echo trim($_details);
// }
}


 elseif ($op == 'apply_now_login') {
    // if(isset($_REQUEST['subbtn'])){

    $loginemail = $_REQUEST['loginemail'];
    $loginpass = $_REQUEST['loginpass'];

    //	$dbobject->logs("loginemail : ".$loginemail." ::loginpass  : ".$loginpass);
    // $loginemail=cryptoJsAesDecrypt(SONMPASSWORDKEY,$loginemail);

    // $loginpass=cryptoJsAesDecrypt(SONMPASSWORDKEY,$loginpass);
    //$dbobject->logs($loginemail." :: ".$loginpass);
    $_details = $dbobject->getApplyNowLogin($loginemail, $loginpass);

    //$dbobject->logs("$_details "."  :after : ");
    echo trim($_details);
// }
} elseif ($op == 'getrrrstatus') {
    $rrr = $_REQUEST['rrr'];

    $response = $dbobject->doConfirmPaymentGeneral($rrr);
    //$dbobject->logs('_details : '.$_details);

    echo trim($response);
} elseif ($op == 'getStateLGA') {
    $country_id = $_REQUEST['selected_state'];
file_put_contents("content.txt",$country_id,FILE_APPEND);
    if ($country_id) {
        echo $query = "SELECT id, name FROM app_states WHERE country_id = '$country_id'";
    } else {
        echo $query = 'SELECT id, name FROM app_states';
    }
    $options = $dbobject->getdataselect($query);
    echo $options;
} elseif ($op == 'getLGA') {
    $state_id = @$_REQUEST['selected_lga'];

    if ($state_id) {
        echo $query = "SELECT id, name FROM app_cities WHERE state_id = '$state_id'";
    } else {
        echo $query = 'SELECT id, name FROM app_cities';
    }
    $options = $dbobject->getdataselect($query);
    echo $options;
} elseif ($op == 'addPartToCart') {
    $email = $_REQUEST['mail'];
    $platenumber = $_REQUEST['platenumber'];
    $aaamount = $_REQUEST['amount'];
    $partname = $_REQUEST['sparepartname'];
    $quantity = $_REQUEST['sparepartquantity'];

    $_details = $dbobject->AddSpareToCart($email, $platenumber, $aaamount, $partname, $quantity);

    echo trim($_details);
} elseif ($op == 'trans_count') {
    $channe = $_REQUEST['channel'];

    $reg_details = $dbobject->getTrans_count($channe);
    //echo "Am here";
    echo trim($reg_details);
} elseif ($op == 'notify_count') {
    $table = $_REQUEST['tablee'];
    //echo "toro ".$table;
    $reg_details = $dbobject->getNotify_count($table);
    //echo "Am here";
    echo trim($reg_details);
} elseif ($op == 'save_vuvaa_trans') {
    $query_trsan = 'INSERT INTO transaction_tb(trans_id,trans_method,customer,sold_by,phone_no,plate_no,item_id,item_amount,item_name,pan,cannel,tarminal_id,trans_type,created,quantity) VALUES';
    $confirm_password = $_REQUEST['confirm_password'];
    $username = $_SESSION['sonm_username'];
    $transaction_id = $_SESSION['sonm_product_id'];
    $transaction_desc = 'God is Good';
    $transaction_amount = $_SESSION['sonm_amount'];
    $customer_id = '088576578';
    $customer_name = $_SESSION['sonm_firstname'];
    $channel = 'web';
    $vuvaa_trans = $dbobject->Vuvaa_transCart($confirm_password, $username, $transaction_id, $transaction_desc, $transaction_amount, $customer_id, $customer_name, $channel);

    if ($vuvaa_trans['status'] == 200) {
        $created = @date('Y-m-d H:i:s');
        foreach ($_SESSION['sonm_Cart'] as $item) {
            $itemid = $item['item_id'];
            $amount = $item['amount'];
            $quantity = $item['quantity'];
            $trans_type = $item['trans_type'];
            if ($i > 0) {
                $query_trsan .= ',("'.$transaction_id.'","Wallet","'.$customer_name.'","'.$username.'","'.$customer_id.'","'.$plate_no.'","'.$itemid.'","'.$amount.'","'.$item['item_name'].'","'.$pan.'","Web","'.$terminal_id.'","'.$trans_type.'","'.$created.'","'.$quantity.'")';
            } else {
                $query_trsan .= '("'.$transaction_id.'","Wallet","'.$customer_name.'","'.$username.'","'.$customer_id.'","'.$plate_no.'","'.$itemid.'","'.$amount.'","'.$item['item_name'].'","'.$pan.'","Web","'.$terminal_id.'","'.$trans_type.'","'.$created.'","'.$quantity.'")';
            }
            ++$i;
        }

        $res = $dbobject->doSaveTb($query_trsan);
        if ($res > 0) {
            echo 'Transaction Successful <br/> <a href="rp/index.php" target="_blank">Print Receipt </a>';
        }
    } elseif ($vuvaa_trans['status'] == 108) {
        echo 'You have insufficient fund. Please, recharge wallet and try again';
    } else {
        echo 'Transaction Fail ';
    }
} elseif ($op == 'LastTransaction') {
    $tablee = '<table class="table"><thead><tr><th>Item Name</th><th>Amount</th></tr></thead><tbody>';
    $userid = $_SESSION['sonm_username'];
    $item = $dbobject->getLastTransactionByUser($userid);
    $json = json_decode($item);
    foreach ($json as $value) {
        $tablee .= '<tr><td>'.$value->ItemName.'</td><td>'.$value->Item_amount.'</td></tr>';
    }
    $tablee .= '</tbody></table>';
    echo $tablee;
} elseif ($op == 'Loadcart') {
    $load_cart = $dbobject->LoadCart($_SESSION[sonm_username]);
    echo $load_cart;
} elseif ($op == 'CartItem') {
    $tid = $_REQUEST['trans_id'];
    $load_cart = $dbobject->getCartItem($tid);
    echo $load_cart;
} elseif ($op == 'autosugg') {
    $tid = $_REQUEST['trans_id'];
    $load_item = $dbobject->getCartItem($tid);
    echo $load_item;
} elseif ($op == 'autoItem') {
    $search = $_REQUEST['search'];

    $autoItem = $dbobject->getAutoItem($search);
    // $row[0]["item_name"];
    echo $autoItem;
} elseif ($op == 'RequeryCart') {
    $RequeryCart = $dbobject->onePayRequeryCart('5434');
    echo $RequeryCart;
} elseif ($op == 'removecart') {
    $id = $_REQUEST['id'];
    $username = $_SESSION[sonm_username];
    $remove_cart = $dbobject->RemoveCart($id, $username);
    echo $remove_cart;
} elseif ($op == 'checkForgotemail') {
    $mail = $_REQUEST['email'];

    $email_details = $dbobject->getemaildetails($mail);
    //echo "Am here";
    echo trim($email_details);
} elseif ($op == 'save_password') {
    if (isset($_REQUEST['subbtn'])) {
        $username = $_REQUEST['username'];
        $oldpassword = $_REQUEST['oldpassword'];
        $user_password = $_REQUEST['userpassword'];

        //echo "oldpassword :: ".$oldpassword;
        if ($dbobject->validatepassword($username, $oldpassword) == '1') {
            $curr_resp = $dbobject->doPasswordChange($username, $user_password, $oldpassword);
            if ($curr_resp == 1) {
                echo '<div class="alert alert-success">The User password has been successfully changed </div>';
            } else {
                echo '<div class="alert alert-error">Error : Please check password detail</div>';
            }
        } else {
            echo '<div class="alert alert-error">Your old password is invalid</div>';
        }
    }
} elseif ($op == 'save_role') {
    //if(isset($_REQUEST['role_id'])){
    $role_id = $_REQUEST['role_id'];
    $role_name = $_REQUEST['role_name'];
    $enable_role = $_REQUEST['enable_role'];
    $role_resp = $dbobject->doRole($role_id, $role_name, $enable_role);
    if ($role_resp == '1') {
        echo 'Role detail has been successfully saved';
    } else {
        echo 'Error : Please check Role detail';
    }
    //}
} elseif ($op == 'detmsg') {
    //if(isset($_REQUEST['role_id'])){
    $id = $_REQUEST['id'];
    echo $id;
// $role_resp = $dbobject->doRole($role_id,$role_name,$enable_role);
        // 	if($role_resp=='1') {
        // 		echo 'Role detail has been successfully saved';
        // 	}else{
        // 		echo 'Error : Please check Role detail';
        // 	}
        //}

        //--------------------------------------------------------------------------
  // 2) Query database for data
  //--------------------------------------------------------------------------
  //$result = mysql_query("SELECT * FROM $tableName");          //query
  //$array = mysql_fetch_row($result);                          //fetch result

  //--------------------------------------------------------------------------
  // 3) echo result as json
  //--------------------------------------------------------------------------
  //echo json_encode($array);
} elseif ($op == 'contact_save') {
    //if(isset($_REQUEST['role_id'])){
    $fname = $_REQUEST['fname'];
    $email = $_REQUEST['email'];
    $message = $_REQUEST['message'];
    $role_resp = $dbobject->doContact($fname, $email, $message);
    if ($role_resp == '1') {
        echo 'Message has been successfully sent';
    } else {
        echo 'Error : Please check your detail';
    }
    //}
} elseif ($op == 'search_rrr') {
    //if(isset($_REQUEST['role_id'])){
    $rrrid = $_REQUEST['rrrid'];
    $rrr_resp = $dbobject->doConfirmPayment($rrrid);
    //Return result to jTable
    $qryResult = array();
    $qryResult[] = $rrr_resp;

    //$qryResult=jsonp_decode($qryResult);
    //var_dump($qryResult);
    echo json_encode($rrr_resp);
    exit();
    //var_dump($rrr_resp);
    if ($rrr_resp == '1') {
        echo 'Message has been successfully get';
    } else {
        echo 'Not Found : Please check your RRR';
    }
    //}
} elseif ($op == 'save_vehicle') {
    if (isset($_REQUEST['subbtn'])) {
        $vid = $_REQUEST['vid'];
        $ownerName = $_REQUEST['ownerName'];
        $vehicleregno = $_REQUEST['vehicleregno'];
        $purpose = $_REQUEST['purpose'];
        $capacity = $_REQUEST['capacity'];
        $vehicleType = $_REQUEST['vehicleType'];
        $TypeOfFuel = $_REQUEST['TypeOfFuel'];
        $email = $_REQUEST['email'];
        $mobilenumber = $_REQUEST['mobilenumber'];

        $user_resp = $dbobject->doVehicle($vid, $ownerName, $vehicleregno, $purpose, $capacity, $vehicleType, $TypeOfFuel, $email, $mobilenumber);

        if ($user_resp == -9) {
            echo 'User detail already exist, please enter a different username';
        } elseif ($user_resp > 0) {
            echo 'User detail has been successfully saved';
        } else {
            echo 'Error : Please check Vehicle detail';
        }
    }
} elseif ($op == 'save_telecom') {
    if (isset($_REQUEST['subbtn'])) {
        $tid = $_REQUEST['tid'];
        $operatorname = $_REQUEST['operatorname'];
        $address = $_REQUEST['address'];
        $massno = $_REQUEST['massno'];
        $vsatno = $_REQUEST['vsatno'];
        $mobilenumber = $_REQUEST['mobilenumber'];

        $user_resp = $dbobject->doTelecom($tid, $operatorname, $address, $massno, $vsatno, $mobilenumber);
        //echo $operatorname. $address.$massno.$vsatno.$mobilenumber;
        if ($user_resp == -9) {
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($user_resp > 0) {
            echo 'Detail has been successfully saved';
        } else {
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'save_service_item') {
    if (isset($_REQUEST['subbtn'])) {
        $item_id = $_REQUEST['item_id'];
        $menu_id_cat = $_REQUEST['Category'];
        $item_name = $_REQUEST['itemname'];
        $item_amount = $_REQUEST['amount'];
        $active = $_REQUEST['status'];
        $service_item = $dbobject->doServiceItem($item_id, $menu_id_cat, $item_name, $item_amount, $active);
        //echo $operatorname. $address.$massno.$vsatno.$mobilenumber;
        if ($service_item == -9) {
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($service_item > 0) {
            echo 'Detail has been successfully saved';
        } else {
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'get_info_by_TransId') {
    if (isset($_REQUEST['subbtn'])) {
        $transId = $_REQUEST['transid'];
        $service_item = $dbobject->doGet_info_by_TransId($transId);
        echo $service_item;
    }
} elseif ($op == 'save_job_card') {
    if (isset($_REQUEST['subbtn'])) {
        $item_id = $_REQUEST['item_id'];
        $operation = $_REQUEST['operation'];
        $customer_Name = $_REQUEST['customerName'];
        $Address = $_REQUEST['address'];
        $modelMake = $_REQUEST['model_make'];
        $chessis_no = $_REQUEST['chessisNo'];
        $Colour = $_REQUEST['colour'];
        $phone_number = $_REQUEST['phoneNumber'];
        $date_received = $_REQUEST['dateReceived'];
        $date_completed = $_REQUEST['dateCompleted'];
        $job_done_by = $_REQUEST['jobDoneBy'];
        $timeStart = $_REQUEST['timeStart'];
        $time_completed = $_REQUEST['timeCompleted'];
        $service_item = $dbobject->doSave_job_card($_REQUEST);
        //echo $operatorname. $address.$massno.$vsatno.$mobilenumber;
        //echo ":: ".$service_item." ::";
        if ($service_item == -9) {
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($service_item == -5) {
            echo 'Transaction Id Used Before';
        } elseif ($service_item > 0) {
            echo 'Detail has been successfully saved';
        } else {
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'save_banks') {
    if (isset($_REQUEST['subbtn'])) {
        $bid = $_REQUEST['bid'];
        $operatorname = $_REQUEST['operatorname'];
        $address = $_REQUEST['address'];
        $massno = $_REQUEST['massno'];
        $vsatno = $_REQUEST['vsatno'];
        $mobilenumber = $_REQUEST['mobilenumber'];

        $ranks = $_REQUEST['ranks'];
        $bank_type = $_REQUEST['bank_type'];
        $bank = explode(',', $bank_type);

        $user_resp = $dbobject->doBanks($bid, $operatorname, $address, $massno, $vsatno, $mobilenumber, $bank[0], $bank[1], $ranks);
        //echo $operatorname. $address.$massno.$vsatno.$mobilenumber;
        if ($user_resp == -9) {
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($user_resp > 0) {
            echo 'Detail has been successfully saved';
        } else {
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'save_Outuser') {
    if (isset($_REQUEST['btn_register'])) {
        $aid = $_REQUEST['bid'];
        $Fullname = $_REQUEST['Fullname'];
        $uid = $dbobject->generatePIN();

        $Username = $_REQUEST['Username'];
        $Password = $_REQUEST['userpassword'];
        $operation = 'new';

        $Userstate = $_SESSION['statecode_sess'];
        $user_resp = $dbobject->doUser($operation, $uid, $Username, $Password, $Fullname, $Fullname, $Username, $mobilenumber, $chgpword_logon, 0, $user_disable, 1, 1, 1, 1, 1, 1, 1, $override_wh, $extend_wh, '5131', "$role_name", $Userstate);
        if ($user_resp > 0) {
            $dbobject->commit();
        } else {
            $dbobject->rollback();
        }
        if ($user_resp == -9) {
            $dbobject->rollback();
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($user_resp > 0) {
            $dbobject->commit();
            echo 'Detail has been successfully saved';
        } else {
            $dbobject->rollback();
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'save_UserStep2') {
    if (isset($_REQUEST['subbtn'])) {
        // $regid=randomcode();
        //	$aid = $_REQUEST['bid'];
        $merchant_id = $_REQUEST['merchant_id'];

        $last_name = $_REQUEST['last_name']; // is for Surname
            $first_name = $_REQUEST['first_name']; // is for Othername

            $phone_no = $_REQUEST['phone_no'];
        $email_address = $_REQUEST['email_address'];
        $username = $_REQUEST['username'];
        $password_confirm = $_REQUEST['password_confirm'];

        //echo $merchant_id ."  ".$last_name."  ".$first_name."  ".$first_name."  ".$first_name;
        $operation = 'new';
        $uid = $_SESSION['user_id_se'];
        //$Userstate = $_SESSION['statecode_sess'];
        $user_resp = $dbobject->doUser_step2($operation, $merchant_id, $last_name, $first_name, $phone_no, $email_address, $username, $password_confirm);
        if ($user_resp > 0) {
            $dbobject->commit();
        } else {
            $dbobject->rollback();
        }

        if ($user_resp == -9) {
            $dbobject->rollback();
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($user_resp > 0) {
            $dbobject->commit();
            echo 'Detail has been successfully saved';
        } else {
            $dbobject->rollback();
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'save_rrr') {
    if (isset($_REQUEST['subbtn'])) {
        $Fullname = $_REQUEST['Fullname'];
        $Position = $_REQUEST['Position'];
        $gender = $_REQUEST['gender'];
        $dob = $_REQUEST['dob'];
        $mobilenumber = $_REQUEST['mobilenumber'];
        $address = $_REQUEST['address'];
        $kinname = $_REQUEST['kinname'];
        $kinno = $_REQUEST['kinno'];
        if ($_REQUEST['agentpassport'] == '') {
            echo 'Error : Select Agent passport to upload';
            exit();
        } else {
            $user_resp = $dbobject->doRrr($Fullname, $Position, $gender, $dob, $mobilenumber, $address, $kinname, $kinno, $imgurl);
            //echo $companyname. $personname.$lot.$mobilenumber.$mobilenumber;
            if ($user_resp == -9) {
                echo 'Recorld already exist, please enter a different Data';
            } elseif ($user_resp > 0) {
                echo 'Detail has been successfully saved';
            } else {
                echo 'Error : Please check detail';
            }
        }
    }
} elseif ($op == 'save_consultant') {
    if (isset($_REQUEST['subbtn'])) {
        $rccno = $_REQUEST['rccno'];
        $companyname = $_REQUEST['companyname'];
        $personname = $_REQUEST['personname'];
        $lot = $_REQUEST['lot'];
        $mobilenumber = $_REQUEST['mobilenumber'];
        $address = $_REQUEST['address'];
        $lots = $_REQUEST['exist_lots'];
        $emailaddress = $_REQUEST['emailaddress'];

        if ($_REQUEST['exist_lots'] == '') {
            echo 'Error : Select At list one lot';
            exit();
        } else {
            $user_resp = $dbobject->doConsultant($rccno, $companyname, $personname, $lot, $mobilenumber, $address, $lots, $emailaddress);
            //echo $companyname. $personname.$lot.$mobilenumber.$mobilenumber;
            if ($user_resp == -9) {
                echo 'Recorld already exist, please enter a different Data';
            } elseif ($user_resp > 0) {
                echo 'Detail has been successfully saved';
            } else {
                echo 'Error : Please check detail';
            }
        }
    }
} elseif ($op == 'save_shop') {
    if (isset($_REQUEST['subbtn'])) {
        $sid = $_REQUEST['sid'];
        $SHOPNAME = $_REQUEST['SHOPNAME'];
        $NAMEOFOPERATOR = $_REQUEST['NAMEOFOPERATOR'];
        $ADDRESS = $_REQUEST['ADDRESS'];
        $PHONENUMBER = $_REQUEST['PHONENUMBER'];
        $METHODOFWASTEDISPOSAL = $_REQUEST['METHODOFWASTEDISPOSAL'];

        $ranks = $_REQUEST['ranks'];
        $type = $_REQUEST['type'];
        $type = explode(',', $type);

        $user_resp = $dbobject->doShop($sid, $SHOPNAME, $NAMEOFOPERATOR, $ADDRESS, $PHONENUMBER, $METHODOFWASTEDISPOSAL, $type[0], $type[1], $ranks);
        //echo $operatorname. $address.$massno.$vsatno.$mobilenumber;
        if ($user_resp == -9) {
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($user_resp > 0) {
            echo 'Detail has been successfully saved';
        } else {
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'save_food_eatery') {
    if (isset($_REQUEST['subbtn'])) {
        $fid = $_REQUEST['fid'];
        $SHOPNAME = $_REQUEST['SHOPNAME'];
        $NAMEOFOPERATOR = $_REQUEST['NAMEOFOPERATOR'];
        $ADDRESS = $_REQUEST['ADDRESS'];
        $PHONENUMBER = $_REQUEST['PHONENUMBER'];
        $METHODOFWASTEDISPOSAL = $_REQUEST['METHODOFWASTEDISPOSAL'];

        $ranks = $_REQUEST['ranks'];
        $type = $_REQUEST['type'];
        $type = explode(',', $type);

        $user_resp = $dbobject->doFoodEatery($fid, $SHOPNAME, $NAMEOFOPERATOR, $ADDRESS, $PHONENUMBER, $METHODOFWASTEDISPOSAL, $type[0], $type[1], $ranks);
        //echo $operatorname. $address.$massno.$vsatno.$mobilenumber;
        if ($user_resp == -9) {
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($user_resp > 0) {
            echo 'Detail has been successfully saved';
        } else {
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'save_computer') {
    if (isset($_REQUEST['subbtn'])) {
        $cid = $_REQUEST['cid'];
        $SHOPNAME = $_REQUEST['SHOPNAME'];
        $NAMEOFOPERATOR = $_REQUEST['NAMEOFOPERATOR'];
        $ADDRESS = $_REQUEST['ADDRESS'];
        $PHONENUMBER = $_REQUEST['PHONENUMBER'];
        $METHODOFWASTEDISPOSAL = $_REQUEST['METHODOFWASTEDISPOSAL'];

        $ranks = $_REQUEST['ranks'];
        $type = $_REQUEST['type'];
        $type = explode(',', $type);

        $user_resp = $dbobject->doComputer_Ict($cid, $SHOPNAME, $NAMEOFOPERATOR, $ADDRESS, $PHONENUMBER, $METHODOFWASTEDISPOSAL, $type[0], $type[1], $ranks);
        //echo $operatorname. $address.$massno.$vsatno.$mobilenumber;
        if ($user_resp == -9) {
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($user_resp > 0) {
            echo 'Detail has been successfully saved';
        } else {
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'save_education') {
    if (isset($_REQUEST['subbtn'])) {
        $eid = $_REQUEST['eid'];
        $SHOPNAME = $_REQUEST['SHOPNAME'];
        $NAMEOFOPERATOR = $_REQUEST['NAMEOFOPERATOR'];
        $ADDRESS = $_REQUEST['ADDRESS'];
        $PHONENUMBER = $_REQUEST['PHONENUMBER'];
        $POPULATION = $_REQUEST['POPULATION'];

        $ranks = $_REQUEST['ranks'];
        $type = $_REQUEST['type'];
        $type = explode(',', $type);

        $user_resp = $dbobject->doEducation($eid, $SHOPNAME, $NAMEOFOPERATOR, $ADDRESS, $PHONENUMBER, $POPULATION, $type[0], $type[1], $ranks);
        //echo $operatorname. $address.$massno.$vsatno.$mobilenumber;
        if ($user_resp == -9) {
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($user_resp > 0) {
            echo 'Detail has been successfully saved';
        } else {
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'save_petrol_gas') {
    if (isset($_REQUEST['subbtn'])) {
        $pid = $_REQUEST['pid'];
        $SHOPNAME = $_REQUEST['SHOPNAME'];
        $NAMEOFOPERATOR = $_REQUEST['NAMEOFOPERATOR'];
        $ADDRESS = $_REQUEST['ADDRESS'];
        $PHONENUMBER = $_REQUEST['PHONENUMBER'];
        $METHODOFWASTEDISPOSAL = $_REQUEST['METHODOFWASTEDISPOSAL'];

        $ranks = $_REQUEST['ranks'];
        $type = $_REQUEST['type'];
        $type = explode(',', $type);

        $user_resp = $dbobject->doPetrolGas($pid, $SHOPNAME, $NAMEOFOPERATOR, $ADDRESS, $PHONENUMBER, $METHODOFWASTEDISPOSAL, $type[0], $type[1], $ranks);
        //echo $operatorname. $address.$massno.$vsatno.$mobilenumber;
        if ($user_resp == -9) {
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($user_resp > 0) {
            echo 'Detail has been successfully saved';
        } else {
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'save_drilling_mining') {
    if (isset($_REQUEST['subbtn'])) {
        $did = $_REQUEST['did'];
        $SHOPNAME = $_REQUEST['SHOPNAME'];
        $NAMEOFOPERATOR = $_REQUEST['NAMEOFOPERATOR'];
        $ADDRESS = $_REQUEST['ADDRESS'];
        $PHONENUMBER = $_REQUEST['PHONENUMBER'];
        $METHODOFWASTEDISPOSAL = $_REQUEST['METHODOFWASTEDISPOSAL'];

        $ranks = $_REQUEST['ranks'];
        $type = $_REQUEST['type'];
        $type = explode(',', $type);

        $user_resp = $dbobject->doDrillingMining($did, $SHOPNAME, $NAMEOFOPERATOR, $ADDRESS, $PHONENUMBER, $METHODOFWASTEDISPOSAL, $type[0], $type[1], $ranks);
        //echo $operatorname. $address.$massno.$vsatno.$mobilenumber;
        if ($user_resp == -9) {
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($user_resp > 0) {
            echo 'Detail has been successfully saved';
        } else {
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'save_median_house') {
    if (isset($_REQUEST['subbtn'])) {
        $mid = $_REQUEST['mid'];
        $SHOPNAME = $_REQUEST['SHOPNAME'];
        $NAMEOFOPERATOR = $_REQUEST['NAMEOFOPERATOR'];
        $ADDRESS = $_REQUEST['ADDRESS'];
        $PHONENUMBER = $_REQUEST['PHONENUMBER'];
        $METHODOFWASTEDISPOSAL = $_REQUEST['METHODOFWASTEDISPOSAL'];

        $ranks = $_REQUEST['ranks'];
        $type = $_REQUEST['type'];
        $type = explode(',', $type);

        $user_resp = $dbobject->doMedianHouse($mid, $SHOPNAME, $NAMEOFOPERATOR, $ADDRESS, $PHONENUMBER, $METHODOFWASTEDISPOSAL, $type[0], $type[1], $ranks);
        //echo $operatorname. $address.$massno.$vsatno.$mobilenumber;
        if ($user_resp == -9) {
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($user_resp > 0) {
            echo 'Detail has been successfully saved';
        } else {
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'save_houspitality') {
    if (isset($_REQUEST['subbtn'])) {
        $hid = $_REQUEST['hid'];
        $SHOPNAME = $_REQUEST['SHOPNAME'];
        $NAMEOFOPERATOR = $_REQUEST['NAMEOFOPERATOR'];
        $ADDRESS = $_REQUEST['ADDRESS'];
        $PHONENUMBER = $_REQUEST['PHONENUMBER'];
        $METHODOFWASTEDISPOSAL = $_REQUEST['METHODOFWASTEDISPOSAL'];

        $ranks = $_REQUEST['ranks'];
        $type = $_REQUEST['type'];
        $type = explode(',', $type);

        $user_resp = $dbobject->doHouspitality($hid, $SHOPNAME, $NAMEOFOPERATOR, $ADDRESS, $PHONENUMBER, $METHODOFWASTEDISPOSAL, $type[0], $type[1], $ranks);
        //echo $operatorname. $address.$massno.$vsatno.$mobilenumber;
        if ($user_resp == -9) {
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($user_resp > 0) {
            echo 'Detail has been successfully saved';
        } else {
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'save_artisans') {
    if (isset($_REQUEST['subbtn'])) {
        $aid = $_REQUEST['aid'];
        $SHOPNAME = $_REQUEST['SHOPNAME'];
        $NAMEOFOPERATOR = $_REQUEST['NAMEOFOPERATOR'];
        $ADDRESS = $_REQUEST['ADDRESS'];
        $PHONENUMBER = $_REQUEST['PHONENUMBER'];
        $METHODOFWASTEDISPOSAL = $_REQUEST['METHODOFWASTEDISPOSAL'];

        $ranks = $_REQUEST['ranks'];
        $type = $_REQUEST['type'];
        $type = explode(',', $type);

        $user_resp = $dbobject->doArtisans($aid, $SHOPNAME, $NAMEOFOPERATOR, $ADDRESS, $PHONENUMBER, $METHODOFWASTEDISPOSAL, $type[0], $type[1], $ranks);
        //echo $operatorname. $address.$massno.$vsatno.$mobilenumber;
        if ($user_resp == -9) {
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($user_resp > 0) {
            echo 'Detail has been successfully saved';
        } else {
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'save_medical') {
    if (isset($_REQUEST['subbtn'])) {
        $mid = $_REQUEST['mid'];
        $ownersname = $_REQUEST['ownersname'];
        $shopname = $_REQUEST['shopname'];
        $address = $_REQUEST['address'];
        $machines_gadget = $_REQUEST['machines_gadget'];
        $waste_despose = $_REQUEST['waste_despose'];
        $mortuaryis = $_REQUEST['mortuaryis'];

        $ranks = $_REQUEST['ranks'];
        $type = $_REQUEST['type'];
        $type = explode(',', $type);
        $user_resp = $dbobject->doMedical($mid, $ownersname, $shopname, $address, $machines_gadget, $waste_despose, $mortuaryis, $type[0], $type[1], $ranks);
        //echo $operatorname. $address.$massno.$vsatno.$mobilenumber;
        if ($user_resp == -9) {
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($user_resp > 0) {
            echo 'Detail has been successfully saved';
        } else {
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'ConfirmPayment') {
    $user_resp = $dbobject->doConfirmPayment($rrr);
} elseif ($op == 'save_supermaket') {
    if (isset($_REQUEST['subbtn'])) {
        $sid = $_REQUEST['sid'];
        $SHOPNAME = $_REQUEST['SHOPNAME'];
        $NAMEOFOWNER = $_REQUEST['NAMEOFOWNER'];
        $ADDRESS = $_REQUEST['ADDRESS'];
        $PHONENUMBER = $_REQUEST['PHONENUMBER'];
        $METHODOFWASTEDISPOSAL = $_REQUEST['METHODOFWASTEDISPOSAL'];

        $ranks = $_REQUEST['ranks'];
        $type = $_REQUEST['type'];
        $type = explode(',', $type);

        $user_resp = $dbobject->doSupermaket($sid, $SHOPNAME, $NAMEOFOWNER, $ADDRESS, $PHONENUMBER, $METHODOFWASTEDISPOSAL, $type[0], $type[1], $ranks);
        //echo $operatorname. $address.$massno.$vsatno.$mobilenumber;
        if ($user_resp == -9) {
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($user_resp > 0) {
            echo 'Detail has been successfully saved';
        } else {
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'save_printing') {
    if (isset($_REQUEST['subbtn'])) {
        $pid = $_REQUEST['pid'];
        $SHOPNAME = $_REQUEST['SHOPNAME'];
        $NAMEOFOPERATOR = $_REQUEST['NAMEOFOPERATOR'];
        $ADDRESS = $_REQUEST['ADDRESS'];
        $PHONENUMBER = $_REQUEST['PHONENUMBER'];
        $TYPESOFMACHINESUSED = $_REQUEST['TYPESOFMACHINESUSED'];
        $METHODOFWASTEDISPOSAL = $_REQUEST['METHODOFWASTEDISPOSAL'];

        $user_resp = $dbobject->doPrinting($pid, $SHOPNAME, $NAMEOFOPERATOR, $ADDRESS, $PHONENUMBER, $TYPESOFMACHINESUSED, $METHODOFWASTEDISPOSAL);
        //echo $operatorname. $address.$massno.$vsatno.$mobilenumber;
        if ($user_resp == -9) {
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($user_resp > 0) {
            echo 'Detail has been successfully saved';
        } else {
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'save_haulage') {
    if (isset($_REQUEST['subbtn'])) {
        $hid = $_REQUEST['hid'];
        $NAMEOFOPERATOR = $_REQUEST['NAMEOFOPERATOR'];
        $ADDRESS = $_REQUEST['ADDRESS'];
        $PHONENUMBER = $_REQUEST['PHONENUMBER'];
        $VEHICLEREGNUMBER = $_REQUEST['VEHICLEREGNUMBER'];
        $WEIGHTHAULED = $_REQUEST['WEIGHTHAULED'];

        $ranks = $_REQUEST['ranks'];
        $type = $_REQUEST['type'];
        $type = explode(',', $type);

        $user_resp = $dbobject->doHaulage($hid, $NAMEOFOPERATOR, $ADDRESS, $PHONENUMBER, $VEHICLEREGNUMBER, $WEIGHTHAULED, $type[0], $type[1], $ranks);
        //echo $operatorname. $address.$massno.$vsatno.$mobilenumber;
        if ($user_resp == -9) {
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($user_resp > 0) {
            echo 'Detail has been successfully saved';
        } else {
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'save_glassshop') {
    if (isset($_REQUEST['subbtn'])) {
        $gid = $_REQUEST['gid'];
        $SHOPNAME = $_REQUEST['SHOPNAME'];
        $NAMEOFOPERATOR = $_REQUEST['NAMEOFOPERATOR'];
        $ADDRESS = $_REQUEST['ADDRESS'];
        $PHONENUMBER = $_REQUEST['PHONENUMBER'];
        $METHODOFWASTEDISPOSAL = $_REQUEST['METHODOFWASTEDISPOSAL'];

        $user_resp = $dbobject->doGlassshop($gid, $SHOPNAME, $NAMEOFOPERATOR, $ADDRESS, $PHONENUMBER, $METHODOFWASTEDISPOSAL);
        //echo $operatorname. $address.$massno.$vsatno.$mobilenumber;
        if ($user_resp == -9) {
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($user_resp > 0) {
            echo 'Detail has been successfully saved';
        } else {
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'save_sector') {
    if (isset($_REQUEST['subbtn'])) {
        $sid = $_REQUEST['sector_id-whr'];
        $statecode = $_REQUEST['stateCode-fd'];
        $sectorname = $_REQUEST['sector_name-fd'];
        $user_resp = $dbobject->doSector($sid, $statecode, $sectorname);
        if ($user_resp == -9) {
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($user_resp > 0) {
            echo 'Detail has been successfully saved';
        } else {
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'save_motovehicle') {
    if (isset($_REQUEST['subbtn'])) {
        $mid = $_REQUEST['mid'];
        $SHOPNAME = $_REQUEST['SHOPNAME'];
        $NAMEOFOPERATOR = $_REQUEST['NAMEOFOPERATOR'];
        $ADDRESS = $_REQUEST['ADDRESS'];
        $PHONENUMBER = $_REQUEST['PHONENUMBER'];
        $METHODOFWASTEDISPOSAL = $_REQUEST['METHODOFWASTEDISPOSAL'];

        $user_resp = $dbobject->doMotovehicle($mid, $SHOPNAME, $NAMEOFOPERATOR, $ADDRESS, $PHONENUMBER, $METHODOFWASTEDISPOSAL);
        if ($user_resp == -9) {
            echo 'Recorld already exist, please enter a different Data';
        } elseif ($user_resp > 0) {
            echo 'Detail has been successfully saved';
        } else {
            echo 'Error : Please check detail';
        }
    }
} elseif ($op == 'save_contact') {
    if (isset($_REQUEST['subbtn'])) {
        if (!isset($_REQUEST['name'])) {
            echo 'Please Input your Fullname';
            exit();
        } elseif (!isset($_REQUEST['Message'])) {
            echo 'Please Input your Message';
            exit();
        } else {
            $name = $_REQUEST['name'];
            $email = $_REQUEST['email'];
            $message = $_REQUEST['Message'];

            // $name=cryptoJsAesDecrypt(SONMPASSWORDKEY,$name);
            // $email=cryptoJsAesDecrypt(SONMPASSWORDKEY,$email);
            // $message=cryptoJsAesDecrypt(SONMPASSWORDKEY,$message);
            $user_resp = $dbobject->doContact($name, $email, $message);

            if ($user_resp == -9) {
                echo 'Messgae detail already exist, please enter a different Message';
            } elseif ($user_resp > 0) {
                echo 'Message has been successfully Sent';
            } else {
                echo 'Error : Please check Your Message detail';
            }
        }
    }
} elseif ($op == 'save_account_setup') {
    if (isset($_REQUEST['subbtn'])) {
        if (!isset($_REQUEST['beneficiaryName'])) {
            echo 'Please Input your Fullname';
            exit();
        } elseif (!isset($_REQUEST['beneficiaryAccount'])) {
            echo 'Please Input your beneficiary Account';
            exit();
        } else {
            $beneficiaryName = $_REQUEST['beneficiaryName'];
            $aid = $_REQUEST['lineItemId'];
            $beneficiaryAccount = $_REQUEST['beneficiaryAccount'];
            $mobileno = $_REQUEST['mobileno'];
            $beneficiaryName = $_REQUEST['beneficiaryName'];
            $deductFrom = $_REQUEST['deductFrom'];
            $bankcode = $_REQUEST['bankcode'];
            $sectorId = $_REQUEST['Sector'];

            $user_resp = $dbobject->doAccountSetup($aid, $sectorId, $beneficiaryName, $beneficiaryAccount, $bankcode, $deductFrom, $mobileno);

            if ($user_resp == -9) {
                echo 'Messgae detail already exist, please enter a different Message';
            } elseif ($user_resp > 0) {
                echo 'Message has been successfully Sent';
            } else {
                echo 'Error : Please check Your Message detail';
            }
        }
    }
} elseif ($op == 'save_rcc') {
    if (isset($_REQUEST['SubmitBtn'])) {
        if (!isset($_REQUEST['owner_surname-fd'])) {
            echo 'Please Input your Fullname';
            exit();
        } elseif (!isset($_REQUEST['owner_othername-fd'])) {
            echo 'Please Input your Othername';
            exit();
        } elseif ($_REQUEST['union_name-fd'] == No and isset($_REQUEST['union_name-fd'])) {
            echo 'Please Select Correct Union or Association? you belong to : ';
            exit();
        }
        /*else if($_REQUEST['idMark-fd']==No AND isset($_REQUEST['idMarkYes-fd'])){
            echo 'Please Select Correct Identification Number : ';
            exit();
        }*/
        else {
            $pid = $_REQUEST['app_id-whr'];

            $transid = 'transaction Id'; //$_REQUEST['transid'];

            $osname = $_REQUEST['owner_surname-fd'];
            $ooname = $_REQUEST['owner_othername-fd'];
            $odob = $_REQUEST['owner_dob-fd'];
            $ogender = $_REQUEST['owner_gender-fd'];
            $obloodgroup = $_REQUEST['owner_blood_group-fd'];
            $olangSpoken = $_REQUEST['owner_lang-fd'];
            $omaritalstatus = $_REQUEST['owner_marital_status-fd'];
            $onationality = $_REQUEST['owner_Nationality-fd'];
            $omobileno = $_REQUEST['owner_gsm-fd'];
            $oaddress = $_REQUEST['owner_address-fd'];

            $opsurname = $_REQUEST['operator_surname-fd'];
            $oponame = $_REQUEST['operator_othername-fd'];
            $opdob = $_REQUEST['operator_dob-fd'];
            $opgender = $_REQUEST['operator_gender-fd'];
            $opbloodg = $_REQUEST['operator_blood_group-fd'];
            $oplangspoken = $_REQUEST['operator_lang-fd'];
            $opmaritalstatus = $_REQUEST['operator_marital_status-fd'];
            $opnationality = $_REQUEST['operator_nationality-fd'];
            $opmobileno = $_REQUEST['operator_gsm-fd'];
            $opaddress = $_REQUEST['operator_address-fd'];

            $kinfullname = $_REQUEST['operator_nextofkin_name-fd'];
            $kinaddress = $_REQUEST['operator_nextofkin_address-fd'];
            $kinmobileno = $_REQUEST['operator_nextofkin_mobileno-fd'];
            $kinemail = $_REQUEST['operator_nextofkin_email-fd'];

            $make = $_REQUEST['cycle_make-fd'];
            $type = $_REQUEST['cycle_type-fd'];
            $idnetificationmark = $_REQUEST['idMark-fd'];
            $stateno = $_REQUEST['idMarkYes-fd'];
            $chessisno = $_REQUEST['cycle_chassis_number-fd'];
            $engineeno = $_REQUEST['cycle_engine_number-fd'];
            $colour = $_REQUEST['cycle_Colour-fd'];

            $anyunion = $_REQUEST['union_Association-fd'];
            $nameofassocation = $_REQUEST['union_name-fd'];
            $officeaddress = $_REQUEST['union_address-fd'];
            $areacouncil = $_REQUEST['union_Area_councile-fd'];
            $typeofid = $_REQUEST['union_type_of_id-fd'];
            $idno = $_REQUEST['union_id_no-fd'];
            $idissuedate = $_REQUEST['union_id_dateIssue-fd'];
            $idexpiredate = $_REQUEST['union_id_dateExpire-fd'];
            $issueauthority = $_REQUEST['union_issue_authority-fd'];
            $permit_issue_dates = $_REQUEST['created-fd'];
            $permit_expiry_dates = $_REQUEST['expire_date-fd'];
            $operation = $_REQUEST['operation'];
            // echo "datatttt".$aid.	$osname.$ooname; 47

            $user_resp = $dbobject->doRcc($pid, $transid, $osname, $ooname, $odob, $ogender, $obloodgroup, $olangSpoken, $omaritalstatus, $onationality, $omobileno, $oaddress, $opsurname, $oponame, $opdob, $opgender, $opbloodg, $oplangspoken, $opmaritalstatus, $opnationality, $opmobileno, $opaddress, $kinfullname, $kinaddress, $kinmobileno, $kinemail, $make, $type, $idnetificationmark, $stateno, $chessisno, $engineeno, $colour, $anyunion, $nameofassocation, $officeaddress, $areacouncil, $typeofid, $idno, $idissuedate, $idexpiredate, $issueauthority, $permit_issue_dates, $permit_expiry_dates, $operation);

            if ($user_resp == -9) {
                echo 'Messgae detail already exist, please enter a different Message';
            } elseif ($user_resp > 0) {
                echo 'Message has been successfully Sent';
            } else {
                echo 'Error : Please check Your Message detail';
            }
        }
    }
} elseif ($op == 'save_user') {
    if (isset($_REQUEST['subbtn'])) {
        /////////////////////////////////////////////////////////////////////////////////////////
        $username = $_REQUEST['username'];
        $userpassword = $_REQUEST['userpassword'];
        $firstname = $_REQUEST['firstname'];
        $lastname = $_REQUEST['lastname'].' '.$_REQUEST['middlename'];
        $email = $_REQUEST['email'];
        $phone = $_REQUEST['phone'];
        $Userstate = $_REQUEST['statecode'];
        $chgpword_logon = $_REQUEST['chgpword_logon'] != '1' ? '0' : $_REQUEST['chgpword_logon'];
        $user_locked = $_REQUEST['user_locked'] != '1' ? '0' : $_REQUEST['user_locked'];
        $user_disable = $_REQUEST['user_disable'] != '1' ? '0' : $_REQUEST['user_disable'];
        $day_1 = $_REQUEST['day_1'] != '1' ? '0' : $_REQUEST['day_1'];
        $day_2 = $_REQUEST['day_2'] != '1' ? '0' : $_REQUEST['day_2'];
        $day_3 = $_REQUEST['day_3'] != '1' ? '0' : $_REQUEST['day_3'];
        $day_4 = $_REQUEST['day_4'] != '1' ? '0' : $_REQUEST['day_4'];
        $day_5 = $_REQUEST['day_5'] != '1' ? '0' : $_REQUEST['day_5'];
        $day_6 = $_REQUEST['day_6'] != '1' ? '0' : $_REQUEST['day_6'];
        $day_7 = $_REQUEST['day_7'] != '1' ? '0' : $_REQUEST['day_7'];
        $override_wh = $_REQUEST['override_wh'] != '1' ? '0' : $_REQUEST['override_wh'];
        $extend_wh = $_REQUEST['extend_wh'];
        if ($override_wh != '1') {
            $extend_wh = '';
        }
        $role_id = $_REQUEST['role_id'];
        $operation = $_REQUEST['operation'];
        $role_id = $_REQUEST['role_id'];

        if (isset($_REQUEST['statecode'])) {
            $_SESSION['statecode'] = $_REQUEST['statecode'];
        } else {
            $_SESSION['statecode'] = $_SESSION['statecode_sess'];
        }
        //$dbobject = new dbobject();
        $uid = $dbobject->paddZeros($dbobject->getnextid('login_id'), 4);
        $role_name = $dbobject->getitemlabel('role', 'role_id', $role_id, 'role_name');
        // echo " operation: ".$operation." uid: ".$uid." username: ".$username." userpassword: ".$userpassword." firstname: ".$firstname." lastname: ".$lastname." email: ".$email." phone: ".$phone." chgpword_logon: ".$chgpword_logon." user_locked: ". $user_locked." user_disable: ".$user_disable." day_1: ".$day_1." day_2: ".$day_2." day_3: ".$day_3." day_4: ".$day_4." day_5: ".$day_5." day_6: ".$day_6." day_7: ".$day_7." override_wh: ".$override_wh." extend_wh: ".$extend_wh." role_id: ".$role_id." role_name: ".$role_name." Userstate: ".$Userstate."<br/>";
        $user_resp = $dbobject->doUser($operation, $uid, $username, $userpassword, $firstname, $lastname, $email, $phone, $chgpword_logon, $user_locked, $user_disable, $day_1, $day_2, $day_3, $day_4, $day_5, $day_6, $day_7, $override_wh, $extend_wh, $role_id, $role_name, $Userstate);

        if ($user_resp == -9) {
            echo 'User detail already exist, please enter a different username';
        } elseif ($user_resp > 0) {
            echo 'User detail has been successfully saved';
        } else {
            echo 'Error : Please check User detail';
        }
    }
} elseif ($op == 'cascaded_getdataselect') {
    $sql = $_REQUEST['str'];
    if ($sql) {
        $options = $dbobject->getdataselect($sql);
        echo $options;
    }
} elseif ($op == 'edit_user') {
    //if(isset($_REQUEST['password'])){

    $firstname = $_REQUEST['fname'];
    $lastname = $_REQUEST['lname'];
    $reg_email = $_REQUEST['reg_email'];
    $phone = $_REQUEST['phone'];
    $dob = $_REQUEST['dob'];
    $gender = $_REQUEST['gender'];
    $contact_address = $_REQUEST['address'];
    $user_resp = $dbobject->doEditUser($reg_email, $firstname, $lastname, $phone, $dob, $gender, $contact_address);

    if ($user_resp == -9) {
        echo 'Account can not be updated at this moment';
    } elseif ($user_resp > 0) {
        echo 'Your profile has been updated';
    } else {
        echo 'Error : Please check User detail'.$user_resp;
    }
    //}else{ echo 'here is the bug';  }
} elseif ($op == 'save_menu') {
    //if(isset($_REQUEST['subbtn'])){
    $menu_id = $_REQUEST['menu_id'];
    $menu_name = $_REQUEST['menu_name'];
    $menu_url = $_REQUEST['menu_url'];
    $parent_menu = $_REQUEST['parent_menu'];

    $operation = $_REQUEST['operation'];
    //if($parent_menu!='#')echo 'parent menu : '.$parent_menu;
    $parent_id2 = '';
    $menu_level = $parent_menu == '#' ? '0' : '1';
    if ($menu_url == '#' && $parent_menu != '#') {
        $menu_level = '1';
        $parent_id2 = '#';
    }
    if ($menu_url != '#' && $parent_menu != '#') {
        $menu_level = $dbobject->getitemlabel('menu', 'menu_id', $parent_menu, 'menu_level');
        //echo 'Level : '.$menu_level;
        $menu_level = ($menu_level == '0') ? '1' : '2';
        $parent_id2 = $parent_id;
        $parent_id = $dbobject->getitemlabel('menu', 'menu_id', $parent_id, 'parent_id');
        //select parent_id from menu where menu_id= '$parent_id'
        if ($operation != 'edit') {
            $form_url = $menu_url.'_form.php';
            $menu_url = $menu_url.'_list.php';
            if (file_exists($menu_url)) {
                echo 'Error : Please menu url exists'.$menu_url;

                exit();
            } else {
                $mc = fopen($menu_url, 'w');

                $content = str_replace(
                                array('%title%', '%report_link%', '%form_link%'),
                                array($menu_name, "$menu_url", "$form_url"),
                                file_get_contents('report_list.php', FILE_USE_INCLUDE_PATH)
                            );
                fwrite($mc, $content);
                fclose($mc);
                $mcf = fopen($form_url, 'w');
                $formContent = str_replace(
                                    array('%title%', '%report_link%'),
                                    array($menu_name,   "$menu_url"),
                                    file_get_contents('form_form.php', FILE_USE_INCLUDE_PATH)
                                );
                fwrite($mcf, $formContent);

                fclose($mcf);
            }
        }
    }

    $menu_resp = $dbobject->doMenu($menu_id, $menu_name, $menu_url, $parent_menu, $menu_level, $parent_id2);
    if ($menu_resp == '1') {
        echo 'Menu detail has been successfully saved';
    } else {
        echo 'Error : Please check Menu detail';
    }
    //}
}
    /////////////////////////////////////
    if ($op == 'getexistrole') {
        $menu_id = $_REQUEST['menu_id'];
        $existrole = $dbobject->getexistrole($menu_id);
        echo $existrole;
    }

    ////////////////////////////////////
    if ($op == 'getnonexistrole') {
        $menu_id = $_REQUEST['menu_id'];
        $noexistrole = $dbobject->getnonexistrole($menu_id);
        echo $noexistrole;
    } elseif ($op == 'save_menugroup') {
        if (isset($_REQUEST['menu_id'])) {
            $menu_id = $_REQUEST['menu_id'];
            $exist_role = $_REQUEST['exist_role'];
            $menugroup_resp = $dbobject->doMenuGroup($menu_id, $exist_role);
            if ($menugroup_resp > 0) {
                echo 'MenuGroup detail has been successfully saved';
            } else {
                echo 'Error : Please check MenuGroup detail';
            }
        }
    } elseif ($op == 'save_password_exp') {
        if (isset($_REQUEST['subbtn'])) {
            $username = $_REQUEST['username'];
            $oldpassword = $_REQUEST['oldpassword'];
            $user_password = $_REQUEST['userpassword'];
            $pass_expiry_days = $_SESSION['password_expiry_days'];
            $today = @date('Y-m-d');
            $pass_dateexpire = @date('Y-m-d', strtotime($today.'+'.$pass_expiry_days.'days'));
            if ($dbobject->validatepassword($username, $oldpassword) == '1') {
                $curr_resp = $dbobject->doPasswordChangeExp($username, $user_password, $pass_dateexpire);
                if ($curr_resp == 1) {
                    echo 'The User password has been successfully changed ';
                } else {
                    echo 'Error : Please check password detail';
                }
            } else {
                echo 'Your old password is invalid';
            }
        }
    } elseif ($op == 'save_password_logon') {
        if (isset($_REQUEST['subbtn'])) {
            $username = $_REQUEST['username'];
            $oldpassword = $_REQUEST['oldpassword'];
            $user_password = $_REQUEST['userpassword'];
            if ($dbobject->validatepassword($username, $oldpassword) == '1') {
                $curr_resp = $dbobject->doPasswordChangeLogon($username, $user_password);
                if ($curr_resp == 1) {
                    echo 'The User password has been successfully changed ';
                } else {
                    echo 'Error : Please check password detail';
                }
            } else {
                echo 'Your old password is invalid';
            }
        }
    } elseif ($op == 'save_reordersubmenu') {
        if (isset($_REQUEST['subbtn'])) {
            $parent_menu = $_REQUEST['parent_menu'];
            $sub_menu = $_REQUEST['sub_menu'];
            //echo $sub_menu;
            $curr_resp = $dbobject->reorder_submenu($parent_menu, $sub_menu);
            if ($curr_resp > 0) {
                echo 'Menu Re-ordering has been successfully saved';
            } else {
                echo 'Error : Changes were not saved';
            }
        }
    } elseif ($op == 'editTrans') {
        if (isset($_REQUEST['subbtn'])) {
            $operation = $_REQUEST['operation'];
            $message = $_REQUEST['message'];

            $tbl = $_REQUEST['tableName'];

            $innp = $_REQUEST['inputs'];
            $inpFds = explode(',', $innp);
            //echo  "Files : ".$inpFds."  ";
            //var_dump($inpFds);
            for ($j = 0; $j < count($inpFds); ++$j) {
                $inpFdsVals[$j] = $_REQUEST[$inpFds[$j]];
                //$inpp .= $inpFds[$j].'='.$inpFdsVals[$j].'-';
            }
            $resp = $dbobject->SaveTransEdit($tbl, $inpFds, $inpFdsVals, $operation);

            if ($resp == '1') {
                echo $resp.'::||::'.$message.' Has been Successful Saved !!!';
            } elseif ($resp == '-1') {
                echo 'ERROR:: '.$message.' Details cannot be Saved:! Please check form Details ';
            } elseif ($resp == '-2') {
                echo 'ERROR:: Update Failed !';
            } else {
                echo $resp;
            }
        }
    } elseif ($op == 'save_secondary') {
        $email = $_SESSION['username_sess'];
        $super_id = $dbobject->getitemlabel('bio_data', 'email', $email, 'application_no');

        $app_log_id = $dbobject->getitemlabel('applicant_log', 'app_id', $super_id, 'app_id');

        $o_level_grade = $_REQUEST['o_level_grade1'];
        $exam_type = $_REQUEST['exam_type1'];
        $period = $_REQUEST['period'];
        $subject1 = $_REQUEST['subject1'];

        /////////////// Check if there are duplicate subject
        $checker = count($subject1) !== count(array_unique($subject1));
        if (($checker == true && $_REQUEST['no_of_sittings'] == 1)) {
            $message = 'One or more of the subjects in your first sitting is the same';
            $datas = array('response_code' => 957, 'response_message' => $message, 'data' => []);
            echo json_encode($datas);
            exit();
        } elseif ($checker == false && $_REQUEST['no_of_sittings'] == 1) {
            ///////////////////////////////////O LEVEL for one sitting /////////////////////
            if (!($app_log_id)) {
                ////// insert new olevel record
                $str = '(subject,sittings,grades,exam_type,created,period,sch_name,end_date,start_date,app_id)';
                $val = '';
                for ($i = 0; $i < count($subject1); ++$i) {
                    $val .= '(';

                    $val .= "'".$_REQUEST[subject1][$i]."','1','".$_REQUEST[o_level_grade1][$i]."','".$_REQUEST[exam_type1]."',NOW(),'".$_REQUEST[period1]."','".$_REQUEST[o_level_sch]."','".$_REQUEST[o_level_end]."','".$_REQUEST[o_level_start]."','".$super_id."'";

                    $val .= '),';
                }
                $val = substr($val, 0, -1);
                $sql = 'INSERT INTO applicant_log '.$str.' VALUES'.$val;
                //            echo $sql;
                $result = mysql_query($sql);
                $count = mysql_affected_rows();
                if ($count > 0) {
                    $datas = array('response_code' => 0, 'response_message' => 'Record Saved Successfully', 'data' => []);
                    echo json_encode($datas);
                } else {
                    $datas = array('response_code' => 62, 'response_message' => 'Unable to save record', 'data' => []);
                    echo json_encode($datas);
                }
            } else {
                $sql = "DELETE  FROM applicant_log WHERE app_id = '$app_log_id'";
                $result = mysql_query($sql);
                $count = mysql_affected_rows();
                if ($count > 0) {
                    $str = '(subject,sittings,grades,exam_type,created,period,sch_name,end_date,start_date,app_id)';
                    $val = '';
                    for ($i = 0; $i < count($subject1); ++$i) {
                        $val .= '(';

                        $val .= "'".$_REQUEST[subject1][$i]."','1','".$_REQUEST[o_level_grade1][$i]."','".$_REQUEST[exam_type1]."',NOW(),'".$_REQUEST[period1]."','".$_REQUEST[o_level_sch]."','".$_REQUEST[o_level_end]."','".$_REQUEST[o_level_start]."','".$super_id."'";

                        $val .= '),';
                    }
                    $val = substr($val, 0, -1);
                    $sql = 'INSERT INTO applicant_log '.$str.' VALUES'.$val;
                    //            echo $sql;
                    $result = mysql_query($sql);
                    $count = mysql_affected_rows();
                    if ($count > 0) {
                        $datas = array('response_code' => 0, 'response_message' => 'Record Saved Successfully', 'data' => []);
                        echo json_encode($datas);
                    } else {
                        $datas = array('response_code' => 62, 'response_message' => 'Unable to save record', 'data' => []);
                        echo json_encode($datas);
                    }
                } else {
                    $data = array('response_code' => 843, 'responsemessage' => 'Something went wrong please try again');
                    echo json_encode($data);
                }
            }
        }
        if ($_REQUEST['no_of_sittings'] == 2) {
            $subject2 = $_REQUEST['subject2'];
            $out = count($subject2) !== count(array_unique($subject2));
            if ($out == true) {
                $message = 'One or more of the subjects in your second sitting is the same';
                $datas = array('response_code' => 957, 'response_message' => $message, 'data' => []);
                echo json_encode($datas);
            } else {
                ///////////////////////////////////O LEVEL for two sitting /////////////////////
                $str = '(subject,sittings,grades,exam_type,created,period,sch_name,end_date,start_date,app_id)';
                $val = '';
                for ($i = 0; $i < count($subject1); ++$i) {
                    $val .= '(';

                    $val .= "'".$_REQUEST[subject2][$i]."','2','".$_REQUEST[o_level_grade2][$i]."','".$_REQUEST[exam_type2]."',NOW(),'".$_REQUEST[period2]."','".$_REQUEST[o_level_sch]."','".$_REQUEST[o_level_start]."','".$_REQUEST[o_level_start]."','".$super_id."'";

                    $val .= '),';
                }
                $val = substr($val, 0, -1);
                $sql = 'INSERT INTO applicant_log '.$str.' VALUES'.$val;
                //            echo $sql;
                $result = mysql_query($sql);
                $count = mysql_affected_rows();
                if ($count > 0) {
                    //                $status = "saved";
                    $datas = array('response_code' => 0, 'response_message' => 'Record Saved Successfully', 'data' => []);
                    echo json_encode($datas);
                } else {
                    $datas = array('response_code' => 74, 'response_message' => 'unable to save record', 'data' => []);
                    echo json_encode($datas);
                }
            }
        }
    }
