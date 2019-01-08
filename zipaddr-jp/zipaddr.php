<?php
if( !defined('ABSPATH') ) exit;
function zipaddr_jp_change($output, $opt=""){
	$flds= zipaddrjp_fld(); foreach($flds as $i => $key){$keys=zipaddr_SYS.$key; $$keys="";}
	$param= unserialize( get_option(zipaddr_DEFINE) ); // get定義情報
	foreach((array)$param as $key => $data){
		$da= ($data=="&nbsp;") ?  $data : htmlspecialchars($data,ENT_QUOTES,'UTF-8');
		$$key= $da;
	}
	if( strstr($output,'zip')==true || strstr($output,'postc')==true || $sys_drct!="" ){;} // keyword
	else  return $output;

	$jsfile= '<script type="text/javascript" charset="UTF-8"';
	$http="http"; $lcpath="";                     // http,  // local_path
if(isset($_SERVER['HTTPS'])) {$http=(empty($_SERVER['HTTPS'])||$_SERVER['HTTPS']=='off')? 'http':'https';}
//	$ssl= ($http=='https') ?  '1' : '';
//	$ssl= '1';
	$pth= isset($_SERVER['SERVER_NAME']) ?  $http.'://'.$_SERVER['SERVER_NAME'] : ""; // host用
	if( empty($sys_site) ) $sys_site= "4";        // パラメータの初期変換
	if( empty($sys_keta) ) $sys_keta= "7";
	if( $sys_site=="1"  || $opt!="" ) $sys_site= "4"; // welcartはzipaddrx.js
	if( $sys_keta < 5 ||  7 < $sys_keta ) $sys_keta= "7";
	if( $sys_pfon < 9 || 25 < $sys_pfon ) $sys_pfon= "";
	if( $sys_sfon < 9 || 25 < $sys_sfon ) $sys_sfon= "";
	 if( $sys_site == "3" ) $lcpath= $pth.'/js/zipaddr.css';
else if( $sys_site == "2" ){$lcpath= $pth.'/css/zipaddr.css';
		$wk= @file_get_contents($lcpath);
		$wk2= strstr($wk,"autozip");
		if( empty($wk) || empty($wk2) ) $lcpath= zipaddr2COM.'zipaddr.css'; // 定義がなければ補う
	 }                                   // 変換の判定開始
                                         $uls= zipaddr_COM. 'js/zipaddr7.js';
	 if( $sys_site == "3" )              $uls= $pth.       '/js/zipaddr.js';
//else if( $sys_site == "2" && $ssl=="1" ) $uls= zipaddr2sCOM.'js/zipaddr3.js';
else if( $sys_site == "2" )              $uls= zipaddr2COM. 'zipaddr3.js';
else if( $sys_site == "4" )              $uls= zipaddr_COM. 'js/zipaddrx.js';
	$pre=($sys_site=="4") ?  "D." : "ZP.";        // prefix
	$js = $jsfile.' src="'.$uls.'?v='.zipaddr_VERS.'"></script>';
	$js.= $jsfile.">function zipaddr_ownb(){" .$pre."wp='1';" .$pre."dli='".$sys_deli."';";
	$js.= $pre."min=".$sys_keta.";"  .$pre."uver='".get_bloginfo('version')."';";
	if( $opt != "" )    $js.= $pre."welcart='1';";
	if( $sys_tate!="" ) $js.= $pre."top=".    $sys_tate. ";";
	if( $sys_yoko!="" ) $js.= $pre."left=".   $sys_yoko. ";";
	if( $sys_pfon!="" ) $js.= $pre."pfon=".   $sys_pfon. ";";
	if( $sys_sfon!="" ) $js.= $pre."sfon=".   $sys_sfon. ";";
	if( $sys_focs!="" ) $js.= $pre."focus='". $sys_focs."';";
	if( $sys_syid!="" ) $js.= $pre."sysid='". $sys_syid."';";
	if( $sys_plce!="" ) $js.= $pre."holder='".$sys_plce."';";
	if( defined('zipaddr_IDENT') && zipaddr_IDENT == "3" ) $js.= $pre."usces='1';";
	$js.= '}</script>';
	if( $sys_site=="2" || $sys_site=="3" ) $js.= '<link rel="stylesheet" href="'.$lcpath.'" />'; // style
	if( $sys_parm != "" ){
		$sys_parm= str_replace("|", ",", $sys_parm);
		$js.= '<input type="hidden" name="zipaddr_param" id="zipaddr_param" value="'.$sys_parm.'">';
	}
		 if( !empty($opt) )       $ans= $output.$js;
	else if( !empty($sys_drct) ) {$ans= $output;  // 無条件挿入
		$urlh= isset($_SERVER['REQUEST_URI']) ?  $_SERVER['REQUEST_URI'] : "";
		$wk= explode(";", $sys_drct);
		foreach($wk as $ka => $da){ if(strstr($urlh,$da)==true){$ans=$output.$js; break;} }
	}
	else  $ans= str_ireplace("<form", $js."<form", $output);
	return $ans;
}
function zipaddr_jp_usces($formtag,$type,$data) {return zipaddr_jp_change($formtag,"1");}
function zipaddr_jp_welcart($script) {$keywd1="if(delivery_days[selected]";
$addon="
if(typeof Zip.welorder==='function'){
	var wk1= $('#delivery_country').val();
	var wk2= $('#delivery_pref').val();
	if( wk1!='' && wk2!='' ) {delivery_country=wk1; delivery_pref=wk2;}
}
";
	$wk0= strstr($script,$keywd1);
	if( !empty($wk0) ) {$script= str_replace($keywd1, $addon.$keywd1, $script);}
	return $script;
}
?>
