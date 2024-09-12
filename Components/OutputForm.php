<div class="form-header">
    <p>Adicionar saída</p>
</div>

<fieldset class="form-fieldset">
    <form action="Methods/Output/InsertOutput.php" method="POST">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3 mb-1 mb-md-0">
                <select name="output-type-id" id="output-type-id" class="form-select" required>
                    <option selected disabled value="">Selecione um tipo</option>
                    <?php     
                        $outputTypes = OutputTypeRepository::getAll(); 
                        foreach ($outputTypes as $outputType) {
                            echo "<option value=\"{$outputType['id']}\">{$outputType['description']}</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="col-12 col-sm-6 col-md-5 mb-1 mb-md-0">
                <input type="text" class="form-control" name="description" id="description" maxlength="100" placeholder="Descrição" required>
            </div>

            <div class="col-6 col-sm-4 col-md-2 mb-1 mb-md-0">
                <div class="input-group form-group">
                    <span class="input-group-text currency-span">R$</span>
                    <input type="text" class="form-control" name="value" id="value" placeholder="00,00" required>
                </div>
            </div>

            <div class="col-6 col-sm-4 col-md-2 mb-1 mb-md-0">
                <input type="date" class="form-control" name="date" id="date" value="<?= date('Y-m-d') ?>" required>
            </div>
        </div>
        
        <div class="buttons">
            <button class="btn me-2 save" type="submit"><i class="fa-solid fa-check"></i> Salvar</button>
            <button class="btn clear" type="reset"><i class="fa-solid fa-trash-can"></i> Limpar</button>
        </div>
    </form>
</fieldset>
