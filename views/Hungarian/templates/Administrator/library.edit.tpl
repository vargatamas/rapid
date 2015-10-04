<form method="post" id="edit-form" action="{$baseURL}administrator/library/file-save" class="form-horizontal" role="form">
    <h1>
        Fájl szerkesztése
        {if="$file.writable"}
        <button type="submit" class="btn btn-sm pull-right btn-primary"><i class="fa fa-floppy-o"></i> Mentés</button>
        {/if}
    </h1>
    <div class="alert hidden">
        <strong class="alert-title">Success!</strong>
        <span class="alert-body">
            {$success}
        </span>
    </div>
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
        <label class="col-lg-2 control-label">Utolsó módosítás</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$file.last_modified}</p>
        </div>
    </div>
    <div class="form-group">
        {if="'js' == substr($file.filename, -2)"}
            <div class="hidden">{$filetype = " javascript"}</div>
        {elseif="'css' == substr($file.filename, -3) || 'less' == substr($file.filename, -4)"}
            <div class="hidden">{$filetype = " css"}</div>
        {/if}
        <div class="col-lg-12">
            <textarea class="form-control codemirror{$filetype}" name="file[content]" id="fileContent" rows="12">{$file.content}</textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-12 text-center">
            {if="$file.writable"}
                <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Mentés</button>&nbsp;
            {else}
                <div class="alert alert-warning"><strong>Figyelem!</strong> Nem tudod elmenteni ezt a fájlt, mert nem írható.</div>
            {/if}
            <a href="javascript:linkConfirm('{$baseURL}administrator/library/rmfile/{$file.path}');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Fájl törlése</a>&nbsp;
            <a href="/{$file.path}" class="btn btn-default" target="_blank"><i class="fa fa-download"></i> Fájl letöltése</a>&nbsp;
            <a href="{$baseURL}administrator/library">Mégse és vissza a listához</a>
        </div>
    </div>
</form>