<h1>Fájl szerkesztése</h1>
{if="'' != $success"}
    <div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Success!</strong> {$success}</div>
{/if}
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
<form method="post" action="{$baseURL}administrator/library/file-save" class="form-horizontal" role="form">
    <input type="hidden" name="file[path]" value="{$file.path}" />
    <div class="form-group">
        <label class="col-lg-2 control-label">Fájlnév</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$file.filename}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Útvonal</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$file.path}</p>
        </div>
    </div>
    <div class="form-group">
        <label for="fileContent" class="col-lg-2 control-label">Fájl tartalma</label>
        {if="'js' == substr($file.filename, -2)"}
            <div class="hidden">{$filetype = " javascript"}</div>
        {elseif="'css' == substr($file.filename, -3) || 'less' == substr($file.filename, -4)"}
            <div class="hidden">{$filetype = " css"}</div>
        {/if}
        <div class="col-lg-10">
            <textarea class="form-control codemirror{$filetype}" name="file[content]" id="fileContent" rows="12">{$file.content}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Utolsó módosítás</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$file.last_modified}</p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            {if="$file.writable"}
                <button type="submit" class="btn btn-primary">Mentés</button>&nbsp;
            {else}
                <div class="alert alert-warning"><strong>Figyelem!</strong> Nem tudod elmenteni ezt a fájlt, mert nem írható.</div>
            {/if}
            <a href="javascript:linkConfirm('{$baseURL}administrator/library/rmfile/{$file.path}');" class="btn btn-danger">Fájl törlése</a>&nbsp;
            <a href="/{$file.path}" class="btn btn-default" target="_blank">Fájl letöltése</a>&nbsp;
            <a href="{$baseURL}administrator/library">Mégse és vissza a listához</a>
        </div>
    </div>
</form>