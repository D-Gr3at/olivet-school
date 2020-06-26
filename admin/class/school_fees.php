<?php

// use phpDocumentor\Reflection\Types\Null_;

// session_start();
// include_once('../libs/dbfunctions.php');
class SchoolFees extends dbobject{

    public function schoolFeesList($data){
		$table_name    = "school_fees_setup";
        $primary_key   = "fee_id";
		$columner = array(
            array( 'db' => 'fee_id', 'dt' => 0),
            array( 'db' => 'faculty_id',  'dt' => 1, 'formatter'=>function($id, $row){
                $faculty_name = $this->getitemlabel('faculty_settup','faculty_id', $row['faculty_id'], 'faculty_name');
                return "<span class='text-uppercase'>".$faculty_name."</span>";
            }),
            array( 'db' => 'department_id', 'dt' => 2, 'formatter'=>function($id, $row){
                $department_name = $this->getitemlabel('department_setup_tbl','dapartment_id', $row['department_id'], 'department_name');
                return "<span class='text-uppercase'>".$department_name."</span>";
            }),
            array( 'db' => 'programme_id',  'dt' => 3, 'formatter'=>function($id, $row){
                $department_option_name = $this->getitemlabel('programme_setup','programme_id', $row['programme_id'], 'programme_name');
                return "<span class='text-uppercase'>".$department_option_name."</span>";
            }),
            array( 'db' => 'setup_level',  'dt' => 4),
            array( 'db' => 'academic_session',  'dt' => 5, 'formatter'=>function($id, $row){
                $session_name = $this->getitemlabel('session_setup','session_id', $row['academic_session'], 'session_name');
                return $session_name;
            }),
            array( 'db' => 'total_amount',  'dt' => 6),
            array( 'db' => 'status',   'dt' => 7, 'formatter'=>function($id, $row){
                if($row['status'] == 0){
                    return "<span style='cursor:pointer' class='badge badge-danger'>INACTIVE</span>";
                }else {
                    return "<span style='cursor:pointer' class='badge badge-success'>ACTIVE</span>";
                }
            }),
            array( 'db' => 'posted_by',  'dt' => 8 ),
            array( 'db' => 'fee_id',   'dt' => 9, 'formatter'=>function($d,$row){
                $locking = ($row['status']==0)?"Enable":"Disable";
                $locking_class = ($row['status']==0)?"btn-success":"btn-danger";
                $edit_class = ($row['status'] == 0)?"display:none":"";
                if($_SESSION['role_id_sess'] == 001){
                    $status = ($row['status']==1)?"<button onclick=\"trigSchoolFees('".$d."','".$row['status']."')\" class='btn btn-sm ".$locking_class."'>".$locking."</button>":"<button onclick=\"trigSchoolFees('".$d."','".$row['status']."')\" class='btn btn-sm ".$locking_class."'>".$locking."</button>";
                    return  $status."  "."<a class='btn btn-sm btn-warning' style='".$edit_class."' onclick=\"getModal('setup/school_fees_setup.php?op=edit&school_fees_id=".$d."','modal_div_lg')\"  href=\"javascript:void(0)\" data-toggle=\"modal\" data-target=\"#sizedModalLg\" >EDIT</a>";
                }  
            }),
        );
        $datatableEngine = new engine();
		echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key);
    }

    public function getSessionName($data){
        if(isset($_POST["query"])){
            $request = filter_var($data["query"],FILTER_SANITIZE_STRING);
            $query = "SELECT * FROM session_setup WHERE (session_name LIKE '%".$request."%' OR session_period_start LIKE '%".$request."%' OR session_period_end LIKE '%".$request."%') AND status = 1";
            $result = mysql_query($query);
            $data = array();
            if(mysql_num_rows($result) > 0){
                while($row = mysql_fetch_array($result)){
                    $data[] = $row["session_name"];
                    $data[] = $row["session_period_start"];
                    $data[] = $row["session_period_end"];
                    $data[] = $row["session_id"];
                }
            }
            if(isset($_POST['typehead_search'])){
    
            }else{
                $data = array_unique($data);
                echo json_encode($data);
            }
        }
    }

    public function changeSchoolFeesStatus($data){
        $school_fees_id = $data['school_fees_id'];
        $status   = ($data['current_status'] == 1)?"0":"1";
        $sql      = "UPDATE school_fees_setup SET status =".$status." WHERE fee_id =".$school_fees_id."";
        $cc = $this->db_query($sql,false);
        if($cc){
            return json_encode(array('response_code'=>0,'response_message'=>'Action on school fees setup is now effective'));
        }else{
            return json_encode(array('response_code'=>432,'response_message'=>'Action failed'));
        } 
    }

      public function register($data, $check){
        $total_amount  = 0;
		//  check if record does not exists before then insert 
            if($data['operation'] != 'edit'){
                $validation = $this->validate(
                    $data,
                    array(
                        'faculty' =>'required',
                        'department' =>'required',
                        'department_option' =>'required',
                        'session_search' =>'required',
                        'level' =>'required',
                        'amount' =>'required',
                        'fee_name' =>'required'
                    ),
                    array(
                        'faculty' =>'Faculty name',
                        'department' =>'Department name',
                        'department_option'    =>'Programme name',
                        'session_search' =>'Session',
                        'level' =>'Level',
                        'amount' =>'Amount',
                        'fee_name' =>'Fee Name'
                    )
                );
               if(!$validation['error']){
                    $session_id = $this->getitemlabel('session_setup', 'session_name', $data['session_search'], 'session_id');
                    $fees = $data["amount"];
                    $fee_names = $data["fee_name"];
                    foreach($fees as $key => $value){
                        $fee = floatval(str_replace(',', '', $value));
                        $total_amount += $fee;
                    }
                    $other_fees = $data["other_fee_amount"];
                    $other_fee_names = $data["other_fee_name"];
                    if(!empty($other_fee_names) && !empty($other_fees)){
                        foreach($other_fees as $key => $value){
                            if(empty($value)){
                                return json_encode(array("response_code"=>20,"response_message"=>'Other fee amount field is required.'));
                            }
                            if(empty($other_fee_names[$key])){
                                return json_encode(array("response_code"=>20,"response_message"=>'Other fee name field is required.'));
                            }
                            $other_fee = floatval(str_replace(',', '', $value));
                            $total_amount += $other_fee;
                        }
                    }
                    var_dump($total_amount);
                    $data['created'] = date('Y-m-d h:i:s');
                    $data['modified'] = date('Y-m-d h:i:s');
                    $data['status'] = 1;
                    $query = "INSERT INTO school_fees_setup(academic_session, setup_level, faculty_id, department_id, programme_id, status, total_amount, posted_by, created, modified) 
                                VALUES ('".$session_id."', ".$data['level'].", ".$data['faculty'].", ".$data["department"].", ".$data["department_option"].", ".$data['status'].",".$total_amount.", '".$data["posted_by"]."', '".$data['created']."', '".$data['modified']."')";
                    // echo $query."\n";
                    $school_fees_setup_count = $this->db_query($query, false);
                    if($school_fees_setup_count > 0){
                        $sql_other_fee = "SELECT fee_id FROM school_fees_setup ORDER BY fee_id DESC LIMIT 1";
                        $result = mysql_query($sql_other_fee);
                        $fee_id = mysql_fetch_row($result);
                        $fee_id = $fee_id[0];
                        foreach($fees as $key => $value){
                            $fee_name = $fee_names[$key];
                            if(empty($value)){
                                return json_encode(array("response_code"=>20,"response_message"=>"Amount field is required."));
                            }
                            $amount = floatval(str_replace(',', '', $value));
                            $query = "INSERT INTO school_fees(fee_name, amount, school_fees_fk, created, modified) VALUES ('". $fee_name."', ".$amount.", ".$fee_id.", '".$data['created']."','". $data['modified']."')";
                            // echo $query."\n";
                            $result = $this->db_query($query, false);
                        }
                        if((sizeof($other_fee_names) == sizeof($other_fees)) && sizeof($other_fee_names) != NULL){
                            // $sql_other_fee = "SELECT fee_id FROM school_fees_setup ORDER BY fee_id DESC LIMIT 1";
                            // $result = mysql_query($sql_other_fee);
                            // $fee_id = mysql_fetch_row($result);
                            // $fee_id = $fee_id[0];
                            foreach ($other_fees as $key => $value){
                                $other_fee = floatval(str_replace(',', '', $value));
                                $query = "INSERT INTO other_fees(fee_name, amount, school_fees_fk, created, modified) VALUES ('". $other_fee_names[$key]."', ".$other_fee.", ".$fee_id.", '".$data['created']."','". $data['modified']."')";
                                // echo $query."\n";
                                $result = $this->db_query($query, false);
                            }
                        }      
                    }             
                    if($result > 0 && $check == 0){
                        return json_encode(array("response_code"=>0,"response_message"=>'Record saved successfully'));
                    }else{
                        return json_encode(array("response_code"=>78,"response_message"=>'Failed to save record'));
                    } 
                }else{
                    return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
                }
            }else{
            //   EDIT EXISTING FACULTY 
                $data['modified'] = date('Y-m-d h:i:s');
                $data['status'] = 1;
                $validation = $this->validate($data,
                            array(
                                'faculty'    =>'required',
                                'department'    =>'required',
                                'session_search' =>'required',
                                'level' =>'required',
                                'amount' =>'required',
                                'fee_name' =>'required'
                                
                            ),
                            array(
                                'faculty'    =>'Faculty name',
                                'department'    =>'Department name',
                                'session_search' =>'Session',
                                'level' =>'Level',
                                'amount' =>'Amount',
                                'fee_name' =>'Fee Name'
                            )
                       );
                if(!$validation['error']){
                    // var_dump($data);
                    $session_id = $this->getitemlabel('session_setup', 'session_name', $data['session_search'], 'session_id');
                    $total_amount = 0;
                    $amounts = $data['amount'];
                    $other_amounts = $data['other_fee_amount'];
                    foreach($amounts as $key => $value){
                        $amount = floatval(str_replace(',', '', $value));
                        $total_amount += $amount;
                    }
                    if(!empty($other_amounts)){
                        foreach($other_amounts as $key => $value){
                            $other_amount = floatval(str_replace(',', '', $value));
                            $total_amount += $other_amount;
                        }
                    }
                    // var_dump($total_amount);
                    $query = "UPDATE school_fees_setup SET faculty_id=".$data['faculty'].", department_id=".$data['department'].", programme_id = ".$data['department_option'].", setup_level=".$data['level'].", modified = '".$data['modified']."', academic_session = '".$session_id."', total_amount = ".$total_amount.", posted_by = '".$data['posted_by']."' WHERE fee_id =".$data['school_fees_id']."";
                    // echo $query."\n";
                    $school_fees_setup_count = $this->db_query($query, false);
                    if($school_fees_setup_count > 0){
                        $check += $school_fees_setup_count;
                    }
                    foreach($amounts as $key => $value){
                        if(empty($value)){
                            return json_encode(array("response_code"=>20,"response_message"=>"Amount field is required."));
                        }
                        $amount = floatval(str_replace(',', '', $value));
                        $query = "UPDATE school_fees SET amount = ".$amount.", modified = '".$data['modified']."' WHERE school_fee_id = ".$data["fees_id"][$key]." AND school_fees_fk = ".$data["school_fees_id"];
                        // echo $query."\n";
                        $count = $this->db_query($query, false);
                        if($count > 0){
                            $check += $count;
                        }
                    }
                    // if(!empty($other_amounts)){
                    //     foreach($other_amounts as $key => $value){

                    //     }
                    // }
                    $sql_other_fees = "SELECT fee_id FROM other_fees ORDER BY fee_id DESC LIMIT 1";
                    $result = mysql_query($sql_other_fees);
                    $fee_id = mysql_fetch_row($result);
                    $fee_id = $fee_id[0];
                    $other_fee_ids = $data['other_fees_id'];
                    if(sizeof($other_fee_ids) < sizeof($other_amounts)){
                        $diff = sizeof($other_amounts) - sizeof($other_fee_ids);
                        for($k = 0; $k < $diff; $k++){
                            $fee_id += 1;
                            array_push($other_fee_ids, (string)$fee_id);
                            end($other_fee_ids);
                            $key = key($other_fee_ids);
                            $amount = floatval(str_replace(',', '', $other_amounts[$key]));
                            if(!empty($data["other_fee_name"][$key])){
                                $query = "INSERT INTO other_fees VALUES (".$fee_id.",'". $data["other_fee_name"][$key]."',". $amount.",". $data['school_fees_id'].",'". $data['modified']."', '".$data['modified']."')";
                                // echo $query."\n";
                                $result = $this->db_query($query, false);
                            }else{
                                return json_encode(array("response_code"=>20,"response_message"=>'Other fee name field is required.'));
                            }
                            if($result > 0){
                                $check += $result;
                            }
                        }
                    }
                    for ($count = 0; $count < sizeof($data['other_fee_amount']); $count++){
                        $amount = floatval(str_replace(',', '', $data['other_fee_amount'][$count]));
                        $query = "UPDATE other_fees SET fee_name = '".$data['other_fee_name'][$count]."', amount = ".$amount.", modified = '".$data['modified']."' WHERE fee_id=".$data['other_fees_id'][$count]."";
                        // echo $query."\n";
                        $result = $this->db_query($query, false);
                        if($result > 1){
                            $check += $result;
                        }
                    }
                    if($check > 0){
                        return json_encode(array("response_code"=>0,"response_message"=>'Record saved successfully'));
                    }else if($check == 0){
                        return json_encode(array("response_code"=>0,"response_message"=>'No changes made. Record saved successfully'));
                    }else{
                        return json_encode(array("response_code"=>78,"response_message"=>'Failed to save record'));
                    }
                }else{
                    return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
                }
          }
        
    }
    
    public function saveSchoolFees($data){
        // var_dump($data);
        $role_id = $_SESSION['role_id_sess'];
        $validation = "";
        if($role_id == 001){
            $validation = $this->validate(
                $data,
                array(
                    'faculty'    =>'required',
                    'department'    =>'required',
                    'session_search' =>'required',
                    'level' =>'required',
                    'amount' =>'required',
                    'fee_name' =>'required'
                ),
                array(
                    'faculty'    =>'Faculty name',
                    'department'    =>'Department name',
                    'session_search' =>'Session',
                    'level' =>'Level',
                    'amount' =>'Amount',
                    'fee_name' =>'Fee Name'
                )
            );
            
            if(!$validation['error']){
                $session_id = $this->getitemlabel('session_setup', 'session_name', $data['session_search'], 'session_id');
                if($data['operation'] == "new"){
                    // var_dump($data);
                    $fee_result = 0;
                    $sql = "SELECT * FROM school_fees_setup WHERE setup_level = ".$data['level']." AND academic_session =".$session_id." AND programme_id = ".$data['department_option']." AND department_id = ".$data['department']."";
                    echo $sql."\n";
                    $fee_result = $this->db_query($sql, false);
                    if($fee_result > 0){
                        $validation['error'] = true;
                        $validation['messages'][0] = "This setup has already been done. There can only be one setup of this type.";
                    }else {
                        return $this->register($data, $fee_result);
                    }
                }else{
                    // var_dump($data);
                    $ids = array();
                    if($data['other_fees_id'] == NULL){
                        $data['other_fees_id'] = array();
                    }
                    $other_fees_ids = $this->db_query("SELECT fee_id FROM other_fees WHERE school_fees_fk = ".$data['school_fees_id']);
                    for($i = 0; $i < sizeof($other_fees_ids); $i++){
                        $ids[$i] = $other_fees_ids[$i]['fee_id'];
                    }
                    // var_dump($ids);
                    $result = array_diff($ids, $data['other_fees_id']);
                    $ids_del = array_values($result);
                    // var_dump($ids_del);
                    $check = 0;
                    $total_amount =$this->getitemlabel('school_fees_setup', 'fee_id', $data['school_fees_id'], 'total_amount');
                    $total_amount = floatval($total_amount);
                    if($ids_del != NULL){
                        foreach($ids_del as $key => $value){
                            $amount = $this->getitemlabel('other_fees', 'fee_id', $value, 'amount');
                            $amount = floatval($amount);
                            $check = $this->db_query("DELETE FROM other_fees WHERE fee_id = ".$value, false);
                            $total_amount -= $amount;
                            $check += $check;
                        }
                        $query = "UPDATE school_fees_setup SET total_amount = ".$total_amount." WHERE fee_id = ".$data['school_fees_id'];
                        // echo $query."\n";
                        $this->db_query($query, false);
                    }
                    return $this->register($data, $check);
                }
            }
                 return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
        }
    }
}
?>