<h2>
Sablonok menedzselése&nbsp;<small>Tudsz készíteni, szerkeszteni vagy törölni Sablonokat.</small>
<a href="{$baseURL}administrator/templates/add" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Új Sablon</a>
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
                            	<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 text-center item moving">
                                    <a href="{$baseURL}administrator/templates/edit/application:{$value.application}/template:{$value.filename}">
                                        <div class="icon"><i class="fa fa-file"></i></div>
                                        <div class="title">{$value.template}</div>
                                        <div class="description">{$value.last_modified}</div>
                                    </a>
                                    <div class="actions">
                                        {if="$value.writable"}
                                            <a href="{$baseURL}administrator/templates/edit/application:{$value.application}/template:{$value.filename}" title="Sablon szerkesztése"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
                                            <a href="javascript:linkConfirm('{$baseURL}administrator/templates/remove/application:{$value.application}/template:{$value.filename}');" title="Végleges eltávolítás" class="text-danger"><i class="fa fa-trash-o"></i></a>
                                        {else}
                                            <a href="{$baseURL}administrator/templates/edit/application:{$value.application}/template:{$value.filename}" title="Sablon megtekintése"><i class="fa fa-eye"></i></a>
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
            <strong>{$page}. oldal</strong><br /><br />
            <div class="btn-group">
                {if="isset($prevStart)"}
                    <a href="{$baseURL}administrator/templates/start:{$prevStart}" class="btn btn-default"><i class="fa fa-angle-left"></i>&nbsp;előző</a>
                {/if}
                {if="isset($nextStart)"}
                    <a href="{$baseURL}administrator/templates/start:{$nextStart}" class="btn btn-default">következő&nbsp;<i class="fa fa-angle-right"></i></a>
                {/if}
            </div>
        </div>
    {/if}
{else}
    <div class="alert alert-info">
        <strong>Nincsenek Sablonok</strong>. Készíthetsz új Sablont, ehhez kattints az <em>Új Sablon</em> gombra felül.
    </div>
{/if}