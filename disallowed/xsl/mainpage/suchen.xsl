<?xml version="1.0" encoding="UTF-8"?>
<!-- Jens -->
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">


    <xsl:template match="/" mode="suchen-header">
        <div class="content-header">
            <div class="header">
                Suchen
            </div>
        </div>
    </xsl:template>

    <xsl:template match="/" mode="suchen-content">
        <div class="content-body">
            <form class="margin-bottom" method="get" action="?site=suchen">
                <datalist id="stations">
                    <xsl:for-each select="xml/bahnhofe">
                        <option value="{./Name}" />
                    </xsl:for-each>
                </datalist>
                <div>
                    <div class="table-row-link">
                        <p class="extra-small"></p>
                        <input class="input collumn-medium" type="text" name="sucheBahnhofA" list="stations" value="{//xml/suche/sucheBahnhofA}" placeholder="Bahnhof A" required="" />
                        <input class="input collumn-medium" type="text" name="sucheBahnhofB" list="stations" value="{//xml/suche/sucheBahnhofB}" placeholder="Bahnhof B" required="" />
                        <p class="m-small text-right">Abfahrtszeit:⠀</p>
                        <input class="input collumn-medium" type="time" name="timeBahnhofA" value="00:00" placeholder="HH:MM" required="" />
                        <p class="extra-small"></p>
                    </div>
                    <div class="table-row-link">
                        <p class="extra-small"></p>
                        <button class="collumn-medium" type="submit" name="site" value="suchen">suchen</button>
                        <p class="collumn-extra-large"></p>
                    </div>
                </div>

            </form>
            <div class="border-top-bottom margin-bottom-small">
                <div class="header">
                    schnellste Verbindung
                </div>
            </div>
            <div>
                <xsl:if test="not(xml/routes/route/node)">
                    <p>⠀⠀leider konnte im angegebenen Zeitraum keine Verbindung gefunden werden...</p>
                </xsl:if>
                <xsl:if test="xml/routes/route/node">
                    <xsl:for-each select="xml/routes/route">
                        <div class="table-row border">

                            <form action="/" method="get">
                                <input type="hidden" name="sucheBahnhofA" value="{/xml/suche/sucheBahnhofA}" />
                                <input type="hidden" name="sucheBahnhofB" value="{/xml/suche/sucheBahnhofB}" />
                                <input type="hidden" name="timeBahnhofA" value="{/xml/suche/timeBahnhofA}" />
                                <input type="hidden" name="reservieren" value="true" />
                                <input type="hidden" name="site" value="suchen" />
                                <div class="reservieren-form">
                                    <button class="reservieren-button" type="submit">
                                        <div class="menu-button">
                                            <img class="menu-button-icon" src="frontend/res/icon-reservierungen.png" />
                                            <div class="menu-button-text">
                                                reservieren
                                            </div>
                                        </div>
                                    </button>
                                    <input class="reservieren-input" type="date" name="datum" required=""/>
                                    <p class="collumn-medium">⠀⠀</p>
                                </div>
                            </form>

                            <xsl:for-each select="node">
                                <div class="table-row-link border">
                                    <div class="extra-extra-small"></div>
                                    <div class="extra-small">
                                        <xsl:choose>
                                            <xsl:when test="stoptype='DEPARTING'">
                                                <img class="menu-button-icon" src="frontend/res/icon-oben-rechts.png" />
                                            </xsl:when>
                                            <xsl:when test="stoptype='ARRIVING'">
                                                <img class="menu-button-icon" src="frontend/res/icon-unten-links.png" />
                                            </xsl:when>
                                        </xsl:choose>
                                    </div>
                                    <div class="collumn-medium">
                                        <xsl:value-of select="linie"></xsl:value-of>
                                    </div>
                                    <div class="collumn-large">
                                        <xsl:value-of select="station"></xsl:value-of>
                                    </div>
                                    <div class="collumn-large">
                                        <xsl:value-of select="time"></xsl:value-of>
                                    </div>
                                </div>
                            </xsl:for-each>
                        </div>
                    </xsl:for-each>
                </xsl:if>

            </div>
        </div>

    </xsl:template>

</xsl:stylesheet>