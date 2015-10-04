<form method="post" action="{$baseURL}administrator/applications/add/save" class="form-horizontal" role="form">
    <h1>
        Add new Application
        <button type="submit" class="btn btn-sm pull-right btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
    </h1>
    {if="'' != $error"}
    <div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
    {/if}
    <div class="form-group">
        <label for="applicationName" class="col-lg-2 control-label">Name</label>
        <div class="col-lg-5">
            <input type="text" name="application[name]" value="{$applicationName}" class="form-control" id="applicationName" placeholder="NewApp" />
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-lg-2 control-label">Title</label>
        <div class="col-lg-10">
			{if="!isset($SITE.titlePrefix)"}
				<input type="text" name="application[title]" value="{$app.title}" class="form-control" id="title" placeholder="My wonderful website" />
			{else}
				<div class="input-group">
					<span class="input-group-addon">{$SITE.titlePrefix}</span>
					<input type="text" name="application[title]" value="{$app.title}" class="form-control" id="title" placeholder="My wonderful website" />
				</div>
			{/if}
        </div>
    </div>
    <div class="form-group">
        <label for="keywords" class="col-lg-2 control-label">Keywords</label>
        <div class="col-lg-10">
            <input type="text" name="application[keywords]" value="{$app.keywords}" class="form-control" id="keywords" placeholder="receipts,cakes,meals,food" />
            <p class="help-block">Separate the keywords with commas.</p>
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-lg-2 control-label">Description</label>
        <div class="col-lg-10">
            <textarea class="form-control" name="application[description]" id="description" rows="3">{$app.description}</textarea>
        </div>
    </div>
    {if="0 < count($languages)"}
        <div class="form-group">
            <label class="col-lg-2 control-label">Languages</label>
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
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>&nbsp;
            <a href="{$baseURL}administrator/applications">Cancel and back to list</a>
        </div>
    </div>
</form>