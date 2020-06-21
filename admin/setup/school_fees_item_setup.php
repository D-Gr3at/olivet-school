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
    $school_item_id  = $_REQUEST['school_item_id'];
    $school_fee_item  = $dbobject->db_query("SELECT * FROM school_fees_item_setup WHERE school_item_id = ".$school_item_id);
    $school_fee_item = $school_fee_item[0];
    $operation = 'edit';
}
else
{
    $operation = 'new';
}
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
    <h4 class="modal-title" style="font-weight:bold"><?php echo ($operation=="edit")?"Edit ":""; ?>School Fees Item Setup<div><small style="font-size:12px">All asterik fields are compulsory</small></div></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3 ">
    <form id="form1" onsubmit="return false">
       <input type="hidden" name="op" value="SchoolFeesItem.saveItem"/>
       <input type="hidden" name="operation" value="<?php echo $operation; ?>"/>
       <?php if($operation != "new"){?>
       <input type="hidden" name="item_id" value="<?php echo $school_item_id; ?>"/>
       <?php }?>
       <input type="hidden" name="posted_by" id="posted_by" value="<?php echo $_SESSION['firstname_sess'].' '.$_SESSION['lastname_sess']; ?>"/>
        <fieldset class="form-group">
            <legend>School Fees Item Info</legend>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                            <label class="form-label">School Fees Item Name<span class="asterik">*</span></label>
                            <input type="text" name="item_name[]" value="<?php echo $school_fee_item['item_name'] ?>" class="form-control text-capitalize">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">Status<span class="asterik">*</span></label>
                        <select class="form-control" name="status[]" id="status">
                            <option value="1" <?php echo ($school_fee_item['status'] == '1')?"selected":""; ?>>Active</option>
                            <option value="0" <?php echo ($school_fee_item['status'] == '0')?"selected":""; ?> >Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <?php if($operation != "edit"){?>
                <div class="float-right">
                    <input type="button" id="addButton" class="btn btn-sm btn-primary" value="add Item">
                </div>
            <?php }?>
        </fieldset>
        <div class="row">
            <div class="col-sm-12">
                <div id="server_mssg"></div>
            </div>
        </div>
        <input type="submit" id="save_item" class="btn btn-primary" value="Submit"/>
    </form>
</div>
</div>
<script>
    $(document).ready(function() {
         // on form submit
        $("#form1").on('submit', function() {
            $("#save_item").val("Loading......");

            var dd = $("#form1").serialize();
            console.log(dd);

            $.post("utilities.php",dd,function(re){
                // console.log(re);
                $("#save_item").val("Save");
                if(re.response_code == 0){
                    $("#server_mssg").text(re.response_message);
                    $("#server_mssg").css({'color':'green','font-weight':'bold'});
                    getpage('school_fees_item_list.php','page');
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


    function elemToAdd(){
        var div;
        for(var i = 0; i <= 100; ++i) {
        div = 
            `<span class='remove-item-form fa fa-times color-danger' style='cursor:pointer;right: 0;margin-right: 50px;font-weight: bolder;'></span>
            <div class = "row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">School Fees Item Name<span class="asterik">*</span></label>
                        <input type="text" name="item_name[]" class="form-control text-capitalize">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">Status<span class="asterik">*</span></label>
                        <select class="form-control" name="status[]" id="status">
                            <option value="1" <?php echo ($faculty[0]['status'] == 1)?"selected":""; ?>>Active</option>
                            <option value="0" <?php echo ($faculty[0]['status'] == 0)?"selected":""; ?> >Inactive</option>
                        </select>
                    </div>
                </div>
            </div>`;
        }
        
	return div;
}


$("#addButton").on("click",function()
{
    let newElem = elemToAdd();
    // let parser = new DOMParser();
    // elementToAdd = parser.parseFromString(newElem, 'text/html');
    var elemParentNode = this.parentNode.previousElementSibling;
    let div = document.createElement("div");
    div.innerHTML = newElem;
    // console.log(div);
    
    let container = div.classList.add("container");
    elemParentNode.appendChild(div);
    // elemParentNode.appendChild(elementToAdd.body.firstChild);
    // elemParentNode.appendChild(elementToAdd.body.childNodes[1]);
    // elemParentNode.appendChild(elementToAdd.body.childNodes[2]);
});

$(document).on("click",".remove-item-form",function(e)
{
	let parentNode = this.parentNode;
	let grandParentNode = this.parentNode.parentNode;
	grandParentNode.removeChild(parentNode);
})
</script>