<?php
/**
 */
 
?>
<div class="fc-filters-dialog-holder">
	<div class="fc-filters-dialog">
		<div class="fbd-arrow-holder">
			<div class="fbd-arrow"></div>
			<div class="fbd-arrow-border"></div>
		</div>
		<div class="fbd-main-holder">
			<div class="fbd-head">&nbsp;</div>
			<div class="fbd-body">
				<div class="fbd-dialog-content">
<?php echo $this->calendars_form_tabs($post_type);?>			
				</div>
				<div class="fbd-dialog-controls">
					<input type="button" class="fbd-button-secondary fbd-dg-remove" name="fbd-dg-remove" value="<?php _e('Show all','rhc')?>" />
					<input type="button" class="fbd-button fbd-button-primary fbd-dg-apply" name="fbd-dg-apply" value="<?php _e('Apply filters','rhc')?>" />
					<div class="fbd-status">
						<img src="<?php echo admin_url('/images/wpspin_light.gif')?>" alt="" />
					</div>
					<div class="fbd-clear"></div>
				</div>
				<div class="fbd-clear"></div>
			</div>
			<div class="fbd-clear"></div>
		</div>
	</div>
</div>
