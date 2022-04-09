<?xml version="1.0" encoding="UTF-8"?>
<!-- Jens -->
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">


    <xsl:template match="/" mode="reservierungen-header">
        <div class="content-header">
            <div class="header">
                Reservierungen
            </div>
        </div>
    </xsl:template>

    <xsl:template match="/" mode="reservierungen-content">
        <div class="table-header border">
            <p class="extra-small" />
            <p class="collumn-small fat">
                Fahrtdatum
            </p>
            <p class="collumn-large fat">
                Anzahl Fahrten
            </p>
        </div>



        <xsl:for-each select="xml/reservierungen/day">

            <div class="table-row hoverable border">
                <a class="table-row-link" href="">                    <!-- /?site=linien&amp;id={LinienID}-->
                    <p class="extra-small"></p>
                    <p class="collumn-small">
                        <xsl:value-of select="date" />
                    </p>
                    <p class="collumn-large">
                        <xsl:value-of select="sum" />
                    </p>
                    <p class="empty-right-header"></p>
                </a>
            </div>
        </xsl:for-each>

    </xsl:template>

</xsl:stylesheet>