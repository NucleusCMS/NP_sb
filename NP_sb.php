<?php

class NP_sb extends NucleusPlugin
{
	function getName()              {return 'NP_sb';}
	function getAuthor()            {return 'yamamoto';}
	function getURL()               {return 'http://kyms.ne.jp/';}
	function getVersion()           {return '0.1';}
	function getMinNucleusVersion() {return 330;}
	function supportsFeature($w)    {return ($w == 'SqlTablePrefix') ? 1 : 0;}
	function getEventList()         {return array('PreItem');}
	function getDescription()       {return 'Facebook, Twitter, mixi';}

	function install()
	{
	/*
		// allowed image maximum width
		$this->createOption('facebook_code',
			'_NP_IMAGELIMITSIZE02', 'text', '550', 'datatype=numerical');

		$this->createOption('twitter_code',
			'_NP_IMAGELIMITSIZE05', 'text', '', 'datatype=numerical');

		// jpeg image quality
		$this->createOption('gree_code',
			'_NP_IMAGELIMITSIZE03', 'text', '85', 'datatype=numerical');

		// allowed image maximum width BLOG special setting
		$this->createBlogOption('status',
			_NP_IMAGELIMITSIZE_STATUS, 'yesno', 'yes');

		// allowed image maximum width BLOG special setting
		$this->createBlogOption('blog_maxwidth',
			_NP_IMAGELIMITSIZE02 . _NP_IMAGELIMITSIZE04, 'text', '', 'datatype=numerical');

		// allowed image maximum width BLOG special setting
		$this->createBlogOption('blog_maxheight',
			_NP_IMAGELIMITSIZE05 . _NP_IMAGELIMITSIZE04, 'text', '', 'datatype=numerical');
		*/
	}

	function init()
	{
		// include language file for this plugin
		$language = preg_replace( '@\\|/@', '', getLanguageName());
		$langDir  = $this->getDirectory() . 'language/';
		if (! @include_once($langDir . $language . '.php')) {include_once($langDir . 'english.php');}
	}
	
	function doSkinVar()
	{
		global $CONF;
		$args = func_get_args();
		array_shift($args);
		$params = $this->_build_params($args);
		extract($params, EXTR_SKIP);
		
		$pligin_dir = $CONF['PluginURL'] . 'sb/';
		
		$output = '';
		if(empty($js)) $js = 'both';
		if($js=='jquery' || $js='both')
		{
			$output .= '<script src="' . $pligin_dir . 'js/jquery-1.4.4.min.js" type="text/javascript"></script>' . "\n";
		}
		if($js=='sb'     || $js='both')
		{
			$output .= '<script src="' . $pligin_dir . 'js/jquery.socialbutton.js" type="text/javascript"></script>' . "\n";
		}
		
		echo $output;
	}
	
	function doTemplateVar()
	{
		$args = func_get_args();
		$item = array_shift($args);
		$item_url = createItemLink($item->itemid,$this->linkparams);
		$output = $this->_build_button($args);
		echo $output;
	}
	
	function _build_params($args)
	{
		$param_str = join(',', $args);
		$params_array = explode(';', $param_str);
		foreach($params_array as $t)
		{
			list($k,$v) = explode(':', $t);
			$k = trim($k);
			$v = trim($v);
			$param[$k] = $v;
		}
		return $param;
	}
	
	function _build_button($args)
	{
		$service = $args[0];
		$params  = '';
		if($args[1])
		{
			array_shift($args);
			$params = ',{' . join(',',$args) . '}';
		}
		$scr  = '<script>' . "\n";
		$scr .= '$(function() {' . "\n";
		$scr .= "    $('." . $service . "').socialbutton('" . $service . "'" . $params . ");" . "\n";
		$scr .= '});' . "\n";
		$scr .= "</script>" . "\n";
		$scr .= '<div class="' . $service . '" style="float:left;"></div>' . "\n";
		return $scr;
	}

}