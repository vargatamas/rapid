<h2>Alkalmazás Források menedzselése</h2>
<h5>Szeretnél néhány egyedülálló javascript plugint vagy stílusfájlt az alkalmazásodnak? Rögzítsd ezeket a Forrásokat.</h5>
<br>
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
                        <td><i class="fa fa-{if="$value.writable"}check{else}remove{/if}"></i></td>
                        <td>{$value.last_modified}</td>
                        <td class="text-right">
                            {if="$value.writable"}
                                <a href="{$baseURL}administrator/sources/edit/application:{$value.name}" title="Források szerkesztése"><i class="fa fa-pencil"></i></a>
                                {if="'-' != $value.last_modified"}
                                    &nbsp;<a href="javascript:linkConfirm('{$baseURL}administrator/sources/clear/application:{$value.name}');" title="Források ürítése" class="text-danger"><i class="fa fa-trash-o"></i></a>
                                {/if}
                            {else}
                                <a href="{$baseURL}administrator/sources/edit/application:{$value.name}" title="Források megtekintése"><i class="fa fa-eye"></i></a>
                            {/if}
                        </td>
                    </tr>
                {/loop}
            </tbody>
        </table>
    </div>
{else}
    <div class="alert alert-info">
        <strong>Nincsenek Alkalmazás Források</strong> hozzáadva.
    </div>
{/if}