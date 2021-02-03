{strip}
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
	{assign var=key_ value=1}
	{foreach item=_value from=$TOTAL key=moduleName}
	    {if $_key mod 3}
		</div></div><div class="row"><div class="col-lg-12 col-md-12 col-sm-12">
	    {/if}
	    <div class="widget{$key_} cumulative-widget">
		<div class="row">
                        <div class="col-md-4">
                                 <i class="fa fa-2x  joicon-{strtolower($moduleName)} text-white"></i>
                        </div>

                	<div class="col-md-8 text-left">
                                 <span class="text-white">{vtranslate($moduleName, $moduleName)}</span>
                                 <h4 class="text-white">{$_value}</h4>
                        </div>
		</div>
	    </div>

	    {$key = $key_++}
	{/foreach}
    </div>
</div>
{/strip}
