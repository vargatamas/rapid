<h1>Új Layout</h1>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
<form method="post" action="{$baseURL}administrator/layouts/add/save" class="form-horizontal" role="form">
    <div class="form-group">
        <label for="layout" class="col-lg-2 control-label">Layout fájlneve</label>
        <div class="col-lg-10">
            <div class="input-group">
                <span class="input-group-addon">layout.</span>
                <input type="text" name="layout[filename]" value="{$layout.filename}" class="form-control" id="layout" placeholder="mylayout" />
                <span class="input-group-addon">.{$tpl_ext}</span>
            </div>
        </div>
    </div>
	<div class="form-group">
		<label for="allLanguage" class="col-lg-2 control-label">Létrehozás minden nyelvre</label>
		<div class="col-lg-10">
			<div class="checkbox">
				<input type="checkbox" name="layout[allLanguage]" id="allLanguage" />
			</div>
		</div>
    </div>
	<div class="form-group">
        <label for="layoutContent" class="col-lg-2 control-label">Layout tartalma</label>
        <div class="col-lg-10">
            <textarea class="form-control summernote" name="layout[content]" id="layoutContent" rows="12">{if="'' != $layout.content"}{$layout.content}{else}{noparse}{$APPLICATION_CONTENT}{/noparse}{/if}</textarea>
            <p class="help-block">A tartalomnak tartalmaznia kell a <strong>{noparse}{$APPLICATION_CONTENT}{/noparse}</strong> nevű változót, amibe az alkalmazás tartalma kerül.</p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary">Mentés</button>&nbsp;
            <a href="{$baseURL}administrator/layouts">Mégse és vissza a listához</a>
        </div>
    </div>
</form>