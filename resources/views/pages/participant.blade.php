@extends('layouts.main')
@section('content')

<style>
    .width-full {width: 100%;}
    .bg_color-negatif {background-color: rgba(82, 82, 82, 0.541) !important; color:azure !important;}
    .bg_color-null {background-color: rgba(226, 226, 226, 0.541) !important;}
    .bg_color-limit {background-color: rgba(255, 122, 122, 0.541) !important;}
    .bg_color-ok {background-color: rgba(255, 209, 71, 0.541) !important;}
    .bg_color-super {background-color: rgba(116, 255, 91, 0.541) !important;}
    .bg_color-groups {background-color: rgba(146, 210, 252, 0.541) !important;}
    .visible_non {opacity: 0;}
</style>

<div class="card">
    <div class="card-header">
        <div class="d-flex col-12 col-md-6">
            <h3 class="mb-3 py-1 px-2 flex-fill @if (!$participant->actif) bg_color-negatif @else bg_color-super @endif rounded">
                {{ $participant->pseudo }} ({{ $participant->firstName }} {{ $participant->lastName }}) 
                @if (!$participant->actif) <span>NON ACTIF(VE)</span> @endif
            </h3>
        </div>
        <div class="d-flex flex-column flex-md-row justify-content-between col-12">
            <div class="d-flex flex-wrap">

                <div class="my-3 me-3">
                    <div class="btn-group">
                        <button     type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#resume_Collapse" 
                                    aria-expanded="true" 
                                    aria-controls="resume_Collapse" 
                                    class="btn btn-info text-nowrap">
                            Résumé
                        </button>
                    </div>
                </div>

                <div class="my-3 me-3">
                    <div class="btn-group">
                        <button     type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#details_Collapse" 
                                    aria-expanded="false" 
                                    aria-controls="details_Collapse" 
                                    class="btn btn-info text-nowrap">
                            Détails personnels
                        </button>
                    </div>
                </div>
        
                <div class="my-3 me-3">
                    <div class="btn-group">
                        <button     type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#historique_Collapse" 
                                    aria-expanded="true" 
                                    aria-controls="historique_Collapse" 
                                    class="btn btn-info text-nowrap">
                            Historique monétaire
                        </button>
                    </div>
                </div>
            </div>

            <div class="my-3 me-3">
                <div class="btn-group">
                    @if ($participant->actif == 1)
                        <form action="{{ route('participantDelete', $participant->id) }}" method="get">
                            @csrf
                            <button type="submit" class="btn btn-danger text-nowrap"
                                onclick="return confirm('Veux tu vraiment rendre inactif(ve) {{ $participant->pseudo }} ?');">Supprimer {{$participant->pseudo}}
                            </button>
                        </form>
                    @else
                        <form action="{{ route('participantActiver', $participant->id) }}" method="get">
                            @csrf
                            <button type="submit" class="btn btn-success text-nowrap"
                                onclick="return confirm('Veux tu vraiment rendre actif(ve) {{ $participant->pseudo }} ?');">Rendre actif(ve) {{$participant->pseudo}}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="collapse" id="details_Collapse">
        <div class="card-body d-flex flex-column flex-md-row">
            <form class="card-body border rounded d-flex flex-column me-0 me-md-3 col-12 col-md-9" method="POST" action="{{ route('updateParticipant', $participant->id) }}">
                @csrf
                <div class="d-flex flex-column flex-md-row">
                    <div class="d-flex flex-column col-12 col-md-8">
                        <div class="form-group form-floating mb-3">
                            <input id="floatingpseudo" 
                                type="text" 
                                class="form-control flex-fill fw-bold" 
                                name="inputPseudo"
                                value="{{ $participant->pseudo }}">
                            <label for="floatingpseudo" class="text-nowrap">Pseudo</label>
                        </div>
        
                        <div class="form-group form-floating mb-3">
                            <input id="floatingfirstName" 
                                type="text" class="form-control flex-fill fw-bold" 
                                name="inputFirstName"
                                value="{{ $participant->firstName }}">
                            <label for="floatingfirstName" class="text-nowrap">Prenom</label>
                        </div>
        
                        <div class="form-group form-floating mb-3">
                            <input id="floatinglastName" 
                                type="text" 
                                class="form-control flex-fill fw-bold" 
                                name="inputLastName"
                                value="{{ $participant->lastName }}">
                            <label for="floatinglastName" class="text-nowrap">Nom</label>
                        </div>
        
                        <div class="form-group form-floating mb-3">
                            <input id="floatingTel" 
                                type="text" class="form-control fw-bold" 
                                name="inputTel"
                                value="{{ $participant->tel }}"
                                >
                            <label for="floatingTel" class="text-nowrap">Téléphone</label>
                        </div>
        
                        <div class="form-group form-floating">
                            <input id="floatingEmail" 
                                type="email" 
                                class="form-control fw-bold" 
                                name="inputEmail"
                                value="{{ $participant->email }}"
                                autocomplete="email">
                            <label for="floatingEmail" class="text-nowrap">Email</label>
                        </div>
                        
                    </div>

                    <div class="d-flex flex-column flex-fill">
                        <div class="form-group form-floating mb-3">
                            <input id="floatingAmount_dispo" class="form-control text-end fw-bold"
                                @if ($participant->amount == 0)
                                    value="0.00 €" 
                                @else 
                                    value="{{ ifNotZero($participant->amount, true, ' €', '.', ' ') }}"
                                @endif 
                                readonly>
                            <label for="floatingAmount_dispo" class="text-nowrap">Fonds globaux</label>
                        </div>
        
                        <div class="form-group form-floating mb-3">
                            <input id="floatingAmount_joue" class="form-control text-end fw-bold"
                                @if (($participant->totalAmount) == 0)
                                    value="0.00 €" 
                                @else 
                                    value="{{ ifNotZero(($participant->totalAmount), true, ' €', '.', ' ') }}" 
                                @endif
                                readonly>
                            <label for="floatingAmount_joue" class="text-nowrap">Mises en jeu globale</label>
                        </div>

                        <div class="d-flex flex-column" style="height: 100%;">
                            <div class="form-group form-floating d-flex flex-column flex-fill">
                                <textarea id="groups" 
                                    class="form-control flex-fill fw-bold text-wrap h-100" 
                                    readonly
                                >{{ $participant_groups ? implode("\n", $participant_groups) : 'Pas de group(s) associé(s)' }}</textarea>
                        
                                <label for="groups" class="text-nowrap">Groups</label>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="form-group form-floating flex-fill d-flex">
                    <button type="submit" class="btn btn-primary text-nowrap flex-fill">Enregistrer Changement</button>
                </div>
            </form>

            <div class="card-body border rounded">
                @if ($participant->actif)
                    <form action="{{ route('changeGroup', $participant->id) }}" method = "POST" style="height: 100%">
                        @csrf
                        <div class="d-flex flex-column"  style="height: 100%">
                            <div class="card-header mb-2">
                                <span class="fw-bold">Définir le(s) Groupe(s) : </span>
                            </div>

                            <div class="d-flex flex-column flex-fill"  style="height: 100%">
                                <div class="d-flex flex-column text-nowrap">
                                    @foreach ($groups as $i => $group)
                                        <div class="ms-1 form-check me-3">
                                            <input class="form-check-input me-2"
                                                type="checkbox" 
                                                name="inputNameGroupNew[]" 
                                                id="flexSwitchNameGroup_{{$i}}" 
                                                value="{{ $group->nameGroup }}">
                                            <label class="form-check-label text-nowrap" for="flexSwitchNameGroup_{{$i}}">{{ $group->nameGroup }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="flex-fill d-flex flex-column justify-content-end">
                                    <button type="submit" class="btn btn-primary text-nowrap mb-3">Changer groupe</button>
                                    <a href="{{ route('participantGroupDelete', $participant->id) }}" class="btn btn-danger text-nowrap">Enlever de tous les groupes</a>
                                </div>

                            </div>
                            
                        </div>
                    </form>
                @else
                    <div class="border border-3 rounded-3 d-flex flex-column  ps-3 py-2 mb-3">
                        <p>Avant d'associer {{ $participant->pseudo }} à un groupe il faut la(le) rendre actif(ve) !</p>
                        <div>
                            <form action="{{ route('participantActiver', $participant->id) }}" method="get">
                                @csrf
                                <div class="">
                                    <button type="submit" class="btn btn-success"
                                        onclick="return confirm('Veux tu vraiment annuler la suppression de {{ $participant->pseudo }} ?');">Rendre actif(ve) {{$participant->pseudo}}
                                    </button>
                                </div>
                            </form>     
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card-body collapse show" id="resume_Collapse">
        <div class="card-header mb-3">
            <h4>Résumée</h4>
        </div>
        @if ($sommes)
            <div class="mb-3 d-flex flex-wrap table-responsive">
                @foreach ( $sommes as $i => $data)
                    <div class="d-flex flex-column flex-wrap flex-fill border rounded bg_color-groups p-1 mb-3">
                        <div class="ms-3 mt-2">
                            <h5>{{ $data['group_name'] }}</h5>
                        </div>
                        <div class="d-flex flex-row flex-wrap flex-fill">

                            <div class="row form-group form-floating mb-3 mx-3 flex-fill">
                                <input id="dispo_{{ $i }}" 
                                    @if ($data['value_totale'] < 0)
                                        class="text-end fw-bold bg_color-negatif text-white form-control text-end fw-bold"
                                        value="{{ ifNotZero($data['value_totale'], true, ' €', '.', ' ') }}"
                                    @elseif ($data['value_totale'] == 0.00 || $data['value_totale'] == null)
                                        class="text-end fw-bold bg_color-null form-control text-end fw-bold"
                                        value="0.00 €"
                                    @elseif ($data['value_totale'] >= 0.01 && $data['value_totale'] <= 3.49 )
                                        class="text-end fw-bold bg_color-limit form-control text-end fw-bold"
                                        value="{{ ifNotZero($data['value_totale'], true, ' €', '.', ' ') }}"
                                    @elseif ($data['value_totale'] >= 3.50 && $data['value_totale'] <= 9.99)
                                        class="text-end fw-bold bg_color-ok form-control text-end fw-bold"
                                        value="{{ ifNotZero($data['value_totale'], true, ' €', '.', ' ') }}"
                                    @else
                                        class="text-end fw-bold bg_color-super form-control text-end fw-bold"
                                        value="{{ ifNotZero($data['value_totale'], true, ' €', '.', ' ') }}"
                                    @endif
                                    readonly
                                >
                                <label for="dispo_{{ $i }}" class="text-nowrap">Fonds dipso</label>
                            </div>

                            <div class="form-group form-floating mb-3 me-3 flex-fill">
                                <input id="credit_{{ $i }}" class="form-control text-end fw-bold text-success"
                                    @if ($data['value_credit'] == 0.00 || $data['value_credit'] == null)
                                        value="0.00 €"
                                    @else
                                        value="+ {{ ifNotZero($data['value_credit'], true, ' €', '.', ' ') }}"
                                    @endif
                                readonly>
                                <label for="credit_{{ $i }}" class="text-nowrap">Crédit total</label>
                            </div>

                            <div class="form-group form-floating mb-3 me-3 flex-fill">
                                <input id="credit_{{ $i }}" class="form-control text-end fw-bold text-danger"
                                    @if ($data['value_debit'] == 0.00 || $data['value_debit'] == null)
                                        value="0.00 €"
                                    @else
                                        value="- {{ ifNotZero($data['value_debit'], true, ' €', '.', ' ') }}"
                                    @endif
                                readonly>
                                <label for="credit_{{ $i }}" class="text-nowrap">Joué total</label>
                            </div>

                            <div class="form-group form-floating mb-3 me-3 flex-fill">
                                <input id="credit_{{ $i }}" class="form-control text-end fw-bold text-success"
                                    @if ($data['value_credit_gain'] == 0.00 || $data['value_credit_gain'] == null)
                                        value="0.00 €"
                                    @else
                                        value="+ {{ ifNotZero($data['value_credit_gain'], true, ' €', '.', ' ') }}"
                                    @endif
                                readonly>
                                <label for="credit_{{ $i }}" class="text-nowrap">Gains totaux</label>
                            </div>
                            
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    
    <div class="card-body collapse" id="historique_Collapse">
        <div class="card-header mb-3">
            <h4>Historique des mouvement monetaire</h4>
        </div>

        <div class="bg-light rounded p-3">
            <table id="table_participant" class="table table-bordered order-column table-hover compact nowrap cell-border small"><?php // Default dataTables  ?>
                <thead>
                    <tr>
                        <th class="text-center">ID ligne</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Groupe</th>
                        <th class="text-center">STATUT</th>
                        <th class="text-center">Credit</th>
                        <th class="text-center">Debit</th>
                        <th class="text-center">Credit Gain</th>
                        <th></th>
                    </tr>
                    <tr class="filterrow">
                        <th><input type="text" class="input-filter form-control form-control-sm" placeholder="Rechercher"></th>
                        <th><input type="text" class="input-filter form-control form-control-sm" placeholder="Rechercher"></th>
                        <th class="select-filter">
                            <select class="column-filter form-select form-select-sm" id="s1-filter" placeholder="Recherche">
                                <option value="">Tous</option>
                            </select>
                        </th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($actions as $action)
                        <tr @if ($action->correction) class="table-secondary" title="correction de fond!" @endif>
                            <td class="text-center">{{ $action->id }}</td>
                            <td class="fw-bold text-center">{{ sql2display($action->date) }}</td>
                            <td class="fw-bold text-center">{{ $action->group_name }}</td>
                            <td class="text-end fw-bold" >{{ ifNotZero($action->amount, true, ' €', '.', ' ') }}</td>
                            @if (!$action->correction)
                                @if ( $action->credit >= 0.01 )
                                    <td class="bg_color-super text-end fw-bold" >{{ ifNotZero($action->credit, true, ' €', '.', ' ') }}</td>
                                @else
                                    <td class="text-end fw-bold visible_non">0.00 €</td>
                                @endif
                            @else
                                <td class="text-end fw-bold">{{ ifNotZero($action->credit, true, ' €', '.', ' ') }}</td>
                            @endif

                            @if (!$action->correction)
                                @if ( $action->debit >= 0.01 )
                                    <td class="bg_color-limit text-end fw-bold">{{ ifNotZero($action->debit, true, ' €', '.', ' ') }}</td>
                                @else
                                    <td class="text-end fw-bold visible_non">0.00 €</td>
                                @endif
                            @else
                                <td class="text-end fw-bold">{{ ifNotZero($action->debit, true, ' €', '.', ' ') }}</td>
                            @endif

                            @if (!$action->correction)
                                @if ( $action->creditGain >= 0.01 )
                                    <td class="bg_color-super text-end fw-bold">{{ ifNotZero($action->creditGain, true, ' €', '.', ' ') }}</td>
                                @else
                                    <td class="text-end fw-bold visible_non">0.00 €</td>
                                @endif
                            @else
                                <td class="text-end fw-bold">{{ ifNotZero($action->creditGain, true, ' €', '.', ' ') }}</td>
                            @endif

                            <td class="d-flex justify-content-center align-items-center">
                                @if ( !$action->creditGain >= 0.01 && !$action->debit >= 0.01 )
                                    <a href="{{ route('delLigDetailParticipant', $action->id)}}">
                                        <i class="bi bi-recycle"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr id="tot-gen">
                        <th colspan="4"><h4>Totaux page</h4></th>
                        <th class="text-right"><h4 id="c1"></h4></th>
                        <th class="text-right"><h4 id="c2"></h4></th>
                        <th class="text-right"><h4 id="c3"></h4></th>
                    </tr>                            
                    <tr>
                        <th colspan="4"><h4>Totaux généraux</h4></th>
                        <th class="text-right"><h4 id="t1"></h4></th>
                        <th class="text-right"><h4 id="t2"></h4></th>
                        <th class="text-right"><h4 id="t3"></h4></th>
                    </tr>
                </tfoot>
            </table>
        </div>
            
    </div>

</div>

<script type="text/javascript">
    var table_participant;
    $(document).ready(function() {
        table_participant = $("#table_participant").DataTable({
            // Layout dans fichier externe
            layout: window.datatableLayout,
            language: window.datatableLangue,

            search: { caseInsensitive: true },
            pageLength: 25,
            lengthMenu: [[ 25, 50, 100, 150, -1], [ 25, 50, 100, 150, "--Tous--"]],
            colReorder: true,
            select: true,
            bSortCellsTop: true,
            autoWidth: false,
            order: [[0, 'desc']],
            drawCallback: function () {
            var api = this.api();
            var c1  = api.column( 4, {page:'current'} ).data().sum();
            var c2  = api.column( 5, {page:'current'} ).data().sum();
            var c3  = api.column( 6, {page:'current'} ).data().sum();
            c1    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(c1);
            c2    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(c2);
            c3    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(c3);
            $("#c1").html(c1);
            $("#c2").html(c2);
            $("#c3").html(c3);
            var t1 = api.column( 4, {filter: 'applied'} ).data().sum();
            var t2 = api.column( 5, {filter: 'applied'} ).data().sum();
            var t3 = api.column( 6, {filter: 'applied'} ).data().sum();
            t1    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(t1);
            t2    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(t2);
            t3    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(t3);
            $("#t1").html(t1);
            $("#t2").html(t2);
            $("#t3").html(t3);

            if (api.page.len() == -1 || api.page.info().pages == 1) {;
                $("#tot-gen").hide(1000);
            } else {
                $("#tot-gen").show(1000);
            }
        },
            // Selects
            initComplete: function () {
                $("#loading").hide();
                    var api = this.api();

                    const filters = [
                        { columnIndex: 2, selectId: "#s1-filter" },
                    ];

                    filters.forEach(({ columnIndex, selectId }) => {
                        const uniqueData = api.column(columnIndex).data().unique().sort();

                        uniqueData.each(function (d) {
                            if (d !== null && d !== "") {
                                $(selectId).append(`<option value="${d}">${d}</option>`);
                            }
                        });

                        $(selectId).on('change', function () {
                            const val = $.fn.dataTable.util.escapeRegex($(this).val());
                            api.column(columnIndex)
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });
                    });
            },

        });

        // Filtres input
        $('.filterrow input.input-filter').each(function() {
            const colIndex = $(this).closest('th').index();
            $(this).on('keyup change', function () {
                table_participant.column(colIndex).search(this.value).draw();
            });
        });

    });
    
    function makeExportButton(type, text) {
        return {
            extend: type,
            className: 'btn btn-sm btn-light border border-primary',
            text: text,
            exportOptions: {
                columns: ':visible'
            }
        };
    }
</script>

@endsection