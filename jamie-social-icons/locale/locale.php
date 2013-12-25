  <?php 

  $lang = get_bloginfo('language');
  
if($lang == 'azb-AZB') {
	$lang = 'en_GB'; }

// Afrikaans FB af_ZA 
// Arabic FB ar_AR 
//Azerbaijani FB az_AZ
if($lang == 'bn-BD') {
	$lang = 'en_GB'; }
//Belarusian FB be_BY 
//Bulgarian FB bg_BG 
//Bengali FB bn_IN 
//Bosnian FB bs_BA
//Catalan FB ca_ES 
 //Czech FB cs_CZ 
 if($lang == 'es-CL') {
	$lang = 'es_ES';	
}
if($lang == 'zh-CN') {
	$lang = 'en_GB';	
}
// Danish FB da_DK 

// German FB de_DE 
// Greek FB el_GR 
// English (UK) FB en_GB 
// English (Pirate)FB en_PI 
// English (Upside Down) FB en_UD 
// English (US) FB en_US 
// Esperanto FB eo_EO 
// Spanish (Spain) FB es_ES 
if($lang == 'es-PE') {
	$lang = 'es_ES';	
}
// Spanish FB es_LA 
// Estonian FB et_EE 
// Basque FB eu_ES 
// Persian FB fa_IR 
// Leet Speak FB fb_LT 
// Finnish FB fi_FI 
// Faroese FB fo_FO 
// French (Canada) FB fr_CA 
// French (France) FB fr_FR 
// Frisian FB fy_NL 
// Irish FB ga_IE 
// Galician FB gl_ES 
// Hebrew FB he_IL 
// Hindi FB hi_IN 
// Croatian FB hr_HR 
// Hungarian FB hu_HU 
// Armenian FB hy_AM 
// Indonesian FB id_ID 
// Icelandic FB is_IS 
// Italian FB it_IT 
// Japanese FB ja_JP 
if($lang == 'ge-GE') {//Georgian FB ka_GE 
	$lang = 'ka_GE';	
} 
if($lang == 'ms-MY') {
	$lang = 'en_GB';	
}
if($lang == 'mg-MG') {
	$lang = 'en_GB';	
}
if($lang == 'ni-ID') {
	$lang = 'en_GB';	
}

// Khmer FB km_KH 
// Korean FB ko_KR 
// Kurdish FB ku_TR 
// Latin FB la_VA 
// Lithuanian FB lt_LT 
// Latvian FB lv_LV 
// Macedonian FB mk_MK 
// Malayalam FB ml_IN 
// Malay FB ms_MY 
// Norwegian (bokmal) FB nb_NO 
// Nepali FB ne_NP 
// Dutch FB nl_NL 
// Norwegian (nynorsk) FB nn_NO 
if($lang == 'fa-IR') {
	$lang = 'en_GB';	
}
// Punjabi FB pa_IN 
// Polish FB pl_PL 
// Pashto FB ps_AF 
// Portuguese (Brazil) FB pt_BR 
// Portuguese (Portugal) FB pt_PT 
// Romanian FB ro_RO 
// Russian FB ru_RU 
// Slovak FB sk_SK 
// Slovenian FB sl_SI 
// Albanian FB sq_AL 
// Serbian FB sr_RS 
if($lang == 'si-LK') {
	$lang = 'en_GB';	
}
// Swedish FB sv_SE 
// Swahili FB sw_KE 
// Tamil FB ta_IN 
// Telugu FB te_IN 
// Thai FB th_TH 
// Filipino FB tl_PH
// Turkish FB tr_TR 
 //Ukrainian FB uk_UA 
// Vietnamese FB vi_VN 
// Simplified Chinese (China) FB zh_CN 
// Traditional Chinese (Hong Kong) FB zh_HK
// Traditional Chinese (Taiwan) FB zh_TW
// Welsh FB cy_GB 

$locale = str_replace('-', '_', $lang);

?>