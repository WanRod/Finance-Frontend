<?php
include_once 'Components/Modals/DeleteModal.html';
include_once 'Components/Modals/UpdateInputModal.php';

$currentQuantity = 20;

if (isset($_POST['quantity']))
{
    $currentQuantity = $_POST['quantity'];
}
?>

<table class="mb-2">
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Descrição</th>
            <th>Valor (R$)</th>
            <th>Data</th>
            <th>Ações</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $inputs = InputRepository::getAll($currentQuantity);

        if (isset($inputs['error']['message']))
        {
            $_SESSION['message'] = $inputs['error']['message'];
        }
        else if ($inputs === null)
        {
            $_SESSION['message'] = "Não foi possível conectar à API, tente novamente mais tarde.";
        }
        else
        {
            $inputs = $inputs['body'];
        }

        if ($inputs === null)
        {
            echo "
                <tr>
                    <td colspan=\"5\" class=\"text-center text-danger\">
                        Não foi possível conectar à API, tente novamente mais tarde.
                    </td>
                </tr>
            ";
        }
        else if (isset($inputs['error'])) 
        {
            echo "
                <tr>
                    <td colspan=\"5\" class=\"text-center text-danger\">
                        Erro ao carregar os dados: {$inputs['error']['message']}
                    </td>
                </tr>
            ";
        } 
        else if ($inputs != null && count($inputs) > 0)  
        {
            foreach ($inputs as $input) 
            {
                $value = str_replace('.', ',', $input['value']);
                $value = number_format((float)str_replace(',', '.', $value), 2, ',', '');

                $date = DateTime::createFromFormat('Y-m-d', $input['date'])->format('d/m/Y');

                echo "
                    <tr>
                        <td data-input-type-id=\"{$input['input_type']['id']}\">{$input['input_type']['description']}</td>
                        <td>{$input['description']}</td>
                        <td class=\"text-end\" data-raw-value=\"{$input['value']}\">R$ {$value}</td>
                        <td data-raw-date=\"{$input['date']}\">{$date}</td>
                        <td class=\"action-col\">
                            <button class=\"edit-button\" data-id=\"{$input['id']}\" data-bs-toggle=\"modal\" data-bs-target=\"#edit-modal\"><i class=\"fa-solid fa-pencil icon\"></i></button>
                            <button class=\"delete-button\" data-id=\"{$input['id']}\" data-bs-toggle=\"modal\" data-bs-target=\"#delete-modal\"><i class=\"fas fa-trash-can icon\"></i></button>
                        </td>
                    </tr>
                    ";
            }
        } 
        else 
        {
            echo "
                <tr>
                    <td colspan=\"5\" class=\"text-center\">Nenhum registro encontrado.</td>
                </tr>
                ";
        }
        ?>
    </tbody>
</table>

<form action="" method="POST" class="d-flex align-items-center justify-content-end">
    <div class="mx-2">
        <b>Quantidade: </b>
    </div>

    <div>
        <select name="quantity" class="form-select" style="max-width: 80px;" onchange="submit()">
            <option value="1" <?= $currentQuantity == 1 ? 'selected' : ''; ?>>1</option>
            <option value="5" <?= $currentQuantity == 5 ? 'selected' : ''; ?>>5</option>
            <option value="10" <?= $currentQuantity == 10 ? 'selected' : ''; ?>>10</option>
            <option value="20" <?= $currentQuantity == 20 ? 'selected' : ''; ?>>20</option>
            <option value="50" <?= $currentQuantity == 50 ? 'selected' : ''; ?>>50</option>
            <option value="100" <?= $currentQuantity == 100 ? 'selected' : ''; ?>>100</option>
            <option value="200" <?= $currentQuantity == 200 ? 'selected' : ''; ?>>200</option>
        </select>
    </div>
</form>

<script>
$(document).ready(function() 
{
    function maskValue(input) 
    {
        var value = input.value;
        
        value = value.replace(/[^\d]/g, '');
                
        value = value.replace(/(\d{1,})(\d{2})$/, '$1,$2');
        value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        
        input.value = value;
    }

    $('#edit-value').on('input', function() 
    {
        maskValue(this);
    });

    $('.delete-button').on('click', function() 
    {
        var id = $(this).data('id');

        $('#confirm-delete').attr('data-id', id);
    });

    $('#confirm-delete').on('click', function() 
    {
        var id = $(this).data('id');

        $.ajax({
            url: 'Methods/Input/DeleteInput.php',
            type: 'POST',
            data: { id: id },
            success: function(response)
            {
                location.replace(location.href);
            },
            error: function(xhr, status, error) 
            {

            }
        });
    });

    $('.edit-button').on('click', function() 
    {
        var row = $(this).closest('tr');
        var id = $(this).data('id');
        var inputTypeId = row.find('td:eq(0)').data('input-type-id');
        var description = row.find('td:eq(1)').text();
        var value = row.find('td:eq(2)').data('raw-value');
        var date = row.find('td:eq(3)').data('raw-date');3

        $('#edit-id').val(id);
        $('#edit-input-type-id').val(inputTypeId);
        $('#edit-description').val(description);
        $('#edit-value').val(value);
        $('#edit-date').val(date);
    });

    $('#confirm-edit').on('click', function()
    {
        var id = $('#edit-id').val();
        var inputTypeId = $('#edit-input-type-id').val();
        var description = $('#edit-description').val();
        var date = $('#edit-date').val();
        var value = $('#edit-value').val();
        value = value.replace(/\./g, '');
        value = value.replace(',', '.');

        $.ajax({
            url: 'Methods/Input/UpdateInput.php',
            type: 'POST',
            data: 
            { 
                id: id,
                inputTypeId: inputTypeId,
                description: description,
                value: value,
                date: date
            },
            success: function(response)
            {
                location.replace(location.href);
            },
            error: function(xhr, status, error) 
            {
                console.error("Erro ao atualizar o registro:", error);
            }
        });
    });

    $('#cancel-edit').on('click', function() 
    {
        $('#edit-input-type-id').val('');
        $('#edit-description').val('');
        $('#edit-value').val('');
        $('#edit-date').val('');
    });
});
</script>
