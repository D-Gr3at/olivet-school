<?php

class Transaction extends dbobject
{
   public function transactionList($data)
    {
		$table_name    = "transaction_table";
		$primary_key   = "transaction_id";
		$columner = array(
			array( 'db' => 'transaction_id', 'dt' => 0 ),
			array( 'db' => 'source_acct', 'dt' => 1 ),
			array( 'db' => 'destination_acct',  'dt' => 2 ),
			array( 'db' => 'transaction_amount',   'dt' => 3 ),
            array( 'db' => 'response_code',  'dt' => 4,'formatter' => function( $d,$row ) {
						return $d."%";
					} ),
            array( 'db' => 'response_message',  'dt' => 5 ),
            array( 'db' => 'id',     'order_id' => 6, 'formatter' => function( $d,$row ) {
						return "<button class=\"btn btn-info\">Edit</button>";
					}
				),
            array( 'db' => 'created',  'dt' => 7 )
			);
		$filter = "";
		$datatableEngine = new engine();
	
		echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key);
    }
    
    public function saveSplit($data)
    {
        $total = 0;
        foreach($data['church_type'] as $amt)
        {
            $total = $total + $amt;
        }
        if($total != 0)
        {
            if($total == 100)
            {
                $min        = trim($data['min_amt']);
                $max        = trim($data['max_amt']);
                if(!isset($data['infinite']))
                {
                  $validation = $this->minMaxValidation($min,$max);  
                }
                $max = (isset($data['infinite']))?'99000000000000':$max;
                if($validation['response_code'] == 0)
                {
                    $split_code = $this->generateSplitCode();
                    foreach($data['church_type'] as $key=>$value)
                    {
                        $sql = "INSERT INTO splitting (code,min_amt,max_amt,church_type,percentage,created,posted_user) VALUES('$split_code','$min','$max','$key','$value',NOW(),'$_SESSION[username_sess]')";
                        $this->db_query($sql);
                    }
                    return json_encode(array("response_code"=>0,"response_message"=>"Split saved successfully"));
                }else
                {
                    return json_encode($validation);
                }
            }else
            {
                return json_encode(array("response_code"=>714,"response_message"=>"Total percentage must be 100"));
            }
        }else
        {
            return json_encode(array("response_code"=>714,"response_message"=>"Total percentage cannot be zero"));
        }
    }
    
    public function minMaxValidation($min,$max)
    {
        if($min == "" || $max == "")
        {
            return array('response_code'=>74,'response_message'=>'Minimu / maximum amount cannot be empty');
        }
        if($min > $max)
        {
            return array('response_code'=>74,'response_message'=>'Minimum amount cannot be greater than maximum amount');
        }
        if($min == $max)
        {
            return array('response_code'=>74,'response_message'=>'You entered the same amount for both minimum and maximum amount');
        }
        $sql    = "SELECT min_amt,max_amt FROM splitting";
        $result = $this->db_query($sql);
        $count = count($result);
        if($count > 0)
        {
            foreach($result as $row)
            {
                
                    if($row['max_amt'] > $min)
                    {
                        return array('response_code'=>74,'response_message'=>$min.' is between an existing split range of '.$row['min_amt'].' and '.$row['max_amt']);
                    }
            }
        }
        return array('response_code'=>0,'response_message'=>'OK');
    }
    private function generateSplitCode()
    {
        return $this->paddZeros($this->getnextid("splitting"),2);
    }
}