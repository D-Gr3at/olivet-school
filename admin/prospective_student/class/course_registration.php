<?php
error_reporting(1);

include_once("../../libs/dbfunctions.php");
// $included_files = get_included_files();
// foreach ($included_files as $filename) {
//     echo "$filename\n";
// }

class CourseRegistration extends dbobject{

    public function getDepartments($data){
        $faculty_id = filter_var($data["id"],FILTER_SANITIZE_STRING);
        $query = "SELECT dapartment_id, department_name,faculty_code FROM department_setup_tbl WHERE faculty_code=$faculty_id";

        $courses_query = "SELECT course_id, course_title, course_code, course_unit FROM course_setup_tbl AS cs 
                            INNER JOIN curriculum_setup_tbl AS cus ON cs.curriculum_id = cus.curriculum_id
                            INNER JOIN department_option AS do ON cus.option_id = do.option_id
                            INNER JOIN department_setup_tbl AS ds ON do.department_id = ds.dapartment_id
                            WHERE ds.faculty_code = $faculty_id";
        $outcome = $this->db_query($courses_query);
        $result = $this->db_query($query);
        return json_encode(array("response_code"=>0,"response_message"=>'successful', "data"=>$result, "courses"=>$outcome));
    }

    public function getDepartmentOptions($data){
        $department_id = filter_var($data["id"], FILTER_SANITIZE_STRING);
        $query = "SELECT option_id, option_name FROM department_option WHERE department_id='$department_id'";
        $result = $this->db_query($query);
        $courses_query = "SELECT course_id, course_title, course_code, course_unit FROM course_setup_tbl AS cs 
                            INNER JOIN curriculum_setup_tbl AS cus ON cs.curriculum_id = cus.curriculum_id
                            INNER JOIN department_option AS do ON cus.option_id = do.option_id
                            WHERE do.department_id = $department_id";
        $outcome = $this->db_query($courses_query);
        return json_encode(array("response_code"=>0,"response_message"=>'successful', "data"=>$result, "courses"=>$outcome));
    }

    public function getOptionCourses($data){
        $option_id = filter_var($data["id"], FILTER_SANITIZE_STRING);
        $courses_query = "SELECT course_id, course_title, course_code, course_unit FROM course_setup_tbl AS cs 
                            INNER JOIN curriculum_setup_tbl AS cus ON cs.curriculum_id = cus.curriculum_id
                            WHERE cus.option_id = $option_id";
        $outcome = $this->db_query($courses_query);
        return json_encode(array("response_code"=>0,"response_message"=>'successful', "courses"=>$outcome));
    }

    public function getLevelCourses($data){
        $level_id = filter_var($data["id"], FILTER_SANITIZE_STRING);
        $courses_query = "SELECT course_id, course_title, course_code, course_unit FROM course_setup_tbl AS cs 
                            INNER JOIN curriculum_setup_tbl AS cus ON cs.curriculum_id = cus.curriculum_id
                            WHERE cus.level = $level_id";
        $outcome = $this->db_query($courses_query);
        return json_encode(array("response_code"=>0,"response_message"=>'successful', "courses"=>$outcome));
    }

    public function getSemesterCourses($data){
        $semester = filter_var($data["semester"], FILTER_SANITIZE_STRING);
        $level = filter_var($data["level"], FILTER_SANITIZE_STRING);
        $student_id = filter_var($data["student_id"], FILTER_SANITIZE_STRING);
        $department_id = filter_var($data["department_id"], FILTER_SANITIZE_STRING);
        $option_id = filter_var($data["department_option_id"], FILTER_SANITIZE_STRING);
        if($option_id != ""){
            $compulsory_query = "SELECT course_id, course_title, course_code, course_unit FROM course_setup_tbl AS cs 
                            INNER JOIN curriculum_setup_tbl AS cus ON cs.curriculum_id = cus.curriculum_id
                            WHERE cus.semester = ".$semester." AND cs.is_elective = 0 AND cus.level = ".$level." AND cus.department_id = ".$department_id." AND cus.option_id = ".$option_id;

            $elective_query = "SELECT course_id, course_title, course_code, course_unit FROM course_setup_tbl AS cs 
                            INNER JOIN curriculum_setup_tbl AS cus ON cs.curriculum_id = cus.curriculum_id
                            WHERE cus.semester = ".$semester." AND cs.is_elective = 1 AND cus.level = ".$level." AND cus.department_id = ".$department_id." AND cus.option_id = ".$option_id;

            $course_reg_closure_query = "SELECT closure_date FROM curriculum_setup_tbl WHERE semester = ".$semester." AND level = ".$level." AND department_id = ".$department_id." AND option_id = ".$option_id;
        }else{
            $compulsory_query = "SELECT course_id, course_title, course_code, course_unit FROM course_setup_tbl AS cs 
                            INNER JOIN curriculum_setup_tbl AS cus ON cs.curriculum_id = cus.curriculum_id
                            WHERE cus.semester = ".$semester." AND cs.is_elective = 0 AND cus.level = ".$level." AND cus.department_id = ".$department_id;

            $elective_query = "SELECT course_id, course_title, course_code, course_unit FROM course_setup_tbl AS cs 
                            INNER JOIN curriculum_setup_tbl AS cus ON cs.curriculum_id = cus.curriculum_id
                            WHERE cus.semester = ".$semester." AND cs.is_elective = 1 AND cus.level = ".$level." AND cus.department_id = ".$department_id;
    
            $course_reg_closure_query = "SELECT closure_date FROM curriculum_setup_tbl WHERE semester = ".$semester." AND level = ".$level." AND department_id = ".$department_id;
        }
        $compulsory = $this->db_query($compulsory_query);
        $elective = $this->db_query($elective_query);
        $registration_closure = $this->db_query($course_reg_closure_query);

        return json_encode(array("response_code"=>0,"response_message"=>'successful', "compulsory"=>$compulsory, "elective"=>$elective, "closure_date" =>$registration_closure));
    }

    public function saveCourseRegistration($data){
        // var_dump($data);
        $date_created = date('Y-m-d h:i:s');
        $status = 1;
        $check = 0;
        $update = 0;
        $department_id = str_replace(['department_id', '='], '', $data['data']);
        $department_id = filter_var($department_id, FILTER_SANITIZE_STRING);
        $department_option_id = filter_var($data["department_option_id"], FILTER_SANITIZE_STRING);
        $level = filter_var($data["level"], FILTER_SANITIZE_STRING);
        $semester = filter_var($data["semester"], FILTER_SANITIZE_STRING);
        $student_id = filter_var($data["student_id"], FILTER_SANITIZE_STRING);
        $compulsory_course_ids = $data['compulsory_course_id'];
        $elective_course_ids = $data['elected_course_id'];
        $is_elective = $data['elected_course'];
        $total_credit_load = filter_var($data["total_credit_load"], FILTER_SANITIZE_STRING);
        if($total_credit_load > 24){
            return json_encode(array("response_code"=>13,"response_message"=>'Failed to register. Total credit unit cannot be greater than 24'));
        }
        // if($department_option_id != ""){
            
        // }else{
        //     $query = "SELECT course_reg_id, course_id FROM course_registration cr INNER JOIN student_information si ON cr.students_id = si.student_id
        //             WHERE cr.students_id = ".$student_id." AND cr.semester = ".$semester." AND si.level = ".$level." AND si.department_id = ".$department_id;
        // }
        $query = "SELECT course_reg_id, course_id FROM course_registration WHERE student_id = '".$student_id."' AND semester = ".$semester." AND level = ".$level;
        echo $query."\n";
        $result = $this->db_query($query);
        if($result != NULL){
            $db_course_ids = array();
            foreach($result as $index => $value){
                array_push($db_course_ids, $value['course_id']);
            }
            var_dump($db_course_ids);
            foreach($elective_course_ids as $key => $value){
                foreach($result as $key1 => $value1){
                    if($value == $value1['course_id']){
                        if($is_elective[$key] == "No"){
                            $query = "DELETE FROM course_registration WHERE course_id = ".$value1['course_id'];
                            echo $query."\n";
                            $update = $this->db_query($query, false);
                            if($update > 0){
                                $update += 1;
                            }
                        }
                    }
                }
                if(!(in_array($value, $db_course_ids)) && $is_elective[$key] == "Yes"){
                    // $sql_course_reg = "SELECT course_reg_id FROM course_registration ORDER BY course_reg_id DESC LIMIT 1";
                    // $result = mysql_query($sql_course_reg);
                    // $course_reg_id = mysql_fetch_row($result);
                    // $course_reg_id = $course_reg_id[0];
                    // $course_reg_id = $course_reg_id + 1;
                    $query = "INSERT INTO course_registration(student_id, course_id, created, status_r, semester, elected, modified, level) VALUES ('".$student_id."', ".$value.", '".$date_created."', ".$status.", ".$semester.", 1, '".$date_created."', ".$level.")";
                    echo $query."\n";
                    $update = $this->db_query($query, false);
                    if($update > 0){
                        $update += $update;
                    }
                } 
            }
        }else{
            for($index = 0; $index < sizeof($compulsory_course_ids); $index++){
                // $sql_course_reg = "SELECT course_reg_id FROM course_registration ORDER BY course_reg_id DESC LIMIT 1";
                // $result = mysql_query($sql_course_reg);
                // $course_reg_id = mysql_fetch_row($result);
                // $course_reg_id = $course_reg_id[0];
                // $course_reg_id = $course_reg_id + 1;
                $query = "INSERT INTO course_registration(student_id, course_id, created, status_r, semester, elected, modified, level) VALUES ('".$student_id."', ".$compulsory_course_ids[$index].", '".$date_created."', ".$status.", ".$semester.", 0, '".$date_created."', ".$level.")";
                echo $query."\n";
                $check = $this->db_query($query, false);
                if($check > 0){
                    $check += $check;
                }
            }
            if($elective_course_ids != NULL){
                foreach($elective_course_ids as $key => $value){
                    if($is_elective[$key] == "Yes"){
                        // $sql_course_reg = "SELECT course_reg_id FROM course_registration ORDER BY course_reg_id DESC LIMIT 1";
                        // $result = mysql_query($sql_course_reg);
                        // $course_reg_id = mysql_fetch_row($result);
                        // $course_reg_id = $course_reg_id[0];
                        // $course_reg_id = $course_reg_id + 1;
                        $query = "INSERT INTO course_registration(student_id, course_id, created, status_r, semester, elected, modified, level) VALUES ('".$student_id."', ".$value.", '".$date_created."', ".$status.", ".$semester.", 1, '".$date_created."', ".$level.")";
                        echo $query."\n";
                        $check = $this->db_query($query, false);
                        if($check > 0){
                            $check += $check;
                        }
                    }
                }
            }
        }
        if($check > 0){
            return json_encode(array("response_code"=>0,"response_message"=>'Courses registered successfully.'));
        }else if($update > 0){
            return json_encode(array("response_code"=>0,"response_message"=>'Course registeration updated successfully.'));
        }else if($check == 0){
            return json_encode(array("response_code"=>0,"response_message"=>'No changes made. Courses registered successfully.'));
        }else{
            return json_encode(array("response_code"=>20,"response_message"=>'Failed to register. Already registered courses for this semester.'));
        }
    }

    public function getRegisteredCourses($data){
        $student_id = filter_var($data["student_id"], FILTER_SANITIZE_STRING);  
        $selected_year = filter_var($data["registered_level"], FILTER_SANITIZE_STRING);  
        $selected_semester = filter_var($data["registered_semester"], FILTER_SANITIZE_STRING);
        $department_id = filter_var($data["department_id"], FILTER_SANITIZE_STRING);
        $department_option_id = filter_var($data["department_option_id"], FILTER_SANITIZE_STRING);
        $query = "SELECT * FROM course_registration WHERE semester = ".$selected_semester." AND level = ".$selected_year." AND student_id = ".$student_id."";
        $resource = $this->db_query($query);
        $compulsory_courses = array();
        $elective_courses = array();
        foreach($resource as $key => $value){
            $query = "SELECT cst.course_id, course_title, course_code, course_unit, course_duration, elected FROM course_setup_tbl cst INNER JOIN course_registration cr 
                ON cst.course_id = cr.course_id WHERE cst.course_id = ".$value['course_id']." AND student_id = ".$student_id;
            $course_resource = $this->db_query($query);
            if($course_resource[0]['elected'] == '1'){
                array_push($elective_courses, $course_resource[0]);
            }else{
                array_push($compulsory_courses, $course_resource[0]);
            }
        }
        return json_encode(array("response_code"=>0,"response_message"=>'success', "compulsory_courses"=>$compulsory_courses, "elective_courses"=>$elective_courses));
    }
}
?>