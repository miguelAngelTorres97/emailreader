<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \DateTime;

class Email extends Model
{
    protected $id, $seen, $sender, $mailDate, $subject, $message;
    
    public function __construct($con, $email_number){
        $overview = imap_fetch_overview($con, $email_number, 0)[0];

        $this->id = $email_number;
        $this->seen = $overview->seen;
        $this->sender = $overview->from;
        $this->subject = isset($overview->subject) ? $overview->subject : '' ;
        $this->mailDate = new DateTime($overview->date);
        $this->mailDate = $this->mailDate->format( Config::$dateFormat );

        $this->message = imap_fetchbody($con,$email_number,1.2, FT_PEEK);
        if(empty($this->message)) $this->message = imap_fetchbody($con,$email_number,1, FT_PEEK);
        
        
        $structure = imap_fetchstructure($con, $email_number);
        
        
        if(isset($structure->parts) && is_array($structure->parts) && isset($structure->parts[1])) {
            $encoding = $structure->parts[1]->encoding;
            $this->message = $this->decode($this->message, $encoding);
            

        }else
            $this->message = quoted_printable_decode($this->message);

        //$this->message = strip_tags($this->message, '');
        //$this->message = nl2br($this->message);
    }

    public function getId(){
        return $this->id;
    }

    public function getSeen(){
        return $this->seen;
    }
    
    public function getSender(){
        return imap_utf8(mb_decode_mimeheader($this->sender));
    }
    
    public function getDate(){
        return $this->mailDate;
    }
    
    public function getSubject(){
        return imap_utf8($this->subject);
    }
    
    public function getMessage(){
        return $this->findURLs($this->message);
    }
    
    function  findURLs($str){
        return preg_replace_callback('/(http[s]?:[^\s]*)/i', function($match){
            $final = preg_match('/.*\)$/', $match[0]) ? ')' : '';
            $url = preg_replace('/\)$/', '', $match[0]);
            
            //$url = urldecode($url);
            //$url = quoted_printable_encode($url);
            
            
            return '<a href="' . $url . '" target="_blank">' . $url . '</a>' . $final;
        },$str);
    }
    
    public function matchSearch($search){
        if(!$search) return true;
        if(strpos(strtolower($this->sender), $search) !== false) return true;
        if(strpos(strtolower($this->mailDate), $search) !== false) return true;
        if(strpos(strtolower($this->subject), $search) !== false) return true;
        if(strpos(strtolower($this->message), $search) !== false) return true;
        
        return false;
    }
    
    function decode($str, $encoding){
        switch ($encoding) {
            # 7BIT
            case 0:
                return $str;
            # 8BIT
            case 1:
                return quoted_printable_decode(imap_8bit($str));
            # BINARY
            case 2:
                return imap_binary($str);
            # BASE64
            case 3:
                return imap_base64($str);
            # QUOTED-PRINTABLE
            case 4:
                return  quoted_printable_decode($str);
            # OTHER
            case 5:
                return $str;
            # UNKNOWN
            default:
                return $str;
        }
    }
    
}
