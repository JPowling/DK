<?xml version="1.0" encoding="UTF-8"?>
<!-- Paul -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">


    <xsl:template match="/" mode="lines">
        <div class="content-parent">
            <div class="content-search">

                <input class="select_input" type="text" id="lines_select" onkeyup="search(lines_select)" placeholder="Suchen..." />
                <ul class="ul" id="lines_select_ul">
                    <xsl:for-each select="xml/lines">
                        <li class="ulli">
                            <a class="ula" href="/moderation/overview?view=l&amp;id={./id}">
                                <xsl:value-of select="./category"></xsl:value-of>
                                <xsl:value-of select="./id" />
                                : Route
                                <xsl:value-of select="./route" />
                                <br />
                                (
                                <xsl:value-of select="./start" />
                                -
                                <xsl:value-of select="./finish" />
                                )
                            </a>
                        </li>
                    </xsl:for-each>
                </ul>

                <p>
        Oder:
    </p>
                <br />

                <a class="navigator-button" href="/moderation/overview?view=l&amp;create=1">Neue Linie anlegen</a>
            </div>
            <div class="content-splitter" />
            <div class="content-result">

                <xsl:choose>
                    <xsl:when test="xml/selection">
                        <p class="bold">
                            Du betrachtest: Line mit Liniennummer
                            <xsl:value-of select="xml/id" />
                        </p>

                        <form action="/moderation/overview?view=l&amp;id={xml/id}" method="post">
                            <p>Route:⠀⠀Startzeit:⠀⠀⠀⠀⠀ Zuggattung:</p>
                            <select name="route" class="addmargin">
                                <xsl:for-each select="xml/routes">
                                    <option value="{./id}">
                                        <xsl:if test="./id = /xml/selection/route">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:value-of select="./id"></xsl:value-of>
                                    </option>
                                </xsl:for-each>
                            </select>
                            <input type="time" name="start" value="{xml/selection/start}" class="addmargin" />
                            <select name="category" class="addmargin">
                                <xsl:for-each select="xml/categories">

                                    <option value="{./name}">
                                        <xsl:if test="./name = /xml/selection/category">
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                        </xsl:if>
                                        <xsl:value-of select="./name"></xsl:value-of>
                                    </option>
                                </xsl:for-each>
                            </select>
                            <br />
                            <br />
                            <a href="/moderation/overview?view=r&amp;id={/xml/selection/route}" class="navigator-button">
                                Aktuelle Route:
                                <xsl:value-of select="/xml/selection/route"></xsl:value-of>
                            </a>
                            <button type="submit" class="button input">Speichern</button>

                            <br />
                            <br />
                            <a href="/moderation/overview?view=l&amp;id={xml/id}&amp;delete=1" class="navigator-button delete">Löschen</a>
                        </form>
                    </xsl:when>
                    <xsl:otherwise>
                        <p>Es wurde keine Linie ausgewählt</p>
                    </xsl:otherwise>
                </xsl:choose>

            </div>
        </div>
    </xsl:template>

</xsl:stylesheet>
