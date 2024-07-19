<div class="modal fade" id="edit-modal" tabindex="-1" aria-labelledby="edit-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-7">
                    <h5 class="modal-title">Editar registro</h5>
                </div>
                
                <div class="col-5">
                    <input type="text" id="edit-id" class="form-control" disabled>
                </div>
            </div>
            <div class="modal-body row my-2">
                <div class="col-sm">
                    <select name="edit-output-type-id" id="edit-output-type-id" class="form-select" required>
                        <option selected disabled value="">Selecione um tipo</option>

                        <?php
                            foreach ($outputTypes as $outputType)
                            {
                                echo "<option value=\"{$outputType['id']}\">{$outputType['description']}</option>";
                            }
                        ?>
                    </select>
                </div>

                <div class="col-sm">
                    <input type="text" class="form-control" name="edit-description" id="edit-description" placeholder="Descrição" required>
                </div>

                <div class="col-sm">
                    <div class="input-group">
                        <span class="input-group-text currency-span">R$</span>
                        <input type="text" class="form-control" name="edit-value" id="edit-value" placeholder="00,00" required>
                    </div>
                </div>

                <div class="col-sm">
                    <input type="date" class="form-control" name="edit-date" id="edit-date" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="confirm-edit" class="btn btn-success save"><i class="fa-solid fa-check"></i> Salvar</button>
                <button type="button" id="cancel-edit" class="btn btn-warning clear" data-bs-dismiss="modal"><i class="fa-regular fa-circle-xmark"></i> Cancelar</button>
            </div>
        </div>
    </div>
</div>