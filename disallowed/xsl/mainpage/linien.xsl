<?xml version="1.0" encoding="UTF-8"?>
<!-- Jens -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">


    <xsl:template match="/" mode="linien-header">
        <div class="content-header">
            <div class="header">
                Linien
            </div>
        </div>
    </xsl:template>

    <xsl:template match="/" mode="linien-content">
        <div class="content-body">


            <div class="linien-table-header">
                <p class="empty-left"></p>
                <p class="collumn-small fat">
                    Zugnummer
                </p>
                <p class="collumn-large fat">
                    Startbahnhof
                </p>
                <p class="collumn-large fat">
                    Endbahnhof
                </p>
                <p class="collumn-medium fat">
                    Startzeit
                </p>
                <p class="empty-right-header"></p>
            </div>

            <xsl:for-each select="xml/linien">
                <div class="linien-table-row">
                    <a class="linien-table-row-link" href="/?site=linie?id={LinienID}">
                        <p class="empty-left"></p>
                        <p class="collumn-small">
                            <xsl:value-of select="Zugnummer" />
                        </p>
                        <p class="collumn-large">
                            <xsl:value-of select="From" />
                        </p>
                        <p class="collumn-large">
                            <xsl:value-of select="TO" />
                        </p>
                        <p class="collumn-medium">
                            <xsl:value-of select="Startzeit" />
                        </p>
                        <p class="empty-right-header"></p>
                    </a>
                </div>
            </xsl:for-each>


        </div>

    </xsl:template>

</xsl:stylesheet>
