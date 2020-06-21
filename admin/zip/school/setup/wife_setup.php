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
    <h4 class="modal-title" style="font-weight:bold">Wife Setup</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3 ">
           
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                            <label class="form-label">is wife from <?php echo $dbobject->getitemlabel("towns","id",$_SESSION['town_id_sess'],"town_name") ?> ?</label>
                            <div>
                                <label for="yes" class="mr-2"><input name="wife_origin" onclick="display_indigene('yes')" value="yes" type="radio" checked id="yes"> Yes</label>
                                <label for="no"><input name="wife_origin" onclick="display_indigene('no')" value="no" type="radio" id="no"> No</label>
                            </div>
                    </div>
                </div>
            </div>
            <div id="indigene" >
              <form action="" id="family_info">
                    <input type="hidden" name="op" value="Family.addWifeIndigene">
                    <input type="hidden" name="operation" value="new">
                    <input type="hidden" name="family_id" value="<?php echo $_REQUEST['family_id']; ?>">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="" class="form-label">Select Village</label>
                                <select name="village_id" onchange="getKindred(this.value,'kindred1')" id="villages1" class="form-control">
                                    <?php
                                        $sql = "SELECT * FROM village WHERE town_id = '$_SESSION[town_id_sess]'";
                                        $result = $dbobject->db_query($sql);
                                        echo "<option value=''>:: SELECT VILLAGE::</option>";
                                        foreach($result as $row)
                                        {
                                            echo "<option value='".$row['village_id']."'>".$row['name']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="" class="form-label">Select Kindred</label>
                                <select name="kindred_id" onchange="getFamilySurname(this.value,'surname1')" id="kindred1" class="form-control"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="" class="form-label">Select Surname</label>
                                    <select name="surname1" onchange="getFamily(this.value,'fam_head')" id="surname1" class="form-control"></select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="" class="form-label">Select Family head</label>
                                <select name="family_id_wife" id="fam_head" class="form-control"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="" class="form-label">Enter Wife Name</label>
                                <input type="text" name="wife_name" class="form-control">
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <a href="javascript:void(0)" onclick="saveRecord('family_info')" class="btn btn-block btn-info">Save Record</a>
                        </div>
                        <div class="col-sm-6"></div>
                    </div>
                </form>
            </div>
            <div id="non_indigene" style="display:none">
               <form action="" id="family_info2">
                    <input type="hidden" name="op" value="Family.addWifeNonIndigene">
                    <input type="hidden" name="operation" value="new">
                    <div class="row">
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
                   </div>
                   <div class="row">
                        <div class="col-sm-6">
                           <div class="form-group">
                            <label for="">Select Town<span class="asterik">*</span></label>
                            <select name="town_id" onchange="getvillages(this.value)" id="town" class="form-control">
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
                        <div class="col-sm-6">
                            <div class="form-group">
                               <div>
                                  <label for="" class="form-label">Select Village</label>
                                   <label for="find_village" style="float:right"><input type="checkbox" onclick="switch_village()" id="find_village" ><small style="color:red">Can't find village?</small></label>
                               </div>

                                    <select name="villages" onchange="getKindred(this.value,'kindred')" id="villages" class="form-control">

                                    </select>
                                    <input type="text" style="display:none" class="form-control" id="villages_text" placeholder="Enter the village name" >

                            </div>

                        </div>
                   </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="" class="form-label">Select Kindred</label>
                                <select id="kindred" onchange="getFamilySurname(this.value,'surname')" class="form-control" name="kindred">

                                </select>
                                <input type="text" style="display:none" class="form-control" id="kindred_text" placeholder="Enter the kindred name" >
                                </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="" class="form-label">Select family surname</label>
                                <select name="family_head" onchange="getFamily(this.value,'father_name')" id="surname" class="form-control">

                                </select>
                                <input type="text" style="display:none" class="form-control" id="surname_text" placeholder="Enter the family name" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="" class="form-label">Select father of wife</label>
                                <select name="father_name" id="father_name" class="form-control"></select>
                                <input type="text" style="display:none" class="form-control" id="father_name_text" placeholder="Enter the father name of wife" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <a href="javascript:void(0)" onclick="saveRecord('family_info2')" class="btn btn-block btn-info">Save Record</a>
                        </div>
                        <div class="col-sm-6"></div>
                    </div>
                </form>
            </div>
            
</div>
<script>
    function saveRecord(form_id)
    {
        $("#save_facility").text("Loading......");
        var dd = $("#"+form_id).serialize();
        console.log(dd)
        $.post("utilities.php",dd,function(re)
        {
            $("#save_facility").text("Save");
            console.log(re);
            if(re.response_code == 0)
                {
                    alert(re.response_message);
                }
            else
                alert(re.response_message)
        },'json')
    }
      function fetchLga(el)
    {
        $("#lga-fd").html("<option>Loading Lga</option>");
        $.post("utilities.php",{op:'Helper.getLga',state:el},function(re){
//            $("#lga-fd").empty();
            console.log(re);
            $("#lga-fd").html(re.state);
//            $("#church_id").html(re.church);
            
        },'json');
    }
    function getTowns(el)
    {
        $("#town").html("<option>Loading Town</option>");
        var ste = $("#church_state").val();
        $.post("utilities.php",{op:'Helper.getTownSelect',filter:"lga",search:el},function(re){
//            $("#lga-fd").empty();
            console.log(re);
            $("#town").html(re.response_message);
            
        },'json');
    }
    
       function display_indigene(el)
    {
        if($("#"+el).val() == "yes")
           {
                $("#indigene").show();
                $("#non_indigene").hide();
           }
        else
           {
               $("#indigene").hide();
                $("#non_indigene").show();
           }
    }
    function getvillages(el)
    {
        $("#villages").html("<option>Loading Villages</option>");
        $.post("utilities.php",{op:'Helper.getVillages',town_id:el},function(re){
            console.log(re);
            if(re.response_code == 510)
                {
                    switcher("on");
                    $("#find_village").attr('checked',true);
                }else{
                    switcher("off");
                    $("#find_village").attr('checked',false);
                }
                
            $("#villages").html(re.response_message);
            
        },'json');
    }
    function getKindred(el,id)
    {
        $("#"+id).html("<option>Loading Kindred</option>");
        $.post("utilities.php",{op:'Helper.getkindred',village_id:el},function(re){
            console.log(re);
            $("#"+id).html(re.response_message);
            
        },'json');
    }
    function getFamilySurname(el,id)
    {
        $("#"+id).html("<option>Loading Surname</option>");
        $.post("utilities.php",{op:'Helper.getFamilySurname',kindred_id:el},function(re){
            console.log(re);
            $("#"+id).html(re.response_message);
            
        },'json');
    }function getFamily(el,id)
    {
        $("#"+id).html("<option>Loading Families</option>");
        $.post("utilities.php",{op:'Helper.getFamily',surname:el},function(re){
            console.log(re);
            $("#"+id).html(re.response_message);
            
        },'json');
    }
    function switch_village()
    {
        if($("#find_village").is(":checked"))
            {
                switcher("on");
            }
        else{ 
                switcher("off");
            }
    }
    function switcher(el)
    {
        if(el == "on")
            {
                $("#villages_text").show();
                $("#kindred_text").show();
                $("#surname_text").show();
                $("#father_name_text").show();
                $("#villages").hide();
                $("#kindred").hide();
                $("#surname").hide();
                $("#father_name").hide();
            }
        else if(el == "off"){
                $("#villages_text").hide();
                $("#kindred_text").hide();
                $("#surname_text").hide();
                $("#father_name_text").hide();
                $("#villages").show();
                $("#kindred").show();
                $("#surname").show();
                $("#father_name").show();
        }
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