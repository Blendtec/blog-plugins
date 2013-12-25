<div class="postbox " id="jamiesocial_wheretoshow">
<h3 class="hndle"><span><?php _e('Post Type To show Jamie Social Icons', 'jamie_social'); ?> </span></h3>
<div class="inside">
<?php // WHERE TO SHOW TABLE ?>	
    <table class="form-table">
    <tr>
    <th colspan="4"><?php _e( '<h4>If you have chosen automatic display choose where you would like to icons to automatically be shown ie. Posts And/Or Pages</h4>', 'jamie_social' ); ?></th>
    </tr>
		<tr valign="top">
        <th scope="row" rowspan="2"><?php _e( 'Show on Post Types', 'jamie_social' ); ?></th>
<td>  <?php /*<ul>    <?php
	
 $args=array(
  'public'   => true,
  '_builtin' => true
); 
$output = 'names'; // names or objects, note names is the default
$operator = 'and'; // 'and' or 'or'
 $post_types=get_post_types($args,$output,$operator);  
foreach ($post_types as $post_type ) {?>

<li>?><input id="jamie_social_options[<?php echo  $post_type; ?>]" name="jamie_social_options[<?php echo  $post_type;  ?>]" type="hidden" value="0"/> <?php 
<input id="jamie_social_options[<?php echo  $post_type; ?>]" name="jamie_social_options[<?php echo   $post_type; ?>]" type="checkbox" value="1" <?php checked( '1', $options[''.$post_type.'']); ?> />  
			
						<label class="description" ><?php _e( ''. $post_type. '', 'jamie_social' ); ?></label></li>
					
					<?php } ?></td>*/?>
                   <td> <input id="jamie_social_options[post]" name="jamie_social_options[post]" type="checkbox" value="1" <?php checked( '1', $options['post'] ); ?> />  
			
						<label class="description" ><?php _e( 'Show On Posts', 'jamie_social' ); ?></label>
					</td>
                      <td> <input id="jamie_social_options[page]" name="jamie_social_options[page]" type="checkbox" value="1" <?php checked( '1', $options['page'] ); ?> />  
			
						<label class="description" ><?php _e( 'Show On Pages', 'jamie_social' ); ?></label>
					</td>
               <?php /*?> </tr>    <tr>
                     <td> <input id="jamie_social_options[home]" name="jamie_social_options[home]" type="checkbox" value="1" <?php checked( '1', $options['home'] ); ?> />  
			
						<label class="description" ><?php _e( 'Show On Home Page ', 'jamie_social' ); ?></label>
				</td>
                <td>
                    <input id="jamie_social_options[archive]" name="jamie_social_options[archive]" type="checkbox" value="1" <?php checked( '1', $options['archive'] ); ?> />  
			
						<label class="description" ><?php _e( 'Show On Archive Pages', 'jamie_social' ); ?></label> </td><td><input id="jamie_social_options[category]" name="jamie_social_options[category]" type="checkbox" value="1" <?php checked( '1', $options['category'] ); ?> />  
			
						<label class="description" ><?php _e( 'Show On Category Pages', 'jamie_social' ); ?></label>
					</td><?php */?>
				</tr>
                </table>
                </div>
                </div>