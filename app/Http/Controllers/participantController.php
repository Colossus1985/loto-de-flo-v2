<?php

namespace App\Http\Controllers;

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

        // dd($participant);
        return view('pages.participant', [
            'actions' => $money, 
            'participant' => $participant,
            'groups' => $groups
            ]
        );
    }


    /**
     * affichage de la liste de tous les participants
     */
    public function getParticipants()
    {
        $participants = $this->participant->getParticipants();
        $participants_del = $this->participant->getParticipantsDeleted();

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
        $controle = $this->controlesInputs($request);
        if ($controle[0]['erreur']) {
            return redirect()->back()
                    ->with('error', $controle[0]['message']);
        }

        $pseudo = $request->inputPseudo;
        $email = $request->inputEmail;

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
            'nameGroup' => null,
            'groupID'   => null,
        ];
        $res_maj_participant = $this->participant->updateParticipant($champs, $id_articipant);
        if ($res_maj_participant['erreur']) {
            return redirect()->back()
                ->with('erreur', $res_maj_participant['message']);
        }
        $pseudo = $participant->pseudo;

        return redirect()->route('participants')
                ->with('success', $pseudo.' a été srendu avec succès!');

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
        $controle = $this->controlesInputs($request);
        // dd($controle);
        if ($controle[0]['erreur']) {
            return redirect()->back()
                    ->with('error', $controle[0]['message']);
        }

        $nameGroup = $request->inputNameGroupNew;
        if ($nameGroup == "Pas de groupe") {
            $nameGroup = null;
            $groupID = null;
        } else {
            $groupID = $this->groups->getGroup('nameGroup', $nameGroup);
        }
        
        $champs = [
            'firstName'     => $request->inputFirstName,
            'lastName'      => $request->inputLastName,
            'nameGroup'     => $nameGroup,
            'pseudo'        => $request->inputPseudo,
            'email'         => $request->inputEmail,
            'tel'           => $request->inputTel,
            'groupID'       => $groupID,
        ];
        $res_update_participant = $this->participant->updateParticipant($champs, $id_participant);

        if ($res_update_participant['erreur']) {
            return redirect()->back()
                ->with('error', $res_update_participant['message']);
        }

        return redirect()->back()
            ->with('success', $res_update_participant['message']);
    }

    public function controlesInputs($request)
    {
        $arrayControles = [];
        $regexInputName = "/^(\s)*[A-Za-z]+((\s)?((\'|\-|\.)?([A-Za-zéèîôàêç@])*))*(\s)*$/";
        $regexInputPseudoPdw = "/^(\s)*[A-Za-z0-9éèîôàêç@]+((\s)?((\'|\-|\.)?([A-Za-z0-9éèîôàêç@])*))*(\s)*$/";
        $regexPhone = "/^([0-9]*)$/";

        $pwd_one = $request->inputPassword;
        $pwd_two = $request->inputPassword_confirmation;
        $phone = $request->inputTel;
        $firstName = $request->inputFirstName;
        $lastName = $request->inputLastName;
        $pseudo = $request->inputPseudo;
        $log_identifiant = $request->inputRegister;
        $nameGroup = $request->inputNameGroup;

        if ($pwd_one != null || $pwd_one != '') {
           if (!preg_match($regexInputPseudoPdw, $pwd_one)) {
                array_push($arrayControles, ['erreur' => false, 'message' => "Attention aux charactères trop spéciaux dans le mot de passe !"]);
                return $arrayControles;
            } 
        }
 
        if ($pwd_two != null || $pwd_two != '') {
            if (!preg_match($regexInputPseudoPdw, $pwd_two)) {
                 array_push($arrayControles, ['erreur' => true, 'message' => "Attention aux charactères trop spéciaux dans le mot de passe !"]);
                 return $arrayControles;
             } 
         }
        
        if ($pwd_one != null && $pwd_two != null) {
            if ($pwd_one != $pwd_two) {
                array_push($arrayControles, ['erreur' => true, 'message' => "Les deux mot de passes ne correspondent pas!"]);
                return $arrayControles;
            }
        }
        
        if ($phone != null || $phone != '') {
           if (!preg_match($regexPhone, $phone)) {
                array_push($arrayControles, ['erreur' => true, 'message' => "Veuillez rentrer seulement des numéros sans espaces pour le numéro de téléphone, s'il vous plait!"]);
                return $arrayControles;
            } 
        }
        
        if ($firstName != null || $firstName != '') {
            if (!preg_match($regexInputName, $firstName)) {
                array_push($arrayControles, ['erreur' => true, 'message' => "Attention aux charactères spéciaux et chiffres dans le prenoms!"]);
                return $arrayControles;
            }
        }
        
        if ($lastName != null || $lastName != '') {
           if (!preg_match($regexInputName, $lastName)) {
                array_push($arrayControles, ['erreur' => true, 'message' => "Attention aux charactères spéciaux et chiffres dans le nom!"]);
                return $arrayControles;
            } 
        }
        
        if ($pseudo != null || $pseudo != '') {
            if (!preg_match($regexInputPseudoPdw, $pseudo)) {
                array_push($arrayControles, ['erreur' => true, 'message' => "Attention aux charactères spéciaux dans le pseudo!"]);
                return $arrayControles;
            }
        }
        
        if ($log_identifiant != null || $log_identifiant != '') {
            if (!preg_match($regexInputPseudoPdw, $log_identifiant)) {
                array_push($arrayControles, ['erreur' => true, 'message' => "Attention aux charactères spéciaux dans l'identifiant !"]);
                return $arrayControles;
            }
        }

        if ($nameGroup != null || $nameGroup != '') {
            if (!preg_match($regexInputPseudoPdw, $nameGroup)) {
                array_push($arrayControles, ['erreur' => true, 'message' => "Attention aux charactères spéciaux dans le nom du groupe!"]);
                return $arrayControles;
            }
        }
        
        //###---If all is alright sending back erruer = 'false' with empty message---###

        array_push($arrayControles, ['erreur' => false, 'message' => ""]);
        return $arrayControles;
    }
}
