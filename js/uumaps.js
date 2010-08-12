// <PROGRAM_NAME>
// Copyright (C) 2010 Filip Gottfridsson (filip.gottfridsson@gmail.com)
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.


var marker;
var myMarker;
var directionsR;
var directionsS;
var defaultLocation;
var map;

//This function will first check if a campus is selected.
//then it will determine if the user want to have directions
//from an address or W3C's Geolocation
//If the user choose directions by addres the 
//this function will check if the entered adress is valid, if so
//it will call on the function GetDírection
function Geocoding(success, fail, getAdress,  fieldtext) 
{
  var myGeocode = new google.maps.Geocoder();
  if ($("#uuMapModule #Locations option:selected").attr('id') == 'CampusListText') 
  {
    alert("You have to choose a campus first");
  }
  else 
  {
    $('#uuMapModule #loadmyGPS').css('opacity', 100);
    if(getAdress == false)
    {
      var height = $("#map_canvas").height();
      $(window).scrollTop(height-15);
      getMyLocation(success, fail);
    }
    else
    {
      var request = {
        address:$('#uuMapModule #DirectionsAddress').val(),
      };
      myGeocode.geocode(request, function(results, status)
      {         
        if (status == google.maps.GeocoderStatus.OK) 
        {
          myMarker.setPosition((results[0].geometry.location));
          var height = $("#map_canvas").height();
          $(window).scrollTop(height-15);
          GetDirection(myMarker.getPosition());
        }
        else if (status == google.maps.GeocoderStatus.ZERO_RESULTS)
        {
          if ($('#uuMapModule #DirectionsAddress').val()!= "Skriv din adress här")
          {
            alert("Address: \"" + fieldtext + "\" not found");
            $('#uuMapModule #loadmyGPS').css('opacity', 0);
          }
          else
          {
            alert("Adress: \""+"\" not found");
            $('#uuMapModule #loadmyGPS').css('opacity', 0);
          }
        }
        else if (status == google.maps.GeocoderStatus.UNKNOWN_ERROR)
        {
          alert("Geocoder error, try again later");
          $('#uuMapModule #loadmyGPS').css('opacity', 0);
        } 
        else
        {
          alert("Geocoder: unknown error");
          $('#uuMapModule #loadmyGPS').css('opacity', 0);
        }
      });    
    }
  }
}

//Function for opening the info window and display, depending on the marker type, address, businfo and title
function openInfoWindow(Title, infomarker, infowindow, Address,type, busstation)
{
  return function()
  {    
    var x;      
    if(type != "bus")
    {
      infowindow.setContent("<div id=\"infoWindow\"> <p id=\"infoWindowText\">" + Title + "<br/>Besöksadress: " + Address + "</p></div>");
      infowindow.open(map, infomarker);
    }
    else
    {
      infowindow.setContent("<div id=\"infoWindow\"> <p id=\"infoWindowText\">" + Title + "<br/>Bussar: "+ "<a target=\"_blank\" href=\"http://www.ul.se/sv/resw/?to=&from="+ busstation+ "\">"+Address+"</a></p></div>");
      infowindow.open(map, infomarker);
    }

  }
}

//Function for retrieving the users position by W3C's Geolocation
//It uses a callback function when finished.
//If the browser does not support Geolocation the user will be placed in siberia (googles idea)
function getMyLocation(success, fail) {
  var initialLocation;
  var siberia = new google.maps.LatLng(60, 105);
  // Try W3C Geolocation
  if (navigator.geolocation) {    
    navigator.geolocation.getCurrentPosition(success, fail, {
      enableHighAccuracy: true,
      maximumAge:60
    });
  }
  // Browser doesn't support Geolocation
  else {
    alert("Your brower doesn't support GeoLocation so you have been placed in Siberia");
    map.setCenter(siberia);
  }
}

//Function for creating markers for different locations
function createMarker(markLocation, type) {
  var NewIcon;
  var Click=true;
  var zindex;
  if (type == "University") {
    Click=true;
    NewIcon = "../gfx/module/uumap/markers/uupoint.png";
    zindex:300;
  }
  else if (type == "MyPos") {
    Click = false;
    NewIcon = "";
    zindex=1000;
  }
  else if (type == "utn") {
    NewIcon = "../gfx/module/uumap/markers/utnpoint.png";
    zindex=100;
  }
  else if (type == "nation") {
    NewIcon = "../gfx/module/uumap/markers/nation.png";
    zindex=7;
  }
  else if (type == "bus"){
    NewIcon = "../gfx/module/uumap/markers/buspoint.png";
    zindex=1;
  }
  else
  {
    NewIcon = "";
    zindex=1000;
  }
  var tempMarker = new google.maps.Marker({
    clickable:Click,
    position: markLocation,
    map: map,
    icon: NewIcon,
    visible: false,
    zIndex:zindex
  });
  return tempMarker;
}

//Function for retrieving the directions from the users position to a campus
//if the route exists it will be displayed on the map. Else an error message will be shown.
//For the moment, Travel-type BYCICLING does not exist in sweden.
function GetDirection(myposition) {
  directionsR.setMap(map);
  var start = myposition;
  var end = marker.getPosition();
  var RouteType = $("#uuMapModule #RouteType").val();
  var request = {
    origin: start,
    destination: end,
    travelMode: google.maps.DirectionsTravelMode[RouteType]
  }
  directionsS.route(request, function (result, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      directionsR.setDirections(result);
      marker.setVisible(false);
      myMarker.setVisible(false);      
    }
    else if (status == google.maps.DirectionsStatus.ZERO_RESULTS)
    {
      alert("No directions avaible for these two locations with travel-type: " + RouteType);
    } 
    else if (status == google.maps.DirectionsStatus.UNKNOWN_ERROR)
    {
      alert("DirectionsService error, try again later");
    }
    else
    {
      alert("DirectionsService: unknown error");
    }

  });
  $('#uuMapModule #loadmyGPS').css('opacity', 0);
}

$(document).ready(function () {

  //declearing variables
  var uuMarkers = new Array();
  var naMarkers = new Array();
  var busMarkers = new Array();
  var utnMarkers = new Array();
  var Address = new Array();
  var coordinates = new Array();
  var CampusMaps = new Array();
  var Description = new Array();
  var buslines = new Array();
  var service = new Array();
  var servicelist;
  var buslist;
  var x;
  var locations;
  var AddressField = "Skriv in din adress här";
  var fieldtext;
  var defaultZoom = 12; //Zoom when the map i loaded
  var markerZoom = 15; //Zoom when a location is choosen

  //Map options
  var defaultOpt = {
    zoom: defaultZoom,
    center: defaultLocation,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  var infowindow = new google.maps.InfoWindow(
    {
      MaxWidth:50 //this options doesn't work for the moment. Hopefully google will fix it, but they haven't in the last 2 years...
    });

    defaultLocation = new google.maps.LatLng(59.858100, 17.644000); //sets Uppsala as default location
    //Create Map
    map = new google.maps.Map(document.getElementById("map_canvas"), defaultOpt);

    //Create the two objects nessecary for retrieving Directions and a Geocoder for retrieving coord. from adresses
    directionsR = new google.maps.DirectionsRenderer();
    directionsS = new google.maps.DirectionsService();
    //Loading XML-document
    $.get("module/uumap/uumap.xml", function(data, status, xhr){
      doc = xhr.responseXML;
      locations = doc.getElementsByTagName("location");
      //this is a callbackfunction for geolocation, if we get a positive result the following will happen:
      var success = function (position) {
        directionsR.setMap(null);// I have to have this, else the directions somehow locks if I press the directionsbutton twice
        if (directionsR.getMap() == null) {
          var initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
          map.setCenter(initialLocation);
          myMarker.setPosition(initialLocation);
          myMarker.setVisible(true);
          $('#uuMapModule #loadmyGPS').css('opacity', 0);
        }
      }
      //this is a callbackfunction for geolocation when we want directions to a campus, if we get a positive result following will happen:
      var successDirection = function (position) {
        directionsR.setMap(null); // I have to have this, else the directions somehow locks if I press the directionsbutton twice
        if (directionsR.getMap() == null) {
          var initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
          map.setCenter(initialLocation);
          myMarker.setPosition(initialLocation);
          myMarker.setVisible(false);
          GetDirection(myMarker.getPosition());
          if(uuMarkers[ID] != null)
          {
            for(x in uuMarkers)
            {
              uuMarkers[x].setVisible(true);
            }
            uuMarkers[ID].setVisible(false);
          }
          else
          {
            alert("fel");
            
            for(x in naMarkers)
            {
              naMarkers[x].setVisible(true);
            }
            naMarkers[ID].setVisible(false);
          }
        }
      }
      //this is a callbackfunction for geolocation, if we get a negative result following will happen:
      var fail = function () {
        alert("Geolocation service fail");
        map.setCenter(defaultLocation);
        map.setZoom(defaultZoom);
        $('#uuMapModule #loadmyGPS').css('opacity', 0);    
      }
      //Creating all markers for the different locations
      //Each location type have a specific marker icon
      $(locations).each(function(){
        var type = $(this).find("type").first().text(); 
        buslist = $(this).find("bus");
        servicelist = $(this).find("service");
        temp = $(this).find("alias").first().text();
        coordinates[temp] = new google.maps.LatLng($(this).find("lat").text(), $(this).find("lng").text());
        Address[temp] = $(this).find("Address").first().text();
        Description[temp] = $(this).find("desc").first().text();
        CampusMaps[temp] = $(this).find("cmap").first().text();
        buslines[temp] = new Array();
        service[temp] = new Array();
        var i = 0;
        $(servicelist).each(function(){
          service[temp][i] = $(this).text();
          i=i+1;
        });
        i=0;
        $(buslist).each(function(){
          buslines[temp][i] = $(this).text();
          i=i+1;
        });
        if(type=="uu")
        {
          uuMarkers[temp] = createMarker(coordinates[temp], "University");
          google.maps.event.addListener(uuMarkers[temp], 'click', openInfoWindow($(this).find("title").first().text(), uuMarkers[temp], infowindow,Address[temp],type));
          uuMarkers[temp].setVisible(true);
        }
        else if(type=="nation")
        {
          naMarkers[temp] = createMarker(coordinates[temp], "nation");
          google.maps.event.addListener(naMarkers[temp], 'click', openInfoWindow($(this).find("title").first().text(), naMarkers[temp], infowindow,Address[temp],type));
          naMarkers[temp].setVisible(false);
        }
        else if(type=="utn")
        {
          utnMarkers[temp] = createMarker(coordinates[temp], "utn");
          google.maps.event.addListener(utnMarkers[temp], 'click', openInfoWindow($(this).find("title").first().text(), utnMarkers[temp], infowindow,Address[temp],type));
          utnMarkers[temp].setVisible(true);
        }
        else if(type == "bus")
        {
          busMarkers[temp] = createMarker(coordinates[temp], "bus");
          google.maps.event.addListener(busMarkers[temp], 'click', openInfoWindow($(this).find("title").first().text(), busMarkers[temp], infowindow,buslines[temp],type,$(this).find("ulname").first().text()));
          busMarkers[temp].setVisible(false);
        }
        else
        {
          //put another marker type here
        }
      });
      uuMarkers['SLU'].setIcon('../gfx/module/uumap/markers/slu.png'); //I'm setting the icon manually because there's only one place that is interesting for Teknat Students
      myMarker = createMarker(defaultLocation, "MyPos");
      marker = createMarker(defaultLocation, "");
      
      //This function handles a bug with googlemaps that appear when resizing divs containging map_canvas
      $("#uumap .togglerbutton").click(function () {
        google.maps.event.trigger(map, 'resize');
        map.setCenter(defaultLocation);
      });
      
      //This Function will move the different
      $("#uuMapModule #Locations").change(function () {
        //Reseting all other markers, windows, information and campus maps

        $("#uuMapModule #CampusListText").remove();
        $("#uuMapModule #CampusOverview").css("display","none");        
        $("#uuMapModule #Information").css("display","none");
        infowindow.close();
        var ID = $("#uuMapModule #Locations option:selected").val();
        myMarker.setVisible(false);
        marker.setVisible(true);
        directionsR.setMap(null);
        map.setCenter(coordinates[ID]);
        marker.setPosition(coordinates[ID]);
        $("#uuMapModule #Information #Address").html("<span class=\"bold\">Besöksadress: </span> " + Address[ID]);
        $("#uuMapModule #Information > #Description").html("");
        $("#uuMapModule #Information > #ServiceList").html("");
        //Show descriptions or services if the selected location have any 
        if(Description[ID] != null)
        {
          if(uuMarkers[ID] == null)
          {
            $("#uuMapModule #Information").animate({opacity:1});
          }
          $("#uuMapModule #Information > #Description").html("<span class=\"bold\">Beskrivning: </span>" + Description[ID]);
        }
        if(service[ID] != null)
        {
          $("#uuMapModule #Information #ServiceList").append("<p class=\"bold\">Här finns:</p> ");
          $("#uuMapModule #Information #ServiceList").append("<div class=\"list\"><ul>");
          var j = 0;      
          for(x in service[ID])
          {
            $("#uuMapModule #Information #ServiceList .list:last ul").append("<li>" +service[ID][x] +"</li>");
            j=j+1;
            if(j%5 == 0)
            {
              $("#uuMapModule #Information #ServiceList").append("</ul></div><div class=\"list\"><ul>");
            }

          }
          if(j%5 == 0)
          {
            $("#uuMapModule #Information #ServiceList").append("</div></ul>");
          }
        }
        
        //Reset show/hide markers
        if(uuMarkers[ID] != null)
        {
          if($("#uuMapModule #uuMarkers").attr('checked'))
          {
            for(x in uuMarkers)
            {
              uuMarkers[x].setVisible(true);
            }
          }
          uuMarkers[ID].setVisible(false);
          if(CampusMaps[ID] != null && CampusMaps[ID] !="")
          {
            $("#uuMapModule #CampusOverview").css('display', 'block');
            $("#uuMapModule #Information").css("display","block");
            $("#uuMapModule #CampusOverview div").html("<img src='../gfx/module/uumap/cmaps/" + CampusMaps[ID] + "'/>");
          }
        }
        else if(utnMarkers[ID] != null)
        {
          if($("#uuMapModule #utnMarkers").attr('checked'))
          {
            for(x in utnMarkers)
            {
              utnMarkers[x].setVisible(true);
            }
          }          utnMarkers[ID].setVisible(false);
        }
        else
        { 
          if($("#uuMapModule #naMarkers").attr('checked'))
          {
            for(x in naMarkers)
            {
              naMarkers[x].setVisible(true);
            }
          }
          naMarkers[ID].setVisible(false);
        }
        map.setZoom(markerZoom);
        google.maps.event.trigger(map, 'resize');
        map.setCenter(marker.getPosition());
        var target = $("#uumap > div");
        var height = target.children(":first").height();
        target.height(height+5);
      });
      //This is for the address field, its displayed when the addressfield is empty and unselected 
      $("#uuMapModule #DirectionsAddress").focus(function()
      {
        $("#uuMapModule #DirectionsAddress").val("");
        $("#uuMapModule #DirectionsAddress").css('color','black');
      });
      $("#uuMapModule #DirectionsAddress").blur(function()
      {
        if($("#uuMapModule #DirectionsAddress").val()=="")
        {
          $("#uuMapModule #DirectionsAddress").val(AddressField);
          $("#uuMapModule #DirectionsAddress").css('color','#b5b5b5');
        }
      });

      //This function will hide and show markers of a specific type
      $("#uuMapModule .ShowHide").change(function () {
        infowindow.close();
        var ID = $(this).attr('id');
        var checked = $(this).attr('checked');
        if(ID == "uuMarkers")
        {
          for(x in uuMarkers)
          {
            if(checked)
            {
              if(uuMarkers[x].getPosition() != marker.getPosition())
              {
                uuMarkers[x].setVisible(true);
              }
            }
            else
            {
              uuMarkers[x].setVisible(false);
            }	
          } 
        }
        else if(ID == "busMarkers")
        {
          for(x in busMarkers)
          {
            if(checked)
            {
              busMarkers[x].setVisible(true);
            }
            else
            {
              busMarkers[x].setVisible(false);
            }
          }
        }
        else if(ID == "utnMarkers")
        {
          for(x in utnMarkers)
          {
            if(checked)
            {
              utnMarkers[x].setVisible(true);
            }
            else
            {
              utnMarkers[x].setVisible(false);
            }
          }
        }
        else{
          for(x in naMarkers)
          {
            if(checked)
            {
              if(naMarkers[x].getPosition() != marker.getPosition())
              {
                naMarkers[x].setVisible(true);
              }
            }
            else
            {
              naMarkers[x].setVisible(false);
            }

          }
        }

      });
      //Click-event for obtaining your location by W3C's GeoLocation 
      //when pressing Geolocation-button
      $("#uuMapModule #GetMyGPS").click(function () {
        infowindow.close();
        $('#uuMapModule #loadmyGPS').css('opacity', 100);
        directionsR.setMap(null);
        marker.setVisible(false);
        getMyLocation( success, fail);
      });
      //This function will update the directions when route-type is changed
      $("#uuMapModule #RouteType").change(function () 
      {
        infowindow.close();
        if(myMarker.getPosition() != defaultLocation)
        {
          GetDirection(myMarker.getPosition());
        }
      });
      //When submitting while the addressfield
      //this function will call the Geocoding function
      $("#uuMapModule form").submit(function()
      {
        infowindow.close();
        Geocoding(successDirection, fail, true,  $("#uuMapModule #DirectionsAddress").val());
        return false;    
      });
      //Click event for retrieving directions by W3C's Geolocation
      //or using the address
      //It is activated when pressing the AddressDirections button or
      //the GeolocatorDirections button 
      $("#uuMapModule .Directions").click(function() 
      {
        infowindow.close();
        if($(this).attr('id')=='DirectionsGeolocater')
        {
          Geocoding( successDirection, fail, false);
        }
        else
        {
          if($("#uuMapModule #DirectionsAddress").val() == AddressField)
          {
            Geocoding( successDirection, fail, true,  "");
          }

          else
          {
            Geocoding( successDirection, fail, true,  $("#uuMapModule #DirectionsAddress").val());
          }    
        }

      });
    }); 
  });