<div class="modal fade" id="profile-modal" tabindex="-1" aria-labelledby="profile-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <?php
                    $user = UserRepository::getData();

                    if ($user === null)
                    {
                        echo "<div>
                                <b>Não foi possível conectar à API, tente novamente mais tarde.</b>
                              </div>";
                    }
                    else if (!isset($user['error']['message']))
                    {
                        $user =  $user['body'];

                        echo '<div>
                                <h2>' . $user['name'] . '</h2>
                              </div>';
        
                        echo '<div>
                                <b>' . $user['cpf_cnpj'] . '</b>
                              </div>';
                ?>

                    <fieldset class="custom-fieldset">
                        <legend><i class="fa-solid fa-key"></i> Alterar Senha</legend>
                        <div class="row">
                            <div class="col">
                                <input type="password" class="form-control" name="newPassword" maxlength="100" placeholder="Nova senha" required>
                            </div>
                            <div class="col">
                                <input type="password" class="form-control" name="confirmPassword" maxlength="100" placeholder="Confirmar senha" required>
                            </div>
                        </div>
                    </fieldset>

                <?php
                    }
                    else
                    {
                        echo "<div>
                                <b>{$user['error']['message']}</b>
                              </div>";
                    }
                ?>
            </div>

            <div class="modal-footer">
                <button type="button" id="profile-save" class="btn save" disabled><i class="fa-solid fa-check"></i> Salvar</button>
                <button type="button" id="profile-cancel" class="btn clear" data-bs-dismiss="modal"><i class="fa-regular fa-circle-xmark"></i> Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function()
    {
        const newPasswordInput = document.querySelector('input[name="newPassword"]');
        const confirmPasswordInput = document.querySelector('input[name="confirmPassword"]');
        const saveButton = document.getElementById('profile-save');
        const cancelButton = document.getElementById('profile-cancel');
        const passwordStrengthIndicator = document.createElement('span');

        // Adiciona o indicador de força da senha
        newPasswordInput.parentNode.appendChild(passwordStrengthIndicator);

        // Desativa o segundo input inicialmente
        confirmPasswordInput.disabled = true;

        // Função para verificar se as senhas coincidem e a força da senha
        function validatePasswords()
        {
            const password = newPasswordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            // Mostra ou esconde o indicador de força da senha
            if (password.length > 0)
            {
                passwordStrengthIndicator.style.display = 'block';
            }
            else
            {
                passwordStrengthIndicator.style.display = 'none';
                saveButton.disabled = true;
                confirmPasswordInput.disabled = true;
                confirmPasswordInput.value = '';
                return;
            }

            confirmPasswordInput.disabled = password.length < 3;

            let strengthMessage;

            if (password.length < 3)
            {
                strengthMessage = 'Senha muito curta';
                passwordStrengthIndicator.style.color = 'red';
            }
            else if (password.length < 5)
            {
                strengthMessage = 'Senha fraca';
                passwordStrengthIndicator.style.color = 'orange';
            }
            else if (!/[A-Z]/.test(password) && !/[0-9]/.test(password) || !/[!@#\$%\^&\*]/.test(password))
            {
                strengthMessage = 'Senha moderada';
                passwordStrengthIndicator.style.color = 'gold';
            }
            else
            {
                strengthMessage = 'Senha forte';
                passwordStrengthIndicator.style.color = 'green';
            }

            passwordStrengthIndicator.textContent = strengthMessage;

            saveButton.disabled = password === '' || password !== confirmPassword;
        }

        newPasswordInput.addEventListener('input', validatePasswords);
        confirmPasswordInput.addEventListener('input', validatePasswords);

        $(saveButton).on('click', function()
        {
            var password = $('input[name="newPassword"]').val();

            $.ajax({
                url: 'Methods/User/UpdateUserPassword.php',
                type: 'POST',
                data: 
                { 
                    password: password
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

        cancelButton.addEventListener('click', function () {
            newPasswordInput.value = '';
            confirmPasswordInput.value = '';
            confirmPasswordInput.disabled = true;
            passwordStrengthIndicator.style.display = 'none';
            saveButton.disabled = true;
        });

        saveButton.disabled = true;
    });
</script>
