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

	function test_widget(){
		$form = new Atk14Form();

		$c1 = $form->add_field("c1", new ColorField(array(
		)));
		$c1 = $form->get_field("c1");
		$this->assertEquals('<input required="required" data-handler="color-picker" data-theme="classic" data-opacity="false" data-swatches="[]" type="text" name="c1" class="text form-control" id="id_c1" />',$c1->as_widget());

		$c2 = $form->add_field("c2", new ColorField(array(
			"required" => false,
			"initial" => "#FFFFFF",
			"theme" => "nano",
			"opacity" => true,
			"swatches" => array("#112233","rgb(0,10,20)")
		)));
		$c2 = $form->get_field("c2");
		$this->assertEquals('<input data-handler="color-picker" data-theme="nano" data-opacity="true" data-swatches="[&quot;#112233&quot;,&quot;rgb(0,10,20)&quot;]" type="text" name="c2" class="text form-control" id="id_c2" value="#FFFFFF" />',$c2->as_widget());
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
