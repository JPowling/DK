<?xml version="1.0" encoding="UTF-8"?>
<!-- Jens -->
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">


    <xsl:template match="/" mode="linien-header">
        <div class="content-header">
            <div class="header">
                Linien
            </div>
        </div>
    </xsl:template>

    <xsl:template match="/" mode="linien-content">
        <xsl:apply-templates select="/" mode="linien"></xsl:apply-templates>

    </xsl:template>


    <xsl:template match="/" mode="linien">
        <div class="content-body">
            <div class="table-header border">
                <p class="extra-small" />
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
                <xsl:variable name="LinienID" select="./LinienID"></xsl:variable>
                <xsl:choose>
                    <xsl:when test="//linie[id=$LinienID]">
                        <div class="table-row border">
                            <div>
                                <a class="" href="/?site=linien">
                                    <div class="table-row hoverable border-opaque">
                                        <div class="table-row-link">
                                            <p class="extra-small"></p>
                                            <p class="collumn-small fat">
                                                <xsl:value-of select="Zugnummer" />
                                            </p>
                                            <p class="collumn-large fat">
                                                <xsl:value-of select="From" />
                                            </p>
                                            <p class="collumn-large fat">
                                                <xsl:value-of select="TO" />
                                            </p>
                                            <p class="collumn-medium fat">
                                                <xsl:value-of select="Startzeit" />
                                            </p>
                                            <p class="empty-right-header"></p>
                                        </div>
                                    </div>
                                </a>
                            </div>


                            <div class="table-header border">
                                <p class="extra-small" />
                                <p class="collumn-small fat">
                                    Halt
                                </p>
                                <p class="collumn-large fat">
                                    Bahnhof
                                </p>
                                <p class="collumn-large fat">
                                    Ankunftszeit
                                </p>
                                <p class="collumn-medium fat">
                                    Abfahrtszeit
                                </p>
                                <p class="empty-right-header"></p>
                            </div>
                            <xsl:for-each select="//linien[LinienID=$LinienID]/haltestelle">
                                <div class="table-row hoverable border">
                                    <a class="table-row-link" href="/?site=bahnhofe">
                                        <p class="extra-small"></p>
                                        <p class="collumn-small">
                                            <xsl:value-of select="Nummer" />
                                        </p>
                                        <p class="collumn-large">
                                            <xsl:value-of select="Bahnhof" />
                                        </p>
                                        <p class="collumn-large">
                                            <xsl:value-of select="Ankunftszeit" />
                                        </p>
                                        <p class="collumn-medium">
                                            <xsl:value-of select="Abfahrtszeit" />
                                        </p>
                                        <p class="empty-right-header"></p>
                                    </a>
                                </div>
                            </xsl:for-each>
                        </div>



                    </xsl:when>

                    <xsl:otherwise>
                        <div class="table-row hoverable border">
                            <a class="table-row-link" href="/?site=linien&amp;id={LinienID}">
                                <p class="extra-small"></p>
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
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:for-each>
        </div>
    </xsl:template>


</xsl:stylesheet>