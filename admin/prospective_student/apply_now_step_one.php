<?php
error_reporting(0);
    define('AJAX_REQUEST', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    if (!AJAX_REQUEST) {
        die();
    }

        session_start();
        include '../../lib/dbfunctions_extra.php';
        $dbobject = new myDbObject();

     if (isset($_SESSION['reg_id'])) {
         $reg_id = $_SESSION['reg_id'];

         $result = $dbobject->getrecordset('app_applicant_account_setup', 'reg_id', $reg_id);

         $numrows = mysql_num_rows($result);
         if ($numrows > 0) {
          //reg_id, session, email, surname, othernaame, phone_number, program, userpassword, created, rrr, reg_status, rrr_status, linkCode, date_of_birth, gender, marital_status, Nationality, state_of_origin, local_Gov_Area, District_word, tribe, religion, postal_address, exam_center, ip, gname, gaddress, statusCode, pay_status, pbirth, passport, fname, rrr_acceptance, rrr_acceptance_date, rrr_acceptance_status, application_lock, educational_status
             $row = mysql_fetch_array($result);
             $surname = str_replace("%20"," ", $row['surname']);
             $othername = str_replace("%20"," ", $row['othernaame']);
             $email = str_replace("%20"," ", $row['email']);
             $Program = str_replace("%20"," ", $row['program']);
             $aid = $row['reg_id'];
             $Nationality = str_replace("%20"," ", $row['Nationality']);
             $state_of_origin = $row['state_of_origin'];
             $local_Gov_Area = $row['local_Gov_Area'];
             $dob = $row['date_of_birth'];
             $pbirth =str_replace("%20"," ", $row['pbirth']);
             $postal_address = str_replace("%2C"," ", $row['postal_address']);
             $gaddress = str_replace("%2C"," ", $row['gaddress']);
             $gname = str_replace("%20"," ",$row['gname']);
             $maritial =$row['marital_status'];
              $religion =$row['religion'];
               $tribe =$row['tribe'];
               $fname = $row['fname'];
             $ward = $row['ward'];
               //Lgaid, Lga, StateId

                 $lga = $dbobject->getitemlabel('app_lga', 'StateId', $row['local_Gov_Area'], 'Lga');
                 //id, name, country_id
                 $state = $dbobject->getitemlabel('app_states', 'id', $row['state_of_origin'], 'name');
                 //center_id, center_name, user, created
                  $centerheld = $dbobject->getitemlabel('app_center_tb', 'center_id', $row['exam_center_id'], 'center_name');
//id, name, state_id
$userlga = $dbobject->getItemLabelArr("app_cities", array("id"), array($row['local_Gov_Area']), array("id",'name'));
$userstate = $dbobject->getItemLabelArr("app_states", array("id"), array($row['state_of_origin']), array("id",'name'));

         }
         $Program =  $Program;
     } else {
         //     include 'logout.php';
     }
     $station = "";
    $program_sel = $dbobject->pickStation($station);

    $load_country = $dbobject->loadCountry($Nationality);

  ?>

<div class="card">
    <div class="card-header">

       <div class="row">
           <div class="col-xs-12 col-sm-4 custom_left">
           <h5 class="card-title mb-1"><p>Applicant Biodata</p></h5>
           </div>
       </div>
    </div>
    <br>
        <h6>All fields marked <span class="error"><font color="red">*</font></span> are required</h6>
        <div class="contact_form">
            <form name="form2" id="form2" onsubmit="return false">
                                    <input type="hidden" name="Program" id="Program" value="<?php echo $Program; ?>" readonly="true"/>
                                    <input type="hidden" name="email" id="email" value="<?php echo $email; ?>" readonly="true"/>
                                    <input type="hidden" name="aid" id="aid" value="<?php echo $aid; ?>" readonly="true"/>

                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Surname <span class="error">*</span></label>
                                                        <!-- <input type="text" class="form-control required-text" name="Surname" readonly> -->
                                                        <input type="text" class="form-control " name="Surname" value="<?php echo $surname; ?>" readonly />
                                                    </div>
                                                </div>

                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label>First Name <span class="error">*</span></label>
                                                    <input type="text" class="form-control " name="othername" id="fname" value="<?php echo $fname; ?>" readonly />
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Country <span class="error">*</span></label>
                                                        <select class="form-control required-text" name="Country" id="Country" onchange="getState1(this);">
                                                            <?php echo $load_country; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                    <label for="state">State of Origin : <span class="error">*</span></label>
                                                            <select name="state" id="state" class="form-control required-text custom-select" onchange="getStateLGA(this);">
                                                              <option value="">:Select State Of Origin:</option>
                                                              <?php if($userstate['id'] !="") {  ?>
                                                                <option value="<?php echo $userstate['id']; ?>"selected><?php echo $userstate['name'];  ?></option>
                                                              <?php } ?>

                                                                  </select>
                                                         </div>
                                                </div>

                                            </div>
                                            <!--end row-->
<div class="row">
    <div class="col-xs-12 col-sm-6">
        <div class="form-group">
            <label>LGA <span class="error">*</span></label>
            <select class="form-control required-text" name="lga" id="lga">
                <option value="">:Select LGA:</option>
                <?php if($userlga['id'] !=""){  ?>
                    <option value="<?php echo $userlga['id']; ?>" selected><?php echo $userlga['name'];  ?></option>
                <?php } ?>

            </select>
            <!-- <select class="form-control required-text" name="lga" id="lga">

            </select>  -->
        </div>
    </div>


                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">

                                                         </div>
                                                </div><div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Religion <span class="error">*</span></label>
                                                        <select class="form-control required-text" name="religion" id="religion">
                                                          <option value="">:Select Religion:</option>
                                                           <?php if($religion!=""){  ?>

                                                            <option value="<?php echo $religion; ?>" selected><?php echo $religion; ?></option>
                                                          <?php  }?>
                                                          <option value="Muslim">Muslim</option>
                                                          <option value="Christian">Christian</option>
                                                          <option value="Others">Others</option>

                                                        </select>
                                                        <!-- <select class="form-control required-text" name="lga" id="lga">

                                                        </select>  -->
                                                    </div>
                                                </div>

                                            </div>



















                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Tribe <span class="error">*</span></label>
                                                        <input type="text" class="form-control required-text" name="tribe" id="tribe" value="<?php echo $tribe; ?>">


                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Marital Status <span class="error">*</span></label>
                                                        <select class="form-control required-text" name="maritial" id="maritial">
                                                          <option value="">Select Marital Status</option>
                                                          <?php if($maritial!=""){  ?>

                                                            <option value="<?php echo $maritial; ?>" selected><?php echo $maritial; ?></option>
                                                          <?php  }?>
                                                          <option value="single">Single</option>
                                                          <option value="married">Married</option>
                                                          <option value="others">Others</option>

                                                        </select>
                                                        <!-- <select class="form-control required-text" name="lga" id="lga">

                                                        </select>  -->
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">

                                                         </div>
                                                </div>

                                            </div>
                                            <!--end row-->

                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Date of Birth <span class="error">*</span></label>
                                                        <input type="text" class="form-control required-text" name="dob" id="dob" value="<?php echo $dob; ?>" />

                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                    <label>Birth Place <span class="error">*</span></label>
                                                    <input type="text" class="form-control " name="pbirth" id="pbirth" value="<?php echo $pbirth; ?>" />
                                             </div>
                                                </div>

                                            </div>
                                            <!--end row-->

                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Postal Address <span class="error">*</span></label>
      <input type="text" class="form-control " name="postal_address" id="postal_address" value="<?php echo $postal_address; ?>" />
                                                    </div>
                                                </div>
                                                   <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Preffered Exam Centre <span class="error">*</span></label>
                                                        <select class="form-control required-text" name="centre" id="centre">
                                                          <option value="">:Select Preffered Exam Centre:</option>
                                                          <?php if( $centerheld!=''){  ?>
                                                            <option value="<?php echo  $row['exam_center_id'];  ?>" selected><?php echo  $centerheld; ?></option>
                                                            <?php }  ?>
                                                          <?php
                                                          //center_tbcenter_id, center_name, user, created
                                                          $centre = mysql_query("select center_id, center_name from app_center_tb");
                                                          while($rowcenter =mysql_fetch_array($centre)){

                                                          ?>
                                                          <option value="<?php echo $rowcenter['center_id']  ?>"><?php echo $rowcenter['center_name']  ?></option>

                                                        <?php  } ?>


                                                        </select>
                                                        <!-- <select class="form-control required-text" name="lga" id="lga">

                                                        </select>  -->
                                                    </div>
                                                </div>

                                            </div>
                                            <!--end row-->
                                            <hr/>

                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Name of Parent/Guidian <span class="error">*</span></label>
                                                        <input type="text" class="form-control " name="Gname" id="Gname" value="<?php echo $gname; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>District/Ward <span class="error">*</span></label>
                                                        <input type="text" class="form-control required-text" name="ward" id="ward" value="<?php echo $ward; ?>">


                                                    </div>
                                                </div>

                                            </div>
                                            <!--end row-->


                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Address <span class="error">*</span></label>
                                                        <input type="text" class="form-control required-text" name="Gaddress" id="Gaddress" value="<?php echo $gaddress; ?>" />
                                                    </div>
                                                </div>


                                            </div>


                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12">
                                                <div id="display_message" name="display_message">

                                                </div></div>
                                            </div><!--end row-->

                                            <div class="row" id="control_btns">
                                                <div class="col"></div>
                                                 <div class=" col-sm-4">
                        <button type="button" class="btn btn-lg btn-success btn-block" name="subbtn" id="subbtn"  value="Submit" onclick="javascript:callpagesubmit('apply_now3')">Save And Continue</button>
                    </div>
<div class=" col-sm-4" style="float: right;">
                        <button type="button" class="btn btn-lg btn-info btn-block" name="reset" id="reset" onclick="getpage('apply_now_step_two.php', 'page');">Next</button>
                    </div><div class="col"></div>

                                        </div>



            </form>
        </div>
</div>

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
