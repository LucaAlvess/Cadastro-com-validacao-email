<div class="container">
	<div class="jumbotron">
		<h1>Ativação de cadastro</h1>
		
		<p><?php echo \App\Lib\Sessao::retornaMensagem(); ?></p>
		
		<?php if (\App\Lib\Sessao::existeFormulario()): ?>
			<form action="/usuario/reenviar" method="post">
				<input type="hidden" name="email"
					   value="<?php echo \App\Lib\Sessao::retornaValorFormulario('email'); ?>">
				
				<button class="btn btn-default">Enviar novamente</button>
			</form>
		<?php else: ?>
			<p>
				<a class="btn btn-lg btn-primary" href="/" role="button">Voltar</a>
			</p>
		<?php endif; ?>
	</div>
</div>