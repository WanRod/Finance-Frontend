<?php
$currentMonth = date('m');
$currentYear = date('Y');

$data = DashboardRepository::getData($currentYear, $currentMonth);

$totalInput = str_replace('.', ',', $data['total_input']);
$totalOutput = str_replace('.', ',', $data['total_output']);
$percentSpent = str_replace('.', ',', $data['percent_spent']);
$remainingAmount = str_replace('.', ',', $data['remaining_amount']);

$months = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];
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
            labels: <?php echo json_encode($months); ?>,
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
                    data: <?php echo json_encode($outputTypeTotals); ?>, // Transforma em valores absolutos
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
                    text: 'Principais Tipos de Saídas do mês <?php echo $currentMonth; ?>',
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
                            return 'Total gasto: R$ ' + totals[index] + ' | Quantidade de saídas: ' + amounts[index];
                        }
                    }
                }
            }
        }
    });
});
</script>