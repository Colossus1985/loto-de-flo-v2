@extends('layouts.main')
@section('content')

<style>
    .width-full {width: 100%;}
    .bg_color-negatif {background-color: rgba(82, 82, 82, 0.541) !important; color:azure !important;}
    .bg_color-null {background-color: rgba(226, 226, 226, 0.541) !important;}
    .bg_color-limit {background-color: rgba(255, 122, 122, 0.541) !important;}
    .bg_color-ok {background-color: rgba(255, 209, 71, 0.541) !important;}
    .bg_color-super {background-color: rgba(116, 255, 91, 0.541) !important;}

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

<div class="card">
    <div class="card-header mb-3">
        <div class="d-flex flex-row justify-content-between my-3">
            <div>
                <h2>Historique des gains 🥳🥳🥳🥳🥳🥳🥳🥳🥳</h2>
            </div>
            <div>
                <button type="button" class="btn btn-light py-0 d-flex align-items-center border border-3" data-bs-toggle="modal" data-bs-target="#modalAddGain">
                    ➕ <span class="fs-3 fw-bold ms-2">de Gains</span> 
                </button>
            </div>
        </div>
    </div>
        
    <div class="card-body table-responsive bg-light p-2 rounded">
        <table id="table_gainsHistory" class="table table-bordered order-column table-hover compact nowrap cell-border small"><?php // Default dataTables  ?>
            <thead>
                <tr>
                    <th class="text-center">Date</th>
                    <th class="text-center">Groupe</th>
                    <th class="text-center">Gain</th>
                    <th class="text-center">Nb Participants</th>
                    <th class="text-center">Gain individuel</th>
                </tr>
                <tr class="filterrow">
                    <th></th>
                    <th class="select-filter">
                        <select class="column-filter form-select form-select-sm" id="ds-filter" placeholder="Recherche">
                            <option value="">Tous</option>
                        </select>
                    </th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gains as $gain)
                    <tr>
                        <td class="text-center align-middle">
                            <span>{{ sql2display($gain->date) }}</span>
                        </td>
                        <td>{{ $gain->nameGroup }}</td>
                        <td class="text-end align-middle fw-bold">
                            @if ($gain->amount == 0)
                                <span>0.00 €</span>
                            @else
                                <span>{{ ifNotZero($gain->amount, true, ' €', '.', ' ') }} </span>
                            @endif
                        </td>
                        <td class="text-end align-middle">
                            <span>{{ $gain->nbPersonnes }}</span>
                        </td>
                        <td class="text-end align-middle fw-bold">
                            @if ($gain->gainIndividuel == 0)
                                <span>0.00 €</span>
                            @else
                                <span>{{ ifNotZero($gain->gainIndividuel, true, ' €', '.', ' ') }} </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr id="tot-gen">
                    <th colspan="2"><h4>Gains page</h4></th>
                    <th class="text-right"><h4 id="c1"></h4></th>
                    <th></th>
                    <th></th>
                </tr>                            
                <tr>
                    <th colspan="2"><h4>Gains totaux</h4></th>
                    <th class="text-right border-gold"><h4 id="t1"></h4></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@include('modals.addGain')

<script src="https://cdn.datatables.net/plug-ins/2.1.2/api/sum().js"></script>
<script type="text/javascript">
    var cols_number = [2, 4];
    var table = $("#table_gainsHistory").DataTable({
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
        
        lengthMenu: [[15, 10, 20, 50, 100, 150, -1], [15, 10, 20, 50, 100, 150, "tous les"]],
        colReorder: true,
        select: true,
        bSortCellsTop: true,
        autoWidth: false,
        order: [],

        columnDefs: [
                { type: 'formatted-num', targets: cols_number },
                { type: 'numeric-comma', targets: cols_number },
            ],
        // Totaux
        drawCallback: function () {
            var api = this.api();
            var c1  = api.column( 2, {page:'current'} ).data().sum();
            c1    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(c1);
            $("#c1").html(c1);
            var t1 = api.column( 2, {filter: 'applied'} ).data().sum();
            t1    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(t1);
            $("#t1").html(t1);

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