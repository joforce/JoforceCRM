{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*************************************************************************************}

{strip}
	<div class=" h-100" id="EmailTaskContainer">
		<div class="row">
			<div class="col-sm-12 col-xs-12">
				<div class="row form-group" >
				<div class="col-sm-1 col-xs-1"></div>
					<div class="col-sm-9 col-xs-9">
						<div class="row">
							<div class="col-sm-2 col-xs-2">{vtranslate('LBL_FROM', $QUALIFIED_MODULE)}</div>
							<div class="col-sm-5 col-xs-5 col-10">
								<input name="fromEmail" class=" fields inputElement" type="text" value="{$TASK_OBJECT->fromEmail}" />
							</div>
							<div class="col-sm-5 col-xs-5 col-10">
						<select id="fromEmailOption" style="min-width: 280px" class="select2 mt10" data-placeholder={vtranslate('LBL_SELECT_OPTIONS',$QUALIFIED_MODULE)}>
							<option></option>
							{$FROM_EMAIL_FIELD_OPTION}
						</select>
					</div>
						</div>
					</div>
					
				</div>

				<div class="row form-group">
				<div class="col-sm-1 col-xs-1"></div>
					<div class="col-sm-9 col-xs-9">
						<div class="row">
							<div class="col-sm-2 col-xs-2">{vtranslate('Reply To',$QUALIFIED_MODULE)}</div>
							<div class="col-sm-5 col-xs-5 col-10">
								<input name="replyTo" class="fields inputElement" type="text" value="{$TASK_OBJECT->replyTo}"/>
							</div>
							<span class="col-sm-5 col-xs-5 col-10">
						<select style="min-width: 280px" class="task-fields select2 overwriteSelection mt10" data-placeholder={vtranslate('LBL_SELECT_OPTIONS',$QUALIFIED_MODULE)}>
							<option></option>
							{$EMAIL_FIELD_OPTION}
						</select>
					</span>
						</div>
					</div>
					
				</div>
				<div class="row form-group">
				<div class="col-sm-1 col-xs-1"></div>
					<div class="col-sm-9 col-xs-9">
						<div class="row">
							<span class="col-sm-2 col-xs-2">{vtranslate('LBL_TO',$QUALIFIED_MODULE)}<span class="redColor">*</span></span>
							<div class="col-sm-5 col-xs-5 col-10">
								<input data-rule-required="true" name="recepient" class="fields inputElement" type="text" value="{$TASK_OBJECT->recepient}" />
							</div>
							<div class="col-sm-5 col-xs-5 col-10">
						<select style="min-width: 280px" class="task-fields select2 mt10" data-placeholder={vtranslate('LBL_SELECT_OPTIONS',$QUALIFIED_MODULE)}>
							<option></option>
							{$EMAIL_FIELD_OPTION}
						</select>
					</div>
						</div>
					</div>
					
				</div>
				<div class="row form-group {if empty($TASK_OBJECT->emailcc)}hide {/if}" id="ccContainer">
				<div class="col-sm-1 col-xs-1"></div>
					<div class="col-sm-9 col-xs-9">
						<div class="row">
							<div class="col-sm-2 col-xs-2">{vtranslate('LBL_CC',$QUALIFIED_MODULE)}</div>
							<div class="col-sm-5 col-xs-5 col-10">
								<input class="fields inputElement" type="text" name="emailcc" value="{$TASK_OBJECT->emailcc}" />
							</div>
							<span class="col-sm-5 col-xs-5 col-10">
						<select class="task-fields select2 mt10" data-placeholder='{vtranslate('LBL_SELECT_OPTIONS',$QUALIFIED_MODULE)}' style="min-width: 280px">
							<option></option>
							{$EMAIL_FIELD_OPTION}
						</select>
					</span>
						</div>
					</div>
					
				</div>
				<div class="row form-group {if empty($TASK_OBJECT->emailbcc)}hide {/if}" id="bccContainer">
				<div class="col-sm-1 col-xs-1"></div>
					<div class="col-sm-9 col-xs-9">
						<div class="row">
							<div class="col-sm-2 col-xs-2">{vtranslate('LBL_BCC',$QUALIFIED_MODULE)}</div>
							<div class="col-sm-5 col-xs-5 col-10">
								<input class="fields inputElement" type="text" name="emailbcc" value="{$TASK_OBJECT->emailbcc}" />
							</div>
							<div class="col-sm-5 col-xs-5 col-10" >
						<select class="task-fields select2 mt10" data-placeholder='{vtranslate('LBL_SELECT_OPTIONS',$QUALIFIED_MODULE)}' stylestyle="min-width: 280px">
							<option></option>
							{$EMAIL_FIELD_OPTION}
						</select>
					</div>
						</div>
					</div>
					
				</div>
				<div class="row form-group {if (!empty($TASK_OBJECT->emailcc)) and (!empty($TASK_OBJECT->emailbcc))} hide {/if}">
				<div class="col-sm-1 col-xs-1"></div>
					<div class="col-sm-8 col-xs-8">
						<div class="row">
							<div class="col-sm-2 col-xs-2">&nbsp;</div>
							<div class="col-sm-8 col-xs-8">
								<a class="cursorPointer {if (!empty($TASK_OBJECT->emailcc))}hide{/if}" id="ccLink">{vtranslate('LBL_ADD_CC',$QUALIFIED_MODULE)}</a>&nbsp;&nbsp;
								<a class="cursorPointer {if (!empty($TASK_OBJECT->emailbcc))}hide{/if}" id="bccLink">{vtranslate('LBL_ADD_BCC',$QUALIFIED_MODULE)}</a>
							</div>
						</div>
					</div>
				</div>
				<div class="row form-group">
				<div class="col-sm-1 col-xs-1"></div>
					<div class="col-sm-9 col-xs-9">
						<div class="row">
							<div class="col-sm-2 col-xs-2">{vtranslate('LBL_SUBJECT',$QUALIFIED_MODULE)}<span class="redColor">*</span></div>
							<div class="col-sm-5 col-xs-5 col-10">
								<input data-rule-required="true" name="subject" class="fields inputElement" type="text" name="subject" value="{$TASK_OBJECT->subject}" id="subject" spellcheck="true"/>
							</div>
							<div class="col-sm-5 col-xs-5 col-10">
						<select style="min-width: 280px" class="task-fields select2 mt10" data-placeholder={vtranslate('LBL_SELECT_OPTIONS',$QUALIFIED_MODULE)}>
							<option></option>
							{$ALL_FIELD_OPTIONS}
						</select>
					</div>
						</div>
					</div>
					
				</div>
				<div class="row form-group">
				<div class="col-sm-1 col-xs-1"></div>
					<div class="col-sm-9 col-xs-9">
						<div class="row">
							<div style="margin-top: 7px" class="col-sm-2 col-xs-2">{vtranslate('LBL_ADD_FIELD',$QUALIFIED_MODULE)}</div>&nbsp;&nbsp;
							<div class="col-sm-8 col-xs-8">
								<select style="min-width: 280px" id="task-fieldnames" class="select2 " data-placeholder={vtranslate('LBL_SELECT_OPTIONS',$QUALIFIED_MODULE)}>
									<option></option>
									{$ALL_FIELD_OPTIONS}
								</select>
							</div>	
						</div>
					</div>
					
				</div>
				<div class="row form-group">
				<div class="col-sm-1 col-xs-1"></div>
				<div class="col-sm-9 col-xs-9">
						<div class="row">
							<div style="margin-top: 7px" class="col-sm-2 col-xs-2">{vtranslate('LBL_GENERAL_FIELDS',$QUALIFIED_MODULE)}</div>&nbsp;&nbsp;
							<div class="col-sm-8 col-xs-8">
								<select style="width: 205px" id="task_timefields" class="select2" data-placeholder={vtranslate('LBL_SELECT_OPTIONS',$QUALIFIED_MODULE)}>
									<option></option>
									{foreach from=$META_VARIABLES item=META_VARIABLE_KEY key=META_VARIABLE_VALUE}
										<option value="{if strpos(strtolower($META_VARIABLE_VALUE), 'url') === false}${/if}{$META_VARIABLE_KEY}">{vtranslate($META_VARIABLE_VALUE,$QUALIFIED_MODULE)}</option>
									{/foreach}	
								</select>
							</div>	
						</div>
					</div>
					</div>
				<div class="row from-group">
					{if $EMAIL_TEMPLATES}
					<div class="col-sm-1 col-xs-1"></div>
						<div class="col-sm-9 col-xs-9">
							<div class="row">
								<div class="col-sm-2 col-xs-2">{vtranslate('LBL_EMAIL_TEMPLATES','EmailTemplates')}</div>
								<div class="col-sm-8 col-xs-8">
									<select style="min-width: 280px" id="task-emailtemplates" class="select2 " data-placeholder={vtranslate('LBL_SELECT_OPTIONS',$QUALIFIED_MODULE)}>
										<option></option>
										{foreach from=$EMAIL_TEMPLATES item=EMAIL_TEMPLATE}
											{if !$EMAIL_TEMPLATE->isDeleted()}
												<option value="{$EMAIL_TEMPLATE->get('body')}">{vtranslate($EMAIL_TEMPLATE->get('templatename'),$QUALIFIED_MODULE)}</option>
											{/if}
										{/foreach}	
									</select>
								</div>
							</div>
						</div>
					{/if}
				</div>
				<div class="row form-group">
					<div class="col-sm-12 col-xs-12">
						<textarea id="content" name="content">{$TASK_OBJECT->content}</textarea>
					</div>
				</div>
			</div>
		</div>
	</div>	
{/strip}