<?php
Class Users extends dbobject{
    
    public function login($data)
	{
		$username = $data['username'];
		$password = $data['password'];
        $validate = $this->validate($data,array('username'=>'required|email','password'=>'required'));
        if($validate['error'])
        {
            return json_encode(array('response_code'=>13,'response_message'=>$validate['messages'][0]));
        }
		 $sql      = "SELECT username,firstname,lastname,sex,role_id,password,user_locked,pin_missed,day_1,day_2,day_3,day_4,day_5,day_6,day_7,passchg_logon,photo,town,kindred,village,state,lga FROM userdata WHERE username = '$username' LIMIT 1";
		$result   = $this->db_query($sql);
		$count    = count($result); 
		if($count > 0)
		{
            if($result[0]['pin_missed'] < 5)
            {
                $hash_password = $result[0]['password'];
                $is_locked          = $result[0]['user_locked'];
                 $verify_pass       = password_verify($password,$hash_password);
//
//                $desencrypt = new DESEncryption();
//                $key = $username;
//                $cipher_password = $desencrypt->des($key, $password, 1, 0, null,null);
//                $str_cipher_password = $desencrypt->stringToHex ($cipher_password);
                if($verify_pass)
                {
                    if($is_locked != 1)
                    {
                        $work_day = $this->workingDays($result[0]);
                        if($work_day['code'] != "44")
                        {
                            $_SESSION['username_sess']   = $result[0]['username'];
                            $_SESSION['firstname_sess']  = $result[0]['firstname'];
                            $_SESSION['lastname_sess']   = $result[0]['lastname'];
                            $_SESSION['sex_sess']        = $result[0]['sex'];
                            $_SESSION['role_id_sess']    = $result[0]['role_id'];
                            $_SESSION['photo_file_sess']  = $result[0]['photo'];
                            $_SESSION['photo_path_sess']  = "img/profile_photo/".$result[0]['photo'];
                            $_SESSION['role_id_name']    = $this->getitemlabel('role','role_id',$result[0]['role_id'],'role_name');
                            
                            $this->setAppSession(array('town'=>$result[0]['town'],'kindred'=>$result[0]['kindred'],'village'=>$result[0]['village'],'state'=>$result[0]['state'],'lga'=>$result[0]['lga']));
                            //update pin missed and last_login
                            $this->resetpinmissed($username);
                            return json_encode(array("response_code"=>0,"response_message"=>"Login Successful"));
                        }
                        else
                        {
                            return json_encode(array("response_code"=>61,"response_message"=>$work_day['mssg']));
                        }
                    }
                    else
                    {
                        //inform the user that the account has been locked, and to contact admin, user has to provide useful info b4 he is unlocked
                        return json_encode(array("response_code"=>60,"response_message"=>"Your account has been locked, kindly contact the administrator."));
                    }
                }
                else	
                {
                    $this->updatepinmissed($username);
                    
                    $remaining = (($result[0]['pin_missed']+1) <= 5)?(5-($result[0]['pin_missed']+1)):0;
                    return json_encode(array("response_code"=>90,"response_message"=>"Invalid username or password, ".$remaining." attempt remaining"));
                }
            }
            elseif($result[0]['pin_missed'] == 5)
            {
                $this->updateuserlock($username,'1');
                return json_encode(array("response_code"=>64,"response_message"=>"Your account has been locked, kindly contact the administrator."));
            }
            else
            {
                 return json_encode(array("response_code"=>62,"response_message"=>"Your account has been locked, kindly contact the administrator."));
            }
		}
        else
		{
			return json_encode(array("response_code"=>20,"response_message"=>"Invalid username or password"));
		}
    }
    public function userlist($data)
    {
		$table_name    = "userdata";
		$primary_key   = "username";
		$columner = array(
			array( 'db' => 'username', 'dt' => 0 ),
			array( 'db' => 'username', 'dt' => 1 ),
			array( 'db' => 'firstname',  'dt' => 2 ),
			array( 'db' => 'lastname',   'dt' => 3 ),
			array( 'db' => 'mobile_phone',   'dt' => 4 ),
			array( 'db' => 'role_id',   'dt' => 5, 'formatter'=>function($d,$row){
                return  $this->getitemlabel('role','role_id',$d,'role_name');
            }  ),
			array( 'db' => 'email',   'dt' => 6 ),
			array( 'db' => 'town',   'dt' => 7,'formatter'=>function($d,$row){
                return $this->getitemlabel('towns','id',$d,'town_name');
            } ),
            array( 'db' => 'state',   'dt' => 8,'formatter'=>function($d,$row){
                return $this->getitemlabel('lga','stateid',$d,'State');
            } ),
            array( 'db' => 'lga',   'dt' => 9,'formatter'=>function($d,$row){
                return $this->getitemlabel('lga','Lgaid',$d,'Lga');
            } ),
			array( 'db' => 'pin_missed',   'dt' => 10 ),
			array( 'db' => 'user_locked',   'dt' => 11, 'formatter'=>function($d,$row){
                return  ($d==1)?'Locked':'Not Locked';
            } ),
            array( 'db' => 'username',   'dt' => 12, 'formatter'=>function($d,$row){
                $locking = ($row['user_locked']==1)?"Unlock User":"Lock User";
                $locking_class = ($row['user_locked']==1)?"btn-success":"btn-danger";
                if($_SESSION['role_id_sess'] == 001)
                {
                    return  "<button onclick=\"trigUser('".$d."','".$row['user_locked']."')\" class='btn btn-sm ".$locking_class."'>".$locking."</button>&nbsp;|&nbsp;<a class='btn btn-sm btn-warning'   onclick=\"getModal('setup/pastor.php?op=edit&username=".$d."','modal_div')\"  href=\"javascript:void(0)\" data-toggle=\"modal\" data-target=\"#defaultModalPrimary\" >EDIT THIS USER</a>";
                }
                else if($_SESSION['role_id_sess'] == 003)
                {
                    return  "<button onclick=\"trigUser('".$d."','".$row['user_locked']."')\" class='btn btn-sm ".$locking_class."'>".$locking."</button>&nbsp;|&nbsp;<a class='btn btn-sm btn-warning'   onclick=\"getModal('setup/user_edit.php?op=edit&username=".$d."','modal_div')\"  href=\"javascript:void(0)\" data-toggle=\"modal\" data-target=\"#defaultModalPrimary\" >EDIT THIS USER</a>";
                }
                
            } ),
			array( 'db' => 'created',   'dt' => 13 )
			);
        
        $filter = "";
        $datatableEngine = new engine();
	
		echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key);

    }
    public function register($data)
	{
		// check if record does not exists before then insert
        $data['day_1'] = (isset($data['day_1']))?1:0;
        $data['day_2'] = (isset($data['day_2']))?1:0;
        $data['day_3'] = (isset($data['day_3']))?1:0;
        $data['day_4'] = (isset($data['day_4']))?1:0;
        $data['day_5'] = (isset($data['day_5']))?1:0;
        $data['day_6'] = (isset($data['day_6']))?1:0;
        $data['day_7'] = (isset($data['day_7']))?1:0;
        $data['passchg_logon'] = (isset($data['passchg_logon']))?1:0;
        $data['user_disabled'] = (isset($data['user_disabled']))?1:0;
        $data['user_locked']   = (isset($data['user_locked']))?1:0;
        $data['posted_user']     = $_SESSION['username_sess'];
        
            if($data['operation'] != 'edit')
            {
                $validation = $this->validate($data,
                        array(
                            'firstname'=>'required|min:2',
                            'lastname'=>'required',
                            'mobile_phone'=>'required|int',
                            'sex'=>'required',
                            'role_id'=>'required',
                            'username'=>'required|email|unique:userdata.username',
                            'password'=>'required|min:6'
                        ),
                        array('firstname'=>'First Name','lastname'=>'Last name','role_id'=>'Role ID','mobile_phone'=>'Phone Number','sex'=>'Gender')
                       );
                if(!$validation['error'])
                {
                    $data['email']       = $data['username'];
                    $data['created']     = date('Y-m-d h:i:s');
                    
//                    $desencrypt          = new DESEncryption();
//                    $key                 = $data['username'];
//                    $cipher_password     = $desencrypt->des($key, $data['password'], 1, 0, null,null);
//                    $str_cipher_password = $desencrypt->stringToHex ($cipher_password);
                    $hash_password  = $this->passwordHash($data['password']);
                    $data['password']    = $hash_password;

                    
                    $count = $this->doInsert('userdata',$data,array('op','confirm_password','operation'));
                    if($count == 1)
                    {
//                        rename('user_passport/'.$temp_pass,'user_passport/'.$data['email'].".".end($array));
                        return json_encode(array("response_code"=>0,"response_message"=>'Record saved successfully'));
                    }
                    else
                    {
                        return json_encode(array("response_code"=>78,"response_message"=>'Failed to save record'));
                    }
                }else
                {
                    return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
                }
            }
            else
            {
//                EDIT EXISTING USER 
                $data['modified_date'] = date('Y-m-d h:i:s');
                $validation = $this->validate($data,
                        array(
                            'firstname'=>'required|min:2',
                            'lastname'=>'required',
                            'mobile_phone'=>'required|int',
                            'sex'=>'required',
                            'role_id'=>'required',
                            'username'=>'required|email',
                        ),
                        array('firstname'=>'First Name','lastname'=>'Last name','role_id'=>'Role ID','mobile_phone'=>'Phone Number','sex'=>'Gender')
                       );
                if(!$validation['error'])
                {
                    $count = $this->doUpdate('userdata',$data,array('op','operation','password'),array('username'=>$data['username']));
                    if($count == 1)
                    {
    //                    rename('user_passport/'.$temp_pass,'user_passport/'.$data['email'].".".end($array));
                        return json_encode(array("response_code"=>0,"response_message"=>'Record saved successfully'));
                    } 
                    else
                    {
                        return json_encode(array("response_code"=>78,"response_message"=>'Failed to save record'));
                    }
                }
                else
                {
                    return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
                }
            }
        
	}
    public function userEdit($data)
    {
        $data['day_1'] = (isset($data['day_1']))?1:0;
        $data['day_2'] = (isset($data['day_2']))?1:0;
        $data['day_3'] = (isset($data['day_3']))?1:0;
        $data['day_4'] = (isset($data['day_4']))?1:0;
        $data['day_5'] = (isset($data['day_5']))?1:0;
        $data['day_6'] = (isset($data['day_6']))?1:0;
        $data['day_7'] = (isset($data['day_7']))?1:0;
        $data['passchg_logon'] = (isset($data['passchg_logon']))?1:0;
        $data['user_disabled'] = (isset($data['user_disabled']))?1:0;
        $data['user_locked']   = (isset($data['user_locked']))?1:0;
        $data['posted_user']     = $_SESSION['username_sess'];
        $cnt = $this->doUpdate('userdata',$data,array('op','operation'),array('username'=>$data['username']));
        if($cnt == 1)
        {
             return json_encode(array("response_code"=>0,"response_message"=>'Record saved successfully'));
        }else
        {
             return json_encode(array("response_code"=>78,"response_message"=>'Failed to save record'));
        }
    }
    public function profileEdit($data)
    {
        $validate = $this->validate($data,array('username'=>'required|email','firstname'=>'required','lastname'=>'required','mobile_phone'=>'required','sex'=>'required'),array('mobile_phone'=>'Phone Number','firstname'=>'First Name','lastname'=>'Last Name','sex'=>'Gender'));
        if(!$validate['error'])
        {
            $cnt = $this->doUpdate('userdata',$data,array('op','operation'),array('username'=>$data['username']));
            if($cnt == 1)
            {
                 return json_encode(array("response_code"=>0,"response_message"=>'Record saved successfully'));
            }
            else
            {
                 return json_encode(array("response_code"=>78,"response_message"=>'No update was made'));
            }
        }
        else
        {
            return json_encode(array('response_code'=>13,'response_message'=>$validate['messages'][0]));
        }
        
    }
    public function saveUser($data)
    {
       if($data['role_id'] == 2) // town administrator
       {
           $validation = $this->validate($data,array('state'=>'required|exist:lga.stateid','lga'=>'required','town'=>'required|exist:towns.id'),array('state'=>'State'));
            if(!$validation['error'])
            {
                return $this->register($data);
            }else
            {
                return json_encode(array("response_code"=>271,"response_message"=>$validation['messages'][0]));
            }
       }
        if($data['role_id'] == "3") // village administrator
        {
           $data['state']  = $_SESSION['state_id_sess'];
           $data['lga']    = $_SESSION['lga_id_sess'];
           $data['town']   = $_SESSION['town_id_sess'];
            $validation = $this->validate($data,array('village'=>'required|exist:village.village_id','state'=>'required|exist:lga.stateid','lga'=>'required','town'=>'required|exist:towns.id'),array('state'=>'State'));
            if(!$validation['error'])
            {
                return $this->register($data);
            }else
            {
                return json_encode(array("response_code"=>290,"response_message"=>$validation['messages'][0]));
            }
        }
        if($data['role_id'] == 4) // kindred administrator
        {
           $data['state'] = $_SESSION['state_id_sess'];
           $data['lga']    = $_SESSION['lga_id_sess'];
           $data['town']   = $_SESSION['town_id_sess'];
           $data['village']   = $_SESSION['village_id_sess'];
            
            $validation = $this->validate($data,array('kindred'=>'required|exist:kindred.id','village'=>'required|exist:village.village_id','state'=>'required|exist:lga.stateid','lga'=>'required','town'=>'required|exist:towns.id'),array('state'=>'State'));
            if(!$validation['error'])
            {
                return $this->register($data);
            }else
            {
                return json_encode(array("response_code"=>2710,"response_message"=>$validation['messages'][0]));
            }
        }
        if($data['role_id'] == 5) // kindred secretary
        {
           $data['state'] = $_SESSION['state_id_sess'];
           $data['lga']    = $_SESSION['lga_id_sess'];
           $data['town']   = $_SESSION['town_id_sess'];
           $data['village']   = $_SESSION['village_id_sess'];
           $data['kindred']   = $_SESSION['kindred_id_sess'];
            
            $validation = $this->validate($data,array('kindred'=>'required|exist:kindred.id','village'=>'required|exist:village.village_id','state'=>'required|exist:lga.stateid','lga'=>'required','town'=>'required|exist:towns.id'),array('state'=>'State'));
            if(!$validation['error'])
            {
                return $this->register($data);
            }else
            {
                return json_encode(array("response_code"=>2370,"response_message"=>$validation['messages'][0]));
            }
        }
        
        
    }
    public function workingDays($dbrow)
    {
        $days_of_week = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
        $db_day       = array('day_1','day_2','day_3','day_4','day_5','day_6','day_7');
        $ddate        = date('w');
        $mssg         = array('code'=>0,'mssg'=>'');
        foreach($days_of_week as $k => $v)
        {
            if($dbrow[$db_day[$k]] == 0 && $ddate == $k)
            {
                $mssg = array( "mssg"=>"You are not allowed to login on $days_of_week[$k]","code"=>"44");
               
            }
        }
        if($dbrow['passchg_logon'] == '1')
        {
            $mssg = array( "mssg"=>"You are required to change your password, follow this link to  <a href='change_psw_logon.php?username={$dbrow[username]}'> change password </a>","code"=>"44");
        }
        return $mssg;
    }
    public function emailPasswordReset($data)
    {
         $email = $data['email'];
        
        $pass_dateexpire = @date("Y-m-d H:i:s",strtotime($today."+ 24 hours"));
		$upd = $this->db_query("UPDATE userdata SET pwd_expiry='".$pass_dateexpire."' WHERE username = '$email'");
        
       
        $recordBiodata = $this->getItemLabelArr('userdata',array('email'),array($email),'*');

        $fname = $recordBiodata['first_name'];
        $lname = $recordBiodata['last_name'];

        
        return json_encode(array("response_code"=>0,"response_message"=>'Check your mail'));
    }
    
    public function changeUserStatus($data)
    {
        $username = $data['username'];
        $status = ($data['current_status'] == 1)?0:1;
        $sql = "UPDATE userdata SET user_locked = '$status' WHERE username = '$username'";
        $this->db_query($sql);
        return json_encode(array("response_code"=>0,"response_message"=>"updated successfully"));
    }
    
    public function doPasswordChange($data)
    {
            $validation = $this->validate($data,
                        array(
                            'username'=>'required',
                            'current_password'=>'required',
                            'password'=>'required|min:6',
                            'confirm_password'=>'required|matches:password'
                        ),
                        array('confirm_password'=>'Confirm password','current_password'=>'Current Password')
                       );
           
            if(!$validation['error'])
            {
                $username      = $data['username'];
                $user_password = $data['password'];
                $user_curr_password = $data['current_password'];
                
//                $desencrypt = new DESEncryption();
//                $key = $username;
//                $cipher_password = $desencrypt->des($key, $user_curr_password, 1, 0, null,null);
//                $str_cipher_password = $desencrypt->stringToHex ($cipher_password);
//                $str_cipher_password = $this->EncryptData($username,$user_password);
                
                  $sql = "SELECT username,password FROM userdata WHERE username = '$username' LIMIT 1 ";
                $rr  = $this->db_query($sql);
                $verify_pass       = password_verify($user_curr_password,$rr[0][password]);
                
                if($verify_pass)
                {
                    $new_hash_password = $this->passwordHash($data['password']);
                    $query_data = "UPDATE userdata set password='$new_hash_password', passchg_logon = '0' where username= '$username'";
//                    echo $query_data;
                    $result_data = $this->db_query($query_data,false);
                    if($result_data > 0)
                    {
                        if($data['page'] == 'first_login')
                        {
                            return json_encode(array('response_code'=>0,'response_message'=>'Your password was changed successfully... <a href="index.html">Proceed to login</a>'));
                        }
                        else
                        {
                            return json_encode(array('response_code'=>0,'response_message'=>'Your password was changed successfully... logging you out'));
                        }
                        
                    }
                    else
                    {
                        return json_encode(array('response_code'=>45,'response_message'=>'Your password could not be changed'));
                    }
                }else
                {
                    return json_encode(array('response_code'=>455,'response_message'=>'current password is invalid'));
                }

                
            }
        else
        {
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
        }
	}
    public function passwordHash($secret)
	{
		$hashvalue = password_hash($secret,PASSWORD_DEFAULT);
		return $hashvalue;
//		echo "<br/>".password_verify($secret,'$2y$10$s4N.5vNNy5iniEQ2Pycn.uE.OJJ69p.1eT9W6JOce7j9TAgzjrxJS');
//		var_dump( password_get_info('$2y$10$s4N.5vNNy5iniEQ2Pycn.uE.OJJ69p.1eT9W6JOce7j9TAgzjrxJS') );
	}
    protected function setAppSession(array $data)
    {
        $_SESSION['state_id_sess']   = $data['state'];
        $_SESSION['lga_id_sess']     = $data['lga'];
        $_SESSION['town_id_sess']    = $data['town'];
        $_SESSION['kindred_id_sess'] = $data['kindred'];
        $_SESSION['village_id_sess'] = $data['village'];
    }
	

}