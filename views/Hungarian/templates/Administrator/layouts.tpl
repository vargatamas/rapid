<h1>Layoutok menedzselése</h1>
<h5>Készíts új Layoutot vagy szerkessz egy meglevőt.</h5>
<a href="{$baseURL}administrator/layouts/add" class="btn btn-primary">Új Layout</a>
<br /><br />
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Kész!</strong> {$success}</div>
{/if}
{if="'' != $layouts"}
    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>Layout fájlneve</th>
                    <th>Írhatóság</th>
                    <th>Utolsó módosítás</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {loop="layouts"}
                    <tr>
                        <td>{$value.name}</td>
                        <td><span class="glyphicon glyphicon-{if="$value.writable"}ok{else}remove{/if}"></span></td>
                        <td>{$value.last_modified}</td>
                        <td>
                            {if="$value.writable"}
                                <a href="{$baseURL}administrator/layouts/edit/filename:{$value.name}" title="Layout szerkesztése"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;
                                <a href="javascript:linkConfirm('{$baseURL}administrator/layouts/remove/filename:{$value.name}');" title="Layout eltávolítása"><span class="glyphicon glyphicon-trash"></span></a>
                            {else}
                                <a href="{$baseURL}administrator/layouts/edit/filename:{$value.name}" title="Layout megtekintése"><span class="glyphicon glyphicon-eye-open"></span></a>
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
                    <a href="{$baseURL}administrator/layouts/start:{$prevStart}" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i>&nbsp;előző</a>
                {/if}
                {if="isset($nextStart)"}
                    <a href="{$baseURL}administrator/layouts/start:{$nextStart}" class="btn btn-default">következő&nbsp;<i class="glyphicon glyphicon-arrow-right"></i></a>
                {/if}
            </div>
        </div>
    {/if}
{else}
    <div class="alert alert-info">
        <strong>Nincsenek Layoutok</strong>. Hozzá tudsz adni új Layoutot, kattints az <em>Új Layout</em> gombra felül.
    </div>
{/if}