<?php
include_once("../libs/dbfunctions.php");
$dbobject = new dbobject();
$sql = "SELECT id,name FROM church_type WHERE part_of_split = '1'";
$c_type = $dbobject->db_query($sql);
//$ss = "SELECT role_id,role_name FROM role WHERE role_id = '003'";
//$ee = $dbobject->db_query($ss);
//$c_type[] = array('id'=>$ee[0]['role_id'],'name'=>$ee[0]['role_name']);
if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'edit')
{
    $operation  = 'edit';
    $code  = $_REQUEST['code'];
    $sql_splitting = "SELECT * FROM splitting WHERE code = '$code'";
    $splitting     = $dbobject->db_query($sql_splitting);
//    var_dump($splitting);
}else
{
    $operation = 'new';
}
?>
 <link rel="stylesheet" href="codebase/dhtmlxcalendar.css" />
<script src="codebase/dhtmlxcalendar.js"></script>
<script>
    doOnLoad();
    var myCalendar;
function doOnLoad()
{
   myCalendar = new dhtmlXCalendarObject(["start_date"]);
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
        font-size: 14px;
        padding: 5px;
        font-weight: bold;
    }
</style>
<div class="modal-header">
    <h4 class="modal-title" style="font-weight:bold">School Setup</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3 ">
    <form id="form1" onsubmit="return false">
       <input type="hidden" name="op" value="Split.saveSplit">
       <input type="hidden" name="operation" value="<?php echo $operation; ?>">
       <input type="hidden" name="code" value="<?php echo $code; ?>">
       <div class="row">
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">School Name</label>
                    <input type="text" name="min_amt" class="form-control" value="<?php echo $splitting[0]['min_amt']; ?>" placeholder="">
                </div>
           </div>
           <div class="col-sm-6">
                <div class="form-group">
                    <label for="">School Address</label>
                    <div class="input-group">
                        <input type="text" id="max_amt" value="<?php echo $splitting[0]['max_amt']; ?>" name="max_amt" class="form-control" placeholder="">
                        
                    </div>
                </div>
           </div>
       </div>
       <div class="row">
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Motto</label>
                    <textarea class="form-control" name="vision" id="" cols="20" rows="2"></textarea>
                </div>
           </div>
           <div class="col-sm-6">
                <div class="form-group">
                    <label for="">Vision</label>
                    <div class="input-group">
                        <textarea class="form-control" name="vision" id="" cols="20" rows="2"></textarea>
                        <!-- <input type="text" id="max_amt" value="<?php echo $splitting[0]['max_amt']; ?>" name="max_amt" class="form-control" placeholder=""> -->
                        
                    </div>
                </div>
           </div>
       </div>
       <div class="row">
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Address</label>
                    <input type="text" class="form-control" name="address" id="address" cols="20" rows="2" />
                </div>
           </div>
           <div class="col-sm-6">
                <div class="form-group">
                    <label for="">School Type</label>
                    <div class="input-group">
                        <select name="school_type" id="school_type" class="form-control">
                            <option value=""></option>
                        </select>
                        
                    </div>
                </div>
           </div>
       </div>
       <div class="row">
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Year of Establishment</label>
                    <input type="text" autocomplete="off" class="form-control" name="year" id="start_date"   />
                </div>
           </div>
           <div class="col-sm-6">
                <div class="form-group">
                    <label for="">Founder</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="founder" id="founder"  />
                    </div>
                </div>
           </div>
       </div>
       
       <div id="err"></div>
        <button id="save_facility" onclick="saveRecord()" class="btn btn-primary">Submit</button>
    </form>
</div>
<script>
    $("#infinite").click(function(){
        if($("#infinite").is(":checked"))
           {
                $("#max_amt").attr('type','text');
                $("#max_amt").attr('disabled',true);
                $("#max_amt").val('infinite amount');
           }else{
               $("#max_amt").attr('type','number');
               $("#max_amt").attr('disabled',false);
                $("#max_amt").val('');
           }
    })
    function saveRecord()
    {
        $("#save_facility").text("Loading......");
        var dd = $("#form1").serialize();
        $.post("utilities.php",dd,function(re)
        {
            $("#save_facility").text("Save");
            console.log(re);
            if(re.response_code == 0)
                {
                    $("#err").css('color','green')
                    $("#err").text(re.response_message);
                    getpage('splitting_list.php','page');
                    setTimeout(()=>{
                        $('#defaultModalPrimary').modal('hide');
                    },1000)
                }
            else
                {
                    $("#err").css('color','red')
                    $("#err").text(re.response_message)
                }
                
        },'json')
    }
    
            
  
</script>