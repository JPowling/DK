<?xml version="1.0" encoding="UTF-8"?>
<!-- Jens -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="mo">

        <p>hallo</p>
        <div>
            <xsl:for-each select="xml/linien">
                <p><xsl:value-of select="LinienID"></xsl:value-of></p>
            </xsl:for-each>
        </div>

    </xsl:template>

</xsl:stylesheet>