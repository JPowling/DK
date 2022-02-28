<?xml version="1.0" encoding="UTF-8"?>
<!-- Jens -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="mo">
        <div>
            <table>
                <xsl:for-each select="xml/linien">
                    <tr>
                        <td>
                            <xsl:value-of select="LinienID" />
                        </td>
                        <td>
                            <xsl:value-of select="Startzeit" />
                        </td>
                        <td>
                            <xsl:value-of select="ZuggattungsID" />
                        </td>
                    </tr>
                </xsl:for-each>
            </table>
        </div>

    </xsl:template>

</xsl:stylesheet>