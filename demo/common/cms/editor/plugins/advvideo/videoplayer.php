<?php
sleep(3);
switch($_GET['media_type'])
{
	case "flasvideo":
	$path_parts = pathinfo($_SERVER['REQUEST_URI']);
?>
<script type="text/javascript">
	var flashvars = {};
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	// CUSTUMIZABLE PARAMETERS ///////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	
	// video width
	var stageW = <?php echo $_GET['width']; ?>;
	
	// video height		NOTE: you should include the control bar height
	var stageH = <?php echo $_GET['height']; ?>;
	
	///////////////////////////////////////////////////////////////////////
	// PATHS //////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////
	// image path
	///flashvars.imagePath			= "images/logo.png";

	// video path
	flashvars.videoPath 		= "<?php echo $_GET['href']; ?>";

	// video title
	flashvars.title 			= "Video Title goes here";

	// video description
	flashvars.description 		= "Some description over this video! \nAlmost all control buttons can easily be turn on or off.";
				
	///////////////////////////////////////////////////////////////////////
	// VIEW CONTROLS ////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////

	// view information button 		 NOTE: this will display the title and description of the video
	flashvars.viewInfoButton 		=	"false";

	// view fullscreen button		( true / false )
	flashvars.viewFullscreenButton 	=	"true";

	// view scale button			( true / false )
	flashvars.viewScaleButton 		=	"true";

	// view volume controls			( true / false )
	flashvars.viewVolumeControls 	=	"true";

	// view video time				( true / false )
	flashvars.viewTime				=	"true";

	// view big middle button		( true / false )
	flashvars.viewBigPlayButton 	=	"true";
	
	// view right click menu		( true / false )
	flashvars.viewRightClickMenu 	=	"true";
	
	////////////////////////////////////////////////////////////////////////
	// MOUSE FUNCTIONS //////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////

	// mouse hide							( true / false )
	flashvars.mouseHide				=	"true";

	// mouse hide after # (seconds)		NOTE : Must be a hole number !
	flashvars.mouseHideTime			=	"3";

	// double click for toggle size view	( true / false )
	flashvars.doubleClick			=	"true";

	// click the video for play/pause		( true / false )
	flashvars.oneClick				=	"true";
	
	////////////////////////////////////////////////////////////////////////
	// KEYBOARD FUNCTIONS ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////

	// play/pause on SPACE key 		( true / false )
	flashvars.spaceKey				=	"true";
	
	////////////////////////////////////////////////////////////////////////
	// VIDEO FUNCTIONS ///////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////

	// video loop				( true / false )
	flashvars.videoLoop			=	"false";

	// video auto play			( true / false )
	flashvars.videoAutoPlay		=	"false";
	
	// video buffer time		( seconds )
	flashvars.videoBufferTime	=	"0.1";
	
	// timeline interval	
	flashvars.tlInterval		=	"100000";

	// sound volume at start 		NOTE :	1=Max	0=Min
	flashvars.soundVolume		=	"0.8";

	// size the video starts at
	// can be set to 1, 2 and 3
	// 1 for narmol size view
	// 2 for aspect view 
	// 3 for full size view
	flashvars.fullSizeView		=	"1";
	
	////////////////////////////////////////////////////////////////////////
	// VISUAL APPEARANCE  /////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////
	
	// spacing between the controls
	flashvars.spacing 			=	"10";
	
	// control bar height		( height )
	flashvars.controlHeight		=	"25";
	
	// vulume scrub lenght 		( lenght )
	flashvars.volumeLengthW		= 	"50";
	
	// controls background		( colors / alphas )
	flashvars.color1 			= 	"0x2e2e2e";
	flashvars.color2 			= 	"0x000000";
	flashvars.alpha1 			= 	"1";
	flashvars.alpha2 			= 	"1";
	
	// controls border			( color / alpha )
	flashvars.borderColor 		= 	"0x555555";
	flashvars.borderAlpha		= 	"1";
	
	// time view ////////////////////////////////////
	// time view background		( colors / alphas )
	flashvars.timeColor1		= 	"0x333333";
	flashvars.timeColor2		= 	"0x111111";
	flashvars.timeAlpha1		= 	"1";
	flashvars.timeAlpha2		= 	"1";

	// time view text color		( color )
	flashvars.timeTextColor1	= 	"0xffffff";
	flashvars.timeTextColor2	= 	"0x888888";

	
	// scrubber /////////////////////////////////////////////////////
	// scrubber height			( height )
	flashvars.scrubberHeight 	=	"3";

	// scrubber background 		( color / alpha )
	flashvars.scrubberColor1	= 	"0x333333";
	flashvars.scrubberAlpha1	= 	"1";

	// scrubber					( color / alpha )
	flashvars.scrubberColor2	= 	"0x47d2ff";
	flashvars.scrubberAlpha2	= 	"1";

	// scrubber glow filter		( color / alpha )
	flashvars.filterColor		= 	"0x0066ff";
	flashvars.filterAlpha		= 	"1";
	
	// control buttons	///////////////////////////////////////////////
	// control buttons color	( color )
	//flashvars.buttonColor		= 	"0x000000";
	
	// info view /////////////////////////////////////////////////////
	// title color					( color )
	flashvars.titleColor			=	"0x47d2ff";

	// description color			( color )
	flashvars.descriptionColor		=	"0xD3D3D3";

	// info background				( color / alpha )
	flashvars.infoBackgroundColor	=	"0x000000";
	flashvars.infoBackgroundAlpha	=	"0.5";
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	
	var params = {};
	params.scale = "noscale";
	params.allowfullscreen = "true";
	params.salign = "tl";
	params.bgcolor = "000000";
	
	var attributes = {};
	
	swfobject.embedSWF("cms/editor/plugins/advvideo/videoplayer.swf", "flashDiv", stageW, stageH, "9.0.0", false, flashvars, params, attributes);
</script>
<div id="flashDiv">
	<a href="http://www.adobe.com/go/getflashplayer">
		<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
	</a>
</div>
<?php
		break;
	case "flashanimaion":
?>
<table border='0' cellpadding='0'>
<tr><td>
<OBJECT classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' width="<?php echo $_GET['width']; ?>" height="<?php echo $_GET['height']; ?>">
<param name='movie' value="<?php echo $_GET['href']; ?>">
<param name='quality' value="high">
<param name='bgcolor' value='#FFFFFF'>
<param name='loop' value="true">
<EMBED src="<?php echo $_GET['href']; ?>" quality='high' bgcolor='#FFFFFF' width="<?php echo $_GET['width']; ?>" height="<?php echo $_GET['height']; ?>" loop="true" type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash'></EMBED>
</OBJECT>
</td></tr>
</table>
<?php
		break;
	case "realmedia":
?>
<table border='0' cellpadding='0'>
<tr><td>
<OBJECT id='rvocx' classid='clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA' width="<?php echo $_GET['width']; ?>" height="<?php echo $_GET['height']; ?>">
<param name='src' value="<?php echo $_GET['href']; ?>">
<param name='autostart' value="true">
<param name='controls' value='imagewindow'>
<param name='console' value='video'>
<param name='loop' value="true">
<EMBED src="<?php echo $_GET['href']; ?>" width="<?php echo $_GET['width']; ?>" height="<?php echo $_GET['height']; ?>" loop="true" type='audio/x-pn-realaudio-plugin' controls='imagewindow' console='video' autostart="true"></EMBED>
</OBJECT>
</td></tr>
<tr><td>
<OBJECT id='rvocx' classid='clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA' width="<?php echo $_GET['width']; ?>" height='30'>
<param name='src' value="<?php echo $_GET['href']; ?>">
<param name='autostart' value="true">
<param name='controls' value='ControlPanel'>
<param name='console' value='video'>
<EMBED src="<?php echo $_GET['href']; ?>" width="<?php echo $_GET['width']; ?>" height='30' controls='ControlPanel' type='audio/x-pn-realaudio-plugin' console='video' autostart="true"></EMBED>
</OBJECT>
</td></tr>
</table>
<?php
		break;
	case "quicktime":
?>
<table border='0' cellpadding='0'>
<tr><td>
<OBJECT classid='clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B' width="<?php echo $_GET['width']; ?>" height="<?php echo $_GET['height']; ?>" codebase='http://www.apple.com/qtactivex/qtplugin.cab'>
<param name='src' value="<?php echo $_GET['href']; ?>">
<param name='autoplay' value="true">
<param name='controller' value="true">
<param name='loop' value="true">
<EMBED src="<?php echo $_GET['href']; ?>" width="<?php echo $_GET['width']; ?>" height="<?php echo $_GET['height']; ?>" autoplay="true" controller="true" loop="true" pluginspage='http://www.apple.com/quicktime/download/'> </EMBED>
</OBJECT>
</td></tr>
</table>
<?php
		break;
	case "windowsmedia":
?>
<table border='0' cellpadding='0' >
<tr><td>
<OBJECT id='mediaPlayer' width="<?php echo $_GET['width']; ?>" height="<?php echo $_GET['height']; ?>" classid='CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95' codebase='http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701' standby='Loading Microsoft Windows Media Player components...' type='application/x-oleobject'>
<param name='fileName' value="<?php echo $_GET['href']; ?>">
<param name='animationatStart' value='true'>
<param name='transparentatStart' value='true'>
<param name='autoStart' value="true">
<param name='showControls' value="true">
<param name='loop' value="true">
<EMBED type='application/x-mplayer2' pluginspage='http://microsoft.com/windows/mediaplayer/en/download/' id='mediaPlayer' name='mediaPlayer' displaysize='4' autosize='-1' bgcolor='darkblue' showcontrols="true" showtracker='-1' showdisplay='0' showstatusbar='-1' videoborder3d='-1' width="<?php echo $_GET['width']; ?>" height="<?php echo $_GET['height']; ?>" src="<?php echo $_GET['href']; ?>" autostart="true" designtimesp='5311' loop="true"></EMBED>
</OBJECT>
</td></tr>
</table>
 <?php
break;
}
?>