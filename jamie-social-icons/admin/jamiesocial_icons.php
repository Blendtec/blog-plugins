<div class="postbox " id="jamiesocial_icons">
<h3 class="hndle"><span><?php _e('What Jamie Social Icons To show', 'jamie_social'); ?> </span></h3>
<div class="inside">
<?php //SOCIAL ICONS TABLE  ?>
			<table class="form-table">
            <tr valign="top"><th scope="row"></th>
					<td>
<input id="jamie_social_options[facebook]" name="jamie_social_options[facebook]" type="hidden" value="0" />
<input id="jamie_social_options[facebook_recommend]" name="jamie_social_options[facebook[facebook_recommend]" type="hidden" value="0" />
<input id="jamie_social_options[google]" name="jamie_social_options[google]" type="hidden" value="0" />
<input id="jamie_social_options[twitter]" name="jamie_social_options[twitter]" type="hidden" value="0" />
<input id="jamie_social_options[linkedin]" name="jamie_social_options[linkedin]" type="hidden" value="0" />
<input id="jamie_social_options[pinterest]" name="jamie_social_options[pinterest]" type="hidden" value="0" />
</td>
                    </tr>
		<tr valign="top"><th scope="row"><?php _e( 'Show Facebook Like', 'jamie_social' ); ?></th>
					<td>
						<input id="jamie_social_options[facebook]" name="jamie_social_options[facebook]" type="checkbox" value="1" <?php checked( '1', $options['facebook'] ); ?> />
						<label class="description" for="jamie_social_options[facebook]"><?php _e( 'Facebook Like', 'jamie_social' ); ?></label>
 <td><input id="jamie_social_options[facebook_recommend]" name="jamie_social_options[facebook_recommend]" type="checkbox" value="1" <?php checked( $options['facebook_recommend'], 1 ); ?> />
<label class="description" for="jamie_social_options[facebook_recommend]"><?php _e( 'Change Verb to Facebook Recommend', 'jamie_social' ); ?></label></td>
                    <td><fb:like  <?php if($options['facebook_recommend'] == true){?>action="recommend" <?php } ?>  send="false" layout="button_count" href="http://www.martiniboy.co.uk/wordpress-plugins/jamie-social-icons/" show_faces="false" width="90"></fb:like></td>
				</tr>
                <tr valign="top"><th scope="row"><?php _e( 'Show Google Plus This', 'jamie_social' ); ?></th>
					<td colspan="2">
						<input id="jamie_social_options[google]" name="jamie_social_options[google]" type="checkbox" value="1" <?php checked( '1', $options['google'] ); ?> />
						<label class="description" for="jamie_social_options[google]"><?php _e( 'Google +', 'jamie_social' ); ?></label>
					</td>
                    <td><g:plusone size="medium" href="http://www.martiniboy.co.uk/wordpress-plugins/jamie-social-icons/" callback="plusone_vote"></g:plusone></td>
				</tr>
                <tr valign="top"><th scope="row"><?php _e( 'Show Tweet This', 'jamie_social' ); ?></th>
					<td colspan="2">
						<input id="jamie_social_options[twitter]" name="jamie_social_options[twitter]" type="checkbox" value="1" <?php checked( '1', $options['twitter'] ); ?> />
						<label class="description" for="jamie_social_options[twitter]"><?php _e( 'Twitter', 'jamie_social' ); ?></label>
					</td>
                    <td><a href="https://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-url="http://www.martiniboy.co.uk/wordpress-plugins/jamie-social-icons/" data-text="Check out Jamie Social Icons http://www.martiniboy.co.uk/wordpress-plugins/jamie-social-icons/">Tweet</a></td>
				</tr>
                    <tr valign="top"><th scope="row"><?php _e( 'Show Linked In Like', 'jamie_social' ); ?></th>
					<td colspan="2">
						<input id="jamie_social_options[linkedin]" name="jamie_social_options[linkedin]" type="checkbox" value="1" <?php checked( '1', $options['linkedin'] ); ?> />
						<label class="description" for="jamie_social_options[linkedin]"><?php _e( 'LinkedIn', 'jamie_social' ); ?></label>
					</td>
                    <td><script type="in/share" data-url="http://www.martiniboy.co.uk/wordpress-plugins/jamie-social-icons" data-counter="right" data-onsuccess="LinkedInShare"></script></td>
				</tr>
               <tr valign="top"><th scope="row"><?php _e( 'Show Pinterest This', 'jamie_social' ); ?></th>
					<td colspan="2">
						<input id="jamie_social_options[pinterest]" name="jamie_social_options[pinterest]" type="checkbox" value="1" <?php checked( '1', $options['pinterest'] ); ?> />
						<label class="description" for="jamie_social_options[pinterest]"><?php _e( 'Pinterest', 'jamie_social' ); ?></label>
					</td>
                    <td><a class="pin-it-button" count-layout="horizontal" always-show-count="1" href="http://pinterest.com/pin/create/button/?url=http://www.martiniboy.co.uk/wordpress-plugins/jamie-social-icons">Pin It</a> <a href="javascript:void(run_pinmarklet())" class="pinarea"></a>
  <script src="http://assets.pinterest.com/js/pinit.js" type="text/javascript"/></script></td>
				</tr>
				</table>
                </div>
                </div>