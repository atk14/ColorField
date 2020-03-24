window.UTILS = window.UTILS || { };

window.UTILS.color_picker_initializer = { };

window.UTILS.color_picker_initializer.init = function() {

	var $ = window.jQuery;
	var Pickr = window.Pickr;

	// See https://github.com/Simonwep/pickr
	$( "input[data-handler=color-picker]" ).each( function() {
		var $input = $( this );
		var placeholder_id = "id_color-picker_" + $input.attr( "id" );
		var pickr;

		if ( $input.data( "color-picker-initialized" ) ) {
			return;
		}
		$input.data( "color-picker-initialized", "true" );
		$input.hide();

		$( "<div id=\"" + placeholder_id + "\">P</div>" ).insertAfter( $input );

		pickr = Pickr.create( {
			el: "#" + placeholder_id,
			theme: "classic", // or "monolith", or "nano"
			default: $input.val() === "" ? null : $input.val(),

			// Swatches: $input.data( "swatches" ),
			swatches: $input.data( "swatches" ),
			defaultRepresentation: "RGB",

			components: {

				// Main components
				preview: true,
				opacity: $input.data( "opacity" ),
				hue: true,

				// Input / output Options
				interaction: {
					hex: true,
					rgba: true,
					hsla: false,
					hsva: false,
					cmyk: false,
					input: true,
					clear: !$input.attr( "required" ),
					save: true
				}
			}
		} );

		pickr.on ( "save", function( color ) {
			console.log( color );
			if ( color === null ) {
				$input.val( "" );
				return;
			}
			$input.val( color.toRGBA().toString( 3 ) );
		} );
	});
};
