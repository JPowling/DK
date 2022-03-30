<?xml version="1.0" encoding="UTF-8"?>
<!-- Paul -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:include href="lines.xsl" />
    <xsl:include href="routes.xsl" />
    <xsl:include href="stations.xsl" />

    <xsl:template match="/" mode="mode">
        <script src="/frontend/js/overview.js" />

        <div class="container-outer">
            <div class="container-inner">
                <h1 class="title">Moderation: Übersicht</h1>
                <div class="body">
                    <div class="navigator">
                        <xsl:apply-templates select="/" mode="navigator" />
                    </div>
                    <div class="overview-content">
                        <xsl:apply-templates select="/" mode="overview-content" />
                    </div>
                </div>
            </div>
        </div>

    </xsl:template>

    <xsl:template match="/" mode="navigator">
        <a class="navigator-button" href="/moderation/overview?view=f">
            <xsl:attribute name="id">
                <xsl:if test="xml/view = 'f'">
                    active
                </xsl:if>
            </xsl:attribute>
            Fahrzeuge
            <xsl:if test="xml/view = 'f'">
                <div class="navigator-active-marker" />
            </xsl:if>
        </a>
        <a class="navigator-button" href="/moderation/overview?view=b">
            <xsl:attribute name="id">
                <xsl:if test="xml/view = 'b'">
                    active
                </xsl:if>
            </xsl:attribute>
            Bahnhöfe
            <xsl:if test="xml/view = 'b'">
                <div class="navigator-active-marker" />
            </xsl:if>
        </a>
        <a class="navigator-button" href="/moderation/overview?view=r">
            <xsl:attribute name="id">
                <xsl:if test="xml/view = 'r'">
                    active
                </xsl:if>
            </xsl:attribute>
            Routen
            <xsl:if test="xml/view = 'r'">
                <div class="navigator-active-marker" />
            </xsl:if>
        </a>
        <a class="navigator-button" href="/moderation/overview?view=l">
            <xsl:attribute name="id">
                <xsl:if test="xml/view = 'l'">
                    active
                </xsl:if>
            </xsl:attribute>
            Linien
            <xsl:if test="xml/view = 'l'">
                <div class="navigator-active-marker" />
            </xsl:if>
        </a>
        <div class="navigator-spacer" />
    </xsl:template>

    <xsl:template match="/" mode="overview-content">
        <xsl:choose>
            <xsl:when test="xml/view = 'f'">
                <xsl:apply-templates select="/" mode="trains" />
            </xsl:when>
            <xsl:when test="xml/view = 'b'">
                <xsl:apply-templates select="/" mode="stations" />
            </xsl:when>
            <xsl:when test="xml/view = 'r'">
                <xsl:apply-templates select="/" mode="routes" />
            </xsl:when>
            <xsl:when test="xml/view = 'l'">
                <xsl:apply-templates select="/" mode="lines" />
            </xsl:when>
        </xsl:choose>
    </xsl:template>

</xsl:stylesheet>
