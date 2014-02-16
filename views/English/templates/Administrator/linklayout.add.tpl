<h1>Add Layout link</h1>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
{if="'' != $layouts"}
    <form method="post" action="{$baseURL}administrator/linkLayouts/add/save" class="form-horizontal" role="form">
        <div class="form-group">
            <label for="application" class="col-lg-2 control-label">Application</label>
            <div class="col-lg-3">
                <select id="application" name="linklayout[application]" class="form-control">
                    <option value="" selected="selected"> :: Choose :: </option>
                    {loop="applications"}<option value="{$value}">{$value}</option>{/loop}
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="layout" class="col-lg-2 control-label">Linked Layout</label>
            <div class="col-lg-3">
                <select id="layout" name="linklayout[layout]" class="form-control">
                    <option value=""> :: Choose :: </option>
                    {loop="layouts"}<option value="{$value}">{$value}</option>{/loop}
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <button type="submit" class="btn btn-primary">Save</button>&nbsp;
                <a href="{$baseURL}administrator/linkLayouts">Cancel and back to list</a>
            </div>
        </div>
    </form>
{else}
    <p class="form-control-static">
        There is no Layouts to link.&nbsp;
        <a href="{$baseURL}administrator/layouts/add">Add Layout</a>
    </p>
{/if}