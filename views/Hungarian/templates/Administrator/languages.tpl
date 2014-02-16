<h1>Nyelvek menedzselése</h1>
<h5>Ellenőrizz vagy készíts nyelvet a weboldalhoz.</h5>
<a data-toggle="modal" href="#new-language" class="btn btn-primary">Új Nyelv</a>
<br /><br />
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Kész!</strong> {$success}</div>
{/if}
{if="'' != $languages"}
    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>Nyelv</th>
                    <th>Fordítások</th>
                    <th>Layoutok</th>
                    <th>Sablonok</th>
                    <th>Alapértelmezettség</th>
                    <th>Aktív</th>
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
                        <td><span class="glyphicon glyphicon-{if="$value.isDefault"}ok{else}remove{/if}"></span></td>
                        <td><span class="glyphicon glyphicon-{if="$value.isActive"}ok{else}remove{/if}"></span></td>
                        <td><a href="javascript:linkConfirm('{$baseURL}administrator/languages/remove/language:{$key}');" title="Nyelv eltávolítása"><span class="glyphicon glyphicon-trash"></span></a></td>
                    </tr>
                {/loop}
            </tbody>
        </table>
    </div>
{else}
    <div class="alert alert-info">
        <strong>Nincsenek Nyelvek</strong>. Hozzá tudsz adni új nyelveket, ehhez kattints a <em>Új Nyelv</em> gombra felül.
    </div>
{/if}
<!-- Modal -->
<div class="modal fade" id="new-language" tabindex="-1" role="dialog" aria-labelledby="mklang-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" method="post" action="{$baseURL}administrator/languages/add">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="mklang-modal-title">Új Nyelv</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="mklang-name" class="col-lg-4 control-label">Név</label>
                        <div class="col-lg-8">
                            <input type="text" name="language[name]" class="form-control" id="mklang-name" placeholder="Német" />
                        </div>
                    </div>
                    <div class="form-group">
                		<label for="mklang-copy" class="col-lg-4 control-label">Sablonok másolása</label>
                		<div class="col-lg-8">
                			<div class="checkbox">
                				<input type="checkbox" name="language[copy]" id="mklang-copy" checked="checked" />
                                <small>(Sablonok és Layout-ok másolása az alapértelmezett nyelvről)</small>
                			</div>
                		</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Bezárás</button>
                    <button type="submit" class="btn btn-primary">Mentés</button>
                </div>
            </form>
        </div>
    </div>
</div>