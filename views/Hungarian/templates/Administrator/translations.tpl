<h1>Fordítások menedzselése</h1>
<h5>Fordíts le üzeneteket, amelyek a Rapidon mennek keresztül, bármilyen nyelvre.</h5>
<a href="{$baseURL}administrator/translations/add" class="btn btn-primary">Új Fordítás</a>
<br /><br />
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Kész!</strong> {$success}</div>
{/if}
{if="'' != $translations"}
    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>Cél nyelv</th>
                    <th>Miről</th>
                    <th>Mire</th>
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
                            <a href="{$baseURL}administrator/translations/edit/language:{$value.language}/index:{$value.index}" title="Fordítás szerkesztése"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;
                            <a href="javascript:linkConfirm('{$baseURL}administrator/translations/remove/language:{$value.language}/index:{$value.index}');" title="Fordítás eltávolítása"><span class="glyphicon glyphicon-trash"></span></a>
                        </td>
                    </tr>
                {/loop}
            </tbody>
        </table>
    </div>
{else}
    <div class="alert alert-info">
        <strong>Nincsenek Fordítások</strong>. Készíthetsz új fordítást, csak kattints az <em>Új Fordítás</em> gombra felül.
    </div>
{/if}