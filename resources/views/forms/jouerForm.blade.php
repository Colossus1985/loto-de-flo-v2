@extends('layouts.main')
@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="p-2 fw-bold">Jouons !!!</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('debitAll') }}">
            @csrf
            <div class="d-flex flex-column flex-md-row">
                <div class="form-group form-floating mb-3 me-3 d-flex flex-column col-12 col-md-6">
                    <div class="form-group form-floating mb-3 d-flex flex-fill">
                        <input
                            type="number"
                            min="0"
                            step="0.01"
                            class="form-control flex-fill text-end fs-4"
                            name="inputAmount"
                            id="floatingMontant"
                            value="{{ old('inputAmount') }}"
                            placeholder="Montant ➖ €"
                            required
                        />
                        <label for="floatingMontant">Montant ➖ <span>€</span></label>
                    </div>
                    <div class="form-group form-floating d-flex flex-fill">
                        <input
                            type="date"
                            class="form-control flex-fill fs-4"
                            name="inputDate"
                            id="floatingDate"
                            value="<?php echo (new DateTime())->format('Y-m-d'); ?>"
                            placeholder="gain"
                            required
                        />
                        <label for="floatingDate">Date</label>
                    </div>
                </div>
                
                
                <div class="card form-group form-floating mb-3 d-flex flex-fill flex-column">
                    <div class="card-header">
                        <span class="fw-bold">Choisis le Group : </span>
                    </div>
                    <div class="card-body">
                        @foreach ($groupsDispo as $group)
                            <div class="ms-3 form-check form-switch">
                                <input class="form-check-input me-3"
                                    type="radio" 
                                    name="inputNameGroup" 
                                    role="switch" 
                                    id="flexSwitchNameGroup" 
                                    value="{{ $group->nameGroup }}"
                                    required
                                />
                                <label class="form-check-label" for="flexSwitchNameGroup">{{ $group->nameGroup }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
    
            <div class="d-flex d-flex justify-content-end ps-4">
                <button
                    class="btn btn-primary col-12 col-md-6"
                    type="submit"
                    onclick="return confirm('Jouer et retirer la mise de tous les participant(s)?');"
                >
                    Jouer
                </button>
            </div>
        </form>
    </div>
    
</div>

@endsection