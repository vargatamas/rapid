<h1>Új Alkalmazás</h1>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
<form method="post" action="{$baseURL}administrator/applications/add/save" class="form-horizontal" role="form">
    <div class="form-group">
        <label for="applicationName" class="col-lg-2 control-label">Név</label>
        <div class="col-lg-5">
            <input type="text" name="application[name]" value="{$applicationName}" class="form-control" id="applicationName" placeholder="NewApp" />
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-lg-2 control-label">Cím</label>
        <div class="col-lg-10">
			{if="!isset($SITE.titlePrefix)"}
				<input type="text" name="application[title]" value="{$app.title}" class="form-control" id="title" placeholder="Az én nagyszerű weboldalam" />
			{else}
				<div class="input-group">
					<span class="input-group-addon">{$SITE.titlePrefix}</span>
					<input type="text" name="application[title]" value="{$app.title}" class="form-control" id="title" placeholder="Az én nagyszerű weboldalam" />
				</div>
			{/if}
        </div>
    </div>
    <div class="form-group">
        <label for="keywords" class="col-lg-2 control-label">Kulcsszavak</label>
        <div class="col-lg-10">
            <input type="text" name="application[keywords]" value="{$app.keywords}" class="form-control" id="keywords" placeholder="receptek, torták, ételek" />
            <p class="help-block">Vesszővel válaszd el a kulcsszavakat.</p>
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-lg-2 control-label">Leírás</label>
        <div class="col-lg-10">
            <textarea class="form-control" name="application[description]" id="description" rows="3">{$app.description}</textarea>
        </div>
    </div>
    {if="0 < count($languages)"}
        <div class="form-group">
            <label class="col-lg-2 control-label">Nyelvek</label>
            <div class="col-lg-10">
                {loop="$languages"}
                    <input type="checkbox" name="application[languages][]" value="{$value}" checked="checked" />
                    {$value}<br />
                {/loop}
            </div>
        </div>
    {/if}
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary">Mentés</button>&nbsp;
            <a href="{$baseURL}administrator/applications">Mégse és vissza a listához</a>
        </div>
    </div>
</form>