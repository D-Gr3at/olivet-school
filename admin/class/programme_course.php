<?php

use phpDocumentor\Reflection\Types\Null_;

session_start();
include_once('../libs/dbfunctions.php');
class ProgrammeCourse extends dbobject{

    public function programmeCourseList($data){
		$table_name    = "programme_course_setup_tbl";
        $primary_key   = "programme_course_id";
		$columner = array(
            array( 'db' => 'programme_course_id', 'dt' => 0),
            array( 'db' => 'department_id',  'dt' => 1, 'formatter'=>function($id, $row){
                $department_name = $this->getitemlabel('department_setup_tbl','dapartment_id', $row['department_id'], 'department_name');
                return $department_name;
            }),
            array( 'db' => 'programme_id', 'dt' => 2, 'formatter'=>function($id, $row){
                $programme_name = $this->getitemlabel('programme_setup','programme_id', $row['programme_id'], 'programme_name');
                return $programme_name;
            }),
            // array( 'db' => 'level',  'dt' => 3, 'formatter'=>function($id, $row){
            //     $department_option_name = $this->getitemlabel('department_option','option_id', $row['option_id'], 'option_name');
            //     return ($department_option_name == NULL)? "N/A":$department_option_name;
            // }),
            array( 'db' => 'level',  'dt' => 3),
            array( 'db' => 'semester',  'dt' => 4, 'formatter' => function($id, $row){
                if($row['semester'] == 1){
                    return "First Semester";
                }else{
                    return "Second Semester";
                }
            }),
            array( 'db' => 'modified',  'dt' => 5),
            array( 'db' => 'status',   'dt' => 6, 'formatter'=>function($id, $row){
                if($row['status'] == 0){
                    return "<span style='cursor:pointer' class='badge badge-danger'>INACTIVE</span>";
                }else {
                    return "<span style='cursor:pointer' class='badge badge-success'>ACTIVE</span>";
                }
            }),
            array( 'db' => 'posted_by',  'dt' => 7 ),
            array( 'db' => 'programme_course_id',   'dt' => 8, 'formatter'=>function($d,$row){
                $locking = ($row['status']==0)?"Enable":"Disable";
                $locking_class = ($row['status']==0)?"btn-success":"btn-danger";
                $edit_class = ($row['status'] == 0)?"display:none":"";
                if($_SESSION['role_id_sess'] == 001){
                    $status = ($row['status']==1)?"<button onclick=\"trigProgrammeCourse('".$d."','".$row['status']."')\" class='btn btn-sm ".$locking_class."'>".$locking."</button>":"<button onclick=\"trigProgrammeCourse('".$d."','".$row['status']."')\" class='btn btn-sm ".$locking_class."'>".$locking."</button>";
                    return  $status."  "."<a class='btn btn-sm btn-warning' style='".$edit_class."' onclick=\"getModal('setup/programme_course_setup.php?op=edit&programme_course_id=".$d."','modal_div_lg')\"  href=\"javascript:void(0)\" data-toggle=\"modal\" data-target=\"#sizedModalLg\" >EDIT</a>";
                }  
            }),
        );
        $datatableEngine = new engine();
		echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key);
    }

    public function getDepartments($data){
        $faculty_id = filter_var($data["id"],FILTER_SANITIZE_STRING);
        $query = "SELECT dapartment_id, department_name, faculty_code FROM department_setup_tbl WHERE faculty_code='$faculty_id'";
        $result = $this->db_query($query);
        return json_encode(array("response_code"=>0,"response_message"=>'successful', "data"=>$result));
    }

    public function getDepartmentOptions($data){
        $department_id = filter_var($data["id"], FILTER_SANITIZE_STRING);
        $query = "SELECT option_id, option_name FROM department_option WHERE department_id='$department_id'";
        $result = $this->db_query($query);
        return json_encode(array("response_code"=>0,"response_message"=>'successful', "data"=>$result));
    }

    public function changeProgrammeCourseStatus($data){
        $programme_course_id = $data['programme_course_id'];
        $status   = ($data['current_status'] == 1)?"0":"1";
        $sql      = "UPDATE programme_course_setup_tbl SET status =".$status." WHERE curriculum_id =".$programme_course_id."";
        $cc = $this->db_query($sql,false);
        if($cc){
            return json_encode(array('response_code'=>0,'response_message'=>'Action on programme course setup is now effective'));
        }else{
            return json_encode(array('response_code'=>432,'response_message'=>'Action failed'));
        } 
    }

      public function register($data){
		//  check if record does not exists before then insert 
            if($data['operation'] != 'edit'){
                $validation = $this->validate(
                    $data,
                    array(
                        'faculty' =>'required',
                        'department' =>'required',
                        'department_option' =>'required',
                        'semester' =>'required',
                        'level' =>'required'
                    ),
                    array(
                        'faculty' => 'Faculty name',
                        'department' => 'Department name',
                        'department_option' => 'Programme name',
                        'semester' => 'Semester',
                        'level' => 'Level'
                    )
                );
               if(!$validation['error']){
                    $sql_course_setup = "SELECT course_id FROM course_setup_tbl ORDER BY course_id DESC LIMIT 1";
                    $result = mysql_query($sql_course_setup);
                    $course_id = mysql_fetch_row($result);
                    $course_id = $course_id[0];
                    $data['created'] = date('Y-m-d h:i:s');
                    $data['status'] = 1;
                    $data['closure_date'] = date($data['closure_date'].' h:i:s');
                    $query = "INSERT INTO programme_course_setup_tbl(faculty_id, department_id, programme_id, created, modified, status, posted_by, level, semester)
                             VALUES (".$data['faculty'].", ".$data['department'].",".$data['department_option'].", '".$data['created']."', '".$data['created']."',".$data['status'].", '".$data['posted_by']."', ".$data['level'].", ".$data['semester'].")";
                    $check = $this->db_query($query, false);
                    // if($data['department_option'] == ""){
                    // }else{
                    //     $query = "INSERT INTO curriculum_setup_tbl VALUES (".$data['curriculum_id'].",'".$data['created']."','".$data['posted_by']."',".$data['department_option'].", ".$data['semester'].",". $data['level'].", ".$data['status'].", '".$data['created']."', ".$data['department'].", '".$data['closure_date']."')";
                    // }
                    // if($data["semester"] == '1'){
                    //     $sem = "First Semester";
                    // }else{
                    //     $sem = "Second Semester";
                    // }
                    // $semester_end = $this->db_query("SELECT semester_end FROM semester_setup WHERE academic_session = ".$data["session_id"]." AND semester_name = '".$sem."'");
                    // $semester_end = $semester_end[0];
                    // if($data["closure_date"] > $semester_end['semester_end']){
                    //     return json_encode(array("response_code"=>78,"response_message"=>'Course registration closure exceeds semester end. Please select an ealier date.'));
                    // }
                    // if($curriculum_setup_count == 1){
                    //     $check += $curriculum_setup_count;
                    // }
                    $course_titles = $data['course_title'];
                    $course_codes = $data["course_code"];
                    $course_durations = $data["course_duration"];
                    $course_units = $data["course_unit"];
                    $sql_course_setup = "SELECT programme_course_id FROM programme_course_setup_tbl ORDER BY programme_course_id DESC LIMIT 1";
                    $result = mysql_query($sql_course_setup);
                    $course_id = mysql_fetch_row($result);
                    $programme_course_id = $course_id[0];
                    foreach($course_titles as $key => $value){                    
                        if($value == ""){
                            $validation['error'] = true;
                            $validation['messages'][0] = "Course title field is required";
                            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
                        }
                        if($course_codes[$key] == ""){
                            $validation['error'] = true;
                            $validation['messages'][0] = "Course code field is required";
                            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
                        }
                        if($course_durations[$key] == ""){
                            $validation['error'] = true;
                            $validation['messages'][0] = "Course duration field is required";
                            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
                        }
                        if($course_units[$key] == ""){
                            $validation['error'] = true;
                            $validation['messages'][0] = "Course unit field is required";
                            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
                        }

                        $query = "INSERT INTO course_setup_tbl(course_title, course_duration, course_unit, course_code, programme_course_fk) 
                        VALUES ('". $value."',".$course_durations[$key].", ".$course_units[$key].", '".$course_codes[$key]."', ".$programme_course_id.")";
                        $check = $this->db_query($query, false);
                    }
                    // for ($count = 0; $count < sizeof($data['course_title']); $count++){
                    //     $course_id = $course_id + 1;
                    //     if($data['isElective'][$count] == "1"){
                    //         $isElective = 1;
                    //     }else{
                    //         $isElective = 0;
                    //     }
                    //     $result = $this->db_query($query, false);
                    //     if($result == 1){
                    //         $check += $result;
                    //     }
                    // }                    
                    if($check > 0){
                        return json_encode(array("response_code"=>0,"response_message"=>'Record saved successfully'));
                    }else{
                        return json_encode(array("response_code"=>78,"response_message"=>'Failed to save record'));
                    } 
                }else{
                    return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
                }
            }else{
            //   EDIT EXISTING FACULTY 
                $check = 0;
                $data['modified_date'] = date('Y-m-d h:i:s');
                $validation = $this->validate($data,
                            array(
                                'faculty' =>'required',
                                'department' =>'required',
                                'department_option' =>'required',
                                'semester' =>'required',
                                'level' =>'required'
                            ),
                            array(
                                'faculty' => 'Faculty name',
                                'department' => 'Department name',
                                'department_option' => 'Programme name',
                                'semester' => 'Semester',
                                'level' => 'Level'
                            )
                       );
                if(!$validation['error']){
                    // var_dump($data);
                    $query = "UPDATE programme_course_setup_tbl SET posted_by='".$data['posted_by']."', programme_id=".$data['department_option'].", semester=".$data['semester'].", level=".$data['level'].", modified = '".$data['modified_date']."', department_id = ".$data['department'].", faculty_id = '".$data['faculty']."' WHERE programme_course_id =".$data['programme_course_id']."";
                    // echo $query."\n";
                    $count = $this->db_query($query, false);
                    if($count > 0){
                        $check += 1;
                    }
                    $sql_course_setup = "SELECT course_id FROM course_setup_tbl ORDER BY course_id DESC LIMIT 1";
                    $result = mysql_query($sql_course_setup);
                    $course_id = mysql_fetch_row($result);
                    $course_id = $course_id[0];
                    $course_ids = (array)$data['course_id'];
                    // var_dump($course_ids);
                    $course_titles = $data['course_title'];
                    // var_dump($course_titles);
                    if(sizeof($course_ids) != sizeof($course_titles)){
                        $diff = sizeof($course_titles) - sizeof($course_ids);
                        // var_dump($diff);
                        for($k = 0; $k < $diff; $k++){
                            $course_id += 1;
                            array_push($course_ids, (string)$course_id);
                            // var_dump($course_ids);
                            // var_dump(end($course_ids));
                            $key = end(array_keys($course_ids));
                            // var_dump($key);
                            // var_dump($data['course_title'][$key]);
                            if($data['course_title'][$key] == ""){
                                $validation['error'] = true;
                                $validation['messages'][0] = "Course title field is required";
                                return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
                            }
                            if($data['course_code'][$key] == ""){
                                $validation['error'] = true;
                                $validation['messages'][0] = "Course code field is required";
                                return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
                            }
                            if($data['course_duration'][$key] == ""){
                                $validation['error'] = true;
                                $validation['messages'][0] = "Course duration field is required";
                                return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
                            }
                            if($data['course_unit'][$key] == ""){
                                $validation['error'] = true;
                                $validation['messages'][0] = "Course unit field is required";
                                return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
                            }
                            $query = "INSERT INTO course_setup_tbl(course_title, course_duration, course_unit, course_code, programme_course_fk) 
                                    VALUES ('". $data['course_title'][$key]."',". $data['course_duration'][$key].",". $data['course_unit'][$key].",'". $data['course_code'][$key]."', ".$data['programme_course_id'].")";
                            // echo $query."\n";
                            $result = $this->db_query($query, false);
                            if($result > 0){
                                $check += $result;
                            }
                        }
                    }

                    for ($count = 0; $count < sizeof($data['course_title']); $count++){
                        $query = "UPDATE course_setup_tbl SET course_title = '".$data['course_title'][$count]."', course_duration=".$data['course_duration'][$count].", course_unit=".$data['course_unit'][$count].", course_code='".$data['course_code'][$count]."' WHERE course_id=".$data['course_id'][$count]."";
                        // echo $query."\n";
                        $result = $this->db_query($query, false);
                        if($result > 0){
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
    
    public function saveProgrammeCourse($data){
        // var_dump($data);
        $role_id = $_SESSION['role_id_sess'];
        $validation = "";
        if($role_id == 001){
            $validation = $this->validate(
                $data,
                array(
                    'faculty'    =>'required',
                    'department'    =>'required',
                    'department_option'    =>'required',
                    'semester' =>'required',
                    'level' =>'required'
                ),
                array(
                    'faculty' => 'Faculty name',
                    'department' => 'Department name',
                    'department_option' => 'Programme name',
                    'semester' => 'Semester',
                    'level' => 'Level '
                )
            );
            
            if(!$validation['error']){
                if($data['operation'] == "new"){
                    $sql = "SELECT * FROM programme_course_setup_tbl WHERE level = ".$data['level']." AND semester =".$data['semester']." AND department_id = ".$data['department']." AND faculty_id = ".$data["faculty"];
                    $curriculum_result = $this->db_query($sql, false);
                    if($curriculum_result > 0){
                        $validation['error'] = true;
                        $validation['messages'][0] = "This setup has already been done. There can only be one setup of this type.";
                    }else {
                       return $this->register($data);
                    }
                }else{
                    // var_dump($data);
                    $ids = array();
                    $course_ids = $this->db_query("SELECT course_id FROM course_setup_tbl WHERE programme_course_fk = ".$data['programme_course_id']);
                    for($i = 0; $i < sizeof($course_ids); $i++){
                        $ids[$i] = $course_ids[$i]['course_id'];
                    }
                    // var_dump($ids);
                    $result = array_diff($ids, $data['course_id']);
                    $ids_del = array_values($result);
                    // var_dump($ids_del);
                    $check = 0;
                    if($ids_del != NULL){
                        foreach($ids_del as $key => $value){
                            $check = $this->db_query("DELETE FROM course_setup_tbl WHERE course_id = ".$value, false);
                            // echo $check."\n";
                            $check += $check;
                        }
                    }
                    return $this->register($data, $check);
                }
            }
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
        }
    }
}
?>