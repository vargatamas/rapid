<h1>E-mail Sablonok menedzselése</h1>
<h5>Hozz létre, szerkessz vagy törölj e-mail sablonokat.</h5>
<a href="{$baseURL}administrator/mails/add" class="btn btn-primary">Új E-mail Sablon</a>
<br /><br />
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Kész!</strong> {$success}</div>
{/if}
{if="'' != $mails"}
    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>Sablon</th>
                    <th>Változók</th>
                    <th>Írhatóság</th>
                    <th>Utoljára módosítva</th>
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
                                <a href="{$baseURL}administrator/mails/edit/mail:{$value.template}" title="E-mail Sablon módosítása"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;
                                <a href="javascript:linkConfirm('{$baseURL}administrator/mails/remove/mail:{$value.template}');" title="E-mail Sablon eltávolítása"><span class="glyphicon glyphicon-trash"></span></a>
                            {else}
                                <a href="{$baseURL}administrator/mails/edit/mail:{$value.template}" title="E-mail Sablon megtekintése"><span class="glyphicon glyphicon-eye-open"></span></a>
                            {/if}
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
                    <a href="{$baseURL}administrator/mails/start:{$prevStart}" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i>&nbsp;előző</a>
                {/if}
                {if="isset($nextStart)"}
                    <a href="{$baseURL}administrator/mails/start:{$nextStart}" class="btn btn-default">következő&nbsp;<i class="glyphicon glyphicon-arrow-right"></i></a>
                {/if}
            </div>
        </div>
    {/if}
{else}
    <div class="alert alert-info">
        <strong>Nincsenek E-mal Sablonok</strong>. Készíthetsz új E-mail Sablont, ehhez kattints az <em>Új E-mail Sablon</em> gombra felül.
    </div>
{/if}