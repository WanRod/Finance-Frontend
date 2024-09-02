<div class="form-header">
    <p>Adicionar entrada</p>
</div>

<fieldset class="form-fieldset">
    <form action="Methods/Input/InsertInput.php" method="POST">
        <div class="row">
            <div class="col-8">
                <input type="text" class="form-control" name="description" id="description" maxlength="100" placeholder="Descrição" required>
            </div>

            <div class="col-2">
                <div class="input-group">
                    <span class="input-group-text currency-span">R$</span>
                    <input type="text" class="form-control" name="value" id="value" placeholder="00,00" required>
                </div>
            </div>

            <div class="col-2">
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
            
            // Remove todos os caracteres que não são dígitos
            value = value.replace(/[^\d]/g, '');
                    
            // Formata o valor com pontos e vírgula
            value = value.replace(/(\d{1,})(\d{2})$/, '$1,$2');
            value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            
            // Atualiza o valor no campo de entrada
            input.value = value;
        }

        $(document).ready(function () 
        {
            // Aplicar máscara ao campo de valor
            $('#value').on('input', function () 
            {
                maskValue(this);
            });

            // Remover a máscara antes de enviar o formulário
            $('form').submit(function () 
            {
                var value = $('#value').val(); // Obter o valor com a máscara
                value = value.replace(/\./g, ''); // Remover pontos
                value = value.replace(',', '.'); // Trocar vírgula por ponto

                $('#value').val(value); // Definir o valor corrigido no campo
            });
        });
    </script>
</fieldset>