<h2>Beállítások</h2>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Kész!</strong> {$success}</div>
{/if}
<form method="post" action="{$baseURL}administrator/preferences/save" class="form-horizontal" role="form">
    <div class="form-group">
        <label for="titlePrefix" class="col-lg-2 control-label">Cím prefixum</label>
        <div class="col-lg-10">
            <input type="text" name="preferences[titlePrefix]" value="{$preferences.titlePrefix}" class="form-control" id="titlePrefix" placeholder="Rapid | " />
            <p class="help-block">Ez a cím fog az Alkalmazás címe elé kerülni.</p>
        </div>
    </div>
	<div class="form-group">
        <label for="author" class="col-lg-2 control-label">Szerző</label>
        <div class="col-lg-10">
            <input type="text" name="preferences[author]" value="{$preferences.author}" class="form-control" id="author" placeholder="Momentoom.hu" />
            <p class="help-block">Állítsd be a weboldal szerzőjét.</p>
        </div>
    </div>
    <div class="form-group">
        <label for="favicon" class="col-lg-2 control-label">Favikon</label>
        <div class="col-lg-10">
            <input type="text" name="preferences[favicon]" value="{$preferences.favicon}" class="form-control" id="favicon" placeholder="/lib/images/favicon.ico" />
            <p class="help-block">A weboldalad favikonja.</p>
        </div>
    </div>
    <div class="form-group">
		<label for="indexing" class="col-lg-2 control-label">Indexelés</label>
		<div class="col-lg-10">
			<div class="checkbox">
				<label><input type="checkbox" name="preferences[indexing]" id="indexing"{if="isset($preferences.indexing)"} checked="checked"{/if} /> Keresőmotorok beindexelésének engedélyezése.</label>
			</div>
		</div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary">Mentés</button>&nbsp;
        </div>
    </div>
</form>