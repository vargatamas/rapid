<h2>
Fordítások menedzselése&nbsp;<small>Fordíts le üzeneteket, amelyek a Rapidon mennek keresztül, bármilyen nyelvre.</small>
<a href="{$baseURL}administrator/translations/add" class="btn btn-primary btn-sm pull-right">Új Fordítás</a>
</h2>
<br>
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
                        <td>{$value.from|mb_substr:0,80,'utf-8'}{if="80 < strlen($value.from)"}..{/if}</td>
                        <td>{$value.to|mb_substr:0,80,'utf-8'}{if="80 < strlen($value.to)"}..{/if}</td>
                        <td class="text-right">
                            <a href="{$baseURL}administrator/translations/edit/language:{$value.language}/index:{$value.index}" title="Fordítás szerkesztése"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;
                            <a href="javascript:linkConfirm('{$baseURL}administrator/translations/remove/language:{$value.language}/index:{$value.index}');" title="Fordítás eltávolítása" class="text-danger"><span class="glyphicon glyphicon-trash"></span></a>
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