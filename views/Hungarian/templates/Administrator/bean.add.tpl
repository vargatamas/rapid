<h1>Új Bean hozzáadása ehhez: <em>{$beanName}</em></h1>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
<div class="alert alert-warning">
    <strong>Légy óvatos.</strong> Ha nem tudod, hogy miért felel pontosan ez a Bean, inkább ne adj hozzá újat.
</div>
<form method="post" action="{$baseURL}administrator/beans/bean:{$beanName}/add/save" class="form-horizontal" role="form">
    {loop="$columns"}
        {if="'id' != $key"}
            {$c=$key}
            <div class="form-group">
                <label for="bean_{$key}" class="col-lg-2 control-label">{$key}</label>
                <div class="col-lg-10">
                    {if="100 > strlen($sample.$c)"}
                        <input type="text" name="bean[{$key}]" value="{$values.$c}" class="form-control" id="bean_{$key}" placeholder="{$sample.$c|substr:0,80}{if="80 < strlen($sample.$c)"} ..{/if}" />
                    {else}
                        <textarea class="summernote" name="bean[{$key}]" id="bean_{$key}">{$values.$c}</textarea>
                    {/if}
                </div>
            </div>
        {/if}
    {/loop}
    <div class="alert alert-info">
        <strong>Ezek csak minták.</strong> A helyörző értékek a mezőkben csak minta adatok, hogy látni lehessen a formájukat.
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary">Mentés</button>&nbsp;
            <a href="{$baseURL}administrator/beans/bean:{$beanName}">Mégse és vissza a listához</a>
        </div>
    </div>
</form>