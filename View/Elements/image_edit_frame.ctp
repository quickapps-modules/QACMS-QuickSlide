<?php if (QS::isVideo($this->data['Image']['src'])): ?>

	<div id="ss-player" style="padding:2px;">
		<a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.
	</div>

	<script type="text/javascript">
		var flashvars = {
			file: "<?php echo $this->Html->url("/files/quick_slide/album-{$this->data['Image']['aid']}/{$this->data['Image']['src']}"); ?>",
		};
		var params = {
			allowfullscreen: true
		};
		var attributes = { };

		function init_players() {
			swfobject.embedSWF(
				"<?php echo $this->Html->url("/quick_slide/swf/player.swf"); ?>",
				"ss-player",
				"100%",
				"400",
				"9", false, flashvars, params, attributes);
		}
	</script>

<?php elseif (QS::isSwf($this->data['Image']['src'])): ?>

	<div id="ss-player" style="padding:2px;">
		<a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.
	</div>


	<script type="text/javascript">
		var flashvars = {};
		var params = {
			allowfullscreen: true
		};
		var attributes = { };

		function init_players() {
			swfobject.embedSWF(
				"<?php echo $this->Html->url("/files/quick_slide/album-{$this->data['Image']['aid']}/{$this->data['Image']['src']}"); ?>",
				"ss-player",
				"100%",
				"400",
				"9", false, flashvars, params, attributes);
		}
	</script>

<?php else: ?>
	<?php
		$path = QS_FOLDER . "album-{$this->data['Image']['aid']}" . DS . $this->data['Image']['src'];
		$info = getimagesize($path);
	?>

	<div id="the_img">
		<div id="img_wrapper">
			<?php echo $this->Html->image('/quick_slide/img/focal_point.png', array('id' => 'anchor', 'style' => 'display:none;')); ?>
			<img id="img_edit" src="<?php echo $this->Html->url('/quick_slide/images/p/' . QS::p($path, 600, 400, 70, 0, 0, 0, 0)); ?>" border="0" class="<?php echo $info[0] >= $info[1] ? 'wide' : 'tall'; ?>" />
		</div>

		<div class="edit-buttons">
			<a href="#" title="<?php echo __d('quick_slide', 'Change Focal Point'); ?>" class="icon fp" onclick="toggleAnchor(); return false;"></a>
		</div>
	</div>
<?php endif; ?>

<div class="img-info">
	<b><?php echo __d('quick_slide', 'Uploaded'); ?>:</b> <?php echo date(__d('quick_slide', 'Y-m-d H:i'), $this->data['Image']['created']); ?>
	<br />
	<b><?php echo isset($info) ? __d('quick_slide', 'Size') . ':': ''; ?></b> <?php echo isset($info) ? "{$info[0]}x{$info[1]}" : ''; ?>
	<br />
	<b><?php echo __d('quick_slide', 'Content ID'); ?>:</b> <?php echo $this->data['Image']['id']; ?>
</div>

<script type="text/javascript">
<?php
	$anchor_coords = $this->data['Image']['anchor'];

	if (empty($anchor_coords)) {
		$anchor_coords['x'] = $anchor_coords['y'] = 50;
	}
?>
	anc_x = <?php echo $anchor_coords['x']; ?>;
	anc_y = <?php echo $anchor_coords['y']; ?>;
</script>