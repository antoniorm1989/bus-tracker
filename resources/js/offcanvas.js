$(function () {
  'use strict'

  $('[data-toggle="offcanvas"]').on('click', function () {
    //$('.offcanvas-collapse').toggleClass('open')
    if($('.offcanvas-collapse').width() == '0'){
      $('.offcanvas-collapse').width('100%');
    }else{
      $('.offcanvas-collapse').width('0');
    }
  })
})