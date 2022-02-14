<?xml version="1.0" encoding="UTF-8"?>
<!-- Paul -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="mode">
        <script src="/frontend/js/overview.js"></script>

        <div class="container-outer">
            <div class="container-inner">
                <h1 class="title">Moderation: Übersicht(STC)</h1>
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
                <xsl:apply-templates select="/" mode="fahrzeuge" />
            </xsl:when>
            <xsl:when test="xml/view = 'b'">
                <xsl:apply-templates select="/" mode="bahnhofe" />
            </xsl:when>
            <xsl:when test="xml/view = 'r'">
                <xsl:apply-templates select="/" mode="routen" />
            </xsl:when>
            <xsl:when test="xml/view = 'l'">
                <xsl:apply-templates select="/" mode="linien" />
            </xsl:when>
        </xsl:choose>
    </xsl:template>

    <xsl:template match="/" mode="fahrzeuge">
        <div class="content-parent">
            <div class="content-search">

                <p>
                    Fahrzeugnummer eingeben:
                    <br />
                    (Fokus auf Textbox verlieren)
                </p>
                <input list="trains" id="trains_select" onfocusout="trainsFocusOut()" onfocusin="trainsFocusIn()" value="{xml/id}" />
                <datalist id="trains">
                    <xsl:for-each select="xml/train">
                        <option value="{./id}" />
                    </xsl:for-each>
                </datalist>

                <p>
                    Oder:
                </p>
                <br/>

                <a class="navigator-button" href="/moderation/overview?view=f&amp;create=1">Neues Fahrzeug anlegen</a>
            </div>
            <div class="content-splitter" />
            <div class="content-result">

                <xsl:choose>
                    <xsl:when test="xml/selection">
                        <form action="/moderation/overview?view=f&amp;id={xml/id}" method="post">
                            <p>Sitzplätze:</p>
                            <input type="number" name="seats" value="{xml/selection/seats}"/>
                            <button type="submit" class="button input">Speichern</button>

                            <br/>
                            <br/>
                            <a href="/moderation/overview?view=f&amp;id={xml/id}&amp;delete=1" class="navigator-button delete">Löschen</a>
                        </form>
                    </xsl:when>
                    <xsl:otherwise>
                        <p>Es wurde kein Fahrzeug ausgewählt</p>
                    </xsl:otherwise>
                </xsl:choose>

            </div>
        </div>
    </xsl:template>
    <xsl:template match="/" mode="bahnhofe"></xsl:template>
    <xsl:template match="/" mode="routen"></xsl:template>
    <xsl:template match="/" mode="linien"></xsl:template>

</xsl:stylesheet>
