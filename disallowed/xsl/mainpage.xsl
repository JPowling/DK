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
                        <div class="menu-button">

                            <a href="/?site=linien">
                                <img class="icon" src="frontend/res/icon-linien.png" />
                                Linien
                            </a>
                        </div>

                        <div class="menu-button">
                            <a href="/?site=bahnofe">
                                <img class="icon" src="frontend/res/icon-linien.png" />
                                Bahnhöfe
                            </a>

                        </div>

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