{literal}
<md-sidenav class="md-sidenav-left" md-component-id="left">
    <md-toolbar class="app-menu md-locked-open">
        <!--div class="md-toolbar-tools">
            <md-button ng-click="navigationToggle()" class="md-icon-button" aria-label="side-menu-close">
                <i class="mdi mdi-arrow-left actionbar-icon"></i>
            </md-button>
        </div-->
        <div class="user-details">
            <md-list-item class="md-1-line">
            {/literal}
            <img src="../../{$TEMPLATE_WEBPATH}/resources/images/butler.jpg" class="md-avatar" alt="butler">
            {literal}
                <div class="md-list-item-text">
                    <h5>{{userinfo.first_name + " "}}{{userinfo.last_name}}</h5>
                    <!--p>{{userinfo.username}}</p>
                    <p>{{userinfo.email}}</p-->
                </div>
            </md-list-item>
        </div>
        <md-input-container class="app-dropdown">
            <md-select ng-model="selectedApp" aria-label="app_menu">
                <md-option ng-repeat="app in apps" ng-value="app">{{app}}</md-option>
            </md-select>
        </md-input-container>
    </md-toolbar>

    <md-list class="sidenav-module-list">
        <md-list-item md-ink-ripple class="md-1-line">
            <span class="vicon-grid"></span> &nbsp; 
            <span class="vmodule-name">Dashboard</span>
        </md-list-item>
        <md-list-item ng-click="navigationToggle();loadList(module.name);" class="md-1-line" ng-click="module.label" ng-repeat="module in menus[selectedApp]">
            <span class="vicon-{{module.name | lowercase | nospace}}"></span> &nbsp; 
            <span class="vmodule-name">{{module.label}}</span>
        </md-list-item>
    </md-list>
    <md-divider ></md-divider>
    <md-list>
        <md-list-item md-ink-ripple class="md-1-line">
            <div class="md-list-item-text">
                <a href="#" class="logout-link" ng-click="logout();"><span class="mdi mdi-power"></span>&nbsp; Logout</a>
            </div>
        </md-list-item>
        <md-list-item class="md-1-line">
            <div class="md-list-item-text">
                &nbsp; 
            </div>
        </md-list-item>
    </md-list>
</md-sidenav>
{/literal}