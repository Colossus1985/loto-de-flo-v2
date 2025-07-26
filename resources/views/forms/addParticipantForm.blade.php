@extends('layouts.main')
@section('content')

<div class="card">
    <div class="card-header">
        <h3>Ajouter un participant</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('addParticipant') }}">
            @csrf
            <div class="d-flex flex-column flex-md-row">
                <div class="col-12 col-md-8 me-0 me-md-3">
                    <div class="form-group form-floating mb-3 d-flex">
                        <input type="text" class="form-control flex-fill" maxlength="20" name="inputFirstName" id="floatingFirstName" value="{{ old('inputFirstName') }}" placeholder="First name" required>
                        <label for="floatingFirstName">Prenom</label>
                    </div>
            
                    <div class="form-group form-floating mb-3 d-flex">
                        <input type="text" class="form-control flex-fill" maxlength="20" name="inputLastName" id="floatingLastName" value="{{ old('inputLastName') }}" placeholder="Last name" required>
                        <label for="floatingLastName">Nom</label>
                    </div>
            
                    <div class="form-group form-floating mb-3 d-flex">
                        <input type="text" class="form-control flex-fill" maxlength="15" name="inputPseudo" id="floatingPseudo" value="{{ old('inputPseudo') }}" placeholder="Pseudo" required>
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
                </div>
                <div class="col-12 col-md-4 border rounded form-group form-floating mb-3 d-flex flex-column">
                    <div class="card-header">
                        <span class="fw-bold">Choisis le ou les Group(s) : </span>
                    </div>
                    <div class="card-body">
                        @foreach ($groups as $i => $group)
                            <div class="form-check form-switch">
                                <input class="form-check-input me-3"
                                    type="checkbox" 
                                    name="inputNameGroup[]"
                                    role="switch" 
                                    id="flexSwitchNameGroup_{{$i}}" 
                                    value="{{ $group->nameGroup }}">
                                <label class="form-check-label" for="flexSwitchNameGroup_{{$i}}">{{ $group->nameGroup }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
    
            
            <div class="d-flex btn-G-L d-flex justify-content-end">
                <button class="btn btn-primary" type="submit" style="width: 45%;">Ajouter</button>
            </div>
        </form>
    </div>
</div>


@endsection