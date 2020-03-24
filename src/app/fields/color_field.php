<?php
class ColorField extends RegexField{

	var $options;
	var $swatches;

	function __construct($options = array()){
		global $ATK14_GLOBAL;
		static $swatches_default;

		if(!isset($swatches_default)){
			$swatches_default = array();
			if($_colors = $ATK14_GLOBAL->getConfig("theme/colors")){
				$_colors = (array)$_colors;
				$_colors = array_unique($_colors);
				$_colors = array_values($_colors);
				$swatches_default = $_colors;
			}
		}
		
		$options += array(
			"theme" => "classic", // "classic", "monolith" or "nano"
			"opacity" => false,
			"swatches" => $swatches_default, // ["rgba(244, 67, 54, 1)","rgba(233, 30, 99, 0.95)","rgba(156, 39, 176, 0.9)"]
			"error_message" => _('Enter a valid color code (%formats%)')
		);

		$theme = $options["theme"];
		unset($options["theme"]);
		$this->opacity = $options["opacity"];
		unset($options["opacity"]);
		$this->swatches = (array)$options["swatches"];
		unset($options["swatches"]);

		$options["error_message"] = str_replace(
			"%formats%",
			$this->opacity ? _("in HEX, HEXA, RGB or RGBA format") : _("in HEX or RGB format"),
			$options["error_message"]
		);

		$hex = "[0-9A-F]";
		$num = '(0|[1-9]\d*)(|\.\d*)'; // 0 1 0.1 1.1 1.
		parent::__construct("/^(#$hex{6}|rgb\($num,$num,$num\)|#$hex{8}|rgba\($num,$num,$num,$num\))$/",$options);

		$this->widget->attrs["data-handler"] = "color-picker";

		$this->widget->attrs["data-theme"] = $theme;
		$this->widget->attrs["data-opacity"] = $this->opacity ? "true" : "false";
		$this->widget->attrs["data-swatches"] = json_encode(array_values($this->swatches));
	}

	function clean($value){
		$value = preg_replace('/\s/','',$value); // "rgba(233, 30, 99, 0.95)" -> "rgba(233,30,99,0.95)"
		if(preg_match('/^[0-9A-F]{6,8}$/i',$value)){
			$value = "#$value"; // "ff00ff" -> "#ff00ff"
		}
		if(preg_match('/^#/',$value)){
			$value = strtoupper($value); // "#ffaa00" -> "#FFAA00"
		}else{
			$value = strtolower($value); // "RGBA(233,30,99,0.95)" -> "rgba(233,30,99,0.95)"
		}
		if(preg_match('/^[0-9A-F]{6}$/',$value)){
			$value = "#$value";
		}

		list($err,$value) = parent::clean($value);

		if($value){
			if(!$this->opacity){
				$value = preg_replace('/^#([0-9A-F]{6})[0-9A-F]{2}$/','#\1',$value);
				$value = preg_replace('/^rgba\((.*?,.*?,.*?),.*?\)$/','rgb(\1)',$value);
			}
			$value = preg_replace('/,/',', ',$value); // "rgba(233,30,99,0.95)" -> "rgba(233, 30, 99, 0.95)"
		}
		return [$err,$value];
	}
}
