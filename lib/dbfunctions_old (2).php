<?php
header("strict-transport-security: max-age=600");
header('X-Frame-Options: SAMEORIGIN');
header("Pragma: no-cache");
//include_once('db_session.php');
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
@session_start();
//require '../../vendor/autoload.php';
//use Mailgun\Mailgun;

///////////////////
//error_reporting(E_ALL);
require_once("dbcnx.inc.php");
require_once('desencrypt.php');
// require_once('../v8/stunts.php');
//////////////////////
class dbobject
{
	private $error      = false;
	private $messageBag = array();
	private $debug      = false;
	function begin()
	{
		$this->db_query("BEGIN", false);
	}
	function commit()
	{
		@$this->db_query("COMMIT", false);
	}
	function rollback()
	{
		@$this->db_query("ROLLBACK", false);
	}
	public function arrayImplode($data = array(), $separated)
	{
		$fields = array_keys($data);
		$values = array_values(array_map('mysql_escape_string', $data));
		$i = 0;
		while ($fields[$i]) {
			if ($i > 0) $string .= $separated;
			$string .= sprintf("%s = '%s'", $fields[$i], $values[$i]);
			$i++;
		}
		return $string;
	}

	function DecryptDataa($key, $password)
	{
		$desencrypt = new DESEncryption();
		$mmm = $desencrypt->hexToString($password);
		return strip_tags($desencrypt->des($key, $mmm, 0, 0, null, null));
	}

	function EncryptDataa($username, $userpassword)
	{
		$desencrypt = new DESEncryption();
		$key = $username;
		$cipher_password = $desencrypt->des($key, $userpassword, 1, 0, null, null);
		$str_cipher_password = $desencrypt->stringToHex($cipher_password);
		return $str_cipher_password;
	}

	public function DecryptData($key, $password)
	{
		$desencrypt = new DESEncryption();
		$mmm = $desencrypt->hexToString($password);
		return strip_tags($desencrypt->des($key, $mmm, 0, 0, null, null));
	}
	function EncryptData($username, $userpassword)
	{
		$desencrypt = new DESEncryption();
		$key = $username;
		$cipher_password = $desencrypt->des($key, $userpassword, 1, 0, null, null);
		$str_cipher_password = $desencrypt->stringToHex($cipher_password);
		return $str_cipher_password;
	}
	public function insertMysql($table, $data = array())
	{
		$fields = implode(', ', array_keys($data));
		$values = implode('", "', array_map('mysql_escape_string', $data));
		$query = sprintf('INSERT INTO %s (%s) VALUES ("%s")', $table, $fields, $values);
		return $this->queryMysql($query);
	}
	public function queryMysql($sql)
	{
		if ($this->debug === false) {
			try {
				$result = $this->db_query($sql);
				if ($result === false) {
					throw new Exception('MySQL Query Error: ' . mysql_error());
					//$result = '-1';
				}
				return $result;
			} catch (Exception $e) {
				return $e->getMessage();
				//exit();
			}
		} else {
			printf('<textarea>%s</textarea>', $sql);
		}
	}

	//////////////////////ugo resend mail///
	function resolve_mail($address)
	{
		$sql2 = "SELECT email_verified FROM userdata_applicants WHERE email = '$address' LIMIT 1";
		$result = $this->db_query($sql2);
		//	while($row = mysql_fetch_array($result))
		foreach ($result as $row) {
			$status = $row['email_verified'];
		}

		if ($status == 1) {
			return "1";
		} else {
			$sql = "UPDATE userdata_applicants SET created = NOW() WHERE email = '$address'";
			$this->db_query($sql, false);
			$key = "accessis4life";
			$magic_key = $this->EncryptData($address, $key);


			$fname = $this->getItemLabel('bio_data', 'email', '$address', 'first_name');
			$lname = $this->getItemLabel('bio_data', 'email', '$address', 'surname');

			$sender = "Nigeria Customs Service ";
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: Nigeria Customs Service Recruitment <noreply@nnpcrecruitment.org.ng> ' . "\r\n";
			$message = "<div><div>Dear " . strtoupper($fname) . " " . strtoupper($lname) . "</div><br><br>
	 	<div>Please confirm your e-mail address in order to complete your application.</div><br>
	 	<div>To confirm your e-mail address, click on the following link : https://careers.nnpcgroup.com/email_verification.php?ga=" . $magic_key . " to verify your mail and complete registration.<br> If you are not automatically redirected, please copy and paste the link on your browser's address bar to continue.</div><br><br>
	 	<div> Thanks</div>";

			mail($address, "Nigeria Customs Service E-RECRUITMENT: EMAIL VERIFICATION", $message, $headers, $sender);
			return "0";
		}
	}
	////////end ugo//////////////////

	//////////////////////////////////Generic Script///////////////////////////////////////////////////
	function SaveTransEdit($tbl, $inpFds, $inpFdsVals, $operation)
	{
		$whrcond = 0;
		$resp = 0;
		if ($operation == 'new') {
			$query = "insert into " . $tbl . " set ";
			$where = "";
			for ($i = 0; $i < count($inpFds); $i++) {
				$field = explode("-", $inpFds[$i]);
				if ($field[1] == 'fd') {
					$query .= $field[0] . "='" . $inpFdsVals[$i] . "', ";
					//$affected .= $field[0].", ";
					//$updatedVals .= $inpFdsVals[$i]."/";
				} elseif ($field[1] == 'whr' && $whrcond == 0) {
					$where .= ", " . $field[0] . "='" . $inpFdsVals[$i] . "'";
					$whrcond += 1;
					//$trail_appl = $inpFdsVals[$i];
				} elseif ($field[1] == 'whr' && $whrcond >= 1) {
					$where .= ", " . $field[0] . "='" . $inpFdsVals[$i] . "'";
					$whrcond += 1;
				}
			}
			$query = rtrim($query, ", ");
			$query_data = $query . $where;
			$query_data .= ';'; //use to disply sql insert
			$daty = @date('Y-m-d H:i:s');
			$officer = $_SESSION['username_sess'];
			$ip = $_SERVER['REMOTE_ADDR'];
			//$query2 = "insert into audit_trail  values('','$tbl','$trail_appl','$afftd','$intVals','$updatedVals','Edit','$daty','$officer','$ip')";
			if (($this->db_query($query_data, false) > 0) or die(mysql_error())) {
				$resp += 1;
				//if(mysql_query($query2))	$resp += 1;
				//else	$resp = -2;

			} else	$resp = -1;
			//if(!mysql_error())*/
			return $resp;
		} elseif ($operation == 'edit') {
			$query = "update " . $tbl . " set ";
			$where = "";
			for ($i = 0; $i < count($inpFds); $i++) {
				$field = explode("-", $inpFds[$i]);
				if ($field[1] == 'fd') {
					$query .= $field[0] . "='" . $inpFdsVals[$i] . "', ";
					//$affected .= $field[0].", ";
					//$updatedVals .= $inpFdsVals[$i]."/";
				} elseif ($field[1] == 'whr' && $whrcond == 0) {
					$where .= " where " . $field[0] . "='" . $inpFdsVals[$i] . "'";
					$whrcond += 1;
					//$trail_appl = $inpFdsVals[$i];
				} elseif ($field[1] == 'whr' && $whrcond >= 1) {
					$where .= " and " . $field[0] . "='" . $inpFdsVals[$i] . "'";
					$whrcond += 1;
				}
			}
			$query = rtrim($query, ", ");
			$query_data = $query . $where;
			$query_data .= ';';
			//$affected = rtrim($affected,", ");
			//$query1 = "select ".$affected." from ".$tbl.$where.';';
			//echo $query_data;
			//$result1 = mysql_query($query1);
			/*while($row=mysql_fetch_array($result1))
		{
			$fdd = explode(", ",$affected);
			for($t=0;$t<count($fdd);$t++)
			{
				$afftd .= $fdd[$t].'/';
				$intVals .= $row[$fdd[$t]].'/';
			}
		}
*/
			$daty = @date('Y-m-d H:i:s');
			$officer = $_SESSION['username_sess'];
			$ip = $_SERVER['REMOTE_ADDR'];
			//$query2 = "insert into audit_trail  values('','$tbl','$trail_appl','$afftd','$intVals','$updatedVals','Edit','$daty','$officer','$ip')";
			if ($this->db_query($query_data, false) > 0) //or die(mysql_error()))
			{
				$resp += 1;
				//if(mysql_query($query2))	$resp += 1;
				//else	$resp = -2;

			} else	$resp = -2;
			//if(!mysql_error())*/
			return $resp;
		} else {
			echo 'something went wrong';
			exit();
		}
	}
	///////////////////////////////////////////////////////
	function exister($table, $field1, $field2, $value1, $value2)
	{
		// counter function=>to return numbers of rows fetched or found
		//		function counter($resource)
		//		{
		//			return mysql_num_rows($resource);
		//		}
		//////////////////////////
		$existed = $this->db_query("SELECT * FROM $table WHERE $field1='$value1' and $field2='$value2'", false);
		$no = $existed; //counter($existed) ;
		return $no;
	}



	function getcheckdetails($user, $password)
	{
		//echo 'country code : '.$countrycode;
		$desencrypt = new DESEncryption();
		$key = $user; //"mantraa360";
		$cipher_password = $desencrypt->des($key, $password, 1, 0, null, null);
		$str_cipher_password = $desencrypt->stringToHex($cipher_password);

		$label = "";
		$table_filter = " where username='" . $user . "' and password='" . $str_cipher_password . "'";

		$query = "select * from userdata " . $table_filter;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		//echo ' num rows :'.$numrows;
		$dbobject = new dbobject();
		$no_of_pin_misses = $dbobject->getitemlabel('parameter', 'parameter_name', 'no_of_pin_misses', 'parameter_value');
		$pin_missed = $dbobject->getitemlabel('userdata', 'username', $user, 'pin_missed');
		$override_wh = $dbobject->getitemlabel('userdata', 'username', $user, 'override_wh');
		$extend_wh = $dbobject->getitemlabel('userdata', 'username', $user, 'extend_wh');

		if ($numrows > 0) {
			@$ddate = date('w');
			$row = $result[0];

			@$dhrmin = date('Hi');
			$worktime = $dbobject->getitemlabel('parameter', 'parameter_name', 'working_hours', 'parameter_value');
			//echo $dhrmin;
			if ($override_wh == '1') {
				$worktime = $extend_wh;
			}
			$worktimesplit = explode("-", $worktime);
			$lowertime = str_replace(":", "", $worktimesplit[0]);
			$uppertime = str_replace(":", "", $worktimesplit[1]);

			$lowerstatus = ($lowertime < $dhrmin) == '' ? "0" : "1";
			$upperstatus = ($dhrmin < $uppertime) == '' ? "0" : "1";

			$pass_dateexpire = $row['pass_dateexpire'];
			@$expiration_date = strtotime($pass_dateexpire);
			@$today = date('Y-m-d');
			@$today_date = strtotime($today);

			//echo 'exp date: '.$pass_dateexpire.'   -  today date: '.$today;
			//echo 'Change on Logon : '.$row['passchg_logon'];

			if ($row['user_disabled'] == '1') {
				$label = "2";
			} else if ($row['user_locked'] == '1') {
				$label = "3";
			} else if ($row['day_1'] == '0' && $ddate == '0') {
				//You are not allowed to login on Sunday
				$label = "4";
			} else if ($row['day_2'] == '0' && $ddate == '1') {
				//You are not allowed to login on Monday
				$label = "5";
			} else if ($row['day_3'] == '0' && $ddate == '2') {
				//You are not allowed to login on Tuesday
				$label = "6";
			} else if ($row['day_4'] == '0' && $ddate == '3') {
				//You are not allowed to login on Wednesday
				$label = "7";
			} else if ($row['day_5'] == '0' && $ddate == '4') {
				//You are not allowed to login on Thursday
				$label = "8";
			} else if ($row['day_6'] == '0' && $ddate == '5') {
				//You are not allowed to login on Friday
				$label = "9";
			} else if ($row['day_7'] == '0' && $ddate == '6') {
				//You are not allowed to login on Saturday
				$label = "10";
			} else if (!(($lowerstatus == 1) && ($upperstatus == 1))) {
				//You are not allowed to login due to working hours violation
				$label = "11";
			}
			/*else if($expiration_date <=$today_date){
			//$label = "13";
		}
*/ else if ($row['passchg_logon'] == '1') {
				$label = "14";
			} else {
				$label = "1";
				// $query="select sch_name, "
				$_SESSION['username_sess'] = $user;
				$_SESSION['role_id_sess']  = $row['role_id'];
				// $_SESSION['tour']          = $row['tour'];
				$_SESSION['role_name_sess'] = $row['role_name'];
				$_SESSION['firstname_sess'] = $row['firstname'];
				$_SESSION['lastname_sess'] = $row['lastname'];
				$_SESSION['user_id_sess'] = $row['user_id'];
				$uname = $_SESSION['user_id_sess'];
				$_SESSION['sch_unique_id'] = $row['sch_unique_id'];
				
	

				$query = "select * from schools where user_id='$uname'";
				$sql = "select * from userdata where role_id!='001'";
				$result1 = $dbobject->db_query($sql);
				$noRows = count($result1);
				$r = [];
				if ($noRows > 0) {
					$r = $result1[0];
				}
				$_SESSION['sch_user_sess'] = $r['username'];

				$res = $dbobject->db_query($query);
				$norows = count($res);
				$row = [];
				if ($norows > 0) {
					$row = $res[0];
				}

				$_SESSION['sch_id_sess'] = $row['sch_unique_id'];
				$_SESSION['sch_code_sess'] = $row['sch_code'];

				$_SESSION['agent_login'] = "OK";
				$_SESSION['last_page_load'] = time();

				$oper = "IN";
				//$audit = $dbobject->doAuditTrail($oper);
				$dbobject->resetpinmissed($user);
			}
			//$label = $user.'|'.$row['role_id'].'|'.$row['role_name'].'|'.$row['branch_code'].'|'.$row['firstname'].'|'.$row['lastname'];
		} else {
			if ($no_of_pin_misses == $pin_missed) {
				$label = "12";
				$dbobject->updateuserlock($user, '1');
			} else {
				$label = "0";
				$dbobject->updatepinmissed($user);
			}
		}
		return $label;
	}

	///// NEW ADDITIONS

	function logaccess($username, $time, $message)
	{
		$filename = date("Y-M-d");
		$my_file = "logs/" . $filename . '.log';
		$success =  $time . ' by ' . $username . ' --- using  ' . $_SERVER['REMOTE_ADDR'] . ' -- ' . $message . "\r\n";
		$handle = fopen($my_file, 'a+') or die('Cannot open file:  ' . $my_file); //implicitly creates file
		fwrite($handle, $success);
		fclose($handle);
	}

	function getuserip_status($user)
	{
		$date = date("Y-m-d");
		$qr1 = " SELECT AUDIT_IP,AUDIT_USER FROM audit_trail_account WHERE AUDIT_USER = '" . $user . "' AND
	SUBSTR(AUDIT_T_IN, 1, 10)= '$date'  AND AUDIT_T_OUT IS NULL  ";
		//echo $qr1;
		//			$mq1 = $this->db_query($qr1);
		$mn1 = $this->db_query($qr1, false);
		$label = 0;
		if ($mn1 > 0) {
			$label = $mn1;
		}
		return $label;
	}


	function doAuditTrai_logout($operation, $user)
	{
		//$count_entry = 0;
		//$user= $_SESSION[username_sess];
		$date = date("Y-m-d");
		$client_ip = $_SERVER['REMOTE_ADDR'];
		$query = "UPDATE  audit_trail_account SET AUDIT_T_OUT=now() WHERE AUDIT_USER='$user'
				   AND SUBSTR(AUDIT_T_IN, 1, 10) = '$date' ";
		//echo $query;
		//$unset = unset($_SESSION['IN']);
		//				   $result = mysql_query($query);
		$count_entry = $this->db_query($query, false);

		return $count_entry;
	}



	function doAuditTrail($operation)
	{
		//$count_entry = 0;
		$user = $_SESSION[username_sess];
		$client_ip = $_SERVER['REMOTE_ADDR'];

		if ($operation == "IN") {
			@$now = date("Y-m-d H:i:s");
			$_SESSION['IN'] = $now;
			$query = " INSERT INTO  audit_trail_account (AUDIT_USER,AUDIT_T_IN,AUDIT_IP)
			  VALUES('$user','$now','$client_ip')";
			//echo $query;
			//			$result = $this->db_query($query);
			$count_entry = $this->db_query($query, false);
		} else
			   if ($operation == "0UT") {
			//echo "innow";
			$now = $_SESSION['IN'];
			$query = "UPDATE  audit_trail_account SET AUDIT_T_OUT=now() WHERE AUDIT_USER='$user'
				   AND AUDIT_T_IN='$now'";
			//echo $query;
			//$unset = unset($_SESSION['IN']);
			//				   $result = mysql_query($query);
			$count_entry = $this->db_query($query, false);
		}
		return $count_entry;
	}

	function getlastlogin($user)
	{
		$date = date("Y-m-d");
		//check to see if the user has logged in to this system or another system
		$qr = " SELECT AUDIT_IP, AUDIT_T_IN FROM audit_trail_account WHERE AUDIT_USER='" . $user . "' AND
			        SUBSTR(AUDIT_T_IN, 1, 10)= '$date'  ORDER BY AUDIT_T_IN DESC LIMIT 1  ";
		//echo $qr;
		$mq = $this->db_query($qr);
		$mn = count($mq);
		if ($mn > 0) {
			$rr = $mq[0];
			$the_ip = $rr["AUDIT_IP"];
			$last_time_in = $rr["AUDIT_T_IN"];
		}
		return $last_time_in;
	}



	function reset_ip($user)
	{

		//check to see if the user has logged in to this system or another system
		$date = date("Y-m-d");
		$qr = " SELECT AUDIT_IP FROM audit_trail_account WHERE AUDIT_USER='" . $user . "' AND
			        SUBSTR(AUDIT_T_IN, 1, 10)= '$date'  AND AUDIT_T_OUT IS NULL ";
		//echo $qr;
		$mq = $this->db_query($qr);
		$mn = count($mq);
		if ($mn > 0) {
			$rr = $mq[0];
			$the_ip = $rr["AUDIT_IP"];
			$sys_ip  = $_SERVER['REMOTE_ADDR'];
			//$label = "16";
			$operation = "0UT";
			$dbobject = new dbobject();
			$audit = $dbobject->doAuditTrai_logout($operation, $user);
		}
	}

	function getitemlabel2($tablename, $table_col, $table_val, $table_col2, $table_val2, $ret_val)
	{
		//echo 'country code : '.$countrycode;
		$label = "";
		$table_filter = " where " . $table_col . "='" . $table_val . "' and " . $table_col2 . "='" . $table_val2 . "'";

		$query = "select " . $ret_val . " from " . $tablename . $table_filter;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			$row = $result[0];
			$label = $row[$ret_val];
		}
		return $label;
	}
	function getitemlabel4($tablename, $table_col, $table_val, $table_col2, $table_val2, $table_col3, $table_val3, $table_col4, $table_val4, $ret_val)
	{
		//echo 'country code : '.$countrycode;
		$label = "";
		$table_filter = " where " . $table_col . "='" . $table_val . "' and " . $table_col2 . "='" . $table_val2 . "' and " . $table_col3 . "='" . $table_val3 . "' and " . $table_col4 . "='" . $table_val4 . "'";

		$query = "select " . $ret_val . " from " . $tablename . $table_filter;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			$row = $result[0];
			$label = $row[$ret_val];
		}
		return $label;
	}


	function reset_login_status($user)
	{
		$date = date("Y-m-d");
		$now = $_SESSION['IN'];
		$qr1 = " SELECT AUDIT_IP,AUDIT_USER FROM audit_trail_account WHERE AUDIT_USER = '" . $user . "' AND
	AUDIT_T_IN= '$now'  AND AUDIT_T_OUT IS NULL AND AUDIT_IP = '" . $_SERVER['REMOTE_ADDR'] . "' ";
		//echo $qr1;
		//			$mq1 = mysql_query($qr1);
		$mn1 = $this->db_query($qr1, false);
		$label = 0;
		if ($mn1 > 0) {
			$label = $mn1;
		}
		return $label;
	}
	// END NEW ADDITIONS





	function accesslog($accessflag, $user)
	{
		$dbobject = new dbobject();
		$logid = $dbobject->paddZeros($dbobject->getnextid("ACCSSLOG"), 4);
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$querylog = "INSERT INTO access_log (logid,accessflag,created,posted_by,posted_ip) VALUES ('$logid','$accessflag',now(),'$user','$ipaddr')";
		@$this->db_query($querylog); //or die(mysql_error());
	}
	function updatepinmissed($username)
	{
		$query = "update userdata set pin_missed=pin_missed+1 where username= '$username'";
		//echo $query;
		$resultid = $this->db_query($query, false);
		//		$numrows = mysql_affected_rows();
	}
	function resetpinmissed($username)
	{
		$query = "update userdata set pin_missed=0 where username= '$username'";
		//echo $query;
		$resultid = $this->db_query($query, false);
		//		$numrows = mysql_affected_rows();
	}
	function updateuserlock($username, $value)
	{
		$query = "update userdata set user_locked='$value' where username= '$username'";
		echo $query;
		$resultid = $this->db_query($query, false);
		//		$numrows = mysql_affected_rows();
	}

	//// select a field from a table
	function getitemlabel($tablename, $table_col, $table_val, $ret_val)
	{
		$label = "";
		$table_filter = " where " . $table_col . "='" . $table_val . "'";

		$query = "select " . $ret_val . " from " . $tablename . $table_filter;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			$row = $result[0];
			$label = $row[$ret_val];
		}
		return $label;
	}

	function getitemlabelmenu($tablename, $table_col, $table_val, $ret_val)
	{
		$label = "";
		$table_filter = " where " . $table_col . "='" . $table_val . "'";

		$query = "select " . $ret_val . " from " . $tablename . $table_filter;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			//		while($row = mysql_fetch_array($result)){
			foreach ($result as $row) {
				$label .= "'" . $row[$ret_val] . "',";
			}
			$label = rtrim($label, ",");
		}
		return $label;
	}

	///////////////
	function loadParameters()
	{
		$label = "";
		$query = "select * from parameter";
		$result = $this->db_query($query);
		$numrows = mysql_num_rows($result);
		$label   = "";
		foreach ($result as $row) {
			$label = $label . '"' . $row["parameter_name"] . '"=>"' . $row["parameter_value"] . "\", ";
			$_SESSION[$row["parameter_name"]] = $row["parameter_value"];
		}
		//		for($i=0; $i<$numrows; $i++){
		//			$row = mysql_fetch_array($result);
		//			$label = $label .'"'.$row["parameter_name"].'"=>"'.$row["parameter_value"]."\", ";
		//			$_SESSION[$row["parameter_name"]] = $row["parameter_value"];
		//		}
		return $label;
	}
	//////////
	function getrecordset($tablename, $table_col, $table_val)
	{
		$label = "";
		$table_filter = " where " . $table_col . "='" . $table_val . "'";

		$query = "select * from " . $tablename . $table_filter;
		//	echo $query;
		$result = $this->db_query($query);
		//$numrows = mysql_num_rows($result);
		/*
		if($numrows > 0){
			$row = mysql_fetch_array($result);
			$label = $row[$ret_val];
		}
		*/
		return $result;
	}
	/////////////////
	function getrecordsetdata($query)
	{
		$query = $query;
		//echo $query;
		$result = $this->db_query($query);
		return $result;
	}

	//Role Changer
	function branch_changer($brac_code)
	{
		$_SESSION[role_id_sess] = $brac_code;
		return 1;
	}

	//////////////////
	function getparentmenu($opt)
	{
		$filter = "";
		$options = "<option value='#'>::: None ::: </option>";
		/*
		 if($opt!= ""){
		 $filter = "where menu_id='".$opt."' and parent_id='#' "; //" username='$username' and password='$password' ";
		 }else{
		 */
		$filter = "where parent_id='#' or parent_id2='#'  order by menu_order";
		//}
		$query = "select distinct menu_id, menu_name from menu  " . $filter;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			for ($i = 0; $i < $numrows; $i++) {
				$row = $result[$i];
				//echo $row['country_code'];
				if ($opt == $row['menu_id']) $filter = 'selected';
				//echo ($opt=='$row["country_code"]'?'selected':'None');
				$options = $options . "<option value='$row[menu_id]' $filter >$row[menu_name]</option>";
				$filter = '';
			}
		}
		return $options;
	}

	function getstatemenu($opt)
	{
		$filter = "";
		$options = "<option value='#'>::: None ::: </option>";

		$filter = "where 1=1";
		//}
		$query = "select distinct state from states  " . $filter;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			for ($i = 0; $i < $numrows; $i++) {
				$row = $result[$i];
				if ($opt == $row['state']) $filter = 'selected';
				$options = $options . "<option value='$row[state]' $filter >$row[state]</option>";
				$filter = '';
			}
		}
		return $options;
	}

	public function getcommitmentmenu($opt)
	{
		$filter = "";
		$options = "<option value='#'>::: None ::: </option>";

		$filter = "where 1=1";
		//}
		$query = "select distinct commitment from commitment  " . $filter;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			for ($i = 0; $i < $numrows; $i++) {
				$row = $result[$i];
				if ($opt == $row['commitment']) $filter = 'selected';
				$options = $options . "<option value='$row[commitment]' $filter >$row[commitment]</option>";
				$filter = '';
			}
		}
		return $options;
	}

	public function getStaffType($opt)
	{
		$filter = "";
		$options = "<option value='#'>::: None ::: </option>";

		$filter = "where 1=1";
		//}
		$query = "select distinct staff_type from staff_type  " . $filter;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			for ($i = 0; $i < $numrows; $i++) {
				$row = $result[$i];
				if ($opt == $row['staff_type']) $filter = 'selected';
				$options = $options . "<option value='$row[staff_type]' $filter >$row[staff_type]</option>";
				$filter = '';
			}
		}
		return $options;
	}

	public function getlocationmenu($opt)
	{
		$filter = "";
		$options = "<option value='#'>::: None ::: </option>";

		$filter = "where 1=1";
		//}
		$query = "select distinct location from location  " . $filter;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			for ($i = 0; $i < $numrows; $i++) {
				$row = $result[$i];
				if ($opt == $row['location']) $filter = 'selected';
				$options = $options . "<option value='$row[location]' $filter >" . ucfirst($row[location]) . "</option>";
				$filter = '';
			}
		}
		return $options;
	}

	public function getClassList($opt)
	{
		$filter = "";
		$options = "<option value='#'>::: None ::: </option>";

		$filter = "where 1=1";
		//}
		$query = "select distinct class_name from class  " . $filter;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			for ($i = 0; $i < $numrows; $i++) {
				$row = $result[$i];
				if ($opt == $row['class_name']) $filter = 'selected';
				$options = $options . "<option value='$row[class_name]' $filter >" . ucfirst($row[class_name]) . "</option>";
				$filter = '';
			}
		}
		return $options;
	}

	public function getclassteacher($opt)
	{
		$filter = "";
		$options = "<option value='#'>::: None ::: </option>";

		$userID = $_SESSION['user_id_sess'];
		// $filter = "where 1=1";
		$filter = "where posted_user='$userID' and role_id='503'";
		$query = "select distinct username from userdata " . $filter;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			for ($i = 0; $i < $numrows; $i++) {
				$row = $result[$i];
				if ($opt == $row['username']) $filter = 'selected';
				$options = $options . "<option value='$row[username]' $filter >" . ucfirst($row[username]) . "</option>";
				$filter = '';
			}
		}
		return $options;
	}

	public function getcategorymenu($opt)
	{
		$filter = "";
		$options = "<option value='#'>::: None ::: </option>";

		$filter = "where 1=1";
		//}
		$query = "select distinct category from category  " . $filter;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			for ($i = 0; $i < $numrows; $i++) {
				$row = $result[$i];
				if ($opt == $row['category']) $filter = 'selected';
				$options = $options . "<option value='$row[category]' $filter >" . ucfirst($row[category]) . "</option>";
				$filter = '';
			}
		}
		return $options;
	}

	function getlgamenu($opt)
	{
		$filter = "";
		$options = "<option value='#'>::: None ::: </option>";

		$filter = "where 1=1";
		//}
		$query = "select distinct lga from lga  " . $filter;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			for ($i = 0; $i < $numrows; $i++) {
				$row = $result[$i];
				if ($opt == $row['lga']) $filter = 'selected';
				$options = $options . "<option value='$row[lga]' $filter >$row[lga]</option>";
				$filter = '';
			}
		}
		return $options;
	}

	public function getdescmenu($opt)
	{
		$filter = "";
		$options = "<option value='#'>::: None ::: </option>";

		$filter = "where 1=1";
		//}
		$query = "select distinct description from description  " . $filter;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			for ($i = 0; $i < $numrows; $i++) {
				$row = $result[$i];
				if ($opt == $row['description']) $filter = 'selected';
				$options = $options . "<option value='$row[description]' $filter >" . ucfirst($row[description]) . "</option>";
				$filter = '';
			}
		}
		return $options;
	}

	public function getclassorder($opt)
	{
		$filter = "";
		$options = "<option value='#'>::: None ::: </option>";

		$filter = "where 1=1";
		//}
		$query = "select class_order from class_order  " . $filter;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			for ($i = 0; $i < $numrows; $i++) {
				$row = $result[$i];
				if ($opt == $row['class_order']) $filter = 'selected';
				$options = $options . "<option value='$row[class_order]' $filter >" . ucfirst($row[class_order]) . "</option>";
				$filter = '';
			}
		}
		return $options;
	}

	function getsubmenu($opt)
	{
		$filter = "";
		$options = "";
		if ($opt != "") {
			$filter = "where parent_id='$opt' order by menu_order"; //" username='$username' and password='$password' ";
		}
		$query = "select distinct menu_id, menu_name from menu  " . $filter;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			for ($i = 0; $i < $numrows; $i++) {
				$row = $result[$i];
				$options = $options . "<option value='$row[menu_id]' $filter >$row[menu_name]</option>";
				$filter = '';
			}
		}
		return $options;
	}
	////////////////////////////////////
	function reorder_submenu($parent_menu, $sub_menu)
	{
		$num_count = 0;
		$sub_menu_arr = explode(',', $sub_menu);
		for ($i = 0; $i < sizeof($sub_menu_arr); $i++) {
			$query = "update menu set menu_order=$i where menu_id= '$sub_menu_arr[$i]'";
			//echo $query;
			//			$result = mysql_query($query);
			$num_count += $this->db_query($query, false);
		}
		return $num_count;
	}
	///////////////////////////////////
	function validatepassword($user, $password)
	{
		//echo 'country code : '.$countrycode;
		$desencrypt = new DESEncryption();
		$key = $user; //"mantraa360";
		$cipher_password = $desencrypt->des($key, $password, 1, 0, null, null);
		$str_cipher_password = $desencrypt->stringToHex($cipher_password);

		$label = "";
		$table_filter = " where username='" . $user . "' and password='" . $str_cipher_password . "'";

		$query = "select * from userdata" . $table_filter;
		//echo $query;
		//	$result = mysql_query($query);
		$numrows = $this->db_query($query, false);
		if ($numrows > 0) $label = "1";
		else $label = "-1";

		return $label;
	}

	// Change to user profile password
	function doPasswordChange($username, $user_password)
	{
		$desencrypt = new DESEncryption();
		$key = $username;
		$cipher_password = $desencrypt->des($key, $user_password, 1, 0, null, null);
		$str_cipher_password = $desencrypt->stringToHex($cipher_password);
		$query_data = "update userdata set password='$str_cipher_password' where username= '$username'";
		//echo $query_data;
		//			$result_data = mysql_query($query_data);
		$count_entry = $this->db_query($query_data, false);

		return $count_entry;
	}
	function pick_role($opt)
	{
		$filter = "";
		$options = "<option value=''>::: Select a Role ::: </option>";
		/*
	if($opt!= ""){
	 $filter = "where role_id='".$opt."'"; //" username='$username' and password='$password' ";
	 }
	 */
		$dbobject = new dbobject();
		$user_role_session = $_SESSION['role_id_sess'];
		//$filter_role_id = $dbobject->getitemlabel('parameter','parameter_name','admin_code','parameter_value');
		//$filteradmin = ($user_role_session == $filter_role_id)?"":" and role_id not in ('".$filter_role_id."')";
		$query = "select distinct role_id, role_name from role where 1=1  "; //.$filteradmin;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			foreach ($result as $row) {
				//		$row = mysql_fetch_array($result);
				//echo $row['country_code'];
				if ($opt == $row['role_id']) $filter = 'selected';
				//echo ($opt=='$row["country_code"]'?'selected':'None');
				$options = $options . "<option value='$row[role_id]' $filter >$row[role_name]</option>";
				$filter = '';
			}
		}
		return $options;
	}
	////////////////////////
	function doRole($role_id, $role_name)
	{
		$count_entry = 0;
		$query = "select * from role  where role_id='$role_id'";
		//echo $query;
		$result = mysql_query($query);
		$numrows = mysql_num_rows($result);
		if ($numrows >= 1) {
			$query_data = "update role set role_name='$role_name', where role_id='$role_id' ";
			$result_data = mysql_query($query_data);
			$count_entry = mysql_affected_rows();
		} else {
			$sql = "select * from role  where role_name='$role_name'";
			if ($res = mysql_query($sql)) {
				if (mysql_num_rows($res) >= 1) {
					$count_entry = -9;
				} else {
					$query_data = "insert into role (role_id,role_name,created)
		values( '$role_id','$role_name',now())";
					//echo $query_data;
					$result_data = mysql_query($query_data);
					$count_entry = mysql_affected_rows();
				}
			}
		}
		return $count_entry;
	}


	function doUser($operation, $username, $userpassword, $firstname, $lastname, $userID, $schoolID, $email, $phone, $chgpword_logon, $user_locked, $user_disable, $day_1, $day_2, $day_3, $day_4, $day_5, $day_6, $day_7, $role_id, $commitment)
	{
		$desencrypt = new DESEncryption();
		$count_entry = 0;
		$key = $username;
		$cipher_password = $desencrypt->des($key, $userpassword, 1, 0, null, null);
		$str_cipher_password = $desencrypt->stringToHex($cipher_password);
		$query = "select * from userdata where username='$username'";
		$result = $this->db_query($query);
		$numrows = count($result);
		// echo $numrows;
		//$operation = $_SESSION['save_user_operation'];
		//echo $operation.":::".$numrows.":::";
		if ($numrows >= 1 && $operation == 'new') {
			$count_entry = -9;
		}
		$dstta = date('Y-m-d');
		if ($numrows >= 1 && $operation != 'new') {
			///////////////////////////
			$addquery = $user_locked == '0' ? ",pin_missed=0" : "";
			$query_data = "update userdata set username='$username', password='$str_cipher_password', role_id='$role_id', commitment='$commitment', firstname='$firstname', lastname='$lastname', email='$email', mobile_phone='$phone', passchg_logon='$chgpword_logon', user_disabled='$user_disable', user_locked='$user_locked', day_1='$day_1', day_2='$day_2', day_3='$day_3', day_4='$day_4', day_5='$day_5', day_6='$day_6', day_7='$day_7', modified=now() where user_id='$userID'";
			$result_data = mysql_query($query_data) or die(mysql_error());
			// echo $query_data;
			//echo mysql_error();
			$count_entry = mysql_affected_rows();
		}
		if ($numrows == 0 && $operation == 'new') {
			/*$pass_expiry_days = $_SESSION['password_expiry_days'];
					$today = @date("Y-m-d");
					$pass_dateexpire = @date("Y-m-d",strtotime($today."+".$pass_expiry_days."days"));*/
			$pass_dateexpire = date('Y-m-d', strtotime("+60 days"));
			$post_user = $_SESSION['user_id_sess'];
			//echo "EXPIRED : ".$pass_dateexpire;
			$query_data = "insert into userdata (username,password,role_id,sch_unique_id,commitment, firstname, lastname, user_id, email, mobile_phone, passchg_logon, user_disabled, user_locked,day_1,day_2,day_3,day_4,day_5,day_6,day_7,created,posted_user,pass_dateexpire) values('$username','$str_cipher_password','$role_id', '$schoolID', '$commitment', '$firstname','$lastname', '$userID', '$email','$phone','$chgpword_logon','$user_disable','$user_locked','$day_1', '$day_2', '$day_3', '$day_4', '$day_5', '$day_6', '$day_7', now(), '$post_user', '$pass_dateexpire')";
			// echo $query_data;
			$result_data = mysql_query($query_data); //or die(mysql_error());
			$count_entry = mysql_affected_rows();
		} //End inner else
		//echo $query_data;
		return $count_entry;
	}

	public function doPartialUser($operation, $username, $userpassword, $firstname, $lastname, $userID, $email, $phone, $chgpword_logon, $user_locked, $user_disable, $day_1, $day_2, $day_3, $day_4, $day_5, $day_6, $day_7, $role_id, $commitment, $sch_unique_id)
	{
		$desencrypt = new DESEncryption();
		$count_entry = 0;
		$key = $username;
		$cipher_password = $desencrypt->des($key, $userpassword, 1, 0, null, null);
		$str_cipher_password = $desencrypt->stringToHex($cipher_password);
		$query = "select * from userdata where username='$username'";
		$result = $this->db_query($query);
		$numrows = count($result);
		// echo $numrows;
		//$operation = $_SESSION['save_user_operation'];
		//echo $operation.":::".$numrows.":::";
		if ($numrows >= 1 && $operation == 'new') {
			$count_entry = -9;
		}
		$dstta = date('Y-m-d');
		if ($numrows >= 1 && $operation != 'new') {
			$query_data = "UPDATE `userdata` SET `username` = '$username', `password` = '$str_cipher_password', `commitment` = '$commitment',`firstname` = '$firstname', `lastname` = ''$lastname, `email` = '$email', `mobile_phone` = '$phone', `user_disabled` = '$user_disable', `user_locked` = '$user_locked', `day_1` = '$day_1', `day_2` = '$day_2', `day_3` = '$day_3', `day_4` = '$day_4', `day_5` = '$day_5', `day_6` = '$day_6', `day_7` = '$day_7', `modified` = now(), `posted_user` = '$userID' WHERE 'user_id' = '$userID'";
		}
		if ($numrows == 0 && $operation == 'new') {
			/*$pass_expiry_days = $_SESSION['password_expiry_days'];
				$today = @date("Y-m-d");
				$pass_dateexpire = @date("Y-m-d",strtotime($today."+".$pass_expiry_days."days"));*/
			$pass_dateexpire = date('Y-m-d', strtotime("+60 days"));
			$post_user = $_SESSION['user_id_sess'];
			$query_data = "insert into userdata (username,password,role_id,sch_unique_id,commitment, firstname, lastname, user_id, email, mobile_phone, passchg_logon, user_disabled, user_locked,day_1,day_2,day_3,day_4,day_5,day_6,day_7,created,posted_user,pass_dateexpire) values('$username','$str_cipher_password','$role_id', '$sch_unique_id', '$commitment', '$firstname','$lastname', '$userID', '$email','$phone','$chgpword_logon','$user_disable','$user_locked','$day_1', '$day_2', '$day_3', '$day_4', '$day_5', '$day_6', '$day_7', now(), '$post_user', '$pass_dateexpire')";
			// echo $query_data;
			$result_data = mysql_query($query_data); //or die(mysql_error());
			$count_entry = mysql_affected_rows();
		} //End inner else
		//echo $query_data;
		return $count_entry;
	}
	function paddZeros($id, $length)
	{
		$data = "";
		$zeros = "";
		$rem_len = $length - strlen($id);

		if ($rem_len > 0) {
			for ($i = 0; $i < $rem_len; $i++) {
				$zeros .= "0";
			}
			$data = $zeros . $id;
		} else {
			$data = $id;
		}
		return $data;
	}

	///////////////////////////////
	function getnextid($tablename)
	{
		//require_once("../../Copy of acomoran/lib/connect.php");
		$id = 0;
		$query = "update gendata set table_id=table_id+1 where table_name= '$tablename'";
		//echo $query;
		$resultid = $this->db_query($query, false);
		$numrows = $resultid;
		//echo 'result '.$resultid;
		if ($numrows == 0) {
			$query_ins = "insert into gendata values ('$tablename', 1)";
			//echo $query_ins;
			$result_ins = $this->db_query($query_ins, false);
			$numrows = $result_ins;
		}
		// Get the new id
		$query_sel = "select table_id from gendata where table_name= '$tablename'";
		//echo $query;
		$result_sel = $this->db_query($query_sel);
		$numrows_sel = count($result_sel);
		if ($numrows_sel == 1) {
			// $row = mysql_fetch_array($result_sel);
			$id = $result_sel[0]['table_id'];

			//result count when it reaches
			if ($id > 999998) {
				$query = "update gendata set table_id=0 where table_name= '$tablename'";
				//echo $query;
				$resultid = $this->db_query($query, false);
			}
		}

		return $id;
	}
	//////////////////////////////////////////
	function getuniqueid($y, $m, $d)
	{
		$month_year = array(
			'01' => '025',
			'02' => '468',
			'03' => '469',
			'04' => '431',
			'05' => '542',
			'06' => '790',
			'07' => '138',
			'08' => '340',
			'09' => '356',
			'10' => '763',
			'11' => '845',
			'12' => '890'
		);
		$year = array(
			'2009' => '111',
			'2010' => '222',
			'2011' => '333',
			'2012' => '444',
			'2013' => '555',
			'2014' => '777',
			'2015' => '000',
			'2016' => '666',
			'2017' => '999',
			'2018' => '123',
			'2019' => '321',
			'2020' => '431',
			'2021' => '521',
			'2022' => '146',
			'2023' => '246',
			'2024' => '357',
			'2025' => '768',
			'2026' => '430',
			'2027' => '770',
			'2028' => '773',
			'2029' => '873',
			'2030' => '962',
			'2031' => '909',
			'2032' => '830',
			'2033' => '349',
			'2034' => '457',
			'2035' => '248'
		);

		$day = array(
			'01' => '50',
			'02' => '31',
			'03' => '23',
			'04' => '12',
			'05' => '54',
			'06' => '67',
			'07' => '87',
			'08' => '90',
			'09' => '11',
			'10' => '34',
			'11' => '22',
			'12' => '38',
			'13' => '88',
			'14' => '78',
			'15' => '33',
			'16' => '54',
			'17' => '67',
			'18' => '77',
			'19' => '29',
			'20' => '59',
			'21' => '17',
			'22' => '32',
			'23' => '44',
			'24' => '66',
			'25' => '00',
			'26' => '04',
			'27' => '05',
			'28' => '03',
			'29' => '08',
			'30' => '20',
			'31' => '45'
		);

		$unique_id = $year[$y] . $month_year[$m] . $day[$d];
		return $unique_id;
	}
	//////////////////////////////////////////
	function getuniqueid1($y, $m, $d)
	{
		$month_year = array(
			'01' => '25',
			'02' => '68',
			'03' => '69',
			'04' => '31',
			'05' => '42',
			'06' => '90',
			'07' => '38',
			'08' => '40',
			'09' => '56',
			'10' => '63',
			'11' => '45',
			'12' => '90'
		);
		$year = array(
			'2012' => '444',
			'2013' => '555',
			'2014' => '777',
			'2015' => '000',
			'2016' => '666',
			'2017' => '999',
			'2018' => '123',
			'2019' => '321',
			'2020' => '431',
			'2021' => '521',
			'2022' => '146',
			'2023' => '246',
			'2024' => '357',
			'2025' => '768',
			'2026' => '430',
			'2027' => '770',
			'2028' => '773',
			'2029' => '873',
			'2030' => '962',
			'2031' => '909',
			'2032' => '830',
			'2033' => '349',
			'2034' => '457',
			'2035' => '888',
			'2036' => '985',
			'2037' => '394',
			'2038' => '125',
			'2039' => '745',
			'2040' => '236'
		);

		$day = array(
			'01' => '50',
			'02' => '31',
			'03' => '23',
			'04' => '12',
			'05' => '54',
			'06' => '67',
			'07' => '87',
			'08' => '90',
			'09' => '11',
			'10' => '34',
			'11' => '22',
			'12' => '38',
			'13' => '88',
			'14' => '78',
			'15' => '33',
			'16' => '54',
			'17' => '67',
			'18' => '77',
			'19' => '29',
			'20' => '59',
			'21' => '17',
			'22' => '32',
			'23' => '44',
			'24' => '66',
			'25' => '00',
			'26' => '04',
			'27' => '05',
			'28' => '03',
			'29' => '08',
			'30' => '20',
			'31' => '45'
		);

		$unique_id = $year[$y] . $day[$d];
		return $unique_id;
	}

	//////////////////////////////////////////
	function doMenu($menu_id, $menu_name, $menu_url, $parent_menu, $menu_level, $parent_menu2)
	{
		$count_entry = 0;
		$query = "select * from menu  where menu_id='$menu_id'";
		$result = $this->db_query($query, false);
		$numrows = $result;
		if ($numrows >= 1) {
			$query_data = "update menu set menu_name='$menu_name', menu_url='$menu_url',
				 parent_id='$parent_menu',  parent_id2='$parent_menu2', menu_level='$menu_level'
				  where menu_id='$menu_id' ";
			//echo $query_data;
			$result_data = $this->db_query($query_data, false);
			$count_entry = $result_data;
		} else {
			$sql = "select * from menu where menu_name='$menu_name'";
			$res = $this->db_query($sql, false);
			// if($res=mysql_query($sql))
			// {
			if ($res >= 1) {
				$count_entry = -9;
			} else if ($res == 0) {
				$query_data = "insert into menu (menu_id,menu_name,menu_url,parent_id,parent_id2,menu_level,created)
						 values( '$menu_id','$menu_name','$menu_url','$parent_menu','$parent_menu2','$menu_level',now())";
				$result_data = $this->db_query($query_data, false);
				$count_entry = $result_data;
			} else {
				$count_entry = -9;
			}
		}
		return $count_entry;
	}
	/////////////////////////////////////////////////////////
	function getmenu($opt)
	{
		$filter = "";
		$options = "<option value='#'>::: Select Menu Option ::: </option>";
		if ($opt != "") {
			$filter = " and menu_id='" . $opt . "' "; //" username='$username' and password='$password' ";
		}
		$filter .= " order by menu_name ";
		$dbobject = new dbobject();
		$user_role_session = $_SESSION['role_id_sess'];
		//$filter_role_id = $dbobject->getitemlabel('parameter','parameter_name','admin_code','parameter_value');
		//$filter_menu_id = $dbobject->getitemlabelmenu('parameter','parameter_name','admin_menu_code','parameter_value');
		//$filteradmin = ($user_role_session == $filter_role_id)?"":" and menu_id not in (".$filter_menu_id.")";
		$query = "select distinct menu_id, menu_name from menu where 1=1 " . $filter;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			foreach ($result as $row) {
				// $row = mysql_fetch_array($result);
				//echo $row['country_code'];
				if ($opt == $row['menu_id']) $filter = 'selected';
				//echo ($opt=='$row["country_code"]'?'selected':'None');
				$options = $options . "<option value='$row[menu_id]' $filter >$row[menu_name]</option>";
				$filter = '';
			}
		}
		return $options;
	}

	public function doSchool($operation, $sch_unique_id, $sch_code, $sch_name, $sch_addr, $state, $location, $clean_name, $sch_email, $sch_phone, $cat_code, $sch_vil_town, $sch_ward, $sch_lga, $year_established, $owned_by, $school_logo, $role_id, $user_id, $sch_type)
	{
		$query = "select * from schools where sch_unique_id='$sch_unique_id'";
		$result = mysql_query($query);
		$numrows = mysql_num_rows($result);
		if ($numrows >= 1 && $operation == 'new' || $operation == 'School.create_school') {
			$count_entry = -9;
		}

		if ($numrows >= 1 && $operation != 'new') {
			///////////////////////////
			$query_data = "update schools set sch_name='$sch_name', sch_addr='$sch_addr', state='$state', location='$location', sch_email='$sch_email', sch_phone='$sch_phone', category='$cat_code', sch_vil_town='$sch_vil_town', sch_ward='$sch_ward', lga='$sch_lga', date_created=now(), owned_by='$owned_by', sch_type='$sch_type', school_logo='$school_logo'  where sch_unique_id='$sch_unique_id'";
			$result_data = mysql_query($query_data) or die(mysql_error());
			$count_entry = mysql_affected_rows();
			// echo "fsa" . $count_entry;
		}
		if ($numrows == 0 && $operation == 'School.create_school' || $operation == 'new') {
			$query_data = "insert into schools (sch_unique_id, sch_name, sch_code, sch_email, sch_addr, sch_phone, sch_vil_town, sch_ward, owned_by, role_id, user_id, sch_type, category, lga, location, clean_name, state, school_logo, date_created) values ('$sch_unique_id', '$sch_name', '$sch_code', '$sch_email', '$sch_addr', '$sch_phone', '$sch_vil_town', '$sch_ward', '$owned_by', '$role_id', '$user_id', '$sch_type', '$cat_code', '$sch_lga', '$location', '$clean_name', '$state', '$school_logo', '$year_established')";
			$result_data = mysql_query($query_data); //or die(mysql_error());
			$count_entry = count($result_data);
			// echo $count_entry;

			mkdir("skool_Images/" . $sch_code);

			$query = "update userdata set sch_unique_id='$sch_unique_id' where username='$user_id'";
			$result = mysql_query($query); //or die(mysql_error());
			$count_entry = count($result);
		} //End inner else
		return $count_entry;
	}

	public function doSchoolClass($class_id, $operation, $sch_id, $standard_id, $user, $init, $counter)
	{
		$query = "select * from class where class_id='$class_id'";
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		//$operation = $_SESSION['save_user_operation'];
		//echo $operation.":::".$numrows.":::";
		if ($numrows >= 1 && $operation == 'new' || $operation == 'SchoolClass.create_class') {
			$count_entry = -9;
		}
		if ($init == 1) {
			if ($numrows >= 1 && $operation != 'new') {
				$query_data = "update class set standard_id='$standard_id', modified=now() where class_id='$class_id'";
				$result_data = $this->db_query($query_data) or die(mysql_error());
				$count_entry = mysql_affected_rows();

				// when updating class rooms
				$query_data1 = "delete from class where class_id!=$class_id";
				$result1 = $this->db_query($query_data1);
				$count = mysql_affected_rows();
			}
			if ($numrows == 0 && $operation == 'SchoolClass.create_class' || $operation == 'School.create_school' || $operation == 'new') {
				// for ($i = 0; $i < count($counter); $i++) {
				for ($j = 0; $j < $counter; $j++) {
					$classID = $this->getnextid("class");	// So as to generate class id on each loop
					$query_data = "insert into class (class_id, sch_unique_id, standard_id, user, created) values ('$classID', '$sch_id', '$standard_id[$j]', '$user', now())";
					// echo $query_data;
					$result_data = mysql_query($query_data); //or die(mysql_error());
				}
				// }
				// echo "Query Data:::" . $query_data;
				$count_entry = mysql_affected_rows();
			}
			return $count_entry;
		} else {
			if ($numrows >= 1 && $operation != 'new') {
				///////////////////////////
				$query = "update class set standard_id='$standard_id', modified=now() where class_id='$class_id'";
				$result_data = $this->db_query($query) or die(mysql_error());
				$count_entry = mysql_affected_rows();
			}
			if ($numrows == 0 && $operation == 'SchoolClass.create_class' || $operation == 'new') {
				$class_ID = $this->getnextid("class");
				$query_data = "insert into class (class_id, sch_unique_id, standard_id, user, created) values ('$class_ID', '$sch_id', '$standard_id', '$user', now())";
				$result_data = mysql_query($query_data); //or die(mysql_error());
				// echo $query_data;
				$result_data = mysql_query($query_data); //or die(mysql_error());
				$count_entry = mysql_affected_rows();
			} //End inner else
			//echo $query_data;
			return $count_entry;
		}
	}

	public function doClassRoom(
		$operation,
		$class_id,
		$sch_unique_id,
		$class_name,
		$class_arm,
		$class_teacher,
		$user,
		$init,
		$counter
	) {
		$query = "select * from class where class_id='$class_id' and sch_unique_id='$sch_unique_id'";
		$result = mysql_query($query);
		$numrows = mysql_num_rows($result);
		if ($numrows >= 1 && $operation == 'new' || $operation == 'ClassRoom.create_room') {
			$count_entry = -9;
		}
		if ($numrows >= 1 && $operation != 'new') {
			///////////////////////////
			$query = "update class_room set class_name='$class_name', class_arm='$class_arm',class_teacher='$class_teacher',user='$user',  modified=now() where class_id='$class_id'";
			$result_data = $this->db_query($query) or die(mysql_error());
			$count_entry = mysql_affected_rows();
		}
		if ($numrows == 0 && $operation == 'ClassRoom.create_room' || $operation == 'new') {
			$class_ID = $this->getnextid("class");
			$query_data = "INSERT INTO class_room (class_id, sch_unique_id, class_name, class_arm, class_teacher, user, created, modified) VALUES ('$class_ID', '$sch_unique_id', '$class_name', '$class_arm', '$class_teacher', '$user', now())";
			$result_data = mysql_query($query_data); //or die(mysql_error());
			// echo $query_data;
			$result_data = mysql_query($query_data); //or die(mysql_error());
			$count_entry = mysql_affected_rows();
		} //End inner else
		return $count_entry;
	}

	public function doTerm($operation, $sch_id, $term_no, $start_month, $start_yr, $end_month, $end_yr)
	{
		if ($operation == 'School.create_school' || $operation == 'new') {
			$query_data = "insert into term (sch_unique_id, term_no, start_month, start_yr, end_month, end_yr) values ('$sch_id', '$term_no', '$start_month', '$start_yr', '$end_month', '$end_yr')";
			$result_data = mysql_query($query_data); //or die(mysql_error());
			$count_entry = mysql_affected_rows();
		}
		return $count_entry;
	}

	public function doSession($operation, $sess_id, $sess_yr, $sch_id, $init)
	{
		$query = "select * from session where sess_id='$sess_id' and sch_unique_id='$sch_id'";
		$result = mysql_query($query);
		$numrows = mysql_num_rows($result);
		if ($numrows >= 1 && $operation == 'new' || $operation == 'School.create_school') {
			$count_entry = -9;
		}
		if ($init == 1) {
			if ($numrows >= 1 && $operation != 'new') {
				$query_data = "update session set sess_yr='$sess_yr'";
				$result_data = mysql_query($query_data) or die(mysql_error());
				$count_entry = mysql_affected_rows();
			}
			if ($numrows == 0 && $operation == 'SchoolClass.create_class' || $operation == 'School.create_school' || $operation == 'new') {
				$sessID = $this->getnextid("session");
				ini_set('date.timezone', 'Africa/Lagos');
				$end_date = date("Y") + 1;
				$sessYr = date("Y") . "/" . $end_date;
				$query_data = "INSERT INTO `session` (`sess_id`, `sess_yr`, `sch_unique_id`) VALUES ( '$sessID', '$sessYr','$sch_id')";
				$result_data = mysql_query($query_data); //or die(mysql_error());
				$count_entry = mysql_affected_rows();
				// }
				$count_entry = mysql_affected_rows();
			}
			return $count_entry;
		} else {
			echo "not init";
			if ($numrows >= 1 && $operation != 'new') {
				///////////////////////////
				$query_data = "update session set sess_yr='$sess_yr'";
				$result_data = mysql_query($query_data) or die(mysql_error());
				$count_entry = mysql_affected_rows();
			}
			if ($numrows == 0 && $operation == 'School.create_school' || $operation == 'new') {
				$sessID = $this->getnextid("session");
				$query_data = "INSERT INTO `session` (`sess_id`, `sess_yr`) VALUES ( '$sessID', '$sess_yr')";
				$result_data = mysql_query($query_data); //or die(mysql_error());
				$count_entry = mysql_affected_rows();
			} //End inner else
			//echo $query_data;
			return $count_entry;
		}
	}

	function ret_sch_type($param)
	{
		$sch_id = $_SESSION['sch_id_sess'];
		// $dbobject = new dbobject();
		$query = "select * from schools where sch_unique_id='$sch_id'";
		$result = $this->db_query($query);
		$numrows =
			count($result);
		if ($numrows > 0) {
			$row = $result;
			$type
				= $row[0]['sch_type'];
			$sch_type = explode(", ", $type);
			if (in_array($param, $sch_type)) {
				return 1;
			} else {
				return "";
			}
		}
	}

	public function doSubject($operation, $subject_id, $class, $subject, $subject_code, $subject_desc, $user)
	{
		$query = "select * from subject where subject_id='$subject_id'";
		$result = $this->db_query($query);
		$numrows = count($result);

		if ($numrows >= 1 && $operation == "new" || $operation == 'Subject.create_subject') {
			$count_entry = -9;
		}

		if ($numrows >= 1 && $operation != 'new') {
			$query_data = "update subject set subject='$subject', class='$class', subject_code='$subject_code', description='$subject_desc', user='$user'where subject_id='$subject_id'";
			$result_data = $this->db_query($query_data) or die(mysql_error());
			$count_entry = mysql_affected_rows();
			echo $count_entry;
		}
		if ($numrows == 0 && $operation == 'Subject.create_subject' || $operation == 'new') {
			$i_query = "insert into subject (subject_id, subject, class, subject_code, description, user, created) values ('$subject_id', '$subject', '$class', '$subject_code', '$subject_desc', '$user', now())";
			$result_data = mysql_query($i_query); //or die(mysql_error());
			$count_entry = mysql_affected_rows();
		}
		return $count_entry;
	}

	public function doGrade($operation, $grade_id, $grade, $range1, $range2, $remark, $user)
	{
		$query = "select * from grade where grade_id='$grade_id'";
		$result = $this->db_query($query);
		$numrows = count($result);

		if ($numrows >= 1 && $operation == "new" || $operation == 'Grade.create_grade') {
			$count_entry = -9;
		}

		if ($numrows >= 1 && $operation != 'new') {
			$query_data = "update grade set grade_id='$grade_id', grade='$grade', range1='$range1', range2='$range2', remark='$remark', posted_by='$user' where grade_id='$grade_id'";
			$result_data = $this->db_query($query_data) or die(mysql_error());
			$count_entry = mysql_affected_rows();
			echo $count_entry;
		}
		if ($numrows == 0 && $operation == 'Grade.create_grade' || $operation == 'new') {
			$g_query = "insert into grade (grade_id, grade, range1, range2, remark, posted_by, created_at) values ('$grade_id', '$grade', '$range1', '$range2', '$remark', $user, now())";
			echo $g_query;
			$result_data = mysql_query($g_query); //or die(mysql_error());
			$count_entry = mysql_affected_rows();
		}
		return $count_entry;
	}

	/////////////////////////////////
	function getexistrole($opt)
	{
		$filter = "";
		$user_role_session = $_SESSION['role_id_sess'];
		//$options = "<option value='#'>::: Select Menu Option ::: </option>";
		if ($opt != "") {
			$filter = "where menu_id='" . $opt . "' "; //" username='$username' and password='$password' ";
		}
		$query = "select role_id, role_name from role where role_id in (select role_id from menugroup   " . $filter . ") and role_id not in(select parameter_value from parameter where parameter_name='$user_role_session' )";
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			foreach ($result as $row) {
				// $row = mysql_fetch_array($result);
				//echo $row['country_code'];
				//if($opt==$row['role_id']) $filter='selected';
				//echo ($opt=='$row["country_code"]'?'selected':'None');
				$options = $options . "<option value='$row[role_id]' $filter >$row[role_name]</option>";
				$filter = '';
			}
		}
		return $options;
	}
	///////////////////////////////////////////
	function getnonexistrole($opt)
	{
		$filter = "";
		$user_role_session = $_SESSION['role_id_sess'];
		//$options = "<option value='#'>::: Select Menu Option ::: </option>";
		if ($opt != "") {
			$filter = "where menu_id='" . $opt . "' "; //" username='$username' and password='$password' ";
		}
		$query = "select role_id, role_name from role where role_id not in (select role_id from menugroup   " . $filter . ") and role_id not in(select parameter_value from parameter where parameter_name='$user_role_session' )";

		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			foreach ($result as $row) {
				// $row = mysql_fetch_array($result);
				//echo $row['country_code'];
				//if($opt==$row['role_id']) $filter='selected';
				//echo ($opt=='$row["country_code"]'?'selected':'None');
				$options = $options . "<option value='$row[role_id]' $filter >$row[role_name]</option>";
				$filter = '';
			}
		}
		return $options;
	}

	function doMenuGroup($menu_id, $exist_role)
	{
		$comp_id = $_SESSION['comp_id_sess'];
		$count_entry = 0;
		$exist_role_arr = explode(",", $exist_role);
		$role_id = "";
		for ($i = 0; $i < count($exist_role_arr); $i++) {
			$role_id = $role_id . "'" . $exist_role_arr[$i] . "', ";
		}
		$role_id = substr($role_id, 0, (strlen($role_id) - 2));

		$query_data = "delete from menugroup where role_id not in ($role_id) and menu_id='$menu_id' ";
		echo $query_data . '<br>';
		$result_data = mysql_query($query_data);
		$count_entry += mysql_affected_rows();

		for ($i = 0; $i < count($exist_role_arr); $i++) {
			$query_data_i = "insert into menugroup(role_id, menu_id) values ('$exist_role_arr[$i]','$menu_id')";
			//echo $query_data_i.'<br>';
			$result_data_i = mysql_query($query_data_i);
			$count_entry += mysql_affected_rows();
		}

		//echo "Count Entry :: "+$count_entry;
		return $count_entry;
	}
	////////////////////////////////////////////////

	function gettableselect($tablename, $field1, $field2, $opt)
	{
		$filter = "";
		$options = "<option value=''>::: please select option ::: </option>";
		$query = "select distinct $field1, $field2 from $tablename  " . $filter;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			foreach ($result as $row) {
				// $row = mysql_fetch_array($result);
				//echo $row['country_code'];
				if ($opt == $row[$field1]) $filter = 'selected';
				//echo ($opt=='$row["country_code"]'?'selected':'None');
				$options = $options . "<option value='$row[$field1]' $filter >$row[$field2]</option>";
				$filter = '';
			}
		}
		return $options;
	}
	///////////////////////////////////
	function gettableselect2($tablename, $field1, $field2, $opt, $opt2, $opt3)
	{
		$filter = "";
		$options = "<option value=''>::: please select option ::: </option>";
		$query = "select distinct $field1, $field2 from $tablename  where $opt2=$opt3" . $filter;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			foreach ($result as $row) {
				// $row = mysql_fetch_array($result);
				//echo $row['country_code'];
				if ($opt == $row[$field1]) $filter = 'selected';
				//echo ($opt=='$row["country_code"]'?'selected':'None');
				$options = $options . "<option value='$row[$field1]' $filter >$row[$field2]</option>";
				$filter = '';
			}
		}
		return $options;
	}
	///////////////////////////////////
	function gettableselectorder($tablename, $field1, $field2, $opt, $order)
	{
		$filter = "";
		$order_by = "";
		$options = "<option value=''>::: please select option ::: </option>";
		if ($order != '') $order_by = " order by " . $order;
		$query = "select distinct $field1, $field2 from $tablename  " . $filter . $order_by;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			foreach ($result as $row) {
				// $row = mysql_fetch_array($result);
				//echo $row['country_code'];
				if ($opt == $row[$field1]) $filter = 'selected';
				//echo ($opt=='$row["country_code"]'?'selected':'None');
				$options = $options . "<option value='$row[$field1]' $filter >$row[$field2]</option>";
				$filter = '';
			}
		}
		return $options;
	}
	/////////////////////////////////////
	function getdataselect($sql)
	{
		$filter = "";
		$options = "<option value=''>::: please select option ::: </option>";
		//$query = "select distinct $field1, $field2 from $tablename  ".$filter;
		//echo $sql;
		$result = $this->db_query($sql);
		$numrows = count($result);
		if ($numrows > 0) {
			foreach ($result as $row) {
				// $row = mysql_fetch_array($result);
				$options = $options . "<option value='$row[0]' $filter >$row[1]</option>";
				$filter = '';
			}
		}
		return $options;
	}


	function getTblField($tablename, $field1, $field2, $field3)
	{
		$query = "select distinct $field1 from $tablename  where $field2='$field3'";
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			// $row = mysql_fetch_array($result);
			$options = $result[0][$field1];
		}
		return $options;
	}

	function getTblItemList($tablename, $field1)
	{
		$options = "<option value=''>::: please select option ::: </option>";
		$query = "select distinct $field1 from $tablename";
		//echo $query;
		$result = $this->db_query($query);
		foreach ($result as $row) {
			$options .= "<option value='$row[$field1]'>$row[$field1]</option>";
		}
		// while($row = mysql_fetch_array($result)){
		// 	$options .= "<option value='$row[$field1]'>$row[$field1]</option>";
		// }
		return $options;
	}

	function getFormInput($tablename, $field2, $field3, $field4, $field5)
	{
		$query = "select * from $tablename  where $field2='$field3' and $field4='$field5'";
		//echo $query;
		$result = $this->db_query($query);
		//$numrows = mysql_num_rows($result);
		/*while($row = mysql_fetch_array($result)){
			$options .= "<input type='checkbox' name='<?php echo $row[$field1]; ?>' id='<?php echo $row[$field1]; ?>'> ".$row[$field]."  &nbsp;&nbsp;&nbsp;&nbsp;".$row[$field1]."<br /><hr></hr>";
		}*/
		return $result;
	}



	function doPasswordChangeExp($username, $user_password, $new_expdate)
	{
		$desencrypt = new DESEncryption();
		$count_entry = 0;
		$key = $username;
		$cipher_password = $desencrypt->des($key, $user_password, 1, 0, null, null);
		$str_cipher_password = $desencrypt->stringToHex($cipher_password);
		$query_data = "update userdata set password='$str_cipher_password', pass_dateexpire='$new_expdate' where username= '$username'";
		//echo $query_data;
		$result_data = $this->db_query($query_data, false);
		$count_entry = $result_data;

		return $count_entry;
	}
	///////////////////////////////
	// Do password change on logon
	function doPasswordChangeLogon($username, $user_password)
	{
		$desencrypt = new DESEncryption();
		$count_entry = 0;
		$key = $username;
		$cipher_password = $desencrypt->des($key, $user_password, 1, 0, null, null);
		$str_cipher_password = $desencrypt->stringToHex($cipher_password);
		$query_data = "update userdata set password='$str_cipher_password', passchg_logon='0' where username= '$username'";
		//echo $query_data;
		$result_data = $this->db_query($query_data, false);
		$count_entry = $result_data;

		return $count_entry;
	}


	function getparameter($opt, $parameter_id, $parameter_table, $parameter_col, $val1)
	{
		$filter = "";
		$options = "<option value=''>::: Select ::: </option>";
		/*
		 if($opt!= ""){
		 $filter = "where menu_id='".$opt."' and parent_id='#' "; //" username='$username' and password='$password' ";
		 }else{
		 */
		$filter1 = "";
		if ($parameter_id != '') {
			$filter1 = "and  " . $parameter_col . " = '$parameter_id' ";
		}
		$filter = " where 1=1 ";
		//}
		$query = "select * from " . $parameter_table . $filter . $filter1;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		$filter = '';
		if ($numrows > 0) {
			foreach ($result as $row) {
				// $row = mysql_fetch_array($result);
				//echo $row['country_code'];
				if ($opt == $row[$val1]) $filter = 'selected';
				//echo ($opt=='$row["country_code"]'?'selected':'None');
				$options = $options . "<option value='$row[$val1]' $filter >$row[$val1]</option>";
				$filter = '';
			}
		}
		return $options;
	}


	////////////////////////////////////////////////////////////////BEGIN CodeEngine SAMABOS/////////////////////////////////////////////////////////


	function doUserAll($username, $userpassword, $surname, $othernames, $address, $email, $phone, $chgpword_logon, $user_locked, $user_disable, $day_1, $day_2, $day_3, $day_4, $day_5, $day_6, $day_7, $override_wh, $extend_wh, $role_id, $role_name, $sex, $title, $uqid, $security_question, $security_answer)
	{
		$dbobject = new dbobject();
		$desencrypt = new DESEncryption();
		$count_entry = 0;
		$key = $username;
		$cipher_password = $desencrypt->des($key, $userpassword, 1, 0, null, null);
		$str_cipher_password = $desencrypt->stringToHex($cipher_password);

		$key = $dbobject->getitemlabel('parameter', 'parameter_name', 'actvt', 'parameter_value');
		$user_uqid = $username . "~" . $uqid;
		$user_uqid = $desencrypt->des($key, $user_uqid, 1, 0, null, null);
		$encrptuqid = $desencrypt->stringToHex($user_uqid);


		$query = "select * from userdata  where username='$username'";
		//echo $query;
		$result = mysql_query($query);
		$numrows = mysql_num_rows($result);
		if (($numrows) >= (1)) {
			$count_entry = 2;
		} else {
			$pass_expiry_days = $_SESSION['password_expiry_days'];
			$today = @date("Y-m-d");
			$pass_dateexpire = @date("Y-m-d", strtotime($today . "+" . $pass_expiry_days . "days"));
			$query_data = "insert into userdata(username, password, role_id, firstname, lastname, address, gender, title, email, mobile_phone, passchg_logon, user_disabled, user_locked, day_1, day_2, day_3, day_4, day_5, day_6, day_7, created, modified, override_wh, extend_wh, pass_dateexpire, login_status, activation_code,hint_question,hint_answer) values ('$username', '$str_cipher_password', '$role_id', '$othernames', '$surname', '$address', '$sex', '$title', '$username', '$phone', '$chgpword_logon', '$user_disable', '$user_locked', '$day_1', '$day_2', '$day_3', '$day_4', '$day_5', '$day_6', '$day_7' , now(), now(), '$override_wh', '$extend_wh', '$pass_dateexpire', '0', '$uqid', '$security_question', '$security_answer')";
			//echo $query_data;
			$result_data = mysql_query($query_data) or die(mysql_error());
			if ((mysql_affected_rows()) > 0) {
				$query_data2 = "insert into customer_balance(username, previous_balance, current_balance, created) values ('$username', 0, 0, now())";
				//echo $query_data;
				$result_data = mysql_query($query_data2) or die(mysql_error());
				$count_entry = mysql_affected_rows();
				if (($count_entry) > 0) {
					//echo "yes";
					$resp = $dbobject->sendEmail($encrptuqid, $username);
					//code to send an email here

				}
			}
		} // End Else
		return $count_entry;
	}

	function getuniqueid2()
	{
		$month_year = array(
			'01' => '025',
			'02' => '468',
			'03' => '469',
			'04' => '431',
			'05' => '542',
			'06' => '790',
			'07' => '138',
			'08' => '340',
			'09' => '356',
			'10' => '763',
			'11' => '845',
			'12' => '890'
		);

		$year = array(
			'2009' => '111',
			'2010' => '222',
			'2011' => '333',
			'2012' => '444',
			'2013' => '555',
			'2014' => '777',
			'2015' => '000',
			'2016' => '666',
			'2017' => '999',
			'2018' => '123',
			'2019' => '321',
			'2020' => '431',
			'2021' => '521',
			'2022' => '146',
			'2023' => '246',
			'2024' => '357',
			'2025' => '768',
			'2026' => '430',
			'2027' => '770',
			'2028' => '773',
			'2029' => '873',
			'2030' => '962',
			'2031' => '909',
			'2032' => '830',
			'2033' => '349',
			'2034' => '457',
			'2035' => '248'
		);

		$day = array(
			'01' => '50',
			'02' => '31',
			'03' => '23',
			'04' => '12',
			'05' => '54',
			'06' => '67',
			'07' => '87',
			'08' => '90',
			'09' => '11',
			'10' => '34',
			'11' => '22',
			'12' => '38',
			'13' => '88',
			'14' => '78',
			'15' => '33',
			'16' => '54',
			'17' => '67',
			'18' => '77',
			'19' => '29',
			'20' => '59',
			'21' => '17',
			'22' => '32',
			'23' => '44',
			'24' => '66',
			'25' => '00',
			'26' => '04',
			'27' => '05',
			'28' => '03',
			'29' => '08',
			'30' => '20',
			'31' => '45'
		);
		//////////////--------> get 2day's date
		$today_date = @date('Y-m-d');
		$date_arr = explode("-", $today_date);
		$unique_id = $year[$date_arr[0]] . $month_year[$date_arr[1]] . $day[$date_arr[2]];
		return $unique_id;
	}


	function getCustomerDetails($str, $URL)
	{

		require "waiseconnectclient.php";
		$wcclient = new wcclient;
		$resp = $wcclient->eHajjWCClientGetPilgrimDetails($str, $URL);
		return $resp;
	}





	function goCallWebServer($branch_acct, $merchant_id, $depositorname, $portalid, $amount, $trans_id)
	{
		require "waiseconnectclient.php";
		$wcclient = new wcclient;
		$dbobject = new dbobject();
		///////////////////////////////////////////////////////////////////////////////////////////////
		//$officer_branch_code = $dbobject->getitemlabel('userdata','username',$officer,"branch_code");
		$destAccount = $dbobject->getitemlabel('merchant_settlement_account_setup', 'merchant_id', $merchant_id, 'account_no');
		$sourceAccount = $branch_acct; //$dbobject->getitemlabel('station','station_code',$officer_branch_code,'station_acct');
		$wcresp = $wcclient->doDepositWC($depositorname, $portalid, $amount, $sourceAccount, $destAccount, $trans_id);
		//print_r($wcresp);
		$wcresp = explode("~", $wcresp);
		if ($wcresp[0] == '000') {
			$updateresp = $dbobject->doDbTblUpdate('transaction_table', array('response_code'), array(0), array('transaction_id'), array($trans_id));
			if ($updateresp == '1') {
				$resp = "SUCCESSFUL:Please wait you will be redirected in a moment";
			} else {
				$resp = "ERROR:Updating status from WaiseConnent";
				$attempt = $dbobject->logaccess($user, date("Y-m-d H:i:s"), mysql_error());
			}
		} else {
			$updateresp = $dbobject->doDbTblUpdate('transaction_table', array('response_code'), array($wcresp[0]), array('transaction_id'), array($trans_id));
			if ($updateresp == '1') {
				$resp = "ERROR:" . $wcresp[1]; //.$wcresp[0];
			} else {
				$resp = "ERROR:Updating status from WaiseConnent";
				$attempt = $dbobject->logaccess($user, date("Y-m-d H:i:s"), mysql_error());
			}
		}
		return $resp;
		////////////////////////////////////////////////////////////////////////////////////////////////////
	}

	function getitemcount($tablename, $table_col, $table_val, $ret_val)
	{
		$label = "";
		$table_filter = " where " . $table_col . "='" . $table_val . "'";

		$query = "select Count(" . $ret_val . ") counter from " . $tablename . $table_filter;
		//echo $query;
		$result = $this->db_query($query); //or die(mysql_error());
		$numrows = count($result);
		if ($numrows > 0) {
			// $row = mysql_fetch_array($result);
			$label = $result[0]['counter'];
		}
		return $label;
	}



	////////////////////////////////////////////////////////////////END CodeEngine SAMABOS///////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////
	//////////////////////////Beginning of Isaiah///////////////////////////////
	function getrecordsetArr($tablename, $table_col_arr, $table_val_arr)
	{
		$where_clause = " ";
		for ($i = 0; $i < count($table_col_arr); $i++) {
			$where_clause .= $table_col_arr[$i] . "='" . $table_val_arr[$i] . "' and ";
		}

		$where_clause = rtrim($where_clause, " and ");
		//echo 'country code : '.$countrycode;
		$label = "";
		$table_filter = " where " . $where_clause;

		$query = "select * from " . $tablename . $table_filter;
		//echo $query;
		$result = $this->db_query($query);
		return $result;
	}

	function getrecordsetArrLim($tablename, $table_col_arr, $table_val_arr, $limval, $orderby_arr, $orderdir)
	{
		$where_clause = " ";
		for ($i = 0; $i < count($table_col_arr); $i++) {
			$where_clause .= $table_col_arr[$i] . "='" . $table_val_arr[$i] . "' and ";
		}
		$table_order = '';
		if ($orderby_arr != '') {
			for ($i = 0; $i < count($orderby_arr); $i++) {
				$orderby_str .= $orderby_arr[$i] . ", ";
			}

			$orderby_str = rtrim($orderby_str, ",");
			$table_order = " ORDERBY " . $orderby_str . " " . $orderdir;
		}
		$where_clause = rtrim($where_clause, " and ");
		//echo 'country code : '.$countrycode;
		$label = "";
		$table_filter = " where " . $where_clause . $table_order . " LIMIT " . $limval;

		$query = "select * from " . $tablename . $table_filter;
		//echo $query;
		$result = $this->db_query($query);
		return $result;
	}
	function gettableselectrpt($tablename, $field1, $field2, $opt, $opt2)
	{
		$filter = $opt;
		$options = "<option value=''>::: please select option ::: </option>";
		$query = "select distinct $field1, $field2 from $tablename  " . $opt2;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			foreach ($result as $row) {
				// $row = mysql_fetch_array($result);
				//echo $row['country_code'];
				if ($opt == $row[$field1]) $filter = 'selected';
				//echo ($opt=='$row["country_code"]'?'selected':'None');
				$options = $options . "<option value='$row[$field1]' $filter >$row[$field2]</option>";
				$filter = '';
			}
		}
		return $options;
	}
	function getnextidApp($tablename)
	{
		$id = 0;
		$query = "update gendata set table_id=table_id+1 where table_name= '$tablename'";
		//echo $query;
		$resultid = $this->db_query($query, false);
		$numrows = $resultid;
		if ($numrows == 0) {
			$query_ins = "insert into gendata values ('$tablename', 1)";
			//echo $query_ins;
			$result_ins = $this->db_query($query_ins, false);
			$numrows = $result_ins;
		}
		// Get the new id
		$query_sel = "select table_id from gendata where table_name='$tablename'";
		$result_sel = $this->db_query($query_sel);
		$numrows_sel = count($result_sel);
		if ($numrows_sel == 1) {
			//    $row = mysql_fetch_array($result_sel);
			$id = $result_sel[0]['table_id'];
			//result count when it reaches
			if ($id > 999999998) {
				$query = "update gendata set table_id=0 where table_name= '$tablename'";
				//echo $query;
				$resultid = $this->db_query($query, false);
			}
		}
		return $id;
	}



	function getparameter1($opt, $parameter_id, $parameter_table, $parameter_col, $val1, $val2)
	{
		$filter = "";
		$options = "<option value=''>:: Please Select :: </option>";
		/*
             if($opt!= ""){
             $filter = "where menu_id='".$opt."' and parent_id='#' "; //" username='$username' and password='$password' ";
             }else{
             */
		$filter1 = "";
		if ($parameter_id != '') {
			$filter1 = "and  " . $parameter_col . " = '$parameter_id' ";
		}
		$filter = " where 1=1 ";
		//}
		$query = "select * from " . $parameter_table . $filter . $filter1;
		//echo $query;
		$result = $this->db_query($query); //or die(mysql_error());
		$numrows = count($result);
		$filter = '';
		if ($numrows > 0) {
			foreach ($result as $row) {
				// $row = mysql_fetch_array($result);
				//echo $row['country_code'];
				if ($opt == $row[$val1]) $filter = 'selected';
				//echo ($opt=='$row["country_code"]'?'selected':'None');
				$options = $options . "<option value='$row[$val2]' $filter >$row[$val1]</option>";
				$filter = '';
			}
		}
		//        echo $numrows;
		return $options;
	}
	function getTableSelectArr($tablename, $selarr, $whrarr, $whrvalarr, $order, $orderdir, $opt, $initOpt)
	{
		$filter = $opt;
		$selectVar = " ";
		$whereClause = " where ";
		for ($i = 0; $i < count($selarr); $i++) {
			$selectVar .= $selarr[$i] . ", ";
			if ($i == 0) {
				$optDisplayVal = $selarr[$i];
			} else {
				$optDisplayName .= $row[$selarr[$i]];
			}
		}
		$selectVar = rtrim($selectVar, ', ');

		for ($i = 0; $i < count($whrarr); $i++) {
			$whereClause .= $whrarr[$i] . "='" . $whrvalarr[$i] . "' and ";
		}

		$whereClause = rtrim($whereClause, " and ");
		if ($order != '') {
			if ($orderdir == '') {
				$oderby = 'order by ' . $order . ' asc';
			} else {
				$oderby = 'order by ' . $order . ' ' . $orderdir;
			}
		} else $oderby = "";
		$options = "<option value='#'>::: Select " . $initOpt . " :::</option>";
		$query = "select distinct $selectVar from $tablename " . $whereClause . $oderby;
		//echo $query.'-'.$opt;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			foreach ($result as $row) {
				// $row = mysql_fetch_array($result);
				for ($j = 0; $j < count($selarr); $j++) {
					if ($j > 0) {
						$optDisplayName .= strtoupper($row[$selarr[$j]]) . " ";
					}
				}
				if ($opt == $row[$optDisplayVal]) $filter = 'selected';
				//echo ($opt=='$row["country_code"]'?'selected':'None');
				$options = $options . "<option value='$row[$optDisplayVal]' $filter >$optDisplayName</option>";
				$filter = '';
				$optDisplayName = "";
				//echo 'yes'.$optDisplayName;
				//echo $row[$field1];
			}
		}

		return $options;
	}


	function doDbTblUpdate($tbl, $setFieldArr, $setFieldValArr, $whrFieldArr, $whrFieldValArr)
	{
		if (count($setFieldArr) == count($setFieldValArr) && count($whrFieldArr) == count($whrFieldValArr)) {
			////////// set clause starts here////////////////////////////////
			for ($i = 0; $i < count($setFieldArr); $i++) {
				$setClause .= $setFieldArr[$i] . "='" . $setFieldValArr[$i] . "', ";
			}
			$setClause = rtrim($setClause, ", ");
			//echo $setClause;
			/////////////////////////////////////////////////////////////////
			///////////////where clause starts here/////////////////////////
			for ($j = 0; $j < count($whrFieldArr); $j++) {
				$whrClause .= $whrFieldArr[$j] . "='" . $whrFieldValArr[$j] . "' AND ";
			}
			$whrClause = rtrim($whrClause, " AND ");
			// echo $whrClause;
			///////////////////////////////////////////////////////////////
			////////////the complete query/////////////////////////////////
			$query = "UPDATE " . $tbl . " SET " . $setClause . " WHERE " . $whrClause . " LIMIT 1";
			//echo $query;

			$result = $this->db_query($query, false);
			if ($result >= 0) {
				$resp = 1; //successful
				return $resp;
			} else {
				$resp = 2; //update not successful. Possibly transaction details not available
				return $resp;
			}
		} else {
			$resp = 3; //array count does not match
			return $resp;
		}
	}

	function getItemLabelArr($tablename, $table_col_arr, $table_val_arr, $ret_val_arr)
	{
		$label = "";
		$selectClause = "";
		$whrClause = "";
		/////////////////////////////////////////////////////////////////
		////////// select clause starts here////////////////////////////////
		if ($ret_val_arr == "*") {
			$qquery = "SHOW COLUMNS FROM $tablename ";
			//echo $qquery;
			$result = $this->db_query($qquery);
			//			echo mysql_error();
			//			while($roww = mysql_fetch_array($result))
			foreach ($result as $roww) {
				$selectClause .= $roww[0] . ", ";
				$ret_val[] = $roww[0];
			}
			$retCount = $ret_val;
			$selectClause = rtrim($selectClause, ", ");
		} else {
			for ($i = 0; $i < count($ret_val_arr); $i++) {
				$selectClause .= $ret_val_arr[$i] . ", ";
			}
			$selectClause = rtrim($selectClause, ", ");
			$retCount = $ret_val_arr;
			//echo $setClause;
		}
		/////////////////////////////////////////////////////////////////
		///////////////where clause starts here/////////////////////////
		for ($j = 0; $j < count($table_col_arr); $j++) {
			$whrClause .= " AND " . $table_col_arr[$j] . "='" . $table_val_arr[$j] . "' ";
		}
		$whrClause = rtrim($whrClause, ", ");
		/////////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////////
		$table_filter = " where 1=1 " . $whrClause;

		$query = "select " . $selectClause . " from " . $tablename . $table_filter;
		//		echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if ($numrows > 0) {
			$retValue  = $result;
		}
		return $retValue;
	}


	function fidelityRespCodes($success)
	{
		switch ($success) {
			case "00":
				return 'Successful Transaction';
				break;
			case "01":
				return 'Failed Transaction';
				break;
			case "02":
				return 'Pending Transaction';
				break;
			case "03":
				return 'Transaction Cancelled';
				break;
			case "04":
				return 'Not Processed';
				break;
			case "05":
				return 'Invalid Merchant';
				break;
			case "06":
				return 'Inactive Merchant';
				break;
			case "07":
				return 'Invalid Order ID';
				break;
			case "08":
				return 'Duplicate Order ID';
				break;
			case "09":
				return 'Invalid Amount';
				break;
			default:
				echo "Transaction Failed Due to UNKNOWN ERROR!!!";
				break;
		}
	}

	//////////////////////////////////MR TURBO////////////////////////////////////////////////////////
	function StrongPasswordChecker($pwd)
	{
		if (strlen($pwd) < 8) {
			$error .= "Password too short! Minimum of 8 Xters Required!<br/>";
		}

		if (strlen($pwd) > 20) {
			$error .= "Password too long! Maximum of 20 Xters Required!<br/>";
		}

		if (!preg_match("#[0-9]+#", $pwd)) {
			$error .= "Password must include at least One Number!<br/>";
		}


		if (!preg_match("#[a-z]+#", $pwd)) {
			$error .= "Password must include at least One SMALL Letter! <br/>";
		}


		if (!preg_match("#[A-Z]+#", $pwd)) {
			$error .= "Password must include at least one CAPS! <br/>";
		}


		if (!preg_match("#\W+#", $pwd)) {
			$error .= "Password must include at least One Symbol!<br/>";
		}

		if ($error) {
			$ErrorResp =  $error;
		} else {
			$ErrorResp = '1';
		}
		return $ErrorResp;
	}
	//////////////////////////////////



	//////////////////////////////////Kunle Mutual DIP Functions
	function doDbTblInsert($tbl, $setFieldArr, $setFieldValArr)
	{
		if (count($setFieldArr) == count($setFieldValArr)) {
			////////// set clause starts here////////////////////////////////
			for ($i = 0; $i < count($setFieldArr); $i++) {
				$setClause .= $setFieldArr[$i] . "='" . $setFieldValArr[$i] . "', ";
			}
			$setClause = rtrim($setClause, ", ");
			//echo $setClause;
			/////////////////////////////////////////////////////////////////
			////////////the complete query/////////////////////////////////
			$query = "INSERT INTO " . $tbl . " SET " . $setClause;
			//echo $query;
			if ($this->db_query($query, false) > 0) {
				$resp = 1; //successful
				return $resp;
			} else {
				$resp = 2; //insertion not successful. Possibly transaction details not available
				return $resp;
			}
		} else {
			$resp = 3; //array count does not match
			return $resp;
		}
	}


	function encrypt_password($username, $userpassword)
	{
		$desencrypt = new DESEncryption();
		$key = $username;
		$cipher_password = $desencrypt->des($key, $userpassword, 1, 0, null, null);
		$str_cipher_password = $desencrypt->stringToHex($cipher_password);
		return $str_cipher_password;
	}


	////////////////////////////////////////////////////////
	///////////////////////////////////
	////Author Isaiah//////////////////


	function decrypt_password($username, $pass_crypt)
	{
		$key = $username;
		$desencrypt = new DESEncryption();
		$cipher_password = $desencrypt->hexToString($pass_crypt);
		$plain_pass = $desencrypt->des($key, $cipher_password, 0, 1);
		return $plain_pass;
	}

	function logger($mssg)
	{
		$myfile = fopen("logs.txt", "a");
		$txt = "[ " . date("Y/M/d h:i:s") . " ] --> " . $mssg . "\n";
		fwrite($myfile, $txt);
		fclose($myfile);
	}


	public function db_query($sql, $object = true)
	{
		//file_put_contents('q_log.txt',$sql."\n\n",FILE_APPEND);
		// if you are performig a UPDATE query; you will need to set $object == false
		$result = mysql_query($sql);
		$count  = mysql_affected_rows();
		if ($object) {
			if ($count > 0) {
				$data = array();
				while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {	// this is prompting errors
					$data[] = $row;
				}
				return $data;
			} else {
				return null;
			}
		} else {
			return $count;
		}
	}




	///////////////////by Tosin
	function doPasswordChangeAppl($username, $user_password)
	{
		$desencrypt = new DESEncryption();
		$key = $username;
		$cipher_password = $desencrypt->des($key, $user_password, 1, 0, null, null);
		$str_cipher_password = $desencrypt->stringToHex($cipher_password);
		$query_data = "update userdata_applicants set password='$str_cipher_password' where username= '$username'";
		//echo $query_data;
		$result_data = $this->db_query($query_data, false);
		$count_entry = $result_data;

		return $count_entry;
	}

	function sendmailatt($too, $title, $msg, $att)
	{

		//# First, instantiate the SDK with your API credentials
		$mailgun_key = $this->getitemlabel('parameter', 'parameter_name', 'mailgun_key', 'parameter_value');
		$nnpc_mail_sender = $this->getitemlabel('parameter', 'parameter_name', 'nnpc_mail_sender', 'parameter_value');
		$mg = Mailgun::create($mailgun_key);
		try {
			$mg->messages()->send('mg.nnpcgroup.com', [
				'from' => 'No-Reply <' . $nnpc_mail_sender . '>',
				'to' => '<' . $too . '>',
				'subject' => $title,
				'html' => $msg,
				'attachment' => [
					['filePath' => $att],
				],
			]);
			$resp = 0;
		} catch (Exception $e) {
			//$resp = $e->getMessage();
			$resp = -1;
		}
		return $resp;
	}


	///////// ********************************************************************************
	///////// ********************************************************************************
	///////// ************** START VALIDATION FUNCTION MR. UGO ******************** //////////
	///////// ********************************************************************************
	///////// ********************************************************************************

	function validate(array $request, array $rulesPair, array $fieldAlias = array())
	{
		foreach ($rulesPair as $key => $val) {
			$rules = explode('|', $val);
			foreach ($rules as $rule_name) {
				$fieldAlias[$key] = ($fieldAlias[$key] == '') ? $key : $fieldAlias[$key];
				$this->hasMetCondition($request[$key], $rule_name, $fieldAlias[$key]);
			}
		}
		return array('error' => $this->error, 'messages' => $this->messageBag);
	}
	function hasMetCondition($val, $rule_to_validate, $alias)
	{
		if (strpos($rule_to_validate, ':') == false) {
			if ($rule_to_validate == 'required') {
				$this->checkRequired($val, $alias);
			}
			if ($rule_to_validate == 'int') {
				if (!is_numeric($val)) {
					$this->error = true;
					$this->messageBag[] = $alias . ' field must be an integer';
				}
			}
			if ($rule_to_validate == 'email') {
				$email = filter_var($val, FILTER_SANITIZE_EMAIL);
				if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
					$this->error = true;
					$this->messageBag[] = $alias . ' field must be a valid email';
				}
			}
		} else {
			$this->numericComparism($val, $rule_to_validate, $alias);
		}
	}
	function numericComparism($val, $rule_to_validate, $alias)
	{
		$r_rule = explode(':', $rule_to_validate);
		if ($r_rule[0] == 'min' && strlen($val) < $r_rule[1]) {
			$this->error = true;
			$this->messageBag[] = $alias . ' field must have a minimum of ' . $r_rule[1] . ' character.';
			return $this->error;
		}
		if ($r_rule[0] == 'max' && strlen($val) > $r_rule[1]) {
			$this->error = true;
			$this->messageBag[] = $alias . ' field must have a maximum of ' . $r_rule[1] . ' character.';
			return $this->error;
		}
	}
	function checkRequired($value, $alias)
	{
		if ($value == "" || $value == null) {
			$this->error = true;
			$this->messageBag[] = $alias . ' field is required.';
			return $this->error;
		}
	}
	///////// ****************************************************************************
	///////// ****************************************************************************
	///////// ************** END VALIDATION FUNCTION ******************** /////////////////
	///////// ****************************************************************************
	///////// ****************************************************************************



	function sendMail_old($address, $title, $message)
	{

		$sender = "Nigeria Customs Service";
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		//		$headers .= "Reply-To:noreply@nnpc-erecruit.org.ng" . "\r\n";
		$headers .= 'From: Nigeria Customs Service <do-not-reply@customs.gov.ng> ' . "\r\n";
		//file_put_contents('gty.txt',$message." add:".$address." title: ".$title);
		mail($address, $title, $message, $headers);
	}

	function sendMail_ollld($address, $title, $message)
	{
		$dbobject = new dbobject();
		$mailgun_key = $dbobject->getitemlabel('parameter', 'parameter_name', 'mailgun_key', 'parameter_value');
		$nnpc_mail_sender = $dbobject->getitemlabel('parameter', 'parameter_name', 'nnpc_mail_sender', 'parameter_value');

		$mg = Mailgun::create($mailgun_key);
		$mg->messages()->send('mg-hr.customs.gov.ng', [
			'from'    => 'Nigeria Customs Service <' . $nnpc_mail_sender . '>',
			'to'      => $address,
			'subject' => $title,
			'text'    => $message,
			'html'    => $message
		]);
	}
}
//End Class
// $db = new dbobject();
// $db->confirm_session();

// $db = new dbobject();
//  $response = $db->validate(
//     array(
//         'name'=>'testme',
//         'pwd'=>'',
//         'email'=>''
//     ),
//     array(
//         'name'=>'required|min:15|int',
//         'pwd'=>'required|min:6',
//         'email'=>'required|email'
//     ),
//       array(
//             'name'=>'First Name',
//             'pwd'=>'password'
//       )
// );
// echo "<pre>";
// var_dump($response);
