<h1>E-mail Sablon szerkesztése</h1>
<div class="alert hidden">
    <strong class="alert-title">Success!</strong>
    <span class="alert-body">
        {$success}
    </span>
</div>
<form method="post" id="edit-form" action="{$baseURL}administrator/mails/edit/save" class="form-horizontal" role="form">
    <input type="hidden" name="mail[filename]" value="{$mail.filename}" />
    <div class="form-group">
        <label class="col-lg-2 control-label">Fájlnév</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$mail.filename}</p>
        </div>
    </div>
    <div class="form-group">
        <label for="templateContent" class="col-lg-2 control-label">E-mail Sablon tartalma</label>
        <div class="col-lg-10">
            <textarea class="form-control codemirror" name="mail[content]" id="templateContent" rows="12">{$mail.content}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Utoljára módosítva</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$mail.last_modified}</p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            {if="$mail.writable"}
                <button type="submit" class="btn btn-primary">Mentés</button>&nbsp;
            {else}
                <div class="alert alert-warning"><strong>Figyelem!</strong> Nem tudod menteni ezt az E-mail Sablont, mivel nem írható.</div>
            {/if}
            <a href="{$baseURL}administrator/mails">Mégse és vissza a listához</a>
        </div>
    </div>
</form>