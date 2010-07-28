function setSelectedMonth(month)
{
  document.form.wv_selectedMonth.value = month;
  document.form.submit();
}

function adjustStartWeek()
{
  if (document.form.wv_startWeek.selectedIndex >
  document.form.wv_stopWeek.selectedIndex)
    document.form.wv_startWeek.selectedIndex =
      document.form.wv_stopWeek.selectedIndex;
}

function adjustStopWeek()
{
  if (document.form.wv_startWeek.selectedIndex >
  document.form.wv_stopWeek.selectedIndex)
    document.form.wv_stopWeek.selectedIndex =
      document.form.wv_startWeek.selectedIndex;
}

function addObject(id)
{
  var reloadingdiv = document.getElementById('reloading');
  reloadingdiv.style.display = '';
  document.form.wv_addObj.value = id;
  document.form.submit();
}

function delObject(id)
{
  document.form.wv_delObj.value = id;
  document.form.submit();
}

function setStartWeek(week)
{
  var weekObj = document.form.wv_startWeek;
  for (i = 0; i < weekObj.length; i++)
  {
    if (weekObj.options[i].value == week)
    {
      weekObj.selectedIndex = i;
    }
  }
  adjustStopWeek();
}

function setStopWeek(week)
{
  var weekObj = document.form.wv_stopWeek;
  for (i = 0; i < weekObj.length; i++)
  {
    if (weekObj.options[i].value == week)
    {
      weekObj.selectedIndex = i;
    }
  }
  adjustStartWeek();
}

function openHelp()
{
  var win = window.open('', 'Help',
    'resizable=yes,scrollbars=yes,status=no,width=300,height=500');
  win.document.location = '/4DACTION/WebShowHome/1-2';
}

function mySubmit()
{
  var reloadingdiv = document.getElementById('reloading');
  reloadingdiv.style.display = '';
  if (document.form.wv_search != null) document.form.wv_search.value = '';
  if (document.form.wv_first != null) document.form.wv_first.value = '0';
  document.form.submit();
}

// function showCal()
// {
//   var week = document.form.wv_startWeek
//     .options[document.form.wv_startWeek.selectedIndex].value
//   var win = window.open('', 'Calendar',
//     'resizable=yes,scrollbars=yes,status=no,width=300,height=200');
//   win.document.location = '/4DACTION/WebShowSearchCalendar/1/2/' + week;
// }