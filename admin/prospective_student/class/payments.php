<?php
Class Payments extends myDbObject {

public function acceptanceRRR($data) {
    $reg_id = $data['reg_id'];
    $rrr = $this->doGetRRR2($reg_id);
    // $rrr = $this->testGetRRR($reg_id);
    if (strlen($rrr) > 11){
        return json_encode(array('response_code'=>0,'response_message'=>$rrr));
    }
    else {
        return json_encode(array('response_code'=>47,'response_message'=>"Unable To generate RRR Please Try again"));
    }
 }

 public function schoolFeesRRR($data){
    $link_code = $data['link_code'];
    $rrr =$this->doGetRRR($link_code);
    if (strlen($rrr) > 11){
        return json_encode(array('response_code'=>0,'response_message'=>$rrr));
    }
    else {
        return json_encode(array('response_code'=>47,'response_message'=>"Unable To generate RRR Please Try again"));
    }
 }

}