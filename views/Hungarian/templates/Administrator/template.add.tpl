<h1>Új Sablon</h1>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
<form method="post" action="{$baseURL}administrator/templates/add/save" class="form-horizontal" role="form">
    <div class="form-group">
        <label for="application" class="col-lg-2 control-label">Alkalmazás</label>
        <div class="col-lg-3">
            <select id="application" name="template[application]" class="form-control">
                <option value="" selected="selected"> :: Válassz :: </option>
                {loop="applications"}<option value="{$value}"{if="$value == $template.application"} selected="selected"{/if}>{$value}</option>{/loop}
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="filename" class="col-lg-2 control-label">Fájlnév</label>
        <div class="col-lg-3">
            <div class="input-group">
                <input type="text" name="template[filename]" value="{$template.filename}" class="form-control" id="filename" placeholder="újsablon" />
                <span class="input-group-addon">.{$tpl_ext}</span>
            </div>
        </div>
    </div>
	<div class="form-group">
		<label for="allLanguage" class="col-lg-2 control-label">Létrehozás minden nyelven</label>
		<div class="col-lg-10">
			<div class="checkbox">
				<label><input type="checkbox" name="template[allLanguage]" id="allLanguage" /></label>
			</div>
		</div>
    </div>
    <div class="form-group">
        <div class="col-lg-12">
            <textarea class="form-control codemirror" name="template[content]" id="templateContent" rows="12">{$template.content}</textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary">Mentés</button>&nbsp;
            <a href="{$baseURL}administrator/templates">Mégse és vissza a listához</a>
        </div>
    </div>
</form>