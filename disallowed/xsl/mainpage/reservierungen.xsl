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
        <div class="content-body">


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
                <xsl:variable name="day" select="./date"></xsl:variable>
                <xsl:choose>
                    <xsl:when test="//detailed[traveldate=$day]">
                        <div class="table-row border-highlight highlight">
                            <a class="" href="/?site=reservierungen">
                                <div class="table-row hoverable border-opaque">
                                    <div class="table-row-link">
                                        <p class="extra-small"></p>
                                        <p class="collumn-small fat">
                                            <xsl:value-of select="date" />
                                        </p>
                                        <p class="collumn-large fat">
                                            <xsl:value-of select="sum" />
                                        </p>
                                        <p class="empty-right-header"></p>
                                    </div>
                                </div>
                            </a>


                            <div class="table-header border">
                                <p class="extra-small" />
                                <p class="collumn-small fat">
                                Linie
                                </p>
                                <p class="collumn-large fat">
                                Von
                                </p>
                                <p class="collumn-large fat">
                                Nach
                                </p>
                                <p class="collumn-medium fat">
                                Ab
                                </p>
                                <p class="collumn-medium fat">
                                An
                                </p>
                                <p class="collumn-medium fat">
                                Reserviert am
                                </p>
                                <p class="empty-right-header"></p>
                            </div>

                            <xsl:for-each select="/xml/reservierungen/detailes/detailed">
                                <div class="table-row hoverable border">
                                    <a class="table-row-link" href="/?site=bahnhofe">
                                        <p class="extra-small"></p>
                                        <p class="collumn-small">
                                            <xsl:value-of select="line" />
                                        </p>
                                        <p class="collumn-large">
                                            <xsl:value-of select="stationA" />
                                        </p>
                                        <p class="collumn-large">
                                            <xsl:value-of select="stationB" />
                                        </p>
                                        <p class="collumn-medium">
                                            <xsl:value-of select="timeA" />
                                        </p>
                                        <p class="collumn-medium">
                                            <xsl:value-of select="timeB" />
                                        </p>
                                        <p class="collumn-medium">
                                            <xsl:value-of select="orderdate" />
                                        </p>
                                        <p class="empty-right-header"></p>
                                    </a>
                                </div>
                            </xsl:for-each>


                        </div>
                    </xsl:when>
                    <xsl:otherwise>
                        <div class="table-row hoverable border">
                            <a class="table-row-link" href="/?site=reservierungen&amp;day={$day}">                                <!-- /?site=linien&amp;id={LinienID}-->
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
                    </xsl:otherwise>
                </xsl:choose>


            </xsl:for-each>
        </div>
    </xsl:template>

</xsl:stylesheet>