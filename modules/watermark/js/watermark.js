$(function(){
	$('#colorpicker').ColorPicker({  
        onSubmit: function(hsb, hex, rgb, el, parent) {  
            $(el).val(hex);  
            $(el).ColorPickerHide();  
        },  
        onBeforeShow: function () {  
            $(this).ColorPickerSetColor(this.value);  
        }  
    })  
    .bind('keyup', function(){  
        $(this).ColorPickerSetColor(this.value);  
    });   
});
