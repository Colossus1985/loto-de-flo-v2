<?php

namespace App\Http\Controllers;

use App\Repositories\LogRepository;
use App\Repositories\GroupsRepository;
use App\Repositories\MoneyRepository;

use Illuminate\Http\Request;

class logController extends Controller
{
    protected $log;
    protected $groups;
    protected $money;

    public function __construct(
        LogRepository $log, 
        GroupsRepository $groups,
        MoneyRepository $money
    )
        {
            $this->log      = $log;
            $this->groups   = $groups;
            $this->money    = $money;
        }

    /**
     * page principale
     * affichage de statistiques
     */
    public function dashbord()
    {
        $groups                 = $this->groups->getGroups();
        $chiffres_groups        = $this->log->getGroups();
        $chiffres_participants  = $this->log->getParticipants();

        $arrayFondsByGroup      = $this->money->fonds($groups);
        $arrayGainByGroup       = $this->money->gains($groups);

        // dd($arrayFondsByGroup);
        
        // dd($chiffres_groups);
        return view('dashbord', [
            'chiffres_groups'       => $chiffres_groups,
            'chiffres_participants' => $chiffres_participants,
            'groups'                => $groups,
            'arrayFondsByGroup'     => $arrayFondsByGroup,
            'arrayGainByGroup'      => $arrayGainByGroup,
        ]);
    }

    /**
     * récupération des stats par groupe en détail
     * appelé par javascript
     * @param string nom du groupe
     * @return string json détails gain du groupe
     */
    public function getGroupData($group_name)
    {
        $res = $this->log->getGroupData($group_name);
        // dd($res);
        // dd($res['group_name']);
        // $res = $res['data'];
        return response()->json($res);
    }




}
