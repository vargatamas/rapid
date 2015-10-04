<h2>
Alkalmazások
<a href="{$baseURL}administrator/applications/add" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Új Alkalmazás</a>
</h2>
<br>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Kész!</strong> {$success}</div>
{/if}
{if="'' != $applications"}
    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>Alkalmazás</th>
                    <th>Nyelv</th>
                    <th>Források</th>
                    <th>Alapértelmezettség</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {loop="applications"}
                    <tr>
                        <td>{$key}</td>
                        <td>{$value.languages}</td>
                        <td>{$value.sources}</td>
                        <td><i class="fa fa-{if="$value.default"}check{else}remove{/if}"></i></td>
                        <td class="text-right">
                            <a href="{$baseURL}administrator/applications/edit/application:{$key}" title="Alkalmazás szerkesztése"><i class="fa fa-pencil"></i></a>&nbsp;
                            <a href="javascript:linkConfirm('{$baseURL}administrator/applications/remove/application:{$key}');" title="Alkalmazás eltávolítása" class="text-danger"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                {/loop}
            </tbody>
        </table>
    </div>
{else}
    <div class="alert alert-info">
        <strong>Nincsenek Alkalmazások</strong>. Új Alkalmazás hozzáadásához kattints az <em>Új Alkalmazás</em> gombra felül.
    </div>
{/if}