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
    <div class="card-header">
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
        
    <div class="card-body table-responsive bg-light p-2 rounded pt-3">
        <table id="table_gainsHistory" class="table table-bordered order-column table-hover compact nowrap cell-border small"><?php // Default dataTables  ?>
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Groupe</th>
                    <th class="text-center">Gain</th>
                    <th class="text-center">Nb Participants</th>
                    <th class="text-center">Gain individuel</th>
                    <th></th>
                </tr>
                <tr class="filterrow">
                    <th><input type="text" class="input-filter form-control form-control-sm" placeholder="Rechercher"></th>
                    <th></th>
                    <th class="select-filter">
                        <select class="column-filter form-select form-select-sm" id="s1-filter" placeholder="Recherche">
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
                @foreach ($gains as $gain)
                    <tr>
                        <td style="width: 4rem;"">
                            <span>{{ $gain->id }}</span>
                        </td>
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
                        <td class="d-flex justify-content-center align-items-center">
                            <a href="{{ route('delLigGain', $gain->id)}}">
                                <i class="bi bi-recycle"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr id="tot-gen">
                    <th colspan="3"><h4>Gains page</h4></th>
                    <th class="text-right"><h4 id="c1"></h4></th>
                    <th colspan="3"></th>
                </tr>                            
                <tr>
                    <th colspan="3"><h4>Gains totaux</h4></th>
                    <th class="text-right border-gold"><h4 id="t1"></h4></th>
                    <th colspan="3"></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@include('modals.addGain')

<script type="text/javascript">
    var lignes;
    $(document).ready(function() {
        lignes = $("#table_gainsHistory").DataTable({
            // Layout dans fichier externe
            layout: window.datatableLayout,
            language: window.datatableLangue,

            search: { caseInsensitive: true },
            pageLength: 20,
            lengthMenu: [[ 20, 50, 100, 150, -1], [ 20, 50, 100, 150, "--Tous--"]],
            colReorder: true,
            select: true,
            bSortCellsTop: true,
            autoWidth: false,
            order: [[0, 'desc']],
            drawCallback: function () {
                var api = this.api();
                var c1 = api.column(3, { page: 'current' }).data().sum();
                c1 = new Intl.NumberFormat("fr-FR", { style: "currency", currency: "EUR" }).format(c1);
                $("#c1").html(c1);
                var t1 = api.column(3, { filter: 'applied' }).data().sum();
                t1 = new Intl.NumberFormat("fr-FR", { style: "currency", currency: "EUR" }).format(t1);
                $("#t1").html(t1);
                if (api.page.len() == -1 || api.page.info().pages == 1) {
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
                lignes.column(colIndex).search(this.value).draw();
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