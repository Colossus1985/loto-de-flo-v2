@extends('layouts.main')
@section('content')

<div class="container">
    <form method="POST" action="{{ route('addParticipant') }}">
        @csrf
        <div class="form-group form-floating mb-3 d-flex">
            <input type="text" class="form-control flex-fill" maxlength="20" name="inputFirstName" id="floatingFirstName" value="{{ old('inputFirstName') }}" placeholder="First name">
            <label for="floatingFirstName">Prenom</label>
        </div>

        <div class="form-group form-floating mb-3 d-flex">
            <input type="text" class="form-control flex-fill" maxlength="20" name="inputLastName" id="floatingLastName" value="{{ old('inputLastName') }}" placeholder="Last name">
            <label for="floatingLastName">Nom</label>
        </div>

        <div class="form-group form-floating mb-3 d-flex">
            <input type="text" class="form-control flex-fill" maxlength="15" name="inputPseudo" id="floatingPseudo" value="{{ old('inputPseudo') }}" placeholder="Pseudo">
            <label for="floatingPseudo">Pseudo</label>
        </div>
        
        <div class="form-group form-floating mb-3 d-flex">
            <input type="email" class="form-control flex-fill" name="inputEmail" id="floatingEmail" value="{{ old('inputEmail') }}" placeholder="name@example.com">
            <label for="floatingEmail">Email</label>
        </div>

        <div class="form-group form-floating mb-3 d-flex">
            <input type="text" class="form-control flex-fill" name="inputTel" minlength="10" maxlength="15" id="floatingTel" value="{{ old('inputTel') }}" placeholder="Phone number">
            <label for="floatingTel">Téléphone</label>
        </div>

        <div class="form-group form-floating mb-3 d-flex">
            <input type="password" class="form-control flex-fill" name="inputPassword" minlength="3" maxlength="20"  id="floatingPassword" placeholder="Password">
            <label for="floatingPassword">Password</label>
        </div>

        <div class="form-group form-floating mb-3 d-flex">
            <input type="password" class="form-control flex-fill" name="inputPassword_confirmation" minlength="3" maxlength="20" id="floatingConfirmPassword" placeholder="Confirm Password">
            <label for="floatingConfirmPassword">Confirm Password</label>
        </div>

        <div class="border border-3 rounded-3 form-group form-floating mb-3 d-flex flex-fill flex-column">
            <div class="">
                <p class="mt-1 mb-2 ps-3">Choisis le Group : </p>
            </div>
             @foreach ($groups as $group)
                <div class="ms-3 form-check form-switch">
                    <input class="form-check-input me-3"
                        type="radio" 
                        name="inputNameGroup" 
                        role="switch" 
                        id="flexSwitchNameGroup" 
                        value="{{ $group->nameGroup }}">
                    <label class="form-check-label" for="flexSwitchNameGroup">{{ $group->nameGroup }}</label>
                </div>
            @endforeach
        </div>
        <div class="d-flex btn-G-L d-flex justify-content-end">
            <button class="btn btn-primary" type="submit" style="width: 45%;">Ajouter</button>
        </div>
    </form>
</div>

@endsection