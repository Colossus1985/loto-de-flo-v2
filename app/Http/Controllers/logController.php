<?php

namespace App\Http\Controllers;

use App\Repositories\LogRepository;

use Illuminate\Http\Request;

class logController extends Controller
{
    public function __construct(LogRepository $log)
        {
            $this->log      = $log;
        }

    public function dashbord()
    {
        $periode                = 3;
        $chiffres_groups        = $this->log->getGroups('mensuel', $periode);
        $chiffres_participants  = $this->log->getParticipants('mensuel', $periode);
        
        return view('dashbord', [
            'chiffres_groups'       => $chiffres_groups,
            'chiffres_participants' => $chiffres_participants,
        ]);
    }
}
