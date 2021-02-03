{strip}
<ul class="dropdown-menu profile-dropdown">
                            <div class="profile-info">
                                <div class="profile-content bg-primary">
                                    <div class="profile-logo">
                                        {if $IMAGE_DETAILS neq '' && $IMAGE_DETAILS[0] neq '' && $IMAGE_DETAILS[0].path neq ''}
                                            {foreach item=IMAGE_INFO from=$IMAGE_DETAILS}
                                                {if !empty($IMAGE_INFO.path) && !empty({$IMAGE_INFO.orgname})}
                                                    <img src="{$SITEURL}{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" class="img-responsive img-circle">
                                                {/if}
                                           {/foreach}
                                        {else}
                                            <div class="user-image"><div class="avatar-circle img-responsive img-circle">{if $first_name neq ''} {$first_name[0]} {else} {$last_name[0]}{/if}</div></div>
                                        {/if}
                                    </div>
                                    <div class="profile-details">
                                                <p>{$USER_MODEL->get('first_name')} {$USER_MODEL->get('last_name')}</p>
                                                <!--<p>{$USER_MODEL->get('email1')}</p>-->
                                                <p>{getRoleName($USER_MODEL->get('roleid'))}</p>
                                    </div>
                                </div>
                            </div>
                            <a href="{$USER_MODEL->getPreferenceDetailViewUrl()}" class="dropdown-item"><i class="fa fa-user-circle"></i>{vtranslate('LBL_MY_ACCOUNT', 'Head')}</a>
                            <a href="{$USER_MODEL->getPreferenceEditViewUrl()}" class="dropdown-item"><i class="fa fa-cog"></i>{vtranslate('LBL_EDIT', 'Head')} {vtranslate('LBL_MY_ACCOUNT', 'Head')}</a>
                            <a class="dropdown-item" href=""><i><img width="20px" height="20px" src="{$SITEURL}/layouts/resources/Images/black-theme.png" ></i>Black theme</a>
                            <a class="dropdown-item" href="{$SITEURL}Users/action/Logout"><i class="fa fa-power-off"></i>{vtranslate('LBL_SIGN_OUT')}</a>
                        </ul>

{/strip}
