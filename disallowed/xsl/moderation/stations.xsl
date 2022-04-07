<?xml version="1.0" encoding="UTF-8"?>
<!-- Paul -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="stations">
        <div class="content-parent">
            <div class="content-search">

                <p>
            Bahnhof auswählen:
        </p>

                <input class="select_input" type="text" id="stations_select" onkeyup="search(stations_select)" placeholder="Suchen..." />
                <ul class="ul" id="stations_select_ul">
                    <xsl:for-each select="xml/station">
                        <li class="ulli">
                            <a class="ula" href="/moderation/overview?view=b&amp;id={./id}">
                                <xsl:value-of select="./id" />
                                -
                                <xsl:value-of select="./name" />
                            </a>
                        </li>
                    </xsl:for-each>
                </ul>

                <p>
            Oder:
        </p>
                <br />

                <form action="/moderation/overview?view=b" method="post">
                    <input type="text" name="newshort" placeholder="Neuer Kürzel" />
                    <button type="submit">Bahnhof bauen</button>
                </form>
            </div>
            <div class="content-splitter" />
            <div class="content-result">

                <xsl:choose>
                    <xsl:when test="xml/selection">
                        <p class="bold">
                            Du betrachtest: Bahnhof mit Kürzel
                            <xsl:value-of select="xml/id" />
                        </p>

                        <form action="/moderation/overview?view=b&amp;id={xml/id}" method="post">
                            <p>Voller Name:</p>
                            <input type="text" name="fullname" value="{xml/selection/fullname}" />
                            <p>Gleise:</p>
                            <input type="number" name="platforms" value="{xml/selection/platforms}" />

                            <table>
                                <tr>
                                    <th>
                                        <p>Verbunden mit:</p>
                                    </th>
                                    <th>
                                        <p>Dauer (min)</p>
                                    </th>
                                    <th>
                                        <p>Dauer Rückweg (min)</p>
                                    </th>
                                    <th>
                                        <p>Verbindung löschen?</p>
                                    </th>
                                </tr>
                                <xsl:for-each select="xml/selection/connection">
                                    <tr class="row-with-button">
                                        <th>
                                            <a class="navigator-button" href="/moderation/overview?view=b&amp;id={./other_short}">
                                                <xsl:value-of select="./other"></xsl:value-of>
                                            </a>
                                            <!-- So that PHP knows what this is connected to (is bad i guess)-->
                                            <input type="hidden" name="connection-{position()}" value="{./other_short}" />
                                        </th>
                                        <th>
                                            <input type="number" name="duration-{position()}" value="{./duration}" />
                                        </th>
                                        <th>
                                            <input type="number" name="duration_rev-{position()}" value="{./duration_rev}" />
                                        </th>
                                        <th>
                                            <input type="checkbox" name="delete-{position()}" />
                                        </th>
                                    </tr>
                                </xsl:for-each>

                                <tr>
                                    <th>
                                        <p>Neu verbinden: (Kürzel) oder neuen Bahnhof erstellen</p>
                                    </th>
                                </tr>

                                <tr>
                                    <th>
                                        <input type="text" name="new-connection" list="stations" />
                                        <datalist id="stations">
                                            <xsl:for-each select="xml/station">
                                                <option value="{./name}" />
                                            </xsl:for-each>
                                        </datalist>
                                    </th>
                                </tr>
                            </table>

                            <button type="submit" class="button input" name="save">Speichern</button>

                            <br />
                            <br />
                            <a href="/moderation/overview?view=b&amp;id={xml/id}&amp;delete=1" class="navigator-button delete">Löschen</a>
                        </form>
                    </xsl:when>
                    <xsl:otherwise>
                        <p>Es wurde kein Bahnhof ausgewählt</p>
                    </xsl:otherwise>
                </xsl:choose>

            </div>
        </div>
    </xsl:template>
</xsl:stylesheet>
