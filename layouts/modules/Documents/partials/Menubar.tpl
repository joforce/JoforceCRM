{if $smarty.request.view eq 'Detail'}
<div id="modules-menu" class="modules-menu">    
    <ul>
        <li class="active">
            <a href="{$MODULE_MODEL->getListViewUrl()}">
                <i class="joicon-documents"></i>
                <span>{$MODULE}</span>
            </a>
        </li>
    </ul>
</div>
{/if}