function mySubmit()
{
  if (document.timeeditform.wv_search != null) document.timeeditform.wv_search.value = '';
  if (document.timeeditform.wv_first != null) document.timeeditform.wv_first.value = '0';
  document.timeeditform.submit();
}

function addObject(id)
{
  document.timeeditform.wv_addObj.value = id;
  document.timeeditform.submit();
}

function delObject(id)
{
  document.timeeditform.wv_delObj.value = id;
  document.timeeditform.submit();
}

function adjustStartWeek()
{
  if (document.timeeditform.wv_startWeek.selectedIndex >
  document.timeeditform.wv_stopWeek.selectedIndex)
    document.timeeditform.wv_startWeek.selectedIndex =
      document.timeeditform.wv_stopWeek.selectedIndex;
}

function adjustStopWeek()
{
  if (document.timeeditform.wv_startWeek.selectedIndex >
  document.timeeditform.wv_stopWeek.selectedIndex)
    document.timeeditform.wv_stopWeek.selectedIndex =
      document.timeeditform.wv_startWeek.selectedIndex;
}

function setStartWeek(week)
{
  var weekObj = document.timeeditform.wv_startWeek;
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
  var weekObj = document.timeeditform.wv_stopWeek;
  for (i = 0; i < weekObj.length; i++)
  {
    if (weekObj.options[i].value == week)
    {
      weekObj.selectedIndex = i;
    }
  }
  adjustStartWeek();
}

