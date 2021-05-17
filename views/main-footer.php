		</div>
		<!-- end row -->
	</div>
	<!-- container -->
</div>
<!-- Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div id="errorBody" class="modal-body text-center">
				<h4 class="text-center text-danger font-weight-bold text-uppercase">Ошибка</h4>
			</div>
		</div>
	</div>
</div>
<?if($headman || $self['is-admin']):?>
<div class="modal fade" id="editScheduleModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalEditLabel">Редактируем ...</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="editBody">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
					<button id="saveChanges" type="button" class="btn btn-info">Сохранить</button>
				</div>
			</div>
	</div>
</div>
<script src="<?=auto_version("/js/headman.tools.min.js")?>"></script>
<?endif?>
<?if($self['is-admin']):?>
<script src="<?=auto_version("/js/admin.tools.js")?>"></script>
<script src="<?=auto_version("/js/jquery.cookie.js")?>"></script>
<?endif?>
<script src="<?=auto_version("/js/schedule.js")?>"></script>
<?if(!strcmp($_SERVER['SCRIPT_NAME'],"/profile.php") && !empty($_GET['go'])) if($_GET['go'] == 'settings' || $_GET['go'] == 'edit'):?>
<script src="<?=auto_version("/js/schedule.options.js")?>"></script>
<?endif?>
<script src="<?=auto_version("/js/jquery.maskedinput.js")?>"></script>
<?if(!strcmp($_SERVER['SCRIPT_NAME'],"/profile.php") && !empty($_GET['go'])) if($_GET['go'] == 'calc'):?>
<script src="<?=auto_version("/js/calc.min.js")?>"></script>
<?endif?>
</body>
</html>
