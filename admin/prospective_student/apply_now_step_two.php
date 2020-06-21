<?php error_reporting(1);

    define('AJAX_REQUEST', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    if (!AJAX_REQUEST) {
        die();
    }

        session_start();
include '../../lib/dbfunctions_extra_jam.php';
$dbobject = new myDbObject();

$school_name_address_1="";
  $certificate_obtained_1 = "";
   $school_date_1 = "";
   $school_name_address_2 = "";
   $certificate_obtained_2 = "";
    $school_date_2 = "";
     $school_name_address_3 = "";
      $certificate_obtained_3 = "";
       $school_date_3 = "";
       $program_code = "";
     if (isset($_SESSION['reg_id'])) {
         $reg_id = $_SESSION['reg_id'];
$program_code = $dbobject->getitemlabel('app_applicant_account_setup', 'reg_id', $reg_id, 'program');
         $result2 = $dbobject->getrecordset('app_educational_qualification_tb', 'reg_id', $reg_id);
         $numrows = mysql_num_rows($result2);
         if ($numrows > 0) {
             $row = mysql_fetch_array($result2);
             //filter_var($dirtyString, FILTER_SANITIZE_STRING);
            //htmlentities( $discipline, ENT_SUBSTITUTE );
              $school_name_address_1 = str_replace("%20"," ", $row['pri_school_name']);
             $certificate_obtained_1 = str_replace("%20"," ", $row['pri_certificate_obtained']);
             $school_date_1 =str_replace("%20"," ",  $row['pri_end_date']);

             //$row = mysql_fetch_array($result);
             $school_name_address_2 = str_replace("%20"," ", $row['jun_school_name']);
             $certificate_obtained_2 = str_replace("%20"," ", $row['jun_certificate_obtained']);
             $school_date_2 = str_replace("%20"," ", $row['jun_end_date']);

             // $row = mysql_fetch_array($result);
             $school_name_address_3 = str_replace("%20"," ", $row['sec_school_name']);
             $certificate_obtained_3 = str_replace("%20"," ", $row['sec_certificate_obtained']);
             $school_date_3 = str_replace("%20"," ", $row['sec_end_date']);
         }
         $Program = $program_code;
     } else {
         //     include 'logout.php';
     }
     $station = "";
     $gid = "";
    $program_sel = $dbobject->pickStation($station);

    $load_grade = $dbobject->loadSubjectGrade($gid);

   $load_subject = $dbobject->loadSubject($gid);
   $result2 = $dbobject->getrecordset('app_results_tb', 'reg_id', $reg_id);
$arry =array();
   while( $numrows = mysql_fetch_array($result2)){



}//sid, reg_id, subject_name, result, grade, year, remarks, created, siting
 $stateexam = $dbobject->getItemLabelArr("app_results_tb", array("reg_id","siting"), array($reg_id,"1"), array("result",'year'));
 $state = $dbobject->getItemLabelArr("app_results_tb", array("reg_id","siting"), array($reg_id,"2"), array("result",'year'));

//sid, reg_id, subject_name, result, grade, year, remarks, created, siting, upload_num

 //$resultfirst = $dbobject->getItemLabelArr("results_tb", array("reg_id","siting","subject_name"), array($reg_id,'1','English'), array("result",'year','grade'));
 //var_dump($resultfirst);
  $dbobject->getmessage("English","1","grade",$reg_id);
  $sub =array();
   $sub2 =array();
  $count1 = 0;
  //sid, reg_id, subject_name, result, grade, year, remarks, created, siting, position
   $sql_files ="SELECT * FROM  app_results_tb where reg_id ='$reg_id' and subject_name !='English' and subject_name !='Mathematics' and subject_name !='Biology' and subject_name !='Chemistry' and subject_name !='Physics' and siting = '1' order by  position ASC  ";//get files uploaded
   //gid, grade_name
         $result_files = mysql_query($sql_files);
         while ($row= mysql_fetch_array( $result_files)) {

            $sub[]  = array('db' => $dbobject->getitemlabel('app_subject_name_tb', 'sid',$row['subject_name'],'subject_name'),'sub'=> $row['subject_name'], 'dt' => $row['grade'],'gd'=>$dbobject->getitemlabel('subject_grade_tb', 'gid',$row['grade'],'grade_name'));
         }


         $sql_files2 ="SELECT * FROM  app_results_tb where reg_id ='$reg_id' and subject_name !='English' and subject_name !='Mathematics' and subject_name !='Biology' and subject_name !='Chemistry' and subject_name !='Physics' and siting = '2'  order by  position ASC    ";//get files uploaded
   //gid, grade_name
         $result_files2 = mysql_query($sql_files2);
         while ($row= mysql_fetch_array( $result_files2)) {

            $sub2[]  = array('db' => $dbobject->getitemlabel('app_subject_name_tb', 'sid',$row['subject_name'],'subject_name'),'sub'=> $row['subject_name'], 'dt' => $row['grade'],'gd'=>$dbobject->getitemlabel('subject_grade_tb', 'gid',$row['grade'],'grade_name'));
         }


?>

<style>
.circle {
    width: 50px;
    height: 50px;
    background: green;
    -moz-border-radius: 50px;
    -webkit-border-radius: 50px;
    border-radius: 50px;
	text-align:center;

}
</style>


<div class="card">
    <div class="card-header">

        <div class="row">
            <div class="col-xs-12 col-sm-4 custom_left">
                <h5 class="card-title mb-1"><p>Applicant Education Background</p></h5>
            </div>
        </div>
        <div class="row">
            <div>Please Ensure that your Educational information are correctly entered</div>

        </div>
        <h6>All fields marked <font color="red">*</font> are required</h6>

    </div>
    <br>

             <div class="contact_form">
            <form name="form3" id="form3" onsubmit="return false">
                                     <input type="hidden" name="email" id="email" value="<?php echo $email; ?>" readonly="true"/>
                                    <input type="hidden" name="aid" id="aid" value="<?php echo $reg_id; ?>" readonly="true"/>
                                        <h4>EDUCATIONAL QUALIFICATION(S)</h4>
                                        <hr/>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-4">
                                                <div class="form-group">
                                                    <label>Primary School Name<span class="error">*</span></label>
                                                    <input type="text" class="form-control required-text" name="pri_school_name" id="pri_school_name" value="<?php echo $school_name_address_1; ?>"/>
                                                </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-4">
                                                    <div class="form-group">
                                                        <label>Certificate Obtained<span class="error">*</span></label>
                                                        <input name="pri_result"  class="form-control required-text" id="pri_result" value="Primary School Certificate" readonly="" >

                                                    </div>
                                                </div>


                        <div class="col-xs-12 col-sm-4">
                            <div class="form-group">
                                <label>End Date <span class="error">*</span></label>
                               <select name="prim_end_year" id="prim_end_year" class="form-control required-text">
                                <?php if($school_date_1!=""){  ?>
<option value="<?php echo $school_date_1; ?>"><?php echo $school_date_1; ?></option>
                                    <?php  }?>
                            <option value="#">SELECT END YEAR</option>
                            <?php
                            $year=date("Y");
                                for ($x = $year; $x >= 1980; --$x) {
                                    ?>

                                    <option value="<?php echo $x; ?>"><?php echo $x; ?></option>


                                <?php
                                }
                                ?>

                        </select>
                            </div>
                        </div>

                    </div>


                                        <div class="row">
                                                <div class="col-xs-12 col-sm-4">
                                                <div class="form-group">
                                                    <label>Senior School Name <span class="error">*</span></label>
                                                    <input name="jun_school_name" type="text" class="form-control required-text" id="jun_school_name" value="<?php echo $school_name_address_2; ?>" placeholder="Senior Secondary School"/>
                                                </div>
                                                </div>

                                            <div class="col-xs-12 col-sm-4">
                                                <div class="form-group">
                                                    <label>Certificate Obtained<span class="error">*</span></label>
                                                    <input class="form-control " name="jun_result" id="jun_result" value="Senior School Certificate" readonly="" >

                                                </div>
                                            </div>

                        <div class="col-xs-12 col-sm-4">
                            <div class="form-group">
                                <label>End Date <span class="error">*</span></label>
                               <select name="jun_end_year" id="jun_end_year" class="form-control required-text">
                                 <?php if($school_date_2!=""){  ?>
<option value="<?php echo $school_date_2; ?>" selected><?php echo $school_date_2; ?></option>
                                    <?php  }?>
                            <option value="#">SELECT END YEAR</option>
                            <?php
                            $year=date("Y");
                                for ($x = $year; $x >= 1980; --$x) {
                                    ?>

                                    <option value="<?php echo $x; ?>"><?php echo $x; ?></option>


                                <?php
                                }
                                ?>

                        </select>
                            </div>
                        </div>

                                        </div>


                                        <div class="row">
                                                <div class="col-xs-12 col-sm-4">
                                                <div class="form-group">
                                                    <label>Other Senior School Name </label>
                                                    <input name="sec_school_name" type="text" class="form-control" id="sec_school_name" value="<?php echo $school_name_address_3; ?>"/>
                                                </div>
                                                </div>

                                            <div class="col-xs-12 col-sm-4">
                                                <div class="form-group">
                                                    <label>Certificate Obtained</label>
                                                    <input class="form-control " name="sec_result" id="sec_result" value="Senior School Certificate"  readonly="">

                                                </div>
                                            </div>

                        <div class="col-xs-12 col-sm-4">
                            <div class="form-group">
                                <label>End Date </label>
                               <select name="sec_end_year" id="sec_end_year" class="form-control ">
                                  <?php if($school_date_3!=""){  ?>
<option value="<?php echo $school_date_3; ?>" selected><?php echo $school_date_3; ?></option>
                                    <?php  }?>
                            <option value="#">SELECT END YEAR</option>
                            <?php
                            $year=date("Y");
                                for ($x = $year; $x >= 1980; --$x) {
                                    ?>

                                    <option value="<?php echo $x; ?>"><?php echo $x; ?></option>


                                <?php
                                }
                                ?>

                        </select>
                            </div>
                        </div>

                                        </div>


                                        <div class="row">
                                            <div class="col-xs-12 col-sm-2">


                                            </div>
                                        </div>


                                        <div id="subject11">
                                        <hr/>
                                        <div class="row">
                                                <div class="col-xs-12 col-sm-2">

                                            <h4>SUBJECT(S)</h4>
                                                </div>


                                                <div class="col-xs-12 col-sm-4">
                                        <select name="exam_type" id="exam_type" class="form-control required-text">
                                            <option value="">SELECT EXAM TYPE</option>
                                            <?php if($stateexam['result']!="") {?>
                                                <option value="<?php echo $stateexam['result'] ?>" selected><?php echo $stateexam['result']; ?></option>
                                            <?php } ?>
                                            <option value="WAEC">WAEC</option>
                                            <option value="NECO">NECO</option>


                                        </select>
                                                </div>


                                                <div class="col-xs-12 col-sm-4">
                                                <select name="exam_year" id="exam_year" class="form-control required-text">
                                                    <option value="">SELECT EXAM YEAR</option>
                                                     <?php if($stateexam['year']!="") {?>
                                                <option value="<?php echo $stateexam['year'] ?>" selected><?php echo $stateexam['year']; ?></option>
                                            <?php } ?>
                                                    <?php
                                                        for ($x = 2019; $x >= 1980; --$x) {
                                                            ?>

                                                            <option value="<?php echo $x; ?>"><?php echo $x; ?></option>


                                                        <?php
                                                        }
                                                        ?>

                                                </select>
                                                </div>
                                                </div>


                                        <hr/>

                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>English <span class="error">*</span></label>

                                                        <input type="text" class="form-control required-text" name="English" id="English" value="English" readonly/>

                                                        </div>
                                                    </div>


                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Grade <span class="error">*</span></label>
                                                        <select class="form-control required-text" name="GradeEnglish" id="GradeEnglish">
                                                            <?php if($dbobject->getmessage("English","1","grade",$reg_id)){  ?>

                                                                <option value="<?php echo $dbobject-> getmessage("English","1","grade",$reg_id);  ?>"><?php echo
                                                                    $dbobject->getitemlabel('subject_grade_tb', 'gid',
  $dbobject->getmessage("English","1","grade",$reg_id),'grade_name');  ?></option>

                                                            <?php  } ?>
                                                            <?php echo $load_grade; ?>
                                                        </select>
                                                         </div>
                                                </div>


                                            </div>
                                            <!--end row-->


                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Mathematics <span class="error">*</span></label>
                                                        <input type="text" class="form-control required-text" name="Mathematics" id="Mathematics" value="Mathematics" readonly/>


                                                              </div>
                                                </div>


                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                    <label>Grade <span class="error">*</span></label>
                                                        <select class="form-control required-text " name="GradeMathematics" id="GradeMathematics">
                                                             <?php if($dbobject->getmessage("Mathematics","1","grade",$reg_id)){  ?>

                                                                <option value="<?php echo $dbobject-> getmessage("Mathematics","1","grade",$reg_id);  ?>"><?php echo
                                                                    $dbobject->getitemlabel('subject_grade_tb', 'gid',
  $dbobject->getmessage("Mathematics","1","grade",$reg_id),'grade_name');  ?></option>

                                                            <?php  } ?>
                                                            <?php echo $load_grade; ?>
                                                        </select>
                                                           </div>
                                                </div>


                                            </div>
                                            <!--end row-->

                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">

                                                            <label>Biology <span class="error">*</span></label>
                                                            <input type="text"  class="form-control" name="subject3" id="subject3" value="Biology" readonly="">


                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Grade <span class="error">*</span></label>
                                                        <select class="form-control required-text" name="GradeSubject3" id="GradeSubject3">
                                                            <?php if($dbobject->getmessage("Biology","1","grade",$reg_id)){  ?>

                                                                <option value="<?php echo $dbobject-> getmessage("Biology","1","grade",$reg_id);  ?>"><?php echo
                                                                    $dbobject->getitemlabel('subject_grade_tb', 'gid',
  $dbobject->getmessage("Biology","1","grade",$reg_id),'grade_name');  ?></option>

                                                            <?php  } ?>
                                                            <?php echo $load_grade; ?>
                                                        </select>

                                                    </div>
                                                </div>

                                            </div>
                                            <!--end row-->


                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                            <label>Chemistry<span class="error">*</span></label>
                                                            <input type="text" class="form-control" name="subject4" id="subject4" value="Chemistry" readonly="">



                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Grade <span class="error">*</span></label>
                                                        <select class="form-control required-text" name="GradeSubject4" id="GradeSubject4">
                                                             <?php if($dbobject->getmessage("Chemistry","1","grade",$reg_id)){  ?>

                                                                <option value="<?php echo $dbobject-> getmessage("Chemistry","1","grade",$reg_id);  ?>"><?php echo
                                                                    $dbobject->getitemlabel('subject_grade_tb', 'gid',
  $dbobject->getmessage("Chemistry","1","grade",$reg_id),'grade_name');  ?></option>

                                                            <?php  } ?>
                                                            <?php echo $load_grade; ?>
                                                        </select>

                                                    </div>
                                                </div>

                                            </div>
                                            <!--end row-->



                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                            <label>Physics <span class="error">*</span></label>
                                                            <input type="Physics" class="form-control" name="subject5" id="subject5" value="Physics" readonly="">



                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Grade <span class="error">*</span></label>
                                                        <select class="form-control required-text" name="GradeSubject5" id="GradeSubject5">
                                                             <?php if($dbobject->getmessage("Physics","1","grade",$reg_id)){  ?>

                                                                <option value="<?php echo $dbobject-> getmessage("Physics","1","grade",$reg_id);  ?>"><?php echo
                                                                    $dbobject->getitemlabel('subject_grade_tb', 'gid',
  $dbobject->getmessage("Physics","1","grade",$reg_id),'grade_name');  ?></option>

                                                            <?php  } ?>
                                                            <?php echo $load_grade; ?>
                                                        </select>

                                                    </div>
                                                </div>

                                            </div>
                                            <!--end row-->



                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                            <label>Subject 6 <span class="error">*</span></label>
                                                            <select class="form-control" name="subject6" id="subject6">
                                                              <option value='#'>::: Select a Subject ::: </option>
                                                              <?php if($sub[0]['db']!=""){ ?>
                                                                <option value="<?php echo $sub[0]['sub'];  ?>" selected><?php echo $sub[0]['db']; ?></option>
                                                            <?php } ?>
                                                                <?php foreach ($load_subject as $key => $value) {
                                                            ?>
                                                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                                <?php
                                                        }?>
                                                            </select>


                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Grade <span class="error">*</span></label>
                                                        <select class="form-control required-text" name="GradeSubject6" id="GradeSubject6">
                                                            <?php if($sub[0]['gd']!=""){ ?>
                                                                <option value="<?php echo $sub[0]['dt'];  ?>" selected><?php echo $sub[0]['gd']; ?></option>
                                                            <?php } ?>
                                                            <?php echo $load_grade; ?>
                                                        </select>

                                                    </div>
                                                </div>

                                            </div>
                                            <!--end row-->


                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                            <label>Subject 7 <span class="error">*</span></label>
                                                            <select class="form-control" name="subject7" id="subject7">
                                                              <option value='#'>::: Select a Subject ::: </option>
                                                              <?php if($sub[1]['db']!=""){ ?>
                                                                <option value="<?php echo $sub[1]['sub'];  ?>" selected><?php echo $sub[1]['db']; ?></option>
                                                            <?php } ?>
                                                                <?php foreach ($load_subject as $key => $value) {
                                                            ?>
                                                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                                <?php
                                                        }?>
                                                            </select>


                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Grade <span class="error">*</span></label>
                                                        <select class="form-control required-text" name="GradeSubject7" id="GradeSubject7">
                                                             <?php if($sub[1]['gd']!=""){ ?>
                                                                <option value="<?php echo $sub[1]['dt'];  ?>" selected><?php echo $sub[1]['gd']; ?></option>
                                                            <?php } ?>
                                                            <?php echo $load_grade; ?>
                                                        </select>

                                                    </div>
                                                </div>

                                            </div>
                                            <!--end row-->


                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                            <label>Subject 8 <span class="error">*</span></label>
                                                             <select class="form-control" name="subject8" id="subject8">
                                                             <option value='#'>::: Select a Subject ::: </option>
                                                             <?php if($sub[2]['db']!=""){ ?>
                                                                <option value="<?php echo $sub[2]['sub'];  ?>" selected><?php echo $sub[2]['db']; ?></option>
                                                            <?php } ?>
                                                               <?php foreach ($load_subject as $key => $value) {
                                                            ?>
                                                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                                <?php
                                                        }?>
                                                            </select>


                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Grade <span class="error">*</span></label>
                                                        <select class="form-control required-text " name="GradeSubject8" id="GradeSubject8">
                                                             <?php if($sub[2]['gd']!=""){ ?>
                                                                <option value="<?php echo $sub[2]['dt'];  ?>" selected><?php echo $sub[2]['gd']; ?></option>
                                                            <?php } ?>
                                                            <?php echo $load_grade; ?>
                                                        </select>

                                                    </div>
                                                </div>

                                            </div>
                                            <!--end row-->


                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                            <label>Subject 9</label>
                                                            <select class="form-control" name="subject9" id="subject9">
                                                            <option value='#'>::: Select a Subject ::: </option>
                                                            <?php if($sub[3]['db']!=""){ ?>
                                                                <option value="<?php echo $sub[3]['sub'];  ?>" selected><?php echo $sub[3]['db']; ?></option>
                                                            <?php } ?>
                                                              <?php foreach ($load_subject as $key => $value) {
                                                            ?>
                                                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                                <?php
                                                        }?>
                                                            </select>


                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Grade </label>
                                                        <select class="form-control" name="GradeSubject9" id="GradeSubject9">
                                                             <?php if($sub[3]['gd']!=""){ ?>
                                                                <option value="<?php echo $sub[3]['dt'];  ?>" selected><?php echo $sub[3]['gd']; ?></option>
                                                            <?php } ?>
                                                            <?php echo $load_grade; ?>
                                                        </select>

                                                    </div>
                                                </div>

                                            </div>
                                            <!--end row-->

</div>



<div class="row">
    <input type="checkbox" name="second" id="second" value="off"  onclick="getaction()"><strong><font color="blue">Add Second Sitting</font></strong>
<input type="hidden" name="passed" id="passed" value="off" >
</div>

 <div id="subject2" class="d-none">
    <hr/>

                                            <div class="row">
                                                <div class="col-xs-12 col-sm-4">

                                            <h4>SUBJECT(S) Second Sitting</h4>
                                                </div>


                                                <div class="col-xs-12 col-sm-4">
                                                <select id="result2" name="result2" class="form-control">
                                                    <option value="#">SELECT EXAM TYPE</option>
                                                    <?php if($state['result']!=""){  ?>
                                                        <option value="<?php echo $state['result'] ?>" selected><?php echo $state['result'] ?></option>
                                                    <?php } ?>
                                                    <option value="WAEC">WAEC</option>
                                                    <option value="NECO">NECO</option>


                                                </select>
                                                </div>


                                                <div class="col-xs-12 col-sm-4">
                                                <select name="exam_year2" id="exam_year2" class="form-control ">
                                                    <?php if($state['year']!=""){  ?>
                                                        <option value="<?php echo $state['year'] ?>" selected><?php echo $state['year'] ?></option>
                                                    <?php } ?>
                                                    <option value="#">SELECT EXAM YEAR</option>
                                                    <?php
                                                        for ($x = 2019; $x >= 1980; --$x) {
                                                            ?>

                                                            <option value="<?php echo $x; ?>"><?php echo $x; ?></option>


                                                        <?php
                                                        }
                                                        ?>

                                                </select>
                                                </div>
                                                </div>


                                        <hr/>


                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>English </label>
                                                                    <input type="text" class="form-control" id="sub111" name="sub111" readonly value="English">

                                                        </div>
                                                    </div>


                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Grade</label>
                                                        <select class="form-control " name="gr111" id="gr111">
                                                             <?php if($dbobject->getmessage("English","2","grade",$reg_id)){  ?>

                                                                <option value="<?php echo $dbobject-> getmessage("English","2","grade",$reg_id);  ?>"><?php echo
                                                                    $dbobject->getitemlabel('subject_grade_tb', 'gid',
  $dbobject->getmessage("English","2","grade",$reg_id),'grade_name');  ?></option>

                                                            <?php  } ?>
                                                            <?php echo $load_grade; ?>
                                                        </select>
                                                         </div>
                                                </div>


                                            </div>
                                            <!--end row-->


                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Mathematics</label>
                                                        <input type="text" class="form-control" id="sub112" name="sub112" readonly value="Mathematics"/>

                                                    </div>
                                                </div>


                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                    <label>Grade </label>
                                                        <select class="form-control " name="gr112" id="gr112">
                                                            <?php if($dbobject->getmessage("Mathematics","2","grade",$reg_id)){  ?>

                                                                <option value="<?php echo $dbobject-> getmessage("Mathematics","2","grade",$reg_id);  ?>"><?php echo
                                                                    $dbobject->getitemlabel('subject_grade_tb', 'gid',
  $dbobject->getmessage("Mathematics","2","grade",$reg_id),'grade_name');  ?></option>

                                                            <?php  } ?>
                                                            <?php echo $load_grade; ?>
                                                        </select>
                                                           </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-2">

                                                <label> <span class="error"></span></label>

                                                </div>

                                            </div>

  <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">

                                                            <label>Biology </label>
                                                            <input type="text"  class="form-control" name="sub113" id="sub113" value="Biology" readonly="">


                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Grade </label>
                                                        <select class="form-control " name="gr113" id="gr113">
                                                             <?php if($dbobject->getmessage("Biology","2","grade",$reg_id)){  ?>

                                                                <option value="<?php echo $dbobject-> getmessage("Biology","2","grade",$reg_id);  ?>"><?php echo
                                                                    $dbobject->getitemlabel('subject_grade_tb', 'gid',
  $dbobject->getmessage("Biology","2","grade",$reg_id),'grade_name');  ?></option>

                                                            <?php  } ?>
                                                            <?php echo $load_grade; ?>
                                                        </select>

                                                    </div>
                                                </div>

                                            </div>
                                            <!--end row-->


                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                            <label>Chemistry</label>
                                                            <input type="text" class="form-control" name="sub114" id="sub114" value="Chemistry" readonly="">



                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Grade </label>
                                                        <select class="form-control " name="gr114" id="gr114">
                                                            <?php if($dbobject->getmessage("Chemistry","2","grade",$reg_id)){  ?>

                                                                <option value="<?php echo $dbobject-> getmessage("Chemistry","2","grade",$reg_id);  ?>"><?php echo
                                                                    $dbobject->getitemlabel('subject_grade_tb', 'gid',
  $dbobject->getmessage("Chemistry","2","grade",$reg_id),'grade_name');  ?></option>

                                                            <?php  } ?>
                                                            <?php echo $load_grade; ?>
                                                        </select>

                                                    </div>
                                                </div>

                                            </div>
                                            <!--end row-->



                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                            <label>Physics </label>
                                                            <input type="Physics" class="form-control" name="sub115" id="sub115" value="Physics" readonly="">



                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Grade </label>
                                                        <select class="form-control " name="gr115" id="gr115">
                                                            <?php if($dbobject->getmessage("Physics","2","grade",$reg_id)){  ?>

                                                                <option value="<?php echo $dbobject-> getmessage("Physics","2","grade",$reg_id);  ?>"><?php echo
                                                                    $dbobject->getitemlabel('subject_grade_tb', 'gid',
  $dbobject->getmessage("Physics","2","grade",$reg_id),'grade_name');  ?></option>

                                                            <?php  } ?>
                                                            <?php echo $load_grade; ?>
                                                        </select>

                                                    </div>
                                                </div>

                                            </div>
                                            <!--end row-->



                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                            <label>Subject 6 </label>
                                                            <select class="form-control" name="sub116" id="sub116">
                                                              <option value='#'>::: Select a Subject ::: </option>
                                                                <?php if($sub2[0]['db']!=""){ ?>
                                                                <option value="<?php echo $sub2[0]['sub'];  ?>" selected><?php echo $sub2[0]['db']; ?></option>
                                                            <?php } ?>
                                                                <?php foreach ($load_subject as $key => $value) {
                                                            ?>
                                                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                                <?php
                                                        }?>
                                                            </select>


                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Grade </label>
                                                        <select class="form-control " name="gr116" id="gr116">
                                                            <?php if($sub2[0]['gd']!=""){ ?>
                                                                <option value="<?php echo $sub2[0]['dt'];  ?>" selected><?php echo $sub2[0]['gd']; ?></option>
                                                            <?php } ?>
                                                            <?php echo $load_grade; ?>
                                                        </select>

                                                    </div>
                                                </div>

                                            </div>
                                            <!--end row-->


                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                            <label>Subject 7 </label>
                                                            <select class="form-control" name="sub117" id="sub117">
                                                              <option value='#'>::: Select a Subject ::: </option>
                                                                <?php if($sub2[1]['db']!=""){ ?>
                                                                <option value="<?php echo $sub2[1]['sub'];  ?>" selected><?php echo $sub2[1]['db']; ?></option>
                                                            <?php } ?>
                                                                <?php foreach ($load_subject as $key => $value) {
                                                            ?>
                                                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                                <?php
                                                        }?>
                                                            </select>


                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Grade </label>
                                                        <select class="form-control " name="gr117" id="gr117">
                                                            <?php if($sub2[1]['gd']!=""){ ?>
                                                                <option value="<?php echo $sub2[1]['dt'];  ?>" selected><?php echo $sub2[1]['gd']; ?></option>
                                                            <?php } ?>
                                                            <?php echo $load_grade; ?>
                                                        </select>

                                                    </div>
                                                </div>

                                            </div>
                                            <!--end row-->


                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                            <label>Subject 8 </label>
                                                             <select class="form-control" name="sub118" id="sub118">
                                                             <option value='#'>::: Select a Subject ::: </option>
                                                               <?php if($sub2[2]['db']!=""){ ?>
                                                                <option value="<?php echo $sub2[2]['sub'];  ?>" selected><?php echo $sub2[2]['db']; ?></option>
                                                            <?php } ?>
                                                               <?php foreach ($load_subject as $key => $value) {
                                                            ?>
                                                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                                <?php
                                                        }?>
                                                            </select>


                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Grade </label>
                                                        <select class="form-control " name="gr118" id="gr118">
                                                            <?php if($sub2[2]['gd']!=""){ ?>
                                                                <option value="<?php echo $sub2[2]['dt'];  ?>" selected><?php echo $sub2[2]['gd']; ?></option>
                                                            <?php } ?>
                                                            <?php echo $load_grade; ?>
                                                        </select>

                                                    </div>
                                                </div>

                                            </div>
                                            <!--end row-->


                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                            <label>Subject 9 </label>
                                                            <select class="form-control" name="sub119" id="sub119">
                                                            <option value='#'>::: Select a Subject ::: </option>
                                                              <?php if($sub2[3]['db']!=""){ ?>
                                                                <option value="<?php echo $sub2[3]['sub'];  ?>" selected><?php echo $sub2[3]['db']; ?></option>
                                                            <?php } ?>
                                                              <?php foreach ($load_subject as $key => $value) {
                                                            ?>
                                                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                                <?php
                                                        }?>
                                                            </select>


                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Grade </label>
                                                        <select class="form-control " name="gr119" id="gr119">
                                                            <?php if($sub2[3]['gd']!=""){ ?>
                                                                <option value="<?php echo $sub2[3]['dt'];  ?>" selected><?php echo $sub2[3]['gd']; ?></option>
                                                            <?php } ?>
                                                            <?php echo $load_grade; ?>
                                                        </select>

                                                    </div>
                                                </div>

                                            </div>


                                            <!--end row-->
                                    <div id="subj1" name="subj1">

                                    </div>


                                            </div>




                                            <!-- <h4>ADDITIONAL/PROFESSIONAL QUALIFICATION(S)</h4>
                                        <hr/>

                                            <hr/>

                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Name of Parent/Guidian <span class="error">*</span></label>
                                                        <input type="text" class="form-control " name="Gname" id="Gname">
                                                    </div>
                                                </div>


                                            </div>


                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Address <span class="error">*</span></label>
                                                        <input type="text" class="form-control required-text" name="Gaddress" id="Gaddress">
                                                    </div>
                                                </div>


                                            </div>

 -->
                                            <div class="row">
                                                <div id="display_message" name="display_message">

                                                    </div>
                                            </div>
 <div class="row" id="control_btns">
                                                <div class="col"></div>
                                                <div class=" col-sm-2">
                        <button type="button" class="btn btn-lg btn-warning btn-block" name="reset" id="reset" onclick="getpage('apply_now_step_one.php', 'page');">Back</button>
                    </div><div class=" col-sm-2"></div>
                                                 <div class=" col-sm-4" >
                        <button type="button" class="btn btn-lg btn-success btn-block" name="subbtn" id="subbtn"  value="Submit" onclick="javascript:callpagesubmit('apply_now4')">Save And Continue</button>
                    </div>
<div class=" col-sm-2" style="float: right;">
                        <button type="button" class="btn btn-lg btn-info btn-block" name="reset" id="reset" onclick="getpage('apply_now_step_three.php', 'page');">Next</button>
                    </div>

                                        </div>





            </form>
             </div></div>
<?php
function getmessage($sub,$sitting,$value,$user){
     $resultfirst = $dbobject->getItemLabelArr("app_results_tb", array("reg_id","siting","subject_name"), array($user,$sitting,$sub), array("result",'year','grade'));
     return $resultfirst[$value];
}


?>




        <script>
    $(document).ready(function() {

        var iCnt = 0;
        // CREATE A "DIV" ELEMENT AND DESIGN IT USING jQuery ".css()" CLASS.
        var container = $('#subj1');
        // .css({
        //     padding: '5px', margin: '20px', width: '170px', border: '1px dashed',
        //     borderTopColor: '#999', borderBottomColor: '#999',
        //     borderLeftColor: '#999', borderRightColor: '#999'
        // });

        $('#btAdd').click(function() {
            if (iCnt <= 19) {

                iCnt = iCnt + 1;

                // ADD TEXTBOX.
                var ro='<div class="row" id="sub1' + iCnt + '">'+
                                                                '<div class="col-xs-12 col-sm-6">'+
                                                    '<div class="form-group">'+
                                                     '       <label>Biology <span class="error">*</span></label>'+
                                                    '       <select class="form-control" name="subject1' + iCnt + '" id="subject1' + iCnt + '">'+
                                                        '  <option value="#">::: Select a Subject ::: </option>'+
                                                   '                 <option value="">MAthes</option>'+
                                                  '          </select>'+


                                                 '   </div>'+
                                                '</div>'+
                                               '<div class="col-xs-12 col-sm-4">'+
                                                    '<div class="form-group">'+
                                                     '  <label>Grade <span class="error">*</span></label>'+
                                                    '    <select class="form-control " name="gr1" id="gr1' + iCnt + ' ">'+
                                                   '          '+
                                                  '      </select>'+

                                                 '   </div>'+
                                                '</div>'+


                                                '<div class="col-xs-12 col-sm-2">'+
                                                    '<div class="form-group">'+
                                                        '<label> <span class="error"></span></label>'+
                                                        '<input type="button" id="btRemove" value="Remove Subject" class="bt" />'+


                                              '  </div>'+
                                             '</div>'+

                                            '</div>';
                $(container).append(ro);


               // $(container).append('<input type=text class="input" id=tb' + iCnt + ' ' +
                 //   'value="Text Element ' + iCnt + '" />');

                // SHOW SUBMIT BUTTON IF ATLEAST "1" ELEMENT HAS BEEN CREATED.
                if (iCnt == 1) {
                    var divSubmit = $(document.createElement('div'));
                    $(divSubmit).append('<input type=button class="bt"' +
                        'onclick="GetTextValue()"' +
                            'id=btSubmit value=SubmitAde />');
                }

                // ADD BOTH THE DIV ELEMENTS TO THE "main" CONTAINER.
                $('#main').after(container, divSubmit);
            }
            // AFTER REACHING THE SPECIFIED LIMIT, DISABLE THE "ADD" BUTTON.
            // (20 IS THE LIMIT WE HAVE SET)
            else {
                $(container).append('<label>Reached the limit</label>');
                $('#btAdd').attr('class', 'bt-disable');
                $('#btAdd').attr('disabled', 'disabled');
            }
        });

        // REMOVE ONE ELEMENT PER CLICK.
        $('#btRemove').click(function() {

            console.log(iCnt +" :::::::::");
            if (iCnt != 0) {
                $('#sub1' + iCnt).remove();
                iCnt = iCnt - 1;
                }

            if (iCnt == 0) {
                $(container)
                    .empty()
                    .remove();

                $('#btSubmit').remove();
                $('#btAdd')
                    .removeAttr('disabled')
                    .attr('class', 'bt');
            }
        });

        // REMOVE ALL THE ELEMENTS IN THE CONTAINER.
        $('#btRemoveAll').click(function() {
            $(container)
                .empty()
                .remove();

            $('#btSubmit').remove();
            iCnt = 0;

            $('#btAdd')
                .removeAttr('disabled')
                .attr('class', 'bt');
        });
    });

    // PICK THE VALUES FROM EACH TEXTBOX WHEN "SUBMIT" BUTTON IS CLICKED.
    var divValue, values = '';

    function GetTextValue() {
        $(divValue)
            .empty()
            .remove();

        values = '';

        $('.input').each(function() {
            divValue = $(document.createElement('div')).css({
                padding:'5px', width:'200px'
            });
            values += this.value + '<br />'
        });

        $(divValue).append('<p><b>Your selected values</b></p>' + values);
        $('body').append(divValue);
    }
</script>

<script>
    $("#subject2").hide();
    //$second
    $("#second").val('off');



    function getaction(){

var isChecked2 = $('#second').prop('checked');

     //var second = = $('input[namseconde=second]:checked');
     // $("#second").val();


     if(isChecked2 == true ){
 $("#passed").val('hello');
  var cad = $("#passed").val();
 console.log(isChecked2+"= "+cad);
$("#subject2").show();}else{
  $("#passed").val('off');
$("#subject2").hide();
}
    }

$("#add_subject2").on("click", function(event){

$("#subject2").show();

$("#add_subject2").hide();

$("#rem_subject2").show();
});
$("#rem_subject2").on("click", function(event){

$("#subject2").hide();
$("#rem_subject2").hide();

$("#add_subject2").show();
});
var subj="";
// $("#subject1").on("change", function(event){
// console.log('enter');
//     console.log("changed");

// });
</script>


        <script>
  $( function() {
    $( "#dob" ).datepicker({
      changeMonth: true,
      changeYear: true,
      showOtherMonths: true,
      selectOtherMonths: true,
      minDate: -20000,
      maxDate: "-1M"
    });
  } );
  </script>
