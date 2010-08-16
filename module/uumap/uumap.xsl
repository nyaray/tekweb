<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html" encoding="utf-8" omit-xml-declaration="yes" />

  <xsl:template match="section/uumap">
    <div>
      <xsl:attribute name="id"><xsl:value-of select="./name" /></xsl:attribute>
      <xsl:attribute name="class">section</xsl:attribute>
      <h1><xsl:value-of select="./head" /></h1>
      <p><xsl:value-of select="./body" /></p>
    </div>
  </xsl:template>

  <xsl:template match="toggler/uumap">
    <div>
      <xsl:attribute name="id"><xsl:value-of select="./name" /></xsl:attribute>
      <xsl:attribute name="class">toggler</xsl:attribute>
      <a>
        <xsl:attribute name="href">?page=<xsl:value-of select="./name" /></xsl:attribute>
        <xsl:attribute name="class">togglerbutton</xsl:attribute>
        <xsl:element name="img">
          <xsl:attribute name="class">togglericon</xsl:attribute>
          <xsl:attribute name="src"><xsl:value-of select="./icon" /></xsl:attribute>
        </xsl:element>
        <span class="togglerbuttontext">
          <xsl:value-of select="./head" />
        </span>
      </a>
      <div>
        <xsl:attribute name="class">togglercontent</xsl:attribute>
        <xsl:attribute name="class">hidden</xsl:attribute>
        <div>
          <xsl:attribute name="class">togglercontentbody</xsl:attribute>
          <div>
            <xsl:attribute name="id">uuMapModule</xsl:attribute>
            <div>
              <div>
                <xsl:attribute name="id">MyMap</xsl:attribute>
                <div>
                  <xsl:attribute name="class">MarkerWrapper</xsl:attribute>
                  <label>
                    <xsl:attribute name="class">MarkerCheckBox</xsl:attribute>
                    <xsl:attribute name="for">uuMarkers</xsl:attribute>
                    <img>
                      <xsl:attribute name="class">MarkerCheckBox</xsl:attribute>
                      <xsl:attribute name="src">./gfx/module/uumap/markers/uupoint.png</xsl:attribute>
                    </img>
                  </label>
                  <input type="checkbox">
                    <xsl:attribute name="id">uuMarkers</xsl:attribute>
                    <xsl:attribute name="class">ShowHide</xsl:attribute>
                    <xsl:attribute name="checked">checked</xsl:attribute>
                  </input>
                </div>
                <div>    
                  <xsl:attribute name="class">MarkerWrapper</xsl:attribute>
                  <label>
                    <xsl:attribute name="class">MarkerCheckBox</xsl:attribute>
                    <xsl:attribute name="for">utnMarkers</xsl:attribute>
                    <img>
                      <xsl:attribute name="class">MarkerCheckBox</xsl:attribute>
                      <xsl:attribute name="src">./gfx/module/uumap/markers/utnpoint.png</xsl:attribute>
                    </img>
                  </label>
                  <input type="checkbox">
                    <xsl:attribute name="id">utnMarkers</xsl:attribute>
                    <xsl:attribute name="class">ShowHide</xsl:attribute>
                    <xsl:attribute name="checked">checked</xsl:attribute>
                  </input>
                </div>
                <div>
                  <xsl:attribute name="class">MarkerWrapper</xsl:attribute>
                  <label>
                    <xsl:attribute name="class">MarkerCheckBox</xsl:attribute>
                    <xsl:attribute name="for">naMarkers</xsl:attribute>
                    <img>
                      <xsl:attribute name="class">MarkerCheckBox</xsl:attribute>
                      <xsl:attribute name="src">./gfx/module/uumap/markers/nation.png</xsl:attribute>
                    </img>
                  </label>                    
                  <input type="checkbox">
                    <xsl:attribute name="id">naMarkers</xsl:attribute>
                    <xsl:attribute name="class">ShowHide</xsl:attribute>
                  </input>
                </div>
                <div>    
                  <xsl:attribute name="class">MarkerWrapper</xsl:attribute>
                  <label>
                    <xsl:attribute name="class">MarkerCheckBox</xsl:attribute>
                    <xsl:attribute name="for">busMarkers</xsl:attribute>
                    <img>
                      <xsl:attribute name="class">MarkerCheckBox</xsl:attribute>
                      <xsl:attribute name="src">./gfx/module/uumap/markers/buspoint.png</xsl:attribute>
                    </img>
                  </label>
                  <input type="checkbox">
                    <xsl:attribute name="id">busMarkers</xsl:attribute>
                    <xsl:attribute name="class">ShowHide</xsl:attribute>
                  </input>
                </div>
                <div>
                  <xsl:attribute name="id">map_canvas</xsl:attribute>
                </div>
                <div>
                  <xsl:attribute name="id">ButtonContainer</xsl:attribute>
                  <a>
                    <xsl:attribute name="class">button</xsl:attribute>
                    <xsl:attribute name="id">GetMyGPS</xsl:attribute>
                    <xsl:value-of select="./map/labels/mygpsbutton"/>
                  </a>
                </div>
                <img>
                  <xsl:attribute name="id">loadmyGPS</xsl:attribute>
                  <xsl:attribute name="src">./gfx/load.gif</xsl:attribute>
                </img>
              </div>
              <div>
                <xsl:attribute name="id">SelectField</xsl:attribute>
                <select>
                  <xsl:attribute name="id">Locations</xsl:attribute>
                  <option>
                    <xsl:attribute name="selected">selected</xsl:attribute>
                    <xsl:attribute name="id">CampusListText</xsl:attribute>
                    --- <xsl:value-of select="./map/labels/campuslist" /> ---
                  </option>
                  <xsl:for-each select="./map/locationlist/location">
                    <xsl:sort select="./title"/>
                    <xsl:if test="type != 'bus' and type!='nation'" > 
                      <option>
                        <xsl:attribute name="value"><xsl:value-of select="./alias"/></xsl:attribute>
                        <xsl:value-of select="./title"/>
                      </option>
                    </xsl:if>
                  </xsl:for-each>
                </select>
                <!-- <select>
                  <xsl:attribute name="id">RouteType</xsl:attribute>
                  <option>
                    <xsl:attribute name="value">DRIVING</xsl:attribute>
                    Åka bil
                  </option>
                  <option>
                    <xsl:attribute name="value">BICYCLING</xsl:attribute>
                    Cykla
                  </option>
                  <option>
                    <xsl:attribute name="value">WALKING</xsl:attribute>
                    Gå
                  </option>
                </select> -->
              </div>    
            </div>
            <form>
              <fieldset>
                <xsl:attribute name="id">DirectionsField</xsl:attribute>
                <legend>Vägbeskrivning<select>
                  <xsl:attribute name="id">RouteType</xsl:attribute>
                  <option>
                    <xsl:attribute name="value">DRIVING</xsl:attribute>
                    Åka bil
                  </option>
                  <!-- <option>
                    <xsl:attribute name="value">BICYCLING</xsl:attribute>
                    Cykla
                  </option> -->
                  <option>
                    <xsl:attribute name="value">WALKING</xsl:attribute>
                    Gå
                  </option>
                </select></legend>
                <div>
                  <xsl:attribute name="class">AddressWrapper</xsl:attribute>
                  Från:
                  <input type="text">
                    <xsl:attribute name="id">DirectionsAddress</xsl:attribute>
                    <xsl:attribute name="value">Skriv in din adress här</xsl:attribute>
                  </input>
                </div>
                <div>
                  <xsl:attribute name="class">AddressWrapper ButtonContainer</xsl:attribute>
                  <a>
                    <xsl:attribute name="id">myAddress</xsl:attribute>
                    <xsl:attribute name="class">Directions button</xsl:attribute>
                    <xsl:value-of select="./map/labels/directionsAddress"/>
                  </a>
                </div>
                <div>
                  <xsl:attribute name="class">ButtonContainer</xsl:attribute>
                  <xsl:attribute name="id">GeolocatorContainer</xsl:attribute>
                  <a>
                    <xsl:attribute name="id">DirectionsGeolocater</xsl:attribute>
                    <xsl:attribute name="class">Directions button</xsl:attribute>
                    <xsl:value-of select="./map/labels/directionsbutton"/>
                  </a>
                </div>
              </fieldset>
              <fieldset>
                <xsl:attribute name="id">Information</xsl:attribute>
                <legend>Information</legend>
                <p>
                  <xsl:attribute name="id">Address</xsl:attribute>
                </p>
                <p>
                  <xsl:attribute name="id">Description</xsl:attribute>
                </p>
                <p>
                  <xsl:attribute name="id">Service</xsl:attribute>
                </p>
                <div>
                  <xsl:attribute name="id">ServiceList</xsl:attribute>
                </div>
              </fieldset>
              <fieldset>
                <xsl:attribute name="id">CampusOverview</xsl:attribute>
                <legend>Översikt Campus</legend>
                <div></div>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
  </xsl:template>
</xsl:stylesheet>