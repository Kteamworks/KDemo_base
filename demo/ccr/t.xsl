<xsl:if test="a:ContinuityOfCareRecord/a:Body/a:Admissions">
                        <tr id="immunizationsrow">
                          <td>
                            <span class="header">Admissions</span>
                            <br/>
                            <table class="list" id="admissions">
                              <tbody>
                                <tr>
                                  <th>Code</th>
                                  <th>Vaccine</th>
                                  <th>Date</th>
                                  <th>Route</th>
                                  <th>Site</th>
                                  <th>Source</th>
                                </tr>
                                <xsl:for-each select="a:ContinuityOfCareRecord/a:Body/a:Immunizations/a:Immunization">
                                  <tr>
                                    <td>
                                      <xsl:apply-templates select="a:Product/a:ProductName/a:Code"/>
                                    </td>
                                    <td>
                                      <strong class="clinical">
                                        <xsl:value-of select="a:Product/a:ProductName/a:Text"/>
                                        <xsl:if test="a:Product/a:Form">
                                          <xsl:text xml:space="preserve"> (</xsl:text>
                                          <xsl:value-of select="a:Product/a:Form/a:Text"/>
                                          <xsl:text>)</xsl:text>
                                        </xsl:if>
                                      </strong>
                                    </td>
                                    <td>
                                      <table class="internal">
                                        <tbody>
                                          <xsl:call-template name="dateTime">
                                            <xsl:with-param name="dt" select="a:DateTime"/>
                                          </xsl:call-template>
                                        </tbody>
                                      </table>
                                    </td>
                                    <td>
                                      <xsl:value-of select="a:Directions/a:Direction/a:Route/a:Text"/>
                                    </td>
                                    <td>
                                      <xsl:value-of select="a:Directions/a:Direction/a:Site/a:Text"/>
                                    </td>
                                    <td>
                                      <a>
                                        <xsl:attribute name="href">
                                          <xsl:text>#</xsl:text>
                                          <xsl:value-of select="a:Source/a:Actor/a:ActorID"/>
                                        </xsl:attribute>
                                        <xsl:call-template name="actorName">
                                          <xsl:with-param name="objID" select="a:Source/a:Actor/a:ActorID"/>
                                        </xsl:call-template>
                                      </a>
                                    </td>
                                  </tr>
                                </xsl:for-each>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                      </xsl:if>