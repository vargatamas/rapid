<form method="post" id="edit-form" action="{$baseURL}administrator/mails/edit/save" class="form-horizontal" role="form">
    <h1>
        Edit Mail Template
        {if="$mail.writable"}
            <button type="submit" class="btn btn-sm pull-right btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
        {/if}
    </h1>
    <div class="alert hidden">
        <strong class="alert-title">Success!</strong>
        <span class="alert-body">
            {$success}
        </span>
    </div>
    <input type="hidden" name="mail[filename]" value="{$mail.filename}" />
    <div class="form-group">
        <label class="col-lg-2 control-label">Filename</label>
        <div class="col-lg-10">
            {if="isset($otherMails)"}
            <select class="form-control" id="other-mail">
                {loop="otherMails"}
                <option value="{$value.filename}"{if="$value.filename == $mail.filename"} selected{/if}>{$value.filename} ({$value.last_modified})</option>
                {/loop}
            </select>
            {else}
            <p class="form-control-static">{$mail.filename}</p>
            {/if}
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-12">
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
                <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>&nbsp;
            {else}
                <div class="alert alert-warning"><strong>Warning!</strong> You can not Save this Template because this file is non-writable.</div>
            {/if}
            <a href="{$baseURL}administrator/mails">Cancel and back to list</a>
        </div>
    </div>
</form>