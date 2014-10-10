<?php
/**
 * @version		$Id: oxsupport_tab.php 3130 2013-03-14 07:56:25Z mic $
 * @package		Support - Tab Template
 * @author		mic - http://osworx.net
 * @copyright	2013 OSWorX - http://osworx.net
 * @license		OCL OSWorX Commercial - http://osworx.net
 */
?>
                    <div id="tab-support">
                        <table class="form">
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                    <a href="http://osworx.net" target="_blank"><img src="http://osworx.net/support/index.php?banner" title="OSWorX" alt="OSWorX" /></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="vtop"><?php echo $text_version; ?></td>
                                <td>
                                    <div id="supportMsg"></div>
                                    <div id="backupHelp" style="display: none;" class="help-outline">
                                        <?php echo $help_update; ?>
                                    </div>
                                    <div style="float: left;">
                                        <div style="float: left;" id="version">
                                            <span class="<?php echo $currentVersion['class']; ?>"><?php echo $version; ?></span>
                                            <br />
                                            <?php echo $currentVersion['changelog']; ?>
                                        </div>
                                        <?php
                                        if( $isSupported ) { ?>
                                            <div class="support" id="newVersion">
                                                <a id="checkVersion" class="support-button blue" title="<?php echo $btn_check_version; ?>"><?php echo $btn_check_version; ?></a>
                                            </div>
                                            <div style="float: left; margin: 0 0 0 20px; display: none;" id="changelog">
                                                <div style="margin: 0 0 20px 20px;">
                                                    <a onclick="updateNow();" id="updateNow" class="support-button green" title="<?php echo $btn_update_now; ?>"><?php echo $btn_update_now; ?></a>
                                                </div>
                                                <div style="margin: 0 0 20px 20px;">
                                                    <label>
                                                        <input type="checkbox" id="backup" onchange="displayHelp();" />
                                                        &nbsp;
                                                        <?php echo $text_update_existing; ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <?php
                                        } ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $text_website; ?></td>
                                <td><a href="http://osworx.net" target="_blank" title="OSWorX">http://osworx.net</a></td>
                            </tr>
                            <tr>
                                <td><?php echo $text_support; ?></td>
                                <td><a href="mailto:support@osworx.net?subject=Support%20<?php echo $_name; ?>" title="Email OSWorX">Email</a></td>
                            </tr>
                            <tr>
                                <td><?php echo $text_license; ?></td>
                                <td><a href="https://osworx.net/en/intern/licenses/67-osworx-commercial-license-ocl" target="_blank" title="OCL OSWorX Commercial License">OCL OSWorX Commercial License</a></td>
                            </tr>
                            <tr>
                                <td><?php echo $text_supportkey; ?></td>
                                <td>
                                    <div id="validMsg"></div>
                                    <div style="float: left;">
                                        <div style="float: left;">
                                            <input type="text" name="<?php echo $_name; ?>_supportKey" value="<?php echo ${$_name . '_supportKey'}; ?>" size="60" />
                                        </div>
                                        <?php
                                        if( $isSupported ) { ?>
                                            <div class="support" id="validUntil">
                                                <a id="isValidUntil" class="support-button white" title="<?php echo $btn_valid_until; ?>"><?php echo $btn_valid_until; ?></a>
                                            </div>
                                            <?php
                                        }else{ ?>
                                            <div class="support shadow">
                                                <?php echo $text_get_key; ?>
                                            </div>
                                            <?php
                                        } ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>