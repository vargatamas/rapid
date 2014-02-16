<h1>Manage Translations</h1>
<h5>Translate messages, which goes through Rapid, to any language.</h5>
<a href="{$baseURL}administrator/translations/add" class="btn btn-primary">New Translation</a>
<br /><br />
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
                        <td>{$value.from|substr:0,60}</td>
                        <td>{$value.to|substr:0,60}</td>
                        <td>
                            <a href="javascript:linkConfirm('{$baseURL}administrator/translations/remove/language:{$value.language}/index:{$value.index}');" title="Remove Translation"><span class="glyphicon glyphicon-trash"></span></a>
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