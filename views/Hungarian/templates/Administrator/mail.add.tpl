<form method="post" action="{$baseURL}administrator/mails/add/save" class="form-horizontal" role="form">
    <h1>
        Új E-mal Sablon készítése
        <button type="submit" class="btn btn-sm pull-right btn-primary"><i class="fa fa-floppy-o"></i> Mentés</button>
    </h1>
    {if="'' != $error"}
    <div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
    {/if}
    <div class="form-group">
        <label for="filename" class="col-lg-2 control-label">Fájlnév</label>
        <div class="col-lg-3">
            <div class="input-group">
                <input type="text" name="mail[filename]" value="{$mailTemplate.filename}" class="form-control" id="filename" placeholder="ujmail" />
                <span class="input-group-addon">.{$tpl_ext}</span>
            </div>
        </div>
    </div>
	<div class="form-group">
		<label for="allLanguage" class="col-lg-2 control-label">Létrehozás minden nyelven</label>
		<div class="col-lg-10">
			<div class="checkbox">
				<label><input type="checkbox" name="mail[allLanguage]" id="allLanguage"{if="$mailTemplate.allLanguage"} checked="checked"{/if} /></label>
			</div>
		</div>
    </div>
    <div class="form-group">
        <div class="col-lg-12">
            <textarea class="form-control codemirror" name="mail[content]" id="templateContent" rows="12">{$mailTemplate.content}</textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Mentés</button>&nbsp;
            <a href="{$baseURL}administrator/mails">Mégse és vissza a listához</a>
        </div>
    </div>
</form>