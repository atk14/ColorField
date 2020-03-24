<?php
class TcColorField extends TcBase {

	function test(){
		$this->field = new ColorField(array(
			"opacity" => true,
		));
		$this->_valid_colors(array(
			" #ff11aa " => "#FF11AA",
			"abcdef" => "#ABCDEF",
			"#1122aaff" => "#1122AAFF", // HEXA

			" RGB ( 0 , 0 , 0 ) " => "rgb(0, 0, 0)",
			" rgba( 0, 200.55, 111.22, 1.0 )" => "rgba(0, 200.55, 111.22, 1.0)",
		));
		$this->_invalid_colors(array(
			"#fff",
			"#1234567",
		));

		$this->field = new ColorField(array(
			"opacity" => false,
		));
		$this->_valid_colors(array(
			" #ff11aa " => "#FF11AA",
			"abcdef" => "#ABCDEF",
			"#1122aaff" => "#1122AA", // auto conversion HEXA -> HEX

			" RGB ( 0 , 0 , 0 ) " => "rgb(0, 0, 0)",
			" rgba( 0, 200.55, 111.22, 1.0 )" => "rgb(0, 200.55, 111.22)", // auto conversion rgba -> rgb
		));

		// Error messages

		$this->field = new ColorField(array(
			"opacity" => true,
		));
		$err = $this->assertInvalid("red");
		$this->assertEquals("Enter a valid color code (in HEX, HEXA, RGB or RGBA format)",$err);

		$this->field = new ColorField(array(
			"opacity" => false,
		));
		$err = $this->assertInvalid("red");
		$this->assertEquals("Enter a valid color code (in HEX or RGB format)",$err);
	}

	function _valid_colors($ary){
		foreach($ary as $input => $result){
			$value = $this->assertValid($input);
			$this->assertEquals($result,$value);
		}
	}

	function _invalid_colors($ary){
		foreach($ary as $input){
			$err = $this->assertInvalid($input);
		}
	}
}
