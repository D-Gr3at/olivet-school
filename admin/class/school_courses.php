<?php

// use phpDocumentor\Reflection\Types\Null_;

// session_start();
// include_once('../libs/dbfunctions.php');
class SchoolCourses extends dbobject{

    public function schoolCoursesList($data){
		$table_name    = "course_setup_tbl";
        $primary_key   = "course_id";
		$columner = array(
            array( 'db' => 'a.course_id', 'dt' => 0),
            array( 'db' => 'a.course_title', 'dt' => 1),
            array( 'db' => 'a.course_code', 'dt' => 2),
            array( 'db' => 'a.course_duration', 'dt' => 3),
            array( 'db' => 'a.course_unit', 'dt' => 4),
            array( 'db' => 'b.department_id',  'dt' => 5, 'formatter'=>function($id, $row){
                $department_name = $this->getitemlabel('department_setup_tbl','dapartment_id', $row['department_id'], 'department_name');
                return "<span class='text-uppercase'>".$department_name."</span>";
            }),
            array( 'db' => 'b.programme_id', 'dt' => 6, 'formatter'=>function($id, $row){
                $programme_name = $this->getitemlabel('programme_setup','programme_id', $row['programme_id'], 'programme_name');
                return "<span class='text-uppercase'>".$programme_name."</span>";
            }),
            array( 'db' => 'b.level',  'dt' => 7, 'formatter'=>function($id, $row){
                return "<span class='text-uppercase'>".$id."</span>";
            }),
            array( 'db' => 'b.semester',  'dt' => 8, 'formatter' => function($id, $row){
                if($id == '1'){
                    return "FIRST SEMESTER";
                }else{
                    return "SECOND SEMESTER";
                }
            }),
            array( 'db' => 'a.status',   'dt' => 9, 'formatter'=>function($id, $row){
                if($row['status'] == 0){
                    return "<span style='cursor:pointer' class='badge badge-danger'>INACTIVE</span>";
                }else {
                    return "<span style='cursor:pointer' class='badge badge-success'>ACTIVE</span>";
                }
            }),
            array( 'db' => 'a.course_id',   'dt' => 10, 'formatter'=>function($d,$row){
                $locking = ($row['status']==0)?"Enable":"Disable";
                $locking_class = ($row['status']==0)?"btn-success":"btn-danger";
                $edit_class = ($row['status'] == 0)?"display:none":"";
                if($_SESSION['role_id_sess'] == 001){
                    $status = ($row['status']==1)?"<button onclick=\"trigSchoolCourses('".$d."','".$row['status']."')\" class='btn btn-sm ".$locking_class."'>".$locking."</button>":"<button onclick=\"trigSchoolCourses('".$d."','".$row['status']."')\" class='btn btn-sm ".$locking_class."'>".$locking."</button>";
                    return  $status."  "."<a class='btn btn-sm btn-warning' style='".$edit_class."' onclick=\"getModal('setup/course_setup.php?op=edit&course_id=".$d."','modal_div_lg')\"  href=\"javascript:void(0)\" data-toggle=\"modal\" data-target=\"#sizedModalLg\" >EDIT</a>";
                }  
            }),
        );
        $filter = "";
        $table_name .= " a JOIN programme_course_setup_tbl b ON a.programme_course_fk = b.programme_course_id ";

        $department = $data["search_department"];
        $level = $data["search_level"];
        $semester = $data["search_semester"];
        $programme = $data["search_programme"];
        $filter .= (isset($department) && $department != "")? " AND b.department_id = '$department' " : "";
        $filter .= (isset($programme) && $programme != "")? " AND b.programme_id = '$programme' " : "";
        $filter .= (isset($level) && $level != "")? " AND b.level = '$level' " : "";
        $filter .= (isset($semester) && $semester != "")? " AND b.semester = '$semester' " : "";
        file_put_contents("file.txt", $filter);
        $datatableEngine = new engine();
		echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key);
    }

    // public function getDepartments($data){
    //     $faculty_id = filter_var($data["id"],FILTER_SANITIZE_STRING);
    //     $query = "SELECT dapartment_id, department_name,faculty_code FROM department_setup_tbl WHERE faculty_code='$faculty_id'";
    //     $result = $this->db_query($query);
    //     return json_encode(array("response_code"=>0,"response_message"=>'successful', "data"=>$result));
    // }

    // public function getProgramme($data){
    //     // var_dump($data);
    //     $department_id = filter_var($data["id"], FILTER_SANITIZE_STRING);
    //     $query = "SELECT programme_id, programme_name FROM programme_setup WHERE department_id='$department_id'";
    //     $result = $this->db_query($query);
    //     return json_encode(array("response_code"=>0,"response_message"=>'successful', "data"=>$result));
    // }

    // public function getProgrammeCourses($data){
    //     $programme_id = filter_var($data["programme_id"], FILTER_SANITIZE_STRING);
    //     $level = filter_var($data["level"], FILTER_SANITIZE_STRING);
    //     $semester = filter_var($data["semester"], FILTER_SANITIZE_STRING);
    //     $query = "SELECT course_id, course_title, course_code, course_unit FROM programme_course_setup_tbl pc INNER JOIN course_setup_tbl cs ON pc.programme_course_id = cs.programme_course_fk 
    //             WHERE pc.programme_id=".$programme_id." AND pc.level = ".$level;
    //     $result = $this->db_query($query);
    //     return json_encode(array("response_code"=>0,"response_message"=>'successful', "data"=>$result));
    // }

    // public function getProgrammeCourseDetails($data){
    //     $course_id = filter_var($data["id"], FILTER_SANITIZE_STRING);
    //     $query = "SELECT course_id, course_title, course_code, course_unit FROM course_setup_tbl WHERE course_id = ".$course_id;
    //     $result = $this->db_query($query);
    //     return json_encode(array("response_code"=>0,"response_message"=>'successful', "data"=>$result));
    // }

    public function changeCourseStatus($data){
        $course_id = $data['id'];
        $status   = ($data['current_status'] == 1)?"0":"1";
        $sql      = "UPDATE course_setup_tbl SET status = ".$status." WHERE course_id =".$course_id."";
        // echo $sql."\n";
        $cc = $this->db_query($sql,false);
        if($cc){
            return json_encode(array('response_code'=>0,'response_message'=>'Action on course is now effective'));
        }else{
            return json_encode(array('response_code'=>432,'response_message'=>'Action failed'));
        } 
    }

      public function register($data, $check){
		//  check if record does not exists before then insert 
            if($data['operation'] != 'edit'){
                $validation = $this->validate(
                    $data,
                    array(
                        'faculty' =>'required',
                        'department' =>'required',
                        'department_option' =>'required',
                        'semester' =>'required',
                        'level' =>'required',
                        'closure_date' => 'Course registration closure date'
                    ),
                    array(
                        'faculty' => 'Faculty Name',
                        'department' => 'Department name',
                        'department_option' => 'Programme name',
                        'semester' => 'Semester',
                        'level' => 'Level',
                        'closure_date' => 'Course registration closure date'
                    )
                );
               if(!$validation['error']){
                    $sql_course_setup = "SELECT curriculum_course_id FROM curriculum_course_tbl ORDER BY curriculum_course_id DESC LIMIT 1";
                    $result = mysql_query($sql_course_setup);
                    $course_id = mysql_fetch_row($result);
                    $course_id = $course_id[0];
                    $data['created'] = date('Y-m-d h:i:s');
                    $data['status'] = 1;
                    $data['closure_date'] = date($data['closure_date'].' h:i:s');
                    // if($data['department_option'] == ""){
                    //     $query = "INSERT INTO curriculum_setup_tbl VALUES (".$data['curriculum_id'].",'".$data['created']."','".$data['posted_by']."',NULL, ".$data['semester'].",". $data['level'].", ".$data['status'].", '".$data['created']."', ".$data['department'].", '".$data['closure_date']."')";
                    // }else{
                    // }
                    $query = "INSERT INTO curriculum_setup_tbl VALUES (".$data['curriculum_id'].",'".$data['created']."','".$data['posted_by']."',".$data['department_option'].", ".$data['semester'].",". $data['level'].", ".$data['status'].", '".$data['created']."', ".$data['department'].", '".$data['closure_date']."')";
                    // echo $query."\n";
                    if($data["semester"] == '1'){
                        $sem = "First Semester";
                    }else{
                        $sem = "Second Semester";
                    }
                    $semester_end = $this->db_query("SELECT semester_end FROM semester_setup WHERE academic_session = ".$data["session_id"]." AND semester_name = '".$sem."'");
                    $semester_end = $semester_end[0];
                    if($data["closure_date"] > $semester_end['semester_end']){
                        return json_encode(array("response_code"=>78,"response_message"=>'Course registration closure exceeds semester end. Please select an ealier date.'));
                    }
                    $curriculum_setup_count = $this->db_query($query, false);
                    if($curriculum_setup_count > 0){
                        $check += $curriculum_setup_count;
                    }
                    for ($count = 0; $count < sizeof($data['isElective']); $count++){
                        $course_id = $course_id + 1;
                        if($data['isElective'][$count] == "1"){
                            $isElective = 1;
                        }else{
                            $isElective = 0;
                        }
                        $query = "INSERT INTO curriculum_courses_tbl(selected_department_id, selected_programme_id, selected_course_id, is_elective, curriculum_setup_fk) 
                                VALUES (".$data['selected_department'][$count].", ". $data['selected_programme'][$count].",". $data['selected_course_code'][$count].", ".$isElective.",".$data['curriculum_id'].")";
                        // echo $query."\n";
                        $result = $this->db_query($query, false);
                        if($result > 0){
                            $check += $result;
                        }
                    }                    
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
                $data['modified_date'] = date('Y-m-d h:i:s');
                $validation = $this->validate($data,
                            array(
                                'faculty'    =>'required',
                                'department'    =>'required',
                                'department_option'    =>'required',
                                'semester' =>'required',
                                'level' =>'required',
                                'course_title'   =>'required',
                                'course_code'   =>'required',
                                'course_duration'   =>'required',
                                'course_unit'   =>'required'
                            ),
                            array(
                                'faculty' => 'Faculty name',
                                'department' => 'Department name',
                                'department_option' => 'Programme name',
                                'semester' => 'Semester',
                                'level' => 'Level',
                                'course_title' => 'Course title',
                                'course_unit' => 'Course unit',
                                'course_code' => 'Course code',
                                'course_duration' => 'Course duration'
                            )
                       );
                if(!$validation['error']){
                    $query = "UPDATE programme_course_setup_tbl SET faculty_id = ".$data['faculty'].", programme_id = ".$data["department_option"].", semester = ".$data['semester'].", level=".$data['level'].", modified = '".$data['modified_date']."', department_id = ".$data['department']." WHERE programme_course_id =".$data['programme_course_id']."";
                    // echo $query."\n";
                    $curriculum_setup_count = $this->db_query($query, false);
                    if($curriculum_setup_count > 0){
                        $check += $curriculum_setup_count;
                    }
                    $query = "UPDATE course_setup_tbl SET course_title = '".$data['course_title']."', course_code = '".$data['course_code']."', course_duration = ".$data['course_duration'].", course_unit = ".$data['course_unit']." WHERE course_id = ".$data['course_id']."";
                    // echo $query."\n";
                    $result = $this->db_query($query, false);
                    if($result > 0){
                        $check += $result;
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
    
    public function saveSchoolCourse($data){
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
                    'level' =>'required',
                    'course_title'   =>'required',
                    'course_code'   =>'required',
                    'course_duration'   =>'required',
                    'course_unit'   =>'required'
                ),
                array(
                    'faculty' => 'Faculty name',
                    'department' => 'Department name',
                    'department_option' => 'Programme name',
                    'semester' => 'Semester',
                    'level' => 'Level',
                    'course_title' => 'Course title',
                    'course_unit' => 'Course unit',
                    'course_code' => 'Course code',
                    'course_duration' => 'Course duration'
                )
            );
            
            if(!$validation['error']){
                if($data['operation'] == "new"){
                    $sql = "SELECT * FROM curriculum_setup_tbl WHERE level = ".$data['level']." AND semester =".$data['semester']." AND programme_id = ".$data['department_option']." AND department_id = ".$data['department']."";
                    // echo $sql."\n";
                    $curriculum_result = $this->db_query($sql, false);
                    if($curriculum_result > 0){
                        $validation['error'] = true;
                        $validation['messages'][0] = "This setup has already been done. There can only be one curriculum of this type.";
                    }else {
                       return $this->register($data, $curriculum_result);
                    }
                }else{
                    // var_dump($data);
                    $check = 0;
                    return $this->register($data, $check);
                }
            }
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
        }
    }
}
?>