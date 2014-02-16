<h1>Create Bean</h1>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
<div class="alert alert-warning"><strong>Attention!</strong> Do not use special characters, use only lowercase alphabet only characters. Also recommended to use camelCase names.</div>
<form method="post" action="{$baseURL}administrator/beans/new/save" class="form-horizontal" role="form">
    <div class="form-group">
        <label for="beanName" class="col-lg-2 control-label">Bean name</label>
        <div class="col-lg-5">
            <input type="text" name="bean[name]" class="form-control" value="{$beanName}" id="beanName" placeholder="news" />
            <p class="help-block">Name of the Bean (table name).</p>
        </div>
    </div>
    <div class="form-group">
        <label for="beanField" class="col-lg-2 control-label">Field(s)</label>
        <div class="col-lg-5">
            <input type="text" name="bean[field][]" class="form-control" placeholder="field" id="beanField" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-5">
            <button type="button" class="btn btn-default" id="add-bean-field"><span class="glyphicon glyphicon-plus"></span>&nbsp;Add Field</button>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary">Save</button>&nbsp;
            <a href="{$baseURL}administrator">Cancel and back to home</a>
        </div>
    </div>
</form>