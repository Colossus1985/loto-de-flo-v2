<?php

namespace App\Http\Controllers;

use App\Repositories\LogRepository;
use App\Repositories\GroupsRepository;

use Illuminate\Http\Request;

class logController extends Controller
{
    public function __construct(LogRepository $log, GroupsRepository $groups)
        {
            $this->log      = $log;
            $this->groups   = $groups;
        }

    public function dashbord()
    {
        $groups                 = $this->groups->getGroups();
        $chiffres_groups        = $this->log->getGroups();
        $chiffres_participants  = $this->log->getParticipants();
        
        // dd($chiffres_groups);
        return view('dashbord', [
            'chiffres_groups'       => $chiffres_groups,
            'chiffres_participants' => $chiffres_participants,
        ]);
    }
}
