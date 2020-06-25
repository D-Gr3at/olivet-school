<?php
error_reporting(1);
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
    $course_id  = $_REQUEST['course_id'];
    $course = $dbobject->db_query("SELECT * FROM course_setup_tbl WHERE course_id=".$course_id."");
    $programme_course = $dbobject->db_query("SELECT * FROM programme_course_setup_tbl WHERE programme_course_id=".$course[0]['programme_course_fk']."");
    $programme = $dbobject->db_query("SELECT * FROM programme_setup WHERE programme_id=".$programme_course[0]['programme_id']."");
    $department = $dbobject->db_query("SELECT * FROM department_setup_tbl WHERE dapartment_id=".$programme[0]['department_id']."");
    $faculty = $dbobject->db_query("SELECT * FROM faculty_settup WHERE faculty_id=".$department[0]['faculty_code']."");
    $operation = 'edit';

}
else
{
    $operation = 'new';
    $query_select_id = "SELECT curriculum_id from curriculum_setup_tbl  ORDER BY curriculum_id DESC LIMIT 1";
    $run_query_select_id = $dbobject->db_query($query_select_id);
    $curriculum_setup_id = $run_query_select_id[0]["curriculum_id"];
    $curriculum_id = $curriculum_setup_id + 1;
}
$session = $dbobject->db_query("SELECT session_id FROM session_setup WHERE status = 1");
$session_id = $session[0];
// var_dump($session_id["session_id"]);
$faculty_names_sql = "SELECT faculty_id, faculty_name FROM faculty_settup";
$faculty_names = $dbobject->db_query($faculty_names_sql);
?>
 <link rel="stylesheet" href="codebase/dhtmlxcalendar.css" />
<script src="codebase/dhtmlxcalendar.js"></script>
<link rel="stylesheet" href="../css/jquery.datetimepicker.css">
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
<div id="mimodal">
<div class="modal-header">
    <h4 class="modal-title" style="font-weight:bold"><?php echo ($operation=="edit")?"Edit ":""; ?>Programme Course<div><small style="font-size:12px">All asterik fields are compulsory</small></div></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3 ">
    <form id="form1" onsubmit="return false">
       <input type="hidden" name="op" value="SchoolCourses.saveSchoolCourse"/>
       <input type="hidden" name="operation" value="<?php echo $operation; ?>"/>
       <input type="hidden" name="course_id" value="<?php echo $course_id; ?>"/>
       <input type="hidden" name="programme_course_id" value="<?php echo $programme_course[0]["programme_course_id"]; ?>"/>
       <input type="hidden" name="session_id" value="<?php echo $session_id['session_id']; ?>"/>
       <input type="hidden" name="posted_by" id="posted_by" value="<?php echo $_SESSION['firstname_sess'].' '.$_SESSION['lastname_sess']; ?>"/>
        <fieldset class="form-group">
            <legend>Department Info</legend>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="form-label">Faculty<span class="asterik">*</span></label>
                        <?php
                            echo "<select class='form-control text-uppercase' name='faculty' id='faculty'>
                            <option disable='disabled' value=''>::SELECT A FACULTY::</option>";
                                $currently_selected = '';
                                foreach ($faculty_names as $key => $value) {
                                    $faculty_name_value = $value["faculty_name"];
                                    $faculty_id = $value["faculty_id"];
                                    if($faculty[0]['faculty_name'] == $faculty_name_value) {
                                        echo "<option value='".$faculty_id."' selected>".$faculty_name_value."</option>";
                                        $departments = $dbobject->db_query("SELECT * FROM department_setup_tbl WHERE faculty_code =".$faculty_id."");
                                    }else{
                                        echo "<option value='".$faculty_id."'>".$faculty_name_value."</option>"; 
                                    }
                                }
                            echo '</select>';
                        ?>
                    </div>
                </div>
                <div class='col-sm-4'>
                    <div class='form-group'>
                        <label class="form-label">Department<span class="asterik">*</span></label>
                        <?php
                            echo "<select class='form-control text-uppercase' name='department' id='department'>
                            <option value=''>::NO DEPARTMENT TO SELECT::</option>";
                            foreach($departments as $key => $value){
                                $department_name = $value['department_name'];
                                $department_id = $value['dapartment_id'];
                                if($department[0]['department_name'] == $department_name){
                                    echo "<option value='".$department_id."' selected>".$department_name."</option>";
                                    $department_options = $dbobject->db_query("SELECT * FROM programme_setup WHERE department_id = ".$department_id."");
                                }
                            }
                            echo "</select>";
                        ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="form-label">Programme<span class="asterik">*</span></label>
                        <?php
                            echo "<select class='form-control' name='department_option' id='department_option'>
                            <option value=''>::NO OPTION TO SELECT::</option>";
                            foreach($department_options as $key => $value){
                                $option_name = $value['programme_name'];
                                $option_id = $value['programme_id'];
                                if($programme[0]['programme_name'] == $option_name){
                                    echo "<option value='".$option_id."' selected>".$option_name."</option>";
                                }
                            }
                            echo "</select>";
                        ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="form-label">Semester<span class="asterik">*</span></label>
                        <select class='form-control' name='semester' id='semester'>
                            <option value='' selected='selected'>::SELECT SEMESTER::</option>
                            <option value="1" <?php echo ($programme_course[0]['semester'] == 1)?"selected":""; ?>>First Semester</option>
                            <option value="2" <?php echo ($programme_course[0]['semester'] == 2)?"selected":""; ?>>Second Semester</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="form-label">Level<span class="asterik">*</span></label>
                        <select class='form-control' name='level' id='level'>
                            <option value=''>::SELECT LEVEL::</option>
                            <?php
                                for($year = 100; $year<=600; $year+=100){
                                    if($programme_course[0]['level'] == $year){
                                        echo "<option value='".$year."' selected>".$year."</option>";
                                    }else{
                                        echo "<option value='".$year."'>".$year."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset class="form-group">
            <legend>Course Info</legend>
            <div class="row">
                <div class="container">
                    <!-- <span class='remove-item-form fa fa-times color-danger' style='cursor:pointer;right: 0;margin-right: 50px;font-weight: bolder;'></span> -->
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <label class="form-label">Course Title<span class="asterik">*</span></label>
                                <input type="text" name="course_title" value="<?php echo $course[0]["course_title"] ?>" id="course_title" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="form-label">Course Code<span class="asterik">*</span></label>
                                <input type="text" name="course_code" value="<?php echo $course[0]["course_code"] ?>" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="form-label">Duration <small class="text-danger"><em>(hours)</em></small><span class="asterik">*</span></label>
                                <input type="text" name="course_duration" value="<?php echo $course[0]["course_duration"] ?>" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <label class="form-label">Unit<span class="asterik">*</span></label>
                                <input type="text" name="course_unit" value="<?php echo $course[0]["course_unit"] ?>" class="form-control"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <div class="row">
            <div class="col-sm-12">
                <div id="server_mssg"></div>
            </div>
        </div>
        <input type="submit" id="save_course" class="btn btn-primary" value="Submit"/>
    </form>
</div>
</div>
<script>
    $(document).ready(function() {
         // on form submit
        $("#form1").on('submit', function() {
            $("#save_course").val("Loading....");
            var dd = $("#form1").serialize();
            // console.log(dd);

            $.post("utilities.php",dd,function(re){
                // console.log(re);
                $("#save_course").val("Save");
                if(re.response_code == 0){
                    $("#server_mssg").text(re.response_message);
                    $("#server_mssg").css({'color':'green','font-weight':'bold'});
                    getpage('school_courses_list.php','page');
                    setTimeout(()=>{
                        $('#sizedModalLg').modal('hide');
                    },1000)
                    }
                else{
                    $("#server_mssg").text(re.response_message);
                    $("#server_mssg").css({'color':'red','font-weight':'bold'});
                }
            },'json');
        });
    });

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

    function elemToAdd(){
        var div;
        for(var i = 0; i <= 100; ++i) {
        div = 
		"<span class='remove-item-form fa fa-times color-danger' style='cursor:pointer;right: 0;margin-right: 50px;font-weight: bolder;'></span>"+
        "<div class='row'>"+
		"<div class='col-sm-7'>"+
			"<div class='form-group'>"+
				"<label class='form-label'>Course Title<span class='asterik'>*</span></label>"+
				"<input type='text' name='course_title[]' value='' class='form-control'>"+
			"</div>"+
		"</div>"+
		"<div class='col-sm-2'>"+
			"<div class='form-group'>"+
				"<label class='form-label'>Course Code<span class='asterik'>*</span></label>"+
				"<input type='text' name='course_code[]' value='' class='form-control'/>"+
			"</div>"+
		"</div>"+
		"<div class='col-sm-2'>"+
			"<div class='form-group'>"+
				"<label class='form-label'>Duration<small class='text-danger'><em>(hours)</em></small><span class='asterik'>*</span></label>"+
				"<input type='text' name='course_duration[]' value='' class='form-control'/>"+
			"</div>"+
		"</div>"+
		"<div class='col-sm-1'>"+
			"<div class='form-group'>"+
				"<label class='form-label'>Unit<span class='asterik'>*</span></label>"+
				"<input type='text' name='course_unit[]' value='' class='form-control'/>"+
			"</div>"+
		"</div>"+
		// "<div class='col-sm-2'>"+
		// 	"<div class='form-group' id='extra_input'>"+
		// 		"<label class='form-label'>Is Elective?<span class='asterik'>*</span></label><br>"+
		// 		"<input type='checkbox' class='' name='isElective[]'/>"+ 
		// 	"</div>"+
		// "</div>"+
    "</div>";
        }
        
	return div;
}


$("#addButton").on("click",function()
{
    // console.log(elemToAdd());
    let newElem = elemToAdd();
    
    //$('#togglehere').append($newElem);
    var elemParentNode = this.parentNode.previousElementSibling;
    var container_div = document.createElement("div");
    container_div.classList.add("container");
    container_div.innerHTML = newElem;
    elemParentNode.appendChild(container_div);
    // container_div = null;
    // newElem   = "";
	// $cloneNode = elemParentNode.cloneNode(true);
    // console.log($cloneNode);
    // var container = document.querySelectorAll('.container');
    // console.log(container);
    // container.forEach((element, index) => {
    // var toggle = element.lastElementChild.lastElementChild.getElementsByClassName('isElective');
    // toggle.forEach((element, index) => {

    // });
        
    // });
    
});

$(document).on("click",".remove-item-form",function(e)
{
	let parentNode = this.parentNode;
	let grandParentNode = this.parentNode.parentNode;
	grandParentNode.removeChild(parentNode);
})
</script>