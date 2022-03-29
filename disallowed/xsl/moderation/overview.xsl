<?xml version="1.0" encoding="UTF-8"?>
<!-- Paul -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="mode">
        <script src="/frontend/js/overview.js"></script>

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
    <xsl:template match="/" mode="bahnhofe">
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
                            <!-- TODO -->
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
                                    </th>
                                </tr>
                            </table>

                            <button type="submit" class="button input">Speichern</button>

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
    <xsl:template match="/" mode="routen">
        <div class="content-parent">
            <div class="content-search">

                <p>
                    RoutenID eingeben:
                    <br />
                    (Fokus auf Textbox verlieren)
                </p>
                <input list="routes" id="routes_select" onfocusout="routesFocusOut()" onfocusin="routesFocusIn()" value="{xml/id}" />
                <datalist id="routes">
                    <xsl:for-each select="xml/route">
                        <option value="{./id}" />
                    </xsl:for-each>
                </datalist>

                <p>
            Oder:
        </p>
                <br />

                <form action="/moderation/overview?view=r" method="post">
                    <input type="text" name="newfrom" placeholder="von" list="stations_full" />
                    <br />
                    <input type="text" name="newto" placeholder="zu" list="stations_full" />
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
                                                        <xsl:if test="./stand_time != 'null'">
                                                            <xsl:attribute name="checked" />
                                                        </xsl:if>
                                                    </input>
                                                </xsl:when>
                                            </xsl:choose>
                                        </th>
                                        <th id="time">
                                            <xsl:if test="not(./stand_time = 'null' or position() = last() or position() = 1)">
                                                <input type="number" name="duration-{position()}" value="{./stand_time}" />
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
                                        <input type="text" id="newConnection" list="stations_full" onkeydown="enter(event)" />
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
    <xsl:template match="/" mode="linien">
        <div class="content-parent">
            <div class="content-search">

                <p>
                    Liniennummer eingeben:
                    <br />
                    (Fokus auf Textbox verlieren)
                </p>
                <input list="lines" id="lines_select" onfocusout="linesFocusOut()" onfocusin="linesFocusIn()" value="{xml/id}" />
                <datalist id="lines">
                    <xsl:for-each select="xml/lines">
                        <option value="{./id}" />
                    </xsl:for-each>
                </datalist>

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
