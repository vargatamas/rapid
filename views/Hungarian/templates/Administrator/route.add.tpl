<h1>Új Útvonal</h1>
{if="'' != $error"}
    <div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
<form id="route-form" method="post" action="{$baseURL}administrator/routes/add/save" class="form-horizontal" role="form">
    <div class="form-group">
        <label class="col-lg-2 control-label">Bázis URL</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$url}</p>
        </div>
    </div>
    <div class="form-group">
        <label for="routeFrom" class="col-lg-2 control-label">Honnan</label>
        <div class="col-lg-2 argument-group">
            <div class="input-group">
                <span class="input-group-addon">/</span>
                <input type="text" name="route[from][]" class="form-control" id="routeFrom" placeholder="argumentum" />
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                    <ul class="dropdown-menu pull-right">
                        <li role="presentation" class="dropdown-header">Alkalmazások</li>
                        {loop="applications"}<li><a href="#" class="select-app">{$value}</a></li>{/loop}
                        <li class="divider"></li>
                        <li role="presentation" class="dropdown-header">Változók</li>
                        <li><a href="#" class="select-var">Szöveg</a></li>
                        <li><a href="#" class="select-var">Szám</a></li>
                        <li class="divider"></li>
                        <li role="presentation" class="dropdown-header">Akciók</li>
                        <li><a href="#" class="route-arg-remove">Argumentum eltávolítása</a></li>
                    </ul>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="button" class="btn btn-default add-from-arg"><i class="glyphicon glyphicon-plus"></i>&nbsp;Új argumentum</button>
        </div>
    </div>
    <div class="form-group">
        <label for="routeTo" class="col-lg-2 control-label">Hova</label>
        <div class="col-lg-2 argument-group">
            <div class="input-group">
                <span class="input-group-addon">/</span>
                <input type="text" name="route[to][]" class="form-control" id="routeTo" placeholder="argumentum" />
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                    <ul class="dropdown-menu pull-right">
                        <li role="presentation" class="dropdown-header">Alkalmazások</li>
                        {loop="applications"}<li><a href="#" class="select-app">{$value}</a></li>{/loop}
                        <li class="divider"></li>
                        <li role="presentation" class="dropdown-header">Változók</li>
                        <li><a href="#" class="select-var">Szöveg</a></li>
                        <li><a href="#" class="select-var">Szám</a></li>
                        <li class="divider"></li>
                        <li role="presentation" class="dropdown-header">Akciók</li>
                        <li><a href="#" class="route-arg-remove">Argumentum eltávolítása</a></li>
                    </ul>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="button" class="btn btn-default add-to-arg"><i class="glyphicon glyphicon-plus"></i>&nbsp;Új argumentum</button>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <div class="alert alert-danger hided">
                <strong>Hiba</strong>, nem lehetnek üres argumentumaid. Töltsd ki vagy távolítsd el.
            </div>
            <button type="button" class="btn btn-primary route-submit">Mentés</button>&nbsp;
            <a href="{$baseURL}administrator/routes">Mégse és vissza a listához</a>
        </div>
    </div>
</form>