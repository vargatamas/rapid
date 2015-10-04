<h2>
    Manage Application Sources
</h2>
<h5>Do you want some unique javascript plugin or an out-standing css for your application? Attach these Sources.</h5>
<br>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Success!</strong> {$success}</div>
{/if}
{if="'' != $sources"}
    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>Application</th>
                    <th>Writable</th>
                    <th>Source last modified</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {loop="sources"}
                    <tr>
                        <td>{$value.name}</td>
                        <td><i class="fa fa-{if="$value.writable"}check{else}remove{/if}"></i></td>
                        <td>{$value.last_modified}</td>
                        <td class="text-right">
                            {if="$value.writable"}
                                <a href="{$baseURL}administrator/sources/edit/application:{$value.name}" title="Edit Source"><i class="fa fa-pencil"></i></a>
                                {if="'-' != $value.last_modified"}
                                    &nbsp;<a href="javascript:linkConfirm('{$baseURL}administrator/sources/clear/application:{$value.name}');" title="Clear Source" class="text-danger"><i class="fa fa-trash-o"></i></a>
                                {/if}
                            {else}
                                <a href="{$baseURL}administrator/sources/edit/application:{$value.name}" title="View Source"><i class="fa fa-eye"></i></a>
                            {/if}
                        </td>
                    </tr>
                {/loop}
            </tbody>
        </table>
    </div>
{else}
    <div class="alert alert-info">
        <strong>No Application Sources</strong> found.
    </div>
{/if}