<h1>Edit File</h1>
<div class="alert hidden">
    <strong class="alert-title">Success!</strong>
    <span class="alert-body">
        {$success}
    </span>
</div>
<form method="post" id="edit-form" action="{$baseURL}administrator/library/file-save" class="form-horizontal" role="form">
    <input type="hidden" name="file[path]" value="{$file.path}" />
    <div class="form-group">
        <label class="col-lg-2 control-label">Filename</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$file.filename}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Path</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$file.path}</p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-12">
            {if="'js' == substr($file.filename, -2)"}
                <div class="hidden">{$filetype = " javascript"}</div>
            {elseif="'css' == substr($file.filename, -3) || 'less' == substr($file.filename, -4)"}
                <div class="hidden">{$filetype = " css"}</div>
            {/if}
            <textarea class="form-control codemirror{$filetype}" name="file[content]" id="fileContent" rows="12">{$file.content}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Last modified</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$file.last_modified}</p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            {if="$file.writable"}
                <button type="submit" class="btn btn-primary">Save</button>&nbsp;
            {else}
                <div class="alert alert-warning"><strong>Warning!</strong> You can not Save this File because this is non-writable.</div>
            {/if}
            <a href="javascript:linkConfirm('{$baseURL}administrator/library/rmfile/{$file.path}');" class="btn btn-danger">Remove file</a>&nbsp;
            <a href="/{$file.path}" class="btn btn-default" target="_blank">Download file</a>&nbsp;
            <a href="{$baseURL}administrator/library">Cancel and back to list</a>
        </div>
    </div>
</form>