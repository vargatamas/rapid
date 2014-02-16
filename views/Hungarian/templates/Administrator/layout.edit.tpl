<h1>Layout szerkesztése</h1>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Kész!</strong> {$success}</div>
{/if}
<form method="post" action="{$baseURL}administrator/layouts/edit/filename:{$filename}/save" class="form-horizontal" role="form">
    <div class="form-group">
        <label class="col-lg-2 control-label">Layout fájlneve</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$filename}</p>
        </div>
    </div>
    <div class="form-group">
        <label for="layoutContent" class="col-lg-2 control-label">Layout tartalma</label>
        <div class="col-lg-10">
            <textarea class="form-control summernote auto-codeview" name="layout[content]" id="layoutContent" rows="12">{$layout.content}</textarea>
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
                <button type="submit" class="btn btn-primary">Mentés</button>&nbsp;
            {else}
                <div class="alert alert-warning"><strong>Figyelem!</strong> Nem tudod elmenteni ezt a Layoutot, mivel a fájl nem írható.</div>
            {/if}
            <a href="{$baseURL}administrator/layouts">Mégse és vissza a listához</a>
        </div>
    </div>
</form>