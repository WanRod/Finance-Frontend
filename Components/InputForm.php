<div class="form-header">
    <p>Adicionar entrada</p>
</div>

<fieldset class="form-fieldset">
    <form action="Methods/Input/InsertInput.php" method="POST">
        <div class="row">
            <div class="col-12 col-md-8 mb-1 mb-md-0">
                <input type="text" class="form-control" name="description" id="description" maxlength="100" placeholder="Descrição" required>
            </div>

            <div class="col-6 col-md-2 mb-1 mb-md-0">
                <div class="input-group">
                    <span class="input-group-text currency-span">R$</span>
                    <input type="text" class="form-control" name="value" id="value" placeholder="00,00" required>
                </div>
            </div>

            <div class="col-6 col-md-2 mb-1 mb-md-0">
                <input type="date" class="form-control" name="date" id="date" value="<?= date('Y-m-d') ?>" required>
            </div>
        </div>
        <div class="buttons">
            <button class="btn me-2 save" type="submit"><i class="fa-solid fa-check"></i> Salvar</button>
            <button class="btn clear" id="clear"><i class="fa-solid fa-trash-can"></i> Limpar</button>
        </div>
    </form>

    <script>
        function maskValue(input) 
        {
            var value = input.value;
            
            value = value.replace(/[^\d]/g, '');
                    
            value = value.replace(/(\d{1,})(\d{2})$/, '$1,$2');
            value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            
            input.value = value;
        }

        $(document).ready(function () 
        {
            $('#value').on('input', function () 
            {
                maskValue(this);
            });

            $('form').submit(function () 
            {
                var value = $('#value').val();
                value = value.replace(/\./g, '');
                value = value.replace(',', '.');

                $('#value').val(value);
            });
        });
    </script>
</fieldset>
