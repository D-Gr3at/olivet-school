<?php

class Menu extends dbobject
{
    public function generateMenu($role_id)
    {
        $output = array();
        $sub_menu = array();
       
        $sql = "select * from menu where menu_level='0' and menu_id in (select menu_id from menugroup where role_id ='$role_id') order by menu_id asc";
        $result = $this->db_query($sql);
        if(count($result) > 0)
        {
            foreach($result as $row)
            {
                $menu_id    = $row["menu_id"];
                $parent_id  = $row["parent_id"];
                $menu_level = $row["menu_level"];
                $icon       = $row['icon'];
                $url        = $row["menu_url"];
                $menu_name  = $row["menu_name"];
                
                $sql_2 = "select * from menu where parent_id = '$menu_id' and menu_id in (select menu_id from menugroup where role_id ='$role_id') order by menu_order";
                $result2 = $this->db_query($sql_2);
                $has_sub_menu = ($result2>0)?true:false;
                if($result2 > 0)
                {
                    foreach($result2 as $row_1)
                    {
                        if($row_1["menu_id"] == "027")
                        {
                            if($_SESSION['church_type_id_sess'] == "1")
                            {
                                $menu_id_1        = $row_1["menu_id"];
                                $menu_url_1       = $row_1["menu_url"];
                                $name             = $row_1["menu_name"];
                                $sub_menu[]       = array(
                                    'menu_id'     => $menu_id_1,
                                    'menu_url'    => $menu_url_1,
                                    'name'        => $name
                                );
                            }
                            
                        }else
                        {
                            
                             $menu_id_1       = $row_1["menu_id"];
                            $menu_url_1       = $row_1["menu_url"];
                            $name             = $row_1["menu_name"];
                            $sub_menu[]       = array(
                                'menu_id'     => $menu_id_1,
                                'menu_url'    => $menu_url_1,
                                'name'        => $name
                            );
                        }
                           
                        
                        
                    }
                }
                $output[] = array(
                                'menu_id'      => $menu_id,
                                'menu_name'    => $menu_name,
                                'parent_id'    => $parent_id,
                                'menu_level'   => $menu_level,
                                'icon'         => $icon,
                                'has_sub_menu' => $has_sub_menu,
                                'sub_menu'     => $sub_menu
                            );
                $sub_menu = array();
            }
        }
        return array('response_code'=>0,'data'=>$output);
    }
    public function saveMenu($data)
    {
        $menu_id      = $data['menu_id'];
        $menu_name    = $data['menu_name'];
        $menu_url     = $data['menu_url'];
        $parent_menu  = $data['parent_menu'];
        $parent_menu2 = $data['parent_menu2'];
        $menu_level   = $data['menu_level'];

        $sql = "insert into menu (menu_id,menu_name,menu_url,parent_id,parent_id2,menu_level,created) values( '$menu_id','$menu_name','$menu_url','$parent_menu','$parent_menu2','$menu_level',now())";
        $this->db_query($sql);
        return array('response_code'=>0,'response_message'=>'Menu Created Successfully');
    }
    
    public function menuList($data)
    {
        $table_name    = "menu";
		$primary_key   = "menu_id";
		$columner = array(
			array( 'db' => 'menu_id', 'dt' => 0 ),
			array( 'db' => 'menu_id', 'dt' => 1 ),
			array( 'db' => 'menu_url',  'dt' => 2 ),
			array( 'db' => 'menu_name',  'dt' => 3 ),
			array( 'db' => 'parent_id',  'dt' => 4,'formatter' => function( $d,$row ) 
                {
                    return ($d == "#")?"This is a Parent Menu":$d;
                } ),
			array( 'db' => 'menu_level',  'dt' => 5 ),
			array( 'db' => 'menu_order',  'dt' => 6 ),
			array( 'db' => 'icon',  'dt' => 7 ),
			array( 'db' => 'menu_id',  'dt' => 8 ),
			array( 'db' => 'created', 'dt' => 9, 'formatter' => function( $d,$row ) {
						return $d;
					}
				)
			);
		$filter = "";
//		$filter = " AND role_id='001'";
		$datatableEngine = new engine();
	
		echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key);
    }
}