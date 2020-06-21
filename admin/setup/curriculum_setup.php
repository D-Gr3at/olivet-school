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
    $curriculum_id  = $_REQUEST['curriculum_id'];
    $curriculum      = $dbobject->db_query("SELECT * FROM curriculum_setup_tbl WHERE curriculum_id=".$curriculum_id."");
    $department = $dbobject->db_query("SELECT * FROM department_setup_tbl WHERE dapartment_id=".$curriculum[0]['department_id']."");
    $department_option = $dbobject->db_query("SELECT * FROM programme_setup WHERE programme_id=".$curriculum[0]['programme_id']."");
    $faculty = $dbobject->db_query("SELECT * FROM faculty_settup WHERE faculty_id=".$department[0]['faculty_code']."");
    $programme_course_ids = $dbobject->db_query("SELECT programme_course_id FROM programme_course_setup_tbl WHERE programme_id = ".$curriculum[0]["programme_id"]." AND level = ".$curriculum[0]["level"]);
    $programme_courses = array();
    foreach($programme_course_ids as $key => $value){
        $programme_course = $dbobject->db_query("SELECT * FROM course_setup_tbl WHERE programme_course_fk = ".$value["programme_course_id"]);
        $programme_courses = array_merge($programme_courses, $programme_course);
    }

    $curriculum_courses = $dbobject->db_query("SELECT * FROM curriculum_courses_tbl WHERE curriculum_setup_fk = ".$curriculum_id);
    $operation = 'edit';

    $query_select_id = "SELECT course_id from course_setup_tbl  ORDER BY course_id DESC LIMIT 1";
    $run_query_select_id = $dbobject->db_query($query_select_id);
    $course_setup_id = $run_query_select_id[0]["course_id"];
    $course_id = $course_setup_id + 1;
    $date = substr($curriculum[0]['closure_date'], 0, 10);
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
$faculty_names_sql = "SELECT faculty_id, faculty_name FROM faculty_settup";
$faculty_names = $dbobject->db_query($faculty_names_sql);

$department_names = $dbobject->db_query("SELECT dapartment_id, department_name FROM department_setup_tbl");
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
    <h4 class="modal-title" style="font-weight:bold"><?php echo ($operation=="edit")?"Edit ":""; ?>Curriculum Setup<div><small style="font-size:12px">All asterik fields are compulsory</small></div></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3 ">
    <form id="form1" onsubmit="return false">
       <input type="hidden" name="op" value="Curriculum.saveCurriculum"/>
       <input type="hidden" name="operation" value="<?php echo $operation; ?>"/>
       <input type="hidden" name="session_id" value="<?php echo $session_id['session_id']; ?>"/>
       <input type="hidden" name="curriculum_id" id="curriculum_id" value="<?= $curriculum_id; ?>"/>
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
                                    $department_options = $dbobject->db_query("SELECT * FROM programme_setup WHERE department_id =".$department_id."");
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
                            echo "<select class='form-control text-uppercase' name='department_option' id='department_option'>
                            <option value=''>::NO OPTION TO SELECT::</option>";
                            foreach($department_options as $key => $value){
                                $option_name = $value['programme_name'];
                                $option_id = $value['programme_id'];
                                if($department_option[0]['programme_name'] == $option_name){
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
                        <select class='form-control text-uppercase' name='semester' id='semester'>
                            <option value='' selected='selected'>::SELECT SEMESTER::</option>
                            <option value="1" <?php echo ($curriculum[0]['semester'] == 1)?"selected":""; ?>>First Semester</option>
                            <option value="2" <?php echo ($curriculum[0]['semester'] == 2)?"selected":""; ?>>Second Semester</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="form-label">Level<span class="asterik">*</span></label>
                        <select class='form-control' name='level' id='level'>
                            <option value=''>::SELECT LEVEL::</option>
                            <?php
                                for($year = 100; $year <= 600; $year+=100){
                                    if($curriculum[0]['level'] == $year){
                                        echo "<option value='".$year."' selected>".$year."</option>";
                                    }else{
                                        echo "<option value='".$year."'>".$year."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="form-label">Course Registration Closure Date<span class="asterik">*</span></label>
                        <input type="date" id="start" name="closure_date" value="<?php echo $date; ?>" min="2000-01-01" class="form-control">
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset class="form-group">
            <legend>Course Info</legend>
            <div class="row">
                <?php if($curriculum_courses != NULL){ 
                    foreach($curriculum_courses as $k => $v){?>
                <div class="container">
                    <span class='remove-item-form fa fa-times color-danger' style='cursor:pointer;right: 0;margin-right: 50px;font-weight: bolder;'></span>
                    <div class="row">
                        <input type="hidden" name="course_id[]" value="<?php echo $v['curriculum_course_id']; ?>" class="form-control"/>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="form-label">Department<span class="asterik">*</span></label>
                                <?php
                                    echo "<select class='form-control text-uppercase course_department' name='selected_department[]'>
                                    <option value=''>::SELECT A DEPARTMENT::</option>";
                                    foreach($department_names as $key => $value){
                                        if(strtoupper($department[0]["department_name"]) == strtoupper($value["department_name"]) ){
                                            echo "<option value='".$value['dapartment_id']."' selected>".$value['department_name']."</option>";
                                            $programmes = $dbobject->db_query("SELECT * FROM programme_setup WHERE department_id =".$value['dapartment_id']."");
                                        }else{
                                            echo "<option value='".$value['dapartment_id']."'>".$value['department_name']."</option>";
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
                                    echo "<select class='form-control text-uppercase programme' name='selected_programme[]'>
                                    <option value=''>::NO PROGRAMME TO SELECT::</option>";
                                    foreach($programmes as $key => $value){
                                        if(strtoupper($department_option[0]["programme_name"]) == strtoupper($value["programme_name"]) ){
                                            echo "<option value='".$value['programme_id']."' selected>".$value['programme_name']."</option>";
                                        }else{
                                            echo "<option value='".$value['programme_id']."'>".$value['programme_name']."</option>";
                                        }
                                    }
                                    echo "</select>";
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="form-label">Course Code<span class="asterik">*</span></label>
                                <?php
                                    echo "<select class='form-control text-uppercase course_code_class' name='selected_course_code[]'>
                                    <option value=''>::NO COURSE CODE::</option>";
                                    foreach($programme_courses as $key => $values){
                                        if(strtoupper($v["selected_course_id"]) == strtoupper($values["course_id"]) ){
                                            $code = $dbobject->getitemlabel('course_setup_tbl', 'course_id', $v["selected_course_id"], 'course_code');
                                            echo "<option value='".$values['course_id']."' selected='selected'>".$values["course_code"]."</option>";
                                        }else{
                                            // $code = $dbobject->getitemlabel('course_setup_tbl', 'course_id', $v["selected_course_id"], 'course_code');
                                            echo "<option value='".$values['course_id']."'>".$values["course_code"]."</option>";
                                        }
                                    }
                                    echo "</select>";
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <label class="form-label">Unit<span class="asterik">*</span></label>
                                <?php
                                    $unit = $dbobject->getitemlabel('course_setup_tbl', 'course_id', $v["selected_course_id"], 'course_unit');
                                 ?>
                                <input type="text" name="course_unit[]" value="<?php echo $unit; ?>" class="form-control course_unit_class" readonly/>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group" id="extra_input">
                                <label class="form-label">Is Elective?<span class="asterik">*</span></label>
                                <input type="checkbox" class="" name="isElective[]" <?php echo ($v['is_elective'] == '1')?"checked":""?>/> 
                           </div>
                        </div>
                    </div>
                </div>
                <?php }
                }else{?>
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label">Department<span class="asterik">*</span></label>
                                    <?php
                                        echo "<select class='form-control text-uppercase course_department' name='selected_department[]' id='selected_department'>
                                        <option value=''>::SELECT A DEPARTMENT::</option>";
                                        foreach($department_names as $key => $value){
                                            echo "<option value='".$value['dapartment_id']."'>".$value['department_name']."</option>";
                                        }
                                        echo "</select>";
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label">Programme<span class="asterik">*</span></label>
                                    <?php
                                        echo "<select class='form-control text-uppercase programme' name='selected_programme[]' id='selected_programme'>
                                        <option value=''>::NO PROGRAMME TO SELECT::</option>";

                                        echo "</select>";
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="form-label">Course Code<span class="asterik">*</span></label>
                                    <?php
                                        echo "<select class='form-control text-uppercase course_code_class' name='selected_course_code[]' id='selected_course_code'>
                                        <option value=''>::NO COURSE CODE::</option>";

                                        echo "</select>";
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label class="form-label">Unit<span class="asterik">*</span></label>
                                    <input type="text" name="course_unit[]" id="course_unit" class="form-control course_unit_class" readonly/>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group" id="extra_input">
                                    <label class="form-label">Elective?<span class="asterik">*</span></label>
                                    <input type="checkbox" class="" name="isElective[]"/> 
                            </div>
                            </div>
                        </div>
                    </div>
                <?php }?>
            </div>
            <div class="float-right">
                <input type="button" id="addButton" class="btn btn-sm btn-primary" value="add course">
            </div>
        </fieldset>
        <div class="row">
            <div class="col-sm-12">
                <div id="server_mssg"></div>
            </div>
        </div>
        <input type="submit" id="save_curriculum" class="btn btn-primary" value="Submit"/>
    </form>
</div>
</div>
<script>
    $(document).ready(function() {
         // on form submit
        $("#form1").on('submit', function() {
            $("#save_curriculum").text("Loading......");
            // to each unchecked checkbox
            $('input[type="checkbox"]').each(function (e, v) {
                // console.log(e, v);
                
                $(this).empty();
                // set value 0 and check it
                if($(this).prop("checked") == true){
                    $(this).val(1);
                }
                else if($(this).prop("checked") == false){
                    $(this).empty();
                    $(this).append('<input type="hidden" class="" name="isElective[]" value="0"/>');
                }
            });

            var dd = $("#form1").serialize();
            // console.log(dd);

            $.post("utilities.php",dd,function(re){
                // console.log(re);
                $("#save_curriculum").text("Save");
                if(re.response_code == 0){
                    $("#server_mssg").text(re.response_message);
                    $("#server_mssg").css({'color':'green','font-weight':'bold'});
                    getpage('curriculum_setup_list.php','page');
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

    function check_bank_det(el)
    {
        if($("#yes").is(':checked')){
            $("#bank_details").slideDown()
        }else if($("#no").is(':checked'))
         {
            $("#bank_details").slideUp()
         }
    }
    

    function elemToAdd(){
        var div;
        for(var i = 0; i <= 100; ++i) {
        div = 
		"<span class='remove-item-form fa fa-times color-danger' style='cursor:pointer;right: 0;margin-right: 50px;font-weight: bolder;'></span>"+
        "<div class='row'>"+
            "<div class='col-sm-4'>"+
                "<div class='form-group'>"+
                    "<label class='form-label'>Department<span class='asterik'>*</span></label>"+
                        "<select class='form-control text-uppercase course_department' name='selected_department[]' id='selected_department'>" +
                        "<option value=''>::SELECT A DEPARTMENT::</option>"+
                        "<?php 
                        foreach($department_names as $key => $value){
                            echo "<option value='".$value['dapartment_id']."'>".$value['department_name']."</option>";
                        } ?>"+
                        "</select>"+                    
                "</div>"+
            "</div>"+
            "<div class='col-sm-4'>"+
                "<div class='form-group'>"+
                    "<label class='form-label'>Programme<span class='asterik'>*</span></label>"+
                    "<select class='form-control text-uppercase programme' name='selected_programme[]' id='selected_programme'>"+
                        "<option value=''>::NO PROGRAMME TO SELECT::</option>"+
                    "</select>"+
                "</div>"+
            "</div>"+
            "<div class='col-sm-2'>"+
                "<div class='form-group'>"+
                    "<label class='form-label'>Course Code<span class='asterik'>*</span></label>"+
                    "<select class='form-control text-uppercase course_code_class' name='selected_course_code[]' id='selected_course_code'>"+
                        "<option value=''>::NO COURSE CODE::</option>"+
                    "</select>"+
                "</div>"+
            "</div>"+
            "<div class='col-sm-1'>"+
                "<div class='form-group'>"+
                    "<label class='form-label'>Unit<span class='asterik'>*</span></label>"+
                    "<input type='text' name='course_unit[]' id='course_unit' class='form-control course_unit_class' readonly/>"+
                "</div>"+
            "</div>"+
            "<div class='col-sm-1'>"+
                "<div class='form-group' id='extra_input'>"+
                    "<label class='form-label'>Elective?<span class='asterik'>*</span></label>"+
                    "<input type='checkbox' class='' name='isElective[]'/>"+ 
                "</div>"+
            "</div>"+
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
});

$(document).on("click",".remove-item-form",function(e)
{
	let parentNode = this.parentNode;
	let grandParentNode = this.parentNode.parentNode;
	grandParentNode.removeChild(parentNode);
})
</script>