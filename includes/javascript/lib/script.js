$(document).ready(function(){

    /* Конвертируем тэг div #box в bounceBox: */
    $('#box').bounceBox();

    /* При поступлении события нажатия кнопки мыши на переключаем выпадающее окно: */
    $('a.button').click(function(e){

        $('#box').bounceBoxToggle();
        e.preventDefault();
    });
    
    /* Если в области выпадающего окна была нажата кнопка мыши, то открываем окно: */
  $('a.close').click(function(a){
        $('#box').bounceBoxHide();
        a.preventDefault();
    });
});
