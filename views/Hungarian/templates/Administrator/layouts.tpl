<h2>
Layoutok menedzselése&nbsp;<small>Készíts új Layoutot vagy szerkessz egy meglevőt.</small>
<a href="{$baseURL}administrator/layouts/add" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Új Layout</a>
</h2>
<br>
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
                        <td><i class="fa fa-{if="$value.writable"}check{else}remove{/if}"></i></td>
                        <td>{$value.last_modified}</td>
                        <td class="text-right">
                            {if="$value.writable"}
                                <a href="{$baseURL}administrator/layouts/edit/filename:{$value.name}" title="Layout szerkesztése"><i class="fa fa-pencil"></i></a>&nbsp;
                                <a href="javascript:linkConfirm('{$baseURL}administrator/layouts/remove/filename:{$value.name}');" title="Layout eltávolítása" class="text-danger"><i class="fa fa-trash-o"></i></a>
                            {else}
                                <a href="{$baseURL}administrator/layouts/edit/filename:{$value.name}" title="Layout megtekintése"><i class="fa fa-eye"></i></a>
                            {/if}
                        </td>
                    </tr>
                {/loop}
            </tbody>
        </table>
    </div>
    {if="isset($prevStart) || isset($nextStart) || isset($page)"}
        <div class="text-center">
            <strong>{$page}. oldal</strong><br /><br />
            <div class="btn-group">
                {if="isset($prevStart)"}
                    <a href="{$baseURL}administrator/layouts/start:{$prevStart}" class="btn btn-default"><i class="fa fa-angle-left"></i>&nbsp;előző</a>
                {/if}
                {if="isset($nextStart)"}
                    <a href="{$baseURL}administrator/layouts/start:{$nextStart}" class="btn btn-default">következő&nbsp;<i class="fa fa-angle-right"></i></a>
                {/if}
            </div>
        </div>
    {/if}
{else}
    <div class="alert alert-info">
        <strong>Nincsenek Layoutok</strong>. Hozzá tudsz adni új Layoutot, kattints az <em>Új Layout</em> gombra felül.
    </div>
{/if}