<h1>Edit Mail Template</h1>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Success!</strong> {$success}</div>
{/if}
<form method="post" action="{$baseURL}administrator/mails/edit/save" class="form-horizontal" role="form">
    <input type="hidden" name="mail[filename]" value="{$mail.filename}" />
    <div class="form-group">
        <label class="col-lg-2 control-label">Filename</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$mail.filename}</p>
        </div>
    </div>
    <div class="form-group">
        <label for="templateContent" class="col-lg-2 control-label">Mail Template content</label>
        <div class="col-lg-10">
            <textarea class="form-control codemirror" name="mail[content]" id="templateContent" rows="12">{$mail.content}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Last modified</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$mail.last_modified}</p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            {if="$mail.writable"}
                <button type="submit" class="btn btn-primary">Save</button>&nbsp;
            {else}
                <div class="alert alert-warning"><strong>Warning!</strong> You can not Save this Template because this file is non-writable.</div>
            {/if}
            <a href="{$baseURL}administrator/mails">Cancel and back to list</a>
        </div>
    </div>
</form>