$(document).ready(function(){

	/* Конвертируем тэг div #box в bounceBox: */
	$('#box_login').bounceBox();

	/* При поступлении события нажатия кнопки мыши на переключаем выпадающее окно: */
	$('a.button_login').click(function(e){

		$('#box_login').bounceBoxToggle();
		e.preventDefault();
	});
	
	/* Если в области выпадающего окна была нажата кнопка мыши, то открываем окно: */
	$('a.close').click(function(a){
		$('#box_login').bounceBoxHide();
        a.preventDefault();
	});
});
