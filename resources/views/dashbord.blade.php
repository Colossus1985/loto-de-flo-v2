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
                            <canvas id="chart_CA" ></canvas>
                        </div>
                        <div id="chartLegend_CA"></div>  
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="box-header with-border">
                    <h3 class="box-title ml-3">Joueur Joué / Gagné (€)</h3>

                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:15rem;">
                            <canvas id="chart_CA" ></canvas>
                        </div>
                        <div id="chartLegend_CA"></div>  
                    </div>

                </div>
            </div>
        </div>
    </div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

</script>



@endsection