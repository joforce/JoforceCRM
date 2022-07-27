{strip}
    <div class="col-lg-12 col-md-12 col-sm-12 row p0 m0">
	{assign var=key_ value=1}
	{assign var=max_ value=count($TOTAL)}
	{foreach item=_value from=$TOTAL key=moduleName}
	    <div class="widget{$key_} cumulative-widget col-lg-4 col-md-4 col-sm-4  p5">
			<div class="row">
			<div class="col-md-12 d-flex justify-content-center">
					<i class="fa fa-2x  joicon-{strtolower($moduleName)} text-white"></i>
					</div>

					<div class="col-lg-12 col-md-12 col-sm-12 text-center ">
					<span class="text-white">{vtranslate($moduleName, $moduleName)}</span>
					<h4 class="text-white">{$_value}</h4>
				</div>
			</div>
	    </div>

	    <!-- {if $key_ mod 3 eq 0}
		{if $key_ neq $max_}
		    </div><div class="col-lg-12 col-md-12 col-sm-12 row  p0 m0">
		{/if}
	    {/if}

	    {$key = $key_++} -->
	{/foreach}
    </div>
{/strip}
