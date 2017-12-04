$(function(){
	
	var current_box;
	var pid3d = $('input[name=pidd]').val();	
	var g_opid;

	$('#images > table').before($('#custom_form_wrapper').html());
	$('#images form').fadeIn();
	$('.dropbox').each(function() {
	  if($(this).attr('id')=='dropbox_first') showCurrent(pid3d,'first');
	  else {
		  g_opid = $(this).attr('id').replace('dropbox_','');
		  if(g_opid!='') showCurrent(pid3d,g_opid);
	  }
	});
	$(document).ready(function(){
	    $('.add_more').click(function(e){
	        e.preventDefault();
	        $(this).before("<input name='pic[]' type='file'/><br>");
	    });
	    $('.ui-tabs-nav li.ui-state-active a').each(function(index, el) {
	   		 if(jQuery(this).attr('href') == '#images'){
	   		 	$('#custom_form').fadeIn();
	   		 }
	    });
	});
	$('#tabs').on( "tabsactivate", function( event, ui ) {
		var active = $('#tabs').tabs( "option", "active" );
		// console.log(active);
		if(active == 1){
			$('#custom_form').fadeIn();
		}else{
			$('#custom_form').hide();
		}
		
	});


	$('.dropbox').filedrop({
		// The name of the $_FILES entry:
		paramname:'pic',
		maxfiles: 40,
    	maxfilesize: 10,
//		url: 'html5uploader/post_file.php?act=update&pid='+pid3d+'&opid=',
    	data: {
    		opid:$(current_box).attr('id'),
    		img_w:$('input[name="img_width"]').val(),
    		img_h:$('input[name="img_height"]').val()
    	},
		uploadFinished:function(i,file,response){
			$.data(file).addClass('done');
			$.data(file).attr('id',response['current']);
			$.data(file).find('img').attr('name',response['current']);
	        $.data(file).append('<span class="delimg" name="'+response['current']+'"></span>');
            $.data(file).find('.delimg').click(function(){deleteCurrent(pid3d,$(this).attr('name'));});
      		$.data(file).append('<span class="editimg" name="'+response['current']+'"></span>');
      		$.data(file).find('.editimg').click(function(){jsoncrop($(this).parent().find('img').attr('name'));});
						
          $('.dropbox').sortable({
            connectWith: ".dropbox",
			update: function(event, ui) {
          		var newOrder = $(this).sortable('toArray').toString();
				$.post('html5uploader/post_file.php', {
					'pid': pid3d,
					'act':'sort', 
					'order':newOrder,
					'opid':$(this).attr('id').replace('dropbox_','')
				});
            }
          });
			// response is the JSON object that post_file.php returns
		},
		
    	error: function(err, file) {
			switch(err) {
				case 'BrowserNotSupported':
					showMessage('Your browser does not support HTML5 file uploads!',current_box);
					break;
				case 'TooManyFiles':
					alert('Too many files! Please select 5 at most! (configurable)');
					break;
				case 'FileTooLarge':
					alert(file.name+' is too large! Please upload files up to 2mb (configurable).');
					break;
				default:
					break;
			}
		},
		
		// Called before each upload is started
		beforeEach: function(file){  
			if(!file.type.match(/^image\//)){
				alert('Можно грузить только картинки!');
				
				// Returning false will cause the
				// file to be rejected
				return false;
			}
		},
		
		drop:function(dropper){
		  current_box = dropper.currentTarget;  
			this.url = 'html5uploader/post_file.php?act=update&pid='+pid3d+'&opid='+$(current_box).attr('id').replace('dropbox_','');   
		},
		
		uploadStarted:function(i, file, len){
			createImage(file,current_box);
		},
		
		dragOver:function(dropper){
			$(dropper.currentTarget).css('border','2px dashed #fff');
		},
		
		dragLeave:function(dropper){
			$(dropper.currentTarget).css('border','2px solid #fff');
		},
		
		progressUpdated: function(i, file, progress) {
			$.data(file).find('.progress').width(progress);
			$.data(file).parent().css('border','2px solid #fff');
		}
  
	});

 	
	var template = '<div class="preview">'+
						       '<span class="imageHolder">'+
							       '<img />'+
							       '<span class="uploaded"></span>'+
						       '</span>'+
						       '<div class="progressHolder">'+
							       '<div class="progress"></div>'+
						       '</div>'+
					       '</div>'; 
	
	
	function createImage(file,dbox_id){
		var preview = $(template), 
			image = $('img', preview);
			
		var reader = new FileReader();
		
		image.width = 100;
		image.height = 100;
		
		reader.onload = function(e){
			
			// e.target.result holds the DataURL which
			// can be used as a source of the image:
			
			image.attr('src',e.target.result);
		};
		
		// Reading the file as a DataURL. When finished,
		// this will trigger the onload function above:
		reader.readAsDataURL(file);
		
		$('.message', dbox_id).remove();
		preview.appendTo(dbox_id);
		
		// Associating a preview container
		// with the file, using jQuery's $.data():
		
		$.data(file,preview);
	}

	function showMessage(msg,current_box){
	  $('.message', current_box).html(msg);
	}         

	function showCurrent(pid,opid){
		 $.getJSON('html5uploader/post_file.php', {'pid': pid,'act':'read','opid':opid}, function(obj) {
		   var curr_dropbox = '#dropbox_'+opid;
  		 if(obj!=null) {
					var objlen = obj.length;
					if(objlen!=0) $(curr_dropbox+' .message').remove();
          for (i=0;i<objlen;i++) {
						 $(curr_dropbox).append('<div class="preview done" id="'+obj[i]+'"><span class="imageHolder"><img name='+obj[i]+' src="http://'+document.domain+'/images/thumb'+obj[i]+'"></span><span class="delimg" name="'+obj[i]+'"></span><span class="editimg" name="'+obj[i]+'"></span></div>');
          } 
          $('.delimg').click(function(){deleteCurrent(pid,$(this).attr('name'));});
          $('.editimg').click(function(){jsoncrop($(this).parent().find('img').attr('name'));});
         
				  $('.dropbox').sortable({
            connectWith: ".dropbox",
						update: function(event, ui) {
						  $('.message', $(this)).remove();
              var newOrder = $(this).sortable('toArray').toString();
							$.post('html5uploader/post_file.php', {'pid': pid,'act':'sort', 'order':newOrder,'opid':$(this).attr('id').replace('dropbox_','')});
            }
          });
			 }		  
     });
	}
	
	function deleteCurrent(pid,img){
	   var opid = $('.delimg[name="'+img+'"]').parent().parent().attr('id').replace('dropbox_','');

	   $.getJSON('html5uploader/post_file.php', {'pid': pid,'act':'del', 'img':img,'opid':opid}, function(obj) {
		   $('.delimg[name="'+img+'"]').parent().animate({width:0,marginLeft:0,opacity:0}, 500, function() {
			   $(this).remove();
			 });
		 });
	}



	$( "#crop_button" ).click(function() {
			
			  var img_name = $("#crop_area img").first().attr('name');
			  $.getJSON('html5uploader/post_file.php', {fn:img_name,act:'crop',v_x:$("#crop_x").val(),v_y:$("#crop_y").val(),v_w:$("#crop_w").val(),v_h:$("#crop_h").val(),img_w:$('input[name="img_width"]').val(),img_h:$('input[name="img_height"]').val()}, function(obj){
          // обноавляем картинку
          $("#crop_area").fadeOut(300,function () {
            $("#crop_area img").remove();
          });
          $('#crop_button').fadeOut(300);

          // обноавляем миниатюру
          $('img[name="'+img_name+'"]').attr('src','../images/thumb'+img_name+'?'+Math.random());

        });              
  });
      
}); 

  function jsoncrop(cur_image) {
    $('#crop_area').fadeIn(300);
    $('#crop_button').fadeIn(300);
    $('#crop_area').html('<img name="'+cur_image+'" src="../images/'+cur_image+'?'+Math.random()+'" />'); 

	  $('#crop_area img').Jcrop({
	    onSelect: updateCoords,
	    aspectRatio: $('input[name="img_width"]').val() / $('input[name="img_height"]').val()
 	  });
  }
  
	function updateCoords(c) {
	  $('#crop_x').val(c.x);
		$('#crop_y').val(c.y);
		$('#crop_w').val(c.w);
		$('#crop_h').val(c.h);
	};