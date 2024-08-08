@extends('layouts.main')
@section('content')

<style>
    .width-full {width: 100%;}
    .bg_color-negatif {background-color: rgba(82, 82, 82, 0.541) !important; color:azure !important;}
    .bg_color-null {background-color: rgba(226, 226, 226, 0.541) !important;}
    .bg_color-limit {background-color: rgba(255, 122, 122, 0.541) !important;}
    .bg_color-ok {background-color: rgba(255, 209, 71, 0.541) !important;}
    .bg_color-super {background-color: rgba(116, 255, 91, 0.541) !important;}
    .visible_non {opacity: 0;}
</style>

<div class="p-5 fw-bold">
    <div>
        <h3 class="mb-3">{{ $participant->pseudo }} ({{ $participant->firstName }} {{ $participant->firstName }})</h3>
    </div>
    <div class="d-flex">
        <div class="my-3 me-3">
            <div class="btn-group">
                <button type="button" data-toggle="collapse" data-target="#details_Collapse" aria-expanded="false" aria-controls="attenteCollapse" class="btn btn-info">
                    Détails personnelles
                </button>
            </div>
        </div>

        <div class="my-3 me-3">
            <div class="btn-group">
                <button type="button" data-toggle="collapse" data-target="#historique_Collapse" aria-expanded="true" aria-controls="attenteCollapse" class="btn btn-info">
                    Historique monétaire
                </button>
            </div>
        </div>

        <div class="my-3 me-3">
            <div class="btn-group">
                @if ($participant->actif == 1)
                    <form action="{{ route('participantDelete', $participant->id) }}" method="get">
                        @csrf
                        <div class="">
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Veux tu vraiment supprimer {{ $participant->pseudo }} ?');">Supprimer {{$participant->pseudo}}
                            </button>
                        </div>
                    </form>
                @else {{ route('participantActiver', $participant->id) }}
                    <form action="{{ route('participantActiver', $participant->id) }}" method="get">
                        @csrf
                        <div class="">
                            <button type="submit" class="btn btn-success"
                                onclick="return confirm('Veux tu vraiment annuler la suppression de {{ $participant->pseudo }} ?');">Rendre actif(ve) {{$participant->pseudo}}
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

    </div>

    <div class="pb-3 box collapse" id="details_Collapse">
        <form method="POST" action="{{ route('updateParticipant', $participant->id) }}">
            @csrf
            <div class="d-flex flex-row flex-wrap">
                <div class="form-group form-floating mb-3 me-3 d-flex flex-fill">
                    <input id="floatingfirstName" 
                        type="text" class="form-control flex-fill fw-bold" 
                        name="inputFirstName"
                        value="{{ $participant->firstName }}">
                    <label for="floatingfirstName" class="text-nowrap">Prenom</label>
                </div>

                <div class="form-group form-floating mb-3 me-3 d-flex flex-fill">
                    <input id="floatinglastName" 
                        type="text" 
                        class="form-control flex-fill fw-bold" 
                        name="inputLastName"
                        value="{{ $participant->lastName }}">
                    <label for="floatinglastName" class="text-nowrap">Nom</label>
                </div>

                <div class="form-group form-floating mb-3 d-flex flex-fill">
                    <input id="floatingpseudo" 
                        type="text" 
                        class="form-control flex-fill fw-bold" 
                        name="inputPseudo"
                        value="{{ $participant->pseudo }}">
                    <label for="floatingpseudo" class="text-nowrap">Pseudo</label>
                </div>
            </div>
            
            <div class="d-flex flex-row flex-wrap">
                <div class="form-group form-floating me-3">
                    <input id="floatingTel" 
                        type="text" class="form-control fw-bold" 
                        name="inputTel"
                        value="{{ $participant->tel }}"
                        >
                    <label for="floatingTel" class="text-nowrap">Téléphone</label>
                </div>
                <div class="form-group form-floating d-flex flex-fill">
                    <input id="floatingEmail" 
                        type="email" 
                        class="form-control flex-fill fw-bold" 
                        name="inputEmail"
                        value="{{ $participant->email }}"
                        autocomplete="email">
                    <label for="floatingEmail" class="text-nowrap">Email</label>
                </div>
            </div>

            <div class="d-flex flex-row flex-wrap">
                <div class="form-group form-floating d-flex flex-fill">
                    <input id="groups" 
                        type="text" 
                        class="form-control flex-fill fw-bold" 
                        @if ($participant_groups)
                            value="@foreach ($participant_groups as $data) {{ $data }} @endforeach"
                        @else
                            value="Pas de groups associé(s)"
                        @endif
                        readonly
                        >
                    <label for="groups" class="text-nowrap">Groups</label>
                </div>
            </div>

            <div class="d-flex flex-row flex-wrap">
                <div class="form-group form-floating mb-3 me-3">
                    <input id="floatingAmount_dispo" class="form-control text-end fw-bold"
                        value="{{ $participant->amount }} €" readonly>
                    <label for="floatingAmount_dispo" class="text-nowrap">Disponible</label>
                </div>

                <div class="form-group form-floating mb-3 me-3">
                    <input id="floatingAmount_joue" class="form-control text-end fw-bold"
                        value="{{ $participant->totalAmount }} €" readonly>
                    <label for="floatingAmount_joue" class="text-nowrap">Joué</label>
                </div>
                
                <div class="form-group form-floating flex-fill d-flex mb-3">
                    <button type="submit" class="btn btn-primary text-nowrap flex-fill">Enregistrer Changement</button>
                </div>
                
            </div>
        </form>

        <form action="{{ route('changeGroup', $participant->id) }}" method = "POST">
            @csrf
            <div class="border border-3 rounded-3 d-flex flex-column  ps-3 py-2 mb-3">
                <div class="">
                    <p class="mt-1 mb-2 text-nowrap">Changer le Groupe : </p>
                </div>
                <div class="d-flex flex-row text-nowrap flex-wrap flex-fill">
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
                <div class="form-group form-floating d-flex mt-3 d-flex flex-wrap">
                    <button type="submit" class="btn btn-primary text-nowrap me-3 mb-3 mb-xs-0 flex-fill">Changer groupe</button>
                    <a href="{{ route('participantGroupDelete', $participant->id) }}" class="flex-fill btn btn-danger text-nowrap me-3 mb-3">Enlever de tous les groupes</a>
                </div>
            </div>
        </form>
    </div>

    <hr class="my-4"></hr>

    <div class="pb-3 box collapse show" id="historique_Collapse">
        <div class="box-body table-responsive">
            <div class="mb-3">
                <h4>Historique des mouvement monetaire</h4>
            </div>
            <table id="table_participant" class="table table-bordered order-column table-hover compact nowrap cell-border small"><?php // Default dataTables  ?>
                <thead>
                    <tr>
                        <th class="text-center">Date</th>
                        <th class="text-center">Groupe</th>
                        <th class="text-center">Montant</th>
                        <th class="text-center">Credit</th>
                        <th class="text-center">Debit</th>
                        <th class="text-center">Credit Gain</th>
                    </tr>
                    <tr class="filterrow">
                        <th></th>
                        <th class="select-filter">
                            <select id="s1-filter" placeholder="Recherche" style="width: 100%; height:1.7rem;">
                                <option value="">Tous</option>
                            </select>
                        </th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($actions as $action)
                        <tr>
                            <td class="fw-bold text-center">{{ sql2display($action->created_at) }}</td>
                            <td class="fw-bold text-center">{{ $action->group_name }}</td>

                            @if ($action->amount < 0)
                                <td class="text-end fw-bold bg_color-negatif text-white">
                                    {{ ifNotZero($action->amount, true, ' €', '.', ' ') }}
                                </td>
                            @elseif ($action->amount == 0.00 || $action->amount == null)
                                <td class="text-end fw-bold bg_color-null">
                                    0.00 €
                                </td>
                            @elseif ($action->amount >= 0.01 && $action->amount <= 3.49 )
                                <td class="text-end fw-bold bg_color-limit">
                                    {{ ifNotZero($action->amount, true, ' €', '.', ' ') }}
                                </td>
                            @elseif ($action->amount >= 3.50 && $action->amount <= 9.99)
                                <td class="text-end fw-bold bg_color-ok">
                                    {{ ifNotZero($action->amount, true, ' €', '.', ' ') }}
                                </td>
                            @elseif ($action->amount >= 10.00)
                                <td class="text-end fw-bold bg_color-super">
                                    {{ ifNotZero($action->amount, true, ' €', '.', ' ') }}
                                </td>
                            @endif
                            
                            @if ( $action->credit >= 0.01 )
                                <td class="bg_color-super text-end fw-bold" >{{ ifNotZero($action->credit, true, ' €', '.', ' ') }}</td>
                            @else
                                <td class="text-end fw-bold visible_non">0.00 €</td>
                            @endif

                            @if ( $action->debit >= 0.01 )
                                <td class="bg_color-limit text-end fw-bold">{{ ifNotZero($action->debit, true, ' €', '.', ' ') }}</td>
                            @else
                                <td class="text-end fw-bold visible_non">0.00 €</td>
                            @endif

                            @if ( $action->creditGain >= 0.01 )
                                <td class="bg_color-super text-end fw-bold">{{ ifNotZero($action->creditGain, true, ' €', '.', ' ') }}</td>
                            @else
                                <td class="text-end fw-bold visible_non">0.00 €</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr id="tot-gen">
                        <th colspan="3"><h4>Totaux page</h4></th>
                        <th class="text-right"><h4 id="c2"></h4></th>
                        <th class="text-right"><h4 id="c3"></h4></th>
                        <th class="text-right"><h4 id="c4"></h4></th>
                    </tr>                            
                    <tr>
                        <th colspan="3"><h4>Totaux généraux</h4></th>
                        <th class="text-right"><h4 id="t2"></h4></th>
                        <th class="text-right"><h4 id="t3"></h4></th>
                        <th class="text-right"><h4 id="t4"></h4></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.datatables.net/plug-ins/2.1.2/api/sum().js"></script>
<script type="text/javascript">
    // $.fn.dataTable.moment( 'DD/MM/YYYY' );
    // $.fn.dataTable.moment( 'DD/MM/YY' );
    // $.fn.dataTable.moment( 'DD/MM/YY HH:mm:ss' );
    // $.fn.dataTable.moment( 'DD/MM/YYYY HH:mm:ss' );
    ///////////////////////////////////////
    var cols_number = [2, 3, 4, 5];
    var table = $("#table_participant").DataTable({
        language: {
            "sProcessing": "Traitement en cours...",
            "sSearch": "Rechercher&nbsp;:",
            "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
            "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
            "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            "sInfoPostFix": "",
            "sLoadingRecords": "Chargement en cours...",
            "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
            "sEmptyTable": "Pas de valeur",
            "oPaginate": {
                "sFirst": "Premier",
                "sPrevious": "Pr&eacute;c&eacute;dent",
                "sNext": "Suivant",
                "sLast": "Dernier"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
            }
        },
        lengthMenu: [[10, 15, 20, 50, 100, 150, -1], [10, 15, 20, 50, 100, 150, "tous les"]],
        colReorder: true,
        select: true,
        bSortCellsTop: true,
        dom: 'B<"clear">lfrtip',
        autoWidth: false,
        buttons: [
            { extend: 'copy', footer: true },
            { extend: 'print', footer: true },
            { extend: 'pdf', footer: true }, //, exportOptions: { columns: [1,2] }}         
            { extend: 'excel', footer: true },
        ],  
        order: [],

        columnDefs: [
                { type: 'formatted-num', targets: cols_number },
                { type: 'numeric-comma', targets: cols_number },
            ],
        // Totaux
        drawCallback: function () {
            var api = this.api();
            var c2  = api.column( 3, {page:'current'} ).data().sum();
            var c3  = api.column( 4, {page:'current'} ).data().sum();
            var c4  = api.column( 5, {page:'current'} ).data().sum();
            c2    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(c2);
            c3    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(c3);
            c4    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(c4);
            $("#c2").html(c2);
            $("#c3").html(c3);
            $("#c4").html(c4);
            var t2 = api.column( 3, {filter: 'applied'} ).data().sum();
            var t3 = api.column( 4, {filter: 'applied'} ).data().sum();
            var t4 = api.column( 5, {filter: 'applied'} ).data().sum();
            t2    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(t2);
            t3    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(t3);
            t4    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(t4);
            $("#t2").html(t2);
            $("#t3").html(t3);
            $("#t4").html(t4);

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
            var elt = api.column(1).data().unique();
            for (var i = 0; i < elt.length; i++) {
                var option = '<option value="'+elt[i]+'">'+elt[i]+'</option>'
                $(option).appendTo("#s1-filter");
            }
        }
    });

    // Recherche input
    $.each($('.input-filter', table.table().header()), function () {
        var column = table.column($(this).index());

        var columnIndex = $(this).data('column');
        
        $('input', this).on('keyup change', function () {
            if (column.search() !== this.value) {
                column
                        .search(this.value)
                        .draw();
            }
            console.log(columnIndex);
        });
    });

    // Recherche select
    $.each($('.select-filter', table.table().header()), function () {
        var column = table.column($(this).index());
        $( 'select', this).on( 'change', function () {
            if ( column.search() !== this.value ) {
                column
                    .search( this.value )
                    .draw();
            } else if ( column.search() !== "" ) {
                column
                    .draw();
            }
        });
    }); 

</script>

@endsection