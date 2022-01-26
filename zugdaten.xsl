<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="utf-8" indent="no" />
    <xsl:template match="/">
        <xsl:text disable-output-escaping="yes">&lt;!DOCTYPE html&gt;</xsl:text>
        <html lang="de">
            <head>
                <link rel="stylesheet" href="main.css" />
                <title>Zugdaten</title>
            </head>
            <body>
                <h1>Liste aller Züge mit Linie und nächster Station</h1>
                <main>
                    <table>
                        <caption>caption</caption>

                        <thead>
                            <tr>
                                <th>Bezeichnung</th>
                                <th>Linie</th>
                                <th>nächste Station</th>
                            </tr>
                        </thead>
                        <tbody>
                            <xsl:for-each select="zugdaten/zug">
                                <tr>
                                    <td>
                                        <xsl:value-of select="bezeichnung"/>
                                    </td>
                                    <td>
                                        <xsl:value-of select="linie"/>
                                    </td>
                                    <td>
                                        <xsl:value-of select="nachsterbahnhof"/>
                                    </td>
                                </tr>
                            </xsl:for-each>
                        </tbody>
                    </table>

                </main>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>