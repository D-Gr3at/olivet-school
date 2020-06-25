<?php
include_once("libs/dbfunctions.php");
//var_dump($_SESSION);
$dbobject = new dbobject();
$sql = "SELECT dapartment_id, department_name FROM department_setup_tbl";
$department_names = $dbobject->db_query($sql);

?>
    <style>
    .buttons-print{
        background-color: rgb(252, 193, 0);
        border: none;
    }
    .buttons-print:hover{
        background-color: rgb(252, 193, 0);
    }
    </style>
   <div class="card">
    <div class="card-header">
        <h5 class="card-title">Course List</h5>
        <h6 class="card-subtitle text-muted">The report contains courses that have been setup in the system.</h6>
    </div>
    <div class="card-body">
        <form id="myform" onsubmit="return false">
            <fieldset class="form-group mb-0">
                <legend>Search Courses</legend>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                                <label class="form-label">Department<span class="asterik">*</span></label>
                                <?php
                                    print '<select class="form-control text-uppercase" name="search_department" id="search_department">
                                                <option value="">::SELECT DEPARTMENT::</option>';
                                        foreach($department_names as $key => $value){
                                            echo "<option value='".$value['dapartment_id']."'>".$value['department_name']."</option>";
                                        }
                                    print '</select>';
                                ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Programme<span class="asterik">*</span></label>
                            <select class="form-control text-uppercase" name="search_programme" id="search_programme">
                                <option value="">::NO PROGRAMME TO SELECT::</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Level<span class="asterik">*</span></label>
                            <select class='form-control' name='search_level' id='search_level'>
                                <option value=''>::SELECT LEVEL::</option>
                                <?php
                                    for($year = 100; $year<=600; $year+=100){
                                        echo "<option value='".$year."'>".$year."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Semester<span class="asterik">*</span></label>
                            <select class="form-control text-uppercase" name="search_semester" id="search_semester">
                                <option value="">::SELECT SEMESTER::</option>
                                <option value="1">FIRST SEMESTER</option>
                                <option value="2">SECOND SEMESTER</option>
                            </select>
                        </div>
                    </div>
                </div>
                
            </fieldset>
            <div class="container">
                <div class="float-right">
                    <button id="search" onclick="do_filter()" class="btn btn-primary">
                        <i class="fa fa-search"></i>
                        Search
                    </button>
                </div>
            </div>
        </form>
        <hr class="mt-5">
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
                                <th>Course Title</th>
                                <th>Course Code</th>
                                <th>Course Duration</th>
                                <th>Course Unit</th>
                                <th>Department</th>
                                <th>Programme</th>
                                <th>Level</th>
                                <th>Semester</th>
                                <th>Status</th>
                                <th>Action</th>
                                <!-- <th>Created</th> -->
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
  var op = "SchoolCourses.schoolCoursesList";
  $(document).ready(function() {
    table = $("#page_list").DataTable({
      processing: true,
      columnDefs: [{
            orderable: false,
            targets: 0
          },
         { width: "3100", targets: "3" }
      ],
      dom: 'Bfrtip',
        buttons: [
            {
                extend: 'print',
                customize: function ( win ) {
                    $('.buttons-print').removeClass('btn-secondary');
                    $('span').addClass('btn-primary');

                    $(win.document.body)
                        .css( 'font-size', '10pt' )
                        .prepend(
                            '<img src="../img/logo.png" style="position:absolute; top:250; left:450;" />'
                        );
 
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                }
            }
        ],
      bFilter: false,
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
            d.search_department = $("#search_department").val();
            d.search_programme = $("#search_programme").val();
            d.search_level = $("#search_level").val();
            d.search_semester = $("#search_semester").val();
//          d.end_date = $("#end_date").val();
        }
      }
    });
  });

  function do_filter() {
    table.draw();
  }
    
    function trigSchoolCourses(course_id,status)
    {
        var r_status = (status == 1)?"Unlock this course":"Lock this course";
        var cnf = confirm("Are you sure you want to "+r_status+" ?");
        if(cnf)
           {
                $.blockUI();
               $.post('utilities.php',{op:'SchoolCourses.changeCourseStatus', current_status:status, id:course_id},function(resp)
               {
                   $.unblockUI();
                   if(resp.response_code == 0)
                       {
//                           alert(resp.response_message);
                          getpage('school_courses_list.php','page'); 
                       }
                   
               },'json')
           }
    }
    function sackUser(username_1,status_1)
    {
        let tt = confirm("Are you sure you want to perform this action");
        if(tt)
            {
                $.post("utilities.php",{op:"Users.sackUser",username:username_1,status:status_1},function(rr){
                    alert(rr.response_message);
                    getpage('user_list.php','page');
                },'json');
            }
    }
    function getModal(url,div)
    {
        $('#'+div).html("<h2>Loading....</h2>");
//        $('#'+div).block({ message: null });
        $.post(url,{},function(re){
            $('#'+div).html(re);
        })
    }
</script>

