<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailReader extends Model
{
    public $con, $emails, $options,  $n;
    public $querySearch, $queryMsg, $queryDate, $querySubject, $queryFrom;
    
    function isDate($date) {
        if (!$date) {
            return false;
        }

        try {
            new \DateTime($date);
            
            return true;
        } catch (\Exception $e) {
            echo 'Incorrect Date Format';
            return false;
        }
    }

    
    public function readEmails(){
        Config::read();
        
        $this->con = imap_open(Config::$host, Config::$email, Config::$pass) or die(imap_last_error());
            
        $this->queryMsg = isset($_GET['m']) ? $_GET['m'] : '';
        $this->queryDate = isset($_GET['d']) ? $_GET['d'] : '';
        $this->querySubject = isset($_GET['s']) ? $_GET['s'] : '';
        $this->queryFrom = isset($_GET['from']) ? $_GET['from'] : '';
        $this->querySearch = isset($_GET['q']) ? strtolower($_GET['q']) : '';
        
       $this->checkCriteria();
        
        if(isset($_GET['unseen']))  unset($_GET['unseen']);
        
        $this->emails = imap_search($this->con, 'ALL ' .  $this->options);
        
        $this->n = isset($_GET['n']) ? $_GET['n'] : Config::$shown;
        
    }
    
    function checkCriteria(){
        $options = '';
        $options .= isset($_GET['unseen']) ? 'UNSEEN ' : '';
        $options .= $this->queryDate && $this->isDate($this->queryDate) ? 'ON "' . ( new \DateTime($this->queryDate) )->format('d-M-Y') . '"' : '';
        $options .= $this->queryMsg ? 'TEXT "' . $this->queryMsg . '"' : '';
        $options .= $this->querySubject ? 'SUBJECT "' . $this->querySubject . '"' : '';
        $options .= $this->queryFrom ? 'FROM "' . $this->queryFrom . '"' : '';
        $this->options = $options;
        
        
    }
    
    /*function searches($queries){
        $output = array();
        foreach($queries as $crit){
            $str =  $crit . ( $crit !== '' ?  ( '"' . $this->query . '" ' ) : '');
            $array = imap_search($this->con, 'ALL ' . $str .  $this->options);
            if($array) $output = array_merge($array, $output);
        }
        return $output;
    }*/


    public function toggleRead($emailId, $readed){
        if($readed){
            imap_clearflag_full($this->con, $emailId, "\\Seen");
            return;
        }

        imap_fetchbody($this->con,$emailId,1);
    }
    
}
