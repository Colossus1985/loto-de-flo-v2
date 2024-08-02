<div class="modal fade" id="modalDebitMoney{{$participant->id}}" tabindex="-1" aria-labelledby="{{$participant->id}}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex flex-row">
                    <h5 class="modal-title me-2" id="{{$participant->id}}">
                        💲💲 Retirer des Fonds de 
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
                <form method="POST" action="{{ route('debitMoney', $participant->id) }}">
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
                            placeholder="Montant"
                            required
                        />
                        <label for="floatingMontant">Montant ➖ <span>€</span></label>
                    </div>

                    <div class="d-flex btn-G-L d-flex justify-content-end">
                        <button
                            class="btn btn-primary"
                            type="submit"
                            style="width: 45%"
                            {{-- onclick="return confirm('Retirer les fonds de {{ $participant->pseudo }} ?');" --}}
                        >
                            Retirer
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