<?php
// error_reporting(1);
// session_start();
// $included_files = get_included_files();
// foreach ($included_files as $filename) {
//     echo "$filename\n";
// }
Class Faculty extends dbobject{
    
    public function facultylist($data){
		$table_name    = "faculty_settup";
		$primary_key   = " faculty_id";
		$columner = array(
			array( 'db' => 'faculty_id', 'dt' => 0 ),
            array( 'db' => 'faculty_name', 'dt' => 1, 'formatter' =>function($id, $row){
                return "<span class='text-uppercase'>".$row['faculty_name']."</span>";
            }),
            array( 'db' => 'faculty_established',  'dt' => 2 ),
            array( 'db' => 'created',  'dt' => 3 ),
            array( 'db' => 'status',   'dt' => 4, 'formatter'=>function($id, $row){
                if($row['status'] == 0){
                    return "<span style='cursor:pointer' class='badge badge-danger'>INACTIVE</span>";
                }else {
                    return "<span style='cursor:pointer' class='badge badge-success'>ACTIVE</span>";
                }
            }),
            array( 'db' => 'posted_by',  'dt' => 5 ),
            array( 'db' => 'faculty_id',   'dt' => 6, 'formatter'=>function($d,$row){
                $locking = ($row['status']==0)?"Enable":"Disable";
                $locking_class = ($row['status']==0)?"btn-success":"btn-danger";
                $edit_class = ($row['status'] == 0)?"display:none":"";
                if($_SESSION['role_id_sess'] == 001){
                    $status = ($row['status']==1)?"<button onclick=\"trigFaculty('".$d."','".$row['status']."')\" class='btn btn-sm ".$locking_class."'>".$locking."</button>":"<button onclick=\"trigFaculty('".$d."','".$row['status']."')\" class='btn btn-sm ".$locking_class."'>".$locking."</button>";
                    return  $status."  "."<a class='btn btn-sm btn-warning' style='".$edit_class."' onclick=\"getModal('setup/faculty_setup.php?op=edit&faculty_id=".$d."','modal_div_lg')\"  href=\"javascript:void(0)\" data-toggle=\"modal\" data-target=\"#sizedModalLg\" >EDIT</a>";
                }
                
            }),
		);
        $datatableEngine = new engine();
		echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key);
    }
    public function register($data)
	{
        // check if record does not exists before then insert
        // var_dump($data);
        
            if($data['operation'] != 'edit'){
                $validation = $this->validate($data,
                    array(
                        'faculty_name'=>'required',
                        // 'faculty_head'=>'required',
                        'status'=>'required',
                        'faculty_established'=>'required',
                        ),
                    array(
                        'faculty_name'=>'Faculty Name',
                        'year_established'=>'Established Year',
                        'status'=>'Status'
                        // 'faculty_head'=>'Faculty Head'
                        )
                    );
                if(!$validation['error'])
                {
                    $data['created'] = date('Y-m-d h:i:s');
                    $count = $this->doInsert('faculty_settup', $data, array('op','operation', 'email', 'account_name', 'faculty_head', 'files'));
                    // echo $count;
                    if($count == 1){
                        return json_encode(array("response_code"=>0,"response_message"=>'Record saved successfully'));
                    }else{
                        return json_encode(array("response_code"=>78,"response_message"=>'Failed to save record'));
                    }
                    
                }else{
                    // var_dump($data);
                    return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
                }
            }else{
//                EDIT EXISTING FACULTY 
                // $data['modified_date'] = date('Y-m-d h:i:s');
                $validation = $this->validate($data,
                        array(
                            'faculty_name'=>'required',
                            // 'faculty_head'=>'required',
                            'status'=>'required',
                            'faculty_established'=>'required',
                            ),
                        array(
                            'faculty_name'=>'Faculty Name',
                            'year_established'=>'Established Year',
                            'status'=>'Status'
                            )
                       );
                if(!$validation['error']){
                    $count = $this->doUpdate('faculty_settup', $data, array('op','operation', 'email', 'account_name', 'files'), array('faculty_id'=>$data['faculty_id']));
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

    public function saveFaculty($data){
        // var_dump($data);
        $faculty_name = filter_var($data["faculty_name"], FILTER_SANITIZE_STRING);
        // echo $faculty_name;
        $role_id = $_SESSION['role_id_sess'];
        $validation = "";
        //var_dump($data);
        if($role_id == 001){
            $validation = $this->validate(
                $data,
                array(
                    'faculty_name'    =>'required',
                    // 'faculty_head'    =>'required',
                    'faculty_established' =>'required',
                    'status'   =>'required'
                ),
                array(
                    'faculty_name' => 'Faculty Name',
                    // 'faculty_head' => 'Faculty Head',
                    'faculty_established' => 'Established Year',
                    'status'   => ' Status'
                )
            );
            if(!$validation['error']){
                if($data['operation'] == "new"){
                    $faculty_name = $this->getitemlabel('faculty_settup','faculty_name',$data['faculty_name'],'faculty_name');
                    if($faculty_name != ""){
                        $validation['error'] = true;
                        $validation['messages'][0] = $faculty_name." already exist. There can only be one faculty with this name. ";
                    }else {
                        return $this->register($data);
                    }
                }else if($data['operation'] != "new"){
                    return $this->register($data);
                    // $faculty_name = $this->getitemlabel('faculty_setup','faculty_name',$data['faculty_name'],'faculty_name');
                    // if($faculty_name != ""){
                    //     $validation['error'] = true;
                    //     $validation['messages'][0] = $faculty_name." already exist. There can only be one faculty with this name. ";
                    // }else{
                        
                    // }
                }
            }
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
        }
    }
    
    public function changeFacultyStatus($data){
        $faculty_id = $data['faculty_id'];
        $status   = ($data['current_status'] == 1)?"0":"1";
        $sql      = "UPDATE faculty_settup SET status = '$status' WHERE faculty_id = '$faculty_id' LIMIT 1";
        $cc = $this->db_query($sql,false);
        if($cc)
        {
            return json_encode(array('response_code'=>0,'response_message'=>'Action on faculty profile is now effective'));
        }else
        {
            return json_encode(array('response_code'=>432,'response_message'=>'Action failed'));
        }
        
    }
}