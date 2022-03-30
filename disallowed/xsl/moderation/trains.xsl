<?xml version="1.0" encoding="UTF-8"?>
<!-- Paul -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="trains">
        <div class="content-parent">
            <div class="content-search">

                <p>Fahrzeugnummer auswählen:</p>

                <input class="select_input" type="text" id="trains_select" onkeyup="search(trains_select)" placeholder="Suchen..." />
                <ul class="ul" id="trains_select_ul">
                    <xsl:for-each select="xml/train">
                        <li class="ulli">
                            <a class="ula" href="/moderation/overview?view=f&amp;id={./id}">
                                <xsl:value-of select="./id" />
                            </a>
                        </li>
                    </xsl:for-each>
                </ul>

                <p>Oder:</p>
                <br />

                <a class="navigator-button" href="/moderation/overview?view=f&amp;create=1">Neues Fahrzeug anlegen</a>
            </div>
            <div class="content-splitter" />
            <div class="content-result">

                <xsl:choose>
                    <xsl:when test="xml/selection">
                        <p class="bold">
                            Du betrachtest: Zug mit Fahrzeugnummer
                            <xsl:value-of select="xml/id" />
                        </p>

                        <form action="/moderation/overview?view=f&amp;id={xml/id}" method="post">
                            <p>Sitzplätze:</p>
                            <input type="number" name="seats" value="{xml/selection/seats}" />
                            <button type="submit" class="button input">Speichern</button>

                            <br />
                            <br />
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

</xsl:stylesheet>
