<h2>
Routes&nbsp;<small>You do not like the default URI path? Redefine it.</small>
<a href="{$baseURL}administrator/routes/add" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Add Route</a>&nbsp;
</h2>
<br>
{if="'' != $error"}
    <div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
{if="'' != $success"}
    <div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Success!</strong> {$success}</div>
{/if}
{if="isset($routes) && 0 < count($routes)"}
    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>#</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Username</th>
                    <th>Last modified</th>
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
                            <a href="javascript:linkConfirm('{$baseURL}administrator/routes/remove/id:{$value.id}');" title="Remove Route" class="text-danger"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                {/loop}
            </tbody>
        </table>
    </div>
{else}
    <div class="alert alert-info">
        <strong>No Routes</strong> are set.
    </div>
{/if}