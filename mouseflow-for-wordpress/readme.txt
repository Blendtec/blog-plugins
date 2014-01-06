=== Mouseflow for Wordpress 3.1 ===
Contributors: Mouseflow
Tags: mouseflow 
Requires at least: 2.0.0
Tested up to: 3.8.0
Stable tag: trunk

Use Mouseflow directly from your Wordpress dashboard. Easy installation and use.

== Description ==

With Mouseflow for Wordpress you can access everything Mouseflow has to offer - directly from your Wordpress dashboard! Learn more about your visitors by viewing recordings of whole user sessions including mouse movements, clicks, scroll events and key strokes. The plugin makes it quick and easy to install the Mouseflow-tracking code on your Wordpress-site.

== Installation ==

Follow these steps to use the plugin:

1. Upload the 'mouseflow-for-wordpress'-folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Paste your Mouseflow script code in the box under Settings | Mouseflow

== Frequently Asked Questions ==

= Where do I get the tracking code? =

You get the tracking code by signing up on Mouseflow.com. You can easily create an account for free by following this link: https://mouseflow.com/sign-up/

= The code is not working =

1. Make sure you've inserted the script code in the settings page.
2. Check that you have the wp_footer() function in the blog template.
3. Check your blog's html source (Page / View source) and search for "mouseflow". In case the script found at the end of the <body> section, but still not working, you're probably running the site from a different domain than the one you entered on your mouseflow account. Make sure that the domains are matching. 
4. Read more here: https://mouseflow.zendesk.com/entries/22097317-The-tracking-code-is-inserted-but-status-is-Not-installed-
5. Get in touch: http://mouseflow.com/support/