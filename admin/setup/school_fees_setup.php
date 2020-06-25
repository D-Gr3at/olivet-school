<?php
error_reporting(1);
SESSION_START();
include_once("../libs/dbfunctions.php");
$dbobject = new dbobject();

// var_dump($_REQUEST);

$user_role = $_SESSION['role_id_sess'];
if ($user_role == 001) {
    $sql_role = "SELECT * FROM role WHERE role_id IN ('003','005','006','007') ";
} elseif ($user_role == 005) {
    $sql_role = "SELECT * FROM role WHERE role_id = '003' ";
} else {
    $sql_role = "SELECT * FROM role WHERE role_id <> '001' AND role_id <> '$user_role' AND role_id NOT IN ('003','005','006','007')";
}

$roles = $dbobject->db_query($sql_role);

$school_fee_names = $dbobject->db_query("SELECT item_name FROM school_fees_item_setup WHERE status = 1");

if (isset($_REQUEST['op']) && $_REQUEST['op'] == 'edit') {
    $school_fees_id  = $_REQUEST['school_fees_id'];
    $school_fees_setup = $dbobject->db_query("SELECT * FROM school_fees_setup WHERE fee_id=" . $school_fees_id . "");
    $department = $dbobject->db_query("SELECT * FROM department_setup_tbl WHERE dapartment_id = ".$school_fees_setup[0]['department_id']."");
    $session = $dbobject->db_query("SELECT * FROM session_setup WHERE session_id=" . $school_fees_setup[0]['academic_session'] . "");
    $department_option = $dbobject->db_query("SELECT * FROM programme_setup WHERE programme_id=" . $school_fees_setup[0]['programme_id'] . "");
    $faculty = $dbobject->db_query("SELECT * FROM faculty_settup WHERE faculty_id=" . $school_fees_setup[0]['faculty_id'] . "");
    $other_fees = $dbobject->db_query("SELECT * FROM other_fees WHERE school_fees_fk=" . $school_fees_id . "");
    $operation = 'edit';
    $amount = $dbobject->db_query("SELECT * FROM school_fees WHERE school_fees_fk=" . $school_fees_id . "");
} else {
    $operation = 'new';
    $query_select_id = "SELECT fee_id from school_fees_setup  ORDER BY fee_id DESC LIMIT 1";
    $run_query_select_id = $dbobject->db_query($query_select_id);
    $school_fees_setup_id = $run_query_select_id[0]["fee_id"];
    $school_fees_id = $school_fees_setup_id + 1;
}
$faculty_names_sql = "SELECT faculty_id, faculty_name FROM faculty_settup";
$faculty_names = $dbobject->db_query($faculty_names_sql);

?>
<link rel="stylesheet" href="codebase/dhtmlxcalendar.css" />
<script src="codebase/dhtmlxcalendar.js"></script>
<script>
    doOnLoad();
    var myCalendar;

    function doOnLoad() {
        myCalendar = new dhtmlXCalendarObject(["start_date", "end_date"]);
        myCalendar.hideTime();
    }
</script>
<style>
    fieldset {
        display: block;
        margin-left: 2px;
        margin-right: 2px;
        padding-top: 0.35em;
        padding-bottom: 0.625em;
        padding-left: 0.75em;
        padding-right: 0.75em;
        border: 1px solid #ccc;
    }

    legend {
        font-size: 18px;
        padding: 5px;
        font-weight: bold;
        color: #d1702b;
        width: auto;
    }

    label {
        color: #000;
        font-weight: bold;
    }

    .number {
        font-size: 1.2em;
        text-align: center;
    }

    #total_amount {
        /* font-size: 1.3em; */
        margin-left: 1%;
        margin-bottom: 1%;
    }

    .amount {
        font-size: 1em;
        margin-top: 1em;
        font-size: 1.1em;
    }

    .fee_name {
        font-size: 1.2em;
    }
</style>
<style>
    #login_days>label {
        margin-right: 10px;
    }

    .asterik {
        color: red;
    }
</style>
<script src="js/bootstrap3-typeahead.min.js"></script>
<div id="mimodal">
    <div class="modal-header">
        <h4 class="modal-title" style="font-weight:bold"><?php echo ($operation == "edit") ? "Edit " : ""; ?>School Fees Setup<div><small style="font-size:12px">All asterik fields are compulsory</small></div>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body m-3 ">
        <form id="form1" onsubmit="return false">
            <input type="hidden" name="op" value="SchoolFees.saveSchoolFees" />
            <input type="hidden" name="operation" value="<?php echo $operation; ?>" />
            <input type="hidden" name="school_fees_id" id="school_fees_id" value="<?= $school_fees_id; ?>" />
            <input type="hidden" name="posted_by" id="posted_by" value="<?php echo $_SESSION['firstname_sess'] . ' ' . $_SESSION['lastname_sess']; ?>" />
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
                                if ($faculty[0]['faculty_name'] == $faculty_name_value) {
                                    echo "<option value='" . $faculty_id . "' selected>" . $faculty_name_value . "</option>";
                                    $departments = $dbobject->db_query("SELECT * FROM department_setup_tbl WHERE faculty_code =" . $faculty_id . "");
                                } else {
                                    echo "<option value='" . $faculty_id . "'>" . $faculty_name_value . "</option>";
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
                            foreach ($departments as $key => $value) {
                                $department_name = $value['department_name'];
                                $department_id = $value['dapartment_id'];
                                if ($department[0]['department_name'] == $department_name) {
                                    echo "<option value='" . $department_id . "' selected>" . $department_name . "</option>";
                                    $department_options = $dbobject->db_query("SELECT * FROM programme_setup WHERE department_id =" . $department_id . "");
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
                            <option value=''>::NO PROGRAMME TO SELECT::</option>";
                            foreach ($department_options as $key => $value) {
                                $option_name = $value['programme_name'];
                                $option_id = $value['programme_id'];
                                if ($department_option[0]['programme_name'] == $option_name) {
                                    echo "<option value='" . $option_id . "' selected>" . $option_name . "</option>";
                                }
                            }
                            echo "</select>";
                            ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Session<span class="asterik">*</span></label>
                            <div id="search_session">
                                <input type="text" name="session_search" id="session_search" class="form-control text-uppercase" value="<?php echo $session[0]['session_name'] ?>" autocomplete="off" placeholder="Type session here" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Level<span class="asterik">*</span></label>
                            <select class='form-control text-uppercase' name='level' id='level'>
                                <option value=''>::SELECT LEVEL::</option>
                                <?php
                                for ($year = 100; $year <= 600; $year += 100) {
                                    if ($school_fees_setup[0]['setup_level'] == $year) {
                                        echo "<option value='" . $year . "' selected>" . $year . "</option>";
                                    } else {
                                        echo "<option value='" . $year . "'>" . $year . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="form-group">
                <legend>Fees Info</legend>
                <div class="row">
                    <div class="container">
                        <div class="row">
                            <?php foreach($school_fee_names as $key => $value){  ?>
                                <div class='col-sm-6'>
                                    <div class='row'>
                                        <div class='col-sm-6'>
                                            <div class='form-group'>
                                                <input type="hidden" name="fees_id[]" value="<?php echo $amount[$key]['school_fee_id']; ?>"/>
                                                <input style="border: none;" name='fee_name[]' value="<?php echo $value['item_name']; ?>" class='text-capitalize' readonly/>
                                            </div>  
                                        </div>
                                        <div class='col-sm-6'>
                                            <div class='form-group'>
                                                <input type='text' name='amount[]' value="<?php echo $amount[$key]['amount'] ?>" class='form-control number'/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php }?>
                            <div class="container">
                                <div class="row" id="fees">
                                    <?php if($other_fees != NULL){
                                        foreach($other_fees as $key => $value){
                                    ?>
                                        <div class="col-sm-6">
                                            <input type="hidden" name="other_fees_id[]" value="<?php echo $value['fee_id']; ?>"/>
                                            <span class="remove-item-form fa fa-times color-danger" style="cursor:pointer;right: 0;margin-right: 50px;font-weight: bolder;"></span>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Fee Name<span class="asterik">*</span></label>
                                                        <input type="text" name= "other_fee_name[]" value="<?php echo $value['fee_name']; ?>" class="form-control text-capitalize"/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Amount<span class="asterik">*</span></label>
                                                        <input type="text" name="other_fee_amount[]" value="<?php echo $value['amount']; ?>" class="form-control number remove"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                 } ?>
                                </div>
                            </div>
                        </div>
                        <div class="font-weight-bold amount">
                            Total Amount:
                            <span id="total_amount">
                                <?php
                                    $total_fee = $dbobject->getitemlabel('school_fees_setup', 'fee_id', $school_fees_id, 'total_amount');
                                    echo ($total_fee != NULL && $operation == 'edit')? $total_fee : "0.00";
                                ?>
                            </span>
                        </div>
                        <div class="">
                            <div class="float-right">
                                <input type="button" id="addButton" class="btn btn-sm btn-primary" value="Add Other fees">
                            </div>
                        </div>
                    </div>
            </fieldset>
            <div class="row">
                <div class="col-sm-12">
                    <div id="server_mssg"></div>
                </div>
            </div>
            <input type="submit" id="save_school_fees_setup" class="btn btn-primary" value="Submit" />
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {

        numberFormat();
        calculateTotal()
        $("#addButton").on("click", function() {
            $('#fees').append("<div class='col-sm-6'>" +
                "<span class='remove-item-form fa fa-times color-danger' style='cursor:pointer;right: 0;margin-right: 50px;font-weight: bolder;'></span>" +
                "<div class='row'>" +
                "<div class='col-sm-6'>" +
                "<div class='form-group'>" +
                "<label class='form-label'>Fee Name<span class='asterik'>*</span></label>" +
                "<input type='text' name='other_fee_name[]' class='form-control text-capitalize'/>" +
                "</div>" +
                "</div>" +
                "<div class='col-sm-6'>" +
                "<div class='form-group'>" +
                "<label class='form-label'>Amount<span class='asterik'>*</span></label>" +
                "<input type='text' name='other_fee_amount[]' class='form-control number remove'/>" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>");
            numberFormat();
            calculateTotal();
        });

        $(document).on("click", ".remove-item-form", function(e) {
            var elemToSubtractFromTotalInput = this.parentNode.querySelector(".row").
            lastElementChild.querySelector(".form-group").querySelector("input").value;
            if(elemToSubtractFromTotalInput!=""){
                elemToSubtractFromTotalInput = elemToSubtractFromTotalInput.replace(',', '');
                var total_amount_elem = document.querySelector("#total_amount");
                var total_amount  = parseFloat(total_amount_elem.innerHTML);
                elemToSubtractFromTotalInput = parseFloat(elemToSubtractFromTotalInput);
                new_amount = total_amount - elemToSubtractFromTotalInput;
                total_amount_elem.innerHTML = ""+new_amount.toFixed(2)+"";
            }
            let parentNode = this.parentNode;
            let grandParentNode = this.parentNode.parentNode;
            grandParentNode.removeChild(parentNode);
        })

        $('#session_search').typeahead({
            source: function(query, result) {
                $.ajax({
                    url: "utilities.php",
                    method: "POST",
                    data: {
                        query: query,
                        op: "SchoolFees.getSessionName"
                    },
                    dataType: "json",
                    success: function(data) {
                        result($.map(data, function(item) {
                            return item;
                        }));
                    }
                });
            }
        });

        function numberFormat() {
            $('input.number').keyup(function(event) {
                // skip for arrow keys
                if (event.which >= 37 && event.which <= 40) {
                    event.preventDefault();
                }
                let returned_value = (index, value) => {
                    return value
                        .replace(/\D/g, "")
                        .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
                }
                $(this).val(returned_value);
            });
        }

        // var total = [];
        // $('input.number').change(function(event) {
        //     let input = parseFloat($(this).val());
        //     total.push(input);

        //     total.forEach(function(item, index, array) {
        //         console.log(index)
        //     })
        // });

        // $('form-group').on('input', '.number', function(){
        //     var totalSum = 0;
        //     $('.form-group .number').each(function(){
        //         var inputVal = $(this).val();
        //         console.log(inputVal);
                
        //         if($.isNumeric(totalVal)){
        //             totalSum += parseFloat(inputVal);
        //         }
        //     });
        //     $('#total_amount').empty();
        //     $('#total_amount').text(totalSum);
        // })

        var total_amount = function(){
            var sum = 0;
            $('.number').each(function(){
                var num = $(this).val().replace(',', '');
                if(num != 0){
                    sum += parseFloat(num);
                }
            });
            $('#total_amount').text(sum.toFixed(2));
        }

        function calculateTotal(){
            $('.number').keyup(function(){
                total_amount();
            });
        }

        // on form submit
        $("#form1").on('submit', function() {
            $("#save_school_fees_setup").val("Loading...");

            var dd = $("#form1").serialize();
            console.log(dd);

            $.post("utilities.php", dd, function(re) {
                console.log(re);
                $("#save_school_fees_setup").val("Submit");
                if (re.response_code == 0) {
                    $("#server_mssg").text(re.response_message);
                    $("#server_mssg").css({
                        'color': 'green',
                        'font-weight': 'bold'
                    });
                    getpage('school_fees_setup_list.php', 'page');
                    setTimeout(() => {
                        $('#sizedModalLg').modal('hide');
                    }, 1000)
                } else {
                    $("#server_mssg").text(re.response_message);
                    $("#server_mssg").css({
                        'color': 'red',
                        'font-weight': 'bold'
                    });
                }
            }, 'json');
        });
    });

    // $('.number').attr('disabled','disabled');
    if ($("#sh_display").is(':checked')) {

    }

    function show_bank_details(val) {
        if (val == 003) {
            $("#parish_pastor_div").show();
        } else {
            $("#parish_pastor_div").hide();
        }
    }

    function fetchLga(el) {
        $("#lga-fd").html("<option>Loading Lga</option>");
        $.post("utilities.php", {
            op: 'Church.getLga',
            state: el
        }, function(re) {
            //            $("#lga-fd").empty();
            console.log(re);
            $("#lga-fd").html(re.state);
            $("#church_id").html(re.church);

        }, 'json');
    }

    function getUniqueChurch(el) {
        $("#church_id").html("<option>Loading Church</option>");
        var ste = $("#church_state").val();
        $.post("utilities.php", {
            op: 'Church.churchByState',
            state: ste,
            lga: el
        }, function(re) {
            //            $("#lga-fd").empty();
            console.log(re);
            $("#church_id").html(re);

        });
    }

    $("#show").click(function() {
        var password = $("#password").attr('type');
        if (password == "password") {
            $("#password").attr('type', 'text');
            $("#show").text("Hide");
        } else {
            $("#password").attr('type', 'password');
            $("#show").text("Show");
        }
    });

    function check_bank_det(el) {
        if ($("#yes").is(':checked')) {
            $("#bank_details").slideDown()
        } else if ($("#no").is(':checked')) {
            $("#bank_details").slideUp()
        }
    }

    function fetchAccName(acc_no) {
        if (acc_no.length == 10) {
            var account = acc_no;
            var bnk_code = $("#bank_name").val();
            $("#acc_name").text("Verifying account number....");
            $("#account_name").val("");
            $.post("utilities.php", {
                op: "Church.getAccountName",
                account_no: account,
                bank_code: bnk_code
            }, function(res) {

                $("#acc_name").text(res);
                $("#account_name").val(res);
            });
        } else {
            $("#acc_name").text("Account Number must be 10 digits");
        }
    }
</script>