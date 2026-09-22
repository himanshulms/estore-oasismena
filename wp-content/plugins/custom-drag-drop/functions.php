<?php
add_action('admin_footer', function() {
        global $pagenow, $post;

    	if (($pagenow !== 'post.php' && $pagenow !== 'post-new.php') || !$post) return;

		 $saved_order = get_post_meta($post->ID, '_cfs_field_order', true);
		?>
		<script>
		jQuery(document).ready(function($) {
			var $fieldsWrapper = $('#normal-sortables');
			if (!$fieldsWrapper.length) return;

			if (!$('#cfs_field_order').length) {
				$('<input>').attr({
					type: 'hidden',
					id: 'cfs_field_order',
					name: 'cfs_field_order'
				}).appendTo('form#post');
			}
			

			var savedOrder = "<?php echo esc_js($saved_order); ?>";
			if (savedOrder) {
				var fieldTitles = savedOrder.split(',');
				fieldTitles.forEach(function(title) {
					var $fieldBox = $fieldsWrapper.find('.cfs_input').filter(function() {
						return $(this).find('.postbox-header h2').html() == title;
					});
					if ($fieldBox.length) {
						$fieldsWrapper.append($fieldBox);
						console.log(savedOrder);
					}
				});
			}
			function moveCfsFieldsOnTop() {
				var $cfsFields = $fieldsWrapper.find('.cfs_input');
				if ($cfsFields.length) {
					// Move all CFS fields to the top of normal meta boxes
					$cfsFields.prependTo($fieldsWrapper);
				}
			}

			// Run on page load
			moveCfsFieldsOnTop();

				
			 function updateOrder() {
					var order = [];
					$fieldsWrapper.find('.cfs_input').each(function(i) {
						var fieldId = $(this).find('.postbox-header h2').html();
						if (fieldId){
							order.push(fieldId);
						}
						const $index = $('<span>')
						.addClass('cfs-field-index') 
						.text(' (' + (i + 1) + ')');

						$(this).find('.postbox-header .cfs-field-index').remove();
						$(this).find('.postbox-header h2').after($index);

						
					});
					$('#cfs_field_order').val(order.join(','));
				}

				  updateOrder();

				
				// Enable sorting
				$fieldsWrapper.sortable({
					update: function() {
					updateOrder();
					 moveCfsFieldsOnTop();
						
					}
				});
		});
		</script>
		<?php
});

add_action('save_post', function($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
   
	  if (isset($_POST['cfs_field_order']) && !empty($_POST['cfs_field_order'])) {
			update_post_meta(
				$post_id,
				'_cfs_field_order',
				sanitize_text_field($_POST['cfs_field_order'])
			);
		}
		
});