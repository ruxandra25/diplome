function daysInMonth(month,year) {
    var dd = new Date(year, month, 0);
    return dd.getDate();
}

function setDayDrop(dyear, dmonth, dday) {
    var year = dyear.options[dyear.selectedIndex].value;
    var month = dmonth.options[dmonth.selectedIndex].value;
    var day = dday.options[dday.selectedIndex].value;
    var days = (year == ' ' || month == ' ') ? 31 : daysInMonth(month,year);
    dday.options.length = 0;
    dday.options[dday.options.length] = new Option(' ',' ');
    for (var i = 1; i <= days; i++)
        dday.options[dday.options.length] = new Option(i,i);
 
}

function setDay() {
    var year = document.getElementById('year');
    var month = document.getElementById('month');
    var day = document.getElementById('day');
    setDayDrop(year,month,day);
}

