<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<!-- module imports -->
    <xsl:import href="../module/statictext/statictext.xsl" />
    <xsl:import href="../module/about/about.xsl" />
    <xsl:import href="../module/feed/feed.xsl" />
    <xsl:import href="../module/uumap/uumap.xsl" />
    <xsl:import href="../module/timeedit/timeedit.xsl" />
    <xsl:import href="../module/empsearch/empsearch.xsl" />
    <xsl:import href="../module/multifeed/multifeed.xsl" />


    <xsl:output method="html" encoding="utf-8" omit-xml-declaration="yes" />
    <xsl:template match="/">
        <xsl:choose>
            <xsl:when test="/root/ajax">
                <xsl:apply-templates select="root/ajax" />
            </xsl:when>
            <xsl:otherwise>
                <xsl:apply-templates select="root" />
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template match="root/title">
        <xsl:value-of select="." />
    </xsl:template>

    <xsl:template match="root">
        <xsl:apply-imports />

        <xsl:text disable-output-escaping="yes">&lt;!DOCTYPE html&gt;
        </xsl:text>
        <html lang="se-SV">
            <head>
                <meta name="keywords" content="Uppsala universitet, teknisk-naturvetenskapliga fakulteten, student, mobilwebb, schema, sök personal, sök student"/>
    <!-- <meta charset="UTF-8" /> -->
                <link rel="stylesheet" type="text/css" href="css/reset.css" media="all" />
                <link rel="stylesheet" type="text/css" href="css/module/statictext.css" media="all" />
                <link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
                <link rel="stylesheet" type="text/css" href="css/layout.css" media="all" />
                <link rel="stylesheet" type="text/css" href="css/module/multifeed.css" media="all" />
                
                <link rel="stylesheet" type="text/css" href="css/module/uumaps.css" media="all" />
                <link rel="stylesheet" type="text/css" href="css/module/timeedit.css" media="all" />
                <link rel="stylesheet" type="text/css" href="css/module/feed.css" media="all" />
                <link rel="stylesheet" type="text/css" href="css/module/about.css" media="all" />
                <link rel="stylesheet" type="text/css" href="css/module/empsearch.css" media="all" />

                <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
                <script type="text/javascript" src="js/main.js"></script>
                <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
                <script type="text/javascript" src="js/empsearch.js"></script>
                <script type="text/javascript" src="js/multifeed.js"></script>
                <script type="text/javascript" src="js/timeedit.js"></script>

                <meta name="viewport" content="width=device-width" />
                <title>
                    <xsl:apply-templates select="./title" />
                </title>
            </head>
            <body>
                <div id="content">
                    <a href="/">
                        <img id="logo" src="./gfx/logo.png" />
                    </a>
    <!-- <img class="divider" src="./gfx/dot.png" /> -->

                    <xsl:for-each select="./section|toggler|teaser">
                        <img class="divider" src="./gfx/dot.png" />
                        <xsl:apply-templates select="." />
                    </xsl:for-each>
                </div>
            </body>
        </html>
    </xsl:template>

</xsl:stylesheet>
