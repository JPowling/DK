<?xml version="1.0" encoding="UTF-8"?>
<!-- Jens -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:include href="mainpage/linien.xsl" />
    <xsl:include href="mainpage/bahnhofe.xsl" />
    <xsl:include href="mainpage/reservierungen.xsl" />
    <xsl:include href="mainpage/suchen.xsl" />

    <xsl:template match="/" mode="mode">
        <div class="outer-box">
            <div class="inner-box">

                <div class="menu-box">

                    <div class="menu-header">
                        <h2>Menü</h2>
                    </div>
                    <div class="menu-content">
                        <a style="display:block" href="/?site=linien">
                            <div class="menu-button">
                                <img class="menu-button-icon" src="frontend/res/icon-linien.png" />
                                <div class="menu-button-text">
                                    <p>
                                        Linien
                                    </p>
                                </div>
                            </div>
                        </a>
                        <a style="display:block" href="/?site=bahnhofe">
                            <div class="menu-button">
                                <img class="menu-button-icon" src="frontend/res/icon-bahnhofe.png" />
                                <div class="menu-button-text">
                                    <p>
                                        Bahnhöfe
                                    </p>
                                </div>
                            </div>
                        </a>
                        <a style="display:block" href="/?site=reservierungen">
                            <div class="menu-button">
                                <img class="menu-button-icon" src="frontend/res/icon-reservierungen.png" />
                                <div class="menu-button-text">
                                    <p>
                                        Reservierungen
                                    </p>
                                </div>
                            </div>
                        </a>
                        <a style="display:block" href="/?site=suchen">
                            <div class="menu-button">
                                <img class="menu-button-icon" src="frontend/res/icon-suchen.png" />
                                <div class="menu-button-text">
                                    <p>
                                        Suchen
                                    </p>
                                </div>
                            </div>
                        </a>

                    </div>

                </div>

                <div class="content-box">
                                        
                    <xsl:choose>
                        <xsl:when test="xml/site = 'linien'">
                            <xsl:apply-templates select="/" mode="linien-header"></xsl:apply-templates>
                        </xsl:when>
                        <xsl:when test="xml/site = 'bahnhofe'">
                            <xsl:apply-templates select="/" mode="bahnhofe-header"></xsl:apply-templates>
                        </xsl:when>
                        <xsl:when test="xml/site = 'reservierungen'">
                            <xsl:apply-templates select="/" mode="reservierungen-header"></xsl:apply-templates>
                        </xsl:when>
                        <xsl:when test="xml/site = 'suchen'">
                            <xsl:apply-templates select="/" mode="suchen-header"></xsl:apply-templates>
                        </xsl:when>
                        <xsl:otherwise></xsl:otherwise>
                    </xsl:choose>

                    <xsl:choose>
                        <xsl:when test="xml/site = 'linien'">
                            <xsl:apply-templates select="/" mode="linien-content"></xsl:apply-templates>
                        </xsl:when>
                        <xsl:when test="xml/site = 'bahnhofe'">
                            <xsl:apply-templates select="/" mode="bahnhofe-content"></xsl:apply-templates>
                        </xsl:when>
                        <xsl:when test="xml/site = 'reservierungen'">
                            <xsl:apply-templates select="/" mode="reservierungen-content"></xsl:apply-templates>
                        </xsl:when>
                        <xsl:when test="xml/site = 'suchen'">
                            <xsl:apply-templates select="/" mode="suchen-content"></xsl:apply-templates>
                        </xsl:when>
                        <xsl:otherwise></xsl:otherwise>
                    </xsl:choose>

                </div>

            </div>
        </div>

    </xsl:template>

</xsl:stylesheet>
