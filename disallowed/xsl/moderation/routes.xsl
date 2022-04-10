<?xml version="1.0" encoding="UTF-8"?>
<!-- Paul -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="routes">
        <datalist id="routes">
            <xsl:for-each select="xml/route">
                <option value="{./id}" />
            </xsl:for-each>
        </datalist>

        <div class="content-parent">
            <div class="content-search">

                <input class="select_input" type="text" id="routes_select" onkeyup="search(routes_select)" placeholder="Suchen..." />
                <ul class="ul" id="routes_select_ul">
                    <xsl:for-each select="xml/route">
                        <li class="ulli">
                            <a class="ula" href="/moderation/overview?view=r&amp;id={./id}">
                                <xsl:value-of select="./id" />
                                :
                                <br/>
                                <xsl:value-of select="./start" />
                                -
                                <xsl:value-of select="./end" />
                            </a>
                        </li>
                    </xsl:for-each>
                </ul>

                <p>
            Oder:
        </p>
                <br />

                <form action="/moderation/overview?view=r" method="post">
                    <input class="wider" type="text" name="newfrom" placeholder="von" list="stations_full" />
                    <br />
                    <input class="wider" type="text" name="newto" placeholder="zu" list="stations_full" />
                    <br />
                    <button type="submit">Route erstellen</button>
                    <datalist id="stations">
                        <xsl:for-each select="xml/station">
                            <option value="{./id}" />
                        </xsl:for-each>
                    </datalist>

                    <datalist id="stations_full">
                        <xsl:for-each select="xml/station">
                            <option value="{./name}" />
                        </xsl:for-each>
                    </datalist>
                </form>
            </div>
            <div class="content-splitter" />
            <div class="content-result">

                <xsl:choose>
                    <xsl:when test="xml/selection">
                        <p class="bold">
                            Du betrachtest: Route mit ID
                            <xsl:value-of select="xml/id" />
                        </p>

                        <form action="/moderation/overview?view=r&amp;id={xml/id}" method="post">
                            <table id="list">
                                <tr>
                                    <th>
                                        <p class="bold">Reihenfolge</p>
                                    </th>
                                    <th class="addpadding">
                                        <p class="bold">Bahnhof</p>
                                    </th>
                                    <th>
                                        <p class="bold">Zug hält?</p>
                                    </th>
                                    <th>
                                        <p class="bold">Standzeit (min)</p>
                                    </th>
                                </tr>
                                <xsl:for-each select="xml/selection/data">

                                    <tr class="row-with-button">
                                        <th>
                                            <p class="lightgray grabber" id="{position()}" ondrop="drop(event)" ondragover="allowDrop(event)" draggable="true" ondragstart="drag(event)">
                                        Drag
                                    </p>
                                        </th>
                                        <th>
                                            <a class="navigator-button" href="/moderation/overview?view=b&amp;id={./station}">
                                                <xsl:value-of select="./station_full"></xsl:value-of>
                                            </a>
                                            <input type="hidden" name="short-{position()}" value="{./station}" />
                                        </th>
                                        <th id="stops">
                                            <xsl:choose>
                                                <xsl:when test="not(position() = last() or position() = 1)">
                                                    <input type="checkbox" name="stands-{position()}" id="stands-{position()}" onchange="handleStopChange(this)">
                                                        <xsl:if test="./stand_time != 'NULL'">
                                                            <xsl:attribute name="checked" />
                                                        </xsl:if>
                                                    </input>
                                                </xsl:when>
                                            </xsl:choose>
                                        </th>
                                        <th id="time">
                                            <xsl:if test="not(./stand_time = 'NULL' or position() = last() or position() = 1)">
                                                <input type="number" name="duration-{position() - 1}" value="{./stand_time}" />
                                            </xsl:if>
                                        </th>
                                        <th id="delete">
                                            <xsl:if test="not(position() = last() or position() = 1)">
                                                <p class="delete pointer" onclick="deleteRoutePart(this)" id="delete-{position()}">
                                        Delete
                                    </p>
                                            </xsl:if>
                                        </th>
                                    </tr>
                                </xsl:for-each>

                                <tr>
                                    <th>
                                        <p>Neu verbinden: (Kürzel)</p>
                                    </th>
                                </tr>

                                <tr>
                                    <th>
                                        <input class="wider" type="text" id="newConnection" list="stations_full" onkeydown="enter(event)" />
                                        <p class="lightgray pointer" onClick="addConnection()">hinzufügen</p>
                                    </th>
                                </tr>
                            </table>

                            <input type="hidden" name="rows" value="{count(xml/selection/data)}" />
                            <button type="submit" class="button input" name="save">Speichern</button>

                            <br />
                            <br />
                            <a href="/moderation/overview?view=r&amp;id={xml/id}&amp;delete=1" class="navigator-button delete">Löschen</a>
                            <a href="/moderation/overview?view=r&amp;id={xml/id}&amp;reverse=1" class="navigator-button">Rückverbindung erstellen</a>
                        </form>
                    </xsl:when>
                    <xsl:otherwise>
                        <p>Es wurde keine Route ausgewählt</p>
                    </xsl:otherwise>
                </xsl:choose>

            </div>
        </div>

    </xsl:template>
</xsl:stylesheet>
