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
        // var_dump($data);
        if ($data["operation"] != 'edit'){
            $menu_id      = $this->paddZeros($this->getnextid("menu"), 2);//$data['menu_id'];
            $menu_name    = $data['menu_name'];
            $menu_url     = $data['menu_url'];
            $parent_menu  = $data['parent_id'];
            $parent_menu2 = $data['parent_menu2'];
            $menu_level   = $data['menu_level'];

            $sql = "insert into menu (menu_id,menu_name,menu_url,parent_id,parent_id2,menu_level,created) values( '$menu_id','$menu_name','$menu_url','$parent_menu','$parent_menu2','$menu_level',now())";
            // echo $sql;
            $this->db_query($sql);
            return json_encode(array('response_code'=>0,'response_message'=>'Menu Created Successfully'));
        }else{
            $menu_id      = $data['id'];
            $menu_name    = $data['menu_name'];
            $menu_url     = $data['menu_url'];
            $parent_menu  = $data['parent_id'];
            $parent_menu2 = $data['parent_menu2'];
            $menu_level   = $data['menu_level'];

            $sql = "update menu  set menu_name = '$menu_name', menu_url = '$menu_url', parent_id = '$parent_menu' where menu_id = '$menu_id'";
            // echo $sql;
            $this->db_query($sql);
            return json_encode(array('response_code'=>0,'response_message'=>'Menu Updated Successfully'));
        }
        
    }
    
    public function menuList($data)
    {
        $table_name    = "menu";
		$primary_key   = "menu_id";
		$columner = array(
			array( 'db' => 'menu_id', 'dt' => 0 ),
//			array( 'db' => 'menu_id', 'dt' => 1 ),
			array( 'db' => 'menu_name',  'dt' => 1 ),
			array( 'db' => 'menu_url',  'dt' => 2 ),
			array( 'db' => 'parent_id',  'dt' => 3,'formatter' => function( $d,$row ) 
                {
                    return ($d == "#")?"This is a Parent Menu":$this->getitemlabel('menu','menu_id',$d,'menu_name');
                } ),
//			array( 'db' => 'menu_level',  'dt' => 5 ),
//			array( 'db' => 'menu_order',  'dt' => 6 ),
			array( 'db' => 'icon',  'dt' => 4 ),
			array( 'db' => 'menu_id',  'dt' => 5,'formatter' => function( $d,$row ) {
                
						return '<a class="btn btn-warning" onclick="getModal(\'setup/menu_setup.php?op=edit&menu_id='.$d.'\',\'modal_div\')"  href="javascript:void(0)" data-toggle="modal" data-target="#defaultModalPrimary">Edit Menu</a> | <a class="btn btn-danger" onclick="deleteMenu(\''.$d.'\')"  href="javascript:void(0)" >Delete Menu</a>';
					} ),
			array( 'db' => 'created', 'dt' => 6, 'formatter' => function( $d,$row ) {
						return $d;
					}
				)
			);
		$filter = "";
//		$filter = " AND role_id='001'";
		$datatableEngine = new engine();
	
		echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key);
    }
    
    public function loadmenus($data)
    {
        $role_id = $data['role_id'];
        $visible = $this->visibleMenus($role_id);
        $invisible = $this->inVisibleMenus($role_id);
        return json_encode(array('response_code'=>0,'response_message'=>'Menu Created Successfully','data'=>array('visible'=>$visible,'invisible'=>$invisible)));
    }
    
    private function visibleMenus($role_id)
    {
        $sql     = "SELECT menu_id,menu_name FROM menu WHERE menu_id IN (SELECT menu_id FROM menugroup WHERE role_id = '$role_id') order by menu_name";
        $result  = $this->db_query($sql);
        $visible = '';
        foreach($result as $row)
        {
            $visible = $visible.'<div class="form-group" draggable="true" ondragstart="drag(event)" id="tt'.$row[menu_id].'">
                          <div>'.$row[menu_name].'</div>
                          <input type="hidden" name="menus[]" value="'.$row[menu_id].'" class="form-group" />
                      </div>';
        }
        return $visible;
    }

    public function loadParentMenu($data)
    {
        $sql    = "SELECT * FROM menu WHERE parent_id = '#'";
        $result = $this->db_query($sql);
        if(count($result) > 0)
        {
            $r = array();
            foreach($result as $row)
            {
                $r[] = array($row['menu_id'],$row['menu_name']);
            }
            return json_encode(array("response_code"=>0,"response_message"=>"Parent menu found", "data"=>$r));
        }
        else
        {
            return json_encode(array("response_code"=>44,"response_message"=>"No parent menu found"));
        }
        
    }
    
    private function inVisibleMenus($role_id)
    {
        $sql     = "SELECT menu_id,menu_name FROM menu WHERE menu_id NOT IN (SELECT menu_id FROM menugroup WHERE role_id = '$role_id') order by menu_name";
        $result  = $this->db_query($sql);
        $invisible = '';
        foreach($result as $row)
        {
            $invisible = $invisible.'<div class="form-group" draggable="true" ondragstart="drag(event)" id="tt'.$row[menu_id].'">
                          <div>'.$row[menu_name].'</div>
                          <input type="hidden" name="menus[]" value="'.$row[menu_id].'" class="form-group" />
                      </div>';
        }
        return $invisible;
    }
    public function saveMenuGroup($data)
    {
        $role_id = $data['role_id'];
        $sql = "DELETE FROM menugroup WHERE role_id = '$role_id'";
        $this->db_query($sql);
        foreach($data['menus'] as $value)
        {
            $sql = "INSERT INTO menugroup (role_id,menu_id) VALUES('$role_id','$value')";
            $this->db_query($sql);
        }
        return json_encode(array('response_code'=>0,'response_message'=>'Menu Role saved Successfully')); 
    }
}