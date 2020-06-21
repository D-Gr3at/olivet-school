<?php
error_reporting(1);
session_start();
// $included_files = get_included_files();
// foreach ($included_files as $filename) {
//     echo "$filename\n";
// }
Class SchoolFeesItem extends dbobject{
    
    public function itemList($data){
		$table_name    = "school_fees_item_setup";
		$primary_key   = "school_item_id";
		$columner = array(
			array( 'db' => 'school_item_id', 'dt' => 0 ),
            array( 'db' => 'item_name', 'dt' => 1, 'formatter'=>function($id, $row){
                return "<span class='text-capitalize'>".$row["item_name"]."</span>";
            }),
            array( 'db' => 'created',  'dt' => 2 ),
            array( 'db' => 'modified',  'dt' => 3 ),
            array( 'db' => 'status',   'dt' => 4, 'formatter'=>function($id, $row){
                if($row['status'] == 0){
                    return "<span style='cursor:pointer' class='badge badge-danger'>INACTIVE</span>";
                }else {
                    return "<span style='cursor:pointer' class='badge badge-success'>ACTIVE</span>";
                }
            }),
            array( 'db' => 'posted_by',  'dt' => 5 ),
            array( 'db' => 'school_item_id',   'dt' => 6, 'formatter'=>function($d,$row){
                $locking = ($row['status']==0)?"Enable":"Disable";
                $locking_class = ($row['status']==0)?"btn-success":"btn-danger";
                $edit_class = ($row['status'] == 0)?"display:none":"";
                if($_SESSION['role_id_sess'] == 001){
                    $status = ($row['status']==1)?"<button onclick=\"trigSchoolFeesItem('".$d."','".$row['status']."')\" class='btn btn-sm ".$locking_class."'>".$locking."</button>":"<button onclick=\"trigSchoolFeesItem('".$d."','".$row['status']."')\" class='btn btn-sm ".$locking_class."'>".$locking."</button>";
                    return  $status."  "."<a class='btn btn-sm btn-warning' style='".$edit_class."' onclick=\"getModal('setup/school_fees_item_setup.php?op=edit&school_item_id=".$d."','modal_div_lg')\"  href=\"javascript:void(0)\" data-toggle=\"modal\" data-target=\"#sizedModalLg\" >EDIT</a>";
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
                        'item_name'=>'required',
                        'status'=>'required'
                        ),
                    array(
                        'item_name'=>'Faculty Name',
                        'status'=>'Status'
                        )
                    );
                if(!$validation['error']){
                    $item_name = $data["item_name"];
                    $statuses = $data["status"];
                    $data['created'] = date('Y-m-d h:i:s');
                    foreach($item_name as $key => $value){
                        $query_select_id = "SELECT school_item_id from school_fees_item_setup  ORDER BY school_item_id DESC LIMIT 1";
                        $run_query_select_id = $this->db_query($query_select_id);
                        $item_id = $run_query_select_id[0]["school_item_id"];
                        $item_id = $item_id + 1;

                        $query = "INSERT INTO school_fees_item_setup VALUES(".$item_id.",'".$value."', ".$statuses[$key].", '".$data['posted_by']."', '".$data['created']."', '".$data["created"]."')";
                        $count = $this->db_query($query, false);
                    }
                    if($count > 0){
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
                $validation = $this->validate($data,
                        array(
                            'item_name'=>'required',
                            'status'=>'required'
                            ),
                        array(
                            'item_name'=>'Item Name',
                            'status'=>'Status'
                            )
                       );
                if(!$validation['error']){
                    $item_name = $data["item_name"];
                    $status = $data["status"];
                    $data['modified'] = date('Y-m-d h:i:s');
                    foreach($item_name as $key => $value){
                        $query = "UPDATE school_fees_item_setup SET item_name = '".$value."', status = ".$status[$key].", modified = '".$data['modified']."' WHERE  school_item_id = ".$data["item_id"];
                        // echo $query;
                        $count = $this->db_query($query, false);
                    }
                    if($count > 0){
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

    public function saveItem($data){
        // var_dump($data);
        $item_name = $data["item_name"];
        $role_id = $_SESSION['role_id_sess'];
        $validation = "";
        if($role_id == 001){
            $validation = $this->validate(
                $data,
                array(
                    'item_name'    =>'required',
                    'status'   =>'required'
                ),
                array(
                    'faculty_name' => 'Item Name',
                    'status'   => ' Status'
                )
            );
            if(!$validation['error']){
                $item_names = $this->db_query("SELECT item_name FROM school_fees_item_setup");
                if($data['operation'] == "new"){
                    foreach($item_name as $key => $value){
                        foreach($item_names as $k => $v){
                            if(in_array($value, $v)){
                                $validation['error'] = true;
                                $validation['messages'][0] = $value." already exist. There can only be one item with this name. ";
                            }
                        }
                        if($value == ""){
                            $validation['error'] = true;
                            $validation['messages'][0] = "Item name field is required.";
                        }
                        if($data["status"][$key] == ""){
                            $validation['error'] = true;
                            $validation['messages'][0] = "Status field is required.";
                        }
                    }
                    if($validation['error'] != true){
                        return $this->register($data);
                    }   
                }else if($data['operation'] != "new"){
                    foreach($item_name as $key => $value){
                        if($value == ""){
                            $validation['error'] = true;
                            $validation['messages'][0] = "Item name field is required.";
                        }
                        if($data["status"][$key] == ""){
                            $validation['error'] = true;
                            $validation['messages'][0] = "Status field is required.";
                        }
                    }
                    if($validation['error'] != true){
                        return $this->register($data);
                    }
                }
            }
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
        }
    }
    
    public function changeSchoolFeesItemStatus($data){
        $item_id = $data['school_item_id'];
        $status   = ($data['current_status'] == 1)?"0":"1";
        $sql      = "UPDATE school_fees_item_setup SET status = '$status' WHERE school_item_id = '$item_id' LIMIT 1";
        $cc = $this->db_query($sql,false);
        if($cc)
        {
            return json_encode(array('response_code'=>0,'response_message'=>'Action on item profile is now effective'));
        }else
        {
            return json_encode(array('response_code'=>432,'response_message'=>'Action failed'));
        }
        
    }
}