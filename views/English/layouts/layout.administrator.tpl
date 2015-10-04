<div id="background-image"></div>

<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand tooltip-item" href="#" data-toggle="modal" data-target="#rapid-info">Rapid.{if="isset($currentVersion)"}&nbsp;<span class="badge"><span class="glyphicon glyphicon-cloud-download"></span></span>{/if}</a>
    </div>
    
    <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="nav navbar-nav">
            <li class="dropdown{$menu.layoutActive || $menu.linkLayoutsActive || $menu.templatesActive || $menu.mailsActive?" active":""}">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Template Engine <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li{$menu.layoutsActive?' class="active"':''}><a href="{$baseURL}administrator/layouts">Manage Layouts</a></li>
                    <li{$menu.linkLayoutsActive?' class="active"':''}><a href="{$baseURL}administrator/linkLayouts">Link Layouts</a></li>
                    <li{$menu.templatesActive?' class="active"':''}><a href="{$baseURL}administrator/templates">Templates</a></li>
                    <li{$menu.mailsActive?' class="active"':''}><a href="{$baseURL}administrator/mails">Mails</a></li>
                </ul>
            </li>
            {if="'' != $navBeans"}
                <li class="dropdown{if="isset($menu.beanActive) || isset($menu.newBeanActive) || isset($menu.executeActive)"} active{/if}">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Database <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        {loop="$navBeans"}
                            {$v=$value}
                            <li{$menu.beanActive.$v?' class="active"':''}>
                                <a href="{$baseURL}administrator/beans/bean:{$value}">{$value|ucfirst}</a>
                            </li>
                        {/loop}
                        <li class="divider"></li>
                        <li{$menu.newBeanActive?' class="active"':''}><a href="{$baseURL}administrator/beans/new">Create Bean</a></li>
                        <li class="divider"></li>
                        <li{$menu.executeActive?' class="active"':''}><a href="{$baseURL}administrator/beans/execute">Execute SQL</a></li>
                    </ul>
                </li>
            {/if}
            <li class="dropdown{$menu.sourcesActive || $menu.libraryActive?" active":""}">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Files <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li{$menu.appSourcesActive?' class="active"':''}><a href="{$baseURL}administrator/sources">Application Sources</a></li>
                    <li{$menu.libraryActive?' class="active"':''}><a href="{$baseURL}administrator/library">Library</a></li>
                </ul>
            </li>
            <li class="dropdown{$menu.routesActive || $menu.applicationsActive || $menu.translationsActive || $menu.languagesActive || $menu.preferencesActive || $menu.globalSourcesActive?" active":""}">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Site <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li{$menu.routesActive?' class="active"':''}><a href="{$baseURL}administrator/routes">Routes</a></li>
                    <li{$menu.applicationsActive?' class="active"':''}><a href="{$baseURL}administrator/applications">Applications</a></li>
                    <li{$menu.translationsActive?' class="active"':''}><a href="{$baseURL}administrator/translations">Translations</a></li>
                    <li{$menu.languagesActive?' class="active"':''}><a href="{$baseURL}administrator/languages">Languages</a></li>
                    <li{$menu.preferencesActive?' class="active"':''}><a href="{$baseURL}administrator/preferences">Preferences</a></li>
                    <li{$menu.globalSourcesActive?' class="active"':''}><a href="{$baseURL}administrator/globalsources">Global Sources</a></li>
                </ul>
            </li>
        </ul>
        {if="$USER"}
            <form method="post" action="/administrator" class="navbar-form navbar-right">
                <input type="hidden" name="auth[logout]" value="1" />
				<button type="submit" class="btn btn-default">Sign out <i class="fa fa-sign-out"></i></button>&nbsp;&nbsp;&nbsp;
            </form>
        {/if}
        <form method="post" action="{$baseURL}administrator" class="navbar-form navbar-right">
            <select name="culture[name]" class="form-control culture">
                {loop="$cultures"}
                    <option value="{$value}"{if="$CULTURE == $value"} selected="selected"{/if}>{$value}</option>
                {/loop}
            </select>
        </form>
    </div>
</nav>

<div class="content-area">
    {$APPLICATION_CONTENT}
</div>

<!-- Modal -->
<div class="modal fade" id="rapid-info" tabindex="-1" role="dialog" aria-labelledby="rapid-info-title" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="rapid-info-title">Rapid FrameWork - {$thisVersion}</h4>
      </div>
      <div class="modal-body">
        {if="isset($currentVersion)"}
            <div class="alert alert-warning">
                <strong>Outdated version!</strong><br />
                This version of Rapid is not the newest. The newest is <em>{$currentVersion}</em>.<br />
                To update Rapid click the <em>Update</em> button on the bottom.
            </div>
        {/if}
        <h4>Information</h4>
        Rapid is a simple mini framework, that use cool libraries like template engine or ORM and controll the requests. It designed for easy and fast developing your website. The strongest part of Rapid is that you can fully manage the view and modell layer of the site via any browser. The Administrator application let you create templates and layouts, edit database, upload/remove files and set your website specific variables. In a user-friendly way.
        <br /><br />
        <h4>Creators</h4>
        Rapid is created by the team of <a href="http://momentoom.hu/" target="_blank">Momentoom</a>. The goal was to make a fast and easy-to-use tool for building websites.
        <br /><br />
        <h4>Libraries</h4>
        <ul>
            <li>PHP Template Engine: <a href="http://www.raintpl.com/" target="_blank">RainTPL</a></li>
            <li>Database ORM: <a href="http://redbeanphp.com/" target="_blank">RedBean PHP</a></li>
            <li>UI elements: <a href="http://getbootstrap.com/" target="_blank">Bootstrap</a></li>
            <li>Style compressor: <a href="http://lesscss.org/" target="_blank">LessCSS</a></li>
            <li>Mail library: <a href="https://github.com/PHPMailer/PHPMailer" target="_blank">PHPMailer</a></li>
        </ul>
      </div>
      <div class="modal-footer">
		  <a href="{$baseURL}administrator/" class="btn btn-default pull-left"><i class="fa fa-home"></i> Welcome Page</a>
		  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
        {if="isset($currentVersion)"}
			<a href="{$baseURL}administrator/update" class="btn btn-success"><i class="fa fa-cogs"></i> Update to {$currentVersion}</a>
        {/if}
      </div>
    </div>
  </div>
</div>