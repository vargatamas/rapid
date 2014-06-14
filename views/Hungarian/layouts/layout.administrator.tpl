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
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sablon Motor <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li{$menu.layoutsActive?' class="active"':''}><a href="{$baseURL}administrator/layouts">Layoutok menedzselése</a></li>
                    <li{$menu.linkLayoutsActive?' class="active"':''}><a href="{$baseURL}administrator/linkLayouts">Layoutok összerendelése</a></li>
                    <li{$menu.templatesActive?' class="active"':''}><a href="{$baseURL}administrator/templates">Sablonok</a></li>
                    <li{$menu.mailsActive?' class="active"':''}><a href="{$baseURL}administrator/mails">E-mailek</a></li>
                </ul>
            </li>
            {if="'' != $navBeans"}
                <li class="dropdown{if="isset($menu.beanActive) || isset($menu.newBeanActive)"} active{/if}">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Adatbázis <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        {loop="$navBeans"}
                            {$v=$value}
                            <li{$menu.beanActive.$v?' class="active"':''}>
                                <a href="{$baseURL}administrator/beans/bean:{$value}">{$value|ucfirst}</a>
                            </li>
                        {/loop}
                        <li class="divider"></li>
                        <li{$menu.newBeanActive?' class="active"':''}><a href="{$baseURL}administrator/beans/new">Új Bean</a></li>
                    </ul>
                </li>
            {/if}
            <li class="dropdown{$menu.sourcesActive || $menu.libraryActive?" active":""}">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Fájlok <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li{$menu.appSourcesActive?' class="active"':''}><a href="{$baseURL}administrator/sources">Alkalmazás Források</a></li>
                    <li{$menu.libraryActive?' class="active"':''}><a href="{$baseURL}administrator/library">Könyvtár</a></li>
                </ul>
            </li>
            <li class="dropdown{$menu.routesActive || $menu.applicationsActive || $menu.translationsActive || $menu.languagesActive || $menu.preferencesActive?" active":""}">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Site <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li{$menu.routesActive?' class="active"':''}><a href="{$baseURL}administrator/routes">Útvonalak</a></li>
                    <li{$menu.applicationsActive?' class="active"':''}><a href="{$baseURL}administrator/applications">Alkalmazások</a></li>
                    <li{$menu.translationsActive?' class="active"':''}><a href="{$baseURL}administrator/translations">Fordítások</a></li>
                    <li{$menu.languagesActive?' class="active"':''}><a href="{$baseURL}administrator/languages">Nyelvek</a></li>
                    <li{$menu.preferencesActive?' class="active"':''}><a href="{$baseURL}administrator/preferences">Beállítások</a></li>
                    <li{$menu.globalSourcesActive?' class="active"':''}><a href="{$baseURL}administrator/globalsources">Globális Források</a></li>
                </ul>
            </li>
        </ul>
        {if="$USER"}
            <form method="post" action="/administrator" class="navbar-form navbar-right">
                <input type="hidden" name="auth[logout]" value="1" />
                <button type="submit" class="btn btn-default">Kilépés</button>
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

<div class="container content-area">
    <div class="well">
        {$APPLICATION_CONTENT}
    </div>
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
                <strong>Elavult verzió!</strong><br />
                A Rapid ezen verziója nem a legfrissebb. A legújabb jelenleg a <em>{$currentVersion}</em>.<br />
                A frissitéshez kattints az <em>Frissítés</em> gombra alul.
            </div>
        {/if}
        <h4>Információ</h4>
        A Rapid egy szimpla mini keretrendszer, amely szuper könyvtárakat használ, mint a sablon motor vagy az ORM illetve kezeli a weboldal kéréseket. Arra lett tervezve, hogy gyorsan készíthesd el a weboldalad. A legerősebb része a Rapidnak, hogy teljes egészében tudod menedzselni a weboldal megjelenési és adatmodell rétegét bármilyen böngészőből. Az Adminisztrátor alkalmazás segítségével sablonokat és layoutokat készíthetsz, adatbázist módosíthatsz, feltölthetsz és törölhetsz fájlokat és beállíthatod a weboldal paramétereit. Felhasználóbarát módon.
        <br /><br />
        <h4>Készítők</h4>
        A Rapidot a <a href="http://momentoom.hu/" target="_blank">Momentoom</a> csapata készítette. A cél az volt, hogy elkészüljön egy gyors és könnyen használható eszköz a weboldalak fejlesztéséhez.
        <br /><br />
        <h4>Könyvtárak</h4>
        <ul>
            <li>PHP sablon motor: <a href="http://www.raintpl.com/" target="_blank">RainTPL</a></li>
            <li>Adatbázis ORM: <a href="http://redbeanphp.com/" target="_blank">RedBean PHP</a></li>
            <li>UI elemek: <a href="http://getbootstrap.com/" target="_blank">Bootstrap</a></li>
            <li>Stílus tömörítő: <a href="http://lesscss.org/" target="_blank">LessCSS</a></li>
            <li>E-mail könyvtár: <a href="https://github.com/PHPMailer/PHPMailer" target="_blank">PHPMailer</a></li>
        </ul>
      </div>
      <div class="modal-footer">
        <a href="{$baseURL}administrator/">Köszöntő oldal</a>&nbsp;&nbsp;
        <button type="button" class="btn btn-default" data-dismiss="modal">Bezárás</button>
        {if="isset($currentVersion)"}
            <a href="{$baseURL}administrator/update" class="btn btn-success">Frissítés erre: {$currentVersion}</a>
        {/if}
      </div>
    </div>
  </div>
</div>