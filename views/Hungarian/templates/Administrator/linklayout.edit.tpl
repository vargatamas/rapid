<h1>Layout összerendelés szerkesztése</h1>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
<form method="post" action="{$baseURL}administrator/linkLayouts/edit/application:{$layout.application}/save" class="form-horizontal" role="form">
    <div class="form-group">
        <label class="col-lg-2 control-label">Alkalmazás</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$layout.application}</p>
        </div>
    </div>
    <div class="form-group">
        <label for="layout" class="col-lg-2 control-label">Kapcsolt Layout</label>
        <div class="col-lg-3">
            {if="'' != $layouts"}
                <select id="layout" name="linklayout[layout]" class="form-control">
                    <option value=""> :: Layout kapcsolás megszüntetése :: </option>
                    {loop="layouts"}<option value="{$value}"{if="$value == $layout.layout"} selected="selected"{/if}>{$value}</option>{/loop}
                </select>
            {else}
                <p class="form-control-static">
                    Nincsenek Layoutok.&nbsp;
                    <a href="{$baseURL}administrator/layouts/add">Új Layout</a>
                </p>
            {/if}
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary">Mentés</button>&nbsp;
            <a href="{$baseURL}administrator/linkLayouts">Mégse és vissza a listához</a>
        </div>
    </div>
</form>