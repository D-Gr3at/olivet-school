<?php
include_once("../libs/dbfunctions.php");
$dbobject = new dbobject();
$sql = "SELECT DISTINCT(State) as state,stateid FROM lga WHERE stateid IN (SELECT state_id FROM states_with_town) order by State";
$states = $dbobject->db_query($sql);

$sql = "Select * FROM family_name WHERE town_id = '$_SESSION[town_id_sess]' AND kindred_id = '$_SESSION[kindred_id_sess]' order by surname";
$surnames = $dbobject->db_query($sql);

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
    <h4 class="modal-title" style="font-weight:bold">Family Setup</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3 ">
    <form action="" id="family_info">
           <input type="hidden" name="op" value="Family.saveFamily">
           <input type="hidden" name="operation" value="new">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                            <label class="form-label">Ancestoral name/Surname</label>
                            <select name="family_name" id="surname" class="form-control">
                            <option value="">:: Select Surname ::</option>
                                <?php
                                foreach($surnames as $row)
                                {
                                    echo "<option value='".$row[id]."'>".$row[surname]."</option>";
                                }
                                ?>
                            </select>
                        </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                       <div>
                           <label for="" class="form-label">Is family known by other surname</label>
                       </div>

                           <label style="cursor:pointer" for="no"><input onclick="alias_name(this.value)" type="radio" checked id="no"  value="no" name="alias" class="">&nbsp;No</label>&nbsp;&nbsp;
                           <label style="cursor:pointer" for="yes"><input onclick="alias_name(this.value)" value="yes" type="radio" id="yes" name="alias" >&nbsp;Yes</label>&nbsp;
                            <input style="display:none" id="alias_text" type="text" name="alias_value" placeholder="Enter the surname chosen.." class="form-control" style="display:inline !important; width:auto">
                        </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="" class="form-label">Enter Husband Name</label>
                            <input type="text" name="family_head" class="form-control">
                        </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="" class="form-label">Enter Wife Name</label>
                        <input type="text" name="wife_name" class="form-control">
                        </div>
                </div>
            </div>
            <div class="row" >
                <div class="col-sm-6" ><a href="javascript:void(0)" onclick="saveRecord()" id="save_facility" class="btn btn-info sw-btn-next mb-1"> Save and Continue</a> <label for="proceed"><input type="checkbox" checked id="proceed" ><small> procced to <b style="color:red">'parent setup'</b> after saving</small></label> </div>
                <div class="col-sm-6" style="text-align:right" ></div>

            </div>
        </form>
</div>
<script>
    function saveRecord()
    {
        $("#save_facility").text("Loading......");
        var dd = $("#family_info").serialize();
        console.log(dd)
        $.post("utilities.php",dd,function(re)
        {
            $("#save_facility").text("Save");
            console.log(re);
            if(re.response_code == 0)
                {
                    alert(re.response_message);
                    if($("#proceed").is(":checked"))
                        {
                            var surname = $("#surname").val();
                            var fam_id = re.data.fam_id;
                            setTimeout(()=>{
//                                $('#defaultModalPrimary').modal('hide');
                               
                                getModal('setup/parent_info.php?surname_id='+surname+'&fam_id='+fam_id,'modal_div');
                                 $('#defaultModalPrimary').modal('show');
                            },1000);
                            
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
            
    function alias_name(val)
    {
        if(val == "no")
           {
            $("#alias_text").hide()
           }else{
                $("#alias_text").show()
               
                $("#alias_text").css("display","inline");
                $("#alias_text").css("width","auto");
               
           }
    }
</script>