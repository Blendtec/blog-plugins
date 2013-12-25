<?php
/*
Plugin Name: Strx Simple Sharing Sidebar Widget
Plugin URI: http://www.strx.it
Description: Simple Sharing Sidebar Widget
Version: 2.1
Author: Strx
Author URI: http://www.strx.it
License: GPL2
*/

require_once('strx-widget.php');
class StrxSimpleSharingSidebar_Widget extends Strx_Widget {
    function w_id(){ return 'strx-simple-sharing-sidebar-widget'; }
    function w_name(){ return 'Strx Simple Sharing Sidebar'; }

    /** Widget Default Options, abstract */
    function w_defaults(){
        return array(
            'title' => 'Share This %type',
            'sites' => 'gplus,facebook,twitter,digg',
            'hmargin' => '4',
            'customcss' => '',
            'iconsperrow' => '10',
            'rowsvmargin' => '10',
            'i18nsite' => 'Site',
            'i18npage' => 'Page',
            'i18npost' => 'Post',
        );
    }

    /** Return the dashboard admin form */
    function w_form($instance){

        $affiliates=array(
            //Elegant themes affiliate program http://www.elegantthemes.com/affiliates/
            '<a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=6321_0_1_7" target="_blank"><img border="0" src="http://www.elegantthemes.com/affiliates/banners/468x60.gif" width="468" height="60"></a>',
            '<a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=6321_0_1_7" target="_blank"><img border="0" src="http://www.elegantthemes.com/affiliates/banners/468x60.gif" width="468" height="60"></a>',
            //Envato refer program http://themeforest.net/wiki/referral/basics-referral/referral-program/
            //logos http://themeforest.net/wiki/referral/basics-referral/banners-and-logos/
            //Themeforest
            '<a href="http://themeforest.net?ref=straps" target="_blank"><img border="0" src="http://envato.s3.amazonaws.com/referrer_adverts/tf_468x60_v2.gif" width="468" height="60"></a>',
            '<a href="http://themeforest.net?ref=straps" target="_blank"><img border="0" src="http://envato.s3.amazonaws.com/referrer_adverts/tf_468x60_v1.gif" width="468" height="60"></a>',
            //Videohive
            '<a href="http://videohive.net?ref=straps" target="_blank"><img border="0" src="http://envato.s3.amazonaws.com/referrer_adverts/vh_468x60_v4.gif" width="468" height="60"></a>',
            //Graphicriver
            '<a href="http://graphicriver.net?ref=straps" target="_blank"><img border="0" src="http://envato.s3.amazonaws.com/referrer_adverts/gr_468x60_v1.gif" width="468" height="60"></a>',
            //Activeden
            '<a href="http://activeden.net?ref=straps" target="_blank"><img border="0" src="http://envato.s3.amazonaws.com/referrer_adverts/ad_468x60_v4.gif" width="468" height="60"></a>',
            //Audiojungle
            '<a href="http://audiojungle.net?ref=straps" target="_blank"><img border="0" src="http://envato.s3.amazonaws.com/referrer_adverts/aj_468x60_v3.gif" width="468" height="60"></a>',
            //3docean
            '<a href="http://3docean.net?ref=straps" target="_blank"><img border="0" src="http://envato.s3.amazonaws.com/referrer_adverts/3d_468x60_v3.gif" width="468" height="60"></a>',
            //Codecanyon
            '<a href="http://codecanyon.net?ref=straps" target="_blank"><img border="0" src="http://envato.s3.amazonaws.com/referrer_adverts/cc_468x60_v3.gif" width="468" height="60"></a>',
            //Tutsplus
            //'<a href="http://tutsplus.com?ref=straps" target="_blank"><img border="0" src="http://envato.s3.amazonaws.com/referrer_adverts/tutorials_468x60_v1.gif" width="468" height="60"></a>',
            //Woothemes
            '<a href="http://www.woothemes.com/amember/go.php?r=38627&i=b43" target="_blank"><img src="http://woothemes.com/ads/468x60b.jpg" border=0 alt="WooThemes - Quality Themes, Great Support" width=468 height=60></a>',
            '<a href="http://www.woothemes.com/amember/go.php?r=38627&i=b44" target="_blank"><img src="http://woothemes.com/ads/468x60c.jpg" border=0 alt="WooThemes - WordPress themes for everyone" width=468 height=60></a>',
            '<a href="http://www.woothemes.com/amember/go.php?r=38627&i=b43" target="_blank"><img src="http://woothemes.com/ads/468x60b.jpg" border=0 alt="WooThemes - Quality Themes, Great Support" width=468 height=60></a>',
            '<a href="http://www.woothemes.com/amember/go.php?r=38627&i=b44" target="_blank"><img src="http://woothemes.com/ads/468x60c.jpg" border=0 alt="WooThemes - WordPress themes for everyone" width=468 height=60></a>',
            //Mojothemes
            '<a href="http://www.mojo-themes.com/?r=straps" target="_blank"><img src="http://www.mojo-themes.com/wp-content/uploads/2010/05/MOJO_THEMES_468_60_banner.jpg" border=0 alt="Mojo Themes" width=468 height=60></a>',
            '<a href="http://www.mojo-themes.com/?r=straps" target="_blank"><img src="http://www.mojo-themes.com/wp-content/uploads/2010/05/mojo-468x60.jpg" border=0 alt="Mojo Themes" width=468 height=60></a>'
        );
        //shuffle($affiliates);


        $rv='';
        $rv.=   '<p>'.$this->w_form_input($instance, 'title', 'Title, if empty will not be shown; if contains %type, it will be replaced with current page type (Site, Page or Post)').'</p>';
        $rv.=   '<p>'.$this->w_form_input($instance, 'sites', 'Sites buttons to display, comma separated; the order corresponds to the buttons order (left to right)<br/>Supported Sites: '.
                    '<a target="_blank" href="http://plus.google.com/">gplus</a>, '.
                    '<a target="_blank" href="http://www.digg.com">digg</a>, '.
                    '<a target="_blank" href="http://www.twitter.com">twitter</a>, '.
                    '<a target="_blank" href="http://www.facebook.com">facebook</a>, '.
                    '<a target="_blank" href="http://www.linkedin.com">linkedin</a>, '.
                    '<a target="_blank" href="http://www.stumbleupon.com">stumble</a>, '.
                    '<a target="_blank" href="http://www.reddit.com">reddit</a>, '.
                    '<a target="_blank" href="http://www.fbshare.me">fbshare</a>, '.
                    '<a target="_blank" href="http://www.google.com/buzz">buzz</a>, '.
                    '<a target="_blank" href="http://tweetmeme.com/about/retweet_button">retweet</a>, '.
                    '<a target="_blank" href="http://www.delicious.com">delicious</a>, '.
                    '<a target="_blank" href="http://www.thewebblend.com">blend</a>, '.
                    '<a target="_blank" href="http://www.designpoke.com">poke</a>, '.
                    '<a target="_blank" href="http://www.designbump.com">bump</a>'
                ).'</p>';

        $rv.='<p><div class="strx-simple-sharing-sidebar-affiliates" style="height:60px; margin: 0 auto; text-align:center;">'.$affiliates[array_rand($affiliates)].'</div></p>';

        $rv.=   '<p>'.$this->w_form_input($instance, 'hmargin', 'Horizontal space between every button, in pixel (default 4)').'</p>';
        $rv.=   '<p>'.$this->w_form_input($instance, 'iconsperrow', 'Number of Icons per row (default 10)').'</p>';
        $rv.=   '<p>'.$this->w_form_input($instance, 'rowsvmargin', 'Vertical margin between rows in pixels (default 10)').'</p>';
        $rv.=   '<p>'.$this->w_form_input($instance, 'i18nsite', 'Translation of the word <b>Site</b> in your language<br>'.
                    '<b>Title hack</b>: you cal write only <b>%type</b> on the title and insert here the complete phrase '.
                    'like <b>Share My Blog</b>, or <b>Share myblog.com</b>'
                ).'</p>';
        $rv.=   '<p>'.$this->w_form_input($instance, 'i18npage', 'Translation of the word <b>Page</b> in your language. <b>Title hack</b> is valid here too.').'</p>';
        $rv.=   '<p>'.$this->w_form_input($instance, 'i18npost', 'Translation of the word <b>Post</b> in your language. <b>Title hack</b> is valid here too.').'</p>';

        $rv.=   '<p>'.$this->w_form_textarea($instance, 'customcss',
                    'Custom CSS: You can make custom css rules for your widget that come with predefined classes:<br>
                    <strong>strx-simple-sharing-sidebar-buttons</strong>, widget wrapper<br/>
                    <strong>strx-simple-sharing-sidebar-button</strong>, applied to every button<br/>
                    <strong>strx-simple-sharing-sidebar-TYPE-button</strong>, applied to every button with TYPE specified (ie: strx-simple-sharing-sidebar-twitter-button)<br/>
                    Usage example:<br/>
                    .strx-simple-sharing-sidebar-twitter-button { border: 1px solid #555; }
                    ').'</p>';

        $rv.='<p><div class="strx-simple-sharing-sidebar-affiliates" style="height:60px; margin: 0 auto; text-align:center;">'.$affiliates[array_rand($affiliates)].'</div></p>';

        $rv.=   '<p><label><b>If you like this plugin</b> help me spread and improve it. How? Simple: '.
                    '<a target="_blank" href="http://wordpress.org/extend/plugins/strx-simple-sharing-sidebar-widget/">rate it with 5 stars and say it works</a>, '.
                    'subscribe to my feed by <a target="_blank" href="http://feedburner.google.com/fb/a/mailverify?uri=StrxBlog">email</a> or <a href="http://feeds.feedburner.com/StrxBlog" target="blank">any other client</a>, '.
                    '<a target="_blank" href="http://twitter.com/fstraps">follow me on twitter</a>, '.
                    '<a target="_blank" href="http://www.strx.it/donate">make a donation</a>'.
                '</label></p>';


        return $rv;
    }

	/** Title %type substitution */
	function w_title($instance){
        extract($instance);

		if (!empty($title)){
			if (strpos($title, '%type')>=0){
                $d=$this->w_defaults();
                $i18nsite=isset($i18nsite) && !empty($i18nsite) ? $i18nsite : $d['i18nsite'];
                $i18npost=isset($i18npost) && !empty($i18npost) ? $i18npost : $d['i18npost'];
                $i18npage=isset($i18npage) && !empty($i18npage) ? $i18npage : $d['i18npage'];

				$type=(is_home() || is_front_page()?$i18nsite:(is_single()?$i18npost:$i18npage));
				$title=str_replace('%type', $type, $title);
			}
		}
		return $title;
	}

    /** Return the real widget content */
    function w_content($instance){
        extract($instance);
        $d=$this->w_defaults();

        $rv=$this->buttonStyles($instance);
        $sites=explode(",", $sites);
        $nsites=count($sites);
        $iconsadded=0;

        $iconsperrow=(int)(isset($iconsperrow) && !empty($iconsperrow)?$iconsperrow:$d['iconsperrow']);
        $rowsvmargin=isset($rowsvmargin) && !empty($rowsvmargin)?$rowsvmargin:$d['rowsvmargin'];

        for ($i=0; $i<$nsites; $i++){
			$site=strtolower(trim($sites[$i]));
			if (method_exists($this, $site.'Button')){
                //Don't insert margin for last button
                $firsticon=$iconsadded===0;
                $lasticon = ($i+1)==$nsites;
                $lastrowicon=$lasticon || ($iconsadded>0 && ( ($iconsadded+1) % $iconsperrow)===0);
                $firstnewrowicon=$iconsadded>0 && ($iconsadded % $iconsperrow)===0;

                $st=''; //Button Styles

                //Every row has its own div
                if ($firstnewrowicon){
                    $rv.='</div>';
                    //New row, add an empty separator div
                    $rv.='<div class="strx-simple-sharing-sidebar-rows-separator" style="height:'.$rowsvmargin.'px;"></div>';
                    //Force the next icon to go to down
                    $st.='clear: both; ';
                }
                if ($firsticon || $firstnewrowicon){
                   $rv.='<div class="strx-simple-sharing-sidebar-buttons">';
                }

                if ( !($lasticon || $lastrowicon) ){
                    $st.='margin-right:'.$hmargin.'px; ';
                }

				$rv.='<div style="'.$st.'" class="strx-simple-sharing-sidebar-button strx-simple-sharing-sidebar-'.$site.'-button">'.
						$this->{$site.'Button'}().
					'</div>';

                //echo "<!-- site=$site, i=$i, nsites=$nsites, i+1==nsites=".(($i+1)==$nsites)." iconsadded=$iconsadded, style=$st, iconsperrow=$iconsperrow, lasticon=$lasticon, lastrowicon=$lastrowicon, firstnewrowicon=$firstnewrowicon -->";

                $iconsadded++;
			}
		}
		$rv.='</div>';

        return $rv;
    }

	function buttonStyles($instance){
		$rv='';
		$rv.='<style type="text/css">';
		$rv.='.strx-simple-sharing-sidebar-buttons { height:72px; } ';
		$rv.='.strx-simple-sharing-sidebar-rows-separator { margin:0;paggin:0;border:0;width:1px;float:left;clear:both !important; } ';
		$rv.='.strx-simple-sharing-sidebar-newrow { clear:both !important; } ';
		$rv.='.strx-simple-sharing-sidebar-button { clear:none !important; float:left; width:56px; vertical-align:middle; overflow:hidden; margin-bottom:4px; } ';
		$rv.='.strx-simple-sharing-sidebar-button iframe { border:0; overflow:hidden; } ';
		$rv.='.strx-simple-sharing-sidebar-blend-button { margin-top:2px; } ';
		$rv.='.strx-simple-sharing-sidebar-poke-button { width:60px; margin-top:-1px; } ';
		$rv.='.strx-simple-sharing-sidebar-bump-button { width:72px; margin-top:-3px; } ';
		$rv.='.strx-simple-sharing-sidebar-delicious-button { margin-top:2px; } ';
		$rv.='.strx-simple-sharing-sidebar-twitter-button { margin-top:2px; } ';
		$rv.='.strx-simple-sharing-sidebar-retweet-button { margin-top:2px; } ';
		$rv.='.strx-simple-sharing-sidebar-stumble-button { margin-top:3px; } ';
		$rv.='.strx-simple-sharing-sidebar-facebook-button { margin-top:2px; } ';
		$rv.='.strx-simple-sharing-sidebar-gplus-button { margin-top:2px; } ';
		$rv.='.strx-simple-sharing-sidebar-linkedin-button { margin-top:2px; } ';
        $rv.=$instance['customcss'];
		$rv.='</style>';
		return $rv;
	}

	function diggButton(){
		//http://about.digg.com/downloads/button/smart
		return '<script type="text/javascript">(function() {var s = document.createElement(\'SCRIPT\'), s1 = document.getElementsByTagName(\'SCRIPT\')[0]; s.type = \'text/javascript\'; s.async = true; s.src = \'http://widgets.digg.com/buttons.js\'; s1.parentNode.insertBefore(s, s1); })();</script><!-- Medium Button --><a class="DiggThisButton DiggMedium"></a>';
	}
	function twitterButton(){
		//http://twitter.com/about/resources/tweetbutton
		return '<a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';
	}
	function facebookButton(){
		//http://developers.facebook.com/docs/reference/plugins/like
		return '<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like layout="box_count" width="50"></fb:like>';
	}
	function buzzButton(){
		//http://www.google.com/buzz/api/admin/configPostWidget
		return '<a title="Post to Google Buzz" class="google-buzz-button" href="http://www.google.com/buzz/post" data-button-style="normal-count"></a>
<script type="text/javascript" src="http://www.google.com/buzz/api/button.js"></script>';
	}
	function stumbleButton(){
		//http://www.stumbleupon.com/badges/
		return '<script src="http://www.stumbleupon.com/hostedbadge.php?s=5"></script>';
	}
	function redditButton(){
		//http://it.reddit.com/buttons/
		return '<script type="text/javascript" src="http://it.reddit.com/static/button/button2.js"></script>';
	}
	function fbshareButton(){
		//http://www.fbshare.me
		return '<script src="http://widgets.fbshare.me/files/fbshare.js"></script>';
	}
    function blendButton(){
        //http://thewebblend.com/tools/vote
        return '<script src="http://thewebblend.com/sites/all/modules/drigg_external/js/button.js" type="text/javascript"></script>';
    }
    function pokeButton(){
        //http://www.designpoke.com/static/tools
        return '<script type="text/javascript" src="http://www.designpoke.com/evb/button.php"></script>';
    }
    function bumpButton(){
        //http://designbump.com/content/evb
        return '<script type="text/javascript">url_site=location.href;</script><script src="http://designbump.com/sites/all/modules/drigg_external/js/button.js" type="text/javascript"></script>';
    }
    function retweetButton(){
        //http://help.tweetmeme.com/2009/04/06/tweetmeme-button/
        return '<script type="text/javascript" src="http://tweetmeme.com/i/scripts/button.js"></script>';
    }
    function deliciousButton(){
        //http://www.moretechtips.net/2010/07/quite-delicious-button-jquery-plugin.html
        return '<script type="text/javascript" src="http://delicious-button.googlecode.com/files/jquery.delicious-button-1.0.min.js"></script><a class="delicious-button" href="http://delicious.com/save">Save on Delicious</a>';
    }
    function linkedinButton(){
        //http://www.linkedin.com/publishers
        return '<script type="text/javascript" src="http://platform.linkedin.com/in.js"></script><script type="in/share" data-counter="top"></script>';
    }
    function gplusButton(){
        //http://www.google.com/webmasters/+1/button/
        return '<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script><g:plusone size="tall"></g:plusone>';
    }

	function currentUrl(){
		$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
		$host     = $_SERVER['HTTP_HOST'];
		$script   = $_SERVER['SCRIPT_NAME'];
		$params   = $_SERVER['QUERY_STRING'];
		$currentUrl = $protocol . '://' . $host . $script;// . '?' . $params;
      	return $currentUrl;
	}

}

StrxSimpleSharingSidebar_Widget::w_init('StrxSimpleSharingSidebar_Widget');
