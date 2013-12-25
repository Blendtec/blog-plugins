// JavaScript Document
(function(){
var twitterWidgets = document.createElement('script');
twitterWidgets.type = 'text/javascript';
twitterWidgets.async = true;
twitterWidgets.src = 'http://platform.twitter.com/widgets.js';
// Setup a callback to track once the script loads.
twitterWidgets.onload = _ga.trackTwitter;
document.getElementsByTagName('head')[0].appendChild(twitterWidgets);
})();

function LinkedInShare() {
_gaq.push(['_trackSocial', 'LinkedIn', 'Share']);
}
