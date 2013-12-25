<?php
$admin_fields = array(
	array('slug'=> '', 'type' => 'title', 'label'=> 'Subscribe Form Settings', 'val' => '' ),
	array(
		'slug'	=> 'ny_subscribe_field_form_heading',
		'type'	=> 'text',
		'label'	=> 'Form Heading',
		'val'	=> 'Subscribe'
	),
	array(
		'slug'	=> 'ny_subscribe_field_form_description',
		'type'	=> 'textarea',
		'label'	=> 'Some description before the form',
		'val'	=> 'Subscribe to get updates!'
	),
	array(
		'slug'	=> 'ny_subscribe_field_label',
		'type'	=> 'text',
		'label'	=> 'Label for Submit Button',
		'val'	=> 'Subscribe'
	),
	array('slug'=> '', 'type' => 'subtitle', 'label'=> 'Fields:', 'val' => '' ),
	array(
		'slug'	=> 'ny_subscribe_email_field_text',
		'type'	=> 'text',
		'label'	=> 'Label for Email field',
		'val'	=> 'E-Mail'
	),
	array(
		'slug'	=> 'ny_subscribe_name_field_text',
		'type'	=> 'text',
		'label'	=> 'Label for Name field<br/><small>(Leave empty if not in used)</small>',
		'val'	=> ''
	),
	array(
		'slug'	=> 'ny_subscribe_field_custom',
		'type'	=> 'text',
		'label'	=> 'Label for Custom field<br/><small>(Leave empty if not in used)</small>',
		'val'	=> ''
	),
	
	// MESSAGES
	array('slug'=> '', 'type' => 'subtitle', 'label'=> 'Error Messages:', 'val' => '' ),
	array(
		'slug'	=> 'ny_subscribe_name_field_error_msg',
		'type'	=> 'text',
		'label'	=> 'If Name field is empty',
		'val'	=> 'Type Name'
	),
	array(
		'slug'	=> 'ny_subscribe_field_email_invalid',
		'type'	=> 'text',
		'label'	=> 'If Invalid email',
		'val'	=> 'Invalid Email'
	),
	array(
		'slug'	=> 'ny_subscribe_field_email_exist',
		'type'	=> 'text',
		'label'	=> 'If Email already exist',
		'val'	=> 'This Email already registered'
	),
	array(
		'slug'	=> 'ny_subscribe_field_custom_error_message',
		'type'	=> 'text',
		'label'	=> 'If custom field is empty',
		'val'	=> 'Required...'
	),
	array(
		'slug'	=> 'ny_subscribe_field_unable_to_subscribe',
		'type'	=> 'text',
		'label'	=> 'If form is not successfully submitted',
		'val'	=> 'Unable to subscribe'
	),
	array('slug'=> '', 'type' => 'subtitle', 'label'=> 'Success Message:', 'val' => '' ),
	array(
		'slug'	=> 'ny_subscribe_field_success',
		'type'	=> 'textarea',
		'label'	=> 'If form is successfully submitted',
		'val'	=> 'To confirm your subscription, please check your email.'
	),
	
	array('slug'=> '', 'type' => 'subtitle', 'label'=> 'Other:', 'val' => '' ),
	array(
		'slug'	=> 'ny_subscribe_field_hide_form',
		'type'	=> 'checkbox',
		'label'	=> 'Hide subscribe form after success',
		'val'	=> 1
	),
	array(
		'slug'	=> 'ny_subscribe_field_send_email',
		'type'	=> 'checkbox',
		'label'	=> 'Notify admin for each successful subscription',
		'val'	=> 1
	),
	array(
		'slug'	=> 'ny_subscribe_field_show_count',
		'type'	=> 'checkbox',
		'label'	=> 'Show subscribers count to users',
		'val'	=> ''
	),
	
	// SUBSCRIBE PAGE
	array('slug'=> '', 'type' => 'title', 'label'=> 'Subscribe Page', 'val' => '' ),
	array(
		'slug'	=> 'ny_subscribe_field_sub_form',
		'type'	=> 'checkbox',
		'label'	=> 'Display subscribe form onto Subscribe Page<br/><small>(If checked, Subscribe widget will be disabled!)</small>',
		'val'	=> ''
	),
	array(
		'slug'	=> 'ny_subscribe_field_sub_empty',
		'type'	=> 'textarea',
		'label'	=> 'Text - Default<br/><small>(If Subscribe page - 1. Accessed directly without parameters and 2. Does not have Subscribe form.)</small>',
		'val'	=> 'This page contains nothing until you confirm your subscription!'
	),
	array(
		'slug'	=> 'ny_subscribe_field_sub_confirmed',
		'type'	=> 'text',
		'label'	=> 'Text - If subscription confirmed',
		'val'	=> 'Congrats! Your subscription has been confirmed!'
	),
	array(
		'slug'	=> 'ny_subscribe_field_sub_not_confirmed',
		'type'	=> 'text',
		'label'	=> 'Text - If subscription is not confirmed',
		'val'	=> 'Sorry! Cannot confirm your subscription'
	),
	
	// UNSUBSCRIBE PAGE
	array('slug'=> '', 'type' => 'title', 'label'=> 'UnSubscribe Page', 'val' => '' ),
	array(
		'slug'	=> 'ny_subscribe_field_unsub_confirmed',
		'type'	=> 'text',
		'label'	=> 'Text - If unsubscription confirmed',
		'val'	=> 'Done! You will not receive any email from us.'
	),
	array(
		'slug'	=> 'ny_subscribe_field_unsub_not_confirmed',
		'type'	=> 'text',
		'label'	=> 'Text - If unsubscription is not confirmed',
		'val'	=> 'OOPs! Cannot unsubscribe!'
	),
	array('slug'=> '', 'type' => 'subtitle', 'label'=> 'UnSubscribe Form', 'val' => '' ),
	array(
		'slug'	=> 'ny_unsubscribe_msg_before_submit',
		'type'	=> 'text',
		'label'	=> 'Text - Above the form',
		'val'	=> 'Please type your email address to unsubscribe'
	),
	array(
		'slug'	=> 'ny_unsubscribe_button_label',
		'type'	=> 'text',
		'label'	=> 'Label for UnSubscribe Button',
		'val'	=> 'UnSubscribe'
	),		
	array(
		'slug'	=> 'ny_unsubscribe_msg_email_not_exist',
		'type'	=> 'text',
		'label'	=> 'Message - if email not exist',
		'val'	=> 'Cannot unsubscribe! This email does not exist.'
	),
	array(
		'slug'	=> 'ny_unsubscribe_msg_after_submit',
		'type'	=> 'textarea',
		'label'	=> 'Message - if form submitted',
		'val'	=> 'Please check your email to confirm your unsubscription.'
	),
	
	// OPTIN LINK TEXT
	array('slug'=> '', 'type' => 'title', 'label'=> 'Subscribe/UnSubscribe Email Link', 'val' => '' ),
	array(
		'slug'	=> 'ny_subscribe_optin_label',
		'type'	=> 'text',
		'label'	=> 'Text - For <strong>Subscribe confirmation</strong> link',
		'val'	=> 'Click here to confirm your subscription'
	),
	array(
		'slug'	=> 'ny_unsubscribe_optin_label',
		'type'	=> 'text',
		'label'	=> 'Text - For <strong>UnSubscribe confirmation</strong> link',
		'val'	=> 'Click here to un-subscribe'
	),		
	array(
		'slug'	=> 'ny_unsubscribe_label',
		'type'	=> 'text',
		'label'	=> 'Text - For UnSubscribe page while sending a <strong>post notification</strong>',
		'val'	=> 'UnSubscribe'
	),
	
	// MISCELLANEOUS
	array('slug'=> '', 'type' => 'title', 'label'=> 'Miscellaneous', 'val' => '' ),
	array(
		'slug'	=> 'ny_subscribe_logged_in_msg',
		'type'	=> 'text',
		'label'	=> 'Display a message if user is logged in',
		'val'	=> 'You are logged in!'
	),
	array(
		'slug'	=> 'ny_subscribe_field_placeholder',
		'type'	=> 'checkbox',
		'label'	=> 'Use HTML5 placeholder instead label text',
		'val'	=> 1
	),
	array(
		'slug'	=> 'ny_subscribe_ticked',
		'type'	=> 'checkbox',
		'label'	=> 'Keep <strong>Notify Subscribers</strong> checkbox default checked on add/edit post',
		'val'	=> ''
	),
	array(
		'slug'	=> 'ny_subscribe_theme_css',
		'type'	=> 'checkbox',
		'label'	=> 'Use active theme CSS for email templates',
		'val'	=> 1
	),
	array(
		'slug'	=> 'ny_subscribe_wipe',
		'type'	=> 'checkbox',
		'label'	=> 'Wipe out plugin changes while de-activating this plugin',
		'val'	=> 1
	),
	array(
		'slug'	=> 'ny_subscribe_css',
		'type'	=> 'textarea',
		'label'	=> 'CSS style for Error/Success messages',
		'val'	=> ".nysSuccess{
						clear:both;
						font-weight:400;
						padding:4px 12px;
						color:#090;
						border:1px solid #3C6;
						background: #C4FFAF;
					}
					.nysError{
						clear:both;
						font-weight:400;
						padding:4px 12px;
						color:#f00;
						border: 1px solid #FF8F8F;
						background: #FFDFDF;						
					}
					"
	),
	
	
);

// EMAIL TEMPLATES
$admin_fields_email_template = array(
	array(
		'slug'	=> 'nyEmailFrom',
		'type'	=> 'text',
		'label'	=> 'Email From',
		'val'	=> '{ADMIN_EMAIL}'
	),
	array('slug'=> '', 'type' => 'title', 'label'=> 'Template: Post/Page Notification', 'val' => '' ),
	array(
		'slug'	=> 'nyEmailSubject',
		'type'	=> 'text',
		'label'	=> 'Subject',
		'val'	=> '{SITE_NAME} - {POST_NAME}'
	),
	array(
		'slug'	=> 'nyEmailBody',
		'type'	=> 'textarea',
		'label'	=> 'Body',
		'val'	=> "{SITE_LINK} has published a new post - {PERMALINK} \n\n{POST_CONTENT} \n\nCategories: {POST_CATEGORIES}\n\nTags: {POST_TAGS}\n\n{UNSUBSCRIBE} if you do not want to receive post notifications from {SITE_LINK}\n\nThanks,\n\n{AUTHOR}"
	),
	
	// SUBSCRIBE CONFIRMATION
	array('slug'=> '', 'type' => 'title', 'label'=> 'Template: Subscribe Confirmation', 'val' => '' ),
	array(
		'slug'	=> 'nyEmailSubscribeSubject',
		'type'	=> 'text',
		'label'	=> 'Subject',
		'val'	=> '{SITE_NAME} - subscribe confirmation'
	),
	array(
		'slug'	=> 'nyEmailSubscribeBody',
		'type'	=> 'textarea',
		'label'	=> 'Body',
		'val'	=> "Hi {SUBSCRIBER_NAME} \n\nYou or someone else has requested to subscribe posts onto {SITE_NAME}. \n\nPlease confirm your subscription by clicking on following link. Ignore if you do not wish to subscribe."
	),
	
	// SUBSCRIBE CONFIRMATION REMINDER
	/*array('slug'=> '', 'type' => 'title', 'label'=> 'Template: Subscribe Confirmation Reminder', 'val' => '' ),
	array(
		'slug'	=> 'nyEmailSubscribeRemindSubject',
		'type'	=> 'text',
		'label'	=> 'Subject',
		'val'	=> 'Reminder - subscribe confirmation'
	),
	array(
		'slug'	=> 'nyEmailSubscribeRemindBody',
		'type'	=> 'textarea',
		'label'	=> 'Body',
		'val'	=> "Hi {SUBSCRIBER_NAME} \n\n"
	),*/

	// UNSUBSCRIBE CONFIRMATION
	array('slug'=> '', 'type' => 'title', 'label'=> 'Template: UnSubscribe Confirmation', 'val' => '' ),
	array(
		'slug'	=> 'nyEmailUnSubscribeSubject',
		'type'	=> 'text',
		'label'	=> 'Subject',
		'val'	=> '{SITE_NAME} - unsubscribe confirmation'
	),
	array(
		'slug'	=> 'nyEmailUnSubscribeBody',
		'type'	=> 'textarea',
		'label'	=> 'Body',
		'val'	=> "Hi {SUBSCRIBER_NAME} \n\nYou or someone else has requested to unsubscribe from {SITE_NAME}. \n\nPlease confirm your unsubscription by clicking on following link. Ignore if you do not wish to unsubscribe."
	)
);

// SPAM BLOCK FIELDS
$admin_fields_spam_block = array(
	array('slug'=> '', 'type' => 'title', 'label'=> 'Spam Block - IP/s', 'val' => '' ),
	array(
		'slug'	=> 'ny_subscribe_spam_ip_msg',
		'type'	=> 'text',
		'label'	=> 'Message for blocked IP address',
		'val'	=> 'Subscription has been blocked for this IP!'
	),
	array(
		'slug'	=> 'ny_subscribe_spam_ip_list_remote',
		'type'	=> 'textarea',
		'label'	=> 'List of blocked <a href="http://php.net/manual/en/reserved.variables.server.php" target="_blank">REMOTE IP</a> addresses<br/><small>(comma separated IP ie.<br/>157.250.45,125.54.56.55)</small>',
		'val'	=> ''
	),
	array(
		'slug'	=> 'ny_subscribe_spam_ip_list_server',
		'type'	=> 'textarea',
		'label'	=> 'List of blocked <a href="http://php.net/manual/en/reserved.variables.server.php" target="_blank">SERVER IP</a> addresses<br/><small>(comma separated IP ie.<br/>157.250.45,125.54.56.55)</small>',
		'val'	=> ''
	),
	
	array('slug'=> '', 'type' => 'title', 'label'=> 'Spam Block - Email/s', 'val' => '' ),
	array(
		'slug'	=> 'ny_subscribe_spam_email_msg',
		'type'	=> 'text',
		'label'	=> 'Message for blocked Email address',
		'val'	=> 'Subscription has been blocked for this Email!'
	),
	array(
		'slug'	=> 'ny_subscribe_spam_email_list',
		'type'	=> 'textarea',
		'label'	=> 'List of blocked Emails<br/><small>(comma separated Emails ie.<br/>abc@abc.com,xyz@xyz.com)</small>',
		'val'	=> ''
	),
	
	array('slug'=> '', 'type' => 'title', 'label'=> 'Spam Block - Domain/s', 'val' => '' ),
	array(
		'slug'	=> 'ny_subscribe_spam_domain_msg',
		'type'	=> 'text',
		'label'	=> 'Message for blocked Domain',
		'val'	=> 'Subscription has been blocked for this Domain!'
	),
	array(
		'slug'	=> 'ny_subscribe_spam_domain_list',
		'type'	=> 'textarea',
		'label'	=> 'List of blocked Domains<br/><small>(comma separated Domain ie.<br/>abc.com,xy.xyz.com)</small>',
		'val'	=> ''
	),
);

?>