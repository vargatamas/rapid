<h1>Edit Application Meta</h1>
<form method="post" action="{$baseURL}administrator/applications/edit/application:{$application}/save" class="form-horizontal" role="form">
    <div class="form-group">
        <label class="col-lg-2 control-label">Application</label>
        <div class="col-lg-10">
            <p class="form-control-static">{$application}</p>
        </div>
    </div>
	<div class="form-group">
        <label for="title" class="col-lg-2 control-label">Title</label>
        <div class="col-lg-10">
			{if="!isset($SITE.titlePrefix)"}
				<input type="text" name="application[title]" value="{$appMeta.title}" class="form-control" id="title" placeholder="My wonderful website" />
			{else}
				<div class="input-group">
					<span class="input-group-addon">{$SITE.titlePrefix}</span>
					<input type="text" name="application[title]" value="{$appMeta.title}" class="form-control" id="title" placeholder="My wonderful website" />
				</div>
			{/if}
        </div>
    </div>
    <div class="form-group">
        <label for="keywords" class="col-lg-2 control-label">Keywords</label>
        <div class="col-lg-10">
            <input type="text" name="application[keywords]" value="{$appMeta.keywords}" class="form-control" id="keywords" placeholder="receipts, cakes, meals, food" />
            <p class="help-block">Separate the keywords with commas.</p>
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-lg-2 control-label">Description</label>
        <div class="col-lg-10">
            <textarea class="form-control" name="application[description]" id="description" rows="3">{$appMeta.description}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="analytics" class="col-lg-2 control-label">Google Analytics code</label>
        <div class="col-lg-10">
            <input type="text" name="application[analytics]" value="{$appMeta.analytics}" class="form-control" id="analytics" placeholder="UA-46377849-1" />
            <p class="help-block">If you have Analytics code, then this Applications will traced trough Google Analytics.</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Languages</label>
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
            <button type="submit" class="btn btn-primary">Save</button>&nbsp;
            <a href="{$baseURL}administrator/applications">Cancel and back to list</a>
        </div>
    </div>
</form>