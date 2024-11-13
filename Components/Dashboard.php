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

if ($years === null)
{
    $years = [$currentYear];
}
else if (isset($years['error']['message']))
{
    $_SESSION['message'] = $years['error']['message'];
    $years = [$currentYear];
}
else
{
    $years = $years['body'];
}

if (isset($data['error']['message']))
{
    $_SESSION['message'] = $data['error']['message'];
}
else if ($data !== null)
{
    $data = $data['body'];
}

$totalInput = number_format(isset($data['total_input']) ? (float)$data['total_input'] : 0, 2, ',', '.');
$totalOutput = number_format(isset($data['total_output']) ? (float)$data['total_output'] : 0, 2, ',', '.');
$percentSpent = number_format(isset($data['percent_spent']) ? (float)$data['percent_spent'] : 0, 2, ',', '.');
$remainingAmount = number_format(isset($data['remaining_amount']) ? (float)$data['remaining_amount'] : 0, 2, ',', '.');

$months = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
$inputs = [];
$outputs = [];

if (isset($data['monthly']))
{
    foreach ($data['monthly'] as $monthData)
    {
        $inputs[] = $monthData['input'];
        $outputs[] = $monthData['output'];
    }
}

$inputTypeDescriptions = [];
$inputTypeAmounts = [];
$inputTypeTotals = [];

if (isset($data['input_types']))
{
    foreach ($data['input_types'] as $inputType)
    {
        $inputTypeDescriptions[] = $inputType['description'];
        $inputTypeAmounts[] = $inputType['amount'];
        $inputTypeTotals[] = $inputType['total'];
    }
}

$outputTypeDescriptions = [];
$outputTypeAmounts = [];
$outputTypeTotals = [];

if (isset($data['output_types']))
{
    foreach ($data['output_types'] as $outputType)
    {
        $outputTypeDescriptions[] = $outputType['description'];
        $outputTypeAmounts[] = $outputType['amount'];
        $outputTypeTotals[] = $outputType['total'];
    }
}
?>

<form action="" method="POST" class="d-flex align-items-center justify-content-start" >
    <div class="mx-2">
        <b>Ano: </b>
    </div>

    <div>
        <select name="year" class="form-select-sm" onchange="this.form.submit()">
            <?php
            foreach($years as $year)
            {
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
        <select name="month" class="form-select-sm" onchange="this.form.submit()">
            <?php
            for ($i = 1; $i < 13; $i++)
            {
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
        <canvas class="graph-div" id="inputTypesChart"></canvas>
    </div>

    <div class="graph-container">
        <canvas class="graph-div" id="outputTypesChart"></canvas>
    </div>

    <div class="graph-container">
        <canvas class="graph-div" id="lineChart"></canvas>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var inputTypesChart = document.getElementById('inputTypesChart').getContext('2d');
    var outputTypesChart = document.getElementById('outputTypesChart').getContext('2d');
    var lineChartContext = document.getElementById('lineChart').getContext('2d');

    var inputTypes = new Chart(inputTypesChart, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($inputTypeDescriptions); ?>,
            datasets: [
                {
                    backgroundColor: [
                        '#00FF00', 
                        '#32CD32',
                        '#7CFC00',
                        '#228B22',
                        '#7FFF00',
                        '#008000',
                        '#ADFF2F',
                        '#008000',
                        '#9ACD32',
                        '#006400'
                    ],
                    categoryPercentage: <?php echo min(0.5, max(0, [0, 0.1, 0.2, 0.2, 0.2, 0.3, 0.3, 0.4, 0.4, 0.5, 0.5][count($inputTypeTotals)] ?? 0)); ?>,
                    data: <?php echo json_encode($inputTypeTotals); ?>,
                    label: 'Entradas',
                    inputAmounts: <?php echo json_encode($inputTypeAmounts); ?>
                }   
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    reverse: false           
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Principais Tipos de Entradas de <?php echo $months[$currentMonth-1]; ?>',
                    font: {
                        size: 20,
                    },
                    color: '#000000',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var totals = context.dataset.data;
                            var amounts = context.dataset.inputAmounts;
                            var index = context.dataIndex;
                            return 'Total recebido: R$ ' + totals[index].toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' | Quantidade de Entradas: ' + amounts[index];
                        }
                    }
                }
            }
        }
    });

    var outputTypes = new Chart(outputTypesChart, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($outputTypeDescriptions); ?>,
            datasets: [
                {
                    backgroundColor: [
                        '#FF0000', 
                        '#B22222',
                        '#800000',
                        '#8B0000',
                        '#A52A2A',
                        '#FF6347',
                        '#FF7F50',
                        '#E9967A',
                        '#FFA07A',
                        '#FA8072'
                    ],
                    categoryPercentage: <?php echo min(0.5, max(0, [0, 0.1, 0.2, 0.2, 0.2, 0.3, 0.3, 0.4, 0.4, 0.5, 0.5][count($outputTypeTotals)] ?? 0)); ?>,
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
                            return 'Total gasto: R$ ' + totals[index].toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' | Quantidade de Saídas: ' + amounts[index];
                        }
                    }
                }
            }
        }
    });

    var lineChart = new Chart(lineChartContext, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_map(function($month) {
            return substr($month, 0, 3);
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
});
</script>