<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_models extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	
	function getselecteddata($fileds,$table,$id){
		if(!empty($id)){
			$this->db->select($fileds);
			$this->db->from($table);
			$this->db->where($id);
			$query = $this->db->get();
			//print_r($this->db->last_query());die;
			 if ($query->num_rows() > 0) {
				$return = $query->row_array();
				return $return;
			} else {
				return false;
			}
		}else{
			return false;
		}
	}

	public function mysqlResult($table,$field,$condition,$orderby,$order,$limit,$offset)
	{
		$this->db->select($field);

		if($condition)

		   $this->db->where($condition);

		if(!empty($offset) && !empty($limit))

		   $this->db->limit($limit, $offset);

		if(!empty($orderby) && !empty($order))

		   $this->db->order_by($orderby,$order);
        
		$query = $this->db->get($table);
        //echo $this->db->last_query(); die;
		return ($query->num_rows() > 0)?$query->result():array();

	}
	public function mysqlResultattempt($table,$field,$condition,$orderby,$order,$limit,$offset)
	{

		$this->db->select($field);

		if($condition)
		{
			
		   $this->db->where($condition);
			
		}

		if(!empty($order) && !empty($limit))

		   $this->db->limit($limit, $offset);

		if(!empty($orderby) && !empty($order))

		   $this->db->order_by($orderby,$order);

		$query = $this->db->get($table);

		return ($query->num_rows() > 0)?$query->result():FALSE;

	}


public function mysqlRowArray($table,$field,$condition,$orderby,$order,$limit,$offset)
	{

		$this->db->select($field);

		if($condition)

		   $this->db->where($condition);

		if(!empty($order) && !empty($limit))

		   $this->db->limit($limit, $offset);

		if(!empty($orderby) && !empty($order))

		   $this->db->order_by($orderby,$order);

		$query = $this->db->get($table);

		return ($query->num_rows() > 0)?$query->row_array():FALSE;

	}


public function verify_user_login($param){
		$this->db->select('*');
		$this->db->where($param);
	    $query = $this->db->get('user');
        //echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            $user = $query->row_array();
			if($user['status'] == 'active'){
				return $user = array('email'=>$user['email'],'image' =>$user['user_img'],'phonenumber'=>$user['phonenumber'],'username'=>$user['username'], 'userID'=>$user['id'],'status'=>1,'message'=>'successfully login');
			}else{
				return $error = array('email'=>$user['email'],'image' =>$user['user_img'],'phonenumber'=>$user['phonenumber'],'username'=>$user['username'], 'userID'=>$user['id'],'status'=>0,'message'=>"Status not active.Pls. contact to MAX Customer Care.");
			}	
        } else {
            return $error = array('status'=>0,'username'=>'', 'userID'=>'','message'=>"Email ID / Mobile No. or Password  is Incorrect");	
        }
		
	}


	function update_table($con,$table,$data){
		$this->db->where($con);
        $this->db->update($table,$data);
		//print_r($this->db->last_query());
        if ($this->db->affected_rows()>0) {
            return 1;
        } else {
            return -1;
        }
	}





	function check_emailID($table,$field,$emailId ='',$phonenumber=''){
		$this->db->select($field);
		$this->db->from($table);
		if(!empty($emailId)){
			$this->db->where($emailId);
		}
		if(!empty($phonenumber)){
			$this->db->where($phonenumber);
		}	
		$query = $this->db->get();
		 if ($query->num_rows() > 0) {
			$return = $query->row_array();
			return $return;
		} else {
			return -1;
		}
	}
	
	public function insert_data($tbl,$data)
	{
		if(!empty($data) && is_array($data))
		{
	        $this->db->insert($tbl,$data);

			$insert_id = $this->db->insert_id();
			//echo $this->db->last_query();die;
			$this->db->cache_delete_all();
			if($insert_id)
				return $insert_id;
			else
				return -2;
		}
		else{
			return -1;
		}

   	}


	function register_user($data,$table,$Rtab){
		if(!empty($data) && is_array($data)){
			$this->db->insert($table,$data);
            $result = $this->db->insert_id();
			if($result > 0){
				$temp = array();
				$temp['phonenumber'] = $data['phonenumber'];
				$this->db->insert($Rtab, $temp);
				$resultrank = $this->db->insert_id();
				if($resultrank > 0){
					return $result;
				}else{
					$this->db->where('id', $result);
					$this->db->delete('user'); 
					return -3;
				}
			}else{
				return -2;
			}
		}else{
			return -1;
		}
	}

	function deleteData($con,$table)
	{
		if(!empty($con) && !empty($id))
		{
			$this->db->where($con);
			$res = $this->db->delete('pre_register'); 
			if($res)
			{
				return 1;
			}
			else
			{
				return -1;
			}
		}
		else
		{
			return -2;
		}
	}
	
	function addpromotor($data)
	{
		if($this->db->insert('EmpMaster',$data))
		{
			return true;
		}
		return false;
		
	}
	
	function checknumberexist($number)
	{
		$res = $this->db->get_where('EmpMaster',array('mobile'=>$number));
		if($res->num_rows() > 0)
		{
			return false;
		}else{
			return true;
		}
	}
	
	function deletepromoter($id)
	{
		$this->db->where('id',$id);
		if($this->db->delete('EmpMaster'))
		{
			return true;
		}else{
		return false;
	   }
	}
	
	function addMiles($data)
	{
		$res = $this->db->get_where('miles',array('user_id'=>$data['user_id']));
		if($res->num_rows() > 0)
		{
			$oldmiles = $res->result_array()[0]['miles'];
			$miles = $data['miles']+$oldmiles;
			$this->db->where('user_id',$data['user_id']);
			return $this->db->update('miles',array('miles'=>$miles));
		}else{
			return $this->db->insert('miles',$data);
		}
	}
	
	function getMiles($uid)
	{
		if(!empty($uid))
		{
			$this->db->select('miles');
			$res = $this->db->get_where('miles',array('user_id'=>$uid));
			if($res->num_rows() > 0){
				return $res->result_array()[0]['miles'];
			}
			    return 0;
		}else{
			$this->db->select('u.username as name,m.miles');
			$this->db->from('miles m');
			$this->db->join('Max_Users u','m.user_id=u.id','INNER');
			$this->db->order_by('m.miles','DESC');
			$this->db->limit(100);
			$res = $this->db->get();
			return $res->result();
		}
	}
	
	function addToken($token)
	{
		$token_exist = $this->db->get_where('AndroidToken',array('token'=>$token));
		if($token_exist->num_rows() == 0)
		{
			$res = $this->db->insert('AndroidToken',array('token'=>$token));
			if($res)
			{
				return 'Token Added';
			}
		}else{
			return 'Token Exist';
		}
		    return false;
	}
	
	function getToken()
	{
		$this->db->select('token');
		$res = $this->db->get('AndroidToken');
		return $res->result();
	}
	
	public function getExpense($empid='')
	{
		if(!empty($empid))
		{
			$res = $this->db->get_where('empExpense',array('empid'=>$empid));
			return $res->result();
		}else{
			$res = $this->db->get('empExpense');
			return $res->result();
		}
		return false;
	}
	
	public function delExpense($id)
	{
		if(!empty($id))
		{
			$this->db->where('id',$id);
			return $this->db->delete('empExpense');
		}
		return false;
	}
	
	public function updateExpense($data,$id)
	{
		if(!empty($id))
		{
			$this->db->where('id',$id);
			return $this->db->update('empExpense',$data);
		}
		    return false;
	}
	
	public function updateUserStatus($data,$phonenumber)
	{
		if(!empty($data && $phonenumber))
		{
			$this->db->where('phonenumber',$phonenumber);
			return $this->db->update('Max_Users',$data);
		}
		    return false;
	}
	
	public function addkioskmedia($media)
	{
		if($this->db->insert('KioskMedia',$media))
		{
			return true;
		}
		return false;
	}
	
	public function getkioskmedia()
	{
		$res = $this->db->get('KioskMedia');
	    return $res->result();
	}
	
	public function delkioskmedia($id)
	{
		if(!empty($id))
		{
			$this->db->where('id',$id);
			$this->db->delete('KioskMedia');
			return true;
		}
	}
	
	public function updatekioskmedia($id,$data)
	{
		if(!empty($id) && $data)
		{
			$this->db->where('id',$id);
			$this->db->update('KioskMedia',$data);
			return true;
		}
	}
	
	public function addBilldetail($media)
	{
		if($this->db->insert('BillDetail',$media))
		{
			return true;
		}
		return false;
	}
	
	public function getBilldetail()
	{
		$res = $this->db->get('BillDetail');
	    return $res->result();
	}
	
	public function delBilldetail($id)
	{
		if(!empty($id))
		{
			$this->db->where('billid',$id);
			$this->db->delete('BillDetail');
			return true;
		}
	}
	
	public function updateBilldetail($id,$data)
	{
		if(!empty($id && $data))
		{
			$this->db->where('billid',$id);
			$this->db->update('BillDetail',$data);
			return true;
		}
	}
	
	public function getUserRanking()
	{
		/*
		$this->db->select('kt.*');
		$this->db->from('Trans_Kwik kt');
		$res = $this->db->get();
		*/
		$res = $this->db->query('SELECT kt.uid,u.username,SUM(kt.amount) as amount FROM Trans_Kwik kt INNER JOIN Max_Users u ON u.id = kt.uid WHERE kt.status = "success" GROUP BY uid ORDER BY amount desc');
		return $res->result();
	}

	public function getAirportList()
	{
		$res = $this->db->query('SELECT cityCode, cityName, name, countryCode, countryName FROM airports');
		return $res->result();
	}

}

/* End of file api_model.php */
/* Location: ./application/model/api_model.php */

