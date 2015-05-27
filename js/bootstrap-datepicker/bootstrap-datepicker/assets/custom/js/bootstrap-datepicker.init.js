if (typeof $.fn.bdatepicker == 'undefined')
	$.fn.bdatepicker = $.fn.datepicker.noConflict();

$(function()
{
	 var nowDate = new Date();
	 var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
	/* DatePicker */
	// default
	$("#datepicker1").bdatepicker({
		format: 'yyyy-mm-dd',
		startDate: today
	});
	// custom datepicker id
	$("#datepicker6").bdatepicker({
		format: 'yyyy-mm-dd',
		startDate: today
	});
   // custom change of datepicker id
   $("#mydatepicker").bdatepicker({
		format: 'yyyy-mm-dd',
		startDate: "2013-05-14"
		
	});
   // custom change of datepicker id
   $("#datepicker").bdatepicker({
		format: 'yyyy-mm-dd',
		startDate: today
		
	});
	// Custom Change of datepicker id
   $("#mydatepicker1").bdatepicker({
		format: 'yyyy-mm-dd',
		startDate: "2013-05-14"
		
	});	
	// Custom Change of datepicker id
   $("#mydatepicker2").bdatepicker({
		format: 'yyyy-mm-dd',
		startDate: "2013-05-14"
		
	});	
	$("#mydatepicker1").bdatepicker({
		format: 'yyyy-mm-dd',
		startDate: today
	});
   
   
	// component
	$('#datepicker2').bdatepicker({
		format: "dd MM yyyy",
		startDate: today
	});

	// today button
	$('#datepicker3').bdatepicker({
		format: "dd MM yyyy",
		startDate: "2013-02-14",
		todayBtn: true
	});

	// advanced
	$('#datetimepicker4').bdatepicker({
		format: "dd MM yyyy - hh:ii",
        autoclose: true,
        todayBtn: true,
        startDate: "2013-02-14 10:00",
        minuteStep: 10
	});
	
	// meridian
	$('#datetimepicker5').bdatepicker({
		format: "dd MM yyyy - HH:ii P",
	    showMeridian: true,
	    autoclose: true,
	    startDate: "2013-02-14 10:00",
	    todayBtn: true
	});

	// other
	if ($('#datepicker').length) $("#datepicker").bdatepicker({ showOtherMonths:true });
	if ($('#datepicker-inline').length) $('#datepicker-inline').bdatepicker({ inline: true, showOtherMonths:true });

});
