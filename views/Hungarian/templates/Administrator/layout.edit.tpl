<form method="post" id="edit-form" action="{$baseURL}administrator/layouts/edit/filename:{$filename}/save" class="form-horizontal" role="form">
    <h1>
        Layout szerkesztése
        <button type="submit" class="btn btn-sm pull-right btn-primary"><i class="fa fa-floppy-o"></i> Mentés</button>
    </h1>
    <div class="alert hidden">
        <strong class="alert-title">Success!</strong>
        <span class="alert-body">
            {$success}
        </span>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Layout fájlneve</label>
        <div class="col-lg-10">
            {if="isset($otherLayouts)"}
            <select class="form-control" id="other-layout">
                {loop="otherLayouts"}
                <option value="{$value.filename}"{if="$value.filename == $filename"} selected{/if}>{$value.filename} ({$value.last_modified})</option>
                {/loop}
            </select>
            {else}
            <p class="form-control-static">{$filename}</p>
            {/if}
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-12">
            <textarea class="form-control codemirror" name="layout[content]" id="layoutContent" rows="12">{$layout.content}</textarea>
            <p class="help-block">A tartalomnak tartalmaznia kell a <strong>{noparse}{$APPLICATION_CONTENT}{/noparse}</strong> nevű változót, amibe az alkalmazás tartalma kerül.</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Utolsó módosítás</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$layout.last_modified}</p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            {if="$layout.writable"}
                <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Mentés</button>&nbsp;
            {else}
                <div class="alert alert-warning"><strong>Figyelem!</strong> Nem tudod elmenteni ezt a Layoutot, mivel a fájl nem írható.</div>
            {/if}
            <a href="{$baseURL}administrator/layouts">Mégse és vissza a listához</a>
        </div>
    </div>
</form>