<h1>Manage Sources</h1>
<h5>Do you want some unique javascript plugin or an out-standing css for your application? Attach these Sources.</h5>
<br /><br />
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
                        <td><span class="glyphicon glyphicon-{if="$value.writable"}ok{else}remove{/if}"></span></td>
                        <td>{$value.last_modified}</td>
                        <td>
                            {if="$value.writable"}
                                <a href="{$baseURL}administrator/sources/edit/application:{$value.name}" title="Edit Source"><span class="glyphicon glyphicon-pencil"></span></a>
                                {if="'-' != $value.last_modified"}
                                    &nbsp;<a href="javascript:linkConfirm('{$baseURL}administrator/sources/clear/application:{$value.name}');" title="Clear Source"><span class="glyphicon glyphicon-trash"></span></a>
                                {/if}
                            {else}
                                <a href="{$baseURL}administrator/sources/edit/application:{$value.name}" title="View Source"><span class="glyphicon glyphicon-eye-open"></span></a>
                            {/if}
                        </td>
                    </tr>
                {/loop}
            </tbody>
        </table>
    </div>
{else}
    <div class="alert alert-info">
        <strong>No Sources</strong> found.
    </div>
{/if}