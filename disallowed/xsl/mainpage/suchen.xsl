<?xml version="1.0" encoding="UTF-8"?>
<!-- Jens -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">


    <xsl:template match="/" mode="suchen-header">
        <div class="content-header">
            <div class="header">
                Suchen
            </div>
        </div>
    </xsl:template>

    <xsl:template match="/" mode="suchen-content">
        <div class="content-body">
            <form method="get" action="?site=suchen">
                <datalist id="stations">
                    <xsl:for-each select="xml/bahnhofe">
                        <option value="{./Name}" />
                    </xsl:for-each>
                </datalist>
                <table>
                    <tr>
                        <td>
                            <input type="text" name="sucheBahnhofA" list="stations" class="input" value="{//xml/suche/sucheBahnhofA}" placeholder="Bahnhof A" required="" />
                        </td>
                        <td>
                            <input type="text" name="timeBahnhofA" list="stations" class="input" value="{//xml/suche/timeBahnhofA}" placeholder="HH:MM" required="" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="sucheBahnhofB" class="input" value="{//xml/suche/sucheBahnhofB}" placeholder="Bahnhof B" required="" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button type="submit" name="site" value="suchen">suchen</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>

    </xsl:template>

</xsl:stylesheet>