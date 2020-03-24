ColorField
==========

ColorField is a form field for entering color in HEX, HEXA, RGB or RGBA format.

Installation
------------

Just use the Composer:

    cd path/to/your/atk14/project/
    composer require atk14/color-field

    ln -s ../../vendor/atk14/color-field/src/app/fields/color_field.php app/fields/color_field.php

### Integration with Pickr

By default, ColorField renders ```<input type="text">``` in which values like ```#FFFFFF```, ```rgb(22,33,44)```, ```rgba(100,150,170,0.8)``` can be written. Nothing interesting...
But ColorField is meant to be integrated with a nice color picker called Pickr (see https://github.com/Simonwep/pickr).

Pickr can be installed using npm:

    npm install --save npm install @simonwep/pickr

And a small js utility needs to be linked into the project:

    ln -s ../../../vendor/atk14/color-field/src/public/scripts/utils/color_picker_initializer.js public/scripts/utils/color_picker_initializer.js

Now edit gulpfile-admin.js (or gulpfile.js):

    var vendorStyles = [
      // ...

      // ColorPickr: Include one of the following themes
      "node_modules/@simonwep/pickr/dist/themes/classic.min.css"
      //"node_modules/@simonwep/pickr/dist/themes/monolith.min.css"
      //"node_modules/@simonwep/pickr/dist/themes/nano.min.css"
    ];

    // ...

    var vendorScripts = [
      // ...
      
      // ColorPickr: modern or es5 bundle (in here, the modern bundle fails)
      //"node_modules/@simonwep/pickr/dist/pickr.min.js"
      "node_modules/@simonwep/pickr/dist/pickr.es5.min.js"
    ];

    // ...

    var applicationScripts = [
      // ...

      "public/scripts/utils/color_picker_initializer.js",

      // ...
    ];

Finally place the following line into function common.init() in public/admin/scripts/application.js (or public/scripts/application.js).

    UTILS.color_picker_initializer.init();

Usage in an ATK14 application
-----------------------------

In a form:

    <?php
    // file: app/forms/pages/create_new_form.php
    class CreateNewForm extends AdminForm {

      function set_up(){
        // ...
        $this->add_field("background_color", new ColorField([
          "label" => "Background color",
          "initial" => "#4455ff",
          "opacity" => false,
          "theme" => "classic", // "classic", "monolith" or "nano"
          "swatches" => ["#4455ff", "#3366ee", "#223388","rgb(122,233,77)"]
        ]));
      }
    }

Testing
-------

    composer update --dev
    cd test
    ../vendor/bin/run_unit_tests

License
-------

ColorField is free software distributed [under the terms of the MIT license](http://www.opensource.org/licenses/mit-license)

[//]: # ( vim: set ts=2 et: )
