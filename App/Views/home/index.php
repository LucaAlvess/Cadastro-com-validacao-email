<div class="container">
	<div class="row">
		<div class="col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4">
			<?php if ($mensagem = \App\Lib\Sessao::retornaMensagem()): ?>
				<div class="alert alert-danger">
					<p><?= $mensagem; ?></p>
				</div>
			<?php endif; ?>

			<form id="form_cadastro" action="/usuario/cadastrar" method="post">
				<fieldset>
					<legend>Cadastro de usuário</legend>
					<div class="form-group">
						<label for="email">Email</label>
						<input id="email" type="email" class="form-control" name="email"
							   value="<?= \App\Lib\Sessao::retornaValorFormulario('email'); ?>" maxlength="100"
							   required>
					</div>
					<div class="form-group">
						<label for="login">Login</label>
						<input id="login" type="text" class="form-control" name="login"
							   value="<?= \App\Lib\Sessao::retornaValorFormulario('login'); ?>" maxlength="20"
							   required>
					</div>
					<div class="form-group">
						<label for="senha">Senha</label>
						<input id="senha" type="password" class="form-control" name="senha"
							   value="<?= \App\Lib\Sessao::retornaValorFormulario('senha'); ?>" maxlength="50"
							   required>
					</div>
					<div class="form-group align-right">
						<button class="btn btn-primary btn-block"><span class="glyphicon glyphicon-ok"></span></button>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
</div>
