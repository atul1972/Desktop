<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class QuizModel extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
    
    
    function delQuiz($quizid)
    {
        $this->db->where(array('id' => $quizid));
        $data = array('status' => 'suspend'); 
        $this->db->update('quiz',$data);

        if ($this->db->affected_rows()>0)
            return true;
        else
            return false;
    }

    function newQuiz($question,$opt1,$opt2,$opt3,$opt4,$ans,$amount,$dateTime,$empId)
    {
        $data = array(
            'empid' => $empId,
            'question' => $question,
            'opt1' => $opt1,
            'opt2' => $opt2,
            'opt3' => $opt3,
            'opt4' => $opt4,
            'ans' => $ans,
            'amount' => $amount,
            'entry_date' => $dateTime,
            'status' => 'active',
        );
        $this->db->insert('quiz',$data);
        $attempt_id = $this->db->insert_id();
        if($attempt_id > 0)
            return true;
        else
            return false;
    }


    function getQuizList($dateTime,$limit,$page,$quizid)
    {
      
       $this->db->select("id,question,opt1,opt2,opt3,opt4,amount,(case when end_date is null then status else (case when end_date > '".$dateTime."' then 'trend' else 'close' end) end) as status");
        $this->db->from('quiz');

        if(!empty($quizid))
        {
            $this->db->where(array('id' => $quizid));
        }

        $this->db->limit($limit,$page);
        $this->db->order_by('id','desc');

        $result = $this->db->get();
        if($result->num_rows() > 0)
            return $result->result_array();
        else
            return array();
    }


    function getQuiz($dateTime,$quiz_id)
    {
        if(!empty($dateTime) || !empty($quiz_id))
        {
            if(!empty($dateTime))
                $query = "select id,question,opt1,opt2,opt3,opt4,amount from quiz where status='active' and (end_date is null or (start_date <= '".$dateTime."' and end_date > '".$dateTime."')) order by id asc ";
            else
                $query = "select * from quiz where status='active' and id='".$quiz_id."' order by id asc "; 

            $result = $this->db->query($query);
            if($result->num_rows() > 0)
                return $result->result_array()[0];
            else
                return false;
        }
        return false;
    }

    function participants()
    {
        $this->db->select('fname,lname,image,amount as credited,attempt_date');
        $this->db->from('quizParticipants');

        $result = $this->db->get();
        if($result->num_rows() > 0)
            return $result->result_array(); 
        else
            return array(); 
    }

    function actAttempt($quiz_id,$number,$date,$sTime,$eTime)
    {
        if(!empty($quiz_id) && !empty($number) && !empty($date) && !empty($sTime) && !empty($eTime))
        {
            $this->db->select('id');
            $this->db->from('quizAttempt');
            $this->db->where(array('quiz_id' => $quiz_id));

            $result = $this->db->get();
            if($result->num_rows() <= 0)
            {         
                $this->db->where(array('id' => $quiz_id));
                $data1 = array(
                    'start_date' => $date.' '.$sTime,
                    'end_date' => $date.' '.$eTime,
                ); 
                $this->db->update('quiz',$data1);

                if ($this->db->affected_rows()>0)
                    return true;
                else
                    return false;
            }

            $this->db->select('id');
            $this->db->from('quizAttempt');
            $this->db->where(array(
                'Mobile' => $number,
                'quiz_id' => $quiz_id)
            );
            
            $result = $this->db->get();
            if($result->num_rows() > 0)
                return array('answered' => true);
            else
                return array('answered' => false);
        }
    }

    function attempt($number,$quiz_id,$dateTime,$ans,$result)
    {
        if(!empty($number) && !empty($quiz_id) && !empty($dateTime) && !empty($ans) && !empty($result))
        {
            $this->db->select('id');
            $this->db->from('quizAttempt');
            $this->db->where(array(
                'Mobile' => $number,
                'quiz_id' => $quiz_id
            ));
            $this->db->order_by('id','asc');

            $res = $this->db->get();
            if($res->num_rows() > 0)
                return array('answered' => true);
            else
            {
                $data = array(
                    'Mobile' => $number,
                    'quiz_id' => $quiz_id,
                    'attempt_date' => $dateTime,
                    'microtime' => (microtime(true) * 10000),
                    'answered' => $ans,
                    'result' => $result
                );
                $this->db->insert('quizAttempt',$data);
                $attempt_id = $this->db->insert_id();
                if($attempt_id > 0)
                {
                    return array(
                        'answered' => false,
                        'id' => $attempt_id
                    );
                }
                else
                    return false;
            }
        }
        return false;
    }

    function prize($number,$quiz_id,$attempt_id,$amount,$dateTime)
    {      
        if(!empty($number) && !empty($quiz_id) && !empty($attempt_id) && !empty($amount) && !empty($dateTime))
        {
            $this->db->select('id');
            $this->db->from('quizWinner');
            $this->db->where(array('quiz_id' => $quiz_id));
            $this->db->order_by('id','asc');

            $result = $this->db->get();
            if($result->num_rows() > 0)       
                return array('winner' => false);
            else
            {
                $data = array(
                    'Mobile' => $number,
                    'quiz_id' => $quiz_id,
                    'quiz_aid' => $attempt_id,
                    'amount' => $amount,
                    'entry_date' => $dateTime,
                    'status' => 'pending',
                );
                $this->db->insert('quizWinner',$data);
                $winner_id = $this->db->insert_id();
                if($winner_id > 0)
                {
                    return array(
                        'winner' => true,
                        'id' => $winner_id
                    );
                }
                else
                    return false;
            }
        }
    }
    
    function trans($number,$txnRefNo,$amount,$winner_id)
    {
        if(!empty($number) && !empty($amount) && !empty($winner_id))
        {
            $data1 = array(
                'Mobile' => $number,
                'TxnRefNo' => $txnRefNo,
                'amount' => $amount,
                'entry_date' => date('Y-m-d h:i:s'),
                'for' => 'quiz'
            );
            $this->db->insert('MaxPeTChili',$data1); 
            $mode_id = $this->db->insert_id();
            if($mode_id > 0)
            {
                if(!empty($txnRefNo))
                    $status = 'success';
                else
                    $status = 'failed';
                
                $this->db->where(array('id' => $winner_id));
                $data2 = array(
                    'mode' => 'wallet',
                    'mode_id' => $mode_id,
                    'status' => $status
                ); 
                $this->db->update('quizWinner',$data2);
                if ($this->db->affected_rows()>0)
                    return true;
                else
                    return false;
            }
            else
                return false;
        }
        else
            return false;
    }

}
	
 