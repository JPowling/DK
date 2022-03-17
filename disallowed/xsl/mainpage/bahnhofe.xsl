<?xml version="1.0" encoding="UTF-8"?>
<!-- Jens -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="bahnhofe-header">
        <div class="content-header">
            <div class="header">
                Bahnhöfe
            </div>
        </div>
    </xsl:template>

    <xsl:template match="/" mode="bahnhofe-content">
        <div class="content-body">
            <div class="table-header">
                <p class="empty-left" />
                <p class="collumn-medium fat">
                    Bahnhof
                </p>
                <p class="collumn-large fat">
                    Gleise
                </p>
            </div>

            <xsl:for-each select="xml/bahnhofe">
                <div class="table-row">
                    <a class="table-row-link" href="/?site=linie?id={LinienID}">
                        <p class="empty-left" />
                        <p class="collumn-medium">
                            <xsl:value-of select="Name" />
                        </p>
                        <p class="collumn-large">
                            <xsl:value-of select="Gleise" />
                        </p>
                    </a>
                </div>
            </xsl:for-each>
        </div>


    </xsl:template>

</xsl:stylesheet>