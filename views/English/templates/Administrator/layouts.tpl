<h1>Manage Layouts</h1>
<h5>Feel free to create new Layout or edit an existing.</h5>
<a href="{$baseURL}administrator/layouts/add" class="btn btn-primary">Add Layout</a>
<br /><br />
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Success!</strong> {$success}</div>
{/if}
{if="'' != $layouts"}
    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>Layout filename</th>
                    <th>Writable</th>
                    <th>Last modified</th>
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
                                <a href="{$baseURL}administrator/layouts/edit/filename:{$value.name}" title="Edit Layout"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;
                                <a href="javascript:linkConfirm('{$baseURL}administrator/layouts/remove/filename:{$value.name}');" title="Remove Layout"><span class="glyphicon glyphicon-trash"></span></a>
                            {else}
                                <a href="{$baseURL}administrator/layouts/edit/filename:{$value.name}" title="View Layout"><span class="glyphicon glyphicon-eye-open"></span></a>
                            {/if}
                        </td>
                    </tr>
                {/loop}
            </tbody>
        </table>
    </div>
    {if="isset($prevStart) || isset($nextStart) || isset($page)"}
        <div class="text-center">
            <strong>{$page}. page</strong><br /><br />
            <div class="btn-group">
                {if="isset($prevStart)"}
                    <a href="{$baseURL}administrator/layouts/start:{$prevStart}" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i>&nbsp;previous</a>
                {/if}
                {if="isset($nextStart)"}
                    <a href="{$baseURL}administrator/layouts/start:{$nextStart}" class="btn btn-default">next&nbsp;<i class="glyphicon glyphicon-arrow-right"></i></a>
                {/if}
            </div>
        </div>
    {/if}
{else}
    <div class="alert alert-info">
        <strong>No Layouts</strong> found. You can add new Layouts, just click on <em>Add Layout</em> button above.
    </div>
{/if}