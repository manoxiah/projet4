



$('.date-picker-booking').datepicker({
    startDate: "now",
    format: "dd/mm/yyyy",
    language: "fr",
    todayHighlight: true,
    daysOfWeekDisabled : "0,2",
    clearBtn: true,
    autoclose : true,
    datesDisabled : [ '01/05/2019','01/11/2019','25/12/2019' ]
});
$('.date-picker-birth').datepicker({
    format: "dd/mm/yyyy",
    language: "fr",
    todayHighlight: true,
    clearBtn: true,
    autoclose : true

});

document.getElementsByClassName("stripe-button-el")[0].style.display = 'none';
