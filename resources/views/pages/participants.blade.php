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

<div class="p-5">
    <div class="mb-3">
        <h2>Liste des Participants</h2>
    </div>
    <div class="box-body table-responsive">
        <table id="table_participants" class="table table-bordered order-column table-hover compact nowrap cell-border small"><?php // Default dataTables  ?>
            <thead>
                <tr>
                    <th class="text-center">Groupe</th>
                    <th class="text-center">Pseudo</th>
                    <th class="text-center">Disponible</th>
                    <th class="text-center"></th>
                    <th class="text-center">Joué</th>
                </tr>
                <tr class="filterrow">
                    <th class="s1_select-filter">
                        <select id="s1-filter" placeholder="Recherche" style="width: 100%; height:1.7rem;">
                            <option value="">Tous</option>
                        </select>
                    </th>
                    <th class="s1_input-filter" data-column="2"><input type="text" placeholder="Recherche" style="width: 100%;"></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($participants as $participant)
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
                        <td 
                            @if ($participant->nameGroup == null || $participant->nameGroup == "" || $participant->nameGroup == "null")
                                class="text_nowrap align-middle d-flex align-items-center justify-content-center">
                                pas de groupe
                            @else
                                class="text_nowrap align-middle d-flex align-items-center justify-content-center">
                                {{ $participant->nameGroup }}
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('participant', [$participant->id, $participant->actif]) }}" 
                                title="voir détails" 
                                class="ui-tooltip btn-sm btn-info d-flex justify-content-center align-items-center mt-1 text-decoration-none">
                                {{ $participant->pseudo }}
                            </a>
                        </td>

                        <td class="fw-bold d-flex justify-content-end align-items-center pe-2" style="height: 2.5rem;">
                            @if ( $participant->amount == 0)
                                0.00 € 
                            @else 
                                {{ ifNotZero($participant->amount, true, ' €', '.', ' ') }}
                            @endif
                        </td>

                        <td>
                            <div class="d-flex flex-row justify-content-center align-items-center">
                                <button type="button" 
                                    class="my-green_light widht-full btn-sm btn-light me-3 ms-1 border ui-tooltip" 
                                    title="crediter" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalAddMoney{{$participant->id}}"
                                    style="width: 100%;">
                                    ➕</button>
                                <button type="button" 
                                    class="widht-full btn-sm btn-light me-1 border ui-tooltip" 
                                    title="debiter" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalDebitMoney{{$participant->id}}"
                                    style="width: 100%;">
                                    ➖</button>
                            </div>
                        </td>

                        <td 
                            @if ($participant->totalAmount == '' || $participant->totalAmount == 0)
                                class="align-middle text-end fw-bold">
                                    0.00 €
                            @else 
                                class="align-middle text-end fw-bold">
                                    {{ ifNotZero($participant->totalAmount, true, ' €', '.', ' ') }}
                            @endif
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
                    <th></th>
                    <th class="text-right"><h4 id="c2"></h4></th>
                </tr>                            
                <tr>
                    <th colspan="2"><h4>Totaux généraux</h4></th>
                    <th class="text-right"><h4 id="t1"></h4></th>
                    <th></th>
                    <th class="text-right"><h4 id="t2"></h4></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="my-3">
        <div class="btn-group">
            <button type="button" @if ( count($participants_del) > 0 ) data-toggle="collapse" data-target="#participants_delCollapse" aria-expanded="false" aria-controls="attenteCollapse" class="btn btn-danger" @else class="btn btn-primary" disabled @endif>
                {{ count($participants_del) }} Participant(s) rendu inactif(s) @if ( count($participants_del) > 0 ) <i class="fa-solid fa-magnifying-glass-plus"></i> @endif
            </button>
        </div>
    </div>
    
    <div class="mt-3">
        <div class="box collapse" id="participants_delCollapse">
            <div class="box-body table-responsive">
                <table id="table_participants_del" class="table table-bordered order-column table-hover compact nowrap cell-border small"><?php // Default dataTables  ?>
                    <thead>
                        <tr>
                            <th class="text-center">Groupe</th>
                            <th class="text-center">Pseudo</th>
                            <th class="text-center">Disponible</th>
                            <th class="text-center"></th>
                            <th class="text-center">Joué</th>
                        </tr>
                        <tr class="filterrow">
                            <th class="a1_select-filter">
                                <select id="a1-filter" placeholder="Recherche" style="width: 100%; height:1.7rem;">
                                    <option value="">Tous</option>
                                </select>
                            </th>
                            <th class="a1_input-filter" data-column="2"><input type="text" placeholder="Recherche" style="width: 100%;"></th>
                            <th></th>
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
                                <td 
                                    @if ($participant->nameGroup == null || $participant->nameGroup == "" || $participant->nameGroup == "null")
                                        class="text_nowrap align-middle d-flex align-items-center justify-content-center">
                                        pas de groupe
                                    @else
                                        class="text_nowrap align-middle d-flex align-items-center justify-content-center">
                                        {{ $participant->nameGroup }}
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('participant', [$participant->id, $participant->actif]) }}" 
                                        title="voir détails" 
                                        class="ui-tooltip btn-sm btn-info d-flex justify-content-center align-items-center mt-1 text-decoration-none">
                                        {{ $participant->pseudo }}
                                    </a>
                                </td>
        
                                <td class="fw-bold d-flex justify-content-end align-items-center pe-2" style="height: 2.5rem;">
                                    @if ( $participant->amount == 0)
                                        0.00 € 
                                    @else 
                                        {{ ifNotZero($participant->amount, true, ' €', '.', ' ') }}
                                    @endif
                                </td>
        
                                <td>
                                    <div class="d-flex flex-row justify-content-center align-items-center">
                                        <button type="button" 
                                            class="my-green_light widht-full btn-sm btn-light me-3 ms-1 border ui-tooltip" 
                                            title="crediter" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalAddMoney{{$participant->id}}"
                                            style="width: 100%;">
                                            ➕</button>
                                        <button type="button" 
                                            class="widht-full btn-sm btn-light me-1 border ui-tooltip" 
                                            title="debiter" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalDebitMoney{{$participant->id}}"
                                            style="width: 100%;">
                                            ➖</button>
                                    </div>
                                </td>
        
                                <td 
                                    @if ($participant->totalAmount == '' || $participant->totalAmount == 0)
                                        class="align-middle text-end fw-bold">
                                            0.00 €
                                    @else 
                                        class="align-middle text-end fw-bold">
                                            {{ ifNotZero($participant->totalAmount, true, ' €', '.', ' ') }}
                                    @endif
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
                            <th></th>
                            <th class="text-right"><h4 id="c2"></h4></th>
                        </tr>                            
                        <tr>
                            <th colspan="2"><h4>Totaux généraux</h4></th>
                            <th class="text-right"><h4 id="t1"></h4></th>
                            <th></th>
                            <th class="text-right"><h4 id="t2"></h4></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
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
    var cols_number = [2, 4];
    var table = $("#table_participants").DataTable({
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
        order: [[ 0, 'desc' ]],

        columnDefs: [
                { type: 'formatted-num', targets: cols_number },
                { type: 'numeric-comma', targets: cols_number },
            ],
        // Totaux
        drawCallback: function () {
            var api = this.api();
            var c1  = api.column( 2, {page:'current'} ).data().sum();
            var c2  = api.column( 4, {page:'current'} ).data().sum();
            c1    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(c1);
            c2    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(c2);
            $("#c1").html(c1);
            $("#c2").html(c2);
            var t1 = api.column( 2, {filter: 'applied'} ).data().sum();
            var t2 = api.column( 4, {filter: 'applied'} ).data().sum();
            t1    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(t1);
            t2    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(t2);
            $("#t1").html(t1);
            $("#t2").html(t2);

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
            var elt = api.column(0).data().unique();
            for (var i = 0; i < elt.length; i++) {
                var option = '<option value="'+elt[i]+'">'+elt[i]+'</option>'
                $(option).appendTo("#s1-filter");
            }
        }
    });

    // Recherche input
    $.each($('.s1_input-filter', table.table().header()), function () {
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
    $.each($('.s1_select-filter', table.table().header()), function () {
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

    // ============================================================

    var table = $("#table_participants_del").DataTable({
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
        order: [[ 0, 'desc' ]],

        columnDefs: [
                { type: 'formatted-num', targets: cols_number },
                { type: 'numeric-comma', targets: cols_number },
            ],
        // Totaux
        drawCallback: function () {
            var api = this.api();
            var c1  = api.column( 2, {page:'current'} ).data().sum();
            var c2  = api.column( 4, {page:'current'} ).data().sum();
            c1    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(c1);
            c2    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(c2);
            $("#c1").html(c1);
            $("#c2").html(c2);
            var t1 = api.column( 2, {filter: 'applied'} ).data().sum();
            var t2 = api.column( 4, {filter: 'applied'} ).data().sum();
            t1    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(t1);
            t2    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(t2);
            $("#t1").html(t1);
            $("#t2").html(t2);

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
            var elt = api.column(0).data().unique();
            for (var i = 0; i < elt.length; i++) {
                var option = '<option value="'+elt[i]+'">'+elt[i]+'</option>'
                $(option).appendTo("#a1-filter");
            }
        }
    });

    // Recherche input
    $.each($('.a1_input-filter', table.table().header()), function () {
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
    $.each($('.a1_select-filter', table.table().header()), function () {
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