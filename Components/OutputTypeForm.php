<div class="form-header">
    <p>Adicionar tipo de saída</p>
</div>

<fieldset class="form-fieldset">
    <form action="Methods/OutputType/InsertOutputType.php" method="POST">
        <div class="row d-flex justify-content-center">
            <input type="text" class="form-control" name="description" id="description" placeholder="Descrição" style="max-width: 40%;" required>
        </div>
        <div class="buttons">
            <button class="btn me-2 save" type="submit"><i class="fa-solid fa-check"></i> Salvar</button>
            <button class="btn clear" type="reset"><i class="fa-solid fa-trash-can"></i> Limpar</button>
        </div>
    </form>
</fieldset>