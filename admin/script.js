$(document).ready(function() {

      // загрузка картинок для атрибутов    
		if($('#uploadButton3').is('*')) { 
			var button3 = $('#uploadButton3'), interval;
    	$.ajax_upload(button3, {
						action : 'upload.php?method=attribs',
						name : 'myfile3',
						onSubmit : function(file, ext) {
							// показываем картинку загрузки файла
							$("img#load3").attr("src", "load.gif");
							$("#uploadButton3 font").text('Загрузка');
							/*Выключаем кнопку на время загрузки файла*/
							this.disable();
						},
						onComplete : function(file, response) {
							// убираем картинку загрузки файла
							$("img#load3").attr("src", "images/butt_ld.png");
							$("#uploadButton3 font").text('Загрузить');
							// снова включаем кнопку
							this.enable();
             	// показываем что файл загружен
              if (response!='') file = response;  
              $("#filenames3").val(file);
              $("#files3").html($("<div class=attr_img><img name="+file+" id="+file+"self src='../images/thumb"+ file +"'/ /><img class=attr_del alt=удалить name="+file+" src=attributeManager/images/icon_delete.png onclick=jsondel3(this,this.name,document.getElementById('attr_id').value); /></div>"));
              $.get('towork_attrib_colors.php', {attr: $("#attr_id").val(),act:'write',img:$("#filenames3").val()}, function(obj){
               // alert(obj);
              });

						}
					});
		}
		
		if($('#uploadButton').is('*')) {  
			var button = $('#uploadButton'), interval;
    	$.ajax_upload(button, {
						action : 'upload.php',
						name : 'myfile',
						onSubmit : function(file, ext) {
							// показываем картинку загрузки файла
							$("img#load").attr("src", "load.gif");
							$("#uploadButton font").text('Загрузка');

							/*
							 * Выключаем кнопку на время загрузки файла
							 */
							this.disable();

						},
						onComplete : function(file, response) {
							// убираем картинку загрузки файла
							$("img#load").attr("src", "loadstop.gif");
							$("#uploadButton font").text('Загрузить');

							// снова включаем кнопку
							this.enable();
             	// показываем что файл загружен
              if (response!='') file = response;  
              var x=document.getElementById("filenames");
              x.value=x.value+file+"|";
              $("<div class=attr_img><img class=attr_crop alt=редактировать onclick=jsoncrop(this) name="+file+" id="+file+"self src='../images/thumb"+ file +"?"+Math.random()+"'/ /><img class=attr_del alt=удалить name="+file+" src=attributeManager/images/icon_delete.png onclick=jsondel(this,this.name,document.getElementById('pidd').value); /></div>").appendTo("#files");
              $.getJSON('towork.php', {pid: document.getElementById('pidd').value,act:'write',img:document.getElementById('filenames').value}, function(obj){
              //  alert(obj);
              });

						}
					});  
		}
	              
		});       
  function jsondel(o,k,p) {
   document.getElementById('filenames').value=document.getElementById('filenames').value.replace(k+'|',''); 
  $.getJSON('towork.php', {fn:o.name,act:'del',pid:p,img:document.getElementById('filenames').value}, function(obj){
             //  alert(obj);
            });
    var elem = document.getElementById(k+'self'); 
    $(elem).parent().remove();
  }
  function jsondel3(o,k,at) {
    $.get('towork_attrib_colors.php', {fn:o.name,act:'del',attr:at,img:document.getElementById('filenames3').value}, function(obj){
      $('.attr_img').remove();
    });  
  }

  
  