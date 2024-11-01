<?php
/*
Plugin Name: Smooci (WordPress on Mobiles)
Version: 1.2
Plugin URI: http://mariuscristiandonea.com/2010/01/21/smooci-wordpress-on-mobiles-wordpress-plugin/
Description: Smooci (WordPress on Mobiles) plugin can be used to display a diferent theme when your WordPress site is visited on mobile phones or devices.
			 The plugin detects the mobile device and displays the theme of your choice. If you encounter a mobile phone or device that doesn't display the selected theme, please leave a comment on plugin's page or mail a message at me@mariuscristiandonea.com.
Author: Marius-Cristian Donea
Author URI: http://www.smooci.com/referral?user=Marius

Change log:
	
	1.0 (2010-01-20) 
	
		* Initial release.
	
	1.1 (2010-01-23) 
	
		* Small coding bug fixed. New mobiles added.
	
	1.2 (2010-01-24) 
	
		* HTC mobiles added.	
		
Installation: Upload the files from the zip file to "wp-content/plugins/"  and activate the plugin in your admin panel. 

Licence:
	
	Copyright 2009 SMOOCI.com  (email : contact@smooci.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/	

	if (!class_exists("SMOOCIAdmin")) 
	{
		class SMOOCIAdmin 
		{
			var $adminOptionsName = "SMOOCIAdminOptions";
			
			//class constructor
			function SMOOCIAdmin() 
			{
			}
			
			//Returns an array of admin options
			function getAdminOptions() 
			{
				$SMOOCIAdminOptions = array('smooci_theme' => '');
				$SMOOCIOptions = get_option($this->adminOptionsName);
				if (!empty($SMOOCIOptions)) 
				{
					foreach ($SMOOCIOptions as $key => $option)
						$SMOOCIAdminOptions[$key] = $option;
				}				
				
				update_option($this->adminOptionsName, $SMOOCIAdminOptions);
				return $SMOOCIAdminOptions;
			}
						
			function init() 
			{
				$this->getAdminOptions();
			}
			
			function theTheme() 
			{
				$SMOOCIOptions = $this->getAdminOptions();
				return $SMOOCIOptions['smooci_theme'];
			}
							
			//Prints out the admin page
			function printAdminPage() 
			{
				$SMOOCIOptions = $this->getAdminOptions();
									
				if (isset($_POST['update_SMOOCIAdminSettings'])) 
				{
					if (isset($_POST['SMOOCITheme'])) 
						{$SMOOCIOptions['smooci_theme'] = $_POST['SMOOCITheme'];}
					
					update_option($this->adminOptionsName, $SMOOCIOptions);						
?>
					<div class="updated"><p><strong><?php _e("Theme saved.", "SMOOCIAdmin");?></strong></p></div> 
<?php
				} 
?>
	
    <div class=wrap>
		<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
            <h2>Smooci (WordPress on Mobiles) Options</h2>
            	<h3>Select the theme you want to display on mobile devices:</h3>
                
                <select name="SMOOCITheme" value="<?php _e($SMOOCIOptions['smooci_theme'], 'SMOOCIAdmin') ?>" style="width:300px;">
                	<option value="">- Select Theme -</option>
<?php
				$themes = get_themes();
				$theme_names = array_keys($themes);
				natcasesort($theme_names);
					
				foreach ($theme_names as $theme_name)
				{
					if ($theme_name == $ct->name)continue;					
?>
					<option value="<?php echo $themes[$theme_name]['Template']; ?>" <?php if ($themes[$theme_name]['Template'] == $SMOOCIOptions['smooci_theme']){echo 'selected="selected"';} ?>><?php echo $themes[$theme_name]['Title']; ?></option>
<?php
				}
?>	                
                </select>                   
           		<div class="submit">
            		<input type="submit" name="update_SMOOCIAdminSettings" value="<?php _e('Save Selected Theme', 'SMOOCIAdmin') ?>" />
                </div>
        </form>
	</div>

<?php 				
			}
		}

	}

	if (class_exists("SMOOCIAdmin")) 
		{$SMOOCI_pluginSeries = new SMOOCIAdmin();}
	
	//Initialize the admin panel
	if (!function_exists("SMOOCIAdmin_ap")) 
	{
		function SMOOCIAdmin_ap() 
		{
			global $SMOOCI_pluginSeries;
			
			if (!isset($SMOOCI_pluginSeries)) 
				{return;}
				
			if (function_exists('add_options_page')) 
				{add_options_page('Smooci (WordPress on Mobiles)', 'Smooci (WordPress on Mobiles)', 9, basename(__FILE__), array(&$SMOOCI_pluginSeries, 'printAdminPage'));}
		}	
	}
	
	//Actions and Filters	
	if (isset($SMOOCI_pluginSeries)) 
	{
		//Actions
		add_action('admin_menu', 'SMOOCIAdmin_ap');
	}
	
//**********************************************************************************************************************************************************
	
	
	define('SMOOCI_MOBILE_THEME', $SMOOCI_pluginSeries->theTheme());
		
	if (SMOOCI_verifyBrowser() == "mobile")
	{	
		add_filter('template', 'SMOOCI_theme');
		add_filter('option_template', 'SMOOCI_theme');
		add_filter('option_stylesheet', 'SMOOCI_theme');
	}	
	
	function SMOOCI_theme($theme)
	{
		return apply_filters('SMOOCI_theme', SMOOCI_MOBILE_THEME);	
	}
	
	function SMOOCI_verifyBrowser()
	{
		$useragent = $_SERVER['HTTP_USER_AGENT'];
		
		if (preg_match('/2.0 mmp|240x320|400x240|android|avantgo|blackberry|blackberry9530|blazer|cellphone|compal|danger|docomo|elaine|eudora|fennec|googlebot|hiptop|htc|htc_|iemobile|ip(hone|od)|iris|jb5|kindle|kyocera|lg\-tu915 obigo|lg\/u990|lge |lge vx|maemo|midp|minimo|mmp|mmef20|mobile|mot\-v|netfront|newt|nintendo wii|nitro|nokia|nokia5800|o2|opera mini|palm|palm( os)?|playstation portable|plucker|pocket|polaris|portalmmm|pre\/|proxinet|psp|s60|sharp\-tq\-gx10|shg\-i900|skyfire|small|smartphone|sonyericsson|symbian os|symbianos|symbian|treo|ts21i\-10|thunderhawk|up\.(browser|link)|vodafone|wap|webos|windows ce|winwap|windows ce; (iemobile|ppc)|wx310k|yahooseeker\/m1a1\-r2d2|xiino/i',$useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
		{return "mobile";}
		else
		{return "not mobile";}
	}
?>