<?php
include_once 'Components/Modals/DeleteModal.html';
include_once 'Components/Modals/UpdateOutputModal.php';
?>

<table>
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
        $outputs = OutputRepository::getAll();

        if ($outputs != null) 
        {
            foreach ($outputs as $output) 
            {
                $value = str_replace('.', ',', $output['value']);
                $value = number_format((float)str_replace(',', '.', $value), 2, ',', '');

                $date = DateTime::createFromFormat('Y-m-d', $output['date'])->format('d/m/Y');

                echo "
                    <tr class=\"tablee\">
                        <td data-output-type-id=\"{$output['output_type']['id']}\">{$output['output_type']['description']}</td>
                        <td>{$output['description']}</td>
                        <td class=\"text-end\" data-raw-value=\"{$output['value']}\">R$ {$value}</td>
                        <td data-raw-date=\"{$output['date']}\">{$date}</td>
                        <td class=\"action-col\">
                            <button class=\"edit-button\" data-id=\"{$output['id']}\" data-bs-toggle=\"modal\" data-bs-target=\"#edit-modal\"><i class=\"fa-solid fa-pencil icon\"></i></button>
                            <button class=\"delete-button\" data-id=\"{$output['id']}\" data-bs-toggle=\"modal\" data-bs-target=\"#delete-modal\"><i class=\"fas fa-trash-can icon\"></i></button>
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
            url: 'Methods/Output/DeleteOutput.php',
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

    // Botão de editar
    $('.edit-button').on('click', function() 
    {
        var row = $(this).closest('tr');
        var id = $(this).data('id');
        var outputTypeId = row.find('td:eq(0)').data('output-type-id');
        var description = row.find('td:eq(1)').text();
        var value = row.find('td:eq(2)').data('raw-value');
        var date = row.find('td:eq(3)').data('raw-date');

        $('#edit-id').val(id);
        $('#edit-output-type-id').val(outputTypeId);
        $('#edit-description').val(description);
        $('#edit-value').val(value);
        $('#edit-date').val(date);
    });

    $('#confirm-edit').on('click', function()
    {
        var id = $('#edit-id').val();
        var outputTypeId = $('#edit-output-type-id').val();
        var description = $('#edit-description').val();
        var value = $('#edit-value').val();
        var date = $('#edit-date').val();

        $.ajax({
            url: 'Methods/Output/UpdateOutput.php',
            type: 'POST',
            data: 
            { 
                id: id,
                outputTypeId: outputTypeId,
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
        $('#edit-output-type-id').val('');
        $('#edit-description').val('');
        $('#edit-value').val('');
        $('#edit-date').val('');
    });
});
</script>
