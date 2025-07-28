@extends('layouts.main')
@section('content')

<style>
    .width-full {width: 100%;}
    .bg_color-negatif {background-color: rgba(82, 82, 82, 0.541) !important; color:azure !important;}
    .bg_color-null {background-color: rgba(226, 226, 226, 0.541) !important;}
    .bg_color-limit {background-color: rgba(255, 122, 122, 0.541) !important;}
    .bg_color-ok {background-color: rgba(255, 209, 71, 0.541) !important;}
    .bg_color-super {background-color: rgba(116, 255, 91, 0.541) !important;}
</style>

<div class="card">
    <div class="card-header">
        <h3>Les participants</h3>
    </div>
    <div class="card-body table-responsive bg-light rounded p-2">
        <table id="table_participants" class="table table-bordered order-column table-hover compact nowrap cell-border small"><?php // Default dataTables  ?>
            <thead>
                <tr>
                    <th class="text-center">Groupe</th>
                    <th class="text-center">Pseudo</th>
                    <th class="text-center">Disponible</th>
                    <th class="text-center">Donné</th>
                    <th class="text-center">Joué</th>
                    <th class="text-center">Gains</th>
                    <th class="text-center"></th>
                    
                </tr>
                <tr class="filterrow">
                    <th class="select-filter">
                        <select class="column-filter form-select form-select-sm" id="s1-filter" placeholder="Recherche">
                            <option value="">Tous</option>
                        </select>
                    </th>
                    <th><input type="text" class="input-filter form-control form-control-sm" placeholder="Rechercher"></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach ($participants as $i => $participant)
                    <tr @if ( $participant->total_dispo < 0)
                            class="bg_color-negatif"
                        @elseif ( $participant->total_dispo == null || $participant->total_dispo == 0)
                            class="bg_color-null"
                        @elseif ( $participant->total_dispo <= 3.49)
                            class="bg_color-limit"
                        @elseif ( $participant->total_dispo < 10 && $participant->total_dispo >= 3.5)
                            class="bg_color-ok"
                        @else
                            class="bg_color-super"
                        @endif
                    >
                        <td style="padding-top: 0.6rem;"
                            @if ($participant->group_name == null || $participant->group_name == "" || $participant->group_name == "null")
                                class="text_nowrap align-middle d-flex align-items-center justify-content-center">
                                pas de groupe
                            @else
                                class="text_nowrap align-middle d-flex align-items-center justify-content-center">
                                {{ $participant->group_name }}
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('participant', [$participant->id, 1]) }}" 
                                title="voir détails" 
                                class="ui-tooltip btn-sm btn-info d-flex justify-content-center align-items-center mt-1 text-decoration-none">
                                {{ $participant->pseudo }}
                            </a>
                        </td>

                        <td class=" pe-2 text-end" style="padding-top: 0.6rem;">
                            @if ( $participant->total_dispo == 0)
                                0.00 € 
                            @else 
                                {{ ifNotZero($participant->total_dispo, true, ' €', '.', ' ') }}
                            @endif
                        </td>

                        <td class=" pe-2 text-end" style="padding-top: 0.6rem;">
                            @if ( $participant->total_credit == 0)
                                0.00 € 
                            @else 
                                {{ ifNotZero($participant->total_credit, true, ' €', '.', ' ') }}
                            @endif
                        </td>

                        <td class=" pe-2 text-end text-danger" style="padding-top: 0.6rem;">
                            @if ( $participant->total_jouee == 0)
                                0.00 € 
                            @else 
                                - {{ ifNotZero($participant->total_jouee, true, ' €', '.', ' ') }}
                            @endif
                        </td>

                        <td class=" pe-2 text-end text-success" style="padding-top: 0.6rem;">
                            @if ( $participant->total_gain == 0)
                                0.00 € 
                            @else 
                                + {{ ifNotZero($participant->total_gain, true, ' €', '.', ' ') }}
                            @endif
                        </td>

                        <td>
                            <div class="d-flex flex-row justify-content-center align-items-center">
                                <button type="button" 
                                    class="my-green_light widht-full btn-sm btn-light me-3 ms-1 border ui-tooltip" 
                                    title="crediter" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalAddMoney{{$i}}"
                                    data-participant-name="{{$participant->group_name}}"
                                    style="width: 100%;">
                                    ➕</button>
                                <button type="button" 
                                    class="widht-full btn-sm btn-light me-1 border ui-tooltip" 
                                    title="debiter" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalDebitMoney{{$i}}"
                                    data-participant-name="{{$participant->group_name}}"
                                    style="width: 100%;">
                                    ➖</button>
                            </div>
                        </td>
                        
                    </tr>
                    @include('modals.addMoney')
                    @include('modals.debitMoney')
                @endforeach
            </tbody>
            <tfoot>
                <tr id="tot-gen">
                    <th colspan="2"><h4>Totaux page</h4></th>
                    <th class="text-right"><h4 id="c1"></h4></th>
                    <th class="text-right"><h4 id="c2"></h4></th>
                    <th class="text-right text-danger"><h4 id="c3"></h4></th>
                    <th class="text-right text-success"><h4 id="c4"></h4></th>
                    <th></th>
                </tr>                            
                <tr>
                    <th colspan="2"><h4>Totaux généraux</h4></th>
                    <th class="text-right"><h4 id="t1"></h4></th>
                    <th class="text-right"><h4 id="t2"></h4></th>
                    <th class="text-right text-danger"><h4 id="t3"></h4></th>
                    <th class="text-right text-success"><h4 id="t4"></h4></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>

        <button type="button" 
            @if ( count($participants_del) > 0 ) 
                data-toggle="collapse" 
                data-target="#participants_delCollapse" 
                aria-expanded="false" 
                aria-controls="attenteCollapse" 
                class="btn btn-danger my-3" 
            @else 
                class="btn btn-primary my-3" 
                disabled 
            @endif>
            {{ count($participants_del) }} Participant(s) rendu inactif(s) @if ( count($participants_del) > 0 ) <i class="fa-solid fa-magnifying-glass-plus"></i> @endif
        </button>
    </div>
</div>

<div class="card collapse" id="participants_delCollapse">
    <div class="card-header fw-bold fs-4">
        <span>Particiapant(s) rendu inactif(v)(s)</span>
    </div>
    <div class="card-body table-responsive">
        <table id="table_participants_del" class="table table-bordered order-column table-hover compact nowrap cell-border small"><?php // Default dataTables  ?>
            <thead>
                <tr>
                    <th class="text-center">Pseudo</th>
                    <th class="text-center">Disponible</th>
                    <th class="text-center">Joué</th>
                </tr>
                <tr class="filterrow_table_participants_del">
                    <th><input type="text" class="input-filter form-control form-control-sm" placeholder="Rechercher"></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($participants_del as $participant)
                    <tr @if ( $participant->amount < 0)
                            class="bg_color-negatif"
                        @elseif ( $participant->amount == null || $participant->amount == 0)
                            class="bg_color-null"
                        @elseif ( $participant->amount <= 3.49)
                            class="bg_color-limit"
                        @elseif ( $participant->amount < 10 && $participant->amount >= 3.5)
                            class="bg_color-ok"
                        @else
                            class="bg_color-super"
                        @endif
                    >
                        <td>
                            <a href="{{ route('participant', [$participant->id, $participant->actif]) }}" 
                                title="voir détails" 
                                class="ui-tooltip btn-sm btn-info d-flex justify-content-center align-items-center mt-1 text-decoration-none"
                                target="blanc">
                                {{ $participant->pseudo }}
                            </a>
                        </td>

                        <td class="text-end pe-2" style="padding-top: 0.7rem;">
                            @if ( $participant->amount == 0)
                                0.00 € 
                            @else 
                                {{ ifNotZero($participant->amount, true, ' €', '.', ' ') }}
                            @endif
                        </td>

                        <td class="text-end pe-2" style="padding-top: 0.7rem;">
                            @if ($participant->totalAmount == '' || $participant->totalAmount == 0)
                                    0.00 €
                            @else 
                                {{ ifNotZero($participant->totalAmount, true, ' €', '.', ' ') }}
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<script src="https://cdn.datatables.net/plug-ins/2.1.2/api/sum().js"></script>
<script type="text/javascript">
    var cols_number_1 = [2, 3, 4, 5];
    var table_participants
    table_participants = $("#table_participants").DataTable({
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
                "sFirst": "<<",
                "sPrevious": "<",
                "sNext": ">",
                "sLast": ">>"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
            }
        },
        dom: window.datatableDom,

        buttons: [
            { extend: 'copy', footer: true },
            { extend: 'print', footer: true },
            { extend: 'pdf', footer: true }, //, exportOptions: { columns: [1,2] }}         
            { extend: 'excel', footer: true },
        ],

        lengthMenu: [[15, 20, 50, 100, 150, -1], [15, 20, 50, 100, 150, "tous les"]],
        colReorder: true,
        select: true,
        bSortCellsTop: true,
        
        autoWidth: false,
        order: [],

        columnDefs: [
                { type: 'formatted-num', targets: cols_number_1 },
                { type: 'numeric-comma', targets: cols_number_1 },
            ],
        // Totaux
        drawCallback: function () {
            var api = this.api();
            var c1  = api.column( 2, {page:'current'} ).data().sum();
            var c2  = api.column( 3, {page:'current'} ).data().sum();
            var c3  = api.column( 4, {page:'current'} ).data().sum();
            var c4  = api.column( 5, {page:'current'} ).data().sum();
            c1    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(c1);
            c2    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(c2);
            c3    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(c3);
            c4    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(c4);
            $("#c1").html(c1);
            $("#c2").html(c2);
            $("#c3").html(c3);
            $("#c4").html(c4);
            var t1 = api.column( 2, {filter: 'applied'} ).data().sum();
            var t2 = api.column( 3, {filter: 'applied'} ).data().sum();
            var t3 = api.column( 4, {filter: 'applied'} ).data().sum();
            var t4 = api.column( 5, {filter: 'applied'} ).data().sum();
            t1    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(t1);
            t2    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(t2);
            t3    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(t3);
            t4    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(t4);
            $("#t1").html(t1);
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

            const filters = [
                { columnIndex: 0, selectId: "#s1-filter" },
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
    $('.filterrow .input-filter').each(function () {
        var columnIndex = $(this).closest('th').index(); // ou simplement $(this).parent().index();
        var column = table_participants.column(columnIndex);

        $(this).on('keyup change', function () {
            if (column.search() !== this.value) {
                column.search(this.value).draw();
            }
        });
    });

    // ============================================================

    var table_participants_del;
    var table_participants_del = $("#table_participants_del").DataTable({
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
                "sFirst": "<<",
                "sPrevious": "<",
                "sNext": ">",
                "sLast": ">>"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
            }
        },
        dom: window.datatableDom,
        buttons: window.datatableButtons,

        lengthMenu: [[10, 15, 20, 50, 100, 150, -1], [10, 15, 20, 50, 100, 150, "tous les"]],
        colReorder: true,
        select: true,
        bSortCellsTop: true,
        autoWidth: false,
        buttons: [
            { extend: 'copy', footer: true },
            { extend: 'print', footer: true },
            { extend: 'pdf', footer: true }, //, exportOptions: { columns: [1,2] }}         
            { extend: 'excel', footer: true },
        ],  
        order: [],
    });

    // Filtres input
    $('.filterrow_table_participants_del .input-filter').each(function () {
        var columnIndex = $(this).closest('th').index();
        var column = table_participants_del.column(columnIndex);
        $(this).on('keyup change', function () {
            if (column.search() !== this.value) {
                column.search(this.value).draw();
                //=== Mettre à jour le compteur
                // var info = table_participants_del.page.info();
                // $('#nb-select').html(info.recordsDisplay);
            }
        });
    });



</script>

@endsection