<h1>Manage Mail Templates</h1>
<h5>You can create, edit or remove the Mail Templates.</h5>
<a href="{$baseURL}administrator/mails/add" class="btn btn-primary">New Mail Template</a>
<br /><br />
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Success!</strong> {$success}</div>
{/if}
{if="'' != $mails"}
    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>Template</th>
                    <th>Variables</th>
                    <th>Writable</th>
                    <th>Last modified</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {loop="mails"}
                    <tr>
                        <td>{$value.template}</td>
                        <td>{$value.variables}</td>
                        <td><span class="glyphicon glyphicon-{if="$value.writable"}ok{else}remove{/if}"></span></td>
                        <td>{$value.last_modified}</td>
                        <td>
                            {if="$value.writable"}
                                <a href="{$baseURL}administrator/mails/edit/mail:{$value.template}" title="Edit Mail Template"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;
                                <a href="javascript:linkConfirm('{$baseURL}administrator/mails/remove/mail:{$value.template}');" title="Remove Mail Template"><span class="glyphicon glyphicon-trash"></span></a>
                            {else}
                                <a href="{$baseURL}administrator/mails/edit/mail:{$value.template}" title="View Mail Template"><span class="glyphicon glyphicon-eye-open"></span></a>
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
                    <a href="{$baseURL}administrator/mails/start:{$prevStart}" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i>&nbsp;previous</a>
                {/if}
                {if="isset($nextStart)"}
                    <a href="{$baseURL}administrator/mails/start:{$nextStart}" class="btn btn-default">next&nbsp;<i class="glyphicon glyphicon-arrow-right"></i></a>
                {/if}
            </div>
        </div>
    {/if}
{else}
    <div class="alert alert-info">
        <strong>No Mail Templates</strong> found. You can add new Mail Templates, just click on <em>New Mail Template</em> button above.
    </div>
{/if}