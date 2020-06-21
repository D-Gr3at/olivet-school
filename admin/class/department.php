<?php
error_reporting(1);
session_start();
// $included_files = get_included_files();
// foreach ($included_files as $filename) {
//     echo "$filename\n";
// }
Class Department extends dbobject{
    
    public function departmentList($data){
		$table_name    = "department_setup_tbl";
		$primary_key   = " dapartment_id";
		$columner = array(
			array( 'db' => 'dapartment_id', 'dt' => 0 ),
            array( 'db' => 'faculty_code', 'dt' => 1, 'formatter' => function($id, $row){
                $faculty_name = $this->getitemlabel('faculty_settup','faculty_id',$id,'faculty_name');
                return "<span class='text-uppercase'>".$faculty_name."</span>";
            } ),
            array( 'db' => 'department_name',  'dt' => 2, 'formatter' => function($id, $row){
                return "<span class='text-uppercase'>".$row['department_name']."</span>";
            }),
            array( 'db' => 'established',  'dt' => 3 ),
            array( 'db' => 'created',  'dt' => 4 ),
            array( 'db' => 'status',   'dt' => 5, 'formatter'=>function($id, $row){
                if($row['status'] == 0){
                    return "<span style='cursor:pointer' class='badge badge-danger'>INACTIVE</span>";
                }else {
                    return "<span style='cursor:pointer' class='badge badge-success'>ACTIVE</span>";
                }
            }),
            array( 'db' => 'posted_by',  'dt' => 6 ),
            array( 'db' => 'dapartment_id',   'dt' => 7, 'formatter'=>function($d,$row){
                $locking = ($row['status']==0)?"Enable":"Disable";
                $locking_class = ($row['status']==0)?"btn-success":"btn-danger";
                $edit_class = ($row['status'] == 0)?"display:none":"";
                if($_SESSION['role_id_sess'] == 001){
                    $status = ($row['status']==1)?"<button onclick=\"trigDepartment('".$d."','".$row['status']."')\" class='btn btn-sm ".$locking_class."'>".$locking."</button>":"<button onclick=\"trigDepartment('".$d."','".$row['status']."')\" class='btn btn-sm ".$locking_class."'>".$locking."</button>";
                    return  $status."  "."<a class='btn btn-sm btn-warning' style='".$edit_class."' onclick=\"getModal('setup/department_setup.php?op=edit&dapartment_id=".$d."','modal_div_lg')\"  href=\"javascript:void(0)\" data-toggle=\"modal\" data-target=\"#sizedModalLg\" >EDIT</a>";
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
                $validation = $this->validate($data,
                    array(
                        'faculty_code'=>'required',
                        'department_name'=>'required',
                        'status'=>'required',
                        'established'=>'required',
                        ),
                    array(
                        'faculty_code'=>'Faculty Name',
                        'established'=>'Established Year',
                        'status'=>'Status',
                        'department_name'=>'Department Name'
                        )
                    );
                if(!$validation['error'])
                {
                    $data['created'] = date('Y-m-d h:i:s');
                    $data['department_name'] = strtoupper($data['department_name']);
                    $count = $this->doInsert('department_setup_tbl', $data, array('op','operation', 'files'));
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
                $validation = $this->validate($data,
                        array(
                            'faculty_code'=>'required',
                            'department_name'=>'required',
                            'status'=>'required',
                            'established'=>'required',
                            ),
                        array(
                            'faculty_code'=>'Faculty Name',
                            'established'=>'Established Year',
                            'status'=>'Status',
                            'department_name'=>'Department Name'
                            )
                       );
                if(!$validation['error']){
                    $data['department_name'] = strtoupper($data['department_name']);
                    $count = $this->doUpdate('department_setup_tbl', $data, array('op','operation', 'files'), array('dapartment_id'=>$data['dapartment_id']));
                    if($count == 1){
                        return json_encode(array("response_code"=>0,"response_message"=>'Record saved successfully'));
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

    public function saveDepartment($data){
        // $department_name = filter_var($data["department_name"], FILTER_SANITIZE_STRING);
        $role_id = $_SESSION['role_id_sess'];
        $validation = "";
        if($role_id == 001){
            $validation = $this->validate(
                $data,
                array(
                    'faculty_code'    =>'required',
                    'department_name'    =>'required',
                    'established' =>'required',
                    'status'   =>'required'
                ),
                array(
                    'faculty_code' => 'Faculty Name',
                    'department_name' => 'Department Name',
                    'established' => 'Established Year',
                    'status'   => 'Status'
                )
            );
            if(!$validation['error']){
                if($data['operation'] == "new"){
                    $data['department_name'] = strtoupper($data['department_name']);
                    $department_name = $this->getitemlabel('department_setup_tbl','department_name',$data['department_name'],'department_name');
                    $faculty_name = $this->getitemlabel('faculty_settup','faculty_id',$data['faculty_code'],'faculty_name');
                    if($department_name != ""){
                        $validation['error'] = true;
                        $validation['messages'][0] = $department_name." already exist. There can only be one department with this name under ".$faculty_name;
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
    
    public function changeDepartmentStatus($data){
        $department_id = $data['department_id'];
        $status   = ($data['current_status'] == 1)?"0":"1";
        $sql      = "UPDATE department_setup_tbl SET status = '$status' WHERE dapartment_id = '$department_id' LIMIT 1";
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