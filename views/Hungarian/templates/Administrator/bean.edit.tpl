<h1>Bean szerkesztése: <em>{$beanName}</em></h1>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
<div class="alert alert-warning">
    <strong>Légy óvatos.</strong> Ha nem tudod, hogy miért felel pontosan ez a Bean, inkább ne adj hozzá újat.
</div>
<form method="post" action="{$baseURL}administrator/beans/bean:{$beanName}/edit/id:{$beanID}/save" class="form-horizontal" role="form">
    <input type="hidden" name="bean[id]" value="{$beanID}" class="hidden" />
    {loop="$bean"}
        {if="'id' != $key"}
            <div class="form-group">
                <label for="bean_{$key}" class="col-lg-2 control-label">{$key}</label>
                <div class="col-lg-10">
                    {if="100 > strlen($value)"}
                        <input type="text" name="bean[{$key}]" value="{$value}" class="form-control" id="bean_{$key}" />
                    {else}
                        <textarea class="summernote auto-codeview" rows="5" name="bean[{$key}]" id="bean_{$key}">{$value}</textarea>
                    {/if}
                </div>
            </div>
        {/if}
    {/loop}
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary">Mentés</button>&nbsp;
            <a href="{$baseURL}administrator/beans/bean:{$beanName}">Mégse és vissza a listához</a>
        </div>
    </div>
</form>