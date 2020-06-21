<?php
SESSION_START();
include_once("../libs/dbfunctions.php");
$dbobject = new dbobject();

$user_role = $_SESSION['role_id_sess'];
if($user_role == 001){
    $sql_role = "SELECT * FROM role WHERE role_id IN ('003','005','006','007') ";
}elseif($user_role == 005){
    $sql_role = "SELECT * FROM role WHERE role_id = '003' ";
}else{
    $sql_role = "SELECT * FROM role WHERE role_id <> '001' AND role_id <> '$user_role' AND role_id NOT IN ('003','005','006','007')";
}

$roles = $dbobject->db_query($sql_role);

if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'edit')
{
    $faculty_id  = $_REQUEST['faculty_id'];
    $faculty      = $dbobject->db_query("SELECT * FROM faculty_setup WHERE faculty_id='$faculty_id'");
    $operation = 'edit';
}
else
{
    $operation = 'new';
    $query_select_id = "SELECT faculty_id from faculty_setup  ORDER BY faculty_id DESC LIMIT 1";
    $run_query_select_id = $dbobject->db_query($query_select_id);
    $faculty_setup_id = $run_query_select_id[0]["faculty_id"];
    if($faculty_setup_id==NULL){
        $faculty_setup_id = 1;
        $faculty_id = $faculty_setup_id;
    }else{
        // $faculty_id = $faculty_setup_id++;
        $faculty_id=$dbobject->getnextid('faculty_setup');
    }
}
$staff_names_sql = "SELECT staff_id, first_name, last_name, middle_name FROM staff_information ORDER BY staff_id DESC";
$staff_names = $dbobject->db_query($staff_names_sql);
// var_dump($staff_names);s
?>
 <link rel="stylesheet" href="codebase/dhtmlxcalendar.css" />
<script src="codebase/dhtmlxcalendar.js"></script>
<script>
    doOnLoad();
    var myCalendar;
function doOnLoad()
{
   myCalendar = new dhtmlXCalendarObject(["start_date","end_date"]);
   myCalendar.hideTime();
}
</script>
<style>
    fieldset 
    { 
    display: block;
    margin-left: 2px;
    margin-right: 2px;
    padding-top: 0.35em;
    padding-bottom: 0.625em;
    padding-left: 0.75em;
    padding-right: 0.75em;
    border: 1px solid #ccc;
    }
    
    legend
    {
        font-size: 18px;
        padding: 5px;
        font-weight: bold;
        color:#d1702b;
        width:auto;
    }
    label{
        color:#000;
        font-weight: bold;
    }
</style>
<style>
    #login_days>label{
        margin-right: 10px;
    }
    .asterik
    {
        color:red;
    }
</style>
<div class="modal-header">
    <h4 class="modal-title" style="font-weight:bold"><?php echo ($operation=="edit")?"Edit ":""; ?>Programme Course Content Setup<div><small style="font-size:12px">All asterik fields are compulsory</small></div></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3 ">
    <form id="form1" onsubmit="return false">
       <input type="hidden" name="op" value="Faculty.saveFaculty"/>
       <input type="hidden" name="operation" value="<?php echo $operation; ?>"/>
       <input type="hidden" name="faculty_id" id="faculty_id" value="<?php echo $faculty_id; ?>"/>
       <input type="hidden" name="account_name" id="account_name" value="<?php echo $user[0]['account_name']; ?>"/>
       <input type="hidden" name="posted_by" id="posted_by" value="<?php echo $_SESSION['firstname_sess'].' '.$_SESSION['lastname_sess']; ?>"/>
        <fieldset class="form-group">
            <legend>Faculty Info</legend>
            <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                        <label class="form-label">Faculty Name<span class="asterik">*</span></label>
                        <input type="text" name="faculty_name" value="<?php echo $faculty[0]['faculty_name']; ?>" class="form-control">
                    </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                        <label class="form-label">Faculty Head<span class="asterik">*</span></label>
                        <?php
                        /**
                         * display faculty head names
                         */
                            echo "<select class='form-control' name='faculty_head' id='faculty_head'>
                                <option disable='disabled' value='' selected='selected'>::SELECT A FACULTY HEAD::</option>";

                                $currently_selected = '';
                                
                                foreach ($staff_names as $key => $value) {
                                    # code...
                                    $first_name = $value["first_name"];
                                    $last_name = $value["last_name"];
                                    $middle_name = $value["middle_name"];
                                    $staff_id = $value["staff_id"];
                                    if($staff_id == $faculty[0]['faculty_head']) {
                                        echo "<option value='$staff_id' selected>".$last_name." ".$middle_name." ".$first_name."</option>";
                                    }else {
                                    echo "<option value='$staff_id'>".$last_name." ".$middle_name." ".$first_name."</option>";
                                    }
                                }
                            echo '</select>';
                        ?>
                    </div>
            </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                            <label class="form-label">Year Established<span class="asterik">*</span></label>
                            <?php
                                $currently_selected = date('Y');
                                $earliest_year = 1964;
                                $latest_year = date('Y');
                                print '<select class="form-control" name="faculty_established" id="faculty_established">';
                                    foreach(range($latest_year, $earliest_year) as $year){
                                        if($faculty[0]['faculty_established'] == $year){
                                            echo "<option value='$faculty[0][faculty_established]' selected>".$faculty[0]['faculty_established']."</option>";
                                        }else{
                                            print '<option value="'.$year.'"'.($year === $currently_selected ? 'selected="selected"' : '').'>'.$year.'</option>';
                                        }
                                    }
                                print '</select>';
                            ?>
                        </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">Status<span class="asterik">*</span></label>
                        <select class="form-control" name="status" id="status">
                            <option value="Active" <?php echo ($faculty[0]['status'] == 1)?"selected":""; ?>>Active</option>
                            <option value="Inactive" <?php echo ($faculty[0]['status'] == 0)?"selected":""; ?> >Inactive</option>
                        </select>
                    </div>
                </div>
        </div>
        </fieldset>
        <div class="row">
            <div class="col-sm-12">
                <div id="server_mssg"></div>
            </div>
        </div>
        <button id="save_facility" onclick="saveRecord()" class="btn btn-primary">Submit</button>
    </form>
</div>
<script>
    function saveRecord()
    {
        $("#save_facility").text("Loading......");
        var dd = $("#form1").serialize();
        console.log(dd);
        
        $.post("utilities.php",dd,function(re)
        {
            console.log(re);
            $("#save_facility").text("Save");
            if(re.response_code == 0)
                {
                    $("#server_mssg").text(re.response_message);
                    $("#server_mssg").css({'color':'green','font-weight':'bold'});
                    getpage('programme_course_content_list.php','page');
                    setTimeout(()=>{
                        $('#sizedModalLg').modal('hide');
                    },1000)
                }
            else
                {
                    $("#server_mssg").text(re.response_message);
                     $("#server_mssg").css({'color':'red','font-weight':'bold'});
                }
                
        },'json');
    }
    if($("#sh_display").is(':checked'))
        {
            
        }
    function show_bank_details(val)
    {
        if(val == 003)
            {
                $("#parish_pastor_div").show();
            }
        else{
            $("#parish_pastor_div").hide();
        }
    }
    function fetchLga(el)
    {
        $("#lga-fd").html("<option>Loading Lga</option>");
        $.post("utilities.php",{op:'Church.getLga',state:el},function(re){
//            $("#lga-fd").empty();
            console.log(re);
            $("#lga-fd").html(re.state);
            $("#church_id").html(re.church);
            
        },'json');
    }
    function getUniqueChurch(el)
    {
        $("#church_id").html("<option>Loading Church</option>");
        var ste = $("#church_state").val();
        $.post("utilities.php",{op:'Church.churchByState',state:ste,lga:el},function(re){
//            $("#lga-fd").empty();
            console.log(re);
            $("#church_id").html(re);
            
        });
    }
    
    $("#show").click(function()
    {
        var password = $("#password").attr('type');
        if(password=="password")
            {
                $("#password").attr('type','text');
                $("#show").text("Hide");
            }else{
                $("#password").attr('type','password');
                $("#show").text("Show");
            }
    });
    function check_bank_det(el)
    {
        if($("#yes").is(':checked')){
            $("#bank_details").slideDown()
        }else if($("#no").is(':checked'))
         {
            $("#bank_details").slideUp()
         }
    }
    
    function fetchAccName(acc_no)
    {
        if(acc_no.length == 10)
            {
                var account  = acc_no;
                var bnk_code = $("#bank_name").val();
                $("#acc_name").text("Verifying account number....");
                $("#account_name").val("");
                $.post("utilities.php",{op:"Church.getAccountName",account_no:account,bank_code:bnk_code},function(res){
                    
                    $("#acc_name").text(res);
                    $("#account_name").val(res);
                });
            }else{
                $("#acc_name").text("Account Number must be 10 digits");
            }
        
    }
</script>