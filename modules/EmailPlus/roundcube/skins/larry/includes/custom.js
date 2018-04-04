// <script>

$(document).ready(function(){	
	$('.folderlist li.mailbox a').css("background-image","none");
	$('li.mailbox.inbox a').html('<i class="fa fa-envelope side-icon"></i> Inbox');
	$('li.mailbox.virtual a:first-child').html('<i class="fa fa-google side-icon"></i> Gmail');
	$('li.mailbox.drafts a').html('<i class="fa fa-pencil side-icon"></i> Drafts');
	$('li.mailbox.sent a').html('<i class="fa fa-send side-icon"></i> Sent');
	$('li.mailbox.trash a').html('<i class="fa fa-trash side-icon"></i> Trash');
	$('li.mailbox.junk a').html('<i class="fa fa-trash-o side-icon"></i> Junk');
	$('li.mailbox.virtual ul li:nth-last-child(3) a').html('<i class="fa fa-envelope-o side-icon"></i> All mail');
	$('li.mailbox.virtual ul li:nth-last-child(2) a').html('<i class="fa fa-file-o side-icon"></i> Important');
	$('li.mailbox.virtual ul li:nth-last-child(1) a').html('<i class="fa fa-file-o side-icon"></i> Starred');
	$('.side-icon').css("font-size","13px").css("margin-right","8px");
	$('.treetoggle.expanded').hide();

	$('.toolbar a.button').css("background-image","none");
	$('.toolbar a.button.checkmail').html('<i class="fa fa-refresh toolbar-icon"></i> Refresh');
	$('.toolbar a.button.compose').html('<i class="fa fa-plus toolbar-icon"></i> Compose');
	$('.toolbar a.button.reply').html('<i class="fa fa-mail-reply toolbar-icon"></i> Reply');
	$('.toolbar a.button.reply-all').html('<i class="fa fa-mail-reply-all toolbar-icon"></i> Reply all');
	$('.toolbar a.button.forward').html('<i class="fa fa-mail-forward toolbar-icon"></i> Forward');
	$('.toolbar a.button.delete').html('<i class="fa fa-trash toolbar-icon"></i> Delete');
	$('.toolbar a.button.markmessage').html('<i class="fa fa-bookmark toolbar-more"></i> Mark');
	$('.toolbar a.button.more').html('<i class="fa fa-ellipsis-h toolbar-more"></i> More');
	$('.toolbar a.button.back').html('<i class="fa fa-arrow-left toolbar-icon"></i> Cancel');
	$('.toolbar a.button.send').html('<i class="fa fa-send toolbar-more"></i> Send');
	$('.toolbar a.button.savedraft').html('<i class="fa fa-save toolbar-more"></i> Save');
	$('.toolbar a.button.spellcheck').html('<i class="fa fa-check toolbar-more"></i> Spell');
	$('.toolbar a.button.attach').html('<i class="fa fa-paperclip toolbar-icon"></i> Attach');
	$('.toolbar a.button.insertsig').html('<i class="fa fa-pencil-square-o toolbar-icon"></i> Signature');
	$('.toolbar a.button.responses').html('<i class="fa fa-file-o toolbar-icon"></i> Responses');
	$('.toolbar a.button.move').html('<i class="fa fa-arrows toolbar-more"></i> Move'); 
		$('.toolbar a.button.print').html('<i class="fa fa-print toolbar-more"></i> Print');
	$('.toolbar-more').css("font-size","13px").css("position","relative").css("top","-15px").css("left","20px");
	$('.toolbar-icon').css("font-size","13px").css("position","relative").css("top","-15px").css("left","25px");


	$('#compose-contacts li a').css("background-image","none");
	$('.addressbook a').html('<i class="fa fa-newspaper-o" style="margin-right:8px;"></i> Personal Addresses');

	$('.messagelist thead tr th.status span.status').css("background-image","none");
	$('.messagelist thead tr th.status').html('<i class="fa fa-star" style="font-size:15px;margin-left:3px;color:#333;"></i>');
	$('.messagelist thead tr th.flag span.flagged').css("background-image","none");
	$('.messagelist thead tr th.flag').html('<i class="fa fa-flag" style="font-size:13px;margin-left:3px;color:#333;"></i>');	
	$('.messagelist thead tr th.attachment span.attachment').css("background-image","none");
	$('.messagelist thead tr th.attachment').html('<i class="fa fa-paperclip" style="font-size:13px;margin-left:3px;color:#333;"></i>');
	// $('.messagelist thead tr th.threads a.listmenu').css("background-image","none");
	// $('.messagelist thead tr th.threads').html('<i class="fa fa-gear" style="font-size:13px;margin-left:3px;color:#333;"></i>');	

	$('.boxfooter .groupactions .inner').css("background-image","none");
	$('.boxfooter .groupactions').html('<i class="fa fa-gear" style="font-size:20px;color:#333;position:relative;left:25px;top:5px;"></i>');
	$('.boxfooter .countdisplay').css("background-image","none").css("top","1px").css("color","#333");

	$('table.headers-table tbody td.header span.quick-create-contact img.quick-create-module').css("width","20px").css("cursor","pointer");

});

// </script>