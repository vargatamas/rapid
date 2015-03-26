<h2>
Manage Translations&nbsp;<small>Translate messages, which goes through Rapid, to any language.</small>
<a href="{$baseURL}administrator/translations/add" class="btn btn-primary btn-sm pull-right">New Translation</a>
</h2>
<br>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Success!</strong> {$success}</div>
{/if}
{if="'' != $translations"}
    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>Language</th>
                    <th>From</th>
                    <th>To</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {loop="translations"}
                    <tr>
                        <td>{$value.language}</td>
                        <td>{$value.from|mb_substr:0,80,'utf-8'}{if="80 < strlen($value.from)"}..{/if}</td>
                        <td>{$value.to|mb_substr:0,80,'utf-8'}{if="80 < strlen($value.to)"}..{/if}</td>
                        <td class="text-right">
                            <a href="{$baseURL}administrator/translations/edit/language:{$value.language}/index:{$value.index}" title="Edit Translation"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;
                            <a href="javascript:linkConfirm('{$baseURL}administrator/translations/remove/language:{$value.language}/index:{$value.index}');" title="Remove Translation" class="text-danger"><span class="glyphicon glyphicon-trash"></span></a>
                        </td>
                    </tr>
                {/loop}
            </tbody>
        </table>
    </div>
{else}
    <div class="alert alert-info">
        <strong>No Translations</strong> found. You can add new Translation, just click on <em>New Translation</em> button above.
    </div>
{/if}