<?php
include_once("libs/dbfunctions.php");
//var_dump($_SESSION);
?>
  <style>
    
    </style>
   <div class="card">
    <div class="card-header">
        <h5 class="card-title">Department List</h5>
        <h6 class="card-subtitle text-muted">The report contains departments that have been setup in the system.</h6>
    </div>
    <div class="card-body">
     <div class="row">
        <?php
         if($_SESSION['role_id_sess'] == 003 || $_SESSION['role_id_sess'] == 001 || $_SESSION['role_id_sess'] == 005 )
         {
             ?>
         <div class="col-sm-2">
             <a class="btn btn-warning" onclick="getModal('setup/department_setup.php?op=new','modal_div_lg')"  href="javascript:void(0)" data-toggle="modal" data-target="#sizedModalLg">Create Department</a>
         </div>
         <?php
         }
         ?>
     </div>
      
        <div id="datatables-basic_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
                <div class="col-sm-3">
                    <label for=""></label>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 table-responsive">
                    <table id="page_list" class="table table-striped" style="width:100%" >
                       
                        <thead>
                            <tr role="row">
                                <th>S/N</th>
                                <th>Faculty Name</th>
                                <th>Department Name</th>
                                <th>Established</th>
                                <th>Created</th>
                                <th>Status</th>
                                <th>Posted By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--<script src="../js/sweet_alerts.js"></script>-->
<!--<script src="../js/jquery.blockUI.js"></script>-->
<script>
  var table;
  var editor;
  var op = "Department.departmentList";
  $(document).ready(function() {
    table = $("#page_list").DataTable({
      processing: true,
      columnDefs: [{
            orderable: false,
            targets: 0
          },
         { width: "3100", targets: "3" }
      ],
      serverSide: true,
      paging: true,
      oLanguage: {
        sEmptyTable: "No record was found, please try another query"
      },

      ajax: {
        url: "utilities.php",
        type: "POST",
        data: function(d, l) {
          d.op = op;
          d.li = Math.random();
//          d.start_date = $("#start_date").val();
//          d.end_date = $("#end_date").val();
        }
      }
    });
  });

  function do_filter() {
    table.draw();
  }
    
    function trigDepartment(id,status){
        var r_status = (status == 1)?"Disable":"Enable";
        var cnf = confirm("Are you sure you want to "+r_status+" this department ?");
        if(cnf)
           {
                $.blockUI();
               $.post('utilities.php',{op:'Department.changeDepartmentStatus',current_status:status,department_id:id},function(resp)
               {
                   $.unblockUI();
                   if(resp.response_code == 0)
                       {
//                           alert(resp.response_message);
                          getpage('department_list.php','page'); 
                       }
                   
               },'json')
           }
    }
    
    function sackUser(username_1,status_1){
        let tt = confirm("Are you sure you want to perform this action");
        if(tt)
            {
                $.post("utilities.php",{op:"Users.sackUser",username:username_1,status:status_1},function(rr){
                    alert(rr.response_message);
                    getpage('user_list.php','page');
                },'json');
            }
    }

    function getModal(url,div){
        $('#'+div).html("<h2>Loading....</h2>");
//        $('#'+div).block({ message: null });
        $.post(url,{},function(re){
            $('#'+div).html(re);
        })
    }
</script>

