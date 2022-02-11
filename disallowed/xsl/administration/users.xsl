<?xml version="1.0" encoding="UTF-8"?>
<!-- Paul -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="mode">

        <div class="container-outer">
            <div class="container-inner">
                <h1 class="title">Liste der Benutzer</h1>
                <div class="body">

                    <xsl:for-each select="xml/user">

                    <div class="user">
                        <a class="user-link" href="/administration/user?id={userid}">
                            <p class="empty-left"></p>
                            <p class="user-name">
                                <xsl:value-of select="name" />
                            </p>
                            <p class="user-rank">
                                <xsl:value-of select="rank" />
                            </p>
                            <p class="user-creation_date">
                                <xsl:value-of select="creation_date" />
                            </p>
                        </a>
                    </div>

                    </xsl:for-each>

                </div>
            </div>
        </div>

    </xsl:template>

</xsl:stylesheet>
