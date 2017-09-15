$(document).ready(function() {
 
     	var url = document.location; 
	var module = app.getModuleName();
  	var currentId = app.getRecordId();
  	var record_view = app.getViewName();
    	if(window.location.href.indexOf("isDuplicate") > -1)	{
      		var dupPage = /isDuplicate=([^&]+)/.exec(url)[1];
      		dupPage = dupPage ? dupPage : 'myDefaultValue';
    	}

      	if(dupPage == "true")	{
          setTimeout(function()	{
                if($('button[type="submit"]'))	{
              		$(".btn-success").addClass("vtSmackCustomClass");
              		$('button[type="submit"]').prop("type", "button");
                } 
     	   },200);
      }
    
    	makeDiv ="";

    	var fieldName = "";
     	fieldNameArray = new Array();
    
    	var urldata = {
               "type": "GET",
               "data": 'module=DuplicateCheck&view=GetFieldsName&moduleName='+module,
               "url": 'index.php'
       	};

           AppConnector.request(urldata).then
           (
              function (response) {  
                var data = JSON.parse(response); 
		var isenable=data.result[0]; 
              	var count = data.result[1][0];
              	var i = 0;
            for( i=1;i<=count;i++ ){
		if(isenable>0)
		{
                var fieldName = data.result[1][i];
		$('[name="'+fieldName+'"]').addClass("vtsmack");
                fieldNameArray.push(data.result[1][i]);
	//	$('[name="'+fieldName+'"]').focusout();
		}
               
            }

         } 
        );
       
        $(".vtsmack").live('focusin', function(){
             $(".vtSmackClass").remove();
        })

          $('.vtsmack').live('focusout', function(){
	
            var fieldName = $(this).attr("name");
            var fieldValues = $(this).val();
            var fieldId = $(this).attr("id");
            if(fieldValues!=""){
            var addCustomClass = $('[name="'+fieldName+'"]').addClass("vtSmackCustomError");
            var validateData = {
                "type":"GET",
                "data":'module=DuplicateCheck&view=ValidateDuplicate&record_id='+currentId+'&record_view='+record_view+'&moduleName='+module+"&fieldName="+fieldName+"&fieldValues="+fieldValues,
                "url" : 'index.php'

            };

            AppConnector.request(validateData).then
            (
                function(response){ 
                    var result = JSON.parse(response); 
                    var resultantCount = result.result[0][0];
                    var count = resultantCount + 2;
		    var count_of_fields=result.result[0][2][0];
		    var count_of_fieldName=result.result[0][2][1];
                    var fieldlabel = result.result[0][1]['fieldlabel'];
                    makeDiv = "";
		if(resultantCount < 1){
			$('button[type="button"]').prop("type", "submit");	
			$('[name="'+fieldName+'"]').removeClass("vtsmackCustomClass");
                    }
        	    else  {
                       $(".btn-success").addClass("vtSmackCustomClass");
                         $('button[type="submit"]').prop("type", "button");
                       makeDiv += "";
                         makeDiv += fieldlabel+" already exists with : "+"<br/>";
                        for(i=3;i<=count;i++){
                            if(dupPage != "true"){
                                if(result.result[0][i]['recordid']==currentId){
                                   continue;
                                }
                            }
			if(count_of_fields==1)
                        	var recordname=result.result[0][i][count_of_fieldName]; 
                        else    {
                                if(result.result[0][i]['firstname'])
                               		var recordname = result.result[0][i][count_of_fieldName[0]] + ' '+result.result[0][i][count_of_fieldName[1]];
                                else
                                	var recordname = result.result[0][i][count_of_fieldName[1]];
                                        }
                                        var recordid = result.result[0][i]['recordid'];
					
                       
                            var urlpath = "index.php?module="+module+"&view=Detail&record="+recordid;
                            makeDiv +="<u><a href="+urlpath+" style='color:white' target=_blank>"+recordname+' '+' ( #'+recordid+' ) '+"</a></u>";
                            makeDiv +="<br/>";

                        }

                        $('#'+fieldId).validationEngine('showPrompt', makeDiv, 'anything else');
                     //   makeDiv = "";
                    }
			var uitype =result.result[1][0];
			var crosscount=result.result[1][2];
			if((uitype == 11) || (uitype==13)){
			for(i=0;i<crosscount;i++){	
				var crossmodulename=result.result[1][3][i]['modulename'];
				var crossrecordid=result.result[1][3][i]['recordid'];
			
				if(crossmodulename == 'Accounts')
					var crossrecordname=result.result[1][3][i][0];
				else
					var crossrecordname=result.result[1][3][i][0] +' '+result.result[1][3][i][1];
			
			 	var modulepath = "index.php?module="+crossmodulename+"&view=Detail&record="+crossrecordid;
				makeDiv +=" <u><a href="+modulepath+" style='color:white' target=_blank>"+crossrecordname+' '+' ( #'+crossrecordid+' '+ crossmodulename +")<a></u><br/>";
			
			}
			$('#'+fieldId).validationEngine('showPrompt', makeDiv, 'anything else');
			}
                }
          )
  }

});  


           	$(".vtSmackCustomClass").live('click',function()	{
                     var message = "Duplicate found. Do you still want to save this record ?";
                     Head_Helper_Js.showConfirmationBox({'message': message}).then(
                     function(e){
				$(".btn-success").removeClass("vtSmackCustomClass");
                        //	$('button[type="button"]').removeClass("vtSmackCustomClass");                            
                          	$('button[type="button"]').prop("type", "submit");
                          	$('#EditView').submit();
                        }
                    )
          	})
});
