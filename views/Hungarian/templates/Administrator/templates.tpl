<h2>
Sablonok menedzselése&nbsp;<small>Tudsz készíteni, szerkeszteni vagy törölni Sablonokat.</small>
<a href="{$baseURL}administrator/templates/add" class="btn btn-primary btn-sm pull-right">Új Sablon</a>
</h2>
<br>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Kész!</strong> {$success}</div>
{/if}
{if="'' != $templates"}
    <div class="panel-group" id="accordion">
        {loop="templates"}
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#app-{$key}" style="display:block;">
                            {$key} <span class="pull-right">{$value|count} sablon</span>
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
                                            <a href="{$baseURL}administrator/templates/edit/application:{$value.application}/template:{$value.filename}" title="Sablon szerkesztése"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;
                                            <a href="javascript:linkConfirm('{$baseURL}administrator/templates/remove/application:{$value.application}/template:{$value.filename}');" title="Végleges eltávolítás" class="text-danger"><span class="glyphicon glyphicon-trash"></span></a>
                                        {else}
                                            <a href="{$baseURL}administrator/templates/edit/application:{$value.application}/template:{$value.filename}" title="Sablon megtekintése"><span class="glyphicon glyphicon-eye-open"></span></a>
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
                    <a href="{$baseURL}administrator/templates/start:{$prevStart}" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i>&nbsp;előző</a>
                {/if}
                {if="isset($nextStart)"}
                    <a href="{$baseURL}administrator/templates/start:{$nextStart}" class="btn btn-default">következő&nbsp;<i class="glyphicon glyphicon-arrow-right"></i></a>
                {/if}
            </div>
        </div>
    {/if}
{else}
    <div class="alert alert-info">
        <strong>Nincsenek Sablonok</strong>. Készíthetsz új Sablont, ehhez kattints az <em>Új Sablon</em> gombra felül.
    </div>
{/if}