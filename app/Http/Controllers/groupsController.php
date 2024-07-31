<?php

namespace App\Http\Controllers;

use App\Repositories\ParticipantRepository;
use App\Repositories\GroupsRepository;
use App\Repositories\MoneyRepository;
use Symfony\Component\HttpFoundation\Request;

class groupsController extends Controller
{
    public function __construct(
        ParticipantRepository $participant,
        GroupsRepository $groups,
        MoneyRepository $money,
        )
    {
        $this->participant      = $participant;
        $this->groups           = $groups;
        $this->money             = $money;
    }
    /**
     * Changement de groupe pour un participant
     * @param request avec le nom du nouveau group
     * @param int id du participant
     * @return back 
     */
    public function changeGroup(Request $request, $id_participant)
    {
        // dd($request);
        $name_group = $request->inputNameGroupNew;
        $group = $this->groups->getGroup('nameGroup', $name_group);

        if ($group != null) {
            $group_id = $group->id;
            $group_name = $group->nameGroup;
        } else {
            $group_id = null;
            $group_name = null;
        }

        $champs = [
            'groupID'   => $group_id,
            'nameGroup' => $group_name,
        ];
        $res_update_participant = $this->participant->updateParticipant($champs, $id_participant);
        
        if ($res_update_participant['erreur']) {
            return redirect()->back()
                ->with('error', $res_update_participant['message']);
        } 
        
        return redirect()->back()
            ->with('success', 'Modification de groupe a été enregistrée avec succès !');
    }
}
