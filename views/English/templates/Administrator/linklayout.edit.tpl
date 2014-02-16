<h1>Edit Layout link</h1>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
<form method="post" action="{$baseURL}administrator/linkLayouts/edit/application:{$layout.application}/save" class="form-horizontal" role="form">
    <div class="form-group">
        <label class="col-lg-2 control-label">Application</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$layout.application}</p>
        </div>
    </div>
    <div class="form-group">
        <label for="layout" class="col-lg-2 control-label">Linked Layout</label>
        <div class="col-lg-3">
            {if="'' != $layouts"}
                <select id="layout" name="linklayout[layout]" class="form-control">
                    <option value=""> :: Remove Layout link :: </option>
                    {loop="layouts"}<option value="{$value}"{if="$value == $layout.layout"} selected="selected"{/if}>{$value}</option>{/loop}
                </select>
            {else}
                <p class="form-control-static">
                    There is no Layouts.&nbsp;
                    <a href="{$baseURL}administrator/layouts/add">Add Layout</a>
                </p>
            {/if}
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary">Save</button>&nbsp;
            <a href="{$baseURL}administrator/linkLayouts">Cancel and back to list</a>
        </div>
    </div>
</form>