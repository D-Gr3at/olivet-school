<?php
// error_reporting(1);
// session_start();
// $included_files = get_included_files();
// foreach ($included_files as $filename) {
//     echo "$filename\n";
// }
Class Programme extends dbobject{
    
    public function programmeList($data){
		$table_name    = "programme_setup";
		$primary_key   = " programme_id";
		$columner = array(
			array( 'db' => 'programme_id', 'dt' => 0),
            // array( 'db' => 'faculty_id', 'dt' => 1, 'formatter' => function($id, $row){
            //     $faculty_name = $this->getitemlabel('faculty_settup','faculty_id',$row['faculty_id'],'faculty_name');
            //     return "<span class='text-uppercase'>".$faculty_name."</span>";
            // } ),
            array( 'db' => 'department_id',  'dt' => 1, 'formatter' => function($id, $row){
                $department_name = $this->getitemlabel('department_setup_tbl','dapartment_id',$row['department_id'],'department_name');
                return "<span class='text-uppercase'>".$department_name."</span>";
            }),
            array( 'db' => 'programme_name',  'dt' => 2, 'formatter' => function($id, $row){
                return "<span class='text-uppercase'>".$row['programme_name']."</span>";
            }),
            array( 'db' => 'programme_duration',  'dt' => 3, 'formatter' => function($id, $row){
                return $row['programme_duration']." years";
            }),
            array( 'db' => 'established',  'dt' => 4 ),
            array( 'db' => 'status',   'dt' => 5, 'formatter'=>function($id, $row){
                if($row['status'] == 0){
                    return "<span style='cursor:pointer' class='badge badge-danger'>INACTIVE</span>";
                }else {
                    return "<span style='cursor:pointer' class='badge badge-success'>ACTIVE</span>";
                }
            }),
            array( 'db' => 'posted_by',  'dt' => 6 ),
            array( 'db' => 'programme_id',   'dt' => 7, 'formatter'=>function($d,$row){
                $locking = ($row['status']==0)?"Enable":"Disable";
                $locking_class = ($row['status']==0)?"btn-success":"btn-danger";
                $edit_class = ($row['status'] == 0)?"display:none":"";
                if($_SESSION['role_id_sess'] == 001){
                    $status = ($row['status']==1)?"<button onclick=\"trigDepartment('".$d."','".$row['status']."')\" class='btn btn-sm ".$locking_class."'>".$locking."</button>":"<button onclick=\"trigDepartment('".$d."','".$row['status']."')\" class='btn btn-sm ".$locking_class."'>".$locking."</button>";
                    return  $status."  "."<a class='btn btn-sm btn-warning' style='".$edit_class."' onclick=\"getModal('setup/programme_setup.php?op=edit&programme_id=".$d."','modal_div_lg')\"  href=\"javascript:void(0)\" data-toggle=\"modal\" data-target=\"#sizedModalLg\" >EDIT</a>";
                }
                
            }),
		);
        $datatableEngine = new engine();
		echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key);
    }

    public function register($data){
        // check if record does not exists before then insert 
            if($data['operation'] != 'edit'){
                $validation = $this->validate(
                    $data,
                    array(
                        'faculty'    =>'required',
                        'department'    =>'required',
                        'programme_name'    =>'required',
                        'programme_duration'    =>'required',
                        'established' =>'required',
                        'status'   =>'required'
                    ),
                    array(
                        'faculty' => 'Faculty name',
                        'department' => 'Department name',
                        'programme_name' => 'Programme name',
                        'programme_duration' => 'Programme duration',
                        'established' => 'Established year',
                        'status'   => 'Status'
                    )
                );
                if(!$validation['error'])
                {
                    $date = date('Y-m-d h:i:s');
                    $programme_name = strtoupper(filter_var($data["programme_name"], FILTER_SANITIZE_STRING));
                    $programme_duration = filter_var($data["programme_duration"], FILTER_SANITIZE_STRING);
                    $faculty_id = $data["faculty"];
                    $department_id = $data["department"];
                    $established = $data["established"];
                    $status = $data["status"];
                    $posted_by = $data["posted_by"];
                    $query = "INSERT INTO programme_setup(programme_name, programme_duration, created, modified, posted_by, status, department_id, faculty_id, established) 
                        VALUES('".$programme_name."', ".$programme_duration.", '".$date."', '".$date."', '".$posted_by."', ".$status.", ".$department_id.", ".$faculty_id.", '".$established."')";
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
                $validation = $this->validate(
                    $data,
                    array(
                        'faculty'    =>'required',
                        'department'    =>'required',
                        'programme_name'    =>'required',
                        'programme_duration'    =>'required',
                        'established' =>'required',
                        'status'   =>'required'
                    ),
                    array(
                        'faculty' => 'Faculty name',
                        'department' => 'Department name',
                        'programme_name' => 'Programme name',
                        'programme_duration' => 'Programme duration',
                        'established' => 'Established year',
                        'status'   => 'Status'
                    ) 
                );
                if(!$validation['error']){
                    $date = date('Y-m-d h:i:s');
                    $programme_name = strtoupper(filter_var($data["programme_name"], FILTER_SANITIZE_STRING));
                    $programme_duration = filter_var($data["programme_duration"], FILTER_SANITIZE_STRING);
                    $faculty_id = $data["faculty"];
                    $department_id = $data["department"];
                    $established = $data["established"];
                    $status = $data["status"];
                    $posted_by = $data["posted_by"];
                    $query = "UPDATE programme_setup SET programme_name = '".$programme_name."', programme_duration = ".$programme_duration.", modified = '".$date."', department_id = ".$department_id.", faculty_id = ".$faculty_id.", status = ".$status.", established = '".$established."' WHERE programme_id = ".$data["programme_id"];
                    // echo $query;
                    $count = $this->db_query($query, false);                    
                    if($count > 0){
                        return json_encode(array("response_code"=>0,"response_message"=>'Record Updated successfully'));
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

    public function saveProgramme($data){
        $programme_name = filter_var($data["programme_name"], FILTER_SANITIZE_STRING);
        $role_id = $_SESSION['role_id_sess'];
        $validation = "";
        if($role_id == 001){
            $validation = $this->validate(
                $data,
                array(
                    'faculty'    =>'required',
                    'department'    =>'required',
                    'programme_name'    =>'required',
                    'programme_duration'    =>'required',
                    'established' =>'required',
                    'status'   =>'required'
                ),
                array(
                    'faculty' => 'Faculty name',
                    'department' => 'Department name',
                    'programme_name' => 'Programme name',
                    'programme_duration' => 'Programme duration',
                    'established' => 'Established year',
                    'status'   => 'Status'
                )
            );
            if(!$validation['error']){
                if($data['operation'] == "new"){
                    $programme_name_query = $this->getitemlabel('programme_setup','programme_name',$programme_name,'programme_id');
                    $department_name = $this->getitemlabel('department_setup_tbl','dapartment_id',$data['department'],'department_name');
                    if($programme_name_query != ""){
                        $validation['error'] = true;
                        $validation['messages'][0] = $programme_name." already exist. There can only be one programme with this name under ".$department_name;
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
    
    public function changeProgrammeStatus($data){
        $programme_id = $data['programme_id'];
        $status   = ($data['current_status'] == 1)?"0":"1";
        $sql      = "UPDATE programme_setup SET status = '$status' WHERE programme_id = '$programme_id' LIMIT 1";
        $cc = $this->db_query($sql,false);
        if($cc)
        {
            return json_encode(array('response_code'=>0,'response_message'=>'Action on department profile is now effective'));
        }else
        {
            return json_encode(array('response_code'=>432,'response_message'=>'Action failed'));
        }
        
    }
}