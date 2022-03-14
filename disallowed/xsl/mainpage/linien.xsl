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

            <xsl:for-each select="xml/linien">

                <div class="linien-table-row">
                    <a class="linien-table-row-link" href="/?site=linie?id={LinienID}">
                        <p class="empty-left"></p>
                        <p class="linien-id-name">
                            <xsl:value-of select="LinienID" />
                        </p>
                        <p class="linien-start-time">
                            <xsl:value-of select="Startzeit" />
                        </p>
                        <p class="linien-train-type">
                            <xsl:value-of select="ZuggattungsID" />
                        </p>
                    </a>
                </div>

            </xsl:for-each>


        </div>

    </xsl:template>

</xsl:stylesheet>