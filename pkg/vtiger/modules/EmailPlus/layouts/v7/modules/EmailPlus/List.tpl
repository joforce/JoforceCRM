<script>
var height = window.innerHeight;
$(document).ready( function(){
	$('#roundcube_interface').css('height', height-70)
} );
</script>
<iframe id="roundcube_interface" style="width: 100%; height: 590px;" src="{$URL}" frameborder="0"> </iframe>
<input type="hidden" value="" id="temp_field" name="temp_field"/>
<input type="hidden" value="{vglobal('site_URL')}" id="site_URL"/>
