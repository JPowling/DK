<?xml version="1.0" encoding="UTF-8"?>
<!-- Jens -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:include href="/disallowed/xsl/mainpage/linien.xsl" />

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

                        <a style="display:block" href="/?site=bahnofe">
                            <div class="menu-button">
                                <img class="menu-button-icon" src="frontend/res/icon-bahnhof.png" />
                                <div class="menu-button-text">
                                    <p>
                                        Bahnhöfe
                                    </p>
                                </div>
                            </div>
                        </a>

                    </div>

                </div>

                <div class="content-box">
                    <p>
                        <xsl:value-of select="xml/site"></xsl:value-of>
                    </p>
                    <xsl:if test="xml/site = 'linien'">
                        <xsl:apply-templates select="/" mode="mo"></xsl:apply-templates>
                    </xsl:if>

                </div>

            </div>
        </div>

    </xsl:template>

</xsl:stylesheet>