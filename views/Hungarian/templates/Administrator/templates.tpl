<h1>Sablonok menedzselése</h1>
<h5>Tudsz készíteni, szerkeszteni vagy törölni Sablonokat.</h5>
<a href="{$baseURL}administrator/templates/add" class="btn btn-primary">Új Sablon</a>
<br /><br />
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Kész!</strong> {$success}</div>
{/if}
{if="'' != $templates"}
    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>Alkalmazás</th>
                    <th>Sablon</th>
                    <th>Írhatóság</th>
                    <th>Utolsó módosítás</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {loop="templates"}
                    <tr>
                        <td>{$value.application}</td>
                        <td>{$value.template}</td>
                        <td><span class="glyphicon glyphicon-{if="$value.writable"}ok{else}remove{/if}"></span></td>
                        <td>{$value.last_modified}</td>
                        <td>
                            {if="$value.writable"}
                                <a href="{$baseURL}administrator/templates/edit/application:{$value.application}/template:{$value.template}" title="Sablon szerkesztése"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;
                                <a href="javascript:linkConfirm('{$baseURL}administrator/templates/remove/application:{$value.application}/template:{$value.template}');" title="Sablon eltávolítása"><span class="glyphicon glyphicon-trash"></span></a>
                            {else}
                                <a href="{$baseURL}administrator/templates/edit/application:{$value.application}/template:{$value.template}" title="Sablon megtekintése"><span class="glyphicon glyphicon-eye-open"></span></a>
                            {/if}
                        </td>
                    </tr>
                {/loop}
            </tbody>
        </table>
    </div>
    {if="isset($prevStart) || isset($nextStart) || isset($page)"}
        <div class="container text-center">
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