<div class="form-header">
    <p>Adicionar saída</p>
</div>

<fieldset class="form-fieldset">
    <form action="Methods/Output/InsertOutput.php" method="POST">
            <div class="row">
                <div class="col-3">
                    <select name="output-type-id" id="output-type-id" class="form-select" required>
                        <option selected disabled value="">Selecione um tipo</option>

                        <?php     
                            $outputTypes = OutputTypeRepository::getAll(); 

                            foreach ($outputTypes as $outputType)
                            {
                                echo "<option value=\"{$outputType['id']}\">{$outputType['description']}</option>";
                            }
                        ?>
                    </select>
                </div>

                <div class="col-5">
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

                <div class="buttons">
                    <button class="btn me-2 save" type="submit"><i class="fa-solid fa-check"></i> Salvar</button>
                    <button class="btn clear" type="reset"><i class="fa-solid fa-trash-can"></i> Limpar</button>
                </div>
        </div>
    </form>

    <script>
        function maskValue(input) 
        {
            var value = input.value;
            
            // Remove todos os caracteres que não são dígitos, exceto o sinal de negativo
            value = value.replace(/[^\d-]/g, '');
            
            // Adiciona o sinal de negativo se não estiver presente
            if (value.charAt(0) != '-') 
            {
                value = '-' + value;
            }
            
            // Remove o sinal de negativo se todos os números forem apagados
            if (value == '-') 
            {
                value = '';
            }
            
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