<?php

@session_start();
///////////////////
//error_reporting(E_ERROR);
require_once 'dbcnx.inc.php';
require_once 'desencrypt.php';
require_once '3des.php';
require_once 'cryptojs-aes.php';
//////////////////////

define('SONMPASSWORDKEY', '123456');

// define("ONEPAY_MERCHANTID", "ACC-OPMHT000000164");
// define("MERCHANTID", "ACC-VMCHT0730");
// define("MERCHANTCODE", "700602X2KA");
//  //define("BASE_URL", "https://vuvaa.com/demo/collections");
//   define("BASE_URL", "https://vuvaa.com/generic_collections");
//  //define("BASE_URL", "192.168.10.201/sonm");
//  //define("BASE_URL", "192.168.10.201/vuvaaApi/generic_wallet_api.php");

define('MERCHANT_ID', '3674587680');
define('APIKEY', '218953');
define('SERVICETYPEID', '3251668876');
define('BASEDURL', 'https://login.remita.net');



//define('MERCHANT_ID', '3674587680');
//define('APIKEY', '218953');
//define('SERVICETYPEID', '12020626');
//define('BASEDURL', 'https://remitademo.net');



class dbobject
{
    public function begin()
    {
        @mysql_query('BEGIN');
    }

    public function commit()
    {
        @mysql_query('COMMIT');
    }

    public function rollback()
    {
        @mysql_query('ROLLBACK');
    }

    //////////////////////////////////Generic Script///////////////////////////////////////////////////

    public function getToken($user, $role)
    {
        // Create token header as a JSON string
        $header = json_encode(['typ' => 'JWT', 'alg' => 'PS384']);

        //Get the IP-address of the user
        $ip = $_SERVER['REMOTE_ADDR'];

        // Create token payload as a JSON string
        $timeStart = @date('YmdHis');
        $role = 'user';
        $payload = json_encode(['valid' => $timeStart, 'ip' => $ip, 'role' => $role]);

        // Encode Header to Base64Url String
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

        // Encode Payload to Base64Url String
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader.'.'.$base64UrlPayload, 'abC123!', true);

        // Encode Signature to Base64Url String
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Create JWT
        $jwt = $base64UrlHeader.'.'.$base64UrlPayload.'.'.$base64UrlSignature;

        echo $jwt;
    }

    public function SaveTransEdit($tbl, $inpFds, $inpFdsVals, $operation)
    {
        $whrcond = 0;
        $resp = 0;
        if ($operation == 'new') {
            $query = 'insert into '.$tbl.' set ';
            $where = '';
            for ($i = 0; $i < count($inpFds); ++$i) {
                $field = explode('-', $inpFds[$i]);
                if ($field[1] == 'fd') {
                    $query .= $field[0]."='".$inpFdsVals[$i]."', ";
                //$affected .= $field[0].", ";
                //$updatedVals .= $inpFdsVals[$i]."/";
                } elseif ($field[1] == 'whr' && $whrcond == 0) {
                    $where .= ', '.$field[0]."='".$inpFdsVals[$i]."'";
                    ++$whrcond;
                //$trail_appl = $inpFdsVals[$i];
                } elseif ($field[1] == 'whr' && $whrcond >= 1) {
                    $where .= ', '.$field[0]."='".$inpFdsVals[$i]."'";
                    ++$whrcond;
                }
            }
            $query = rtrim($query, ', ');
            $query_data = $query.$where;
            $query_data .= ';';
            $daty = @date('Y-m-d H:i:s');
            $officer = $_SESSION['sonm_'];
            $ip = $_SERVER['REMOTE_ADDR'];
            if (mysql_query($query_data) or die(mysql_error())) {
                ++$resp;
            } else {
                $resp = -1;
            }
            //if(!mysql_error())*/
            return $resp;
        } elseif ($operation == 'edit') {
            $query = 'update '.$tbl.' set ';
            $where = '';
            for ($i = 0; $i < count($inpFds); ++$i) {
                $field = explode('-', $inpFds[$i]);
                if ($field[1] == 'fd') {
                    $query .= $field[0]."='".$inpFdsVals[$i]."', ";
                } elseif ($field[1] == 'whr' && $whrcond == 0) {
                    $where .= ' where '.$field[0]."='".$inpFdsVals[$i]."'";
                    ++$whrcond;
                } elseif ($field[1] == 'whr' && $whrcond >= 1) {
                    $where .= ' and '.$field[0]."='".$inpFdsVals[$i]."'";
                    ++$whrcond;
                }
            }
            $query = rtrim($query, ', ');
            $query_data = $query.$where;
            $query_data .= ';';
            $daty = @date('Y-m-d H:i:s');
            $officer = $_SESSION['sonm_username'];
            $ip = $_SERVER['REMOTE_ADDR'];
            if (mysql_query($query_data)) {
                ++$resp;
            } else {
                $resp = -2;
            }

            return $resp;
        } else {
            echo 'something went wrong';
        }
    }

    ///////////////////////////////////////////////////////
    public function exister($table, $field1, $field2, $value1, $value2)
    {
        // counter function=>to return numbers of rows fetched or found
        function counter($resource)
        {
            return mysql_num_rows($resource);
        }
        //////////////////////////
        $existed = mysql_query("SELECT * FROM $table WHERE $field1='$value1' and $field2='$value2'") or die('Inavlid Exist Query'.mysql_error());
        $no = counter($existed);

        return $no;
    }

    ///////////////////////////////////////////////////////
    public function pinexist($table, $field1, $value1)
    {
        // counter function=>to return numbers of rows fetched or found
        function counter($resource)
        {
            return mysql_num_rows($resource);
        }
        //////////////////////////
        $existed = mysql_query("SELECT * FROM $table WHERE $field1='$value1' and $field2='$value2'") or die('Inavlid Exist Query'.mysql_error());
        $no = counter($existed);

        return $no;
    }

    public function getcheckdetails($user, $password)
    {
        $desencrypt = new DESEncryption();
        $key = $user; //"mantraa360";
        $cipher_password = $desencrypt->des($key, $password, 1, 0, null, null);
        $str_cipher_password = $desencrypt->stringToHex($cipher_password);
        $label = '';
        $table_filter = " where username='".$user."' and password='".$str_cipher_password."'";

        $query = 'select * from userdata '.$table_filter;
        // echo "NAme  ::::".$user." pass::::".$password;
        $result = mysql_query($query);
        $numrows = mysql_affected_rows();
        $dbobject = new dbobject();
        $no_of_pin_misses = $dbobject->getitemlabel('parameter', 'parameter_name', 'no_of_pin_misses', 'parameter_value');
        $pin_missed = $dbobject->getitemlabel('userdata', 'username', $user, 'pin_missed');
        $override_wh = $dbobject->getitemlabel('userdata', 'username', $user, 'override_wh');
        $extend_wh = $dbobject->getitemlabel('userdata', 'username', $user, 'extend_wh');

        if ($numrows > 0) {
            @$ddate = date('w');
            $row = mysql_fetch_array($result);

            @$dhrmin = date('Hi');
            $worktime = $dbobject->getitemlabel('parameter', 'parameter_name', 'working_hours', 'parameter_value');
            //echo $dhrmin;
            if ($override_wh == '1') {
                $worktime = $extend_wh;
            }
            $worktimesplit = explode('-', $worktime);
            $lowertime = str_replace(':', '', $worktimesplit[0]);
            $uppertime = str_replace(':', '', $worktimesplit[1]);

            $lowerstatus = ($lowertime < $dhrmin) == '' ? '0' : '1';
            $upperstatus = ($dhrmin < $uppertime) == '' ? '0' : '1';

            $pass_dateexpire = $row['pass_dateexpire'];
            @$expiration_date = strtotime($pass_dateexpire);
            $today = @date('Y-m-d');
            @$today_date = strtotime($today);

            if ($row['user_locked'] == '1') {
                $label = '3';
            } elseif ($row['day_1'] == '0' && $ddate == '0') {
                //You are not allowed to login on Sunday
                $label = '4';
            } elseif ($row['day_2'] == '0' && $ddate == '1') {
                //You are not allowed to login on Monday
                $label = '5';
            } elseif ($row['day_3'] == '0' && $ddate == '2') {
                //You are not allowed to login on Tuesday
                $label = '6';
            } elseif ($row['day_4'] == '0' && $ddate == '3') {
                //You are not allowed to login on Wednesday
                $label = '7';
            } elseif ($row['day_5'] == '0' && $ddate == '4') {
                //You are not allowed to login on Thursday
                $label = '8';
            } elseif ($row['day_6'] == '0' && $ddate == '5') {
                //You are not allowed to login on Friday
                $label = '9';
            } elseif ($row['day_7'] == '0' && $ddate == '6') {
                //You are not allowed to login on Saturday
                $label = '10';
            } elseif (!(($lowerstatus == 1) && ($upperstatus == 1))) {
                //You are not allowed to login due to working hours violation
                $label = '11';
            }
            /*else if($expiration_date <=$today_date){
                //$label = "13";
            }
            */
            elseif ($row['passchg_logon'] == '1') {
                $label = '14';
            } else {
                $label = '1';
                $_SESSION['sonm_username'] = $user;
                $_SESSION['sonm_role_id'] = $row['role_id'];
                $_SESSION['sonm_user_id'] = $row['user_id'];
                $_SESSION['sonm_role_name'] = $row['role_name'];
                $_SESSION['sonm_branch_code'] = $row['branch_code'];
                $_SESSION['sonm_firstname'] = $row['firstname'];
                $_SESSION['sonm_lastname'] = $row['lastname'];
                $_SESSION['sonm_statecode'] = $row['statecode'];
                $_SESSION['sonm_uid'] = $row['username'];
                $_SESSION['sonm_last_seen'] = $row['last_seen'];
                $_SESSION['img'] = $row['img_url'];
                $_SESSION['sonm_last_page_load'] = time();
                $oper = 'IN';
                $dbobject->resetpinmissed($user);
                $dbobject->resetLastseen($user);
            }
        } else {
            if ($no_of_pin_misses == $pin_missed) {
                $label = '12';
                $dbobject->updateuserlock($user, '1');
            } else {
                $label = '0';
                $dbobject->updatepinmissed($user);
            }
        }

        return $label;
    }

    public function LoadCart($username)
    {
        $result = "SELECT * FROM cart_tb WHERE (username='$username' OR  transaction_id='$_SESSION[trans_id]') AND item_status='00'";

        $result = mysql_query($result);
        $numrows = mysql_affected_rows();
        if ($numrows > 0) {
            while ($row = mysql_fetch_array($result)) {
                $json[] = $row;
                $_SESSION['sonm_Cart'] = $json;
                $_SESSION['sonm_product_id'] = $row['transaction_id'];
                $_SESSION['sonm_amount'] = $row['amount'];
            }
        }
        echo json_encode($json);
    }

    public function getCartItem($trans_id)
    {
        $trans_id = $trans_id;
        $_SESSION['trans_id'] = $trans_id;
        $result = "SELECT * FROM cart_tb WHERE transaction_id='$trans_id' AND item_status='00'";
        $result = mysql_query($result);
        $numrows = mysql_affected_rows();
        if ($numrows > 0) {
            while ($row = mysql_fetch_array($result)) {
                $json[] = $row;
                $_SESSION['sonm_Cart'] = $json;
                $_SESSION['sonm_product_id'] = $row['transaction_id'];
                $_SESSION['sonm_amount'] = $row['amount'];
            }
        }
        echo json_encode($json);
    }

    public function getAutoItem($catid, $search)
    {
        $catid = '01';
        $result = "SELECT * FROM sonm_item WHERE menu_id_cat='$catid' AND item_name LIKE '%$search%'";
        $resp = array();
        $result = mysql_query($result);
        $numrows = mysql_affected_rows();

        if ($numrows > 0) {
            while ($row = mysql_fetch_array($result)) {
                $rows = array();
                $rows['item_id'] = $row['item_id'];
                $rows['item_name'] = $row['item_name'];
                $rows['item_amount'] = $row['item_amount'];
                $rows['created'] = $row['created'];
                array_push($resp, $rows);
            }
        }

        return json_encode($resp);
    }

    public function Vuvaa_transCart($confirm_password, $username, $transaction_id, $transaction_desc, $transaction_amount, $customer_id, $customer_name, $channel)
    {
        $dbobject = new dbobject();
        $trans_type = 'AUTO-PAY';
        $des = new MCrypt();

            $content = '{
    			"merchant_id":"'.$des->encrypt(MERCHANTID).'"'.',
    			'.'"customer_id":"'.$des->encrypt($customer_id).'"'.',
    			"transaction_id":"'.$des->encrypt($transaction_id).'",
    			"transaction_desc":"'.$des->encrypt($transaction_desc).'"'.',
    			"password":"'.$des->encrypt($confirm_password).'"'.',
    			"username":"'.$des->encrypt($username).'"'.',
    			'.'"trans_type":"'.$des->encrypt($trans_type).'"'.',
    			'.'"merchant_code":"'.$des->encrypt(MERCHANTCODE).'"'.',
    			'.'"itemtype_code":"'.$des->encrypt(32882888).'"'.',
    			'.'"transaction_amount":"'.$des->encrypt($transaction_amount).'"'.',
    			'.'"customer_name":"'.$des->encrypt($customer_name).'"
    		}';

        $uurrll = BASE_URL.'/doTransaction';

        $curl = curl_init($uurrll);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        curl_setopt($curl, CURLOPT_POST, true);
        //curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $json_response = curl_exec($curl);
        $url_status = curl_getinfo($curl);

        curl_close($curl);
        $jsonData = json_decode($json_response, true);
        $status = $jsonData[status];
        $message = $jsonData[message];

        if ($status == 200) {
            $result = "UPDATE cart_tb SET item_status='11' WHERE (username='$_SESSION[sonm_username]' OR username='$username') AND item_status='00'";

            $result = mysql_query($result);
            $numrows = mysql_affected_rows();

            if ($numrows > 0) {
                $dbobject = new dbobject();
                //$dbobject->logaccess ("Transaction status :: " .$status." :: ".$message);
                unset($_SESSION['sonm_product_id']);
                $subject = 'Transaction on sonm';

                $transaction = $dbobject->getTransaction($transaction_id);

                $message = Template::get_contents('email_template/email_do_transaction.html', array('name' => $_SESSION[sonm_username], 'username' => $username, 'userpassword' => $userpassword, 'role_name' => $role_name));
                $emaail_resp = $dbobject->sendMail_global($_SESSION[sonm_username], $subject, $message);
                if ($emaail_resp) {
                    echo 'Email sent';
                } else {
                    echo 'Email not sent';
                }
            }

            return $jsonData;
        } else {
            return $jsonData;
        }
    }

    public function getTransaction($trans_id)
    {
        $fullname = 'Adeniyi James A.';
        $table = '
      		<table width="90%" align="center" cellpadding="3">
      		<tr>
      		<td>
      			<img src="images/mail/trans.jpg" width="100%" />
      		</td>
      		</tr> <tr>
      		<td>
      		Dear <b>'.$fullname.'</b>
      		</td>
      		</tr>
      		<tr>
      		<td><br/>
      		This is a summary of a transaction that has occurred on your account
      		</td>
      		</tr>
      		</table><br/>
      		';

        $query = "SELECT * FROM cart_tb WHERE  transaction_id='$trans_id' AND item_status='11'";
        $table .= '<table align="center" width="80%"><tr><th width="30px">No. </th>  <th>Item Name / Spare Parts </th> <td width="40px">Qty </th>  <th> Price</td>  <th>Total </th> </tr>';
        $result = mysql_query($query);
        $i = 0;
        $total_amount = 0.00;
        $total_quantity = 0;
        while ($row = mysql_fetch_array($result)) {
            ++$i;
            $trsansaction_id = $row['transaction_id'];
            $username = $row['username'];
            $user = $row['user'];
            $item_name = $row['item_name'];
            $quantity = $row['quantity'];
            $amount = $row['amount'];
            $total_amount = $total_amount + ($row['amount'] * $quantity);

            $tamount = $row['amount'] * $quantity;

            $total_quantity = $total_quantity + $quantity;

            $table .= '<tr><td>'.$i.' </td>  <td>'.$item_name.' </td> <td>'.$quantity.' </td>  <td> '.$amount.'</td>  <td>'.$tamount.' </td> </tr>';
        }
        $table .= '<tr><td>  </td>  <td>Total </td> <td>'.$total_quantity.' </td>  <td>  </td>  <td>'.$total_amount.' </td> </tr>';
        $table .= '</table>';

        $table .= '
    		<table width="90%" height="30" align="center" cellpadding="3">
    		<tr>
    		<td>
    			<img src="images/mail/trans_footer.jpg" width="100%" />
    		</td>
    		</table><br/>
    		';

            return $table;
    }

    public function onePayRequeryCart($transid)
    {
        $curl = curl_init();
        $data = 'MerchantRegID='.ONEPAY_MERCHANTID.'&MerchantTransID='.$transid;
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.onepay.com.ng/api/ValidateTrans/getTrans.php',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_POSTREDIR => 3,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'cURL Error #:'.$err;
        } else {
            return $response;
        }
    }

    public function RemoveCart($id, $username)
    {
        $result = "DELETE FROM cart_tb WHERE username='$username' AND item_status='00' AND item_id='$id'";

        $result = mysql_query($result);
        $numrows = mysql_affected_rows();
        echo json_encode($numrows);
    }

    public function getItemByid($id)
    {
        $resp['itemlist'] = array();
        $query = "select * from sonm_item where menu_id_cat='$id'";
        // echo ":: Query :: ". $query;
        $result = mysql_query($query);
        $numrows = mysql_affected_rows();
        //$data[]="";

        if ($numrows > 0) {
            $i = 1;
            while ($row = mysql_fetch_array($result)) {
                //$data = array();
                $data['item_id'] = $row['item_id'];
                $data['item_name'] = $row['item_name'];
                $data['item_amount'] = $row['item_amount'];
                array_push($resp['itemlist'], $data);
                ++$i;
            }

            return json_encode($resp);
        } else {
            $resp[status] = '412';
            $resp[message] = 'Fail, Group id does not exit';
            $resp1 = json_encode($resp);

            return $resp1;
        }
        //print "_ counter :: ".$ii;return $resp1;
    }

    public function getCartItemByTransId($id)
    {
        $query = "select * from cart_tb where transaction_id='$id' and item_status=00";
        // echo ":: Query :: ". $query;
        $result = mysql_query($query);
        $numrows = mysql_affected_rows();
        $data[] = '';
        if ($numrows > 0) {
            $i = 0;
            while ($row = mysql_fetch_array($result)) {
                $data[$i]->group_id = $row['group_id'];
                $data[$i]->item_id = $row['item_id'];
                $data[$i]->item_name = $row['item_name'];
                $data[$i]->username = $row['username'];
                $data[$i]->created = $row['created'];
                $data[$i]->item_amount = $row['amount'];
                ++$i;
            }
        } else {
        }
        //print "_ counter :: ".$ii;
        return $data;
    }

    public function getCartItemByUsername($id)
    {
        $query = "select * from cart_tb where username='$id' and item_status=00";
        $result = mysql_query($query);
        $numrows = mysql_affected_rows();
        $data = '';
        $data1['cartlist'] = array();
        if ($numrows > 0) {
            while ($row = mysql_fetch_array($result)) {
                $data[group_id] = $row['group_id'];
                $data[item_id] = $row['item_id'];
                $data[item_name] = $row['item_name'];
                $data[amount] = $row['amount'];
                $data[created] = $row['created'];
                $data[item_amount] = $row['item_amount'];
                $data[quantity] = $row['quantity'];
                $data[transaction_id] = $row['transaction_id'];
                array_push($data1['cartlist'], $data);
            }
        } else {
        }
        //print "_ counter :: ".$ii;
        return json_encode($data1);
    }

    public function getTransactionByusername($username)
    {
        $dbobject = new dbobject();
        $query = "SELECT trans_id,customer,item_amount,created,plate_no from transaction_tb where sold_by='$username' order By created DESC";
        //echo ":: Query :: ". $query;
        $result = mysql_query($query);

        $numrows = mysql_affected_rows();
        //$data[]="";
        $tid = array();
        if ($numrows > 0) {
            $i = 0;
            $tid = '';
            $data['Items'];
            $resp['itemlist'] = array();
            while ($row = mysql_fetch_array($result)) {
                if (!(in_array($row['trans_id'], $tid, true))) {
                    $tid[$i] = $row['trans_id'];
                    // 	$data[$i]->transId=$row["trans_id"];
                    // 	$data[$i]->customer=$row["customer"];
                    // 	$data[$i]->plateNo=$row["plate_no"];
                    $data['transId'] = $row['trans_id'];
                    $data['customer'] = $row['customer'];
                    $data['plateNo'] = $row['plate_no'];
                    $data['dateCreated'] = $row['created'];

                    array_push($resp['itemlist'], $data);
                }
            }
        } else {
        }
        //print "_ counter :: ".$ii;
        return $resp;
    }

    public function getTransactionBytransid($transid)
    {
        $dbobject = new dbobject();
        $query = "select * from transaction_tb where trans_id='$transid' order By created DESC";
        // echo ":: Query :: ". $query;
        $result = mysql_query($query);

        $numrows = mysql_affected_rows();
        $data1['trans'] = array();
        $data['Items'];
        if ($numrows > 0) {
            $i = 0;
            while ($row = mysql_fetch_array($result)) {
                $data1['sold_by'] = $row['sold_by'];
                $data1['transMethod'] = $row['trans_method'];
                $data1['customer'] = $row['customer'];
                $data1['phoneNo'] = $row['phone_no'];
                $data1['tarminalId'] = $row['tarminal_id'];
                $data1['plateNo'] = $row['plate_no'];
                $data1['dateCreated'] = $row['created'];
                $data1['pan'] = $row['pan'];
                $data['itemName'] = $dbobject->getitemlabel('sonm_item', 'item_id', $row['item_id'], 'item_name');
                $data['item_id'] = $row['item_id'];
                $data['item_amount'] = $row['item_amount'];
                array_push($data1['trans'], $data);
                ++$i;
                //echo $dbobject->getitemlabel('sonm_item','item_id',$row["item_id"],'item_name');
            }
        } else {
        }
        //print "_ counter :: ".$ii;
        return json_encode($data1);
    }

    public function getTransactionBytransid2($transid)
    {
        $dbobject = new dbobject();
        $query = "select * from transaction_tb where trans_id='$transid' order By created DESC";
        // echo ":: Query :: ". $query;
        $result = mysql_query($query);

        $numrows = mysql_affected_rows();
        $data1['trans'] = array();
        $data['Items'];
        if ($numrows > 0) {
            $i = 0;
            while ($row = mysql_fetch_array($result)) {
                $data1['sold_by'] = $row['sold_by'];
                $data1['transMethod'] = $row['trans_method'];
                $data1['customer'] = $row['customer'];
                $data1['phoneNo'] = $row['phone_no'];
                $data1['tarminalId'] = $row['tarminal_id'];
                $data1['plateNo'] = $row['plate_no'];
                $data1['dateCreated'] = $row['created'];
                $data1['pan'] = $row['pan'];
                $data['itemName'] = $dbobject->getitemlabel('sonm_item', 'item_id', $row['item_id'], 'item_name');
                $data['item_id'] = $row['item_id'];
                $data['item_amount'] = $row['item_amount'];
                array_push($data1['trans'], $data);
                ++$i;
                //echo $dbobject->getitemlabel('sonm_item','item_id',$row["item_id"],'item_name');
            }
        } else {
        }
        //print "_ counter :: ".$ii;
        return json_encode($data1);
    }

    public function getLastTransactionByUser($userid)
    {
        $dbobject = new dbobject();
        $query = "select * from transaction_tb where sold_by='$userid' order By created DESC LIMIT 7";
        //echo ":: Query :: ". $query;
        $result = mysql_query($query);

        $numrows = mysql_affected_rows();
        $data1[] = array();

        if ($numrows > 0) {
            $i = 0;
            while ($row = mysql_fetch_array($result)) {
                $data['Trans_id'] = $row['trans_id'];
                $data['Sold_by'] = $row['sold_by'];
                $data['TransMethod'] = $row['trans_method'];
                $data['Customer'] = $row['customer'];
                $data['phoneNo'] = $row['phone_no'];
                $data['TarminalId'] = $row['tarminal_id'];
                $data['PlateNo'] = $row['plate_no'];
                $data['DateCreated'] = $row['created'];
                $data['Pan'] = $row['pan'];
                $data['ItemName'] = $dbobject->getitemlabel('sonm_item', 'item_id', $row['item_id'], 'item_name');
                $data['Item_id'] = $row['item_id'];
                $data['Item_amount'] = $row['item_amount'];
                array_push($data1, $data);
                ++$i;
                //echo $dbobject->getitemlabel('sonm_item','item_id',$row["item_id"],'item_name');
            }
        } else {
        }
        //print "_ counter :: ".$ii;
        return json_encode($data1);
    }

    public function AddSpareToCart($email, $platenumber, $aaamount, $partname, $quantity)
    {
        $dbobject = new dbobject();
        if (isset($_SESSION['sonm_product_id'])) {
            //$_SESSION['sonm_product_id'] =$id;
        } else {
            $_SESSION['sonm_product_id'] = $dbobject->paddZeros($dbobject->getnextid('PRODUCT-ID'), 8);
        }

        $item_id = $dbobject->paddZeros($dbobject->getnextid('item_id'), 6);
        $item_name = $partname;
        $item_amount = $aaamount;
        //$platenumber
        //$quantity
        @$now = date('Y-m-d H:i:s');
        $str = "INSERT INTO cart_tb(username,item_id,item_name,amount,transaction_id,user,item_status,trans_type,created,quantity)VALUE('$email','$item_id','$item_name','$item_amount','$_SESSION[sonm_product_id]','$_SESSION[sonm_username]','00','Part','$now','$quantity')";
        $result = mysql_query($str);

        return $label;
    }

    public function AddToCart($group_id, $item_id, $item_name, $item_amount, $category, $username, $item_quantity, $transaction_id, $trans_type)
    {
        $dbobject = new dbobject();
        @$now = date('Y-m-d H:i:s');
        $str = "INSERT INTO cart_tb(group_id,username,item_id,item_name,amount,transaction_id,user,item_status,trans_type,created,quantity)VALUE('$group_id','$username','$item_id','$item_name','$item_amount','$transaction_id','$username','00','$category','$now','$item_quantity')";
        $result = mysql_query($str);
        $numrows = mysql_affected_rows();
        echo $numrows;
    }

    public function getpindetails($pin)
    {
        $query = 'select * from pintb where pin='.$pin;

        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_affected_rows();

        if ($numrows > 0) {
            $row = mysql_fetch_array($result);

            if ($row['status'] == '1') {
                $label = '2';
            } else {
                $label = '1';
                $_SESSION['sonm_pinName'] = $row['pinname'];
                $_SESSION['sonm_pinAmount'] = $row['pinamount'];
                $_SESSION['sonm_pinCarType'] = $row['pinCarType'];
                $_SESSION['sonm_pin'] = $row['pin'];
            }
        } else {
            $label = '0';
        }

        return $label;
    }

    public function getTrans_count($channel)
    {
        if ($_SESSION['sonm_role_id'] == '5111' or $_SESSION['sonm_role_id'] == '5121') {
            if ($channel == 'total') {
                $query = 'select SUM(item_amount)AS total from transaction_tb where 1=1';
            } else {
                $query = "select SUM(item_amount)AS total from transaction_tb where 1=1 AND cannel ='$channel' ";
            }
        } elseif ($_SESSION['sonm_role_id'] == '5131') {
            if ($channel == 'total') {
                $query = 'select SUM(item_amount)AS total from transaction_tb where 1=1';
            } else {
                $query = "select SUM(item_amount)AS total from transaction_tb where 1=1 AND cannel ='$channel' ";
            }
        } else {
            $query = "select SUM(item_amount)AS total from transaction_tb where 1=1  AND cannel ='$channel' AND sold_by='$_SESSION[user_id_se]' ";
        }
        // echo $query;
        $result = mysql_query($query);
        if ($result) {
            $row = mysql_fetch_array($result);
            $count = $row['total'];
        } else {
            $count = 'error';
        }

        return $count;
    }

    public function getNotify_count($table)
    {
        if ($_SESSION['sonm_role_id'] == '5011' or $_SESSION['sonm_role_id'] == '5021') {
            $query = "select count(id) counter from $table where 1=1 AND status='Unread'";
        } elseif ($_SESSION['sonm_role_id'] == '5031') {
            $query = "select count(id) counter from $table where 1=1 AND status='Unread'";
        } else {
            $query = "select count(id) counter from $table where 1=1 AND status='Unread'";
        }
        // echo $query;
        $result = mysql_query($query);
        if ($result) {
            $row = mysql_fetch_array($result);
            $count = $row['counter'];
        } else {
            $count = 'error';
        }

        return $count;
    }

    public function getemaildetails($email)
    {
        $query = "select * from userdata where email='".$email."'";

        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_affected_rows();

        if ($numrows > 0) {
            $row = mysql_fetch_array($result);

            if ($row['user_locked'] == '1') {
                $label = '2';
            } else {
                $label = '1';
                $_SESSION[emailname] = $row['firstname'] + ' ' + $row['lastname'];
            }
        } else {
            $label = '0';
        }

        return $label;
    }

    public function DoSaveTransaction($trans_id, $trans_method, $customer, $sold_by, $phone_no, $plate_no, $item_id, $item_amount, $item_name, $channel, $pan, $tarminal_id)
    {
        @$now = date('Y-m-d H:i:s');
        $query = "INSERT INTO  transaction_tb(trans_id,trans_method,customer,sold_by,phone_no,plate_no,item_id,item_amount,item_name,cannel,pan,tarminal_id,created)VALUE('$trans_id','$trans_method','$customer','$sold_by','$phone_no','$plate_no','$item_id','$item_amount','$item_name','$channel','$pan','$tarminal_id','$now')";
        $result = mysql_query($query);

        return $result;
    }

    public function DoTransaction($trans_id, $trans_method, $customer, $sold_by, $phone_no, $plate_no, $item_id, $item_amount, $item_name, $channel, $tarminal_id)
    {
        @$now = date('Y-m-d H:i:s');
        $query = "INSERT INTO  transaction_tb(trans_id,trans_method,customer,sold_by,phone_no,plate_no,item_id,item_amount,item_name,cannel,pan,tarminal_id,created)VALUE('$trans_id','$trans_method','$customer','$sold_by','$phone_no','$plate_no','$item_id','$item_amount','$item_name','$channel','$pan','$tarminal_id','$now')";
        // echo $query;
        $result = mysql_query($query);

        return $result;
    }

    public function doSaveTb($query_)
    {
        $result_data = mysql_query($query_) or die(mysql_error());
        $count_entry = mysql_affected_rows();

        return $count_entry;
    }

    ///// NEW ADDITIONS

    public function logaccess($message)
    {
        $time = @date('Y-m-d H:i:s');
        $filename = date('Y-M-d');
        $my_file = 'logs/'.$filename.'.txt';
        $success = $time.' by '.$_SESSION[sonm_username].' --- using  '.$_SERVER['REMOTE_ADDR'].' -- '.$message."\r\n";
        $handle = fopen($my_file, 'a+') or die('Cannot open file:  '.$my_file); //implicitly creates file
        fwrite($handle, $success);
        fclose($handle);
    }

    public function getuserip_status($user)
    {
        $date = date('Y-m-d');
        $qr1 = " SELECT AUDIT_IP,AUDIT_USER FROM audit_trail_account WHERE AUDIT_USER = '".$user."' AND
			SUBSTR(AUDIT_T_IN, 1, 10)= '$date'  AND AUDIT_T_OUT IS NULL  ";
        //echo $qr1;
        $mq1 = mysql_query($qr1);
        $mn1 = mysql_num_rows($mq1);
        $label = 0;
        if ($mn1 > 0) {
            $label = $mn1;
        }

        return $label;
    }

    public function doAuditTrai_logout($operation, $user)
    {
        $date = date('Y-m-d');
        $client_ip = $_SERVER['REMOTE_ADDR'];
        $query = "UPDATE  audit_trail_account SET AUDIT_T_OUT=now() WHERE AUDIT_USER='$user'
			AND SUBSTR(AUDIT_T_IN, 1, 10) = '$date' ";
        $result = mysql_query($query);
        $count_entry = $query;

        return $count_entry;
    }

    public function doAuditTrail($operation)
    {
        //$count_entry = 0;
        $user = $_SESSION[username_se];
        $client_ip = $_SERVER['REMOTE_ADDR'];

        if ($operation == 'IN') {
            @$now = date('Y-m-d H:i:s');
            $_SESSION['sonm_IN'] = $now;
            $query = " INSERT INTO  audit_trail_account (AUDIT_USER,AUDIT_T_IN,AUDIT_IP)
					VALUES('$user','$now','$client_ip')";
            //echo $query;
            $result = mysql_query($query);
        //$count_entry = mysql_num_rows($result);
        } elseif ($operation == '0UT') {
            //echo "innow";
            $now = $_SESSION['sonm_IN'];
            $query = "UPDATE  audit_trail_account SET AUDIT_T_OUT=now() WHERE AUDIT_USER='$user'
						AND AUDIT_T_IN='$now'";
            //echo $query;
            //$unset = unset($_SESSION['sonm_IN']);
            $result = mysql_query($query);
            $count_entry = $query;
        }

        return $count_entry;
    }

    //Our custom function.
    public function generatePIN($digits, $type)
    {
        $pin = ''; //our default pin is blank.
        if ($type == 0) {
            $i = 0; //counter

            while ($i < $digits) {
                //generate a random number between 0 and 9.
                $pin .= mt_rand(0, 9);
                ++$i;
            }
        } else {
            $randomBytes = openssl_random_pseudo_bytes($digits);
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            $charactersLength = strlen($characters);
            for ($i = 0; $i < $digits; ++$i) {
                $pin .= $characters[ord($randomBytes[$i]) % $charactersLength];
            }
        }

        return $pin;
    }

    //Our custom function.
    public function generatePIN_text($length = 4)
    {
        $randomBytes = openssl_random_pseudo_bytes($length);
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $result = '';
        for ($i = 0; $i < $length; ++$i) {
            $result .= $characters[ord($randomBytes[$i]) % $charactersLength];
        }
        echo $result;
    }

    public function getlastlogin($user)
    {
        $date = date('Y-m-d');
        //check to see if the user has logged in to this system or another system
        $qr = " SELECT AUDIT_IP, AUDIT_T_IN FROM audit_trail_account WHERE AUDIT_USER='".$user."' AND
							SUBSTR(AUDIT_T_IN, 1, 10)= '$date'  ORDER BY AUDIT_T_IN DESC LIMIT 1  ";
        //echo $qr;
        $mq = mysql_query($qr);
        $mn = mysql_num_rows($mq);
        if ($mn > 0) {
            $rr = mysql_fetch_array($mq);
            $the_ip = $rr['AUDIT_IP'];
            $last_time_in = $rr['AUDIT_T_IN'];
        }

        return $last_time_in;
    }

    public function reset_ip($user)
    {
        //check to see if the user has logged in to this system or another system
        $date = date('Y-m-d');
        $qr = " SELECT AUDIT_IP FROM audit_trail_account WHERE AUDIT_USER='".$user."' AND
							SUBSTR(AUDIT_T_IN, 1, 10)= '$date'  AND AUDIT_T_OUT IS NULL ";
        //echo $qr;
        $mq = mysql_query($qr);
        $mn = mysql_num_rows($mq);
        if ($mn > 0) {
            $rr = mysql_fetch_array($mq);
            $the_ip = $rr['AUDIT_IP'];
            $sys_ip = $_SERVER['REMOTE_ADDR'];
            $operation = '0UT';
            $dbobject = new dbobject();
            $audit = $dbobject->doAuditTrai_logout($operation, $user);
        }
    }

    public function getitemlabel2($tablename, $table_col, $table_val, $table_col2, $table_val2, $ret_val)
    {
        //echo 'country code : '.$countrycode;
        $label = '';
        $table_filter = ' where '.$table_col."='".$table_val."' and ".$table_col2."='".$table_val2."'";

        $query = 'select '.$ret_val.' from '.$tablename.$table_filter;
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            $row = mysql_fetch_array($result);
            $label = $row[$ret_val];
        }

        return $label;
    }

    public function reset_login_status($user)
    {
        $date = date('Y-m-d');
        $now = $_SESSION['sonm_IN'];
        $qr1 = " SELECT AUDIT_IP,AUDIT_USER FROM audit_trail_account WHERE AUDIT_USER = '".$user."' AND
			AUDIT_T_IN= '$now'  AND AUDIT_T_OUT IS NULL AND AUDIT_IP = '".$_SERVER['REMOTE_ADDR']."' ";
        $mq1 = mysql_query($qr1);
        $mn1 = mysql_num_rows($mq1);
        $label = 0;
        if ($mn1 > 0) {
            $label = $mn1;
        }

        return $label;
    }

    // END NEW ADDITIONS

    public function getUniqId($uname)
    {
        $month_year = array('01' => '025',
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
                            '12' => '890', );

        $year = array('2009' => '111',
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
                    '2035' => '248', );

        $day = array('01' => '50',
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
                    '31' => '45', );
        //////////////--------> get 2day's date
        $uname = substr($uname, 0, 4);
        $today_date = @date('Y-m-d');
        $date_arr = explode('-', $today_date);
        $unique_id = mb_strtoupper($uname, 'UTF-8').$year[$date_arr[0]].$month_year[$date_arr[1]].$day[$date_arr[2]];

        return $unique_id;
    }

    public function accesslog($accessflag, $user)
    {
        $dbobject = new dbobject();
        $logid = $dbobject->paddZeros($dbobject->getnextid('ACCSSLOG'), 4);
        $ipaddr = $_SERVER['REMOTE_ADDR'];
        $querylog = "INSERT INTO access_log (logid,accessflag,created,posted_by,posted_ip) VALUES ('$logid','$accessflag',now(),'$user','$ipaddr')";
        @mysql_query($querylog); //or die(mysql_error());
    }

    public function updatepinmissed($username)
    {
        $query = "update userdata set pin_missed=pin_missed+1 where username= '$username'";
        //echo $query;
        $resultid = mysql_query($query);
        $numrows = mysql_affected_rows();
    }

    public function resetpinmissed($username)
    {
        $query = "update userdata set pin_missed=0 where username='$username'";
        //echo $query;
        $resultid = mysql_query($query);
        $numrows = mysql_affected_rows();
    }

    public function resetLastseen($username)
    {
        $modify = date('Y-m-d h:i:sa');
        $query = "update userdata set last_seen='$modify' where username= '$username'";
        //echo $query;
        $resultid = mysql_query($query);
        $numrows = mysql_affected_rows();
    }

    public function updateuserlock($username, $value)
    {
        $query = "update userdata set user_locked='$value' where username= '$username'";
        //echo $query;
        $resultid = mysql_query($query);
        $numrows = mysql_affected_rows();
    }

    //// select a field from a table
    public function getitemlabel($tablename, $table_col, $table_val, $ret_val)
    {
        $label = '';
        $table_filter = ' where '.$table_col."='".$table_val."'";

        $query = 'select '.$ret_val.' from '.$tablename.$table_filter;
        //echo $query;

        $result = mysql_query($query);
        $numrows = mysql_affected_rows();
        if ($numrows > 0) {
            $row = mysql_fetch_array($result);
            $label = $row[$ret_val];
        }

        return $label;
    }

    public function getitemlabelmenu($tablename, $table_col, $table_val, $ret_val)
    {
        $label = '';
        $table_filter = ' where '.$table_col."='".$table_val."'";

        $query = 'select '.$ret_val.' from '.$tablename.$table_filter;
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_affected_rows();
        if ($numrows > 0) {
            while ($row = mysql_fetch_array($result)) {
                $label .= "'".$row[$ret_val]."',";
            }
            $label = rtrim($label, ',');
        }

        return $label;
    }

    ///////////////
    public function loadParameters()
    {
        $label = '';
        $query = 'select * from parameter';
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        for ($i = 0; $i < $numrows; ++$i) {
            $row = mysql_fetch_array($result);
            $label = $label.'"'.$row['parameter_name'].'"=>"'.$row['parameter_value'].'", ';
            $_SESSION[$row['parameter_name']] = $row['parameter_value'];
        }

        return $label;
    }

    //////////
    public function getrecordset($tablename, $table_col, $table_val)
    {
        $label = '';
        $table_filter = ' where '.$table_col."='".$table_val."'";

        $query = 'select * from '.$tablename.$table_filter;

        $result = mysql_query($query);

        return $result;
    }

    //////////
    public function getRole()
    {
        $user_role_session = $_SESSION['sonm_role_id'];

        $query = "select * from role where role_id='".$user_role_session."'";

        $result = mysql_query($query);

        if ($result) {
            $row = mysql_fetch_array($result);
            $rolename1 = $row['role_name'];
        }

        return $rolename1;
    }

    public function getStatus()
    {
        $sonm_username = $_SESSION['sonm_username'];

        $query = "select * from userdata where username ='".$sonm_username."'";

        $result = mysql_query($query);

        if ($result) {
            $row = mysql_fetch_array($result);
            $user_status = $row['user_status'];
        }

        return $user_status;
    }

    ////////////
    public function getJob()
    {
        $result = 'select * from job_card_tb';
        while ($row = mysql_fetch_array($result)) {
            $datad[] = $row['labour_costs_total'];
        }
    }

    ////////////
    public function doGet_info_by_TransId($trans_id)
    {
        $trans_id = $trans_id;
        $_SESSION['trans_id'] = $trans_id;
        $result = "SELECT * FROM cart_tb WHERE transaction_id='$trans_id' AND item_status='11'";
        $result = mysql_query($result);
        $numrows = mysql_affected_rows();
        if ($numrows > 0) {
            while ($row = mysql_fetch_array($result)) {
                $json[] = $row;
                $_SESSION['sonm_Cart'] = $json;
                $_SESSION['sonm_product_id'] = $row['transaction_id'];
                $_SESSION['sonm_amount'] = $row['amount'];
            }
        }

        return json_encode($json);
        // $query = "select * from cart_tb where transaction_id='$data' and item_status=11";

            // $result = mysql_query($query);
            // while ($row = mysql_fetch_array($result)) {
            // 	$datad[] = $row;
            // }
            // return json_encode($datad);
    }

    ////////// to get record from any table  /////////////////
    public function getrecord($tablename, $table_col, $table_val)
    {
        if ($table_col > 0) {
            $label = '';
            $table_filter = ' where '.$table_col."='".$table_val."'";

            $query = 'select * from '.$tablename.$table_filter;
            //echo $query;
            $result = mysql_query($query);
        //$numrows = mysql_num_rows($result);
            /*
            if($numrows > 0){
                $row = mysql_fetch_array($result);
                $label = $row[$ret_val];
            }
            */
        } else {
            $query = 'select * from '.$tablename;

            $result = mysql_query($query);
        }

        return $result;
    }

    /////////////////
    public function getrecordsetdata($query)
    {
        $query = $query;
        //echo $query;
        $result = mysql_query($query);

        return $result;
    }

    //////////////////
    public function getparentmenu($opt)
    {
        $filter = '';
        $options = "<option value='#'>::: None ::: </option>";
        /*
        if($opt!= ""){
        $filter = "where menu_id='".$opt."' and parent_id='#' "; //" username='$username' and password='$password' ";
        }else{
        */
        $filter = "where parent_id='#' or parent_id2='#'  order by menu_order";
        //}
        $query = 'select distinct menu_id, menu_name from menu  '.$filter;
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                //echo $row['country_code'];
                if ($opt == $row['menu_id']) {
                    $filter = 'selected';
                }
                //echo ($opt=='$row["country_code"]'?'selected':'None');
                $options = $options."<option value='$row[menu_id]' $filter >$row[menu_name]</option>";
                $filter = '';
            }
        }

        return $options;
    }

    public function getsubmenu($opt)
    {
        $filter = '';
        $options = '';
        if ($opt != '') {
            $filter = "where parent_id='$opt' order by menu_order"; //" username='$username' and password='$password' ";
        }
        $query = 'select distinct menu_id, menu_name from menu  '.$filter;
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                $options = $options."<option value='$row[menu_id]' $filter >$row[menu_name]</option>";
                $filter = '';
            }
        }

        return $options;
    }

    ////////////////////////////////////
    public function reorder_submenu($parent_menu, $sub_menu)
    {
        $num_count = 0;
        $sub_menu_arr = explode(',', $sub_menu);
        for ($i = 0; $i < sizeof($sub_menu_arr); ++$i) {
            $query = "update menu set menu_order=$i where menu_id= '$sub_menu_arr[$i]'";
            //echo $query;
            $result = mysql_query($query);
            $num_count += mysql_affected_rows();
        }

        return $num_count;
    }

    //////////////////
    public function getLots()
    {
        $filter = '';
        $options; //= "<option value='#'>::: None ::: </option>";
        /*
        if($opt!= ""){
        $filter = "where menu_id='".$opt."' and parent_id='#' "; //" username='$username' and password='$password' ";
        }else{
        */
        $filter = "where parent_id='#' or parent_id2='#'  order by menu_order";
        //}
            $query = 'select distinct category, sector_id from sectors  order by category'; //.$filter;
            //echo $query;
            $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                //echo $row['country_code'];
                if ($opt == $row['menu_id']) {
                    $filter = 'selected';
                }
                //echo ($opt=='$row["country_code"]'?'selected':'None');
                $options = $options."<option value='$row[sector_id]'>$row[category]</option>";
                $filter = '';
            }
        }

        return $options;
    }

    ///////////////////////////////////
    public function validatepassword($user, $password)
    {
        //echo 'country code : '.$countrycode;
        $desencrypt = new DESEncryption();
        $key = $user; //"mantraa360";
        $cipher_password = $desencrypt->des($key, $password, 1, 0, null, null);
        $str_cipher_password = $desencrypt->stringToHex($cipher_password);

        $label = '';
        $table_filter = " where username='".$user."' and password='".$str_cipher_password."'";

        $query = 'select * from userdata'.$table_filter;
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            $label = '1';
        } else {
            $label = '-1';
        }

        return $label;
    }

    // Change to user profile password
    public function doPasswordChange($username, $user_password, $oldpword)
    {
        $desencrypt = new DESEncryption();
        $key = $username;
        $cipher_password = $desencrypt->des($key, $user_password, 1, 0, null, null);
        $str_cipher_password = $desencrypt->stringToHex($cipher_password);
        $query_data = "update userdata set password='$str_cipher_password' where username= '$username'";
        //echo $query_data;
        $result_data = mysql_query($query_data);
        $count_entry = mysql_affected_rows();
        if ($_SESSION['sonm_role_id'] == '5131') {
            $des = new MCrypt();
            $content = '{
							"merchant_id":"'.$des->encrypt(MERCHANTID).'"'.',
							'.'"username":"'.$des->encrypt($username).'"'.',
							"old_password":"'.$des->encrypt($oldpword).'"'.',
							"new_password":"'.$des->encrypt($user_password).'"
						}';
            $uurrll = BASE_URL.'/changePassword';
            $curl = curl_init($uurrll);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
            curl_setopt($curl, CURLOPT_POST, true);
            //curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $json_response = curl_exec($curl);
            $url_status = curl_getinfo($curl);

            curl_close($curl);
            $jsonData = json_decode($json_response, true);

            $status = $jsonData[status];
            $message = $jsonData[message];
            $dbobject = new dbobject();
            $dbobject->logaccess('change password : '.$status.' : '.$message);
        }

        return $count_entry;
    }

    public function pick_role($opt)
    {
        $filter = '';
        $options = "<option value=''>::: Select a Role ::: </option>";
        /*
        if($opt!= ""){
        $filter = "where role_id='".$opt."'"; //" username='$username' and password='$password' ";
        }
        */
        $dbobject = new dbobject();
        $user_role_session = $_SESSION['sonm_role_id'];
        //$filter_role_id = $dbobject->getitemlabel('parameter','parameter_name','admin_code','parameter_value');
        //$filteradmin = ($user_role_session == $filter_role_id)?"":" and role_id not in ('".$filter_role_id."')";
        if ($_SESSION['sonm_role_id'] == '5011') {
            $query = "select distinct role_id, role_name from role where  1=1 and role_id >= '$user_role_session'  and role_id not in(select parameter_value from parameter where parameter_name='$user_role_session' )"; //.$filteradmin;
        } else {
            $query = "select distinct role_id, role_name from role where  1=1 and role_id > '$user_role_session'  and role_id not in(select parameter_value from parameter where parameter_name='$user_role_session' )"; //.$filteradmin;
        }

        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                //echo $row['country_code'];
                if ($opt == $row['role_id']) {
                    $filter = 'selected';
                }
                //echo ($opt=='$row["country_code"]'?'selected':'None');
                $options = $options."<option value='$row[role_id]' $filter >$row[role_name]</option>";
                $filter = '';
            }
        }

        return $options;
    }

    public function pick_state($opt)
    {
        $filter = '';
        $options = "<option value=''>::: Select a State ::: </option>";
        /*
        if($opt!= ""){
        $filter = "where role_id='".$opt."'"; //" username='$username' and password='$password' ";
        }
        */
        $dbobject = new dbobject();
        $user_role_session = $_SESSION['sonm_role_id'];

        $query = 'select distinct statecode, state from states where 1=1';
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                //echo $row['country_code'];
                if ($opt == $row['statecode']) {
                    $filter = 'selected';
                }
                //echo ($opt=='$row["country_code"]'?'selected':'None');
                $options = $options."<option value='$row[statecode]' $filter >$row[state]</option>";
                $filter = '';
            }
        }

        return $options;
    }

    public function pick_type($category, $sector)
    {
        $filter = '';
        $options = "<option value=''>::: Select a Type ::: </option>";

        $dbobject = new dbobject();
        $user_role_session = $_SESSION['sonm_role_id'];

        $query = "select distinct levy_class_name, levy_class_id from levy_classifications where 1=1 AND sector_id='$category'";

        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                // echo "sector".$sector;
                if ($sector == $row['levy_class_name']) {
                    $filter = 'selected';
                }
                //echo ($opt=='$row["country_code"]'?'selected':'None');
                $val = $row['levy_class_id'].','.$row['levy_class_name'];
                $options = $options."<option value='$val' $filter>$row[levy_class_name]</option>";
                $filter = '';
            }
        }

        return $options;
    }

    public function pick_Category($opt)
    {
        $filter = '';
        $options = "<option value=''>::: Select a Category ::: </option>";
        /*
        if($opt!= ""){
        $filter = "where role_id='".$opt."'"; //" username='$username' and password='$password' ";
        }
        */
        $dbobject = new dbobject();
        $user_role_session = $_SESSION['sonm_role_id'];

        $query = 'select * from category_tb where 1=1';
        //  echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                //echo $row['country_code'];
                if ($opt == $row['cat_Id']) {
                    $filter = 'selected';
                }
                //echo ($opt=='$row["country_code"]'?'selected':'None');
                $options = $options."<option value='$row[cat_Id]' $filter >$row[Category_name]</option>";
                $filter = '';
            }
        }

        return $options;
    }

    public function pick_vehicleType($opt)
    {
        $filter = '';
        $options = "<option value=''>::: Select a Role ::: </option>";
        /*
        if($opt!= ""){
        $filter = "where role_id='".$opt."'"; //" username='$username' and password='$password' ";
        }
        */
        $dbobject = new dbobject();
        $user_role_session = $_SESSION['sonm_role_id'];

        $query = 'select distinct statecode, state from states where 1=1';
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                //echo $row['country_code'];
                if ($opt == $row['statecode']) {
                    $filter = 'selected';
                }
                //echo ($opt=='$row["country_code"]'?'selected':'None');
                $options = $options."<option value='$row[statecode]' $filter >$row[state]</option>";
                $filter = '';
            }
        }

        return $options;
    }

    ////////////////////////
    public function doRole($role_id, $role_name, $enable_role)
    {
        $count_entry = 0;
        $query = "select * from role  where role_id='$role_id'";
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update role set role_name='$role_name', role_enabled='$enable_role' where role_id='$role_id' ";
            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();
        } else {
            $sql = "select * from role  where role_name='$role_name'";
            if ($res = mysql_query($sql)) {
                if (mysql_num_rows($res) >= 1) {
                    $count_entry = -9;
                } else {
                    $query_data = "insert into role (role_id,role_name,role_enabled,created) values( '$role_id','$role_name','$enable_role',now())";
                    //echo $query_data;
                    $result_data = mysql_query($query_data);
                    $count_entry = mysql_affected_rows();
                }
            }
        }

        return $count_entry;
    }

    // ////////////////////////
    // function doContact($fname,$email,$message){
    // 		$count_entry = 0;
    // 		//$created =date('d-m-y');
    // 		$query_data = "insert into contacttb(fname,email,message,datesent) values( '$fname','$email','$message',now())";
    // 		//echo $query_data;
    // 		$result_data = mysql_query($query_data);
    // 		$count_entry = mysql_affected_rows();

    // 		return $count_entry;
    // 	}

    public function doUser($operation, $uid, $username, $userpassword, $firstname, $lastname, $email, $phone, $chgpword_logon, $user_locked, $user_disable, $day_1, $day_2, $day_3, $day_4, $day_5, $day_6, $day_7, $override_wh, $extend_wh, $role_id, $role_name, $Userstate)
    {
        $dbobject = new dbobject();

        // if($this->EmailValidation($email))
        // {
        //  return true;
        // }
        // else{

        // 	echo "Invalide email Address";
        // 	return false;
        // }

        $desencrypt = new DESEncryption();
        $count_entry = 0;
        $key = $username;
        $cipher_password = $desencrypt->des($key, $userpassword, 1, 0, null, null);
        $str_cipher_password = $desencrypt->stringToHex($cipher_password);

        $query = "select * from userdata where username='$username'";
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        //$operation = $_SESSION['sonm_save_user_operation'];
        //echo $operation.":::".$numrows.":::";
        if ($numrows >= 1 && $operation == 'new') {
            $count_entry = -9;
        }
        $dstta = date('Y-m-d');

        if ($numrows >= 1 && $operation != 'new') {
            ///////////////////////////
            $addquery = $user_locked == '0' ? ',pin_missed=0' : '';
            $query_data = "update userdata set password='$str_cipher_password', role_id='$role_id', firstname='$firstname', lastname='$lastname', email='$email', mobile_phone='$phone', passchg_logon='$chgpword_logon', user_disabled='$user_disable', user_locked='$user_locked', day_1='$day_1', day_2='$day_2', day_3='$day_3', day_4='$day_4', day_5='$day_5', day_6='$day_6', day_7='$day_7', modified=now(), override_wh='$override_wh', extend_wh='$extend_wh',posted_user='$posted_user',last_used_passwords='$LastUsedPassword' where username='$username'";
            //echo $query_data;
            $result_data = mysql_query($query_data) or die(mysql_error());
            //echo mysql_error();
            $count_entry = mysql_affected_rows();
            echo $username.' ::: '.$email;
            $des = new MCrypt();
            $content = '{
							"merchant_id":"'.$des->encrypt(MERCHANTID).'"'.',
							'.'"username":"'.$des->encrypt($email).'"'.',
							"password":"'.$des->encrypt($userpassword).'",
							"password_confirm":"'.$des->encrypt($userpassword).'"'.',
							"phone_no":"'.$des->encrypt($phone).'"'.',
							'.'"email_address":"'.$des->encrypt($email).'"'.',
							'.'"first_name":"'.$des->encrypt($firstname).'"'.',
							'.'"last_name":"'.$des->encrypt($lastname).'"
						}';

            $uurrll = BASE_URL.'/createUser';
            $curl = curl_init($uurrll);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
            curl_setopt($curl, CURLOPT_POST, true);
            //curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $json_response = curl_exec($curl);
            $url_status = curl_getinfo($curl);

            curl_close($curl);
            $jsonData = json_decode($json_response, true);
            $status = $jsonData[status];
            $message = $jsonData[message];
        }
        if ($numrows == 0 && $operation == 'new') {
            $pin = $dbobject->generatePIN(6, 0);

            $pass_dateexpire = date('Y-m-d', strtotime('+60 days'));
            $vech_statecode = $_SESSION['sonm_statecode']; //$_SESSION['sonm_vech_statecode'];
            $query_data = "insert into userdata(user_id,username,password,role_id, firstname, lastname, email, mobile_phone, passchg_logon, user_disabled, user_locked,day_1,day_2,day_3,day_4,day_5,day_6,day_7,created, modified,override_wh,extend_wh,pass_dateexpire,statecode,vcode) values( '$uid', '$username','$str_cipher_password','$role_id', '$firstname','$lastname','$email','$phone','$chgpword_logon','$user_disable','$user_locked','$day_1', '$day_2', '$day_3', '$day_4', '$day_5', '$day_6', '$day_7' , now(), now(), '$override_wh', '$extend_wh', '$pass_dateexpire','$vech_statecode','$pin')";
            $result_data = mysql_query($query_data); //or die(mysql_error());
            $count_entry = mysql_affected_rows();
            ///////////
            if ($count_entry > 0) {
                //echo "Reg :: ". $count_entry ." ::";

                $subject = 'New Registration on sonm';
                $email_subject_image = 'email_reg';

                //$message = Template::get_contents("email_template/email_user.html", array('name' => $firstname, 'username' => $username, 'userpassword' => $userpassword, 'vcode' => $pin));
                $message = $dbobject->reg_email_template($firstname, $username, $userpassword, $pin,$path);

                $emaail_resp = $dbobject->sendMail_global($email, $subject, $message);
                if ($emaail_resp) {
                    // echo "Email sent ";
                } else {
                    echo 'Email not sent ';
                }
            }
        }

        return $count_entry;
    }

    public function reg_email_template($name, $email, $password, $link)
    {
        // Get email template as string

            $dbobject = new dbobject();
		          $path = $this->getitemlabel('parameter','parameter_name','root_link','parameter_value');
        $email_template_string = file_get_contents('email_template/verification_temp.html', true);
        // Fill email template with message and relevant banner image
        $email_template = str_replace(
                array('%name%', '%email%', '%password%', '%link%','%path%'),
                array($name, $email, $password, $link,$path), $email_template_string
            );

        return $email_template;
    }

    public function password_recover_email_template($name, $email, $password, $link)
    {
        // Get email template as string

            $dbobject = new dbobject();
                  $path = $this->getitemlabel('parameter','parameter_name','root_link','parameter_value');
        $email_template_string = file_get_contents('email_template/reset_password.html', true);
        // Fill email template with message and relevant banner image
        $email_template = str_replace(
                array('%name%', '%email%', '%password%', '%link%','%path%'),
                array($name, $email, $password, $link,$path), $email_template_string
            );

        return $email_template;
    }

    public function send_mail_online($mail_address, $mail_subject, $mail_msg)
    {
        // Create map with request parameters
        $params = array('mail_subject' => "$mail_subject", 'mail_address' => "$mail_address", 'mail_msg' => "$mail_msg");

        // Build Http query using params
        $query = http_build_query($params);

        // Create Http context details
        $contextData = array(
                            'method' => 'POST',
                            'header' => "Connection: close\r\n".
                                        'Content-Length: '.strlen($query)."\r\n",
                            'content' => $query, );

        // Create context resource for our request
        $context = stream_context_create(array('http' => $contextData));

        // Read page rendered as result of your POST request
        $result = file_get_contents(
                            'http://accessng.com/remote_mailer.php',  // page url
                            false,
                            $context);

        return $result;
    }

    public function payment_email_template($firstname, $username, $userpassword)
    {
        // Get email template as string
        		$path = $dbobject->getitemlabel('parameter','parameter_name','root_link','parameter_value');
        $email_template_string = file_get_contents('email_template/email_user.html', true);
        $email_template = str_replace(
                array('%name%', '%username%', '%userpassword%', '%pin%','%$path%'),
                array($firstname, $username, $userpassword, $pin,$path), $email_template_string
            );

        return $email_template;
    }

    public function build_email_template($email_subject_image, $message)
    {
        // Get email template as string
        $email_template_string = file_get_contents('email_template/email_user.html', true);
        // Fill email template with message and relevant banner image
        $email_template = sprintf($email_template_string, 'images/'.$email_subject_image.'.jpg', $message, $mobile_plugin_string);

        return $email_template;
    }

    public function send_mail_template($to, $from, $subject, $message)
    {
        $headers = 'MIME-Version: 1.0'."\r\n";
        $headers .= 'Content-type:text/html;charset=UTF-8'."\r\n";
        $headers .= 'From: <'.$from.">\r\n";
        //$response = mail($to, $subject, $message, $headers);
    }


    function sendMail_new($address,$title,$message)
    {
          $dbobject = new dbobject();
          $sender = "School of nursing and midwifery Lafia";
          $headers = "MIME-Version: 1.0" . "\r\n";
          $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
          //		$headers .= "Reply-To:noreply@nnpc-erecruit.org.ng" . "\r\n";
          $headers .= 'From: SONM <info@sonm.org.ng> ' . "\r\n";
          $headers .= "X-Priority: 1\r\n";
      	 // file_put_contents($address.'txt',$message." add:".$address." title: ".$title);
          mail($address,$title,$message,$headers);
}
    public function doUser_step2($operation, $merchant_id, $last_name, $first_name, $phone_no, $email_address, $username, $password_confirm)
    {
        $dbobject = new dbobject();
        $desencrypt = new DESEncryption();
        $count_entry = 0;
        $key = $username;
        $cipher_password = $desencrypt->des($key, $userpassword, 1, 0, null, null);
        $str_cipher_password = $desencrypt->stringToHex($cipher_password);
        $query = "select * from userdata where username='$email_address'";
        //  echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        $dstta = date('Y-m-d');

        if ($numrows >= 1 && $operation == 'new') {
            ///////////////////////////
            $addquery = $user_locked == '0' ? ',pin_missed=0' : '';
            $query_data = "update userdata set firstname='$last_name', lastname='$first_name', mobile_phone='$phone_no', user_status='11', modified=now() where username='$email_address'";
            //echo $query_data;
            $result_data = mysql_query($query_data) or die(mysql_error());
            //echo mysql_error();
            $count_entry = mysql_affected_rows();
            $des = new MCrypt();
            $content = '{
					"merchant_id":"'.$des->encrypt(MERCHANTID).'"'.',
					'.'"username":"'.$des->encrypt($username).'"'.',
					"password":"'.$des->encrypt($password_confirm).'",
					"password_confirm":"'.$des->encrypt($password_confirm).'"'.',
					"phone_no":"'.$des->encrypt($phone_no).'"'.',
					'.'"email_address":"'.$des->encrypt($email_address).'"'.',
					'.'"first_name":"'.$des->encrypt($first_name).'"'.',
					'.'"last_name":"'.$des->encrypt($last_name).'"
				}';
            $uurrll = BASE_URL.'/createUser';
            $curl = curl_init($uurrll);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
            curl_setopt($curl, CURLOPT_POST, true);
            //curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $json_response = curl_exec($curl);
            $url_status = curl_getinfo($curl);

            curl_close($curl);
            $jsonData = json_decode($json_response, true);
            $status = $jsonData[status];
            $message = $jsonData[message];
            // echo $status." :: :: ".$message;

            if ($status = 200) {
                $res = $dbobject->doConfirm_user();
            }
        }

        return $count_entry;
    }

    public function DoBalance($user)
    {
        $des = new MCrypt();
        //	echo " 111 : ".$_SESSION['sonm_username'];
        $content = '{
					"merchant_id":"'.$des->encrypt(MERCHANTID).'"'.',
					'.'"username":"'.$des->encrypt($user).'"
				}';

        $uurrll = BASE_URL.'/balanceEnquiry';
        $curl = curl_init($uurrll);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        curl_setopt($curl, CURLOPT_POST, true);
        //curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $json_response = curl_exec($curl);
        $url_status = curl_getinfo($curl);

        curl_close($curl);
        // echo "content : ".$content ;
        $jsonData = json_decode($json_response, true);

        $status = $jsonData[status];
        $message = $jsonData[message];
        $balance = $jsonData[account_bal];

        return $balance;
        //var_dump($jsonData);
    }

    public function doVehicle($vid, $ownerName, $vehicleregno, $purpose, $capacity, $vehicleType, $TypeOfFuel, $email, $mobilenumber)
    {
        $count_entry = 0;
        $query = "select * from vehicleinfo where regId='$vid'";

        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update vehicleinfo set ownerName='$ownerName', vehicleregno='$vehicleregno', purpose='$purpose',  capacity='$capacity', vehicleType='$vehicleType' , TypeOfFuel='$TypeOfFuel' , email='$email' , mobilenumber='$mobilenumber' where regId='$vid' ";
            //echo $query_data;
            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $regid = randomcode();

            $vech_statecode = $_SESSION[statecode_sess]; //$ownerName; //$_SESSION['sonm_vech_statecode'];
            $consultance_id = $_SESSION[uid_sess];
            $create = date('Y-m-d h:i:sa');

            $query_data = "insert into vehicleinfo (regId,stateCode,consultance_id, ownerName, vehicleregno, purpose, capacity, vehicleType,TypeOfFuel, created,email,mobilenumber) values('$regid','$vech_statecode','$consultance_id',  '$ownerName', '$vehicleregno', '$purpose', '$capacity', '$vehicleType', '$TypeOfFuel', '$create','$email','$mobilenumber')";

            $result_data = mysql_query($query_data); //or die(mysql_error());
            $count_entry = mysql_affected_rows();
        } //End inner else
        //echo $query_data;
        $_SESSION[regid] = $regid;

        return $count_entry;
    }

    public function doVerify_user($code)
    {
        $count_entry = 0;
        $query = "select * from userdata  where username='$_SESSION[sonm_username]' AND vcode='$code'";

        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update userdata set user_status='11' where username='$_SESSION[sonm_username]' ";

            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            return 0; //$count_entry;
        }
    }

    public function doConfirm_user()
    {
        $count_entry = 0;
        $query = "select * from userdata  where username='$_SESSION[sonm_username]'";

        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update userdata set user_status='22' where username='$_SESSION[sonm_username]' ";

            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            return 0; //$count_entry;
        }
    }

    public function doServiceItem($item_id, $menu_id_cat, $item_name, $item_amount, $active)
    {
        $dbobject = new dbobject();
        $count_entry = 0;
        $query = "select * from sonm_item  where item_id='$item_id'";
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update sonm_item set menu_id_cat='$menu_id_cat', item_name='$item_name', item_amount='$item_amount', active='$active' where item_id='$item_id' ";
            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            // $itemid =$dbobject->paddZeros($dbobject->getnextid("itemId"),4);
            $statecode = $_SESSION['sonm_statecode'];
            $create = date('Y-m-d h:i:sa');
            $query_data = "insert into sonm_item(item_id,menu_id_cat,item_name,item_amount,active,created) values('$item_id','$menu_id_cat','$item_name','$item_amount','$active','$create')";
            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }

    public function doSave_job_card($data)
    {
        $job_id = $data['job_id'];
        $operation = $data['operation'];
        $customer_Name = $data['customerName'];
        $Address = $data['address'];
        $modelMake = $data['model_make'];
        $chessis_no = $data['chessisNo'];
        $Colour = $data['colour'];
        $phone_number = $data['phoneNumber'];
        $date_received = $data['dateReceived'];
        $date_completed = $data['dateCompleted'];
        $job_done_by = $data['jobDoneBy'];
        $timeStart = $data['timeStart'];
        $time_completed = $data['timeCompleted'];

        $dbobject = new dbobject();
        $count_entry = 0;
        $query = "select * from job_card_tb  where job_number='$job_id'";
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);

        if ($numrows > 0) {
            // $query_data ="update job_card_tb set address='$Address', model_make='$modelMake', date_received='$item_amount', chassis_no='$active' where job_number='$job_id' ";
            // $result_data = mysql_query($query_data);
            // $count_entry = mysql_affected_rows();
            $resp['status'] = '301';
            $resp['msg'] = 'Transaction Id Used Before';

            return -5;
        } else {
            $_product_id = $_SESSION['sonm_product_id'];
            $user_id = $_SESSION['sonm_username'];
            $create = date('Y-m-d h:i:sa');
            $query_data = "insert into job_card_tb(user_id,customer_name,job_number,address,model_make,date_received,chassis_no,date_to_completed,colour,phone_no,job_done_by,time_start,time_completed,created) values('$user_id','$customer_Name','$job_id','$Address','$modelMake','$date_received','$chessis_no','$date_completed','$Colour','$phone_number','$job_done_by','$timeStart','$time_completed','$create')";
            // echo $query_data;
            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }

    public function doBanks($bid, $bankname, $address, $mass, $vsat, $phoneno, $class_id, $type, $ranks)
    {
        $count_entry = 0;
        $query = "select * from business_organisationtb  where organisation_id='$bid'";

        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update business_organisationtb set organisation_name='$bankname', addresss='$address', organisation_type='$type', no_of_mass='$mass',  no_of_vsat='$vsat',  ranks='$ranks', phone_number='$phoneno' where organisation_id='$bid' ";
            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $regid = randomcode();
            $regid = $this->getUniqId($bankname);
            $statecode = $_SESSION['sonm_statecode'];
            //$statecode=$_SESSION['sonm_user_id'];
            $consultance_id = $_SESSION['sonm_user_id'];
            $create = date('Y-m-d h:i:sa');

            $query_data = "insert into business_organisationtb (organisation_id,stateCode,sector_id,consultance_id,organisation_name,addresss,no_of_mass,no_of_vsat,phone_number,ranks,organisation_type,classification_id,created) values('$regid','$statecode','001','$consultance_id','$bankname','$address','$mass','$vsat','$phoneno','$ranks','$type','$class_id','$create')";

            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            if ($count_entry > 0) {
                $this->sendSMSCeatedBussines($phoneno);
            }

            return $count_entry;
        }
    }

    public function jsonp_decode($jsonp, $assoc = false)
    {
        if ($jsonp[0] !== '[' && $jsonp[0] !== '{') { // we have JSONP
            $jsonp = substr($jsonp, strpos($jsonp, '('));
        }

        return json_decode(trim($jsonp, '();'), $assoc);
    }
public function doConfirmPaymentGeneral($rrr)
    {
        $dbobject = new dbobject();
        $merchantid = MERCHANT_ID;

        $api_key = APIKEY;
        $strToken = $rrr.$api_key.$merchantid;
        $remitaToken = hash('sha512', $strToken);
        $cu = curl_init();
        $url = BASEDURL.'/remita/ecomm/'.$merchantid.'/'.$rrr.'/'.$remitaToken.'/status.reg';

        curl_setopt($cu, CURLOPT_URL, $url);
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($cu, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($cu, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cu, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($cu, CURLOPT_POST, true);
        $server_output = curl_exec($cu);
       // echo  $server_output;

        // print_r($server_output);
        curl_close($cu);

        //$result = json_decode($server_output);
      $result= json_decode($server_output, true);
      //var_dump($result);
        $amount = $result['amount'];

        $R_R_R = $result['RRR'];
        $orderId = $result['orderId'];
        $message = $result['message'];
        $transactiontime = $result['transactiontime'];
        $status = $result['status'];



        return trim($message);
    }

    public function doConfirmPayment($rrr,$email)
    {
        $dbobject = new dbobject();
        $merchantid = MERCHANT_ID;
        $status ="";
        $api_key = APIKEY;
        $strToken = $rrr.$api_key.$merchantid;
        $remitaToken = hash('sha512', $strToken);
        $cu = curl_init();
        $url = BASEDURL.'/remita/ecomm/'.$merchantid.'/'.$rrr.'/'.$remitaToken.'/status.reg';

        curl_setopt($cu, CURLOPT_URL, $url);
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($cu, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($cu, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cu, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($cu, CURLOPT_POST, true);
        $server_output = curl_exec($cu);
       // echo  $server_output;

        // print_r($server_output);
        curl_close($cu);

        //$result = json_decode($server_output);
      $result= json_decode($server_output, true);
       //var_dump($result);
        $amount = $result['amount'];

        $R_R_R = $result['RRR'];
        $orderId = $result['orderId'];
        $message = $result['message'];
        $transactiontime = $result['transactiontime'];
        $status = $result['status'];
       file_put_contents("logger/response".date('Y_m_d').".txt","\n <incoming remitta response>\nregid=>".$email. "rrr=> ".$R_R_R."=> response ".$message." Status Code=>:".$status. "\n ", FILE_APPEND);

        if((trim($status) =='00')||(trim($status) == '01')){

        $this->logs('amount : '.$amount.' RRR : '.$R_R_R.' ::: orderId : '.$orderId.' ::: message : '.$message.' ::: transactiontime : '.$transactiontime.'  ::: status : '.$status);
        $time = str_replace("AM", "", $transactiontime);
         $mainTime = trim(str_replace("PM", "",  $time));
             $time =date("Y-m-d h-m-s");
             //rrr, email, amount, date_generated, date_paid, order_id, status_code, status_message, logid, program_code
       try{
           $program_code = "101";
           $str = "INSERT INTO tbl_rrr_log(rrr,email,amount,date_generated,date_paid,order_id,status_code,status_message,logid,program_code)VALUE('$R_R_R','$email','$amount','$time','$mainTime','$orderId','$status','$message','$time','$program_code')";
           // file_put_contents("logger/log.txt",$str);
        $result=   mysql_query($str);
       }catch (Exception $ex){}
       if($result>0){
            $strup = "UPDATE applicant_account_setup SET rrr_status='22',reg_status ='22' where reg_id ='$email' AND rrr ='$R_R_R'";
            mysql_query($strup);}
            return trim($status);
    }else{
            return trim($status);
    }


    }

    public function doConfirmPayment11($rrr)
    {
        $dbobject = new dbobject();
        $merchantid = '2547916';

        $api_key = '1946';

        $strToken = $rrr.$api_key.$merchantid;
        $remitaToken = hash('sha512', $strToken);
        //$headers = array("Content-Type: application/json", "Authorization: remitaConsumerKey=$merchantid, remitaConsumerToken=$remitaToken");
        //http://remitademo.net/remita/ecomm/2547916/280007680343/63cf99f3ca6126654695653d2bb3e9f66ee709305e3e51c825b48c432516aabfc7240a8ddc4f366d8af1f381a97edd538ede70dac325e1a0f21e1543f7fc2962/status.reg
        $cu = curl_init();

        curl_setopt($cu, CURLOPT_URL, 'http://remitademo.net/remita/ecomm/'.$merchantid.'/'.$rrr.'/'.$remitaToken.'/status.reg');

        //curl_setopt($cu, CURLOPT_HTTPHEADER, $headers);
        //curl_setopt($cu, CURLOPT_POST, 1);
        //curl_setopt($cu, CURLOPT_POSTFIELDS, $data);
        // receive server response ...
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($cu);

        print_r($server_output);
        curl_close($cu);

        $result = json_decode($server_output);
        $amount = $result->amount;
        $RRR = $result->RRR;
        $orderId = $result->orderId;
        $message = $result->message;
        $transactiontime = $result->transactiontime;
        $status = $result->status;

        return $result;
    }

    public function doSaveConfirmPayment($rrr, $channel, $amount, $transactiondate, $debitdate, $bank, $branch, $serviceTypeId, $dateRequested, $orderRef, $payerName, $payerPhoneNumber, $payerEmail, $uniqueIdentifier, $statusCode)
    {
        $dbobject = new dbobject();
        $pnid = $dbobject->paddZeros($dbobject->getnextid('REMITA_NOTIFICATION_STATUS'), 12);
        $pnid = 'RRR'.$pnid;
        $create = date('Y-m-d h:i:sa');
        $query_data = "insert into paymentnotificationtb(pnId,rrr,channel ,amount ,transactiondate ,debitdate ,bank ,branch ,serviceTypeId ,dateRequested ,orderRef ,payerName ,payerPhoneNumber ,payerEmail ,uniqueIdentifier,created ,statusCode) values('$pnid','$rrr','$channel' ,'$amount' ,'$transactiondate' ,'$debitdate' ,'$bank' ,'$branch' ,'$serviceTypeId' ,$dateRequested ,'$orderRef' ,'$payerName' ,'$payerPhoneNumber' ,'$payerEmail' ,'$uniqueIdentifier','$create','$statusCode')";
        //echo "Insert : ".$query_data;
        $result_data = mysql_query($query_data) or die(mysql_error());
        $count_entry = mysql_affected_rows();

        return $count_entry;
    }

    public function UpdateRRRstatus($rrr, $scode)
    {
        $query_data = "update applicant_account_setup set rrr_status='$scode' where rrr='$rrr' ";

        $result_data = mysql_query($query_data);
        $count_entry = mysql_affected_rows();

        return $count_entry;
    }

    public function doLogRRR($rrr, $message, $status)
    {
        $query_data = "update tbl_rrr_log set status_code='$status',status_message='$message' where rrr='$rrr' ";

        $result_data = mysql_query($query_data);
        $count_entry = mysql_affected_rows();

        return $count_entry;
    }

    public function doConfirmRRR($rrr)
    {
        $query_data = "select * from tbl_rrr_log where rrr ='$rrr'";
        // echo $query_data;
        $result = mysql_query($query_data);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                //echo $row['status_message'];
                $status_message = $row['status_message'];
            }
        }else{
            $status_message =" Yet To Be Proccessed";
        }

        return $status_message;
    }

    public function doConfirmRRRNotification($rrr)
    {
        $query_data = "select * from tbl_rrr_log where rrr ='$rrr'";
        //echo $query_data;
        $result = mysql_query($query_data);
        $numrows = mysql_num_rows($result);
        // if ($numrows > 0) {
        //     for ($i = 0; $i < $numrows; ++$i) {
        //         $row = mysql_fetch_array($result);
        //         //echo $row['status_message'];
        //         $status_message = $row['status_message'];
        //     }
        // }
        return $numrows;
    }//getRRRRemita2


    public function getRRRRemita2($studentName, $phoneno, $Program, $desc = 'sonm', $payerEmail = 'info@sonm.com.ng', $code)
    {
        $programId = $Program; // '001';

        $dbobject = new dbobject();
         $merchantid = MERCHANT_ID;
        $serviceTypeId = SERVICETYPEID; //$this->getitemlabel('sectors','sector_id',$programId,'service_type_id');
        $api_key = APIKEY;
        $orderId = $dbobject->paddZeros($dbobject->getnextid('accetance2019'), 8);

        $query_split = 'INSERT INTO split_report_tb(sr_id,transaction_d,account_number,account_name,amount,percent_amount,bank_name,created) VALUES';
        $create = date('Y-m-d h:i:sa');
        $beneficiaryAccountNo = '
            "lineItems":[';

        $total_amount = 0;
        $count = 0;
        $query_ = "SELECT * FROM account_settup WHERE programId= '101'";
        //echo $query_;
        $result_ = mysql_query($query_);
        while ($row = mysql_fetch_array($result_)) {
            $beneficiaryName = $row['beneficiaryName'];
            $beneficiaryAccount = $row['beneficiaryAccount'];
            $bankcode = $row['bankcode'];
            $deductFrom = $row['deductFrom'];
            $lineItemId = $row['lineItemId'];

            $acno_amount = $row['beneficiaryAmount'];

            $total_amount += $acno_amount;
            if ($count > 0) {
                $beneficiaryAccountNo .= ',{"lineItemsId":"'.$row['lineItemId'].'","beneficiaryName":"'.$row['beneficiaryName'].'","beneficiaryAccount":"'.$beneficiaryAccount.'","bankCode":"'.$row['bankcode'].'","beneficiaryAmount":"'.$acno_amount.'","deductFeeFrom":"'.$row['deductFrom'].'"}';
                $query_split .= ',("'.$orderId.$count.'","'.$orderId.'","'.$beneficiaryAccount.'","'.$row['beneficiaryName'].'","'.$acno_amount.'","'.$row['beneficiaryAmount'].'","'.$bankcode.'","'.$create.'")';
            } else {
                $query_split .= '("'.$orderId.$count.'","'.$orderId.'","'.$beneficiaryAccount.'","'.$row['beneficiaryName'].'","'.$acno_amount.'","'.$row['beneficiaryAmount'].'","'.$bankcode.'","'.$create.'")';
                $beneficiaryAccountNo .= '{"lineItemsId":"'.$row['lineItemId'].'","beneficiaryName":"'.$row['beneficiaryName'].'","beneficiaryAccount":"'.$beneficiaryAccount.'","bankCode":"'.$row['bankcode'].'","beneficiaryAmount":"'.$acno_amount.'","deductFeeFrom":"'.$row['deductFrom'].'"}';
            }
            ++$count;
        }
        // echo ' :::::::::::: '.$total_amount;
        //  $beneficiaryAccountNo.=',{"lineItemsId":"accessng001","beneficiaryName":"ASL","beneficiaryAccount":"0760547017","bankCode":"214","beneficiaryAmount":"250","deductFeeFrom":"0"}';

        $beneficiaryAccountNo .= ']';
        // if($total_amount == $amount)
        // {
        //  //echo " total_amount ::: ".$total_amount. " amount ::: ".$amount ." This is parfect";
        // }
        // else
        // {
        //  //echo " total_amount ::: ".$total_amount. " amount ::: ".$amount ." This is Bad";
        // }

        // $query_split .= ',("'.$orderId.'8'.'","'.$orderId.'","0760547017","ASL","250","0","214","'.$create.'")';

        $content = '{
                    "serviceTypeId":"'.$serviceTypeId.'"'.',
                    '.'"amount":"'.$total_amount.'"'.',
                    "orderId":"'.$orderId.'",
                    "payerName":"'.$studentName.'"'.',
                    "payerEmail":"'.$payerEmail.'"'.',
                    '.'"payerPhone":"'.$phoneno.'"'.',
                    '.'"description":"'.$desc.'",
                    '.$beneficiaryAccountNo.'
                }';

        $strToken = $merchantid.$serviceTypeId.$orderId.$total_amount.$api_key;
        //  echo $content;
      //  file_put_contents("rmit.txt", $content);
        //  exit();
        $remitaToken = hash('sha512', $strToken);
        $headers = array('Content-Type: application/json', "Authorization: remitaConsumerKey=$merchantid, remitaConsumerToken=$remitaToken");
         $uurrll = BASEDURL.'/remita/exapp/api/v1/send/api/echannelsvc/merchant/api/paymentinit';
        //echo $content;
         // file_put_contents("result2.txt", $content);
        $curl = curl_init($uurrll);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $json_response = curl_exec($curl);
        $url_status = curl_getinfo($curl);

        // print_r($json_response);
        curl_close($curl);
       // file_put_contents("result1.txt", $json_response);
        $jsonData = $this->jsonp_decode($json_response);

        $status_code = $jsonData->statuscode;
        $RRR = $jsonData->RRR;
        $status = $jsonData->status;
        $status_msg = $jsonData->statusMessage;

        //$dbobject->logs('rrr :'.$RRR.' : status_code : '.$status_code.' :status : '.$status.' :status_msg :'.$status_msg);
        // file_put_contents("result.txt", $status_code);
        if ($status_code == '025') {
            $now = @date('Y-m-d H:i:s');
            $r_r_r = time();
            $str = "INSERT INTO tbl_rrr_log(rrr,email,amount,date_generated,date_paid,order_id,status_code,status_message)VALUE('$RRR','$payerEmail','$total_amount','$now','$now','$orderId','025','$status_msg')";
          //  file_put_contents("rresult.txt", $str);

            $result = mysql_query($str);
            $label = mysql_affected_rows();
//reg_id, session, email, surname, othernaame, phone_number, program, userpassword, created, rrr, reg_status, rrr_status, linkCode, date_of_birth, gender, marital_status, Nationality, state_of_origin, local_Gov_Area, District_word, tribe, religion, postal_address, exam_center, ip, gname, gaddress, statusCode, pay_status, pbirth, passport, fname, rrr_acceptance, rrr_acceptance_date, rrr_acceptance_status, application_lock, educational_status, admissionstatus, date_adm, exam_center_id
            $str = "UPDATE applicant_account_setup SET rrr_acceptance = '$RRR', rrr_acceptance_status = '0' where reg_id='$code'";
            //file_put_contents("resultm.txt",   $str);
            $result = mysql_query($str); // or die(mysql_error());
            return $RRR;
        } else {
            return $status_code;
        }
    }


public function getRRRRemita($studentName, $phoneno, $Program, $desc = 'sonm', $payerEmail = 'info@sonm.com.ng', $code)
    {
        $programId = $Program; // '001';

        $dbobject = new dbobject();
         $merchantid = MERCHANT_ID;
        $serviceTypeId = SERVICETYPEID; //$this->getitemlabel('sectors','sector_id',$programId,'service_type_id');
        $api_key = APIKEY;
        $orderId = $programId.$dbobject->paddZeros($dbobject->getnextid('programm'), 8);

        $query_split = 'INSERT INTO split_report_tb(sr_id,transaction_d,account_number,account_name,amount,percent_amount,bank_name,created) VALUES';
        $create = date('Y-m-d h:i:s');
        $beneficiaryAccountNo = '
            "lineItems":[';

        $total_amount = 0;
        $count = 0;
        $query_ = "SELECT * FROM account_settup WHERE programId= '$programId'";
        //echo $query_;
        $result_ = mysql_query($query_);
        while ($row = mysql_fetch_array($result_)) {
            $beneficiaryName = $row['beneficiaryName'];
            $beneficiaryAccount = $row['beneficiaryAccount'];
            $bankcode = $row['bankcode'];
            $deductFrom = $row['deductFrom'];
            $lineItemId = $row['lineItemId'];

            $acno_amount = $row['beneficiaryAmount'];

            $total_amount += $acno_amount;
            if ($count > 0) {
                $beneficiaryAccountNo .= ',{"lineItemsId":"'.$row['lineItemId'].'","beneficiaryName":"'.$row['beneficiaryName'].'","beneficiaryAccount":"'.$beneficiaryAccount.'","bankCode":"'.$row['bankcode'].'","beneficiaryAmount":"'.$acno_amount.'","deductFeeFrom":"'.$row['deductFrom'].'"}';
                $query_split .= ',("'.$orderId.$count.'","'.$orderId.'","'.$beneficiaryAccount.'","'.$row['beneficiaryName'].'","'.$acno_amount.'","'.$row['beneficiaryAmount'].'","'.$bankcode.'","'.$create.'")';
            } else {
                $query_split .= '("'.$orderId.$count.'","'.$orderId.'","'.$beneficiaryAccount.'","'.$row['beneficiaryName'].'","'.$acno_amount.'","'.$row['beneficiaryAmount'].'","'.$bankcode.'","'.$create.'")';
                $beneficiaryAccountNo .= '{"lineItemsId":"'.$row['lineItemId'].'","beneficiaryName":"'.$row['beneficiaryName'].'","beneficiaryAccount":"'.$beneficiaryAccount.'","bankCode":"'.$row['bankcode'].'","beneficiaryAmount":"'.$acno_amount.'","deductFeeFrom":"'.$row['deductFrom'].'"}';
            }
            ++$count;
        }
        //file_put_contents("hello.txt",$query_split);
        // echo ' :::::::::::: '.$total_amount;
        //  $beneficiaryAccountNo.=',{"lineItemsId":"accessng001","beneficiaryName":"ASL","beneficiaryAccount":"0760547017","bankCode":"214","beneficiaryAmount":"250","deductFeeFrom":"0"}';

        $beneficiaryAccountNo .= ']';
        // if($total_amount == $amount)
        // {
        //  //echo " total_amount ::: ".$total_amount. " amount ::: ".$amount ." This is parfect";
        // }
        // else
        // {
        //  //echo " total_amount ::: ".$total_amount. " amount ::: ".$amount ." This is Bad";
        // }

        // $query_split .= ',("'.$orderId.'8'.'","'.$orderId.'","0760547017","ASL","250","0","214","'.$create.'")';

        $content = '{
                    "serviceTypeId":"'.$serviceTypeId.'"'.',
                    '.'"amount":"'.$total_amount.'"'.',
                    "orderId":"'.$orderId.'",
                    "payerName":"'.$studentName.'"'.',
                    "payerEmail":"'.$payerEmail.'"'.',
                    '.'"payerPhone":"'.$phoneno.'"'.',
                    '.'"description":"'.$desc.'",
                    '.$beneficiaryAccountNo.'
                }';

        $strToken = $merchantid.$serviceTypeId.$orderId.$total_amount.$api_key;
        // echo $content;
        //file_put_contents("result1.txt", $content);
         // exit();
        $remitaToken = hash('sha512', $strToken);
        $headers = array('Content-Type: application/json', "Authorization: remitaConsumerKey=$merchantid, remitaConsumerToken=$remitaToken");
         $uurrll = BASEDURL.'/remita/exapp/api/v1/send/api/echannelsvc/merchant/api/paymentinit';
        //echo $content;
        $curl = curl_init($uurrll);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $json_response = curl_exec($curl);
        $url_status = curl_getinfo($curl);

        // print_r($json_response);
        curl_close($curl);
        $jsonData = $this->jsonp_decode($json_response);
       // file_put_contents("result.txt", $json_response);
        $status_code = $jsonData->statuscode;
        $RRR = $jsonData->RRR;
        $status = $jsonData->status;
        $status_msg = $jsonData->statusMessage;
        $this->logs("rrr response from remitta: rrr: => ".$RRR." status => ".$status." msg => ".$status_msg);
        //$dbobject->logs('rrr :'.$RRR.' : status_code : '.$status_code.' :status : '.$status.' :status_msg :'.$status_msg);
        if ($status_code == 25) {
            $now = @date('Y-m-d H:i:s');
           // $r_r_r = time();
            //$str = "INSERT INTO tbl_rrr_log(rrr,email,amount,date_generated,date_paid,order_id,status_code,status_message)VALUE('$RRR','$payerEmail','$amountt','$now','$now','$orderId','025','$status_msg')";

           // $result = mysql_query($str);
           // $label = mysql_affected_rows();

            $str = "UPDATE applicant_account_setup SET rrr = '$RRR', rrr_status = '025' where linkCode='$code'";
            $result = mysql_query($str); // or die(mysql_error());
            return $RRR;
        } else {
            return $status_code;
        }
    }

    /////////////////////////

    public function percentage($per, $amount)
    {
        return ($per / 100) * $amount;
    }

    public function doShop($sid, $shopname, $operator, $address, $phoneno, $METHODOFWASTEDISPOSAL, $classification_id, $type, $ranks)
    {
        $count_entry = 0;
        $query = "select * from business_organisationtb  where organisation_id='$sid'";

        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update business_organisationtb set name_of_owner='$operator', organisation_name='$shopname', addresss='$address', classification_id='$classification_id',organisation_type='$type',ranks='$ranks' , method_of_waste_dispose='$METHODOFWASTEDISPOSAL', phone_number='$phoneno' where organisation_id='$sid' ";

            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $regid = randomcode();
            //$regid=getUniqId($operator);
                $statecode = $_SESSION['sonm_statecode']; //$ownerName; //$_SESSION['sonm_vech_statecode'];
                $consultance_id = $_SESSION['sonm_user_id'];
            $create = date('Y-m-d h:i:sa');

            $query_data = "insert into business_organisationtb (organisation_id,stateCode,sector_id,consultance_id, name_of_owner, organisation_name, addresss, method_of_waste_dispose, phone_number,created,classification_id,organisation_type,ranks) values('$regid','$statecode','003','$consultance_id','$shopname','$operator','$address','$phoneno','$METHODOFWASTEDISPOSAL','$create','$classification_id','$type','$ranks')";

            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }

    public function doFoodEatery($fid, $shopname, $operator, $address, $phoneno, $METHODOFWASTEDISPOSAL, $classification_id, $type, $ranks)
    {
        $count_entry = 0;
        $query = "select * from food_eaterytb  where organisation_id='$sid'";
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update food_eaterytb set name_of_owner='$operator', organisation_name='$shopname', addresss='$address', classification_id='$classification_id',organisation_type='$type',ranks='$ranks'  method_of_waste_dispose='$METHODOFWASTEDISPOSAL', phone_number='$phoneno' where organisation_id='$fid' ";

            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $regid = randomcode();
            //$regid=getUniqId($operator);
                $statecode = $_SESSION['sonm_statecode']; //$ownerName; //$_SESSION['sonm_vech_statecode'];
                $consultance_id = $_SESSION['sonm_user_id'];
            $create = date('Y-m-d h:i:sa');
            $query_data = "insert into business_organisationtb (organisation_id,stateCode,sector_id,consultance_id, name_of_owner, organisation_name, addresss, method_of_waste_dispose, phone_number,created,classification_id,organisation_type,ranks) values('$regid','$statecode','004','$consultance_id','$shopname','$operator','$address','$phoneno','$METHODOFWASTEDISPOSAL','$create','$classification_id','$type','$ranks')";
            //echo $query_data;
            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }

    public function doComputer_Ict($cid, $shopname, $operator, $address, $phoneno, $METHODOFWASTEDISPOSAL, $classification_id, $type, $ranks)
    {
        $count_entry = 0;
        $query = "select * from business_organisationtb  where organisation_id='$cid'";
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update business_organisationtb set name_of_owner='$operator', dateModifies='$create',  organisation_name='$shopname', addresss='$address', classification_id='$classification_id',organisation_type='$type',ranks='$ranks' , method_of_waste_dispose='$METHODOFWASTEDISPOSAL', phone_number='$phoneno' where organisation_id='$cid' ";

            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $regid = randomcode();
            //$regid=getUniqId($operator);
                $statecode = $_SESSION['sonm_statecode']; //$ownerName; //$_SESSION['sonm_vech_statecode'];
                $consultance_id = $_SESSION['sonm_user_id'];
            $create = date('Y-m-d h:i:sa');
            $query_data = "insert into business_organisationtb (organisation_id,stateCode,sector_id,consultance_id, name_of_owner, organisation_name, addresss, method_of_waste_dispose, phone_number,created,classification_id,organisation_type,ranks) values('$regid','$statecode','006','$consultance_id','$shopname','$operator','$address','$phoneno','$METHODOFWASTEDISPOSAL','$create','$classification_id','$type','$ranks')";

            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }

    public function doEducation($eid, $shopname, $operator, $address, $phoneno, $PHONENUMBER, $classification_id, $type, $ranks)
    {
        $count_entry = 0;
        $query = "select * from business_organisationtb  where organisation_id='$eid'";
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update business_organisationtb set name_of_owner='$operator', organisation_name='$shopname', addresss='$address', classification_id='$classification_id',organisation_type='$type',ranks='$ranks',  population='$METHODOFWASTEDISPOSAL', phone_number='$phoneno' where organisation_id='$eid' ";
            echo $query_data;
            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $regid = randomcode();
            //$regid=getUniqId($operator);
            $statecode = $_SESSION['sonm_statecode'];
            $consultance_id = $_SESSION['sonm_user_id'];
            $create = date('Y-m-d h:i:sa');
            $query_data = "insert into business_organisationtb (organisation_id,stateCode,sector_id,consultance_id, name_of_owner, organisation_name, addresss, population, phone_number,created,classification_id,organisation_type,ranks) values('$regid','$statecode','011','$consultance_id','$shopname','$operator','$address','$PHONENUMBER','$phoneno ','$create','$classification_id','$type','$ranks')";

            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }

    public function doPetrolGas($pid, $shopname, $operator, $address, $phoneno, $METHODOFWASTEDISPOSAL, $classification_id, $type, $ranks)
    {
        $count_entry = 0;
        $query = "select * from business_organisationtb  where organisation_id='$pid'";
        echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update business_organisationtb set name_of_owner='$operator', organisation_name='$shopname', addresss='$address', classification_id='$classification_id',organisation_type='$type',ranks='$ranks' , method_of_waste_dispose='$METHODOFWASTEDISPOSAL', phone_number='$phoneno' where organisation_id='$pid' ";
            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $regid = randomcode();
            //$regid=getUniqId($operator);
            $statecode = $_SESSION['sonm_statecode'];
            $consultance_id = $_SESSION['sonm_user_id'];
            $create = date('Y-m-d h:i:sa');
            $query_data = "insert into business_organisationtb (organisation_id,stateCode,sector_id,consultance_id, name_of_owner, organisation_name, addresss, method_of_waste_dispose, phone_number,created,classification_id,organisation_type,ranks) values('$regid','$statecode','007','$consultance_id','$shopname','$operator','$address','$phoneno','$METHODOFWASTEDISPOSAL','$create','$classification_id','$type','$ranks')";
            //echo $query_data;
            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }

    public function doDrillingMining($did, $shopname, $operator, $address, $phoneno, $METHODOFWASTEDISPOSAL, $classification_id, $type, $ranks)
    {
        $count_entry = 0;
        $query = "select * from business_organisationtb  where organisation_id='$did'";

        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update business_organisationtb set name_of_owner='$operator', organisation_name='$shopname', addresss='$address', classification_id='$classification_id',organisation_type='$type',ranks='$ranks',  method_of_waste_dispose='$METHODOFWASTEDISPOSAL', phone_number='$phoneno' where organisation_id='$did' ";
            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $regid = randomcode();
            $statecode = $_SESSION['sonm_statecode'];
            $consultance_id = $_SESSION['sonm_user_id'];
            $create = date('Y-m-d h:i:sa');
            $query_data = "insert into business_organisationtb (organisation_id,stateCode,sector_id,consultance_id, name_of_owner, organisation_name, addresss, method_of_waste_dispose, phone_number,created,classification_id,organisation_type,ranks) values('$regid','$statecode','009','$consultance_id','$shopname','$operator','$address','$phoneno','$METHODOFWASTEDISPOSAL','$create','$classification_id','$type','$ranks')";

            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }

    public function doHouspitality($hid, $shopname, $operator, $address, $phoneno, $METHODOFWASTEDISPOSAL, $classification_id, $type, $ranks)
    {
        $count_entry = 0;
        $query = "select * from business_organisationtb  where organisation_id='$hid'";

        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update business_organisationtb set name_of_owner='$operator', organisation_name='$shopname', addresss='$address', classification_id='$classification_id',organisation_type='$type',ranks='$ranks' , method_of_waste_dispose='$METHODOFWASTEDISPOSAL', phone_number='$phoneno' where organisation_id='$hid' ";
            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $regid = randomcode();
            $statecode = $_SESSION['sonm_statecode'];
            $consultance_id = $_SESSION['sonm_user_id'];
            $create = date('Y-m-d h:i:sa');
            $query_data = "insert into business_organisationtb (organisation_id,stateCode,sector_id,consultance_id, name_of_owner, organisation_name, addresss, method_of_waste_dispose, phone_number,created,classification_id,organisation_type,ranks) values('$regid','$statecode','010','$consultance_id','$shopname','$operator','$address','$phoneno','$METHODOFWASTEDISPOSAL','$create','$classification_id','$type','$ranks')";
            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }

    public function doMedianHouse($mid, $shopname, $operator, $address, $phoneno, $METHODOFWASTEDISPOSAL, $classification_id, $type, $ranks)
    {
        $count_entry = 0;
        $query = "select * from business_organisationtb  where organisation_id='$mid'";

        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update business_organisationtb set name_of_owner='$operator', organisation_name='$shopname', addresss='$address', classification_id='$classification_id',organisation_type='$type',ranks='$ranks' , method_of_waste_dispose='$METHODOFWASTEDISPOSAL', phone_number='$phoneno' where organisation_id='$mid' ";

            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $regid = randomcode();
            $statecode = $_SESSION['sonm_statecode'];
            $consultance_id = $_SESSION['sonm_user_id'];
            $create = date('Y-m-d h:i:sa');
            $query_data = "insert into business_organisationtb (organisation_id,stateCode,sector_id,consultance_id, name_of_owner, organisation_name, addresss, method_of_waste_dispose, phone_number,created,classification_id,organisation_type,ranks) values('$regid','$statecode','012','$consultance_id','$shopname','$operator','$address','$phoneno','$METHODOFWASTEDISPOSAL','$create','$classification_id','$type','$ranks')";

            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }
 public function getitemcount($regid,$subject,$sitting,$type) {
       // $query1 = "select count(reg_id) as total from results_tb where reg_id='$regId' and subject_name = '$subjectName' and siting ='$sitting' ";

        $data = $this->getItemLabelArr("results_tb", array("reg_id", "position", "siting","result"), array($regid, $subject, $sitting,$type), array("count(reg_id) as total"));
file_put_contents("hi.txt", $data,FILE_APPEND);
        return ($data != "") ? $data['total'] : "0";
    }
     public function getitemKey($regid,$subject,$sitting,$type) {
       // $query1 = "select count(reg_id) as total from results_tb where reg_id='$regId' and subject_name = '$subjectName' and siting ='$sitting' ";

        $data = $this->getItemLabelArr("results_tb", array("reg_id", "subject_name", "siting","result"), array($regid, $subject, $sitting,$type), array("sid as sid"));
file_put_contents("hi.txt", $data,FILE_APPEND);
        return ($data != "") ? $data['sid'] : "0";
    }
     public function getitemPosition($regid,$subject,$sitting,$type) {
       // $query1 = "select count(reg_id) as total from results_tb where reg_id='$regId' and subject_name = '$subjectName' and siting ='$sitting' ";

        $data = $this->getItemLabelArr("results_tb", array("reg_id", "subject_name", "siting","result"), array($regid, $subject, $sitting,$type), array("position as position"));
file_put_contents("hi.txt", $data,FILE_APPEND);
        return ($data != "") ? $data['position'] : "0";
    }
    public function getitemPosition2($regid,$sitting,$type,$pos) {
       // $query1 = "select count(reg_id) as total from results_tb where reg_id='$regId' and subject_name = '$subjectName' and siting ='$sitting' ";

        $data = $this->getItemLabelArr("results_tb", array("reg_id", "siting","result","position"), array($regid, $sitting,$type,$pos), array("sid as sid"));
file_put_contents("hi.txt", $data,FILE_APPEND);
        return ($data != "") ? $data['sid'] : "0";
    }
    public function doArtisans($aid, $shopname, $operator, $address, $phoneno, $METHODOFWASTEDISPOSAL, $classification_id, $type, $ranks)
    {
        $count_entry = 0;
        $query = "select * from business_organisationtb  where organisation_id='$aid'";

        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update business_organisationtb set name_of_owner='$operator', organisation_name='$shopname', addresss='$address', classification_id='$classification_id',organisation_type='$type',ranks='$ranks', method_of_waste_dispose='$METHODOFWASTEDISPOSAL', phone_number='$phoneno' where organisation_id='$aid' ";

            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $regid = randomcode();
            $statecode = $_SESSION['sonm_statecode'];
            $consultance_id = $_SESSION['sonm_user_id'];
            $create = date('Y-m-d h:i:sa');
            $query_data = "insert into business_organisationtb (organisation_id,stateCode,sector_id,consultance_id, name_of_owner, organisation_name, addresss, method_of_waste_dispose, phone_number,created,classification_id,organisation_type,ranks) values('$regid','$statecode','013','$consultance_id','$shopname','$operator','$address','$phoneno','$METHODOFWASTEDISPOSAL','$create','$classification_id','$type','$ranks')";

            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }

    public function doAccountSetup($aid, $programId, $beneficiaryName, $beneficiaryAccount, $bankcode, $deductFrom, $mobileno)
    {
        $dbobject = new dbobject();
        $count_entry = 0;
        $query = "select * from account_settup  where lineItemId='$aid'";
        $create = date('Y-m-d h:i:sa');
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update account_settup set beneficiaryName='$beneficiaryName', beneficiaryAccount='$beneficiaryAccount', bankcode='$bankcode', deductFrom='$deductFrom' ,edited='$create' where lineItemId='$aid' ";
            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $regid = $dbobject->paddZeros($dbobject->getnextid('acno'), 4);
            $statecode = $_SESSION['sonm_statecode'];
            $consultance_id = $_SESSION['sonm_user_id'];
            $query_data = "insert into account_settup (programId,beneficiaryName,beneficiaryAccount, bankcode, deductFrom, lineItemId, created) values('$programId','$beneficiaryName','$beneficiaryAccount','$bankcode','$deductFrom','$regid','$create')";
            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }

    public function doAgent($aid, $Fullname, $Position, $gender, $dob, $mobilenumber, $address, $kinname, $kinno, $imgurl, $consultance)
    {
        $count_entry = 0;
        $query = "select * from consultant_proccessortb  where agent_id='$aid'";
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update consultant_proccessortb set consultance_id='$consultance', name='$Fullname', position='$Position',  mobile_number='$mobilenumber', address='$address' where agent_id='$aid' ";
            // echo $query_data;
            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $dbobject = new dbobject();
            $regid = randomcode();
            $agent_id = $dbobject->paddZeros($dbobject->getnextid('agent'), 4);
            $statecode = $_SESSION['sonm_statecode']; //$ownerName; //$_SESSION['sonm_vech_statecode'];
            $consultance_id = $_SESSION['sonm_user_id'];
            $create = date('Y-m-d h:i:sa');
            $uid = md5(uniqid(rand(), true));
            $query_data = "insert into consultant_proccessortb (agent_id,consultance_id,name, address, gender, position, mobile_number,next_of_kins_fullnem,next_of_kins_no,passport_img,created) values('$agent_id','$consultance_id','$Fullname','$address','$gender','$Position','$mobilenumber','$kinname','$kinno','$imgurl','$create')";
            //echo $query_data;
            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }

    public function doRrr($Fullname, $Position, $gender, $dob, $mobilenumber, $address, $kinname, $kinno, $imgurl)
    {
        $count_entry = 0;
        $query = "select * from consultant_proccessortb  where rcc_number='$sid'";
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update consultant_proccessortb set name_of_company='$companyname', contact_person_name='$personname', lot='$lot',  person_mobile='$mobilenumber', address='$address' where agent_id='$rccno' ";
            // echo $query_data;
            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $dbobject = new dbobject();
            $regid = randomcode();
            $agent_id = $dbobject->paddZeros($dbobject->getnextid('agent'), 4);
            $statecode = $_SESSION[statecode_sess]; //$ownerName; //$_SESSION['sonm_vech_statecode'];
            $consultance_id = $_SESSION[uid_sess];
            $create = date('Y-m-d h:i:sa');
            $uid = md5(uniqid(rand(), true));
            $query_data = "insert into consultant_proccessortb (agent_id,consultance_id,name, address, gender, position, mobile_number,next_of_kins_fullnem,next_of_kins_no,passport_img,created) values('$agent_id','$consultance_id','$Fullname','$address','$gender','$Position','$mobilenumber','$kinname','$kinno','$create','$create')";
            //echo $query_data;
            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }

    public function doConsultant($rccno, $companyname, $personname, $lot, $mobilenumber, $address, $lots, $emailaddress)
    {
        $count_entry = 0;
        $query = "select * from consultanttb  where rcc_number='$rccno'";

        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update consultanttb set name_of_company='$companyname', contact_person_name='$personname',emailaddress='$emailaddress', lot='$lot',  person_mobile='$mobilenumber', address='$address' where rcc_number='$rccno' ";
            // echo $query_data;
            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $dbobject = new dbobject();
            $regid = randomcode();
            $uid = 'Cons'.$dbobject->paddZeros($dbobject->getnextid('Consultant'), 4);

            $statecode = $_SESSION['sonm_statecode'];
            $consultance_id = $uid; //$_SESSION['sonm_user_id'];
            $create = date('Y-m-d h:i:sa');
            $query_data = "insert into consultanttb (rcc_number,stateCode,consultance_id, name_of_company, contact_person_name, lot, person_mobile,address,created,emailaddress) values('$rccno','$statecode','$consultance_id','$companyname','$personname','$lot','$mobilenumber','$address','$create','$emailaddress')";
            //echo $query_data;
            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();
            if ($count_entry > 0) {
                $create_user = $dbobject->doUser('new', $uid, $rccno, $rccno, $personname, '', $emailaddress, $mobilenumber, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, $override_wh, $extend_wh, '5060', 'Administrator', $branch_id);
                $userinrole = $dbobject->doAddToRole('5060', $uid);
                //echo $lots ."<br/>";
                $userinrole = $dbobject->doAddLotToConsult($uid, $lots);
            }

            return $count_entry;
        }
    }

    public function doContactUs($fullname, $email, $message, $phone_no)
    {
        $msgid = $dbobject->paddZeros($dbobject->getnextid('contactus'), 8);
        $sender_ip = $dbobject->getRealIp();
        $create = date('Y-m-d h:i:sa');
        $query_data = "insert into contact_us (contact_id,fullname,email,message, phone_no, sender_ip, status,created) values('$msgid','$fullname','$email','$message','$phone_no','$sender_ip','00','$create')";
        $result_data = mysql_query($query_data) or die(mysql_error());
        $count_entry = mysql_affected_rows();

        return $count_entry;
    }

    public function doSupermaket($sid, $shopname, $operator, $address, $phoneno, $METHODOFWASTEDISPOSAL, $classification_id, $type, $ranks)
    {
        $count_entry = 0;
        $query = "select * from business_organisationtb  where organisation_id='$sid'";

        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $create = date('Y-m-d H:i:s');
            $query_data = "update business_organisationtb set name_of_owner='$operator', organisation_type='$type',organisation_name='$shopname', dateModifies='$create', addresss='$address', ranks='$ranks',  method_of_waste_dispose='$METHODOFWASTEDISPOSAL', phone_number='$phoneno' where organisation_id='$sid' ";

            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $regid = randomcode();
            $statecode = $_SESSION['sonm_statecode'];
            $consultance_id = $_SESSION['sonm_user_id'];
            $create = date('Y-m-d H:i:s');
            $query_data = "insert into business_organisationtb (organisation_id,stateCode,sector_id,consultance_id, name_of_owner, organisation_name, addresss, method_of_waste_dispose, phone_number,created,classification_id,organisation_type,ranks) values('$regid','$statecode','005','$consultance_id','$operator','$shopname','$address','$METHODOFWASTEDISPOSAL','$phoneno','$create','$classification_id','$type','$ranks')";
            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }

    public function doPrinting($pid, $SHOPNAME, $NAMEOFOPERATOR, $ADDRESS, $PHONENUMBER, $TYPESOFMACHINESUSED, $METHODOFWASTEDISPOSAL)
    {
        $count_entry = 0;
        $query = "select * from printingtb  where printingtstore_id='$pid'";
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update printingtb set name_of_owner='$NAMEOFOPERATOR', organisation_name='$SHOPNAME', address='$ADDRESS',  method_of_waste_dispose='$METHODOFWASTEDISPOSAL', shop_levies_mobileno='$PHONENUMBER' where printingtstore_id='$pid' ";
            //echo $query_data;
            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $regid = randomcode();
            //$regid=getUniqId($operator);
            $statecode = $_SESSION[statecode_sess];
            $consultance_id = $_SESSION[uid_sess];
            $create = date('Y-m-d H:i:s');

            $query_data = "insert into printingtb (printingtstore_id,stateCode,consultance_id, name_of_owner, organisation_name, address,machines_used, method_of_waste_dispose, shop_levies_mobileno,created) values('$regid','$statecode','$consultance_id','$NAMEOFOPERATOR','$SHOPNAME','$ADDRESS','$TYPESOFMACHINESUSED','$METHODOFWASTEDISPOSAL','$PHONENUMBER','$create')";
            //echo $query_data;
            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }

    public function doHaulage($hid, $NAMEOFOPERATOR, $ADDRESS, $PHONENUMBER, $VEHICLEREGNUMBER, $WEIGHTHAULED, $classification_id, $type, $ranks)
    {
        $count_entry = 0;
        $query = "select * from business_organisationtb  where organisation_id='$hid'";
        echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $create = date('Y-m-d H:i:s');
            $query_data = "update business_organisationtb set name_of_owner='$NAMEOFOPERATOR', dateModifies='$create',  addresss='$ADDRESS',phone_number='$PHONENUMBER',classification_id='$classification_id',organisation_type='$type', ranks='$ranks',  vehicle_reg_no='$VEHICLEREGNUMBER', weight_hauled='$WEIGHTHAULED' where organisation_id='$hid' ";
            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $regid = randomcode();
            $statecode = $_SESSION['sonm_statecode'];
            $consultance_id = $_SESSION['sonm_user_id'];
            $create = date('Y-m-d H:i:s');
            $query_data = "insert into business_organisationtb (organisation_id,stateCode,sector_id,consultance_id, name_of_owner,addresss,phone_number,vehicle_reg_no,weight_hauled,created,classification_id,organisation_type,ranks) values('$regid','$statecode','008','$consultance_id','$NAMEOFOPERATOR','$ADDRESS','$PHONENUMBER','$VEHICLEREGNUMBER','$WEIGHTHAULED','$create','$classification_id','$type','$ranks')";
            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }

    public function doGlassshop($gid, $SHOPNAME, $NAMEOFOPERATOR, $ADDRESS, $PHONENUMBER, $METHODOFWASTEDISPOSAL)
    {
        $count_entry = 0;
        $query = "select * from glassshoptb  where glassshop_id='$gid'";
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update glassshoptb set name_of_owner='$NAMEOFOPERATOR',organisation_name='$SHOPNAME', shop_levies_address='$ADDRESS',  method_of_waste_dispose='$METHODOFWASTEDISPOSAL', shop_levies_mobileno='$PHONENUMBER' where glassshop_id='$gid' ";
            //echo $query_data;
            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $regid = randomcode();
            //$regid=getUniqId($operator);
            $statecode = $_SESSION[statecode_sess];
            $consultance_id = $_SESSION[uid_sess];
            $create = date('Y-m-d h:i:sa');
            $query_data = "insert into glassshoptb (glassshop_id,stateCode,consultance_id, organisation_name, name_of_owner, shop_levies_address, shop_levies_mobileno, method_of_waste_dispose,shop_levies_created) values('$regid','$statecode','$consultance_id','$SHOPNAME','$NAMEOFOPERATOR','$ADDRESS','$PHONENUMBER','$METHODOFWASTEDISPOSAL','$create')";
            //echo $query_data;
            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }

    public function doSector($sid, $statecode, $sectorname)
    {
        $count_entry = 0;
        $query = "select * from sectors  where sector_id='$sid'";

        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update sectors set sector_name='$sectorname', category='$SHOPNAME', menu_url='$ADDRESS', where sector_id='$gid' ";

            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $regid = randomcode();
            $statecode = $_SESSION['sonm_statecode'];
            $userid = $_SESSION['sonm_user_id'];
            $create = date('Y-m-d h:i:sa');
            $lot = 'Lot '.$sid;
            $menu_url = strtolower(preg_replace('/\s+/', '', $sectorname).'_list.php');
            $state_code = $statecode;

            if ($_SESSION['sonm_role_id'] == '5011' or $_SESSION['sonm_role_id'] == '5021') {
                $state_code = $_SESSION['sonm_statecode'];
            }
            $query_data = "insert into sectors (sector_id,stateCode,sector_name,category, menu_url,officer,created_on) values('$sid','$state_code','$sectorname','$lot','$menu_url','$userid','$create')";
            //echo $query_data;
            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }
public function nursing_generic_count($field = '',$status = ''){
    if($field!=""){
        $query = "select count(reg_id) as total from applicant_account_setup where `$field` ='$status' AND program ='101'";
    }else{
         $query = "select count(reg_id) as total from applicant_account_setup where  program ='101' ";
    }
        //echo $query;
        $result = mysql_query($query);
        $data = mysql_fetch_assoc($result);

        return ($data != "") ? $data['total'] : "0";
}
public function nursing_generic_count_main($field = '',$status = ''){
    if($field!=""){
        //reg_id, session, email, surname, othernaame, phone_number, program, userpassword, created, rrr, reg_status, rrr_status, linkCode, date_of_birth, gender, marital_status, Nationality, state_of_origin, local_Gov_Area, District_word, tribe, religion, postal_address, exam_center, ip, gname, gaddress, statusCode, pay_status, pbirth, passport, fname, rrr_acceptance, rrr_acceptance_date, rrr_acceptance_status, application_lock, educational_status, admissionstatus, date_adm, exam_center_id
        $query = "select count(reg_id) as total from applicant_account_setup where `$field` ='$status' AND (reg_status !='0' OR reg_status !='22') AND (program ='101')";
    }else{
         $query = "select count(reg_id) as total from applicant_account_setup where  program ='101' ";
    }
        //echo $query;
        $result = mysql_query($query);
        $data = mysql_fetch_assoc($result);

        return ($data != "") ? $data['total'] : "0";
}
public function nursing_generic_query_count($query){
   // file_put_contents("inno.txt", $query);

        $result = mysql_query($query);
        $data = mysql_fetch_assoc($result);

        return ($data != "") ? $data['total'] : "0";
}
public function nursing_table_generic_count($table,$field,$status = '',$reverse =''){
   if($reverse !=''){
    if($reverse =='1'){
        $status =' != '.$status;

    }else{
        $status = ' = '.$status;
    }}

    if($status!=""){
        $query = "select count($field) as total from `$table` where `$field` ". $status;
    }else{
         $query = "select count($field) as total from `$table` ";
    }
        //echo $query;
        $result = mysql_query($query);
        $data = mysql_fetch_assoc($result);

        return ($data != "") ? $data['total'] : "0";
}
public function nursing_generic_count_not($field = '',$status = ''){
        //reg_id, session, email, surname, othernaame, phone_number, program, userpassword, created, rrr, reg_status, rrr_status, linkCode, date_of_birth, gender, marital_status, Nationality, state_of_origin, local_Gov_Area, District_word, tribe, religion, postal_address, exam_center, ip, gname, gaddress, statusCode, pay_status, pbirth, passport, fname, rrr_acceptance, rrr_acceptance_date, rrr_acceptance_status, application_lock, educational_status, admissionstatus, date_adm, exam_center_id, ward, date_locked
    if($status!=""){
        $query = "select count(reg_id) as total from applicant_account_setup where `$field` !='$status' AND program ='101' ";
    }else{
         $query = "select count(reg_id) as total from applicant_account_setup where  program ='101'";
    }
       // echo $query;
        $result = mysql_query($query);
        $data = mysql_fetch_assoc($result);

        return ($data != "") ? $data['total'] : "0";
}
    public function doMotovehicle($mid, $SHOPNAME, $NAMEOFOPERATOR, $ADDRESS, $PHONENUMBER, $METHODOFWASTEDISPOSAL)
    {
        $count_entry = 0;
        $query = "select * from motovehicletb  where motovehicle_id='$mid'";
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update motovehicletb set name_of_owner='$NAMEOFOPERATOR', organisation_name='$SHOPNAME', shop_levies_address='$ADDRESS',  method_of_waste_dispose='$METHODOFWASTEDISPOSAL', shop_levies_mobileno='$PHONENUMBER' where motovehicle_id='$mid' ";
            //echo $query_data;
            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();

            return $count_entry;
        } else {
            $regid = randomcode();
            //$regid=getUniqId($operator);
            $statecode = $_SESSION[statecode_sess];
            $consultance_id = $_SESSION[uid_sess];
            $create = date('Y-m-d h:i:sa');
            $query_data = "insert into motovehicletb (motovehicle_id,stateCode,consultance_id, name_of_owner, organisation_name, shop_levies_address, method_of_waste_dispose, shop_levies_mobileno,shop_levies_created) values('$regid','$statecode','$consultance_id','$SHOPNAME','$NAMEOFOPERATOR','$ADDRESS','$PHONENUMBER','$METHODOFWASTEDISPOSAL','$create')";
            //echo $query_data;
            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }

    public function doContact($name, $email, $message)
    {
        $dbobject = new dbobject();
        $regid = $dbobject->generatePIN('12', '0');

        $create = date('Y-m-d h:i:sa');
        $query_data = "insert into contact_us (contact_id,fullname,email, message,created) values('$regid','$name','$email','$message','$create')";
        $result_data = mysql_query($query_data); //or die(mysql_error());
        $count_entry = mysql_affected_rows();

        return $count_entry;
    }

    public function doRcc($pid, $transid, $osname, $ooname, $odob, $ogender, $obloodgroup, $olangSpoken, $omaritalstatus, $onationality, $omobileno, $oaddress, $opsurname, $oponame, $opdob, $opgender, $opbloodg, $oplangspoken, $opmaritalstatus, $opnationality, $opmobileno, $opaddress, $kinfullname, $kinaddress, $kinmobileno, $kinemail, $make, $type, $idnetificationmark, $stateno, $chessisno, $engineeno, $colour, $anyunion, $nameofassocation, $officeaddress, $areacouncil, $typeofid, $idno, $idissuedate, $idexpiredate, $issueauthority, $permit_issue_dates, $permit_expiry_dates, $operation)
    {
        $userid = $_SESSION[uid_sess];
        if ($operation == 'edit') {
            $count_entry = 0;
            $query = "select * from rcc_tbl  where rptid='$pid'";
            //echo $query;
            $result = mysql_query($query);
            $numrows = mysql_num_rows($result);
            if ($numrows >= 1) {
                $query_data = "update rcc_tbl set owner_surname='$osname', owner_othername='$ooname', owner_dob='$odob',  owner_gender='$ogender', owner_blood_group='$obloodgroup' , owner_lang='$olangSpoken' , owner_marital_status='$omaritalstatus' , owner_Nationality='$onationality' , owner_gsm='$omobileno' , owner_address='$oaddress' , operator_surname='$opsurname' , operator_othername='$oponame' , operator_dob='$opdob' , operator_gender='$opgender' , operator_blood_group='$opbloodg' , operator_lang='$oplangspoken' , operator_nationality='$opnationality' , operator_marital_status='$opmaritalstatus' , operator_gsm='$opmobileno' , operator_address='$opaddress' , operator_nextofkin_name='$kinfullname' , operator_nextofkin_address='$kinaddress' , operator_nextofkin_mobileno='$kinmobileno' , operator_nextofkin_email='$kinemail' , cycle_make='$make' , cycle_type='$type' , idMark='$idnetificationmark' , idMarkYes='$stateno' , cycle_chassis_number='$chessisno' , cycle_engine_number='$engineeno' , cycle_Colour='$colour' , belong_to_union='$anyunion' , union_name='$nameofassocation' , union_address='$officeaddress' , union_Area_council='$areacouncil' , union_id_dateIssue='$idissuedate' , union_type_of_id='$typeofid' , union_id_dateExpire='$idexpiredate' , union_issue_authority='$issueauthority' , union_id_no='$idno' , created='$create' , expire_date='$expire_date' , officer='$userid' where app_id='$pid' ";
                $result_data = mysql_query($query_data);
                $count_entry = mysql_affected_rows();

                return $count_entry;
            } else {
                // Error Message here
            }
        }

        if ($operation == 'new') {
            $statecode = $_SESSION[statecode_sess];
            $create = date('Y-m-d h:i:sa');
            //$dday=365;
            $add = strtotime('+365 days');
            $expire_date = date('Y-m-d', $add);
            $query_data = "insert into rcc_tbl (app_id,trans_id,owner_surname,owner_othername,owner_dob,owner_gender,owner_blood_group,owner_lang,owner_marital_status,owner_Nationality,owner_gsm,owner_address,operator_surname,operator_othername,operator_dob,operator_gender,operator_blood_group,operator_lang,operator_nationality,operator_marital_status,operator_gsm,operator_address,operator_nextofkin_name,operator_nextofkin_address,operator_nextofkin_mobileno,operator_nextofkin_email,cycle_make,cycle_type,idMark,idMarkYes,cycle_chassis_number,cycle_engine_number,cycle_Colour,belong_to_union,union_name,union_address,union_Area_council,union_id_dateIssue, union_type_of_id,union_id_dateExpire,union_issue_authority,union_id_no,created,expire_date,officer) values('$pid','$transid','$osname','$ooname','$odob','$ogender','$obloodgroup','$olangSpoken','$omaritalstatus','$onationality','$$omobileno','$oaddress','$opsurname','$oponame','$opdob','$opgender','$opbloodg','$oplangspoken','$opnationality','$opmaritalstatus','$opmobileno','$opaddress','$kinfullname','$kinaddress','$kinmobileno','$kinemail','$make','$type','$idnetificationmark','$stateno','$chessisno','$engineeno','$colour','$anyunion','$nameofassocation','$officeaddress','$areacouncil','$idissuedate','$typeofid','$idexpiredate','$issueauthority','$idno','$create','$expire_date','$userid')";
            // echo $query_data;
            $result_data = mysql_query($query_data) or die(mysql_error());
            $count_entry = mysql_affected_rows();

            return $count_entry;
        }
    }

    public function logs($text)
    {
        $current_date = date('Y_m_d');
    $logfile = 'logger';
    if (!file_exists($logfile)) {
       mkdir('logger');
    }

        $success =  $current_date.' by '."nursing".' --- using  '.$_SERVER['REMOTE_ADDR'].' -- '.$text."\r\n";

        file_put_contents('logger/'.$current_date.'.txt', $success.PHP_EOL , FILE_APPEND | LOCK_EX);
    }

    public function paddZeros($id, $length)
    {
        $data = '';
        $zeros = '';
        $rem_len = $length - strlen($id);

        if ($rem_len > 0) {
            for ($i = 0; $i < $rem_len; ++$i) {
                $zeros .= '0';
            }
            $data = $zeros.$id;
        } else {
            $data = $id;
        }

        return $data;
    }

    ///////////////////////////////
    public function getnextid($tablename)
    {
        //require_once("../../Copy of acomoran/lib/connect.php");
        $id = 0;
        $query = "update gendata set table_id=table_id+1 where table_name= '$tablename'";

        $resultid = mysql_query($query);
        $numrows = mysql_affected_rows();
        if ($numrows == 0) {
            $query_ins = "insert into gendata values ('$tablename', 1)";
            //echo $query_ins;
            $result_ins = mysql_query($query_ins);
            $numrows = mysql_affected_rows();
        }
        // Get the new id
        $query_sel = "select table_id from gendata where table_name= '$tablename'";
        //echo $query;
        $result_sel = mysql_query($query_sel);
        $numrows_sel = mysql_num_rows($result_sel);
        if ($numrows_sel == 1) {
            $row = mysql_fetch_array($result_sel);
            $id = $row['table_id'];

            //result count when it reaches
            if ($id > 99999998) {
                $query = "update gendata set table_id=table_id+1 where table_name= '$tablename'";
                //echo $query;
                $resultid = mysql_query($query);
            }
        }

        return $id;
    }

    //////////////////////////////////////////
    public function getuniqueid($y, $m, $d)
    {
        $month_year = array('01' => '025',
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
                            '12' => '890', );
        $year = array('2009' => '111',
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
                    '2035' => '248', );

        $day = array('01' => '50',
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
                    '31' => '45', );

        $unique_id = $year[$y].$month_year[$m].$day[$d];

        return $unique_id;
    }

    //////////////////////////////////////////
    public function getuniqueid1($y, $m, $d)
    {
        $month_year = array('01' => '25',
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
                            '12' => '90', );
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
                    '2040' => '236', );

        $day = array('01' => '50',
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
                    '31' => '45', );

        $unique_id = $year[$y].$day[$d];

        return $unique_id;
    }

    //////////////////////////////////////////
    public function doMenu($menu_id, $menu_name, $menu_url, $parent_menu, $menu_level, $parent_menu2)
    {
        $count_entry = 0;
        $query = "select * from menu  where menu_id='$menu_id'";
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows >= 1) {
            $query_data = "update menu set menu_name='$menu_name', menu_url='$menu_url', parent_id='$parent_menu',  parent_id2='$parent_menu2', menu_level='$menu_level' where menu_id='$menu_id' ";
            //echo $query_data;
            $result_data = mysql_query($query_data);
            $count_entry = mysql_affected_rows();
        } else {
            $sql = "select * from menu  where menu_name='$menu_name'";
            if ($res = mysql_query($sql)) {
                if (mysql_num_rows($res) >= 1) {
                    $count_entry = -9;
                } elseif (mysql_num_rows == 0) {
                    $query_data = "insert into menu (menu_id,menu_name,menu_url,parent_id,parent_id2,menu_level,created) values( '$menu_id','$menu_name','$menu_url','$parent_menu','$parent_menu2','$menu_level',now())";
                    //echo $query_data;
                    $result_data = mysql_query($query_data);
                    $count_entry = mysql_affected_rows();
                } else {
                    $count_entry = -9;
                }
            }
        }

        return $count_entry;
    }

    /////////////////////////////////////////////////////////
    public function getmenu($opt)
    {
        $filter = '';
        $options = "<option value='#'>::: Select Menu Option ::: </option>";
        if ($opt != '') {
            $filter = " and menu_id='".$opt."' "; //" username='$username' and password='$password' ";
        }
        $filter .= ' order by menu_name ';
        $dbobject = new dbobject();
        $user_role_session = $_SESSION['sonm_role_id'];
        //$filter_role_id = $dbobject->getitemlabel('parameter','parameter_name','admin_code','parameter_value');
        //$filter_menu_id = $dbobject->getitemlabelmenu('parameter','parameter_name','admin_menu_code','parameter_value');
        //$filteradmin = ($user_role_session == $filter_role_id)?"":" and menu_id not in (".$filter_menu_id.")";
        $query = 'select distinct menu_id, menu_name from menu where 1=1 '.$filter;
        //echo $query;
        $result = mysql_query($query);
        $numrows = @mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                //echo $row['country_code'];
                if ($opt == $row['menu_id']) {
                    $filter = 'selected';
                }
                //echo ($opt=='$row["country_code"]'?'selected':'None');
                $options = $options."<option value='$row[menu_id]' $filter >$row[menu_name]</option>";
                $filter = '';
            }
        }

        return $options;
    }

    /////////////////////////////////
    public function getexistrole($opt)
    {
        $filter = '';
        $user_role_session = $_SESSION['sonm_role_id'];
        //$options = "<option value='#'>::: Select Menu Option ::: </option>";
        if ($opt != '') {
            $filter = "where menu_id='".$opt."' "; //" username='$username' and password='$password' ";
        }
        $query = 'select role_id, role_name from role where role_id in (select role_id from menugroup   '.$filter.") and role_id not in(select parameter_value from parameter where parameter_name='$user_role_session' )";
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                //echo $row['country_code'];
                //if($opt==$row['role_id']) $filter='selected';
                //echo ($opt=='$row["country_code"]'?'selected':'None');
                $options = $options."<option value='$row[role_id]' $filter >$row[role_name]</option>";
                $filter = '';
            }
        }

        return $options;
    }

    ///////////////////////////////////////////
    public function getnonexistrole($opt)
    {
        $filter = '';
        $user_role_session = $_SESSION['sonm_role_id'];
        //$options = "<option value='#'>::: Select Menu Option ::: </option>";
        if ($opt != '') {
            $filter = "where menu_id='".$opt."' "; //" username='$username' and password='$password' ";
        }
        $query = 'select role_id, role_name from role where role_id not in (select role_id from menugroup   '.$filter.") and role_id not in(select parameter_value from parameter where parameter_name='$user_role_session' )";

        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                //echo $row['country_code'];
                //if($opt==$row['role_id']) $filter='selected';
                //echo ($opt=='$row["country_code"]'?'selected':'None');
                $options = $options."<option value='$row[role_id]' $filter >$row[role_name]</option>";
                $filter = '';
            }
        }

        return $options;
    }

    public function doMenuGroup($menu_id, $exist_role)
    {
        //$comp_id = $_SESSION['sonm_comp_id'];
        $count_entry = 0;
        $exist_role_arr = explode(',', $exist_role);
        $role_id = '';
        for ($i = 0; $i < count($exist_role_arr); ++$i) {
            $role_id = $role_id."'".$exist_role_arr[$i]."', ";
        }
        $role_id = substr($role_id, 0, (strlen($role_id) - 2));
        $query_data = "delete from menugroup where role_id not in ($role_id, 001) and menu_id='$menu_id' ";
        //echo $query_data.'<br>';
        $result_data = mysql_query($query_data);
        $count_entry += mysql_affected_rows();
        for ($i = 0; $i < count($exist_role_arr); ++$i) {
            $query_data_i = "insert into menugroup values ('$exist_role_arr[$i]','$menu_id')";

            $result_data_i = mysql_query($query_data_i);
            $count_entry += mysql_affected_rows();
        }

        return $count_entry;
    }

    ////////////////////////

    public function doAddLotToConsult($user_id, $exist_lots)
    {
        $count_entry = 0;
        $exist_lots_arr = explode(',', $exist_lots);

        for ($i = 0; $i < count($exist_lots_arr); ++$i) {
            $query_data_i = "insert into consult_in_slottb values ('$user_id','$exist_lots_arr[$i]')";
            //echo " Yes : ".$exist_lots_arr[$i];
            $result_data_i = mysql_query($query_data_i);
            $count_entry += mysql_affected_rows();
        }

        return $count_entry;
    }

    //http://www.mobbow.com/post_sms.php?username=fleetmanager&password=123456&sms_id=112554&sms_to=2347031242507&sms_from=YOUR MOBOW ACCOUNT &sms_message=YOUR MESSAGE HERESent on:TueFrom:Ese Kelvin Uvbiekpahor1 = successful2 = failure in processing3 = Insufficient SMS Unit4 = sms account not setup5 = Invalid UserFrom:Ese Kelvin Uvbiekpahorpublic
    public function sendSMS($rrr_id, $amount)
    {
        $customerId = $this->getitemlabel('demand_notictb', 'rrrId', $rrr_id, 'customerId');
        $phone_no = $this->getitemlabel('business_organisationtb', 'organisation_id', $customerId, 'phone_number');

        $url = 'http://www.mobbow.com/post_sms.php';
        $username = 'erexadmin';
        $password = 'Access123456';
        $sms_id = date('ymdhis');
        $sms_from = 'Kogi eRex';
        $sms_to = '234'.substr($phone_no, '1');
        $sms_message = "We received $amount being full payment for your 2018 environmental levy. Trans ID BT0000000002. Join GYB to move Kogi state forward. visit sonm.com";

        $data = 'username='.$username.'&password='.$password.'&sms_id='.$sms_id.'&sms_to='.$sms_to.'&sms_from='.$sms_from.'&sms_message='.$sms_message;
        $curl = curl_init();
        curl_setopt_array($curl, array(
                        CURLOPT_URL => $url,
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => 2,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POSTFIELDS => $data,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return 'cURL Error #:'.$err;
        } else {
            return $response;
        }
    }

    public function sendSMSdemandNotice($phone_no)
    {
        $url = 'http://www.mobbow.com/post_sms.php';
        $username = 'erexadmin';
        $password = 'Access123456';
        $sms_id = date('ymdhis');
        $sms_from = 'Kogi eRex';
        $sms_to = '234'.substr($phone_no, '1');

        $sms_message = 'Your demand notice for '.date('Y').' environmental levy is ready. Visit sonm.com or call 07057805080 for pick up. Join hands with GYB to move Kogi state forward.';
        $data = 'username='.$username.'&password='.$password.'&sms_id='.$sms_id.'&sms_to='.$sms_to.'&sms_from='.$sms_from.'&sms_message='.$sms_message;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_CUSTOMREQUEST => 'POST',
                ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return 'cURL Error #:'.$err;
        } else {
            return $response;
        }
    }

    public function sendSMSCeatedBussines($phone_no)
    {
        $url = 'http://www.mobbow.com/post_sms.php';
        $username = 'erexadmin';
        $password = 'Access123456';
        $sms_id = date('ymdhis');
        $sms_from = 'Kogi eRex';
        $sms_to = '234'.substr($phone_no, '1');
        $sms_message = 'Please visit sonm.com to request for your '.date('Y').' demand notice as your payment will be due on the 20-06-2018. Join hands with GYB to move Kogi state forward.';
        $data = 'username='.$username.'&password='.$password.'&sms_id='.$sms_id.'&sms_to='.$sms_to.'&sms_from='.$sms_from.'&sms_message='.$sms_message;
        $curl = curl_init();
        curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_CUSTOMREQUEST => 'POST',
                    ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return 'cURL Error #:'.$err;
        } else {
            return $response;
        }
    }

    public function doRemitaRrr($customerId, $productId_id, $rrrId, $statusCode, $statusMsg, $amount, $phoneno)
    {
        $count_entry = 0;
        $year = @date('Y');
        $created = @date('Y-m-d');
        $consultance_id = $_SESSION['sonm_user_id'];
        $query_data = "insert into demand_notictb(customerId,sector_id,trans_id,rrrId,statusCode,statusMsg,year,amount,created,consultance_id,stateCode) values ('$customerId','$_SESSION[sector_id]','$productId_id','$rrrId','$statusCode','$statusMsg','$year','$amount','$created','$consultance_id','$_SESSION[statecode_sess]')";

        $result_data_i = mysql_query($query_data) or die('Failed to update site table. Mysql returned the following:<br><br>'.mysql_error());
        $count_entry += mysql_affected_rows();
        //echo "count_entry : ".$count_entry;
        if ($count_entry > 0) {
            //echo "number : ".$phoneno;
            $this->sendSMSdemandNotice($phoneno);
        } else {
            echo 'Mysql Error : '.mysql_error();
        }

        return $count_entry;
    }

    ////////////////////////

    public function doAddToRole($roleid, $userid)
    {
        $count_entry = 0;

        $query_data_i = "insert into user_in_role values ('$roleid','$userid')";

        $result_data_i = mysql_query($query_data_i);
        $count_entry += mysql_affected_rows();

        return $count_entry;
    }

    ////////////////////////

    public function gettableselect($tablename, $field1, $field2, $opt)
    {
        $filter = '';
        $options = "<option value=''>::: please select option ::: </option>";
        $query = "select distinct $field1, $field2 from $tablename  ".$filter;
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                //echo $row['country_code'];
                if ($opt == $row[$field1]) {
                    $filter = 'selected';
                }
                //echo ($opt=='$row["country_code"]'?'selected':'None');
                $options = $options."<option value='$row[$field1]' $filter >$row[$field2]</option>";
                $filter = '';
            }
        }

        return $options;
    }

    ///////////////////////////////////

    public function gettableselectorder($tablename, $field1, $field2, $opt, $order)
    {
        $filter = '';
        $order_by = '';
        $options = "<option value=''>::: please select option ::: </option>";
        if ($order != '') {
            $order_by = ' order by '.$order;
        }
        $query = "select distinct $field1, $field2 from $tablename  ".$filter.$order_by;
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                //echo $row['country_code'];
                if ($opt == $row[$field1]) {
                    $filter = 'selected';
                }
                //echo ($opt=='$row["country_code"]'?'selected':'None');
                $options = $options."<option value='$row[$field1]' $filter >$row[$field2]</option>";
                $filter = '';
            }
        }

        return $options;
    }

    /////////////////////////////////////
    public function getdataselect($sql)
    {
        $filter = '';
        $options = "<option value=''>::: please select option ::: </option>";
        //$query = "select distinct $field1, $field2 from $tablename  ".$filter;
        //echo $sql;
        $result = mysql_query($sql);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                $options = $options."<option value='$row[0]' $filter >$row[1]</option>";
                $filter = '';
            }
        }

        return $options;
    }

    public function getTblField($tablename, $field1, $field2, $field3)
    {
        $query = "select distinct $field1 from $tablename  where $field2='$field3'";
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            $row = mysql_fetch_array($result);
            $options = $row[$field1];
        }

        return $options;
    }

    public function getTblItemList($tablename, $field1)
    {
        $options = "<option value=''>::: please select option ::: </option>";
        $query = "select distinct $field1 from $tablename";
        //echo $query;
        $result = mysql_query($query);
        while ($row = mysql_fetch_array($result)) {
            $options .= "<option value='$row[$field1]'>$row[$field1]</option>";
        }

        return $options;
    }

    public function getFormInput($tablename, $field2, $field3, $field4, $field5)
    {
        $query = "select * from $tablename  where $field2='$field3' and $field4='$field5'";
        //echo $query;
        $result = mysql_query($query);
        //$numrows = mysql_num_rows($result);
        /*while($row = mysql_fetch_array($result)){
            $options .= "<input type='checkbox' name='<?php echo $row[$field1]; ?>' id='<?php echo $row[$field1]; ?>'> ".$row[$field]."  &nbsp;&nbsp;&nbsp;&nbsp;".$row[$field1]."<br /><hr></hr>";
        }*/
        return $result;
    }

    public function doPasswordChangeExp($username, $user_password, $new_expdate)
    {
        $desencrypt = new DESEncryption();
        $count_entry = 0;
        $key = $username;
        $cipher_password = $desencrypt->des($key, $user_password, 1, 0, null, null);
        $str_cipher_password = $desencrypt->stringToHex($cipher_password);
        $query_data = "update userdata set password='$str_cipher_password', pass_dateexpire='$new_expdate' where username= '$username'";
        //echo $query_data;
        $result_data = mysql_query($query_data);
        $count_entry = mysql_affected_rows();

        return $count_entry;
    }

    ///////////////////////////////
    // Do password change on logon
    public function doPasswordChangeLogon($username, $user_password)
    {
        $desencrypt = new DESEncryption();
        $count_entry = 0;
        $key = $username;
        $cipher_password = $desencrypt->des($key, $user_password, 1, 0, null, null);
        $str_cipher_password = $desencrypt->stringToHex($cipher_password);
        $query_data = "update userdata set password='$str_cipher_password', passchg_logon='0' where username= '$username'";
        //echo $query_data;
        $result_data = mysql_query($query_data);
        $count_entry = mysql_affected_rows();

        return $count_entry;
    }

    public function getparameter($opt, $parameter_id, $parameter_table, $parameter_col, $val1)
    {
        $filter = '';
        $options = "<option value=''>::: Select ::: </option>";
        /*
        if($opt!= ""){
        $filter = "where menu_id='".$opt."' and parent_id='#' "; //" username='$username' and password='$password' ";
        }else{
        */$filter1 = '';
        if ($parameter_id != '') {
            $filter1 = 'and  '.$parameter_col." = '$parameter_id' ";
        }
        $filter = ' where 1=1 ';
        //}
        $query = 'select * from '.$parameter_table.$filter.$filter1;
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        $filter = '';
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                //echo $row['country_code'];
                if ($opt == $row[$val1]) {
                    $filter = 'selected';
                }
                //echo ($opt=='$row["country_code"]'?'selected':'None');
                $options = $options."<option value='$row[$val1]' $filter >$row[$val1]</option>";
                $filter = '';
            }
        }

        return $options;
    }

    public function getMount($m)
    {
        $month_year = array('01' => 'January',
                            '02' => 'February',
                            '03' => 'March',
                            '04' => 'April',
                            '05' => 'May',
                            '06' => 'June',
                            '07' => 'July',
                            '08' => 'August',
                            '09' => 'September',
                            '10' => 'October',
                            '11' => 'November',
                            '12' => 'December', );

        $unique_id = $month_year[$m];

        return $unique_id;
    }

    public function getuniqueid2()
    {
        $month_year = array('01' => '025',
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
                                                    '12' => '890', );

        $year = array('2009' => '111',
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
                    '2035' => '248', );

        $day = array('01' => '50',
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
                    '31' => '45', );
        //////////////--------> get 2day's date
        $today_date = @date('Y-m-d');
        $date_arr = explode('-', $today_date);
        $unique_id = $year[$date_arr[0]].$month_year[$date_arr[1]].$day[$date_arr[2]];

        return $unique_id;
    }

    ////////////////////////////////////////////////////////////////////////////
    //////////////////////////Beginning of Isaiah///////////////////////////////
    public function getrecordsetArr($tablename, $table_col_arr, $table_val_arr)
    {
        $where_clause = ' ';
        for ($i = 0; $i < count($table_col_arr); ++$i) {
            $where_clause .= $table_col_arr[$i]."='".$table_val_arr[$i]."' and ";
        }

        $where_clause = rtrim($where_clause, ' and ');
        //echo 'country code : '.$countrycode;
        $label = '';
        $table_filter = ' where '.$where_clause;

        $query = 'select * from '.$tablename.$table_filter;
        //echo $query;
        $result = mysql_query($query);

        return $result;
    }

    public function getrecordsetArrLim($tablename, $table_col_arr, $table_val_arr, $limval, $orderby_arr, $orderdir)
    {
        $where_clause = ' ';
        for ($i = 0; $i < count($table_col_arr); ++$i) {
            $where_clause .= $table_col_arr[$i]."='".$table_val_arr[$i]."' and ";
        }
        $table_order = '';
        if ($orderby_arr != '') {
            for ($i = 0; $i < count($orderby_arr); ++$i) {
                $orderby_str .= $orderby_arr[$i].', ';
            }

            $orderby_str = rtrim($orderby_str, ',');
            $table_order = ' ORDERBY '.$orderby_str.' '.$orderdir;
        }
        $where_clause = rtrim($where_clause, ' and ');
        //echo 'country code : '.$countrycode;
        $label = '';
        $table_filter = ' where '.$where_clause.$table_order.' LIMIT '.$limval;

        $query = 'select * from '.$tablename.$table_filter;
        //echo $query;
        $result = mysql_query($query);

        return $result;
    }

    public function getTableSelectArr($tablename, $selarr, $whrarr, $whrvalarr, $order, $orderdir, $opt, $initOpt)
    {
        $filter = $opt;
        $selectVar = ' ';
        $optDisplayName ="";
        $whereClause = ' where ';
        for ($i = 0; $i < count($selarr); ++$i) {
            $selectVar .= $selarr[$i].', ';
            if ($i == 0) {
                $optDisplayVal = $selarr[$i];
            } else {
                $optDisplayName .= $row[$selarr[$i]];
            }
        }
        $selectVar = rtrim($selectVar, ', ');

        for ($i = 0; $i < count($whrarr); ++$i) {
            $whereClause .= $whrarr[$i]."='".$whrvalarr[$i]."' and ";
        }

        $whereClause = rtrim($whereClause, ' and ');
        if ($order != '') {
            if ($orderdir == '') {
                $oderby = 'order by '.$order.' asc';
            } else {
                $oderby = 'order by '.$order.' '.$orderdir;
            }
        } else {
            $oderby = '';
        }
        $options = "<option value='#'>::: Please Select ".$initOpt.' :::</option>';
        $query = "select distinct $selectVar from $tablename ".$whereClause.$oderby;
        //echo $query.'-'.$opt;
        file_put_contents("hello1.txt",$query);
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                for ($j = 0; $j < count($selarr); ++$j) {
                    if ($j > 0) {
                        $optDisplayName .= $row[$selarr[$j]].' ';
                    }
                }
                if ($opt == $row[$optDisplayVal]) {
                    $filter = 'selected';
                }
                //echo ($opt=='$row["country_code"]'?'selected':'None');
                $options = $options."<option value='$row[$optDisplayVal]' $filter >$optDisplayName</option>";
                $filter = '';
                $optDisplayName = '';
                //echo 'yes'.$optDisplayName;
                //echo $row[$field1];
            }
        }

        return $options;
    }

    public function doDbTblUpdate($tbl, $setFieldArr, $setFieldValArr, $whrFieldArr, $whrFieldValArr)
    { $setClause = "";
        $whrClause ="";
        if (count($setFieldArr) == count($setFieldValArr) && count($whrFieldArr) == count($whrFieldValArr)) {
            ////////// set clause starts here////////////////////////////////
            for ($i = 0; $i < count($setFieldArr); ++$i) {
                $setClause .= $setFieldArr[$i]."='".$setFieldValArr[$i]."', ";
            }
            $setClause = rtrim($setClause, ', ');
            //echo $setClause;
            /////////////////////////////////////////////////////////////////
            ///////////////where clause starts here/////////////////////////
            for ($j = 0; $j < count($whrFieldArr); ++$j) {
                $whrClause .= $whrFieldArr[$j]."='".$whrFieldValArr[$j]."' AND ";
            }
            $whrClause = rtrim($whrClause, ' AND ');
            // echo $whrClause;
            ///////////////////////////////////////////////////////////////
            ////////////the complete query/////////////////////////////////
            $query = 'UPDATE '.$tbl.' SET '.$setClause.' WHERE '.$whrClause;
            //echo $query;
            $result = mysql_query($query);
            if (mysql_affected_rows() >= 0) {
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

    public function getItemLabelArr($tablename, $table_col_arr, $table_val_arr, $ret_val_arr)
    {
        $label = '';
        $selectClause ="";
        $whrClause ="";
        /////////////////////////////////////////////////////////////////
        ////////// select clause starts here////////////////////////////////
        if ($ret_val_arr == '*') {
            $qquery = "SHOW COLUMNS FROM $tablename ";
            //echo $qquery;
            $result = mysql_query($qquery);
            echo mysql_error();
            while ($roww = mysql_fetch_array($result)) {
                $selectClause .= $roww[0].', ';
                $ret_val[] = $roww[0];
            }
            $retCount = $ret_val;
            $selectClause = rtrim($selectClause, ', ');
        } else {
            for ($i = 0; $i < count($ret_val_arr); ++$i) {
                $selectClause .= $ret_val_arr[$i].', ';
            }
            $selectClause = rtrim($selectClause, ', ');
            $retCount = $ret_val_arr;
            //echo $setClause;
        }
        /////////////////////////////////////////////////////////////////
        ///////////////where clause starts here/////////////////////////
        for ($j = 0; $j < count($table_col_arr); ++$j) {
            $whrClause .= ' AND '.$table_col_arr[$j]."='".$table_val_arr[$j]."' ";
        }
        $whrClause = rtrim($whrClause, ', ');
        /////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////
        $table_filter = ' where 1=1 '.$whrClause;

        $query = 'select '.$selectClause.' from '.$tablename.$table_filter;
        //echo $query;
        file_put_contents("hello.txt",$query);
        $result = mysql_query($query);
        $numrows = mysql_affected_rows();
        if ($numrows > 0) {
            $retValue = mysql_fetch_assoc($result);
        }

        return $retValue;
    }

    public function updateApplince($reg_id, $response_code, $flex_id)
    {
        $str = "UPDATE applicant_account_setup SET reg_status = '$response_code',rrr=$flex_id,statusCode='onePay' where reg_id='$reg_id'";
        $result = mysql_query($str); // or die(mysql_error());
    }

    public function doDbTblInsert($tbl, $setFieldArr, $setFieldValArr)
    { $setClause ="";
        if (count($setFieldArr) == count($setFieldValArr)) {
            ////////// set clause starts here////////////////////////////////
            for ($i = 0; $i < count($setFieldArr); ++$i) {
                $setClause .= $setFieldArr[$i]."='".$setFieldValArr[$i]."', ";
            }
            $setClause = rtrim($setClause, ', ');
            //echo $setClause;
            /////////////////////////////////////////////////////////////////
            ////////////the complete query/////////////////////////////////
            $query = 'INSERT INTO '.$tbl.' SET '.$setClause;
            //echo $query;
            if ($result = mysql_query($query)) {
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

        function getRealIp()
        {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  //check ip from share internet
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            return $ip;
        }
    }

    public function sendmail_old($to, $consultancy)
    {
        // PREPARE THE BODY OF THE MESSAGE

        // $message = '<html><body>';
        // $message .= '<img src="http://css-tricks.com/examples/WebsiteChangeRequestForm/images/wcrf-header.png" alt="Website Change Request" />';
        // $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
        // $message .= "<tr style='background: #eee;'><td><strong>Name:</strong> </td><td>" . " 454353 45 525 " . "</td></tr>";
        // $message .= "<tr><td><strong>Email:</strong> </td><td>" . 22222222222222222 . "</td></tr>";
        // $message .= "<tr><td><strong>Type of Change:</strong> </td><td>" . " 22222222222222 " . "</td></tr>";
        // $message .= "<tr><td><strong>Urgency:</strong> </td><td>" . 4552525 . "</td></tr>";
        // $message .= "<tr><td><strong>URL To Change (main):</strong> </td><td>" . 365664363 . "</td></tr>";

        // $message .= "<tr><td><strong>NEW Content:</strong> </td><td>" . "ade" . "</td></tr>";
        // $message .= "</table>";
        // $message .= "</body></html>";

        $message = 'Dear '.$consultancy;
        $message .= 'You have been successfully created on sonm.com.';
        $message .= 'Username: ';
        $message .= 'Password: ';
        $message .= 'Role: ';
        $message .= '<br/>';
        $message .= 'For more enquiry visit sonm.com or call 070.....';

        $message .= 'Erex; solutions based on IT';

        //  MAKE SURE THE "FROM" EMAIL ADDRESS DOESN'T HAVE ANY NASTY STUFF IN IT

        // $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i";
        // if (preg_match($pattern, trim(strip_tags($_POST['req-email'])))) {
        // 	$cleanedFrom = trim(strip_tags($_POST['req-email']));
        // } else {
        // 	return "The email address you entered was invalid. Please try again!";
        // }

        //   CHANGE THE BELOW VARIABLES TO YOUR NEEDS

        //$to = 'JUNKKKKK@gmail.com';

        $subject = 'sonm ';

        //$headers = "From: adeniyijamesa@gmail.com\r\n";
        //$headers .= "Reply-To: adeniyijamesa@gmail.com\r\n";
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        if (@mail($to, $subject, $message, $headers)) {
            echo 'Your message has been sent.';
        } else {
            echo 'There was a problem sending the email.';
        }

        echo 'Enter Email function ';
    }

    public function sendMail_global($address, $subject, $message)
    {
        $headers = "From: sonm_user@sonm.com \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $headers .= "X-Priority: 1\r\n";
        if (@mail($address, $subject, $message, $headers)) {
            return true;
        } else {
            return false;
        }
    }

    public function EmailValidation($email)
    {
        $email = htmlspecialchars(stripslashes(strip_tags($email))); //parse unnecessary characters to prevent exploits
        if (eregi('[a-z||0-9]@[a-z||0-9].[a-z]', $email)) {
            //checks to make sure the email address is in a valid format
            $domain = explode('@', $email); //get the domain name
            if (@fsockopen($domain[1], 80, $errno, $errstr, 3)) {
                //if the connection can be established, the email address is probably valid
                //echo "Domain Name is valid ";
                return true;
            } else {
                // echo "Con not a email domian";
                return false; //if a connection cannot be established return false
            }

            return false; //if email address is an invalid format return false
        }
    }

    public function encrypt_password($username, $userpassword)
    {
        $desencrypt = new DESEncryption();
        $key = $username;
        $cipher_password = $desencrypt->des($key, $userpassword, 1, 0, null, null);
        $str_cipher_password = $desencrypt->stringToHex($cipher_password);

        return $str_cipher_password;
    }

    public function decrypt_password($username, $pass_crypt)
    {
        $key = $username;
        $desencrypt = new DESEncryption();
        $cipher_password = $desencrypt->hexToString($pass_crypt);
        $plain_pass = $desencrypt->des($key, $cipher_password, 0);

        return $plain_pass;
    }

    public function DecryptData($key, $password)
    {
        $desencrypt = new DESEncryption();
        $mmm = $desencrypt->hexToString($password);

        return strip_tags($desencrypt->des($key, $mmm, 0, 0, null, null));
    }

    public function EncryptData($username, $userpassword)
    {
        $desencrypt = new DESEncryption();
        $key = $username;
        $cipher_password = $desencrypt->des($key, $userpassword, 1, 0, null, null);
        $str_cipher_password = $desencrypt->stringToHex($cipher_password);

        return $str_cipher_password;
    }

    public function randomcode()
    {
        $var = 'Aa0BbCDc1EdFGe2HfIJj3KhLMi4NjOPk5QlRSm6TnUVo7WpXYq8Zrabs9ctdefghijkmnopqrstuvwxyz';
        srand((float) microtime() * 10000000);
        $i = 0;
        $code = '';
        while ($i <= 31) {
            $num = rand() % 33;
            $tmp = substr($var, $num, 1);
            $code = $code.$tmp;
            ++$i;
        }

        return $code;
    }

     public function resendMail($user)
    {
        $dbobject = new dbobject();
        // AND program ='100'
        //reg_id, session, email, surname, othernaame, phone_number, program, userpassword, created, rrr, reg_status, rrr_status, linkCode, date_of_birth, gender, marital_status, Nationality, state_of_origin, local_Gov_Area, District_word, tribe, religion, postal_address, exam_center, ip, gname, gaddress, statusCode, pay_status, pbirth, passport
        $message = $dbobject->reg_email_template($dbobject->getItemLabel("applicant_account_setup", "email", $user, "surname" ), $user, $dbobject->getItemLabel("applicant_account_setup", "email", $user, "userpassword" ), $dbobject->getItemLabel("applicant_account_setup", "email", $user, "linkCode" ));
        $subject = 'SONM Application';
                // $emaail_resp =   $dbobject->sendMail_global($email, $subject, $message);
                //  if($emaail_resp){
                //      // echo "Email sent ";
                //  }else{
                //      echo "Email not sent ";
                //  }
                // echo $message;
                $label = '44';
             $result =   $dbobject->send_mail_online($user, $subject, $message);
             if($result !=""){
               return array(
            'message' => 'Done',
            'code' => '200'
        );
          }else{
            return array(

            'message' => 'failed',
            'code' => '400'
        );
          }
              //$dbobject->sendMail_new($email, $subject, $message);

    }
}

    class Template
    {
        public function get_contents($templateName, $variables)
        {
            $template = file_get_contents($templateName);

            foreach ($variables as $key => $value) {
                $template = str_replace('{{ '.$key.' }}', $value, $template);
            }

            return $template;
        }
    }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//End Class
