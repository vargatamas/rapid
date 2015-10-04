<h2>
E-mail Sablonok menedzselése&nbsp;<small>Hozz létre, szerkessz vagy törölj e-mail sablonokat.</small>
<a href="{$baseURL}administrator/mails/add" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Új E-mail Sablon</a>
</h2>
<br>
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
                        <td><i class="fa fa-{if="$value.writable"}check{else}remove{/if}"></i></td>
                        <td>{$value.last_modified}</td>
                        <td class="text-right">
                            {if="$value.writable"}
                                <a href="{$baseURL}administrator/mails/edit/mail:{$value.template}" title="E-mail Sablon módosítása"><i class="fa fa-pencil"></i></a>&nbsp;
                                <a href="javascript:linkConfirm('{$baseURL}administrator/mails/remove/mail:{$value.template}');" title="E-mail Sablon eltávolítása" class="text-danger"><i class="fa fa-trash-o"></i></a>
                            {else}
                                <a href="{$baseURL}administrator/mails/edit/mail:{$value.template}" title="E-mail Sablon megtekintése"><i class="fa fa-eye"></i></a>
                            {/if}
                        </td>
                    </tr>
                {/loop}
            </tbody>
        </table>
    </div>
    {if="isset($prevStart) || isset($nextStart) || isset($page)"}
        <div class="text-center">
            <strong>{$page}. oldal</strong><br /><br />
            <div class="btn-group">
                {if="isset($prevStart)"}
                    <a href="{$baseURL}administrator/mails/start:{$prevStart}" class="btn btn-default"><i class="fa fa-angle-left"></i>&nbsp;előző</a>
                {/if}
                {if="isset($nextStart)"}
                    <a href="{$baseURL}administrator/mails/start:{$nextStart}" class="btn btn-default">következő&nbsp;<i class="fa fa-angle-right"></i></a>
                {/if}
            </div>
        </div>
    {/if}
{else}
    <div class="alert alert-info">
        <strong>Nincsenek E-mal Sablonok</strong>. Készíthetsz új E-mail Sablont, ehhez kattints az <em>Új E-mail Sablon</em> gombra felül.
    </div>
{/if}