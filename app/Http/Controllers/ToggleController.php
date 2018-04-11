<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Config;

class ToggleController extends Controller
{

    public function toggle(){
        $response = [
            'status' => 404,
            'msg' => 0,
        ];

        if(isset($_POST['email_id']) && isset($_POST['email_readed'])){
            $response['status'] = 200;

            Config::read();
            $con = imap_open(Config::$host, Config::$email, Config::$pass) or die();

            if($_POST['email_readed'] == 1){
                imap_clearflag_full($con, $_POST['email_id'], "\\Seen") or die();
                $response['msg'] = 0;

            }else {
                imap_setflag_full($con, $_POST['email_id'], "\\Seen") or die();

                $response['msg'] = 1;
            }
        }

        return $response;
    }


}
