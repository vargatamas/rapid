<h1>Link Layouts</h1>
<h5>Note: if no Layout linked to an application, Rapid tries to load the <em>layout.appname.tpl</em> Layout. When there is no linked and <em>layout.appname.tpl</em>, Rapid load the <em>defaultLayout</em>.</h5>
<a href="{$baseURL}administrator/linkLayouts/add" class="btn btn-primary">Add Layout link</a>
<br /><br />
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Success!</strong> {$success}</div>
{/if}
{if="'' != $linkedlayouts"}
    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>Application</th>
                    <th>Layout filename</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {loop="linkedlayouts"}
                    <tr>
                        <td>{$key}</td>
                        <td>{$value}</td>
                        <td>
                            <a href="{$baseURL}administrator/linkLayouts/edit/application:{$key}" title="Edit Layout link"><span class="glyphicon glyphicon-pencil"></span></a>
                            <a href="javascript:linkConfirm('{$baseURL}administrator/linkLayouts/remove/application:{$key}');" title="Remove Layout link"><span class="glyphicon glyphicon-trash"></span></a>
                        </td>
                    </tr>
                {/loop}
            </tbody>
        </table>
    </div>
{else}
    <div class="alert alert-info">
        <strong>No Layout links</strong> found. You can add new Layout links, just click on <em>Add Layout link</em> button above.
    </div>
{/if}