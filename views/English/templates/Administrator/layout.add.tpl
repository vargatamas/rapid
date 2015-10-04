<form method="post" action="{$baseURL}administrator/layouts/add/save" class="form-horizontal" role="form">
    <h1>
        Create new Layout
        <button type="submit" class="btn btn-sm pull-right btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
    </h1>
    {if="'' != $error"}
    <div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
    {/if}
    <br>
    <div class="form-group">
        <label for="layout" class="col-lg-2 control-label">Layout filename</label>
        <div class="col-lg-10">
            <div class="input-group">
                <span class="input-group-addon">layout.</span>
                <input type="text" name="layout[filename]" value="{$layout.filename}" class="form-control" id="layout" placeholder="mylayout" />
                <span class="input-group-addon">.{$tpl_ext}</span>
            </div>
        </div>
    </div>
	<div class="form-group">
		<label for="allLanguage" class="col-lg-2 control-label">Create on every language</label>
		<div class="col-lg-10">
			<div class="checkbox">
				<label><input type="checkbox" name="layout[allLanguage]" id="allLanguage" /></label>
			</div>
		</div>
    </div>
    <div class="form-group">
        <div class="col-lg-12">
            <textarea class="form-control codemirror" name="layout[content]" id="layoutContent" rows="12">{if="'' != $layout.content"}{$layout.content}{else}{noparse}{$APPLICATION_CONTENT}{/noparse}{/if}</textarea>
            <p class="help-block">The content has to contains <strong>{noparse}{$APPLICATION_CONTENT}{/noparse}</strong> variable to place the application's content.</p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>&nbsp;
            <a href="{$baseURL}administrator/layouts">Cancel and back to list</a>
        </div>
    </div>
</form>