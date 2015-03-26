<h1>Preferences</h1>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Success!</strong> {$success}</div>
{/if}
<form method="post" action="{$baseURL}administrator/preferences/save" class="form-horizontal" role="form">
    <div class="form-group">
        <label for="titlePrefix" class="col-lg-2 control-label">Title Prefix</label>
        <div class="col-lg-10">
            <input type="text" name="preferences[titlePrefix]" value="{$preferences.titlePrefix}" class="form-control" id="titlePrefix" placeholder="Rapid | " />
            <p class="help-block">This prefix is before your Application's title.</p>
        </div>
    </div>
	<div class="form-group">
        <label for="author" class="col-lg-2 control-label">Author</label>
        <div class="col-lg-10">
            <input type="text" name="preferences[author]" value="{$preferences.author}" class="form-control" id="author" placeholder="Momentoom.hu" />
            <p class="help-block">Set the author of the site.</p>
        </div>
    </div>
    <div class="form-group">
        <label for="favicon" class="col-lg-2 control-label">Favicon</label>
        <div class="col-lg-10">
            <input type="text" name="preferences[favicon]" value="{$preferences.favicon}" class="form-control" id="favicon" placeholder="/lib/images/favicon.ico" />
            <p class="help-block">The icon of your website.</p>
        </div>
    </div>
    <div class="form-group">
        <label for="indexing" class="col-lg-2 control-label">Indexing</label>
        <div class="col-lg-10">
            <div class="checkbox">
                <label><input type="checkbox" name="preferences[indexing]" id="indexing"{if="isset($preferences.indexing)"} checked="checked"{/if} /> Allow search engines to index this Site.</label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary">Save</button>&nbsp;
        </div>
    </div>
</form>