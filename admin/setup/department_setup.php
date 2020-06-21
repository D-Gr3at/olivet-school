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
// var_dump($_REQUEST);

if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'edit')
{
    $department_id  = $_REQUEST['dapartment_id'];
    $department      = $dbobject->db_query("SELECT * FROM department_setup_tbl WHERE dapartment_id='$department_id'");
    $operation = 'edit';
    $faculty_name = $dbobject->getitemlabel('faculty_settup', 'faculty_id', $department[0]['faculty_code'], 'faculty_name');
    $faculty_query = "SELECT faculty_id, faculty_name FROM faculty_settup";
    $faculties = $dbobject->db_query($faculty_query);
}
else
{
    $operation = 'new';
    $query_select_id = "SELECT dapartment_id from department_setup_tbl  ORDER BY dapartment_id DESC LIMIT 1";
    $run_query_select_id = $dbobject->db_query($query_select_id);
    $department_setup_id = $run_query_select_id[0]["dapartment_id"];
    $department_id = $department_setup_id+1;
    $faculty_query = "SELECT faculty_id, faculty_name FROM faculty_settup";
    $faculties = $dbobject->db_query($faculty_query);
}
$staff_names_sql = "SELECT staff_id, first_name, last_name, middle_name FROM staff_information ORDER BY staff_id DESC";
$staff_names = $dbobject->db_query($staff_names_sql);
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
    <h4 class="modal-title" style="font-weight:bold"><?php echo ($operation=="edit")?"Edit ":""; ?>Department Setup<div><small style="font-size:12px">All asterik fields are compulsory</small></div></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3 ">
    <form id="form1" onsubmit="return false">
       <input type="hidden" name="op" value="Department.saveDepartment"/>
       <input type="hidden" name="operation" value="<?php echo $operation; ?>"/>
       <input type="hidden" name="dapartment_id" id="dapartment_id" value="<?php echo $department_id; ?>"/>
       <!-- <input type="hidden" name="account_name" id="account_name" value="<?php echo $user[0]['account_name']; ?>"/> -->
       <input type="hidden" name="posted_by" id="posted_by" value="<?php echo $_SESSION['firstname_sess'].' '.$_SESSION['lastname_sess']; ?>"/>
        <fieldset class="form-group">
            <legend>Department Info</legend>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">Faculty<span class="asterik">*</span></label>
                        <?php
                            echo "<select class='form-control text-uppercase'  name='faculty_code' id='faculty_code'>
                            <option disable='disabled' value=''>::SELECT A FACULTY::</option>";
                                $currently_selected = '';
                                foreach ($faculties as $key => $value) {
                                    $faculty_name_value = $value["faculty_name"];
                                    $faculty_id = $value["faculty_id"];
                                    if($faculty_name == $faculty_name_value) {
                                        echo "<option value='".$faculty_id."' selected>".$faculty_name_value."</option>";
                                    }else{
                                        echo "<option value='".$faculty_id."'>".$faculty_name_value."</option>"; 
                                    }
                                }
                            echo '</select>';
                        ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                            <label class="form-label">Department Name<span class="asterik">*</span></label>
                            <input type="text" name="department_name" value="<?php echo $department[0]['department_name']; ?>" class="form-control text-uppercase">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                            <label class="form-label">Year Established<span class="asterik">*</span></label>
                            <?php
                                $currently_selected = date('Y');
                                $earliest_year = 1980;
                                $latest_year = date('Y');
                                print '<select class="form-control" name="established" id="established">
                                        <option value="">::SELECT ESTABLISHED YEAR::</option>';
                                    foreach(range($latest_year, $earliest_year) as $year){
                                        if($department[0]['established'] == $year){
                                            echo "<option value='".$department[0]['established']."' selected>".$department[0]['established']."</option>";
                                        }else{
                                            print '<option value="'.$year.'">'.$year.'</option>';
                                        }
                                    }
                                print '</select>';
                            ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">Status<span class="asterik">*</span></label>
                        <select class="form-control text-uppercase" name="status" id="status">
                            <option value="">::SELECT STATUS::</option>
                            <option value="1" <?php echo ($department[0]['status'] == '1')?"selected":""; ?>>Active</option>
                            <option value="0" <?php echo ($department[0]['status'] == '0')?"selected":""; ?>>Inactive</option>
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
        <button id="save_department" onclick="saveRecord()" class="btn btn-primary">Submit</button>
    </form>
</div>
<script>
    function saveRecord()
    {
        $("#save_department").text("Loading......");
        var dd = $("#form1").serialize();
        console.log(dd);
        $("#server_mssg").empty();
        $.post("utilities.php",dd,function(re)
        {
            console.log(re);
            $("#save_department").text("Save");
            if(re.response_code == 0)
                {
                    $("#server_mssg").empty();
                    $("#server_mssg").text(re.response_message);
                    $("#server_mssg").css({'color':'green','font-weight':'bold'});
                    getpage('department_list.php','page');
                    setTimeout(()=>{
                        $('#sizedModalLg').modal('hide');
                    },1000)
                }else{
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