<div class="modal fade" id="modalAddMoney{{$participant->id}}" tabindex="-1" aria-labelledby="{{$participant->id}}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex flex-row">
                    <h5 class="modal-title me-1" id="{{$participant->id}}">
                        💲💲 Ajouter des Fonds pour 
                    </h5>
                    <h5 class="modal-title text-info mb-0">{{$participant->pseudo}} 💲💲</h5>
                </div>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                <form class="d-flex flex-row" method="POST" action="{{ route('addMoney', $participant->id) }}">
                    @csrf
                    <input name="input_pseudo" hidden readonly value="{{$participant->pseudo}}">
                    <div class="form-group form-floating mb-3 d-flex">
                        <input
                            type="number"
                            min="0"
                            step="0.01"
                            class="form-control flex-fill"
                            name="inputMontant"
                            id="floatingMontant"
                            value="{{ old('inputMontant') }}"
                            placeholder="First name"
                            required
                        >
                        <label for="floatingMontant">Montant ➕ <span>€</span></label>
                    </div>

                    
                        <div class="border border-3 rounded-3 px-3 d-flex flex-column flex-fill mt-3 mb-3">
                            <p class="my-1">Est ce un gain?</p>
                            <div class="d-flex flex-column flex-fill  justify-content-around mb-2">
                                <div class="d-flex flex-row">
                                    <input type="radio" class="me-3" name="inputAddGain" id="info-outlined-yes" autocomplete="off" value="true" >
                                <label class="btn-outline-info" for="info-outlined-yes">Oui</label>
                                </div>
                                <div class="d-flex flex-row">
                                    <input type="radio" class="me-3" name="inputAddGain" id="info-outlined-no" autocomplete="off" value="false" checked>
                                    <label class="btn-outline-info" for="info-outlined-no">Non</label>
                                </div>
                            </div>
                        </div>

                    <div class="d-flex btn-G-L d-flex justify-content-end">
                        <button
                            class="btn btn-primary"
                            type="submit"
                            style="width: 45%"
                            onclick="return confirm('Ajouter les fonds pour {{ $participant->pseudo }} ?');"
                        >
                            Rajouter
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-start">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>