Intoduction
================================
So what is ImagePicker? Some time ago (august 2011) I was making an edit
page for a database entity with a foreign key reference to the image 
table. So I wanted the user to be able to select an image. Fist I wanted
to use the WYSIWYG module, until I found out that the coupling between
this module and the calling module is very tightly. The module adds a
piece of HTML to some editor. But all I wanted is the pimary key of the
image. So I decided to write something that is loosly coupled.

Features
================================
* Loosly coupled (i.e. the module has no need to know anything about the
  calling module.)
* Select images
* Define the width of your image (optionally)
* Define the alignment of your image (optionally)
* No iframe

Usage
================================
So how do you use this marvelous piece of technology? Simple! Just follow
the following steps:

* download and install the module
* append some JavaScript and CSS files to your template in your 
  contoller:

```PHP
$this->template
	// This one is very important.
	->append_metadata(js('imagepicker.js', 'imagepicker'))
	// To make it look good...
	->append_metadata(css('admin.css', 'imagepicker'))
	// You probably already use the following two, no need to include them twice...
	->append_metadata(js('jquery/jquery-ui.min.js'))
	->append_metadata(css('jquery/ui-lightness/jquery-ui.css'));
```
* In your view somewhere near the (hidden) input field that holds the
  primary key of the image make a button or a link that fires a piece
  of JavaScript when clicked:

```javascipt
<script type="text/javascript">
	(function($) {  
		$('#pickanimage').livequery('click', function(){
			ImagePicker.open({
				showSizeSlider      : false, //true,
				showAlignButtons    : false, //true,
				onPickCallback      : function(imageId, size, alignment) {
					alert("you chose image: " + imageId + ", with width: " + size + " and alignment: " + alignment);
				}
			});
			return false;
		});
	})(jQuery);
</script>
```

That's it...

The function **ImagePicker.open** takes 3 arguments:

* **showSizeSlider** when true the slider to chose the size is shown. 
  (default true)
* **showAlignButtons** when true the buttons to chose the alignment 
  are shown. (default true)
* **onPickCallback** this is a callback function that is called when an
  image is chosen (by clicking on it), you can use the three parameters
  any way you like, therby giving you complete freedom.

Copyright
================================

Copyright 2011 Geert Mulders

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

	http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

Change List
================================

**ImagePicker v0.1**

* Initial version.

