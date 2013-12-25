<div class="postbox " id="jamiesocial_lang">
<h3 class="hndle"><span><?php _e('Language Of Jamie Social Icons (FB initially)', 'jamie_social'); ?> </span></h3>
<div class="inside">
<table class="form-table">
<tr width="80%">
<th colspan="2" width="80%">
<?php _e('Jamie Social Icons should automatically detect what language to show the icons in if for some reason it does not or you would like to change the default language change the folowing - this is will initially change Facebook, I believe Twitter automatically changes depending on hosted server and LinkedIn & Pinterest language cannot be changed (let me know if I am wrong) and Google Plus is universal anyway.  If I have missed a language that you want let me know');?>
</th>
</tr>
         <tr width="80%">
         <th width="20%"><?php _e('Preffered language', 'jamie_social');?></th>
					<td width="80%">
                    <input id="jamie_social_options[lang]" name="jamie_social_options[lang]" type="hidden" value="default" />
				<ul>
                <li><input id="jamie_social_options[lang]" name="jamie_social_options[lang]" type="radio" value="default"<?php checked ('default' == $options['lang']);?> />
						<label class="description"><?php _e( 'Default Language', 'jamie_social' ); ?></label></li>
                        <li>				
				<input id="jamie_social_options[lang]" name="jamie_social_options[lang]" type="radio" value="eng"<?php checked ('eng' == $options['lang']);?> />
						<label class="description"><?php _e( 'English', 'jamie_social' ); ?></label></li>
					<li><input id="jamie_social_options[lang]" name="jamie_social_options[lang]" type="radio" value="esp"<?php checked ('esp' == $options['lang']);?> />
						<label class="description"><?php _e( 'Espanol', 'jamie_social' ); ?></label></li>
			<li><input id="jamie_social_options[lang]" name="jamie_social_options[lang]" type="radio" value="pt"<?php checked ('pt' == $options['lang']);?> />
						<label class="description"><?php _e( 'Portuguese', 'jamie_social' ); ?></label></li>
                        <li><input id="jamie_social_options[lang]" name="jamie_social_options[lang]" type="radio" value="de"<?php checked ('de' == $options['lang']);?> />
						<label class="description"><?php _e( 'Deutsch', 'jamie_social' ); ?></label></li>
                        <li><input id="jamie_social_options[lang]" name="jamie_social_options[lang]" type="radio" value="fr"<?php checked ('fr' == $options['lang']);?> />
						<label class="description"><?php _e( 'Français', 'jamie_social' ); ?></label></li>
                        </ul>
					</td>
				</tr>
                </table> 
                </div><!--end of inside-->
</div><!--end of postbox-->
