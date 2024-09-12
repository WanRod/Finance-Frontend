<?php
include_once 'Components/Modals/DeleteModal.html';
include_once 'Components/Modals/UpdateOutputTypeModal.html';
?>

<table>
    <thead>
        <tr>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $outputTypes = OutputTypeRepository::getAll();

        if ($outputTypes != null) 
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
