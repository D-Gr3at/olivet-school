<?php
error_reporting(1);
session_start();
// $included_files = get_included_files();
// foreach ($included_files as $filename) {
//     echo "$filename\n";
// }
Class Semester extends dbobject{
    
    public function semesterList($data){
		$table_name    = "semester_setup";
		$primary_key   = " semester_id";
		$columner = array(
			array( 'db' => 'semester_id', 'dt' => 0 ),
            array( 'db' => 'academic_session', 'dt' => 1, 'formatter' => function($id, $row){
                $session_name = $this->getitemlabel('session_setup','session_id',$id,'session_name');
                // var_dump($faculty_name);
                return $session_name;
            } ),
            array( 'db' => 'semester_name',  'dt' => 2 ),
            array( 'db' => 'semester_start',  'dt' => 3, 'formatter' => function($id, $row){
                $date = new DateTime($row['semester_start']);
                $result = $date->format('Y-m-d');
                return $result;
            }),
            array( 'db' => 'semester_end',  'dt' => 4, 'formatter' => function($id, $row){
                $date = new DateTime($row['semester_end']);
                $result = $date->format('Y-m-d');
                return $result;
            }),
            array( 'db' => 'status',   'dt' => 5, 'formatter'=>function($id, $row){
                if($row['status'] == 0){
                    return "<span style='cursor:pointer' class='badge badge-danger'>INACTIVE</span>";
                }else {
                    return "<span style='cursor:pointer' class='badge badge-success'>ACTIVE</span>";
                }
            }),
            array( 'db' => 'posted_by',  'dt' => 6 ),
            array( 'db' => 'semester_id',   'dt' => 7, 'formatter'=>function($d,$row){
                $locking = ($row['status']==0)?"Enable":"Disable";
                $locking_class = ($row['status']==0)?"btn-success":"btn-danger";
                $edit_class = ($row['status'] == 0)?"display:none":"";
                if($_SESSION['role_id_sess'] == 001){
                    $status = ($row['status']==1)?"<button onclick=\"trigSemester('".$d."','".$row['status']."')\" class='btn btn-sm ".$locking_class."'>".$locking."</button>":"<button onclick=\"trigSemester('".$d."','".$row['status']."')\" class='btn btn-sm ".$locking_class."'>".$locking."</button>";
                    return  $status."  "."<a class='btn btn-sm btn-warning' style='".$edit_class."' onclick=\"getModal('setup/semester_setup.php?op=edit&semester_id=".$d."','modal_div_lg')\"  href=\"javascript:void(0)\" data-toggle=\"modal\" data-target=\"#sizedModalLg\" >EDIT</a>";
                }
                
            }),
		);
        $datatableEngine = new engine();
		echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key);
    }

    public function register($data)
	{
        // check if record does not exists before then insert 
            if($data['operation'] != 'edit'){
                $validation = $this->validate(
                    $data,
                    array(
                        'academic_session'    =>'required',
                        'semester_name'    =>'required',
                        'start_date' =>'required',
                        'end_date'   =>'required',
                        'status'   =>'required'
                    ),
                    array(
                        'academic_session' => 'Academic session',
                        'semester_name' => 'Semester',
                        'start_date' => 'Semester start date',
                        'end_date' => 'Semester end date',
                        'status'   => 'Status'
                    )
                );
                if(!$validation['error']){
                    $session_name = filter_var($data["academic_session"], FILTER_SANITIZE_STRING);
                    $semester_number = filter_var($data["semester_name"], FILTER_SANITIZE_STRING);
                    if($semester_number == '1'){
                        $semester_name = "First Semester";
                    }else{
                        $semester_name = "Second Semester";
                    }
                    $session_id = $this->getitemlabel('session_setup', 'session_name', $session_name, 'session_id');
                    $sql = "SELECT semester_id FROM semester_setup ORDER BY semester_id DESC LIMIT 1";
                    $result = mysql_query($sql);
                    $semester_id = mysql_fetch_row($result);
                    $semester_id = $semester_id[0];
                    $query = $this->db_query("SELECT semester_end FROM semester_setup WHERE semester_id = ".$semester_id);
                    $semester_dates = $query[0];
                    $start_date = date($data['start_date'].' h:i:s');
                    $end_date = date($data['end_date'].' h:i:s');
                    $date_diff = strtotime($end_date) - strtotime($start_date);
                    $sql = "SELECT semester_end FROM semester_setup ORDER BY semester_id DESC LIMIT 1";
                    $result = mysql_query($sql);
                    $semester = mysql_fetch_row($result);
                    $semester = $semester[0];
                    if($semester > $start_date){
                        return json_encode(array("response_code"=>78,"response_message"=>'Semester cannot start on/before prevoius semester end date. Please choose a later date.'));
                    }
                    if($start_date >  $end_date){
                        return json_encode(array("response_code"=>78,"response_message"=>'Semester start cannot be greater then semester end.'));
                    }
                    if($start_date <= $semester_dates["semester_end"]){
                        return json_encode(array("response_code"=>78,"response_message"=>'Wrong semester start date, please choose an earlier date.'));
                    }
                    $months = floor($date_diff/2628000);
                    // var_dump()
                    if($months < 3){
                        return json_encode(array("response_code"=>78,"response_message"=>'There should be at least 3 months difference between semester start and semester end.'));
                    }
                    $semester_id = $semester_id + 1;
                    $query = "INSERT INTO semester_setup VALUES (".$semester_id.", '".$session_id."', '".$semester_name."', '".$start_date."', '".$end_date."', ".$data['status'].", '".$data['posted_by']."')";
                    $count = $this->db_query($query, false);
                    if($count > 0){
                        return json_encode(array("response_code"=>0,"response_message"=>'Record saved successfully'));
                    }else{
                        return json_encode(array("response_code"=>78,"response_message"=>'Failed to save record'));
                    }
                    
                }else{
                    return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
                }
            }else{
//                EDIT EXISTING FACULTY 
                // $data['modified_date'] = date('Y-m-d h:i:s');
                $validation = $this->validate(
                    $data,
                    array(
                        'academic_session'    =>'required',
                        'semester_name'    =>'required',
                        'start_date' =>'required',
                        'end_date'   =>'required',
                        'status'   =>'required'
                    ),
                    array(
                        'academic_session' => 'Academic session',
                        'semester_name' => 'Semester',
                        'start_date' => 'Semester start date',
                        'end_date' => 'Semester end date',
                        'status'   => 'Status'
                    )
                );
                if(!$validation['error']){
                    $session_name = filter_var($data["academic_session"], FILTER_SANITIZE_STRING);
                    $semester_number = filter_var($data["semester_name"], FILTER_SANITIZE_STRING);
                    if($semester_number == '1'){
                        $semester_name = "First Semester";
                    }else{
                        $semester_name = "Second Semester";
                    }
                    $session_id = $this->getitemlabel('session_setup', 'session_name', $session_name, 'session_id');
                    $start_date = date($data['start_date'].' h:i:s');
                    $end_date = date($data['end_date'].' h:i:s');
                    $date_diff = strtotime($end_date) - strtotime($start_date);
                    if($start_date >  $end_date){
                        return json_encode(array("response_code"=>78,"response_message"=>'Semester start cannot be greater then semester end.'));
                    }
                    $months = floor($date_diff/2628000);
                    // var_dump($months);
                    if($months < 3){
                        return json_encode(array("response_code"=>78,"response_message"=>'There should be at least 3 months difference between semester start and semester end.'));
                    }
                    $query = "UPDATE semester_setup SET academic_session = '".$session_id."', semester_name = '".$semester_name."', semester_start = '".$start_date."', semester_end = '".$end_date."', status = ".$data['status'].", posted_by = '".$data['posted_by']."' WHERE semester_id = ".$data['semester_id'];
                    // echo $query;
                    $count = $this->db_query($query, false);
                    // echo $count;
                    if($count > 0){
                        return json_encode(array("response_code"=>0,"response_message"=>'Record updated successfully'));
                    }else if($count == 0){
                        return json_encode(array("response_code"=>0,"response_message"=>'No changes made. Record saved successfully'));
                    }else{
                        return json_encode(array("response_code"=>78,"response_message"=>'Failed to save record'));
                    }
                }else{
                    return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
                }
            }
        
	}

    public function saveSemester($data){
        // var_dump($data);
        $session_name = filter_var($data["academic_session"], FILTER_SANITIZE_STRING);
        $semester_number = filter_var($data["semester_name"], FILTER_SANITIZE_STRING);
        if($semester_number == '1'){
            $semester_name = "First Semester";
        }else{
            $semester_name = "Second Semester";
        }
        $session_id = $this->getitemlabel('session_setup', 'session_name', $session_name, 'session_id');
        $role_id = $_SESSION['role_id_sess'];
        $validation = "";
        if($role_id == 001){
            $validation = $this->validate(
                $data,
                array(
                    'academic_session'    =>'required',
                    'semester_name'    =>'required',
                    'start_date' =>'required',
                    'end_date'   =>'required',
                    'status'   =>'required'
                ),
                array(
                    'academic_session' => 'Academic session',
                    'semester_name' => 'Semester',
                    'start_date' => 'Semester start date',
                    'end_date' => 'Semester end date',
                    'status'   => 'Status'
                )
            );
            if(!$validation['error']){
                if($data['operation'] == "new"){
                    $query = "SELECT * FROM semester_setup WHERE semester_name = '".$semester_name."' AND academic_session = ".$session_id;
                    $count = $this->db_query($query, false);
                    if($count > 0){
                        $validation['error'] = true;
                        $validation['messages'][0] = "Semester already created for ".$session_name." session.";
                    }else {
                        return $this->register($data);
                    }
                }else if($data['operation'] != "new"){
                    return $this->register($data);
                }
            }
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
        }
    }
    
    public function changeSemesterStatus($data){
        $semester_id = $data['semester_id'];
        $status   = ($data['current_status'] == 1)?"0":"1";
        $sql      = "UPDATE semester_setup SET status = '$status' WHERE semester_id = $semester_id LIMIT 1";
        $cc = $this->db_query($sql,false);
        if($cc)
        {
            return json_encode(array('response_code'=>0,'response_message'=>'Action on semester profile is now effective'));
        }else
        {
            return json_encode(array('response_code'=>432,'response_message'=>'Action failed'));
        }
        
    }
}