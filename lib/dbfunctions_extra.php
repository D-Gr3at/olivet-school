<?php
//main one
@session_start();
///////////////////
/*error_reporting(E_ERROR);*/
require 'dbfunctions.php';
//////////////////////
class myDbObject extends dbobject
{
    private $created;
    private $current_user;

    public function __construct()
    {
        $this->created = date('Y-m-d H:i:s');
        $this->current_user = $_SESSION['sonm_username'];
    }

    public function cleanUpData($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                cleanUpData($value);
            } else {
                $array[$key] = trim(mysql_real_escape_string($value));
            }
        }

        return $array;
    }
    public function pickStation($opt)
    {
        $filter = '';
        $options = '';
        $query = 'select distinct prog_id, program_name from app_programme_setup_tb where 1=1 and status=1';

        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                if ($opt == $row['prog_id']) {
                    $filter = 'selected';
                }
                $options = $options."<option value='$row[prog_id]' $filter >$row[program_name]</option>";
                $filter = '';
            }
        }

        return $options;
    }

    public function loadCountry($opt)
    {
        $filter = '';
        $options = "<option value=''>::: Select a Country ::: </option>";

        $query = 'select distinct id, name from app_countries where 1=1';
        //echo $query;
        $result = mysql_query($query);
        $numrows = mysql_num_rows($result);
        if ($numrows > 0) {
            for ($i = 0; $i < $numrows; ++$i) {
                $row = mysql_fetch_array($result);
                //160
                if ($opt == $row['id']) {
                    $filter = 'selected';
                }
                /* if ($row['id'] =='160') {
                     $filter = 'selected';
                 }*/
                $options = $options."<option value='$row[id]' $filter >$row[name]</option>";
                $filter = '';
            }
        }

        return $options;
    }
    public function validateEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) != false && preg_match('/@.+\./', $email) == 1) {
            return 1;
        } else {
            return 0;
        }
    }

    public function validatePhoneNo($phone_no)
    {
        if (strlen($phone_no) != 11 || is_nan($phone_no) || strpos($phone_no, '0') != 0) {
            return 0;
        } else {
            return 1;
        }
    }

    public function CurlSendUpdated($url, $data)
    {
        $send = curl_init();
        curl_setopt($send, CURLOPT_URL, $url);
        curl_setopt($send, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($send, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($send, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($send, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($send, CURLOPT_POSTREDIR, 3);
        //curl_setopt($send, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($send, CURLOPT_POST, true);
        curl_setopt($send, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($send);

        /*if (!curl_errno($send))
        {
            $info = json_encode(curl_getinfo($send));
            file_put_contents('curl_err.txt',$info);
        }*/
        curl_close($send);

        return $output;
    }

    private function sendMail($address, $subject, $message, $header)
    {
        $headers = 'MIME-Version: 1.0'."\r\n";
        $headers .= 'Content-type:text/html;charset=UTF-8'."\r\n";
        $headers .= $header;

        if (@mail($address, $subject, $message, $headers)) {
            return true;
        } else {
            return false;
        }
    }

    public function sendContactMail($id, $msg, $subject)
    {
        $count = 0;
        if ($subject == '' || $msg == '' || $id == '') {
            $count = -1;	//empty parameters sent
        }
        $ver_rslt = $this->getrecordset('contact_us', 'contact_id', $id);
        $ver_nums = mysql_num_rows($ver_rslt);
        if ($ver_nums > 0) {
            $sql = "UPDATE contact_us SET replied_msg='$msg',replied_by='$this->current_user',replied_date='$this->created',status='1' WHERE contact_id='$id'";
            $rslt = mysql_query($sql);
            $aff = mysql_affected_rows();
            if ($aff > 0) {
                $ct_rslt = $this->getrecordsetArr('contact_us', array('contact_id'), array($id));
                $ct_rows = mysql_fetch_array($ct_rslt);
                $to = $ct_rows['email'];
                $fullname = $ct_rows['fullname'];
                $msgg = '<p>Hello '.ucwords($fullname).','."</p><br/>\r\n";
                $msgg .= '<p>'.$msg.'</p>';
                $domain = $this->getitemlabel('parameter', 'parameter_name', 'official_domain_url', 'parameter_value');
                //file_put_contents('asa.txt',$domain);
                $header = 'From: School Of Nursing and Midwifery, Lafia <noreply@'.$domain."> \r\n";
                //$sent = $this->sendMail($to,$subject,$msgg,$header);
                $url = 'http://accessng.com/remote_mailer.php';
                $data = 'mail_subject='.$subject.'&mail_address='.$to.'&mail_msg='.$msgg;
                $sent = $this->CurlSendUpdated($url, $data);
                if ($sent == 1) {
                    $count = 1;
                }
            }
        } else {
            $count = -2;		//invalid message ID
        }

        return $count;
    }

    public function updateNews($op, $id, $news_body, $news_head, $dept, $status)
    {
        $count = 0;
        if ($news_body == '') {
            $count = -1;
        } elseif ($news_head == '') {
            $count = -2;
        } elseif ($dept == '') {
            $count = -3;
        } elseif ($status == '' || $status == '#') {
            $count = -4;
        } elseif ($op == '') {
            $count = -5;
        } else {
            if ($op == 'new') {
                $sql = "INSERT INTO news_tb SET news_head='$news_head',content='$news_body',poster='$this->current_user',status='$status',department='$dept',created='$this->created'";
                $rslt = mysql_query($sql);
                $nums = mysql_affected_rows();
                if ($nums > 0) {
                    $ssql = 'SELECT id FROM news_tb ORDER BY created DESC LIMIT 1';
                    $rrslt = mysql_query($ssql);
                    $rrow = mysql_fetch_array($rrslt);
                    $id = $rrow['id'];
                    $count = 1;
                }
            } elseif ($op == 'edit') {
                $query = "UPDATE news_tb SET news_head='$news_head',content='$news_body',modified_by='$this->current_user',status='$status',department='$dept',modified='$this->created' WHERE id='$id'";
                $result = mysql_query($query);
                $numrows = mysql_affected_rows();
                if ($numrows > 0) {
                    $count = 1;
                }
            }
        }

        return json_encode(array('id' => $id, 'ct' => $count));
    }

    public function updateSlider($op, $id, $text, $title, $btn_label, $slider_status, $status)
    {
        $count = 0;
        if ($title == '') {
            $count = -1;
        } elseif ($text == '') {
            $count = -2;
        } elseif ($btn_label == '') {
            $count = -3;
        } elseif ($status == '' || $status == '#') {
            $count = -4;
        } elseif ($slider_status == '' || $slider_status == '#') {
            $count = -6;
        } elseif ($op == '') {
            $count = -5;
        } else {
            if ($op == 'new') {
                $sql = "INSERT INTO slider_tb SET msg_head='$title',message='$text',button_label='$btn_label',show_button='$slider_status',posted_user='$this->current_user',status='$status',created='$this->created'";
                $rslt = mysql_query($sql);
                $nums = mysql_affected_rows();
                if ($nums > 0) {
                    $ssql = 'SELECT id FROM slider_tb ORDER BY created DESC LIMIT 1';
                    //file_put_contents('edi.txt',$ssql);
                    $rrslt = mysql_query($ssql);
                    $rrow = mysql_fetch_array($rrslt);
                    $id = $rrow['id'];
                    $count = 1;
                }
            } elseif ($op == 'edit') {
                $query = "UPDATE slider_tb SET msg_head='$title',message='$text',button_label='$btn_label',show_button='$slider_status',modified_by='$this->current_user',status='$status',modified='$this->created' WHERE id='$id'";
                //file_put_contents('edi.txt',$query);
                $result = mysql_query($query);
                $numrows = mysql_affected_rows();
                if ($numrows > 0) {
                    $count = 1;
                }
            }
        }

        return json_encode(array('id' => $id, 'ct' => $count));
    }

    /////////end of class
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//End Class
