$(document).ready(function(){

// top search css
  
$('#joforce-search-btn').click(function(){
				$('#joforce-search-section').slideToggle( "fast",function(){
					 $( '#joforce-search-box' ).toggleClass( "joforce-search-open" );
				});
				$('#joforce-search-box').focus()
		});
$('.joforce-search-close').click(function(){
$('#joforce-search-section').css("display","none");

});


// $('.table-container .ps-container .ps-active-y').each(function(){
//     if($(this).hasClass('.ps-active-x')) {
//         $(this).closest('.table-container .ps-container .ps-active-y').siblings('#joforce-table-search').toggleClass("off");
//     } 
// });

// if($(".table-container .ps-container .ps-active-y").hasClass(".ps-active-x")){

// 	$("#joforce-table-search").addClass("lll");
// }
 

 // $( ".table-container.ps-container.ps-active-y.ps-active-x" ).toggleClass( "active" );

});


$(document).on('click','#joforce-table-search', function() {
    $(".searchRow").slideToggle();
}); 