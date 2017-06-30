{assign var="topMenus" value=$MENU_STRUCTURE->getTop()}
{assign var="moreMenus" value=$MENU_STRUCTURE->getMore()}

<div id="modules-menu" class="modules-menu">
	<ul>
		{foreach item=SIDE_BAR_LINK from=$QUICK_LINKS['SIDEBARLINK']}
			{assign var=CURRENT_LINK_NAME value="List"}
			{assign var=VIEW_ICON_CLASS value="vicon-calendarlist"}
			{if $SIDE_BAR_LINK->get('linklabel') eq 'LBL_CALENDAR_VIEW'}
				{assign var=CURRENT_LINK_NAME value="Calendar"}
				{assign var=VIEW_ICON_CLASS value="vicon-mycalendar"}
			{else if $SIDE_BAR_LINK->get('linklabel') eq 'LBL_SHARED_CALENDAR'}
				{assign var=CURRENT_LINK_NAME value="SharedCalendar"}
				{assign var=VIEW_ICON_CLASS value="vicon-sharedcalendar"}
			{/if}
			<li class="module-qtip {if $CURRENT_LINK_NAME eq $CURRENT_VIEW}active{/if}" title="{vtranslate($SIDE_BAR_LINK->get('linklabel'),'Calendar')}">
				<a href="{$SIDE_BAR_LINK->get('linkurl')}">
					<i class="{$VIEW_ICON_CLASS}"></i>
					<span>{vtranslate($SIDE_BAR_LINK->get('linklabel'),'Calendar')}</span>
				</a>
			</li>
		{/foreach}
	</ul>
</div>