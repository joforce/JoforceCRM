                        <div class="summaryViewFields">
				{assign var=_key value=1}
                                {foreach item=SUMMARY_CATEGORY from=$SUMMARY_INFORMATION}
                                        <div class="row textAlignCenter roundedCorners">
                                                {foreach key=FIELD_NAME item=FIELD_VALUE from=$SUMMARY_CATEGORY}
                                                        <div class="col-lg-3">
                                                                <div class="project-well widget{$_key} text-white">
                                                                        <div>
                                                                                <label class="font-x-small">
                                                                                        {vtranslate($FIELD_NAME,$MODULE_NAME)}
                                                                                </label>
                                                                        </div>
                                                                        <div>
                                                                                <label class="font-x-x-large">
                                                                                        {if !empty($FIELD_VALUE)}{$FIELD_VALUE}{else}0{/if}
                                                                                </label>
                                                                        </div>
                                                                </div>
                                                        </div>
							{$key =$_key++}
                                                {/foreach}
                                        </div>
                                {/foreach}
                        </div>
