<h1>Források menedzselése</h1>
<h5>Szeretnél néhány egyedülálló javascript plugint vagy stílusfájlt az alkalmazásodnak? Rögzítsd ezeket a Forrásokat.</h5>
<br /><br />
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Kész!</strong> {$success}</div>
{/if}
{if="'' != $sources"}
    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>Alkalmazás</th>
                    <th>Írhatóság</th>
                    <th>Utolsó módosítás</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {loop="sources"}
                    <tr>
                        <td>{$value.name}</td>
                        <td><span class="glyphicon glyphicon-{if="$value.writable"}ok{else}remove{/if}"></span></td>
                        <td>{$value.last_modified}</td>
                        <td>
                            {if="$value.writable"}
                                <a href="{$baseURL}administrator/sources/edit/application:{$value.name}" title="Források szerkesztése"><span class="glyphicon glyphicon-pencil"></span></a>
                                {if="'-' != $value.last_modified"}
                                    &nbsp;<a href="javascript:linkConfirm('{$baseURL}administrator/sources/clear/application:{$value.name}');" title="Források ürítése"><span class="glyphicon glyphicon-trash"></span></a>
                                {/if}
                            {else}
                                <a href="{$baseURL}administrator/sources/edit/application:{$value.name}" title="Források megtekintése"><span class="glyphicon glyphicon-eye-open"></span></a>
                            {/if}
                        </td>
                    </tr>
                {/loop}
            </tbody>
        </table>
    </div>
{else}
    <div class="alert alert-info">
        <strong>Nincsenek Források</strong> hozzáadva.
    </div>
{/if}