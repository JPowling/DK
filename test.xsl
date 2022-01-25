<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="utf-8" indent="no" />
    <xsl:template match="testdaten">   
    <xsl:text disable-output-escaping="yes">&lt;!DOCTYPE html&gt;</xsl:text>
        <html lang="de">
            <head>
                <link rel="stylesheet" href="main.css" />
                <title>testdaten</title>
            </head>
            <body>
                <h1>Testdaten von Paul</h1>
                <main>
                    <p>hier sind die Daten: </p>
                    <table>
                        <caption>Pauls Eigenschaften</caption>
                        <thead>
                            <tr>
                                <th>Eigenschaften</th>
                            </tr>
                        </thead>
                        <tbody>
                            <xsl:for-each select="testdaten">
                                <tr>
                                    <td>
                                        <xsl:value-of select="paulist" />
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