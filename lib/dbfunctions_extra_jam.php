<?php
error_reporting(0);
@session_start();
///////////////////
/*error_reporting(E_ERROR);*/
require 'dbfunctions.php';
//////////////////////
class myDbObject extends dbobject
{
    private $created;
    private $currentUser;

    private $merchant_id;

    public function __construct()
    {
        $this->merchant_id = 'ACC-OPMHT000000182';
        $this->created = date('Y-m-d H:i:s');
        if(isset($_SESSION['sonm_username'])){
        $this->currentUser = $_SESSION['sonm_username'];
    }
    }

    public function cleanUpData($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                cleanUpData($value);
            } else {
                $array[$key] = trim(mysql_real_escape_string($value));
            }
        }

        return $array;
    }
 public function confirm($userid,$confrim)
    {
        $query = "select * from applicant_account_setup where reg_id='$userid' and program ='101'";

        $file =$_SESSION['filename'];
        //$_SESSION['filename']
        $result = mysql_query($query);
        $row = mysql_fetch_array( $result);
       if(!isset($file )){
            echo "0";
            return "0";

       }else
        if($row['reg_status'] =="33"){
        if($row['passport'] ==""){
            echo "0";
            return "0";
        }else if($row['passport']!="") {
            if($confrim =='hello'){
                if(isset($file)){
            $up ="UPDATE applicant_account_setup SET application_lock ='1' where reg_id='$userid' and program ='101' ";
             $update = mysql_query($up);
             if($up > 0){
                echo "1";
                return "1";
             }else{
               echo "1";
                return "1";
             }
         }else{
             echo "0";
            return "0";
         }
}else{
            echo "5";
                return "5";
        }
        }
    }else{
           echo "4";
                return "4";
        }

    }






    public function getApplyNow($surname, $othernaame, $Program, $email, $password, $phoneno,$fname,$cpassword,$Gender)
    {   
        // var_dump("================================= In dbfuntionction =======================================");
        $numrows  = 0;
         $label = "";
        if($surname == ''){
            $label ="Please Enter Your Surname";
        }
        if($fname == ''){
            $label ="Please Enter Your Firstname";
        }
         if($cpassword != $password){
            $label = 20;
             return $label;
             exit(1);
        }
        $dbobject = new dbobject();
        $query = "select * from app_applicant_account_setup where email='$email'";
        // echo $query."\n";
        $result = mysql_query($query);
        $numrows = mysql_affected_rows();
        // echo $numrows."\n";
        if($label !=""){
             $label =  $label;
        }else if ($numrows > 0) {
            // $rows = mysql_fetch_array($result);
            $label = 0;
        } else {
            $year = @date('Y');
            $regId = $year.$dbobject->paddZeros($dbobject->getnextid('ApplyNow'), 6);
            // echo $regId."\n";
            //$regId = $dbobject->generatePIN(6, 0);
            $registrationId = $year.$regId;
            // echo $registrationId."\n";
            @$now = date('Y-m-d H:i:s');
           // $userpass =$password;
            $userpass =  $dbobject->encrypt_password($email, $password);
            // echo $userpass."\n";
            //reg_id, session, email, surname, othernaame, phone_number, program, userpassword, created, rrr, reg_status, rrr_status, linkCode, date_of_birth, gender, marital_status, Nationality, state_of_origin, local_Gov_Area, District_word, tribe, religion, postal_address, exam_center, ip, gname, gaddress, statusCode, pay_status, pbirth, passport, fname, rrr_acceptance, rrr_acceptance_date, rrr_acceptance_status, application_lock, educational_status
            $linkCode = $dbobject->generatePIN(64, 1);
            // echo $linkCode."\n";
            //file_get_contents("result.txt",$linkCode);
            $str = "INSERT INTO app_applicant_account_setup (session,email,surname,othernaame,program,userpassword,reg_id,phone_number,created,linkCode,reg_status,fname,gender) VALUE('$year','$email','$surname','$othernaame','$Program',' $userpass','$registrationId','$phoneno','$now','$linkCode','00','$fname','$Gender')";
            // echo $str;
            //file_put_contents("up2.txt",$str);
            $result = mysql_query($str); // or die(mysql_error());
            $label = mysql_affected_rows();
            if ($label > 0) {
                $whatIWant = substr($email, strpos($email, '@') + 1);
                $_SESSION['sonm_mail_log'] = 'http://www.'.$whatIWant;
                $_SESSION['sonm_user'] = $email;
                // var_dump("USER -==========>>>>>>>>>> ".$_SESSION['sonm_user']."  I DONT KNOW =======================>>>>> ".$_SESSION['sonm_mail_log']);
                $message = $dbobject->reg_email_template($surname, $email, $password, $linkCode);
                // var_dump("Message ==============> ".$message);
                // file_put_contents("mail.txt", $message);

				//  echo $message;

			 //exit();
                $subject = 'SONM Application';
                // $emaail_resp =	$dbobject->sendMail_global($email, $subject, $message);
                // 	if($emaail_resp){
                // 		// echo "Email sent ";
                // 	}else{
                // 		echo "Email not sent ";
                // 	}
                // echo $message;
                $label = '44';
              //  $dbobject->send_mail_online($email, $subject, $message);

              $dbobject->sendMail_new($email, $subject, $message);

            }
        }

        // var_dump($label);

        return $label;
    }


    public function getApplyNow3($aid, $email, $program, $surname, $othername, $country, $state, $lga, $dob, $pbirth, $postalAddress, $gname, $gaddress, $examCenter, $religion, $tribe, $religion1, $maritial,$centre,$ward)
    {
        $year = @date('Y');
        //getApplyNow3($aid, $email, $program, $Surname, $othername, $country, $state, $lga, $dob, $pbirth, $postal_address, $Gname, $Gaddress, $Gaddress, $Gaddress, $tribe, $religion, $maritial,$centre,$ward);
        $dbobject = new dbobject();

        //$dbobject->logs(' Program  ::  '.$program.' ::  Surname '.$surname.'  ::othername  '.$othername.'  ::Country  '.$country.'  :: state '.$state.'  :: lga '.$lga.'  ::dob  '.$dob.'  :: pbirth '.$pbirth.'  ::postal_address  '.$postalAddress.'  ::Gname  '.$gname.'  ::Gaddress  '.$gaddress);
//center_id, center_name, user, created
        $query = "select * from app_applicant_account_setup where email='$email' and session ='$year'";
        $main_exam_center = $dbobject->getitemlabel('app_center_tb', 'center_id', $centre, 'center_name');
        $result = mysql_query($query);

        $numrows = mysql_affected_rows();
        if ($numrows > 0) {
            $linkCode = $dbobject->generatePIN(64, 1);
           //reg_id, session, email, surname, othernaame, phone_number, program, userpassword, created, rrr, reg_status, rrr_status, linkCode, date_of_birth, gender, marital_status, Nationality, state_of_origin, local_Gov_Area, District_word, tribe, religion, postal_address, exam_center, ip, gname, gaddress, statusCode, pay_status, pbirth, passport, fname, rrr_acceptance, rrr_acceptance_date, rrr_acceptance_status, application_lock, educational_status, admissionstatus, date_adm, exam_center_id
            $iipp = $_SERVER['REMOTE_ADDR']; //$dbobject->getRealIp();

            $str = "UPDATE app_applicant_account_setup SET ward ='$ward', Nationality='$country',state_of_origin='$state',local_Gov_Area='$lga',religion='$religion',postal_address='$postalAddress',exam_center='$gaddress',gname='$gname',gaddress='$examCenter',ip='$iipp',reg_status='33',date_of_birth= '$dob',marital_status ='$maritial', pbirth = '$pbirth' , tribe ='$tribe' , exam_center_id ='$centre' WHERE reg_id ='$aid' AND email ='$email' and session ='$year'";
            //echo $str;
           file_put_contents("up.txt", $str);
            $result = mysql_query($str); // or die(mysql_error());
            $label = mysql_affected_rows();
            // echo  $label.' a::::::::::::::::::::::::::::::: a ';
            if ($label > 0) {
                $label = 11;
                // $message = $dbobject->reg_email_template($othernaame, $email, $password, $linkCode);
           // $subject = 'SONM Application';
            // $emaail_resp =	$dbobject->sendMail_global($email, $subject, $message);
            // 	if($emaail_resp){
            // 		// echo "Email sent ";
            // 	}else{
            // 		echo "Email not sent ";
            }
            // echo $message;
            //$dbobject->send_mail_online($email, $subject, $message);

            //}
        }

        return $label;
    }

    public function getApplyNow4($aid, $email, $pri_school_name, $pri_result, $prim_end_year, $jun_school_name, $jun_result, $result3,$jun_end_year,$sec_school_name,$sec_result,$sec_end_year)
    {$label = 0;
		try{

        $dbobject = new dbobject();
        @$now = date('Y-m-d H:i:s');
        $query = "select * from app_educational_qualification_tb where reg_id='$aid'";
        $label=0;
        $result = mysql_query($query);
        $numrows = mysql_affected_rows();
        if ($numrows <= 0) {
            $iipp = $_SERVER['REMOTE_ADDR'];
            $query1 = "INSERT INTO app_educational_qualification_tb(reg_id,pri_school_name,pri_certificate_obtained,pri_end_date,jun_school_name,jun_certificate_obtained,jun_end_date,sec_school_name,sec_certificate_obtained,sec_end_date,created)VALUES('$aid','$pri_school_name', '$pri_result', '$prim_end_year', '$jun_school_name', '$jun_result','$jun_end_year','$sec_school_name','$sec_result','$sec_end_year','$now')";

            $result1 = mysql_query($query1) ;
            $label = mysql_affected_rows();
				$label=11;
      		}else{
                 $resultrow = mysql_fetch_array($result);

				$query2 ="UPDATE app_educational_qualification_tb set pri_school_name ='$pri_school_name' , pri_certificate_obtained ='$pri_result' ,pri_end_date ='$prim_end_year' , jun_school_name = '$jun_school_name',  jun_certificate_obtained ='$jun_result' , jun_end_date ='$jun_end_year', sec_school_name ='$sec_school_name', sec_certificate_obtained ='$sec_result',sec_end_date ='$sec_end_year' where  reg_id ='{$resultrow['reg_id']}'";
                    $result1 = mysql_query($query2) ;
                    $label=11;
                		 //     $regId = $dbobject->paddZeros($dbobject->getnextid('results'), 10);
				//     $query = "INSERT INTO results_tb(sid,reg_id,subject_name,result,grade,year,remarks,created)VALUES('$aid','$schoolname1','$result1','$schoolname2','$result2','$schoolname3','$result3','$now')";
				//     $result = mysql_query($query); // or die(mysql_error());
				//     $label = mysql_affected_rows();
						}

				}
				catch (exception $e) {
			//code to handle the exception
			$label=0;
			}


        return $label;
    }

    public function getResultIn($regId)
    {
        $dbobject = new dbobject();

        //  $dbobject->logs($reg_id." :::::::::::: ".$result_type." :::::::::::: ".$subject_name." :::::::::::::: ".$grade." ::::::::::: ".$year." ::::::::::");

        @$now = date('Y-m-d H:i:s');
        //@$aid = date('YmdHis');
        $query = "select * from app_results_tb where reg_id='$regId'";
        $result = mysql_query($query);

        $numrows = mysql_affected_rows();
        if ($numrows > 0) {
            $query = "DELETE FROM app_results_tb WHERE reg_id='$regId'";
            $dbobject->logs($query);
            $result = mysql_query($query);
        }
    }

  public function getApplyNow41($regId, $resultType, $subjectName, $grade, $year, $remarks,$sitting,$position)
    {  $resultsearch =0;
        $dbobject = new dbobject();
        $subjectName = str_replace("%23"," ",$subjectName);
        $grade = str_replace("%23"," ",$grade);
        $resultType = str_replace("%23"," ",$resultType);
        $year = str_replace("%23"," ",$year);
        @$now = date('Y-m-d H:i:s');

        $aid = $regId.$resultType.$subjectName.$sitting;
        //$dbobject->logs($reg_id." :::::::::::: ".$result_type." :::::::::::: ".$subject_name." :::::::::::::: ".$grade." ::::::::::: ".$year." ::::::::::");
//sid, reg_id, subject_name, result, grade, year, remarks, created, siting

       try{
         $resultsearch =$dbobject->getitemcount($regId,$position,$sitting,$resultType);
       }catch(exception $ee){
        $resultType = 0;
       }

if(($resultType !=" " )&& ($year !=" ") && (!empty($year))){
       if( $resultsearch <= 0){
        $queryfirst = "INSERT INTO results_tb(sid,reg_id,subject_name,result,grade,year,remarks,created,siting,position)VALUES('$aid','$regId','$subjectName','$resultType','$grade','$year','$remarks','$now','$sitting','$position')";

        $result = mysql_query($queryfirst); // or die(mysql_error());
        if( $result > 0){
            $label =1;
        }

    }else if($resultsearch == 1){

         $aidnew =$dbobject->getitemKey($regId,$subjectName,$sitting,$resultType);
        $pos =$dbobject->getitemPosition($regId,$subjectName,$sitting,$resultType);

           $key =  $dbobject-> getitemPosition2($regId,$sitting,$resultType,$position) ;
            if(((int) $pos)<=0){
                 $querymain ="Update results_tb set subject_name='$subjectName', result='$resultType',grade ='$grade',remarks = '$remarks' where sid ='$key'";
                  $result = mysql_query($querymain);
          if($result > 0){
            $label = 1;
          }else{
            $label =0;
          }
            }else
        if(( ((int)$position)==((int)$pos) ) ){

         $querymain ="Update results_tb set subject_name='$subjectName', result='$resultType',grade ='$grade',remarks = '$remarks' where sid ='$key'";
          $result2 = mysql_query($querymain);
if($result2 > 0){
             $label ='1';
          }else{
            $label =0;
          }

      }else {

          $label = -1;

       echo $label;

         exit(1);
      }



    }else if($resultsearch > 1){

         $label = -1;
          return $label;
         exit(1);

    }


}else{
    if($sitting =='1'){
        $label = 11;
    }else{
        $label = 12;
    }
 echo $label;
 exit(1);
}

    ///

        if($label == 1){
            //apply_now4
            mysql_query("UPDATE applicant_account_setup set educational_status = '1' where reg_id = '$regId' and program ='101' ");
        }
          $dbobject->logs(" response => ".$label);


      return $label;
    }
//getApplicantNowLogin
 public function getApplicantNowLogin($email, $pass)
    {//application_lock
        $dbobject = new dbobject();
        $desencrypt = new DESEncryption();
        //$pass =  trim($dbobject ->encrypt_password($email, $pass));
        $key = $email; //"mantraa360";
        //reg_id, session, email, surname, othernaame, phone_number, program, userpassword, created, rrr, reg_status, rrr_status, linkCode, date_of_birth, gender, marital_status, Nationality, state_of_origin, local_Gov_Area, District_word, tribe, religion, postal_address, exam_center, ip, gname, gaddress, statusCode, pay_status, pbirth, passport, fname, rrr_acceptance, rrr_acceptance_date, rrr_acceptance_status, application_lock, educational_status, admissionstatus, date_adm, exam_center_id, ward, date_locked, application_count
        $cipher_password = $desencrypt->des($key, $pass, 1, 0, null, null);
        $str_cipher_password = $desencrypt->stringToHex($cipher_password);
        $query = "select * from applicant_account_setup where email='$email' and program ='101'";

        $result = mysql_query($query);
        $numrows = mysql_affected_rows();
        if ($numrows > 0) {
            $row = mysql_fetch_array($result);
            $passs = trim($row['userpassword']);
            $reg_status = $row['rrr_status'];
            $reg_id = $row['reg_id'];
            $linkCode = $row['linkCode'];
            $application_lock  = $row['application_lock'];
            $admissionstatus = $row['admissionstatus'];
            if ($str_cipher_password == $passs) {
                if ($reg_status != '025') {
                    //reg_id, session, email, surname, othernaame, phone_number, program, userpassword, created, rrr, reg_status, rrr_status, linkCode, date_of_birth, gender, marital_status, Nationality, state_of_origin, local_Gov_Area, District_word, tribe, religion, postal_address, exam_center, ip, gname, gaddress, statusCode, pay_status, pbirth, passport, fname, rrr_acceptance, rrr_acceptance_date, rrr_acceptance_status, application_lock, educational_status, admissionstatus, date_adm, exam_center_id
                    if( $reg_status !='025'){
                    $res = '00';  // login successfull
                    $_SESSION['reg_id'] = $reg_id;
                    $_SESSION['vcode'] = $linkCode;
                }else{
                   $res ="-1";
                }

                } else {
                    $res = '02';
                }
            } else {
                $res = '01'; // incorect password
            }
            //}else{
            //$res="02";  // email is not validat
            /// }
            return $res;
        } else {
            return '03';  // email is not valide (incorect email)
        }
    }

    public function getApplyNowLogin($email, $pass)
    {
        $dbobject = new dbobject();
        $pass =  trim($dbobject ->encrypt_password($email, $pass));
        $year = @date('Y');
        $query = "select * from app_applicant_account_setup where email='$email' and session ='$year'";

        $result = mysql_query($query);
        $numrows = mysql_affected_rows();
        if ($numrows > 0) {
            $row = mysql_fetch_array($result);
            $passs = trim($row['userpassword']);
            $reg_status = $row['reg_status'];
            $reg_id = $row['reg_id'];
            $admissionstatus = $row['reg_status'];
            $linkCode = $row['linkCode'];
            if ($pass == $passs) {
                //
                if ($admissionstatus != '0') {
                    //reg_id, session, email, surname, othernaame, phone_number, program, userpassword, created, rrr, reg_status, rrr_status, linkCode, date_of_birth, gender, marital_status, Nationality, state_of_origin, local_Gov_Area, District_word, tribe, religion, postal_address, exam_center, ip, gname, gaddress, statusCode, pay_status, pbirth, passport, fname, rrr_acceptance, rrr_acceptance_date, rrr_acceptance_status, application_lock, educational_status, admissionstatus, date_adm, exam_center_id
                    $res = '00';  // login successfull
                    $_SESSION['reg_id'] = $reg_id;
                    $_SESSION['vcode'] = $linkCode;
                    $_SESSION['sonm_username'] = $email;

                } else {
                    $res = '02';
                }
            } else {
                $res = '01'; // incorect password
            }
            //}else{
            //$res="02";  // email is not validat
            ///	}
            return $res;
        } else {
            return '03';  // email is not valide (incorect email)
        }
    }

    public function getApply_now_save($Surname, $othernaame, $Program, $email, $password, $phoneno)
    {
        $dbobject = new dbobject();
        //$dbobject->logs("entered  : hereeee");

        $whatIWant = substr($email, strpos($email, '@') + 1);
        $_SESSION['sonm_mail_log'] = 'http://'.$whatIWant;

        $query = "select * from applicant_account_setup where email='$email' and program ='101'";
        $result = mysql_query($query);
        $numrows = mysql_affected_rows();
        if ($numrows > 0) {
            $row = mysql_fetch_array($result);
            $label = 0;

            $dbobject->logs('found  : '.$label);
        } else {
            $year = @date('Y');
            $regId = $dbobject->paddZeros($dbobject->getnextid('ApplyNow'), 6);
            $regId = "100".$dbobject->generatePIN(6, 0);
            $registrationId = $year.$regId;
            @$now = date('Y-m-d H:i:s');
            $paymentlink = time();

            $linkCode = $dbobject->generatePIN(64, 1);

            $str = "INSERT INTO applicant_account_setup(session,email,surname,othernaame,program,userpassword,rrr,reg_id,phone_number,created,linkCode)VALUE('$year','$email','$Surname','$othernaame','$Program','$password','$paymentlink','$registrationId','$phoneno','$now','$linkCode')";

            $result = mysql_query($str); // or die(mysql_error());
            $label = mysql_affected_rows();
            //if($label>1){
            $message = $dbobject->reg_email_template($othernaame, $email, $password, $linkCode);
            $subject = 'SONM Application';
            // $emaail_resp =	$dbobject->sendMail_global($email, $subject, $message);
            // 	if($emaail_resp){
            // 		// echo "Email sent ";
            // 	}else{
            // 		echo "Email not sent ";
            // 	}
            // echo $message;
            $emaail_resp = $dbobject->send_mail_online($email, $subject, $message);

            //}
        }
        $dbobject->logs('returned  : '.$label."=> ".$str);

        return $label;
    }//recoverpassword
    public  function recoverpassword($email)
    {   $label = 0;
         $dbobject = new dbobject();
         $linkCode = $dbobject->generatePIN(64, 1);
       $query = "select * from applicant_account_setup where email='$email' and program ='101'";
        $result = mysql_query($query);
        $numrows = mysql_affected_rows();
        if ($numrows > 0) {
            $row = mysql_fetch_array($result);
            //recoveryid, email, recovery_code, date_created, posteduser, status, used_status
            $query2 = "select * from password_recovery where email='$email' AND status ='0'";
        $result2 = mysql_query($query2);
        $numrows2 = mysql_num_rows($result2);
       // file_put_contents("num.txt",  $numrows2." = >". $query2);

        if($numrows2 > 0){
            $label = 1;
             $queryupdate = "update password_recovery set status ='1' where email='$email' ";
        mysql_query($queryupdate);
       }
            $year = @date('Y');
            $recoverid = $dbobject->paddZeros($dbobject->getnextid('recoverpassword'.$year), 6);
            $query2m = "insert into  password_recovery(recoveryid, email, recovery_code, date_created, posteduser, status, used_status) values('$recoverid','$email','$linkCode',now(),'$email','0','0')";

        $resultsend = mysql_query($query2m);
       // file_put_contents("ressss.txt",  $resultsend);
        if( $resultsend > 0){
            $othernaame = $row ['surname'];
            $password =  "-";

            $message = $dbobject->password_recover_email_template($othernaame, $email, $password, $linkCode);
            $subject = 'SONM Password Recovery';
            //file_put_contents("recover.txt", $message);
            // $emaail_resp =   $dbobject->sendMail_global($email, $subject, $message);
            //  if($emaail_resp){
            //      // echo "Email sent ";
            //  }else{
            //      echo "Email not sent ";
            //  }
            // echo $message;
            $emaail_resp = $dbobject->sendMail_new($email, $subject, $message);
           // file_put_contents("mmmm.txt",  $emaail_resp);
            $label = 22;

        }




        }else{
            $label = 0;
        }
        return $label;
    }//updateexamcenter
 public function updateexamcenter($center){
        $reg_id = $_SESSION['reg_id'];

        $query = "select * from applicant_account_setup where reg_id='$reg_id' and program ='101'";
        $result = mysql_query($query);
        $numrows = mysql_affected_rows();
        if ($numrows > 0) {
          //reg_id, session, email, surname, othernaame, phone_number, program, userpassword, created, rrr, reg_status, rrr_status, linkCode, date_of_birth, gender, marital_status, Nationality, state_of_origin, local_Gov_Area, District_word, tribe, religion, postal_address, exam_center, ip, gname, gaddress, statusCode, pay_status, pbirth, passport, fname, rrr_acceptance, rrr_acceptance_date, rrr_acceptance_status, application_lock, educational_status, admissionstatus, date_adm, exam_center_id
            $query2 = "update applicant_account_setup set exam_center_id ='$center' where  reg_id='$reg_id' and program ='101'";
        $resultmain = mysql_query($query2);
           if($resultmain > 0){
            $label = 1;
        }
        }else{
            $label = 2;
        }

        return $label;
    }


    public function resendmain($email){
        $dbobject = new dbobject();
        //$dbobject->logs("entered  : hereeee");
        $label = 0;
        $whatIWant = substr($email, strpos($email, '@') + 1);
        $_SESSION['sonm_mail_log'] = 'http://'.$whatIWant;

        $query = "select * from applicant_account_setup where email='$email' and program ='101'";
        $result = mysql_query($query);
        $numrows = mysql_affected_rows();
        if ($numrows > 0) {
            $row = mysql_fetch_array($result);
            //reg_id, session, email, surname, othernaame, phone_number, program, userpassword, created, rrr, reg_status, rrr_status, linkCode, date_of_birth, gender, marital_status, Nationality, state_of_origin, local_Gov_Area, District_word, tribe, religion, postal_address, exam_center, ip, gname, gaddress, statusCode, pay_status, pbirth, passport, fname, rrr_acceptance, rrr_acceptance_date, rrr_acceptance_status, application_lock, educational_status, admissionstatus, date_adm
            if($row['reg_status']=='0'){
            $dbobject->logs('found  : '.$label);

            $othernaame = $row ['surname'];
            $password =  $row ['userpassword'];
            $linkCode =  $row['linkCode'];
            $message = $dbobject->reg_email_template($othernaame, $email, $password, $linkCode);
            $subject = 'SONM Application';
            // $emaail_resp =   $dbobject->sendMail_global($email, $subject, $message);
            //  if($emaail_resp){
            //      // echo "Email sent ";
            //  }else{
            //      echo "Email not sent ";
            //  }
            // echo $message;
            $emaail_resp = $dbobject->sendMail_new($email, $subject, $message);
            $label = 1;
        }else{
            $label = 2;
        }
        } else{

            $label = 0;
        }

        return $label;
    }


public function getmessage($sub,$sitting,$value,$user){
     $dbobject = new dbobject();
     $resultfirst = $dbobject->getItemLabelArr("results_tb", array("reg_id","siting","subject_name"), array($user,$sitting,$sub), array("result",'year','grade'));
     return $resultfirst[$value];
}

 public function doGetRRR2($code)
    {
        $dbobject = new dbobject();
        $query = "select * from applicant_account_setup where reg_id='$code' and program ='101'";
        //echo $query;  AND rrr_status !='025'
        $this->logs('nursing. ::: '.$query);
        $result = mysql_query($query);
        $numrows = mysql_affected_rows();
        //$this->logs('Adeniyi James A.');
        //reg_id, session, email, surname, othernaame, phone_number, program, userpassword, created, rrr, reg_status, rrr_status, linkCode, date_of_birth, gender, marital_status, Nationality, state_of_origin, local_Gov_Area, District_word, tribe, religion, postal_address, exam_center, ip, gname, gaddress, statusCode, pay_status, pbirth, passport, fname, rrr_acceptance, rrr_acceptance_date, rrr_acceptance_status, application_lock, educational_status
        if ($numrows > 0) {
            $row = mysql_fetch_array($result);
            $rrr_status = $row['rrr_acceptance'];
            if(trim($row['rrr_acceptance']) !=''){
              $rrr = $row['rrr_acceptance'];
            }else{
              $fullname = $row['surname'].' '.$row['fname'];
              $phoneno = $row['phone_number'];
              $program = $row['program'];
              $email = $row['email'];
              $desc = 'Payment for Admission Letter';
              $rrr = $dbobject->getRRRRemita2($fullname, $phoneno, $program, $desc, $email, $code);
            }

            // $str = "UPDATE applicant_account_setup SET rrr = '$rrr', rrr_status = '025' where linkCode='$code'";
            // $result = mysql_query($str); // or die(mysql_error());
            // $label = mysql_affected_rows();
        }

        // else {
        //     $year = @date('Y');
        //     $regId = $dbobject->paddZeros($dbobject->getnextid('ApplyNow'), 6);
        //     $regId = $dbobject->generatePIN(6, 0);
        //     $registrationId = $year.$regId;
        //     @$now = date('Y-m-d H:i:s');
        //     $linkCode = $dbobject->generatePIN(64, 1);
        //     $str = "INSERT INTO applicant_account_setup(session,email,surname,othernaame,program,userpassword,rrr,reg_id,phone_number,created,linkCode)VALUE('$year','$email','$Surname','$othernaame','$Program','$password','$r_r_r','$registrationId','$phoneno','$now','$linkCode')";
        //     $result = mysql_query($str); // or die(mysql_error());
        //     $label = mysql_affected_rows();
        //     //if($label>1){
        //     $message = $dbobject->reg_email_template($othernaame, $email, $password, $linkCode);
        //     $subject = 'SONM Application';

        //     //$emaailResp =
        //     $dbobject->send_mail_online($email, $subject, $message);

        //     //}
        //  }

        return $rrr;
    }
//recoverpasswordmain
    public function recoverpasswordmain($email, $password){
 $dbobject = new dbobject();
 $label = 0;
        $userpass =  $dbobject->encrypt_password($email, $password);
 //reg_id, session, email, surname, othernaame, phone_number, program, userpassword, created, rrr, reg_status, rrr_status, linkCode, date_of_birth, gender, marital_status, Nationality, state_of_origin, local_Gov_Area, District_word, tribe, religion, postal_address, exam_center, ip, gname, gaddress, statusCode, pay_status, pbirth, passport, fname, rrr_acceptance, rrr_acceptance_date, rrr_acceptance_status, application_lock, educational_status, admissionstatus, date_adm
        $query = "update  applicant_account_setup set userpassword ='$userpass' where email ='$email' and program ='101'";

         $result = mysql_query($query);
         if($result > 0){
            $label =1;
         }else{$label =2;}
         return $label;


    }

    public function doGetRRR($code)
    {
        $dbobject = new dbobject();
        $query = "select * from applicant_account_setup where linkCode='$code' and program ='101'";
        //echo $query;  AND rrr_status !='025'
        $this->logs('nursing school. ::: '.$query);
        $result = mysql_query($query);
        $numrows = mysql_affected_rows();
        //$this->logs('Adeniyi James A.');
        if ($numrows > 0) {
            $row = mysql_fetch_array($result);
            $rrr_status = $row['rrr_status'];
            if($rrr_status=='025'){
              $rrr = $row['rrr'];
            }else{
              $fullname = $row['surname'].' '.$row['fname'];
              $phoneno = $row['phone_number'];
              $program = $row['program'];
              $email = $row['email'];
              $desc = 'Payment for the application Form';
              $rrr = $dbobject->getRRRRemita($fullname, $phoneno, $program, $desc, $email, $code);

            }

            // $str = "UPDATE applicant_account_setup SET rrr = '$rrr', rrr_status = '025' where linkCode='$code'";
            // $result = mysql_query($str); // or die(mysql_error());
            // $label = mysql_affected_rows();
        }

        // else {
        //     $year = @date('Y');
        //     $regId = $dbobject->paddZeros($dbobject->getnextid('ApplyNow'), 6);
        //     $regId = $dbobject->generatePIN(6, 0);
        //     $registrationId = $year.$regId;
        //     @$now = date('Y-m-d H:i:s');
        //     $linkCode = $dbobject->generatePIN(64, 1);
        //     $str = "INSERT INTO applicant_account_setup(session,email,surname,othernaame,program,userpassword,rrr,reg_id,phone_number,created,linkCode)VALUE('$year','$email','$Surname','$othernaame','$Program','$password','$r_r_r','$registrationId','$phoneno','$now','$linkCode')";
        //     $result = mysql_query($str); // or die(mysql_error());
        //     $label = mysql_affected_rows();
        //     //if($label>1){
        //     $message = $dbobject->reg_email_template($othernaame, $email, $password, $linkCode);
        //     $subject = 'SONM Application';

        //     //$emaailResp =
        //     $dbobject->send_mail_online($email, $subject, $message);

        //     //}
        //  }

        return $rrr;
    }

    // public function doConfirmPayment($rrr)
    // {
    //     $dbobject = new dbobject();
    //     $merchantid = MERCHANTID;

    //     $api_key = APIKEY;
    //     $strToken = $rrr.$api_key.$merchantid;
    //     $remitaToken = hash('sha512', $strToken);
    //     $cu = curl_init();
    //     $url = BASEDURL.'/remita/ecomm/'.$merchantid.'/'.$rrr.'/'.$remitaToken.'/status.reg';
    //     //echo $url;
    //     curl_setopt($cu, CURLOPT_URL, $url);
    //     curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);
    //     //curl_setopt($cu, CURLOPT_CUSTOMREQUEST, "POST");
    //     curl_setopt($cu, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($cu, CURLOPT_SSL_VERIFYHOST, 0);
    //     //curl_setopt($cu, CURLOPT_POST, true);
    //     $server_output = curl_exec($cu);

    //     curl_close($cu);

    //     $result = json_decode($server_output);
    //     $amount = $result->amount;
    //     $RRR = $result->RRR;
    //     $orderId = $result->orderId;
    //     $message = $result->message;
    //     $transactiontime = $result->transactiontime;
    //     $status = $result->status;

    //     //echo "rrr :: ".$RRR ;
    //     // print_r($result);
    //     file_put_contents('doConfirmPayment.txt', 'amount : '.$amount.' RRR : '.$RRR.'orderId : '.$orderId.' message : '.$message.' transactiontime : '.$transactiontime.'status : '.$status);

    //     return $result;
    // }

    public function pickStation($opt)
    {
        $filter = '';
         $options = '';
        $query = 'select distinct prog_id, program_name from app_programme_setup_tb where 1=1 and status=1';

        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                if ($opt == $row['prog_id']) {
                    $filter = 'selected';
                }
                $options = $options."<option value='$row[prog_id]' $filter >$row[program_name]</option>";
                $filter = '';
            }
        }

        return $options;
    }

    public function loadCountry($opt)
    {
        $filter = '';
        $options = "<option value=''>::: Select a Country ::: </option>";

        $query = 'select distinct id, name from countries where 1=1';
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                //160
                if ($opt == $row['id']) {
                    $filter = 'selected';
                }
               /* if ($row['id'] =='160') {
                    $filter = 'selected';
                }*/
                $options = $options."<option value='$row[id]' $filter >$row[name]</option>";
                $filter = '';
            }
        }

        return $options;
    }

    public function loadSubjectGrade($opt)
    {
        $filter = '';
        $options = "<option value=''>::: Select a Grade ::: </option>";

        $query = 'select distinct gid, grade_name from subject_grade_tb where 1=1';
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                if ($opt == $row['gid']) {
                    $filter = 'selected';
                }
                $options = $options."<option value='$row[gid]' $filter >$row[grade_name]</option>";
                $filter = '';
            }
        }

        return $options;
    }

    public function loadSubject($options)
    {
        $query = 'select distinct sid, subject_name,category from subject_name_tb where 1=1';

        $options = "<option value=''>::: Select a Grade ::: </option>";
        $result = mysql_query($query);
        // $numrows = mysql_num_rows($result);
        $row = array();
        // if ($numrows > 0) {
        //     for ($i = 0; $i < $numrows; ++$i) {
        //         $res = mysql_fetch_array($result);
        //         $row[$row['gid']] =$row['subject_name'] ;
        //     }
        // }

        while ($res = mysql_fetch_array($result)) {
            $row[$res['sid']] = $res['subject_name'];
        }
        // echo $row;

        return $row;
    }
	public function doPassport($regid,$pass){

	$queryUpdate = "UPDATE applicant_account_setup SET passport='$pass' where  reg_id ='$regid'  and program ='101'";
            $result1 = mysql_query($queryUpdate);
	}//
    public function doVerifyMailCode($code)
    {//AND reg_status='00'
    $status = "";
        $query = "select * from applicant_account_setup where 1=1 AND linkCode='$code' and program ='101' ";
        $result = mysql_query($query);
        while ($res = mysql_fetch_array($result)) {
            $_SESSION['reg_id'] = $res['reg_id'];
            //echo $res['email'].' email';
            $status = $res['reg_status'];
            $_SESSION['email'] = $res['email'];
        }
        $numrows = mysql_num_rows($result);
        if($status == '00'){
        if ($numrows > 0  ) {
            $queryUpdate = "UPDATE applicant_account_setup SET reg_status='11' where 1=1 AND linkCode='$code' and program ='101'";
            $result1 = mysql_query($queryUpdate);
            $res = '1';
        } }else if($status == '11'){
            $res = '2';
        }else{
           $res = '0' ;
        }

        return $res;
    }


    public function dopasswordCode($code)
    {//AND reg_status='00'
    $dbobject = new dbobject();
    $date = date('Y-m-d H:i:s');
    $status = "";
    $label = 0;
    //recoveryid, email, recovery_code, date_created, posteduser, status, used_status
        $query = "select * from password_recovery where 1=1 AND recovery_code ='$code' and status = '0' ";
       // echo $query ;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        $res = mysql_fetch_array($result); echo $numrows;
        //var_dump($res);
        if($numrows > 0){
            $codetime = $res ['date_created'];
            //$since_start = $date->diff($codetime);
            $since_start = (time() - strtotime($codetime) ) / 60;
             //$since_start = (strtotime("2012-09-21 12:12:22") - time()) / 60;

           // file_put_contents("diff.txt", (int)$since_start);
            if(((int)$since_start)<=5){
                 $querystatus = "update password_recovery set status ='1' where recovery_code ='$code'";
        $resultm = mysql_query($querystatus);
        if( $resultm > 0){
            $label = 22;
            }else{
                $label = 11;
            }
        }

        }else{
            $label = 0;
        }



        return $label;
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//End Class
