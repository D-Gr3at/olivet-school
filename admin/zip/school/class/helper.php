<?php

class Helper extends dbobject
{
   public function getTownSelect($data)
    {
       $filter = $data['filter'];
       if($filter == "lga")
       {
            $sql = "SELECT * FROM towns WHERE lga = '$data[search]'";   
       }elseif($filter == "state")
       {
           $sql = "SELECT * FROM towns WHERE state = '$data[search]'";   
       }
       $result  = $this->db_query($sql);
       $options = "<option value=''>:: SELECT TOWN ::</option>";
       foreach($result as $row)
       {
           $options = $options."<option value='".$row[id]."'>".$row[town_name]."</option>";
       }
       return json_encode(array("response_code"=>0,"response_message"=>$options));
    }
    public function getVillages($data)
    {
        $town_id = $data['town_id'];
        $sql     = "SELECT * FROM village WHERE town_id = '$town_id' ";
        $result  = $this->db_query($sql);
        $options = "<option value=''>:: SELECT VILLAGE ::</option>";
        if(count($result) > 0)
        {
            foreach($result as $row)
            {
                $options .= "<option value='".$row[village_id]."'>".$row[name]."</option>";
            }
            return json_encode(array("response_code"=>0,"response_message"=>$options));
        }else
        {
            return json_encode(array("response_code"=>510,"response_message"=>"<option value=''>No village found</option>"));
        }
        
    }
    public function getLga($data)
    {
        $state  = $data['state'];
        
        $sql    = "SELECT Lga,Lgaid FROM lga WHERE stateid = '$state' order by Lga";
        $result = $this->db_query($sql);
        $output   = "<option value=''>:: ALL LGA ::</option>";
        foreach($result as $row)
        {
            $output.= "<option value='".$row['Lgaid']."'>".$row['Lga']."</option>";
        }
      
//        $church = $this->churchByState(array('state'=>$state,'lga'=>$data['lga']));
        return json_encode(array('state'=>$output));
    }
    public function getKindred($data)
    {
        $village_id = $data['village_id'];
        $sql        = "SELECT * FROM kindred WHERE village_id = '$village_id' ";
        $result     = $this->db_query($sql);
        $options    = "<option value=''>:: SELECT KINDRED ::</option>";
        if(count($result) > 0)
        {
           foreach($result as $row)
            {
                $options .= "<option value='".$row[id]."'>".$row[kindred_name]."</option>";
            }
            return json_encode(array("response_code"=>0,"response_message"=>$options));
        }
        else
        {
            return json_encode(array("response_code"=>0,"response_message"=>"<option value=''>No Kindred found</option>"));
        }
    }
    public function getFamilySurname($data)
    {
        $kindred_id = $data['kindred_id'];
        $sql        = "SELECT * FROM family_name WHERE kindred_id = '$kindred_id' ";
        $result     = $this->db_query($sql);
        $options    = "<option value=''>:: SELECT FAMILY SURNAME ::</option>";
        if(count($result) > 0)
        {
           foreach($result as $row)
            {
                $options .= "<option value='".$row[id]."'>".$row[surname]."</option>";
            }
            return json_encode(array("response_code"=>0,"response_message"=>$options));
        }
        else
        {
            return json_encode(array("response_code"=>0,"response_message"=>"<option value=''>No Surname found</option>"));
        }
    }
    public function getFamily($data)
    {
        $surname = $data['surname'];
        $sql        = "SELECT * FROM family WHERE family_name = '$surname' ";
        $result     = $this->db_query($sql);
        $options    = "<option value=''>:: SELECT FAMILY ::</option>";
        if(count($result) > 0)
        {
           foreach($result as $row)
            {
                $options .= "<option value='".$row[family_id]."'>".$row[family_head]."</option>";
            }
            return json_encode(array("response_code"=>0,"response_message"=>$options));
        }
        else
        {
            return json_encode(array("response_code"=>0,"response_message"=>"<option value=''>No Family found</option>"));
        }
    }
}