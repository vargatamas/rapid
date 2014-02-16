<h1>Útvonalak</h1>
<h5>Nem tetszik az alapértelmezett URI útvonal? Definiáld újra.</h5>
<br />
{if="'' != $error"}
    <div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
{if="'' != $success"}
    <div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Kész!</strong> {$success}</div>
{/if}
<a href="{$baseURL}administrator/routes/add" class="btn btn-primary">Új útvonal</a>&nbsp;
<br /><br />
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
                        <td>
                            <a href="javascript:linkConfirm('{$baseURL}administrator/routes/remove/id:{$value.id}');" title="Útvonal eltávolítása"><span class="glyphicon glyphicon-trash"></span></a>
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