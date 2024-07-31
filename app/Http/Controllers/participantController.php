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
    public function getParticipant($id_participant)
    {
        // dd($id_participant);
        $participant        = $this->participant->getParticipant('id', $id_participant);
        $money              = $this->money->getMoney('id_pseudo', $id_participant);
        $groups             = $this->groups->getGroups();

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

        return view('pages.participants', [
            'participants'      => $participants,
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

        //###---controle if pseudo or mail are already in use---###############################################
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

        dd($resInsert);
        if ($resInsert['erreur']) {
            return redirect()->back()
                ->with('success', $pseudo." enregistré(e) avec succès !");
        } else {
            return redirect()->back()
                ->with('error', $resInsert['message']);
        }
        
    }

    public function participantDelete($idParticipant)
    {
        
        $participant = Participants::query()
            ->select('pseudo')
            ->where('id', '=', $idParticipant)
            ->get();
        $pseudo = $participant[0]->pseudo;

        $admins = Users::query()
            ->select('admin')
            ->where('admin', '=', 1)
            ->get();

        if (Auth::user()->admin == 1 && Auth::user()->id != $idParticipant) {
            $this->deleteUser($idParticipant, $pseudo);
            return redirect()->route('home', 'all')
                ->with('success', $pseudo.' a été supprimé avec succès!');

        } else if (Auth::user()->admin == 1 && Auth::user()->id == $idParticipant) {
            if (count($admins) > 1 ) {
                $this->deleteUser($idParticipant, $pseudo);
                
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();
                
                return redirect()->route('logReg')
                    ->with('success', 'Vous ne faite plus parti(e) de "Loto avec Flo"');

            } else {
                return redirect()->back()
                    ->with('error', 'Tant que vous êtes le seul administrateur vous ne pouvez vous supprimer!'); 
            }

        } else if (Auth::user()->admin == 0) {
            $this->deleteUser($idParticipant, $pseudo);
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            
            return redirect()->route('logReg')
                ->with('success', 'Vous ne faite plus parti(e) de "Loto avec Flo"');
        }
            
        
    }

    public function updateParticipant(Request $request, $idParticipant)
    {
        // dd($request);
        $controle = $this->controlesInputs($request);
        if (!$controle[0]['bool']) {
            return redirect()->back()
                    ->with('error', $controle[0]['message']);
        }

        $request->validate([
            'inputPasswordActuel' => 'required|current_password',
        ]);

        $pseudo = $request->inputPseudo;

        $participant = Participants::find($idParticipant);

        $inputNameGroupNew = $request->inputNameGroupNew;
        if ($inputNameGroupNew == "Pas de groupe") {
            $inputNameGroup = Null;
            // dd($inputNameGroup);
            $id_group = Null;
            $participant->id_group = $id_group;
        } else {
            $inputNameGroup = $inputNameGroupNew;
            $id_group = Groups::where('nameGroup', '=', $inputNameGroup)->first();
            $participant->id_group = $id_group['id'];
        }
        
        $participant->firstName = $request->inputFirstName;
        $participant->lastName = $request->inputLastName;
        $participant->nameGroup = $inputNameGroup;
        $participant->pseudo = $request->inputPseudo;
        $participant->email = $request->inputEmail;
        $participant->tel = $request->inputTel;
        try {
            $participant->save();
        } catch (Exception $e){
            return redirect()->back()
                ->with('error', 'La mise à jour a échoué!');
        }
        
        $user = User::find($idParticipant);
        if ($request -> inputPassword != "" || $request -> inputPassword != null) {
            $user->password = Hash::make($request -> inputPassword);
        } 
        try {
            $user->save();
        } catch (Exception $e){
            return redirect()->back()
                ->with('error', 'La mise à jour a échoué!');
        }

        return redirect()->back()
            ->with('success', $pseudo.' mis(e) à jour!');
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
