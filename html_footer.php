<?php
/*
Plugin Name: HTML Footer
Plugin URI: http://blendtec.com/blog
Description: Simple html section for footer of website
Version: 1.0
Author: Josie Young
Author URI: http://blendtec.com
*/

global $wp_version;

if ( !version_compare($wp_version, "3.0", ">="))
{
	die("You need at least version 3.0 of Wordpress to use the HTML footer plugin");
}

function html_footer()
{
	$footer_html = "<div class=\"content\"><div id=\"footer2\">
            <div id=\"footer_background\">
                <ul>
                    <li><span class=\"footer_heading\">Company</span>
                        <ul>
                            <li><a href=\"http://www.blendtec.com/company/about\">About Us</a></li>
                            <li><a href=\"http://www.blendtec.com/support\">Customer Support</a></li>
                            <li><a href=\"http://www.blendtec.com/affiliates\">Web Affiliate Program</a></li>
                            <li><a href=\"http://blendtecjobs.applicantpro.com/jobs/\">Employment</a></li>
                            <li><a href=\"http://www.blendtec.com/employment\">Become a Demonstrator</a></li>
                            <li><a href=\"http://www.blendtec.com/dealers\">Become a Dealer</a></li>
                            <li><a href=\"http://www.blendtec.com/company/return-policy\">Return Policy</a></li>
                            <li><a href=\"http://www.blendtec.com/company/privacy-policy\">Privacy Policy</a></li>
                        </ul>
                    </li>
                    <li><span class=\"footer_heading\">Our Sites</span>
                        <ul>
                            <li><a href=\"http://www.willitblend.com\" target=\"_new\">Will It Blend?</a></li>
                            <li><a href=\"http://www.blendtec.com/commercial\" target=\"_new\">Blendtec Commercial</a></li>
                            <li><a href=\"http://www.blendtec.com/blog\" target=\"_new\">Blog</a></li>
                        </ul>
                    </li>
                    <li><span class=\"footer_heading\">Contact</span>
                        <ul>
                            <li><span>800-BLENDTEC</span></li>
                            <li><span>801-222-0888</span></li>
                            <li><span>Fax: 801-802-8584</span></li>
                            <li><span>M-F 8:00am to 5:30pm Mountain Standard Time</span></li>
                            <li><a href=\"http://www.blendtec.com/contacts\">Contact Us</a></li>
                        </ul>
                    </li>
                    <li><span class=\"footer_heading\">Location</span>
                        <ul>
                            <li><span>Blendtec Corporate Offices<br />1206 South 1680 West<br />Orem, Utah 84058</span></li>
                        </ul>
                    </li>

                    <li><span class=\"footer_heading\">On the Web</span>
                        <ul>
                            <li><a href=\"http://www.twitter.com/blendtec\" target=\"_blank\">Follow us on Twitter</a></li>
                            <li><a href=\"http://www.facebook.com/BlendTec\" target=\"_blank\">Become a fan on Facebook</a></li>
                            <li><a href=\"http://www.youtube.com/user/blendteconsumer\" target=\"_blank\">YouTube – Recipes</a></li>
                            <li><a href=\"http://www.youtube.com/user/Blendtec\" target=\"_blank\">YouTube – Will it Blend?</a></li>
                            <li><a href=\"http://eepurl.com/cXWy2\" target=\"_blank\">E-Newsletter</a></li>
                               
                        </ul>
                      </li>
                </ul>               
                <div class=\"clear\"></div></div></div> 
			</div></div></div>
			<div class=\"copyright\">&#169; 2010 by Blendtec, a division of K-TEC, Inc.</div>";
	
	echo $footer_html;

		
}

add_action('pagelines_footer', 'html_footer');
//add_action('wp_footer', 'html_footer');


?>