//apply datepicker for input
$('input.datepicker').each(function() {
   var options = $(this).data('options') || {};
   options.show_icon = false;
   options.direction = true;
   if (typeof options.pair === 'string') {
       options.pair = $(options.pair);
   }
   $(this).Zebra_DatePicker(options);
});
