<h1>Edit Template</h1>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Success!</strong> {$success}</div>
{/if}
<form method="post" action="{$baseURL}administrator/templates/edit/save" class="form-horizontal" role="form">
    <input type="hidden" name="template[application]" value="{$template.application}" />
    <input type="hidden" name="template[filename]" value="{$template.filename}" />
    <div class="form-group">
        <label class="col-lg-2 control-label">Application</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$template.application}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Filename</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$template.filename}</p>
        </div>
    </div>
    <div class="form-group">
        <label for="templateContent" class="col-lg-2 control-label">Template content</label>
        <div class="col-lg-10">
            <textarea class="form-control summernote auto-codeview" name="template[content]" id="templateContent" rows="12">{$template.content}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Last modified</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$template.last_modified}</p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            {if="$template.writable"}
                <button type="submit" class="btn btn-primary">Save</button>&nbsp;
            {else}
                <div class="alert alert-warning"><strong>Warning!</strong> You can not Save this Template because this file is non-writable.</div>
            {/if}
            <a href="{$baseURL}administrator/templates">Cancel and back to list</a>
        </div>
    </div>
</form>