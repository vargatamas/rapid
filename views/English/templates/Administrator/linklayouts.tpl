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
                        <td>{$value.from}</td>
                        <td>{$value.to}</td>
                        <td>
                            <a href="{$baseURL}administrator/linkLayouts/edit/application:{$value.from}" title="Edit Layout link"><span class="glyphicon glyphicon-pencil"></span></a>
                            <a href="javascript:linkConfirm('{$baseURL}administrator/linkLayouts/remove/application:{$value.from}');" title="Remove Layout link"><span class="glyphicon glyphicon-trash"></span></a>
                        </td>
                    </tr>
                {/loop}
            </tbody>
        </table>
    </div>
    {if="isset($prevStart) || isset($nextStart) || isset($page)"}
        <div class="container text-center">
            <strong>{$page}. page</strong><br /><br />
            <div class="btn-group">
                {if="isset($prevStart)"}
                    <a href="{$baseURL}administrator/linkLayouts/start:{$prevStart}" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i>&nbsp;previous</a>
                {/if}
                {if="isset($nextStart)"}
                    <a href="{$baseURL}administrator/linkLayouts/start:{$nextStart}" class="btn btn-default">next&nbsp;<i class="glyphicon glyphicon-arrow-right"></i></a>
                {/if}
            </div>
        </div>
    {/if}
{else}
    <div class="alert alert-info">
        <strong>No Layout links</strong> found. You can add new Layout links, just click on <em>Add Layout link</em> button above.
    </div>
{/if}