<?php
include_once 'Components/Modals/DeleteModal.html';
include_once 'Components/Modals/UpdateOutputTypeModal.html';

$currentQuantity = 20;

if (isset($_POST['quantity']))
{
    $currentQuantity = $_POST['quantity'];
}
?>

<table class="mb-2">
    <thead>
        <tr>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $outputTypes = OutputTypeRepository::getAll($currentQuantity);

        if (isset($outputTypes['error'])) 
        {
            echo "
                <tr>
                    <td colspan=\"2\" class=\"text-center text-danger\">
                        Erro ao carregar os dados: {$outputTypes['error']}
                    </td>
                </tr>
            ";
        } 
        elseif ($outputTypes != null && count($outputTypes) > 0) 
        {
            foreach ($outputTypes as $outputType) 
            {
                echo "
                    <tr>
                        <td class=\"text-start\">{$outputType['description']}</td>
                        <td class=\"action-col\">
                            <button class=\"edit-button\" data-id=\"{$outputType['id']}\" data-bs-toggle=\"modal\" data-bs-target=\"#edit-modal\"><i class=\"fa-solid fa-pencil icon\"></i></button>
                            <button class=\"delete-button\" data-id=\"{$outputType['id']}\" data-bs-toggle=\"modal\" data-bs-target=\"#delete-modal\"><i class=\"fas fa-trash-can icon\"></i></button>
                        </td>
                    </tr>
                ";
            }
        } 
        else 
        {
            echo "
                <tr>
                    <td colspan=\"2\" class=\"text-center\">Nenhum registro encontrado.</td>
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
    $('.delete-button').on('click', function() 
    {
        var id = $(this).data('id');

        $('#confirm-delete').attr('data-id', id);
    });

    $('#confirm-delete').on('click', function() 
    {
        var id = $(this).data('id');

        $.ajax({
            url: 'Methods/OutputType/DeleteOutputType.php',
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

    $('#cancel-delete').on('click', function()
    {
        $('#confirm-delete').removeAttr('data-id');
    });

    $('.edit-button').on('click', function() 
    {
        var id = $(this).data('id');
        var description = $(this).closest('tr').find('td:eq(0)').text();

        $('#edit-id').val(id);
        $('#edit-description').val(description);
        $('#confirm-edit').attr('data-id', id);
    });

    $('#confirm-edit').on('click', function()
    {
        var id = $('#edit-id').val();
        var description = $('#edit-description').val();

        $.ajax({
            url: 'Methods/OutputType/UpdateOutputType.php',
            type: 'POST',
            data: 
            { 
                id: id,
                description: description
            },
            success: function(response)
            {
                location.replace(location.href);
            },
            error: function(xhr, status, error) 
            {

            }
        });
    });

    $('#cancel-edit').on('click', function() 
    {
        $('#edit-description').val('');
    });
});
</script>
