<?php
/**
 * Управление плагином
 * User: P.Nixx
 * Date: 07.11.12
 * Time: 15:00
 */
?>
<? if( !get_option('livdis_uid') ): ?>
<div class="wrap">
	<div class="ld-header">
		<a class="ld-logo" href="<?= LD_URL ?>"></a>
		<div class="ld-logo-bottom"></div>
	</div>
	<div class="ld-content">
		<iframe id="auth_frame" src="<?= LD_URL ?>/api/login/form?layout=wordpress"></iframe>
		<form method="post" style="display: none;" id="form_app">
			<input type="hidden" name="livdis_form_counter_sub" value="Y">
			<input type="hidden" id="livdis_uid" name="livdis_uid">
			<button>Save</button>
		</form>
	</div>
</div>
<? else: ?>
<style type="text/css">
	html { background: #F5F5F5; }
</style>
<div class="wrap-auth">
	<iframe id="auth_frame" src="<?= LD_URL ?>/api/wordpress/community?app_id=<?= get_option('livdis_uid') ?>"></iframe>
</div>
<? endif ?>
