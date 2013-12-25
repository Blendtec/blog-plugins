<?php
/*
Plugin Name:  Pinterest Pin It Button
Plugin URI:   http://pleer.co.uk/wordpress/plugins/pinterest-pin-it-button/
Description:  A simple plugin that lets you output a Pinterest "Pin It" button via shortcode
Version:      1.0
Author:       Alex Moss
Author URI:   http://alex-moss.co.uk/
Contributors: pleer, ShaneJones

Copyright (C) 2010-2010, Alex Moss
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
Neither the name of Alex Moss or pleer nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

*/

function pinterestJS(){
echo  <<<EOT
<script type="text/javascript">
(function() {
    window.PinIt = window.PinIt || { loaded:false };
    if (window.PinIt.loaded) return;
    window.PinIt.loaded = true;
    function async_load(){
        var s = document.createElement("script");
        s.type = "text/javascript";
        s.async = true;
        if (window.location.protocol == "https:")
            s.src = "https://assets.pinterest.com/js/pinit.js";
        else
            s.src = "http://assets.pinterest.com/js/pinit.js";
        var x = document.getElementsByTagName("script")[0];
        x.parentNode.insertBefore(s, x);
    }
    if (window.attachEvent)
        window.attachEvent("onload", async_load);
    else
        window.addEventListener("load", async_load, false);
})();
</script>
<script>
function exec_pinmarklet() {
    var e=document.createElement('script');
    e.setAttribute('type','text/javascript');
    e.setAttribute('charset','UTF-8');
    e.setAttribute('src','http://assets.pinterest.com/js/pinmarklet.js?r=' + Math.random()*99999999);
    document.body.appendChild(e);
}
</script>
EOT;
}
add_action('wp_footer', 'pinterestJS', 100);


function pinterestCSS(){
echo "<link rel=\"stylesheet\" href=\"".plugins_url( 'pinterest/pin-it.css' )."\" type=\"text/css\" />";
}
add_action('wp_head', 'pinterestCSS', 1);


function pinitbutton($content){
if (is_single()) {
    $content .= '
<div class="pinterest-btn">
	<a href="javascript:exec_pinmarklet();" class="pin-it-btn" title="Pin It on Pinterest"></a>
</div>';
}
return $content;
}
add_filter('the_content', 'pinitbutton');

function pinitshortcode(){
    $pinitbutton = '
<div class="pinterest-btn">
	<a href="javascript:exec_pinmarklet();" class="pin-it-btn" title="Pin It on Pinterest"></a>
</div>';
return $pinitbutton;
}

add_filter('widget_text', 'do_shortcode');
add_shortcode('pinterest', 'pinitshortcode');
?>