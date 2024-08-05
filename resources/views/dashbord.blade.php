@extends('layouts.main')
@section('content')

<?php 
$month = config('commun.mois_ordo');
$begin_year = 2017;
$today_year = date('Y');
$today_month = date('m');
?>
<div class="content body">

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="box-header with-border">
                    <h3 class="box-title ml-3">Groups Joué / Gagné (€)</h3>

                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:15rem;">
                            <canvas id="canvas_groups"></canvas>
                        </div>
                        <div id="chartLegend_groups"></div>  
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    window.onload = function() {
        const labels_groups         = {!! json_encode($chiffres_groups['labels'], JSON_UNESCAPED_UNICODE) !!};
        const datas_groups          = {!! json_encode($chiffres_groups['datasets']) !!};
        const dataset_totaux_groups = {!! json_encode($chiffres_groups['totaux']) !!};

        const stats_groups      = document.getElementById('canvas_groups');
        const years_groups      = Object.keys(datas_groups);
        const datasets_groups   = [];
        var label_mois_groups   = [];

        years_groups.forEach(year => {
            const yearData  = datas_groups[year];
            const data      = [];

            for (const month in yearData) {
                if (yearData.hasOwnProperty(month)) {
                    if (!label_mois_groups.includes(month)) {
                        label_mois_groups.push(month);
                    }
                    const value = yearData[month];
                    data.push(value);
                }
            }

            datasets_groups.push({
                label: year,
                data: data,
                borderWidth: 2,
                // backgroundColor and borderColor could be defined here for each dataset
            });
        });

        if (stats_groups) {
            new Chart(stats_groups, {
                type: 'bar',
                data: {
                    labels: label_mois_groups,
                    datasets: datasets_groups
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                }
            });
        } else {
            console.error("L'élément canvas avec l'ID 'groups' n'a pas été trouvé.");
        }


        const totalDataElement_groups = document.getElementById('chartLegend_groups');
        const totalData_groups = dataset_totaux_groups;
        var index = 0;

        let htmlString_groups = '<ul style="list-style: none;">';
        for (const year in totalData_groups) {
            if (totalData_groups.hasOwnProperty(year)) {
                const value = totalData_groups[year];
                htmlString_groups += `<li class='mb-2'><span class='my-2 col-12 px-2 py-1 rounded-2 text-center text-bold' style='background-color: ` + (datasets_groups[index].backgroundColor || 'defaultColor') + `;'>${year}:</span> ${value} €</li>`;
                index++;
            }
        }
        htmlString_groups += '</ul>';
        totalDataElement_groups.innerHTML = htmlString_groups;
    }
</script>




@endsection