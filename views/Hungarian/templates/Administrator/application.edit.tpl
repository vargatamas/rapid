<h1>Alkalmazás Meta adatainak szerkesztése</h1>
<form method="post" action="{$baseURL}administrator/applications/edit/application:{$application}/save" class="form-horizontal" role="form">
    <div class="form-group">
        <label class="col-lg-2 control-label">Alkalmazás</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$application}</p>
        </div>
    </div>
	<div class="form-group">
        <label for="title" class="col-lg-2 control-label">Cím</label>
        <div class="col-lg-10">
			{if="!isset($SITE.titlePrefix)"}
				<input type="text" name="application[title]" value="{$appMeta.title}" class="form-control" id="title" placeholder="Az én nagyszerű weboldalam" />
			{else}
				<div class="input-group">
					<span class="input-group-addon">{$SITE.titlePrefix}</span>
					<input type="text" name="application[title]" value="{$appMeta.title}" class="form-control" id="title" placeholder="Az én nagyszerű weboldalam" />
				</div>
			{/if}
        </div>
    </div>
    <div class="form-group">
        <label for="keywords" class="col-lg-2 control-label">Kulcsszavak</label>
        <div class="col-lg-10">
            <input type="text" name="application[keywords]" value="{$appMeta.keywords}" class="form-control" id="keywords" placeholder="receptek, torták, ételek" />
            <p class="help-block">Vesszővel válaszd el a kulcsszavakat.</p>
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-lg-2 control-label">Leírás</label>
        <div class="col-lg-10">
            <textarea class="form-control" name="application[description]" id="description" rows="3">{$appMeta.description}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="analytics" class="col-lg-2 control-label">Google Analytics kód</label>
        <div class="col-lg-10">
            <input type="text" name="application[analytics]" value="{$appMeta.analytics}" class="form-control" id="analytics" placeholder="UA-46377849-1" />
            <p class="help-block">Amennyiben rendelkezel Analytics kóddal, itt add meg, hogy követhesd az Alkalmazás statisztikáit.</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Nyelvek</label>
        <div class="col-lg-10">
            <p class="form-control-static">
                {loop="appMeta.languages"}
                    <input type="hidden" class="hided" name="application[languages][]" value="{$key}" />
                    {$key}&nbsp;
                {/loop}
            </p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary">Mentés</button>&nbsp;
            <a href="{$baseURL}administrator/applications">Mégse és vissza a listához</a>
        </div>
    </div>
</form>