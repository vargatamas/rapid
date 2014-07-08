<h1>Manage Templates</h1>
<h5>You can create, edit or remove the Templates.</h5>
<a href="{$baseURL}administrator/templates/add" class="btn btn-primary">New Template</a>
<br /><br />
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Success!</strong> {$success}</div>
{/if}
{if="'' != $templates"}
    <div class="panel-group" id="accordion">
        {loop="templates"}
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#app-{$key}">
                            {$key}
                        </a>
                    </h4>
                </div>
                <div id="app-{$key}" class="panel-collapse collapse in">
                    <div class="panel-body admin-files">
                        <div class="row">
                            {loop="value"}
                            	<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 text-center item">
                                    <a href="{$baseURL}administrator/templates/edit/application:{$value.application}/template:{$value.filename}">
                                        <div class="icon"><span class="glyphicon glyphicon-file"></span></div>
                                        <div class="title">{$value.template}</div>
                                        <div class="description">{$value.last_modified}</div>
                                    </a>
                                    <div class="actions">
                                        {if="$value.writable"}
                                            <a href="{$baseURL}administrator/templates/edit/application:{$value.application}/template:{$value.filename}" title="Edit Template"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;
                                            <a href="javascript:linkConfirm('{$baseURL}administrator/templates/remove/application:{$value.application}/template:{$value.filename}');" title="Remove Permanently"><span class="glyphicon glyphicon-trash"></span></a>
                                        {else}
                                            <a href="{$baseURL}administrator/templates/edit/application:{$value.application}/template:{$value.filename}" title="View Template"><span class="glyphicon glyphicon-eye-open"></span></a>
                                        {/if}
                                    </div>
                                </div>
                            {/loop}
                        </div>
                    </div>
                </div>
            </div>
        {/loop}
    </div>
    {if="isset($prevStart) || isset($nextStart) || isset($page)"}
        <div class="text-center">
            <strong>{$page}. page</strong><br /><br />
            <div class="btn-group">
                {if="isset($prevStart)"}
                    <a href="{$baseURL}administrator/templates/start:{$prevStart}" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i>&nbsp;previous</a>
                {/if}
                {if="isset($nextStart)"}
                    <a href="{$baseURL}administrator/templates/start:{$nextStart}" class="btn btn-default">next&nbsp;<i class="glyphicon glyphicon-arrow-right"></i></a>
                {/if}
            </div>
        </div>
    {/if}
{else}
    <div class="alert alert-info">
        <strong>No Templates</strong> found. You can add new Templates, just click on <em>New Template</em> button above.
    </div>
{/if}