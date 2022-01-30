<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="utf-8" indent="yes" />

    <xsl:variable name="ContentXSL" select="block/xslcontent" />

    <xsl:include href="mainpage.xsl" />

    <xsl:template match="/">
        <xsl:text disable-output-escaping="yes">&lt;!DOCTYPE html&gt;</xsl:text>

        <html lang="en">
            <head>
                <meta charset="UTF-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />

                <link rel="stylesheet" href="css/base.css" />

                <title>#Jens</title>
            </head>
            <body>

                <header>
                    <div class="grid-container">
                        <img class="logo" src="res/logo.svg" alt="Deutsche Bahn Logo" />

                        <button class="header-button" value="Hauptmenü">Startseite</button>
                        <button class="header-button" action="" value="Timetable">Timetable</button>
                        <button class="header-button" action="" value="Login">Login</button>
                    </div>
                </header>

                <div class="content">
                    <xsl:call-template name="getcontent" />
                </div>

                <footer>
                    <h1>(C) Jens Rosenbauer und Paul Joachim</h1>
                </footer>

            </body>
        </html>

    </xsl:template>

    <xsl:template name="getcontent">
        <xsl:choose>
            <xsl:when test="$ContentXSL = 'mainpage'">
                <xsl:apply-templates select="/" mode="mainpage" />
            </xsl:when>
            <xsl:otherwise>
                <p>Error: No ContentXSL found!</p>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

</xsl:stylesheet>