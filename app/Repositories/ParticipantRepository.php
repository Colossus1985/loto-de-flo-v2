<?php

namespace App\Repositories;

use App\Models\Gains;
use App\Models\Groups;
use App\Models\Money;
use App\Models\Participants;
use App\Models\User;
use App\Models\Users;

class ParticipantRepository 
{
    public function getParticipants()
    {
        $query = Participants::query()
            ->orderBy('nameGroup', 'asc')
            ;
        $res = $query->get();
    }

    public function groupsDisponible() 
    {
        $groupsDispo = Groups::join(
                'participants', 'groups.id', '=', 'participants.id_group')
            ->select('groups.nameGroup')
            ->groupByRaw('groups.nameGroup')
            ->get();

        return $groupsDispo;
    }

}