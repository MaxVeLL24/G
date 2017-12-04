
function Requester()
{
	this.action = null;
	this.XML = null;
	this.commInterface = null;
	this.targetId = null
	// Initialise XMLHttpRequest object
	this.resetXMLHR();

	return true;
}


/**
 * Check if the XMLHttpRequest object is available 
 */
Requester.prototype.isAvailable = function(){
	return (this.commInterface == null) ? false : true;
}

 function go_uploadattr(getattrid){ 
     $.get('towork_opt_values.php', {attr:getattrid,act:'read'}, function(obj) {
       $('#files_'+getattrid).html('');
       $('#filenames_'+getattrid).val(obj.replace(/\,/g,'|'));
          obj = obj.split('|');
          for (i=0;i<obj.length-1;i++) {
            fname = obj[i].substring(obj[i].lastIndexOf('/')+1,obj[i].length);
            $("#files_"+getattrid).append('<div class=attr_img style="margin:0 2px;"><img style="height:22px;" name='+obj[i]+' id='+obj[i]+'self src="../images/thumb'+ fname +'" /><img class=attr_del2 style="cursor:pointer;" alt=удалить name='+obj[i]+' src=attributeManager/images/icon_delete.png onclick={jsondelattr(this,this.name,'+getattrid+');} /></div>');
          }
      });
 }
 
  function jsondelattr(o,k,at) {
    $("#filenames_"+at).val($("#filenames_"+at).val().replace(k+'|',''));
    $.get('towork_opt_values.php', {fn:o.name,act:'del',attr:at,img:$('#filenames_'+at).val()}, function(obj){
      amSendRequest();
    });  
  }

/* Execute the action which has been associated with the completion of this object */
Requester.prototype.executeAction = function() {
	// If XMLHR object has finished retrieving the data
	
	if (this.commInterface.readyState == 4)	{
		// If the data was retrieved successfully
		try	{
			if (this.commInterface.status == 200)	{
				this.responseText = this.commInterface.requestXML;
				this.action();
        
    //----------------------raid-----------------------------------// 
      
$(document).ready(function() {
   $(".r_sizes").each(function() {
     var thisval = $(this).val();
     go_uploadattr(thisval); 
     
		if($('#uploadButton_'+thisval).is('*')) { 
			var button = $('#uploadButton_'+thisval), interval;
    	$.ajax_upload(button, {
						action : 'upload.php?method=opt_values&thisval='+thisval,
						name : 'myfile_'+thisval,
						onSubmit : function(file, ext) {
							// показываем картинку загрузки файла
							$("img#load_"+thisval).attr("src", "load.gif");
							$("#uploadButton_"+thisval+" font").text('Загрузка');
							/*Выключаем кнопку на время загрузки файла*/
							this.disable();
						},
						onComplete : function(file, response) {
							// убираем картинку загрузки файла
							$("img#load_"+thisval).attr("src", "images/butt_ld.png");
							$("#uploadButton_"+thisval+" font").text('Загрузить');
							// снова включаем кнопку
							this.enable();
             	// показываем что файл загружен
              if (response!='') file = response;
              $("#filenames_"+thisval).val($("#filenames_"+thisval).val()+file+"|");
               $.get('towork_opt_values.php', {attr: thisval,act:'write',img:$("#filenames_"+thisval).val()}, function(obj){
                amSendRequest();
              });

						}
					});
		}
  });  
});
    //----------------------raid-----------------------------------//  
			}
			// IE returns status = 0 on some occasions, so ignore
			else if (this.commInterface.status != 0){
				alert("There was an error while retrieving the URL: " + this.commInterface.statusText);
			}
		}
		catch (error){}
	}
	return true;
}


/* Return responseText */
Requester.prototype.getText = function() {
	return this.commInterface.responseText;
}


/* Return responseXML */
Requester.prototype.getXML = function() {
	return this.commInterface.responseXML;
}


/* Initialise XMLHR object and load URL */
Requester.prototype.loadURL = function(URL, CGI) {
	this.resetXMLHR();
	
	this.commInterface.open("GET", URL + "?" + CGI);
	var e=(document.charset||document.characterSet||'ISO-8859-8-i');
	this.commInterface.setRequestHeader("Content-Type", "text/html; charset="+e);
	this.commInterface.setRequestHeader('Accept-Charset',e)
	this.commInterface.send(null);

	return true;
}


/* Turn off existing connections and create a new XMLHR object */
Requester.prototype.resetXMLHR = function() {
	var self = this;

	if (this.commInterface != null && this.commInterface.readyState != 0 && this.commInterface.readyState != 4)	{
		this.commInterface.abort();
	}

	try	{
		this.commInterface = new XMLHttpRequest();
	}
	catch (error) {
		try {
			this.commInterface = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch (error) {
			return false;
		}
	}

	this.commInterface.onreadystatechange = function()	{
		self.executeAction();
		return true;
	};
	return true;
}

/* Assign the function which will be executed once the XMLHR object finishes retrieving data */
Requester.prototype.setAction = function(actionFunction,part) {
	this.action = actionFunction;
	return true;
}


Requester.prototype.setTarget = function(targetId) {
	this.targetId = targetId;
	return true;
}

Requester.prototype.getTarget = function() {
	return this.targetId;
}