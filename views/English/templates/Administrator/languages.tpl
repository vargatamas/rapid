<h2>
Manage Languages&nbsp;<small>Check or create Languages of your Site.</small>
<a data-toggle="modal" href="#new-language" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> New Language</a>
</h2>
<br>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Success!</strong> {$success}</div>
{/if}
{if="'' != $languages"}
    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>Language</th>
                    <th>Translations</th>
                    <th>Layouts</th>
                    <th>Templates</th>
                    <th>Default</th>
                    <th>Active</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {loop="languages"}
                    <tr{if="$value.isActive"} class="active"{/if}>
                        <td>{$key}</td>
                        <td>{$value.translationsCount}</td>
                        <td>{$value.layoutsCount}</td>
                        <td>
                            {if="0 < count($value.templates)"}
                                {loop="value.templates"}
                                    {$key}: {$value}<br />
                                {/loop}
                            {else}
                                ?
                            {/if}
                        </td>
                        <td><i class="fa fa-{if="$value.isDefault"}check{else}remove{/if}"></i></td>
                        <td><i class="fa fa-{if="$value.isActive"}check{else}remove{/if}"></i></td>
                        <td class="text-right">
                            <a href="javascript:linkConfirm('{$baseURL}administrator/languages/remove/language:{$key}');" title="Remove Language" class="text-danger"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                {/loop}
            </tbody>
        </table>
    </div>
{else}
    <div class="alert alert-info">
        <strong>No Languages</strong> found. You can add new Languages, just click on <em>New Language</em> button above.
    </div>
{/if}
<!-- Modal -->
<div class="modal fade" id="new-language" tabindex="-1" role="dialog" aria-labelledby="mklang-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" method="post" action="{$baseURL}administrator/languages/add">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="mklang-modal-title">New Language</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="mklang-name" class="col-lg-4 control-label">Name</label>
                        <div class="col-lg-8">
                            <input type="text" name="language[name]" class="form-control" id="mklang-name" placeholder="German" />
                        </div>
                    </div>
                    <div class="form-group">
                		<label for="mklang-copy" class="col-lg-4 control-label">Copy templates</label>
                		<div class="col-lg-8">
                			<div class="checkbox">
                				<label>
                                    <input type="checkbox" name="language[copy]" id="mklang-copy" checked="checked" />
                                    <small>Copy Templates and Layouts from default Language</small>
                                </label>
                			</div>
                		</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>