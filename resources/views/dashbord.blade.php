@extends('layouts.main')
@section('content')

<?php 
$month = config('commun.mois_ordo');
$begin_year = 2017;
$today_year = date('Y');
$today_month = date('m');
?>
<div class="content body">

    <div>
        <h3 class="p-3">Statistiques de LOTO DE FLO</h3>
    </div>
    <div>
        <h4 class="p-3">Gains de LOTO DE FLO</h4>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="box-header with-border">
                    <h3 class="box-title ml-3">Gains en €</h3>

                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:15rem;">
                            <canvas id="chiffres_groups"></canvas>
                        </div>
                        <div id="chartLegend_groups"></div>  
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div>
        <h4 class="p-3">Gains par groupe</h4>
    </div>
    <div id="div_btn_group_detail" class="d-flex flex-row flex-wrap mb-3 mt-2"></div>
    <div id="div_canvas_group_detail"></div>

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    //====================== group détail =============
    const groups = {!! json_encode($groups) !!};
    const canvasContainer = document.getElementById('div_canvas_group_detail');

    // Fonction pour créer un graphique pour un groupe spécifique
    function createChart(data) {
        console.log(data);

        // Clear previous canvas if it exists
        canvasContainer.innerHTML = `
            <div class="card">
                <div class="box-header with-border">
                    <h3 id="title_group_name" class="box-title ml-3"></h3>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:15rem;">
                            <canvas id="groupChart"></canvas>
                        </div>
                        <div id="chartLegend_group"></div>
                    </div>
                </div>
            </div>
        `;

        // Mettre à jour le titre avec le nom du groupe
        document.getElementById('title_group_name').innerText = `${data.group_name} gains en €`;

        // Préparer les données pour le graphique
        const labels = data.labels;
        const datasets = [];

        for (const month in data.data) {
            if (data.data.hasOwnProperty(month)) {
                const yearData = data.data[month];

                for (const year in yearData) {
                    if (yearData.hasOwnProperty(year)) {
                        let dataset = datasets.find(d => d.label === year);
                        if (!dataset) {
                            dataset = {
                                label: year,
                                data: [],
                                backgroundColor: `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.5)`,
                                borderColor: `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 1)`,
                                borderWidth: 1
                            };
                            datasets.push(dataset);
                        }
                        dataset.data.push(yearData[year]);
                    }
                }
            }
        }

        // Compléter les données pour chaque mois manquant par année avec des zéros
        datasets.forEach(dataset => {
            if (dataset.data.length < labels.length) {
                for (let i = dataset.data.length; i < labels.length; i++) {
                    dataset.data.push(0);
                }
            }
        });

        // Créer le graphique
        const ctx = document.getElementById('groupChart').getContext('2d');
        window.myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Afficher les totaux
        const totalDataElement_groups = document.getElementById('chartLegend_group');
        const totalData_groups = data.totaux;
        let index = 0;
        let htmlString_groups = '<div class="d-flex flex-row flex-wrap">';
        
        for (const year in totalData_groups) {
            if (totalData_groups.hasOwnProperty(year)) {
                const value = totalData_groups[year];
                if (datasets[index]) {
                    const backgroundColor = datasets[index].backgroundColor || 'gray';
                    htmlString_groups += `<span class='my-2 me-3 px-2 py-1 rounded-2 text-center text-bold text-nowrap' style='background-color: ${backgroundColor};'>${year}: &nbsp;${value.toFixed(2)} €</span>`;
                }
                index++;
            }
        }
        htmlString_groups += '</div>';
        totalDataElement_groups.innerHTML = htmlString_groups;

    }

    // Ajout des boutons et configuration de l'événement de clic pour charger les données de chaque groupe
    const btnContainer = document.getElementById('div_btn_group_detail');

    groups.forEach(group => {
        const button = document.createElement('button');
        button.textContent = group.nameGroup;
        button.className = 'btn-sm btn-info me-3 mb-3';
        button.setAttribute('data-url', `/log/get-group-data/${group.nameGroup}`);
        button.onclick = function() {
            const url = this.getAttribute('data-url');
            fetch(url)
                .then(response => response.json())
                .then(data => createChart(data))
                .catch(error => console.error('Error fetching data:', error));
        };
        btnContainer.appendChild(button);
    });


//====================== fin group détail ==========

//====================== groups totaux =============
    const stats_groups = document.getElementById('chiffres_groups');

    const labels_groups = {!! json_encode($chiffres_groups['labels']) !!};
    const datas_groups = {!! json_encode($chiffres_groups['data']) !!};
    
    // Extract all years from the data
    const years_groups = new Set();
    labels_groups.forEach(month => {
        const yearData = datas_groups[month];
        Object.keys(yearData).forEach(year => years_groups.add(year));
    });
    
    // Convert Set to array and sort
    const yearsArray_groups = Array.from(years_groups).sort();

    // Initialize datasets for each year
    const datasets_groups = yearsArray_groups.map((year, index) => {
        const data = labels_groups.map(month => datas_groups[month][year] || 0);
        return {
            label: year,
            data: data,
            backgroundColor: `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.5)`,
            borderColor: `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 1)`,
            borderWidth: 1,
        };
    });

    new Chart(stats_groups, {
        type: 'bar',
        data: {
            labels: labels_groups,
            datasets: datasets_groups
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                }
            },
        }
    });

    const totalDataElement_groups = document.getElementById('chartLegend_groups');
    const totalData_groups = {!! json_encode($chiffres_groups['totaux']) !!};
    let index = 0;

    // Create an HTML string to display the total data for each year
    let htmlString_groups = '<div class="d-flex flex-row flex-wrap">';
    for (const year in totalData_groups) {
        if (totalData_groups.hasOwnProperty(year)) {
            const value = totalData_groups[year];
            // Add a check for datasets_groups[index] existence
            if (datasets_groups[index]) {
                const backgroundColor = datasets_groups[index].backgroundColor || 'gray';
                htmlString_groups += `<span class='my-2 me-3 px-2 py-1 rounded-2 text-center text-bold text-nowrap' style='background-color: ${backgroundColor};'>${year}: &nbsp;${value} €</span>`;
            }
            index++;
        }
    }
    htmlString_groups += '</div>';
    totalDataElement_groups.innerHTML = htmlString_groups;

//======================  Fin groups totaux ============
</script>






@endsection