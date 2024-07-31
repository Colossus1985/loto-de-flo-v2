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

<div class="mx-5 fw-bold">
    <form method="POST" action="{{ route('updateParticipant', $participant->id) }}">
        @csrf
        <div class="d-flex flex-row">
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
        
        <div class="d-flex flex-row">
            <div class="form-group form-floating me-3">
                <input id="floatingTel" 
                    type="text" class="form-control fw-bold" 
                    name="inputTel"
                    value="{{ $participant->tel }}"
                    >
                <label for="floatingTel" class="text-nowrap">Téléphone</label>
            </div>
            <div class="form-group form-floating me-3 d-flex flex-fill">
                <input id="floatingEmail" 
                    type="email" 
                    class="form-control flex-fill fw-bold" 
                    name="inputEmail"
                    value="{{ $participant->email }}"
                    autocomplete="email">
                <label for="floatingEmail" class="text-nowrap">Email</label>
            </div>
            <div class="d-flex flex-row ">
                <div class="form-group form-floating d-flex flex-fill me-0">
                    @if ($participant->nameGroup == null || $participant->nameGroup == "null" || $participant->nameGroup == "") 
                        <input id="floatingNameGroup" type="text" class="form-control flex-fill fw-bold" name="inputNameGroupNew"
                            value="Pas de groupe" readonly>
                        <label for="floatingNameGroup" class="text-nowrap">Groupe</label>
                    @else
                        <input id="floatingNameGroup" type="text" class="form-control flex-fill fw-bold" name="inputNameGroupNew"
                            value="{{ $participant->nameGroup }}" readonly>
                        <label for="floatingNameGroup" class="text-nowrap">Groupe</label>
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex flex-row">
            <div class="form-group form-floating mb-3 me-3 d-flex flex-fill">
                <input id="floatingPassword" 
                    type="password" maxlength="20" 
                    minlength="3" 
                    class="form-control ui-tooltip" 
                    title="entre 3 et 20 charactères" 
                    name="inputPasswordActuel"
                    placeholder="Password"
                    autocomplete="current-password">
                <label for="floatingPassword">actuel Password</label>
            </div>
            <div class="form-group form-floating mb-3 me-3 d-flex flex-fill">
                <input id="floatingPasswordNew" 
                    type="password" 
                    maxlength="20" 
                    minlength="3" 
                    class="form-control ui-tooltip" 
                    title="entre 3 et 20 charactères" 
                    name="inputPassword"
                    placeholder="Password"
                    autocomplete="new-password" >
                <label for="floatingPasswordNew">Nouveau Password</label>
            </div>
            <div class="form-group form-floating mb-3 d-flex flex-fill">
                <input id="floatingConfirmPassword" 
                    type="password"    
                    maxlength="20" 
                    minlength="3" 
                    class="form-control ui-tooltip" 
                    title="entre 3 et 20 charactères"
                    name="inputPassword_confirmation" 
                    autocomplete="new-password" 
                    placeholder="Confirm Password">
                <label for="floatingConfirmPassword">Confirmer Password</label>
            </div>
        </div>

        <div class="d-flex flex-row">
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
    <div>
        <form action="{{ route('changeGroup', $participant->id) }}" method = "POST">
            @csrf
            <div class="border border-3 rounded-3 d-flex flex-column  ps-3 py-2 mb-3">
                <div class="">
                    <p class="mt-1 mb-2 text-nowrap">Changer le Groupe : </p>
                </div>
                <div class="d-flex flex-row text-nowrap flex-wrap flex-fill">
                    <div class="form-check form-switch bg-warning rounded-2 me-3 ms-1 text-nowrap">
                        <input class="form-check-input me-1"
                            type="radio" 
                            name="inputNameGroupNew" 
                            role="switch" 
                            id="group_null" 
                            value="null">
                        <label class="form-check-label me-2 text-nowrap" for="group_null">Pas de groupe</label>
                    </div>
                    @foreach ($groups as $i => $group)
                        <div class="ms-1 form-check form-switch me-3">
                            <input class="form-check-input me-2"
                                type="radio" 
                                name="inputNameGroupNew" 
                                role="switch" 
                                id="flexSwitchNameGroup_{{$i}}" 
                                value="{{ $group->nameGroup }}">
                            <label class="form-check-label text-nowrap" for="flexSwitchNameGroup_{{$i}}">{{ $group->nameGroup }}</label>
                        </div>
                    @endforeach
                </div>
                <div class="form-group form-floating d-flex mt-3">
                    <button type="submit" class="btn btn-primary text-nowrap">Changer groupe</button>
                </div>
            </div>
        </form>
    </div>

    <div>
        <div class="box-body table-responsive">
            <table id="table_participant" class="table table-bordered order-column table-hover compact nowrap cell-border small"><?php // Default dataTables  ?>
                <thead>
                    <tr>
                        <th class="text-center">Date</th>
                        <th class="text-center">Montant</th>
                        <th class="text-center">Credit</th>
                        <th class="text-center">Debit</th>
                        <th class="text-center">Credit Gain</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($actions as $action)
                        <tr>
                            <td class="fw-bold text-center">{{ $action->date }}</td>

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
                        <th colspan="2"><h4>Totaux page</h4></th>
                        <th class="text-right"><h4 id="c2"></h4></th>
                        <th class="text-right"><h4 id="c3"></h4></th>
                        <th class="text-right"><h4 id="c4"></h4></th>
                    </tr>                            
                    <tr>
                        <th colspan="2"><h4>Totaux généraux</h4></th>
                        <th class="text-right"><h4 id="t2"></h4></th>
                        <th class="text-right"><h4 id="t3"></h4></th>
                        <th class="text-right"><h4 id="t4"></h4></th>
                    </tr>
                </tfoot>
            
        </table>
    </div>
    {{-- {{ $actions->links() }} --}}
    

    <form action="{{ route('participantDelete', $participant->id) }}" method="get">
        @csrf
        @method('DELETE')
        <div class="">
            <button type="submit" class="btn btn-danger"
                onclick="return confirm('Veux tu vraiment supprimer {{ $participant->pseudo }} ?');">Supprimer {{$participant->pseudo}}
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.datatables.net/plug-ins/2.1.2/api/sum().js"></script>
<script type="text/javascript">
    // $.fn.dataTable.moment( 'DD/MM/YYYY' );
    // $.fn.dataTable.moment( 'DD/MM/YY' );
    // $.fn.dataTable.moment( 'DD/MM/YY HH:mm:ss' );
    // $.fn.dataTable.moment( 'DD/MM/YYYY HH:mm:ss' );
    ///////////////////////////////////////
    var cols_number = [1, 2, 3, 4];
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
        order: [[ 0, 'desc' ]],

        columnDefs: [
                { type: 'formatted-num', targets: cols_number },
                { type: 'numeric-comma', targets: cols_number },
            ],
        // Totaux
        drawCallback: function () {
            var api = this.api();
            var c2  = api.column( 2, {page:'current'} ).data().sum();
            var c3  = api.column( 3, {page:'current'} ).data().sum();
            var c2  = api.column( 4, {page:'current'} ).data().sum();
            c2    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(c2);
            c3    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(c3);
            c4    = new Intl.NumberFormat("fr-FR", {style: "currency", currency: "EUR"}).format(c4);
            $("#c2").html(c2);
            $("#c3").html(c3);
            $("#c4").html(c4);
            var t2 = api.column( 2, {filter: 'applied'} ).data().sum();
            var t3 = api.column( 3, {filter: 'applied'} ).data().sum();
            var t4 = api.column( 4, {filter: 'applied'} ).data().sum();
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
            var elt = api.column(0).data().unique();
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