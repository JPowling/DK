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
                <input type="text" name="sucheBahnhofA" class="input" value="{//xml/suche/sucheBahnhofA}" placeholder="Bahnhof A" required="" />
                <input type="text" name="sucheBahnhofB" class="input" value="{//xml/suche/sucheBahnhofB}" placeholder="Bahnhof B" required="" />
                <button type="submit" name="site" value="suchen">suchen</button>
            </form>
        </div>

    </xsl:template>

</xsl:stylesheet>