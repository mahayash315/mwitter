<?php
/**
 * The template for a form to creat a timeline item
 *
 * local variables:
 *    $parent: parent tweet (nullable)
 */ 
?>
<div class="tweet-create">
	<div class="media">
		<form action="javascript:void(0);">
			<?php if (!is_null($parent)) { ?>
			<input type="hidden" name="parent_tid" value="<?php echo $parent->tid; ?>" />
			<?php } ?>
			<div class="media-left icon">
				<img src="holder.js/32x32" class="img-rounded" />
			</div>
			<div class="media-body main">
				<div class="content">
					<?php if (is_null($parent)) { ?>
					<textarea id="content" class="form-control" name="content" rows="3"></textarea>
					<?php } else { ?>
					<textarea id="content" class="form-control" name="content" rows="2"></textarea>
					<?php } ?>
				</div>
				<div class="actions">
					<div class="row">
						<div class="col-sm-6">
							
						</div>
						<div class="col-sm-6">
							<span class="lc-indicator">残り文字数: <span class="injection">140</span>&nbsp;</span>
							<button type="button" class="btn btn-primary btn-tweet">ツイート</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>