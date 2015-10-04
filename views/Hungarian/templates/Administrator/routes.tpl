<h2>
Útvonalak&nbsp;<small>Nem tetszik az alapértelmezett URI útvonal? Definiáld újra.</small>
<a href="{$baseURL}administrator/routes/add" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Új útvonal</a>&nbsp;
</h2>
<br />
{if="'' != $error"}
    <div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
{if="'' != $success"}
    <div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Kész!</strong> {$success}</div>
{/if}
<br>
{if="isset($routes) && 0 < count($routes)"}
    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Honnan</th>
                    <th>Hova</th>
                    <th>Felhasználónév</th>
                    <th>Utolsó módosítás</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {loop="routes"}
                    <tr>
                        <td>{$value.id}</td>
                        <td>{$url}/{$value.from}</td>
                        <td>{$url}/{$value.to}</td>
                        <td>{$value.user}</td>
                        <td>{$value.last_modified}</td>
                        <td class="text-right">
                            <a href="javascript:linkConfirm('{$baseURL}administrator/routes/remove/id:{$value.id}');" title="Útvonal eltávolítása" class="text-danger"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                {/loop}
            </tbody>
        </table>
    </div>
{else}
    <div class="alert alert-info">
        <strong>Nincsenek Útvonalak</strong> beállítva egyelőre.
    </div>
{/if}