<?php
Class School extends dbobject{
    
    
    public function school_list($data)
    {
		$table_name    = "school_information";
		$primary_key   = "school_id";
		$columner = array(
			array( 'db' => 'school_id', 'dt' => 0 ),
			array( 'db' => 'school_id', 'dt' => 1 ),
			array( 'db' => 'school_name', 'dt' => 2 ),
			array( 'db' => 'school_address',  'dt' => 3 ),
			array( 'db' => 'school_type', 'dt' => 4 ),
			array( 'db' => 'year_of_establishment', 'dt' => 5),
			array( 'db' => 'founder',  'dt' => 6),
			array( 'db' => 'vision_mission', 'dt' => 7 ),
			array( 'db' => 'created', 'dt' => 8 ),
			array( 'db' => 'posted_by', 'dt' => 9 ),
			array( 'db' => 'school_id', 'dt' => 10 ),
			);
		// $filter              = " AND role_id <> '001' AND role_id <> '$_SESSION[role_id_sess]'";
        // $filter = $filter;
        $datatableEngine = new engine();
	
		echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key);

    }
    
	

}