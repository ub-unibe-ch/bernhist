require('../css/app.scss');

const $ = require('jquery');
require('bootstrap');
require('./chartist-modal.js');

$(document).ready(function(){
   console.log('Welcome to BERNHIST!');
   if(window.location.hash){
      let id = window.location.hash.substr(1);
      let current = $('#level-' + id);

      current.parents('.level-entry').each(function(){
         $(this).find('.level-toggle:first').each(function(){
            if(!$(this).hasClass('opened')){
               $(this).click();
            }
         });
      });
   }
});

$('.level-toggle').click(function(){
   $(this).toggleClass('opened');
   $(this).closest('.level-entry').children('.level-entry').toggleClass('d-none');
});

$('.year-select').change(function(){
   let url = $(this).closest('.year-select-group').data('url');
   let yearFrom = $('#year-from').val();
   let yearTo = $('#year-to').val();
   url = url.replace('.yearFrom.', yearFrom).replace('.yearTo.', yearTo);
   document.location.href = url;
});