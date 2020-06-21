<?php
include_once("../libs/dbfunctions.php");
$dbobject = new dbobject();
$sql = "SELECT DISTINCT(State) as state,stateid FROM lga WHERE stateid IN (SELECT state_id FROM states_with_town) order by State";
$states = $dbobject->db_query($sql);

if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'edit')
{
    $operation  = 'edit';
    $id  = $_REQUEST['id'];
    $sql_collection_type = "SELECT * FROM collection_type WHERE id = '$id'";
    $collection_type     = $dbobject->db_query($sql_collection_type);
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
<div class="modal-header">
    <h4 class="modal-title" style="font-weight:bold">Village Setup</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3 ">
    <form id="form1" onsubmit="return false">
       <input type="hidden" name="op" value="Village.saveVillage">
       <input type="hidden" name="operation" value="<?php echo $operation; ?>">
       <input type="hidden" name="id" value="<?php echo $id; ?>">
       <div class="row">
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Village Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo $collection_type[0]['name']; ?>" placeholder="">
                </div>
           </div>
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">State</label>
                    <select name="" id="church_state" onchange="fetchLga(this.value)" class="form-control">
                       <option value="">:: SELECT STATE ::</option>
                        <?php
                        foreach($states as $row)
                        {
                            $selected = ($church[0]['state'] == $row['stateid'])?"selected":"";
                            echo "<option ".$selected." value='".$row['stateid']."'>".$row['state']."</option>";
                        }
                        ?>
                    </select>
                </div>
           </div>
       </div>
       <div class="row">
           
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label" >Local Government Area</label>
                    <select name="" onchange="getTowns(this.value)" id="lga-fd" class="form-control" >
                        <?php 
                        if($operation == "edit")
                        {
                            echo "<option value='".$church[0]['lga']."'>".$dbobject->getitemlabel('lga','Lgaid',$church[0]['lga'],'Lga')."</option>";
                        } ?>
                    </select>
                </div>
           </div>
           <div class="col-sm-6">
               <div class="form-group">
                <label for="">User Town<span class="asterik">*</span></label>
                <select name="town_id" id="church_id" class="form-control">
<!--                   <option value=''>::SELECT A CHURCH::</option>-->
                    <?php
                    if($operation == "edit")
                    {
                        $t_lga  = $user[0][lga];
                        $sql_town = "SELECT * FROM towns WHERE lga = '$t_lga'";
                        $towns = $dbobject->db_query($sql_town);
                        foreach($towns as $row)
                        {
                            $selected = ($user[0]['town'] == $row['id'])?"selected":"";
                            echo "<option $selected value='".$row['id']."'>".$row['town_name']."</option>";
                        }
                    }
                            
                    ?>
                </select>
                </div>
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
        $.post("utilities.php",dd,function(re)
        {
            $("#save_facility").text("Save");
            console.log(re);
            if(re.response_code == 0)
                {
                    alert(re.response_message);
                    getpage('village.php','page');
//                    setTimeout(()=>{
//                        $('#defaultModalPrimary').modal('hide');
//                    },1000)
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