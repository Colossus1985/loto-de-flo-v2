@extends('layouts.main')
@section('content')

<style>
    .border-gold {
        border: 2px solid gold; 
        padding: 5px;
        border-radius: 5px; 
        animation: glowing_full 1.5s infinite; 
    }

    /* Animation de brillance */
    @keyframes glowing_full {
        0% { box-shadow: 0 0 5px gold; }
        50% { box-shadow: 0 0 20px rgb(226, 126, 44); }
        100% { box-shadow: 0 0 5px gold; }
    }
</style>

<div class="p-5">
    <div class="d-flex flex-row">
        <h3>Ajouter un Gain</h3>
    </div>
    <div class="modal-body">
        <form method="POST" action="{{ route('addGain') }}">
            @csrf
            <div class="d-flex flex-row">
                <div class="form-group form-floating mb-3 me-3 d-flex flex-column">
                    <div class="form-group form-floating mb-3 d-flex flex-fill">
                        <input
                            type="number"
                            min="0"
                            step="0.01"
                            class="form-control flex-fill border-gold"
                            name="inputAmount"
                            id="floatingMontant"
                            value="{{ old('inputAmount') }}"
                            placeholder="Montant ➕ €"
                            required
                        />
                        <label for="floatingMontant">Montant ➕ <span>€</span></label>
                    </div>
                    <div class="form-group form-floating d-flex flex-fill">
                        <input
                            type="date"
                            class="form-control flex-fill"
                            name="inputDate"
                            id="floatingDate"
                            value="<?php echo (new DateTime())->format('Y-m-d'); ?>"
                            placeholder="gain"
                            required
                        />
                        <label for="floatingDate">Date</label>
                    </div>
                    <div class="border border-3 rounded-3 px-3 d-flex flex-column flex-fill mt-3">
                        <p class="my-1">Rajouter les gains au(x) participant(s)</br> du group choisit automatiquement?</p>
                        <div class="d-flex flex-row flex-fill mb-2 justify-content-around">
                            <input type="radio" class="btn-check flex-fill" name="inputAddGainAuto" id="info-outlined-yes" autocomplete="off" value="true" checked
                                style="width: 40%">
                            <label class="btn btn-outline-info" for="info-outlined-yes">Oui</label>

                            <input type="radio" class="btn-check flex-fill" name="inputAddGainAuto" id="info-outlined-no" autocomplete="off" value="false"
                                style="width: 40%">
                            <label class="btn btn-outline-info" for="info-outlined-no">Non</label>
                        </div>
                    </div>
                </div>

                <div class="border border-3 rounded-3 form-group form-floating mb-3 d-flex flex-fill flex-column">
                    <div class="">
                        <p class="mt-1 mb-2 ps-3">Choisir le groupe : </p>
                    </div>
                    @foreach ($groupsDispo as $group)
                        <div class="ms-3 form-check form-switch">
                            <input class="form-check-input me-3"
                                type="radio" 
                                name="inputNameGroup" 
                                role="switch" 
                                id="flexSwitchNameGroup" 
                                value="{{ $group->nameGroup }}"
                                required>
                            <label class="form-check-label" for="flexSwitchNameGroup">{{ $group->nameGroup}}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="d-flex btn-G-L justify-content-end">
                <button
                    class="btn btn-primary"
                    type="submit"
                    style="width: 45%"
                    onclick="return confirm('Ajouter le gain?');"
                >
                    Ajouter Gain
                </button>
            </div>
        </form>
    </div>
</div>

@endsection