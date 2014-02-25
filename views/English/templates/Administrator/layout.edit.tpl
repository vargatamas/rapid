<h1>Edit Layout</h1>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Success!</strong> {$success}</div>
{/if}
<form method="post" action="{$baseURL}administrator/layouts/edit/filename:{$filename}/save" class="form-horizontal" role="form">
    <div class="form-group">
        <label class="col-lg-2 control-label">Layout filename</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$filename}</p>
        </div>
    </div>
    <div class="form-group">
        <label for="layoutContent" class="col-lg-2 control-label">Layout content</label>
        <div class="col-lg-10">
            <textarea class="form-control codemirror" name="layout[content]" id="layoutContent" rows="12">{$layout.content}</textarea>
            <p class="help-block">The content has to contains <strong>{noparse}{$APPLICATION_CONTENT}{/noparse}</strong> variable to place the application's content.</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Last modified</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$layout.last_modified}</p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            {if="$layout.writable"}
                <button type="submit" class="btn btn-primary">Save</button>&nbsp;
            {else}
                <div class="alert alert-warning"><strong>Warning!</strong> You can not Save this Layout because this file (applications/{$filename}) is non-writable.</div>
            {/if}
            <a href="{$baseURL}administrator/layouts">Cancel and back to list</a>
        </div>
    </div>
</form>