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
     * affichage du formulaire pour gérer un group
     */
    public function participantGroupForm()
    {
        $groups = $this->groups->getGroups();
        $participants = $this->participant->getParticipants();
        $groups_details = $this->groups->getVueGroupe();

        return view('pages.groupParticipant', [
            'groups'            => $groups,
            'participants'      => $participants,
            'groups_details'    => $groups_details,
        ]);
    }

    /**
     * composer un group avec des participants
     */
    public function participantGroup(Request $request)
    {
        $nameGroup = $request->inputNameGroup;
        $id_group = $this->groups->getGroup('nameGroup', $nameGroup);

        $arrayParticipant = $request->inputParticipantArray;
        
        foreach ($arrayParticipant as $participant) {
            $participant_details = $this->participant->getParticipant('pseudo', $participant);
            $id_participant = $participant_details->id;

            $champs = [
                'nameGroup' => $nameGroup,
                'groupID'  => $id_group->id,
            ];
            $res_update_participant = $this->participant->updateParticipant($champs, $id_participant);
            if ($res_update_participant['erreur']) {
                return redirect()->back()
                    ->with('error', $res_update_participant['erreur']);
            }
        }
        
        return redirect()->back()
            ->with('success', 'Nouvelles composition du group '.$nameGroup.' réussi!');
    }

    /**
     * ajout d'un nouveau group
     * @param Request le nom du group
     */
    public function addGroup(Request $request)
    {
        $controle = controlesInputs($request);
        // dd($controle);
        if ($controle[0]['erreur']) {
            return redirect()->back()
                    ->with('error', $controle[0]['message']);
        }

        $nameGroup = $request->inputNameGroup;
        $groupExists = $this->groups->getGroup('nameGroup', $nameGroup);

        if ($groupExists) {
            return redirect()->back()
                ->with('error', 'Le groupe '.$nameGroup.' existe déjà!');
        }

        $champs = ['nameGroup' => $nameGroup];
        $res_insert_group = $this->groups->addGroup($champs);
        if ($res_insert_group['erreur']) {
            return redirect()->back()
                ->with('error', $res_insert_group['message']);
        }

        return redirect()->back()
            ->with('success', 'Le groupe "'.$nameGroup.'" à été crée avec succès!');
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

        $group_ids      = [];
        $group_names    = [];
        foreach ($group as $data) {
            $group_ids[]    = $data->id;
            $group_names[]  = $data->nameGroup;
            $res = $this->money->historique_exist($data->id, $data->nameGroup, $id_participant);
        }
        $group_ids_json     = json_encode($group_ids);
        $group_names_json   = json_encode($group_names);

        $champs = [
            'group_id'      => $group_ids_json,
            'nameGroup'     => $group_names_json,
        ];

        $res_update_participant = $this->participant->updateParticipant($champs, $id_participant);
        
        if ($res_update_participant['erreur']) {
            return redirect()->back()
                ->with('error', $res_update_participant['message']);
        } 
        
        return redirect()->back()
            ->with('success', 'Modification de groupe a été enregistrée avec succès !');
    }

    /**
     * elnélver un participant de tous les groupes auxquels il adhère
     * @param int id participant
     */
    public function participantGroupDelete($id_participant)
    {
        $champs = [
            'groupID'   => null,
            'nameGroup' => null,
        ];
        $res_delete_groupParticipant = $this->participant->updateParticipant($champs, $id_participant);
        if ($res_delete_groupParticipant['erreur']) {
            return redirect()->back()
                ->with('error', $res_delete_groupParticipant['message']);
        } 
        
        return redirect()->back()
            ->with('success', 'Le participant le fait plus partie d\'un groupe.');
    }

    /**
     * supprimer un group (soft)
     * enlever les participant du groupe supprimer
     * @param int id du groupe
     */
    public function groupDelete($id_group)
    {
        $res_delete_group = $this->groups->deleteGroup($id_group);
        if ($res_delete_group['erreur']) {
            return redirect()->back()
                ->with('error', $res_delete_group['message']);
        } 
        
        return redirect()->back()
            ->with('success', 'Le groupe à été rendu inactif.');
    }
    

    /**
     * Reactiver un groupe
     * @param int id du groupe
     */
    public function groupRallume($id_group)
    {
        $res = $this->groups->groupRallume($id_group);

        if ($res['erreur']) {
            return redirect()->back()
                ->with('error', $res['message']);
        } 
        return redirect()->back()
            ->with('success', $res['message']);
    }

}
