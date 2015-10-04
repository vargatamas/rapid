<form id="route-form" method="post" action="{$baseURL}administrator/routes/add/save" class="form-horizontal" role="form">
    <h1>
        Add Route
        <button type="button" class="btn btn-sm pull-right btn-primary route-submit"><i class="fa fa-floppy-o"></i> Save</button>
    </h1>
    {if="'' != $error"}
        <div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
    {/if}
    <div class="form-group">
        <label class="col-lg-2 control-label">Base URL</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$url}</p>
        </div>
    </div>
    <div class="form-group">
        <label for="routeFrom" class="col-lg-2 control-label">From</label>
        <div class="col-lg-2 argument-group">
            <div class="input-group">
                <span class="input-group-addon">/</span>
                <input type="text" name="route[from][]" class="form-control" id="routeFrom" placeholder="argument" />
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                    <ul class="dropdown-menu pull-right">
                        <li role="presentation" class="dropdown-header">Applications</li>
                        {loop="applications"}<li><a href="#" class="select-app">{$value}</a></li>{/loop}
                        <li class="divider"></li>
                        <li role="presentation" class="dropdown-header">Variables</li>
                        <li><a href="#" class="select-var">Text</a></li>
                        <li class="divider"></li>
                        <li role="presentation" class="dropdown-header">Actions</li>
                        <li><a href="#" class="route-arg-remove">Remove this arg.</a></li>
                    </ul>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="button" class="btn btn-default add-from-arg"><i class="fa fa-plus"></i>&nbsp;Add argument</button>
        </div>
    </div>
    <div class="form-group">
        <label for="routeTo" class="col-lg-2 control-label">To</label>
        <div class="col-lg-2 argument-group">
            <div class="input-group">
                <span class="input-group-addon">/</span>
                <input type="text" name="route[to][]" class="form-control" id="routeTo" placeholder="argument" />
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                    <ul class="dropdown-menu pull-right">
                        <li role="presentation" class="dropdown-header">Applications</li>
                        {loop="applications"}<li><a href="#" class="select-app">{$value}</a></li>{/loop}
                        <li class="divider"></li>
                        <li role="presentation" class="dropdown-header">Variables</li>
                        <li><a href="#" class="select-var">Text</a></li>
                        <li class="divider"></li>
                        <li role="presentation" class="dropdown-header">Actions</li>
                        <li><a href="#" class="route-arg-remove">Remove this arg.</a></li>
                    </ul>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="button" class="btn btn-default add-to-arg"><i class="fa fa-plus"></i>&nbsp;Add argument</button>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <div class="alert alert-danger hided">
                <strong>Error</strong>, you can not have empty arguments. Fill it or remove it.
            </div>
            <button type="button" class="btn btn-primary route-submit"><i class="fa fa-floppy-o"></i> Save</button>&nbsp;
            <a href="{$baseURL}administrator/routes">Cancel and back to list</a>
        </div>
    </div>
</form>