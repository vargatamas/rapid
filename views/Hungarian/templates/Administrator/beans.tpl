{if="$beanName"}
    <h1>Bean: <em>{$beanName}</em></h1>
    <br /><br />
{/if}
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Kész!</strong> {$success}</div>
{/if}
{if="'' != $beanName && '' != $bean && '' != $beans"}
    <a href="{$baseURL}administrator/beans/bean:{$beanName}/add" class="btn btn-primary">Új Bean</a>&nbsp;
    <a href="javascript:linkConfirm('{$baseURL}administrator/beans/bean:{$beanName}/remove-all');" class="btn btn-danger">Összes Bean ürítése</a>
    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>Oszlop neve</th>
                    <th>Típus</th>
                </tr>
            </thead>
            <tbody>
                {loop="bean"}
                    <tr>
                        <td>{$key}</td>
                        <td>{$value}</td>
                    </tr>
                {/loop}
            </tbody>
        </table>
    </div>
    {if="'' != $beans.0"}
        <br />
        <div class="table-responsive">
            <table class="table table-striped table-condensed">
                <thead>
                    <tr>
                        {loop="bean"}
                            {if="6 > $counter"}<th>{$key}</th>{/if}
                        {/loop}
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {loop="beans"}
                        {$k=$key}
                        <tr>
                            {loop="beans.$k"}
                                {if="'id' == $key"}{$id=$value}{/if}
                                {if="6 > $counter"}<td>{$value|substr:0,80}{if="80 < strlen($value)"} ..{/if}</td>{/if}
                            {/loop}
                            <td>
                                <a href="{$baseURL}administrator/beans/bean:{$beanName}/edit/id:{$id}" title="Bean szerkesztése"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;
                                <a href="javascript:linkConfirm('{$baseURL}administrator/beans/bean:{$beanName}/remove/id:{$id}');" title="Bean eltávolítása"><span class="glyphicon glyphicon-trash"></span></a>
                            </td>
                        </tr>
                    {/loop}
                </tbody>
            </table>
        </div>
    {else}
        <div class="alert alert-info">
            <strong>Nem található</strong> Bean.
        </div>
    {/if}
{else}
    <div class="alert alert-info">
        <strong>Nem található</strong> ez a Bean, később próbáld újra.
    </div>
{/if}