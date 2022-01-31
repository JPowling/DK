<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="mainpage">
        <p><xsl:value-of select="xml/lustig"/></p>
    </xsl:template>

</xsl:stylesheet>