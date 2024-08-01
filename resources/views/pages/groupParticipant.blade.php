@extends('layouts.main')
@section('content')

<style>
    .box_group {
        margin: 1rem;
        box-shadow: 0px 0px 10px rgb(114 119 255 / 50%);
        width: 20rem;
        padding: 0.5rem;
    }
</style>

<div class="p-5">
    <div class="d-flex">
        <div class="my-3 me-3">
            <div class="btn-group">
                <button type="button" @if ( count($groups_details) > 0 ) data-toggle="collapse" data-target="#groups_details_actifCollapse" aria-expanded="false" aria-controls="attenteCollapse" class="btn btn-info" @else class="btn btn-primary" disabled @endif>
                    {{ count($groups_details[0]) }} Groupes actif(s) @if ( count($groups_details) > 0 ) <i class="fa-solid fa-magnifying-glass-plus"></i> @endif
                </button>
            </div>
        </div>

        <div class="my-3 me-3">
            <div class="btn-group">
                <button type="button" data-toggle="collapse" data-target="#groups_ceationCollapse" aria-expanded="false" aria-controls="attenteCollapse" class="btn btn-info">
                    Céation d'un groupe <i class="fa-solid fa-pen"></i>
                </button>
            </div>
        </div>

        <div class="my-3 me-3">
            <div class="btn-group">
                <button type="button" @if ( count($groups_details) > 0 ) data-toggle="collapse" data-target="#groups_composeCollapse" aria-expanded="false" aria-controls="attenteCollapse" class="btn btn-info" @else class="btn btn-primary" disabled @endif>
                    @if ( count($groups_details) > 0 ) Composer un groupe <i class="fa-solid fa-pen"></i> 
                    @else Crée un groupe avant avant de composer 
                    @endif
                </button>
            </div>
        </div>

        <div class="my-3">
            <div class="btn-group">
                <button type="button" @if ( count($groups_details) > 0 ) data-toggle="collapse" data-target="#groups_details_inactifCollapse" aria-expanded="false" aria-controls="attenteCollapse" class="btn btn-secondary" @else class="btn btn-primary" disabled @endif>
                    {{ count($groups_details[1]) }} Groupes inactif(s) @if ( count($groups_details) > 0 ) <i class="fa-solid fa-magnifying-glass-plus"></i> @endif
                </button>
            </div>
        </div>

    </div>
    
    <div class="pb-3 box collapse" id="groups_ceationCollapse">
        <h3 class="modal-title mb-3" id="addGain">
            Création d'un groupe
        </h3>
        <div class="mt-3">
            <div class="">
                <form method="POST" action="{{ route('addGroup') }}">
                    @csrf
                    <div class="form-group form-floating mb-3 d-flex flex-fill">
                        <input
                        type="text"
                        class="form-control flex-fill"
                        name="inputNameGroup"
                        id="floatingNameGroup"
                        value="{{ old('inputAmount') }}"
                        maxlength="20"
                        placeholder="Nom du Groupe"
                        required
                        />
                        <label for="floatingNameGroup">Nom du Groupe aA-zZ 0-9</label>
                    </div>

                    <div class="d-flex btn-G-L justify-content-end">
                        <button
                            class="btn btn-primary"
                            type="submit"
                            style="width: 45%"
                            onclick="return confirm('Créer ce group?');"
                        >
                            Créer ce group
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="pb-3 box collapse" id="groups_details_actifCollapse">
        <h3 class="mb-3">
            Les groupes avtif(s)
        </h3>
        <div class="d-flex flex-direction-row flex-wrap">
            @foreach ($groups_details[0] as $groupe)
                <div class="box_group d-flex flex-column justify-content-between rounded">
                    <div>
                        <h4>{{$groupe->nameGroup}} ({{( count($groupe->participants_group)) }})</h4>
                        <ol>
                            @foreach ( $groupe->participants_group as $participant)
                                <li>
                                    <a class="btn btn-sm bg-info mb-1" 
                                        href="{{ route('participant', [$participant->id, $participant->actif]) }}"
                                        style="width: 100%" 
                                        target="blanc"> 
                                        {{ $participant->pseudo }}
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                    <div class="text-end">
                        @if ($groupe->total_gain_group != 0)
                            <span class="fw-bold fs-3">Gains : {{ ifNotZero($groupe->total_gain_group, true, ' €', '.', ' ') }}</span>
                        @else
                            <span class="fw-bold fs-3">Gains : 0.00 €</span>
                        @endif
                        <div class="fs-3">
                            <a href="{{ route('groupDelete', $groupe->id) }}"
                                @if ( count($groupe->participants_group) > 0) 
                                    onclick="return confirm('Attention il y a encore des joueurs dans le groupe {{ $groupe->nameGroup }}!! Continuer la suppression ?');"
                                @else
                                    onclick="return confirm('Veux tu vraiment supprimer le groupe {{ $groupe->nameGroup }} ?');"
                                @endif
                                >
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                        
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="pb-3 box collapse" id="groups_details_inactifCollapse">
        <h3 class="mb-3">
            Les groupes inactifs
        </h3>
        <div class="d-flex flex-direction-row flex-wrap">
            @foreach ($groups_details[1] as $groupe)
                <div class="box_group d-flex flex-column justify-content-between rounded bg-secondary">
                    <div class="text-end">
                        <div>
                            <h4>{{$groupe->nameGroup}}</h4>
                        </div>
                        @if ($groupe->total_gain_group != 0)
                            <span class="fw-bold fs-3">Gains : {{ ifNotZero($groupe->total_gain_group, true, ' €', '.', ' ') }}</span>
                        @else
                            <span class="fw-bold fs-3">Gains : 0.00 €</span>
                        @endif
                        <div class="fs-3">
                            <a href="{{ route('groupRallume', $groupe->id) }}"
                                onclick="return confirm('Veux tu vraiment reactiver le groupe {{ $groupe->nameGroup }} ?');"
                                >
                                <i class="fa-solid fa-circle-up"></i>
                            </a>
                        </div>
                        
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <div class="pb-3 box collapse" id="groups_composeCollapse">
        <h3 class="modal-title mb-3" id="addGain">
            Composition de Groupe
        </h3>
        <div class="mt-3">
            <form method="POST" action="{{ route('participantGroup') }}">
                @csrf
                <div class="form-group form-floating mb-3 d-flex flex-row">
                    <div class="border border-3 rounded-3 form-group form-floating me-3 d-flex flex-fill flex-column">
                        <div class="">
                            <p class="mt-1 mb-2 ps-3">Choisis le Group : </p>
                        </div>
                            @foreach ($groups as $group)
                            <div class="ms-3 form-check form-switch">
                                <input class="form-check-input me-3"
                                    type="radio" 
                                    name="inputNameGroup" 
                                    role="switch" 
                                    id="flexSwitchNameGroup" 
                                    value="{{ $group->nameGroup }}">
                                <label class="form-check-label" for="flexSwitchNameGroup">{{ $group->nameGroup }}</label>
                            </div>
                        @endforeach
                    </div>

                    <div class="border border-3 rounded-3 form-group form-floating d-flex flex-fill flex-column">
                        <div class="">
                            <p class="mt-1 mb-2 ps-3">Choisir le(s) Participant(s) : </p>
                        </div>
                        @foreach ($participants as $participant)
                            <div class="ms-3 form-check form-switch">
                                <input class="form-check-input me-3"
                                    type="checkbox" 
                                    name="inputParticipantArray[]" 
                                    role="switch" 
                                    id="flexSwitchCheckDefault" 
                                    value="{{ $participant->pseudo }}">
                                <label class="form-check-label" for="flexSwitchCheckDefault">{{ $participant->pseudo}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex btn-G-L justify-content-end">
                    <button
                        class="btn btn-primary"
                        type="submit"
                        style="width: 45%"
                        onclick="return confirm('Sur de la composition du group?');"
                    >
                        Composer le group
                    </button>
                </div>
            </form>
        </div>
    </div>


</div>

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