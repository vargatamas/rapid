<h2>
Manage Layouts&nbsp;<small>Feel free to create new Layout or edit an existing.</small>
<a href="{$baseURL}administrator/layouts/add" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Add Layout</a>
</h2>
<br>
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
                        <td><i class="fa fa-{if="$value.writable"}check{else}remove{/if}"></i></td>
                        <td>{$value.last_modified}</td>
                        <td class="text-right">
                            {if="$value.writable"}
                                <a href="{$baseURL}administrator/layouts/edit/filename:{$value.name}" title="Edit Layout"><i class="fa fa-pencil"></i></a>&nbsp;
                                <a href="javascript:linkConfirm('{$baseURL}administrator/layouts/remove/filename:{$value.name}');" title="Remove Layout" class="text-danger"><i class="fa fa-trash-o"></i></a>
                            {else}
                                <a href="{$baseURL}administrator/layouts/edit/filename:{$value.name}" title="View Layout"><i class="fa fa-eye"></i></a>
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
                    <a href="{$baseURL}administrator/layouts/start:{$prevStart}" class="btn btn-default"><i class="fa fa-angle-left"></i>&nbsp;previous</a>
                {/if}
                {if="isset($nextStart)"}
                    <a href="{$baseURL}administrator/layouts/start:{$nextStart}" class="btn btn-default">next&nbsp;<i class="fa fa-angle-right"></i></a>
                {/if}
            </div>
        </div>
    {/if}
{else}
    <div class="alert alert-info">
        <strong>No Layouts</strong> found. You can add new Layouts, just click on <em>Add Layout</em> button above.
    </div>
{/if}