<?php

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
             $row = mysql_fetch_array($result);
             $surname = $row['surname'];
             $othername = $row['othernaame'];
             $email = $row['email'];
             $Program = $row['program'];
             $aid = $row['reg_id'];
             $Nationality = $row['Nationality'];
             $state_of_origin = $row['state_of_origin'];
             $local_Gov_Area = $row['local_Gov_Area'];
             $dob = $row['date_of_birth'];
             $pbirth = $row['pbirth'];
             $postal_address = $row['postal_address'];
             $gaddress = $row['gaddress'];
             $gname = $row['gname'];



         }
         $Program = $Program;
     } else {
         //     include 'logout.php';
     }
     $station = "";
    $program_sel = $dbobject->pickStation($station);

    $load_country = $dbobject->loadCountry($Nationality);
 unset($_SESSION['filename']);
  ?>
<div class="card">
    <div class="card-header">

        <div class="row">
            <div class="col-xs-12 col-sm-12 custom_left">
                <h5 class="card-title mb-1"><p>Please Upload a valid and latest passport to complete Application</p></h5>
            </div>
        </div>
        <div class="row">
            <div><font color="blue">Note:  Your passport must not be more than 600kb</font></div>
    </div>
    <br>
    </div>


        <div class="contact_form">
            <form name="form1" id="form1" onsubmit="return false">
                                    <input type="hidden" name="Program" id="Program" value="<?php echo $Program; ?>" readonly="true"/>
                                    <input type="hidden" name="email" id="email" value="<?php echo $email; ?>" readonly="true"/>
                                    <input type="hidden" name="aid" id="aid" value="<?php echo $aid; ?>" readonly="true"/>
                                    <input type="hidden" name="userid" id="userid" value="<?php echo  $reg_id; ?>">

                                            <div class="row">
                                                <div class="col-xs-12 col-sm-4">
                                              </div>

                                            <div class="col-xs-12 col-sm-6">

        <img src="img/empty_passport.jpg" id="output" style="margin:10px; width:250px; height:250px;"/>
          <input type="file" accept="image/*" onchange="loadFile(event)" id="apppassport" name="files[]"><br/>

                                            </div>

                                        </div>


                                            <div class="row">
                                               <div id="loading_spinner"><i class="fa fa-spinner fa-pulse"></i> Uploading</div>
                        <div id="result"></div>
                        <div class="row">
                                                <div id="display_message" name="display_message">

                                                    </div>
                                                     <div id="display_message2" name="display_message2">

                                                    </div>
                                            </div>
                                            </div><!--end row-->
                                            <div id="confirm" >
                                                <input type="checkbox" id="confirm2" name="confirm2" value="off" onclick="getaction()">By clicking  on this, you have confirmed that all information filled are correct
                                            </div>
                <br><br>
                                            <div class="row" id="control_btns">
                                                <div class="col"></div>
                                                <div class=" col-sm-2">
                        <button type="button" class="btn btn-lg btn-warning btn-block" name="reset" id="reset" onclick="getpage('apply_now_step_two.php', 'mainContent');">Back</button>
                    </div>
                                                 <div class=" col-sm-4" style="float: right;">
                        <button type="button" class="btn btn-lg btn-success btn-block" name="subbtn" id="subbtn"  value="Complete Application" onclick="javascript:callpage('apply_now5')">Complete Application</button>
                    </div>
<div class="col"></div>

                                        </div>
            </form>
        </div>
</div>
<script type="text/javascript" src="js/ajaxupload.3.5.js"></script>

   <script type="text/javascript">
   $("#confirm").hide();
function getaction(){

var isChecked2 = $('#confirm2').prop('checked');

     //var second = = $('input[namseconde=second]:checked');
     // $("#second").val();


     if(isChecked2 == true ){
 $("#confirm2").val('hello');

}else{
  $("#confirm2").val('off');
}
    }

  var loadFile = function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('output');
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };

            function TransferCompleteCallback(content){
                // we might want to use the transferred content directly
                // for example to render an uploaded image
            }

            $( document ).ready(function() {
                var input = document.getElementById("apppassport");
                var formdata = false;

                if (window.FormData) {
                    formdata = new FormData();
                    $("#btn_submit").hide();
                    $("#loading_spinner").hide();
                }

                $('#apppassport').on('change',function(event){
                    var i = 0, len = this.files.length, img, reader, file;
                    //console.log('Number of files to upload: '+len);
                    $('#result').html('');
                    $('#apppassport').prop('disabled',true);
                    $("#loading_spinner").show();
                     $("#display_message").hide();
                    for ( ; i < len; i++ ) {
                        file = this.files[i];
                        //console.log(file);
                        if(!!file.name.match(/.*\.jpg|png|JPG|PNG|jpeg$/)){
                                if ( window.FileReader ) {
                                    reader = new FileReader();
                                    reader.onloadend = function (e) {
                                        TransferCompleteCallback(e.target.result);
                                    };
                                    reader.readAsDataURL(file);
                                }
                                if (formdata) {
                                    formdata.append("files[]", file);
                                }
                        } else {
                            $("#loading_spinner").hide();
                            $('#apppassport').val('').prop('disabled',false);
							alert(file.name+' is not a jpg or png or jpeg');
                        }
                    }
                    if (formdata) {
                        console.log("processing ");
                        $.ajax({
                            url: "process_files.php",
                            type: "POST",
                            data: formdata,
                            processData: false,
                            contentType: false, // this is important!!!
                            success: function (res) {
                                console.log(res);
                                var result="";
                                try {
                                 result = JSON.parse(res);
                                 }
catch(err) {
     $("#confirm").hide();
      $("#result").hide();
     $("#display_message2").show();
      $("#display_message2").addClass('col-xs-12 col-sm-12 alert alert-danger').html("File Too Large. Image Must not be more than 600kb").removeClass("alert-warning alert-success");



}

                                $("#loading_spinner").hide();

                                $('#apppassport').val('').prop('disabled',false);
                                if(result.res === true){
                                     $("#display_message").hide();
                                    var buf = '<ul class="list-group">';
                                    for(var x=0; x<result.data.length; x++){
                                        buf+='<li class="list-group-item">'+result.data[x]+'</li>';
                                    }
                                    buf += '</ul>';
                                    $('#result').addClass('col-xs-12 col-sm-12 alert alert-success').html('<strong>Files uploaded:</strong>'+buf);
                                    setTimeout(function(){
                                         $("#result").hide();
                                           $("#display_message").hide();
                                         //display_message
                                         $("#display_message2").show();
                                         $("#display_message2").addClass('col-xs-12 col-sm-12 alert alert-info').html("Click on Complete Application to Complete Registration").removeClass("alert-warning alert-danger");
                                          $("#confirm").show();

                  },2500);
                                } else {
                                    $('#result').addClass('alert alert-warning').html(result.data);
                                }
                                // reset formdata
                                formdata = false;
                                formdata = new FormData();
                            }
                        });
                    }
                    return false;
                });
            });
        </script>

