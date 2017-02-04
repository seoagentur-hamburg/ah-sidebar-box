// ========== Sidebox ==========================================================

jQuery(document).ready(function() {

  //Get all the LI from the #tabMenu UL
  jQuery('#tabMenu > li').click(function(){

    //remove the selected class from all LI
    jQuery('#tabMenu > li').removeClass('selected');

    //Reassign the LI
    jQuery(this).addClass('selected');

    //Hide all the DIV in .boxBody
    jQuery('.boxBody div').slideUp('1500');

    //Look for the right DIV in boxBody according to the Navigation UL index, therefore, the arrangement is very important.
    jQuery('.boxBody div:eq(' + jQuery('#tabMenu > li').index(this) + ')').slideDown('1500');

  }).mouseover(function() {

    //Add and remove class, Personally I dont think this is the right way to do it, anyone please suggest
    jQuery(this).addClass('mouseover');
    jQuery(this).removeClass('mouseout');

  }).mouseout(function() {

    //Add and remove class
    jQuery(this).addClass('mouseout');
    jQuery(this).removeClass('mouseover');

  });

  //Mouseover with animate Effect for Category menu list
  jQuery('.boxBody #category li').mouseover(function() {

    //animate the padding
    jQuery(this).children().animate({paddingLeft:"20px"}, {queue:false, duration:100});
  }).mouseout(function() {

    //animate the padding
    jQuery(this).children().animate({paddingLeft:"0"}, {queue:false, duration:100});
  });

});