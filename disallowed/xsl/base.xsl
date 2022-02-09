<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" id="start">
    <xsl:output method="html" encoding="utf-8" indent="yes" />

    <xsl:variable name="ContentXSL" select="xml/include_xsl" />

    <xsl:template match="/">
        <xsl:text disable-output-escaping="yes">&lt;!DOCTYPE html&gt;</xsl:text>

        <html lang="en">
            <head>
                <meta charset="UTF-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />

                <link rel="stylesheet" href="/backend/css/base.css" />

                <title>#Jens</title>
            </head>
            <body>

                <header>
                    <div class="grid-container">
                        <a class="logo" href="/">
                            <img class="logo" src="/backend/res/logo.svg" alt="Deutsche Bahn Logo" />
                        </a>

                        <xsl:choose>
                            <xsl:when test="xml/loggedin = '1'">
                                <a class="header-button" href="/profile">Profil</a>
                                <a class="header-button" href="/account/logout">Ausloggen</a>
                            </xsl:when>

                            <xsl:otherwise>
                                <a class="header-button" href="/account/login">Login</a>
                                <a class="header-button" href="/account/register">Registrieren</a>
                            </xsl:otherwise>
                        </xsl:choose>

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

</xsl:stylesheet>
