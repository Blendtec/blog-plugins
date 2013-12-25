<div class="postbox " id="jamiesocial_position">
<h3 class="hndle"><span><?php _e('Showing Of Jamie Social Icons', 'jamie_social'); ?> </span></h3>
<div class="inside">
<table class="form-table">
         <tr width="80%">
         <th width="20%"><?php _e('Automatic icon display', 'jamie_social');?></th>
					<td width="80%">
				<ul>
                <li><input id="jamie_social_options[showing]" name="jamie_social_options[showing]" type="hidden" value="noauto" />
                <input id="jamie_social_options[showing]" name="jamie_social_options[showing]" type="radio" value="top"<?php checked ('top' == $options['showing']);?> />
						<label class="description"><?php _e( 'Top Of Post', 'jamie_social' ); ?></label></li>
                        <li>				
				<input id="jamie_social_options[showing]" name="jamie_social_options[showing]" type="radio" value="bottom"<?php checked ('bottom' == $options['showing']);?> />
						<label class="description"><?php _e( 'Bottom of post', 'jamie_social' ); ?></label></li>
					<li><input id="jamie_social_options[showing]" name="jamie_social_options[showing]" type="radio" value="topbottom"<?php checked ('topbottom' == $options['showing']);?> />
						<label class="description"><?php _e( 'Top & Bottom of post', 'jamie_social' ); ?></label></li>
			<li><input id="jamie_social_options[showing]" name="jamie_social_options[showing]" type="radio" value="noauto"<?php checked ('noauto' == $options['showing']);?> />
						<label class="description"><?php _e( 'No Automatic show ', 'jamie_social' ); ?></label></li>
                        </ul>
					</td>
				</tr>
                
                
                <tr width="80%">
         <th width="20%"><?php _e('Alignment Of icon list', 'jamie_social');?></th>
					<td width="80%">
				<ul>
                <li><input id="jamie_social_options[align]" name="jamie_social_options[align]" type="radio" value="left"<?php checked ('left' == $options['align']);?> />
						<label class="description"><?php _e( 'Left Align Icon List', 'jamie_social' ); ?></label></li>
                        <li>				
				<input id="jamie_social_options[align]" name="jamie_social_options[align]" type="radio" value="center"<?php checked ('center' == $options['align']);?> />
						<label class="description"><?php _e( 'Center Align Icon List', 'jamie_social' ); ?></label></li>
					<li><input id="jamie_social_options[align]" name="jamie_social_options[align]" type="radio" value="right"<?php checked ('right' == $options['align']);?> />
						<label class="description"><?php _e( 'Right Align Icon List', 'jamie_social' ); ?></label></li>
			                        </ul>
					</td>
                    </tr>
                    <tr>
                    <th colspan="2">
                    <?php _e('The following function adds the ability to add text before the icons I have added basic styling to this but this can be overridden in your themes styles - unfortunately because each theme has different requirements in styles I won&acute;t be able to support the styling of this element.  You may experience unexpected results by aligning the icons center and using text before icons')?>
                    </th>
                    </tr>
                    <tr>
                    <th width="20%"><?php _e('Text To Be Shown infront of icons', 'jamie_social');?></th>
                    <td>
                    <ul>
                     <li>				
				<input id="jamie_social_options[icontext_]" name="jamie_social_options[icontext_]" type="radio" value="share"<?php checked ('share' == $options['icontext_']);?> />
						<label class="description"><?php _e( 'Show &quot;<strong>Share</strong>&quot; Before icons', 'jamie_social' ); ?></label></li>
					<li><input id="jamie_social_options[icontext_]" name="jamie_social_options[icontext_]" type="radio" value="sharewith"<?php checked ('sharewith' == $options['icontext_']);?> />
						<label class="description"><?php _e( '&quot;<strong>Share Post/Page</strong>&quot; (dependent on what you are on)', 'jamie_social' ); ?></label></li>
                        <li><input id="jamie_social_options[icontext_]" name="jamie_social_options[icontext_]" type="radio" value="custom"<?php checked ('custom' == $options['icontext_']);?> />
						<label class="description"><?php _e( 'Custom text type below', 'jamie_social' ); ?></label><br />

                    <input id="jamie_social_options[icontext]" name="jamie_social_options[icontext]" type="text" value="<?php echo $options['icontext']; ?>"  />
						<label class="description"><?php _e( 'Custom text to be shown before icons', 'jamie_social' ); ?></label></li>
                        <li>				
				<input id="jamie_social_options[icontext_]" name="jamie_social_options[icontext_]" type="radio" value="none"<?php checked ('none' == $options['icontext_']);?> />
						<label class="description"><?php _e( 'Show No text', 'jamie_social' ); ?></label></li>
			                        </ul>
                    </td>
                    
				</tr>
                
                
                
                </table> 
                </div><!--end of inside-->
</div><!--end of postbox-->
