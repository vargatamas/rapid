<h1>Layout összerendelése</h1>
<h5>Megjegyzés: ha nincs Layout kapcsolva egy alkalmazáshoz, akkor a Rapid megpróbálja betölteni a <em>layout.appname.tpl</em> Layoutot. Ha nincs kapcsolt és <em>layout.appname.tpl</em> Layout sem, akkor a Rapid betölti a <em>defaultLayout</em>-ot.</h5>
<a href="{$baseURL}administrator/linkLayouts/add" class="btn btn-primary">Új Layout összerendelés</a>
<br /><br />
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Kész!</strong> {$success}</div>
{/if}
{if="'' != $linkedlayouts"}
    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>Alkalmazás</th>
                    <th>Layout fájlneve</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {loop="linkedlayouts"}
                    <tr>
                        <td>{$key}</td>
                        <td>{$value}</td>
                        <td>
                            <a href="{$baseURL}administrator/linkLayouts/edit/application:{$key}" title="Layout összerendelés szerkesztése"><span class="glyphicon glyphicon-pencil"></span></a>
                            <a href="javascript:linkConfirm('{$baseURL}administrator/linkLayouts/remove/application:{$key}');" title="Layout összerendelés eltávolítása"><span class="glyphicon glyphicon-trash"></span></a>
                        </td>
                    </tr>
                {/loop}
            </tbody>
        </table>
    </div>
{else}
    <div class="alert alert-info">
        <strong>Nincsenek Layout összerendelések</strong>. Hozzá tudsz adni új Layout összerendeléseket, csak kattints az <em>Új Layout összerendelés</em> gombra felül.
    </div>
{/if}