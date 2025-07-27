<div class="modal fade" id="modalDebitMoney{{$i}}" tabindex="-1" aria-labelledby="{{$i}}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 125%; !important">
            <div class="modal-header">
                <div class="d-flex flex-row text-nowrap">
                    <h5 class="modal-title me-2" id="{{$participant->id_pseudo}}">💲💲 Retirer des Fonds de </h5>
                    <h5 class="modal-title text-info mb-0">{{$participant->pseudo}}</h5>
                    <h5 class="modal-title ">&nbsp; du groupe &nbsp;</h5>
                    <h5 class="modal-title text-danger mb-0">{{ $participant->group_name }}</h5>
                    <h5 class="modal-title ">&nbsp; pour correction 💲💲</h5>
                </div>
                
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                <form class="d-flex flex-row" method="POST" action="{{ route('debitMoney', [$participant->id_pseudo, $participant->group_name]) }}">
                    @csrf
                    <input name="input_pseudo" hidden readonly value="{{$participant->pseudo}}">
                    <input name="input_group_name" hidden readonly value="{{$participant->group_name}}">
                    <input name="input_correction" hidden readonly value="1">
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