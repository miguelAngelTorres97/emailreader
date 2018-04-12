<?php

namespace App\Http\Controllers;

use App\Config;
use App\Email;
use App\EmailReader;
use Illuminate\Http\Request;

class EmailReaderController extends Controller
{

    public function emailReader()
    {
        $reader = new EmailReader();

        $unseen = isset($_GET['unseen']);
        $reader->readEmails();

        $url = $_SERVER['PHP_SELF'] . ( count($_GET) != 0 ? ('?' . http_build_query($_GET) . '&') : '?');

        $email_list = [];
        $max = (!Config::$maxEmails || Config::$maxEmails == -1) ? count($reader->emails) : Config::$maxEmails;

        if ($reader->emails) {
            rsort($reader->emails);
            foreach($reader->emails as $i => $email_number) {
                if ($reader->n === 0 || $max <= $i) break;

                $email = new Email($reader->con, $email_number);

                if (!$reader->querySearch || $email->matchSearch($reader->querySearch)) {
                    $reader->n--;
                    $email_list[] = $email;
                }
            }
        }


        $return_link = Config::getVar('return_link', '');

        $labels = [
            'mark_uncompleted'  =>  Config::getVar('mark_uncompleted', 'Mark as uncompleted'),
            'mark_completed'    =>  Config::getVar('mark_completed', 'Mark as completed'),
            'show_all'          =>  Config::getVar('show_all', 'Show all') ,
            'show_unseen'       =>  Config::getVar('show_uncompleted', 'Show unseen'),
            'return'            =>  Config::getVar('return', 'Return'),
        ];

        return view('emailreader/emailreader', ['url' => $url, 'reader' => $reader, 'max' => $max, 'email_list' => $email_list, 'unseen' => $unseen, 'return_link' => $return_link, 'labels' => $labels]);
    }
}
