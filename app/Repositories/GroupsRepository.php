<?php

namespace App\Repositories;

use App\Models\Gains;
use App\Models\Groups;
use App\Models\Money;
use App\Models\Participants;
use App\Models\User;
use App\Models\Users;

class GroupsRepository 
{
    public function getGroups()
    {
        $query = Groups::query()
            ;
        $res = $query->get();

        return $res;
    }


}