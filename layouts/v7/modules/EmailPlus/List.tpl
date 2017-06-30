{*<!--
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */
-->*}

<script>
var height = window.innerHeight;
$(document).ready( function(){
	$('#roundcube_interface').css('height', height-70)
} );
</script>
<iframe id="roundcube_interface" style="width: 100%; height: 590px;" src="{$URL}" frameborder="0"> </iframe>
<input type="hidden" value="" id="temp_field" name="temp_field"/>
<input type="hidden" value="{vglobal('site_URL')}" id="site_URL"/>
