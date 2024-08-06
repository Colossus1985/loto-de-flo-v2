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
                    <h3 class="box-title ml-3">Groups gagné (€)</h3>

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

    <div id="result"></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    //====================== groups =============
    <?php 
        $labels_groups          = $chiffres_groups['labels'];
        $datasets_groups        = $chiffres_groups['data']; 
        $datasets_totaux_groups = $chiffres_groups['totaux']; 
    ?>
    
    const stats_groups = document.getElementById('chiffres_groups');

    // Extract PHP data
    const labels_groups = <?php echo json_encode($labels_groups); ?>;
    const datas_groups = <?php echo json_encode($datasets_groups); ?>;
    
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
        type: 'line',
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
    const totalData_groups = <?php echo json_encode($datasets_totaux_groups); ?>;
    var index = 0;

    // Create an HTML string to display the total data for each year
    let htmlString_groups = '<ul style="list-style: none;">';
    for (const year in totalData_groups) {
        if (totalData_groups.hasOwnProperty(year)) {
            const value = totalData_groups[year];
            // Add a check for datasets_groups[index] existence
            if (datasets_groups[index]) {
                const backgroundColor = datasets_groups[index].backgroundColor || 'gray';
                htmlString_groups += `<li class='mb-2'><span class='my-2 col-12 px-2 py-1 rounded-2 text-center text-bold' style='background-color: ${backgroundColor};'>${year}:</span> ${value} €</li>`;
            }
            index++;
        }
    }
    htmlString_groups += '</ul>';
    totalDataElement_groups.innerHTML = htmlString_groups;

    //======================  Fin CA ============
</script>





@endsection