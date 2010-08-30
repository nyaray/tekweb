<?xml version="1.0"?>
<!-- <PROGRAM_NAME>
 Copyright (C) 2010 Magnus Söderling (magnus.soderling@gmail.com)

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.-->
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="utf-8" omit-xml-declaration="yes" />

    <xsl:template match="section/empsearch">
        <div class="section empsearchmodule">
            <xsl:apply-templates select="form"/>
            <xsl:apply-templates select="message"/>
            <xsl:apply-templates select="employeelist[1]"/>
            <xsl:apply-templates select="nonexactmessage"/>
            <xsl:apply-templates select="employeelist[2]"/>
        </div>
    </xsl:template>

    <xsl:template match="toggler/empsearch">
        <div class="toggler empsearchmodule">
            <xsl:attribute name="id">
                <xsl:value-of select="name" />
            </xsl:attribute>
            <a class="togglerbutton">
                <xsl:attribute name="href">
                    <xsl:text>?page=</xsl:text>
                    <xsl:value-of select="name" />
                </xsl:attribute>
                <xsl:element name="img">
                    <xsl:attribute name="class">togglericon</xsl:attribute>
                    <xsl:attribute name="src">
                        <xsl:value-of select="./icon" />
                    </xsl:attribute>
                </xsl:element>
                <span class="togglerbuttontext">
                    <xsl:value-of select="head" />
                </span>
            </a>
            <div>
                <xsl:attribute name="class">hidden</xsl:attribute>
                <div>
                    <xsl:attribute name="class">togglercontentbody</xsl:attribute>
                </div>
            </div>
        </div>
    </xsl:template>

    <xsl:template match="ajax/empsearch">
        <div>
            <xsl:attribute name="class">togglercontentbody</xsl:attribute>
            <xsl:apply-templates select="form"/>
            <xsl:apply-templates select="ajaxform"/>
            <xsl:apply-templates select="message"/>
            <xsl:apply-templates select="employeelist[1]"/>
            <xsl:apply-templates select="nonexactmessage"/>
            <xsl:apply-templates select="employeelist[2]"/>
        </div>
    </xsl:template>

    <xsl:template match="ajaxform">
        <form class="empform">
            <xsl:attribute name="action">
                <xsl:value-of select="action"/>
            </xsl:attribute>
            <xsl:attribute name="method">
                <xsl:value-of select="method"/>
            </xsl:attribute>
            <fieldset>
                <legend>
                <xsl:value-of select="../head"/>
                </legend>
                <label>
                    <xsl:attribute name="for">
                        <xsl:text>str</xsl:text>
                        <xsl:value-of select="name"/>
                    </xsl:attribute>
                    <xsl:text>Namn</xsl:text>
                </label>
                <input id="searchstring" type="text">
                    <xsl:attribute name="id">
                        <xsl:text>str</xsl:text>
                        <xsl:value-of select="name"/>
                    </xsl:attribute>
                    <xsl:attribute name="name">
                        <xsl:value-of select="name"/>
                    </xsl:attribute>
                    <xsl:attribute name="value">
                        <xsl:value-of select="value"/>
                    </xsl:attribute>
                </input>
                <xsl:apply-templates select="ajaxempbutton"/>
            </fieldset>
        </form>
    </xsl:template>

    <xsl:template match="form">
        <form class="empform">
            <xsl:attribute name="action">
                <xsl:value-of select="action"/>
            </xsl:attribute>
            <xsl:attribute name="method">
                <xsl:value-of select="method"/>
            </xsl:attribute>
            <fieldset>
                <legend>Sök personal</legend>
                <label>
                    <xsl:attribute name="for">
                        <xsl:text>str</xsl:text>
                        <xsl:value-of select="name"/>
                    </xsl:attribute>
                    <xsl:text>Namn</xsl:text>
                </label>
                <input id="searchstring" type="text">
                    <xsl:attribute name="id">
                        <xsl:text>str</xsl:text>
                        <xsl:value-of select="name"/>
                    </xsl:attribute>
                    <xsl:attribute name="name">
                        <xsl:value-of select="name"/>
                    </xsl:attribute>
                    <xsl:attribute name="value">
                        <xsl:value-of select="value"/>
                    </xsl:attribute>
                </input>
                <xsl:apply-templates select="empbutton"/>
                <xsl:apply-templates select="page"/>
            </fieldset>
        </form>
    </xsl:template>

    <xsl:template match="empbutton">
        <input>
            <xsl:attribute name="value">
                <xsl:value-of select="value"/>
            </xsl:attribute>
            <xsl:attribute name="type">
                <xsl:value-of select="type"/>
            </xsl:attribute>
        </input>
    </xsl:template>

    <xsl:template match="ajaxempbutton">
        <div class="button">
            <a href="">
                <xsl:value-of select="value"/>
            </a>
        </div>
    </xsl:template>

    <xsl:template match="message">
        <p class="message">
            <xsl:value-of select="."/>
        </p>
    </xsl:template>

    <xsl:template match="nonexactmessage">
        <p class="message">
            <xsl:value-of select="."/>
        </p>
    </xsl:template>
    
    <xsl:template match="page">
        <input type="hidden" name="page">
            <xsl:attribute name="value">
                <xsl:value-of select="value"/>
            </xsl:attribute>
        </input>
    </xsl:template>

    <xsl:template match="employeelist">
        <ul class="employees">
            <xsl:for-each select="employee">
            <!-- "unsorted"(ÄÅÖ) same as uu.se-->
                <xsl:sort select="surname" lang="sv"/>
                <xsl:sort select="givenname" lang="sv"/>
                <li>
                    <b>
                        <xsl:value-of select="givenname"/>
                        <xsl:text>&#160;</xsl:text>
                        <xsl:value-of select="surname"/>
                    </b>
                    <ul>
                        <xsl:if test="titleatdep">
                            <li class="titleatdep">
                                <xsl:value-of select="titleatdep"/>
                            </li>
                        </xsl:if>
                        <xsl:if test="visitingaddress">
                            <li class="visitingaddress">
                                <b>
                                    <xsl:text>Besöksadress: </xsl:text>
                                </b>
                                <xsl:value-of select="visitingaddress"/>
                            </li>
                        </xsl:if>
                        <xsl:if test="roomnumber">
                            <li class="roomnumber">
                                <b>
                                    <xsl:text>RUM: </xsl:text>
                                </b>
                                <xsl:value-of select="roomnumber"/>
                            </li>
                        </xsl:if>
                        <xsl:if test="mail">
                            <li class="mail">
                                <a>
                                    <xsl:attribute name="href">
                                        <b>
                                            <xsl:text>mailto:</xsl:text>
                                        </b>
                                        <xsl:value-of select="mail"/>
                                    </xsl:attribute>
                                    <b>
                                        <xsl:text>E-post: </xsl:text>
                                    </b>
                                    <xsl:value-of select="mail"/>
                                </a>
                            </li>
                        </xsl:if>
                        <xsl:if test="phonenumber">
                            <li class="phonenumber">
                                <a>
                                    <xsl:attribute name="href">
                                        <xsl:text>tel:0</xsl:text>
                                        <xsl:value-of select="substring(phonenumber, 4)"/>
                                    </xsl:attribute>
                                    <b>
                                        <xsl:text>TEL: </xsl:text>
                                    </b>
                                    <xsl:text>0</xsl:text>
                                    <xsl:value-of select="substring(phonenumber, 4, 2)"/>
                                    <xsl:text>&#160;</xsl:text>
                                    <xsl:value-of select="substring(phonenumber, 6, 3)"/>
                                    <xsl:text>&#160;</xsl:text>
                                    <xsl:value-of select="substring(phonenumber, 9)"/>
                                </a>
                            </li>
                        </xsl:if>
                        <xsl:if test="mobilenumber">
                            <li class="phonenumber">
                                <a>
                                    <xsl:attribute name="href">
                                        <xsl:text>tel:0</xsl:text>
                                        <xsl:value-of select="substring(mobilenumber, 4)"/>
                                    </xsl:attribute>
                                    <b>
                                        <xsl:text>TEL: </xsl:text>
                                    </b>
                                    <xsl:text>0</xsl:text>
                                    <xsl:value-of select="substring(mobilenumber, 4, 2)"/>
                                    <xsl:text>&#160;</xsl:text>
                                    <xsl:value-of select="substring(mobilenumber, 6, 3)"/>
                                    <xsl:text>&#160;</xsl:text>
                                    <xsl:value-of select="substring(mobilenumber, 9)"/>
                                </a>
                            </li>
                        </xsl:if>
                        <xsl:if test="faxnumber">
                            <li class="faxnumber">
                                <b>
                                    <xsl:text>FAX: </xsl:text>
                                </b>
                                <xsl:text>0</xsl:text>
                                <xsl:value-of select="substring(faxnumber, 4, 2)"/>
                                <xsl:text>&#160;</xsl:text>
                                <xsl:value-of select="substring(faxnumber, 6, 3)"/>
                                <xsl:text>&#160;</xsl:text>
                                <xsl:value-of select="substring(faxnumber, 9)"/>
                            </li>
                        </xsl:if>
                    </ul>
                </li>
            </xsl:for-each>
        </ul>
    </xsl:template>
</xsl:stylesheet>
