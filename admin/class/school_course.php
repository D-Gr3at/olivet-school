<?php
Class SchoolCourse extends dbobject{
    
    
    public function school_course_list($data)
    {
		$table_name    = "course_setup";
		$primary_key   = "course_id";
		$columner = array(
			array( 'db' => 'course_id', 'dt' => 0 ),
			array( 'db' => 'course_id', 'dt' => 1 ),
			array( 'db' => 'department_id', 'dt' => 2 ),
			array( 'db' => 'course_name',  'dt' => 3 ),
			array( 'db' => 'course_lecturer', 'dt' => 4 ),
			array( 'db' => 'course_unit', 'dt' => 5),
			array( 'db' => 'created', 'dt' => 6 ),
			array( 'db' => 'posted_by', 'dt' => 7 ),
			);
		// $filter              = " AND role_id <> '001' AND role_id <> '$_SESSION[role_id_sess]'";
        // $filter = $filter;
        $datatableEngine = new engine();
	
		echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key);

    }
    
	

}