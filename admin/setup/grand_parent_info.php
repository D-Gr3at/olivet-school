<?php
include_once("../libs/dbfunctions.php");
$dbobject = new dbobject();
$sql = "SELECT DISTINCT(State) as state,stateid FROM lga WHERE stateid IN (SELECT state_id FROM states_with_town) order by State";
$states = $dbobject->db_query($sql);

if(isset($_REQUEST['surname_id']))
{
//    var_dump($_SESSION);
    $operation   = 'edit';
    $id          = $_REQUEST['surname_id'];
    $fam_id          = $_REQUEST['fam_id'];
    $parent_fam_id    = $_REQUEST['parent_fam_id'];
    $sql_surname = "SELECT surname,id FROM family_name WHERE id = '$id' ";
    $surname     = $dbobject->db_query($sql_surname);
    
    $sql_family_unit = "SELECT family_id,family_head FROM family WHERE family_name = '$id' AND family_id NOT IN('$fam_id','$parent_fam_id') ";
    $family_unit     = $dbobject->db_query($sql_family_unit);
}else
{
    $operation = 'new';
}
// In the event there is an edit on the family's parent information, 
// we will alert the user that a parent already exist 
// if we find any
$sql = "SELECT grand_parent_id FROM family WHERE family_id = '$fam_id' LIMIT 1";
$result = $dbobject->db_query($sql);
if($result[0]['grand_parent_id'] != "")
{
    echo "<h3>This family already has a parent</h3>";
    exit();
}
$fam_head = $dbobject->getitemlabel('family','family_id',$fam_id,'family_head');
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
<div class="modal-header">
    <h4 class="modal-title" style="font-weight:bold">Enter grand parent for <?php echo $fam_head; ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3">
    <form id="form31" onsubmit="return false">
      
       <input type="hidden" name="op" value="Family.updateFamilyGrandParent">
       <input type="hidden" name="operation" value="<?php echo $operation; ?>" >
       <input type="hidden" name="family_id" value="<?php echo $fam_id; ?>" >
       <div class="row">
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Surname</label>
                    <select name="family_name" id="surname" class="form-control">
                        <?php
                            foreach($surname as $row)
                            {
                                echo "<option value='".$row[id]."'>".$row[surname]."</option>";
                            }
                        ?>
                    </select>
                </div>
           </div>
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Family Head</label>
                    <select name="grand_parent_id" id="grand_parent_id"  class="form-control">
                        <?php
                        foreach($family_unit as $row)
                        {
                            echo "<option value='".$row['family_id']."'>".$row['family_head']."</option>";
                        }
                        ?>
                    </select>
                </div>
           </div>
       </div>
        <button id="save_facility" onclick="saveRecord()" class="btn btn-info">Submit</button>
        <br>
        <label for="proceed"><input type="checkbox" checked id="proceed" ><small> procced to <b style="color:red">'grandparent setup'</b> after saving</small></label>
    </form>
    <p><small style="color:red;font-weight: bold;"><span style="color:#000"> NOTE:</span> Close this form and click on "Create Family" button if you can't find <?php echo $fam_head ?>'s grand parent from the dropdown.</small></p>
</div>
<script>
    function saveRecord()
    {
        $("#save_facility").text("Loading......");
        var dd = $("#form31").serialize();
        $.post("utilities.php",dd,function(re)
        {
            $("#save_facility").text("Save");
            console.log(re);
            if(re.response_code == 0)
                {
                    alert(re.response_message)
                    if($("#proceed").is(":checked"))
                        {
                            var surname = $("#surname").val();
                            var parent_id = "<?php echo $parent_fam_id; ?>";
                            var grand_parent_id = $("#grand_parent_id").val();
                            var fam_id = "<?php echo $fam_id ?>";
                            getpage('setup/great_grand_parent_info.php?surname_id='+surname+'&parent_fam_id='+parent_id+'&fam_id='+fam_id+'&grand_parent_fam_id='+grand_parent_id,'modal_div');
                        }
                }
            else
                alert(re.response_message)
        },'json')
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
    function getTowns(el)
    {
        $("#church_id").html("<option>Loading Town</option>");
        var ste = $("#church_state").val();
        $.post("utilities.php",{op:'Helper.getTownSelect',filter:"lga",search:el},function(re){
//            $("#lga-fd").empty();
            console.log(re);
            $("#church_id").html(re.response_message);
            
        },'json');
    }
            
  
</script>