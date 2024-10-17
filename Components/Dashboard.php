<?php
$currentMonth = date('m');
$currentYear = date('Y');

if (isset($_POST['month']) && isset($_POST['year']))
{
    $currentMonth = $_POST['month'];
    $currentYear = $_POST['year'];
}

$years = DashboardRepository::getAvailableYears();
$data = DashboardRepository::getData($currentYear, $currentMonth);

if (!$years)
{
    $years = [$currentYear];
}

$totalInput = number_format((float)$data['total_input'], 2, ',', '.');
$totalOutput = number_format((float)$data['total_output'], 2, ',', '.');
$percentSpent = number_format((float)$data['percent_spent'], 2, ',', '.');
$remainingAmount = number_format((float)$data['remaining_amount'], 2, ',', '.');

$months = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
$inputs = [];
$outputs = [];

foreach ($data['monthly'] as $monthData)
{
    $inputs[] = $monthData['input'];
    $outputs[] = $monthData['output'];
}

$outputTypeDescriptions = [];
$outputTypeAmounts = [];
$outputTypeTotals = [];

foreach ($data['output_types'] as $outputType)
{
    $outputTypeDescriptions[] = $outputType['description'];
    $outputTypeAmounts[] = $outputType['amount'];
    $outputTypeTotals[] = $outputType['total'];
}
?>

<form action="" method="POST" class="d-flex align-items-center justify-content-start" style="max-width: 410px;">
    <div class="mx-2">
        <b>Ano: </b>
    </div>

    <div>
        <select name="year" class="form-select" style="max-width: 100px;" onchange="this.form.submit()">
            <?php
            foreach($years as $year) {
                $selected = ($year == $currentYear) ? 'selected' : '';
                echo "<option value=\"{$year}\" {$selected}>{$year}</option>";
            }
            ?>
        </select>
    </div>

    <div class="mx-2">
        <b>Mês: </b>
    </div>

    <div>
        <select name="month" class="form-select" style="max-width: 150px;" onchange="this.form.submit()">
            <?php
            for ($i = 1; $i < 13; $i++) {
                $selected = ($i == $currentMonth) ? 'selected' : '';
                echo "<option value=\"{$i}\" {$selected}>{$months[$i-1]}</option>";
            }
            ?>
        </select>
    </div>
</form>


<!-- Dashboard data -->
<div class="data-container">
    <div class="data-div">
        <h5>Total recebido no mês</h5>
        <p class="data"><span class="symbol-blue">R$</span><span class="value"><?php echo $totalInput ?></span></p>
    </div>

    <div class="data-div">
        <h5>Total gasto no mês</h5>
        <p class="data"><span class="symbol-red">R$</span><span class="value"><?php echo $totalOutput ?></span></p>
    </div>

    <div class="data-div">
        <h5>Gasto do mês</h5>
        <p class="data"><span class="value"><?php echo $percentSpent ?></span><span class="symbol-red">%</span></p>
    </div>

    <div class="data-div align-content-center">
        <h5>Saldo do mês</h5>
        <p class="data"><span class="symbol-blue">R$</span><span class="value"><?php echo $remainingAmount ?></span></p>
    </div>
</div>

<!-- Gráficos -->
<div class="graphs-container">
    <div class="graph-container">
        <canvas class="graph-div" id="lineChart"></canvas>
    </div>

    <div class="graph-container">
        <canvas class="graph-div" id="barChart"></canvas>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var lineChartContext = document.getElementById('lineChart').getContext('2d');
    var barChartContext = document.getElementById('barChart').getContext('2d');

    var lineChart = new Chart(lineChartContext, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_map(function($month) {
            return substr($month, 0, 3); // Pega os três primeiros caracteres do mês
            }, $months)); ?>,
            datasets: [
                {
                    label: 'Entradas',
                    data: <?php echo json_encode($inputs); ?>,
                    borderColor: 'LimeGreen',
                    backgroundColor: 'rgb(50, 205, 50)',
                    pointStyle: 'circle',
                    tension: 0,
                    pointBackgroundColor: 'LimeGreen'
                },
                {
                    label: 'Saídas',
                    data: <?php echo json_encode($outputs); ?>,
                    borderColor: 'Red',
                    backgroundColor: 'rgb(255, 0, 0)',
                    pointStyle: 'circle',
                    tension: 0,
                    pointBackgroundColor: 'Red'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Entradas / Saídas de <?php echo $currentYear; ?>',
                    font: {
                        size: 20,
                    },
                    color: '#000000',
                }
            }
        }
    });

    var barChart = new Chart(barChartContext, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($outputTypeDescriptions); ?>,
            datasets: [
                {
                    backgroundColor: [
                        '#000080', 
                        '#000C66',
                        '#0000FF',
                        '#7EC8E3',
                        '#145DA0',
                        '#0C2D48',
                        '#2E8BC0',
                        '#B1D4E0'
                    ],
                    categoryPercentage: 0.3,
                    data: <?php echo json_encode($outputTypeTotals); ?>,
                    label: 'Saídas',
                    outputAmounts: <?php echo json_encode($outputTypeAmounts); ?>
                }   
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    reverse: true
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Principais Tipos de Saídas de <?php echo $months[$currentMonth-1]; ?>',
                    font: {
                        size: 20,
                    },
                    color: '#000000',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var totals = context.dataset.data;
                            var amounts = context.dataset.outputAmounts;
                            var index = context.dataIndex;
                            return 'Total gasto: R$ ' + totals[index].toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' | Quantidade de saídas: ' + amounts[index];
                        }
                    }
                }
            }
        }
    });
});
</script>