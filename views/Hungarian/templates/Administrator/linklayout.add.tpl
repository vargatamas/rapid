<h1>Új Layout összerendelés</h1>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
{if="'' != $layouts"}
    <form method="post" action="{$baseURL}administrator/linkLayouts/add/save" class="form-horizontal" role="form">
        <div class="form-group">
            <label for="application" class="col-lg-2 control-label">Alkalmazás</label>
            <div class="col-lg-3">
                <select id="application" name="linklayout[application]" class="form-control">
                    <option value="" selected="selected"> :: Válassz :: </option>
                    {loop="applications"}<option value="{$value}">{$value}</option>{/loop}
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="layout" class="col-lg-2 control-label">Kapcsolt Layout</label>
            <div class="col-lg-3">
                <select id="layout" name="linklayout[layout]" class="form-control">
                    <option value=""> :: Válassz :: </option>
                    {loop="layouts"}<option value="{$value}">{$value}</option>{/loop}
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Mentés</button>&nbsp;
                <a href="{$baseURL}administrator/linkLayouts">Mégse és vissza a listához</a>
            </div>
        </div>
    </form>
{else}
    <p class="form-control-static">
        Nincsenek Layoutok, amelyeket össze lehetne rendelni.&nbsp;
        <a href="{$baseURL}administrator/layouts/add">Új Layout</a>
    </p>
{/if}