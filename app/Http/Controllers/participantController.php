<?php

namespace App\Http\Controllers;

use App\Models\Participants;
use App\Repositories\ParticipantRepository;
use App\Repositories\GroupsRepository;
use App\Repositories\MoneyRepository;
use Symfony\Component\HttpFoundation\Request;


class participantController extends Controller
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
     * voir les détails d'un participant
     * @param int id du participant
     */
    public function getParticipant($id_participant, $actif = 1)
    {
        // dd($id_participant);
        $participant        = $this->participant->getParticipant('id', $id_participant, $actif);
        $money              = $this->money->getMoney('id_pseudo', $id_participant);
        $groups             = $this->groups->getGroups();

        $participant_groups = json_decode($participant->nameGroup);
        if (!$participant_groups) { 
            $participant_groups = []; 
        }

        //=== si le participant n'a plus d'apartenance à un group on affiche plus les totaux
        $sommes = [];
        if ($money[0]->group_name) {
            $array_groups_id = json_decode($participant->group_id);
            foreach ($money as $data) {
                //=== on vérifie si le participant est associé encore au groupe
                if (in_array($data->id_group, $array_groups_id)) {
                    $group_name = $data->group_name;
                
                    $totale_value       = $data->credit + $data->creditGain - $data->debit;
                    $value_credit       = $data->credit;
                    $value_debit        = $data->debit;
                    $value_credit_gain  = $data->creditGain;
                    
                    // Si le group_name n'existe pas encore dans le tableau, l'initialiser
                    if (!isset($sommes[$group_name])) {
                        $sommes[$group_name] = [
                            'value_totale'      => 0,
                            'value_credit'      => 0,
                            'value_debit'       => 0,
                            'value_credit_gain' => 0,
                        ];
                    }
                    
                    // Ajouter la valeur au total pour ce group_name
                    $sommes[$group_name]['value_totale']        += $totale_value;
                    $sommes[$group_name]['value_credit']        += $value_credit;
                    $sommes[$group_name]['value_debit']         += $value_debit;
                    $sommes[$group_name]['value_credit_gain']   += $value_credit_gain;
                    $sommes[$group_name]['group_name']          = $group_name;
                }
                
            }
        }
        
        return view('pages.participant', [
            'actions'               => $money, 
            'participant_groups'    => $participant_groups,
            'participant'           => $participant,
            'sommes'                => $sommes,
            'groups'                => $groups
            ]
        );
    }


    /**
     * affichage de la liste de tous les participants
     */
    public function getParticipants()
    {
        $participants       = $this->participant->getParticipants();
        $participants_del   = $this->participant->getParticipantsDeleted();

        // dd($participants[count($participants) -1]);

        return view('pages.participants', [
            'participants'      => $participants,
            'participants_del'  => $participants_del,
        ]);
    }

    /**
     * affichage formulaire pour ajouter un participant
     */
    public function addParticipantForm()
    {
        $groups = $this->groups->getGroups();

        return view('forms.addParticipantForm', [
            'groups'        => $groups,
        ]);
    }

    /**
     * ajouter un participant
     */
    public function addParticipant(Request $request)
    {
        $controle = controlesInputs($request);
        if ($controle[0]['erreur']) {
            return redirect()->back()
                    ->with('error', $controle[0]['message']);
        }

        $pseudo = $request->inputPseudo;
        $email = $request->inputEmail;

        //=== vérification si le participant existe déjà sur le mail ou sinon le pseudo
        if ($email == null || $email == "") {
            $participantExist = $this->participant->getParticipant('pseudo', $pseudo);
            
            if ($participantExist) {
                return redirect()->back()
                    ->with('error', $pseudo.' déjà existant!');
            }
        } else {
            $participantExist = $this->participant->getParticipant('email', $email);
            
            if ($participantExist) {
                return redirect()->back()
                    ->with('error', $email.' déjà existant!');
            }
        }

        //=== ajout du participant dans le système
        $resInsert = $this->participant->addParticipant($request);

        if ($resInsert['erreur']) {
            return redirect()->back()
                ->with('success', $pseudo." enregistré(e) avec succès !");
        } else {
            return redirect()->back()
                ->with('error', $resInsert['message']);
        }
        
    }

    /**
     * supprimer un participant (soft delete)
     * maj champ delete dans la table (1 deleted, 0 actif)
     */
    public function participantDelete($id_articipant)
    {
        
        $participant = $this->participant->getParticipant('id', $id_articipant);
        $champs = [
            'actif'     => 0,
            'nameGroup' => [],
            'group_id'   => [],
        ];
        $res_maj_participant = $this->participant->updateParticipant($champs, $id_articipant);
        if ($res_maj_participant['erreur']) {
            return redirect()->back()
                ->with('erreur', $res_maj_participant['message']);
        }

        return redirect()->route('participants')
                ->with('success', $participant->pseudo.' a été rendu inactif(ve) avec succès!');

    }

    /**
     * rendre un participant actif
     * maj champ delete dans la table (1 deleted, 0 actif)
     */
    public function participantActiver($id_articipant)
    {
        $participant = $this->participant->getParticipant('id', $id_articipant, 0);
        $res_maj_participant = $this->participant->updateParticipant(['actif' => 1,], $id_articipant);
        if ($res_maj_participant['erreur']) {
            return redirect()->back()
                ->with('erreur', $res_maj_participant['message']);
        }
        $pseudo = $participant->pseudo;

        return redirect()->route('participants')
                ->with('success', $pseudo.' a été rendu actif(ve)!');

    }

    /**
     * maj d'informations d'un participant (nom, pseudo, email, mdp ...)
     * @param Request infos
     * @param int id du participant
     */
    public function updateParticipant(Request $request, $id_participant)
    {
        // dd($request);
        $controle = controlesInputs($request);
        // dd($controle);
        if ($controle[0]['erreur']) {
            return redirect()->back()
                    ->with('error', $controle[0]['message']);
        }

        $champs = [
            'firstName'     => $request->inputFirstName,
            'lastName'      => $request->inputLastName,
            'pseudo'        => $request->inputPseudo,
            'email'         => $request->inputEmail,
            'tel'           => $request->inputTel,
        ];
        $res_update_participant = $this->participant->updateParticipant($champs, $id_participant);

        if ($res_update_participant['erreur']) {
            return redirect()->back()
                ->with('error', $res_update_participant['message']);
        }

        return redirect()->back()
            ->with('success', $res_update_participant['message']);
    }

    //=== migration -> v3 réunification des pseudo
    public function unificationPseudo()
    {
        $res = $this->participant->unificationPseudo();
        if ($res) {
            return response()->json('fini');
        }
    }

}
