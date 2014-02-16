<h1>Create new Layout</h1>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
<form method="post" action="{$baseURL}administrator/layouts/add/save" class="form-horizontal" role="form">
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
				<input type="checkbox" name="layout[allLanguage]" id="allLanguage" />
			</div>
		</div>
    </div>
    <div class="form-group">
        <label for="layoutContent" class="col-lg-2 control-label">Layout content</label>
        <div class="col-lg-10">
            <textarea class="form-control summernote" name="layout[content]" id="layoutContent" rows="12">{if="'' != $layout.content"}{$layout.content}{else}{noparse}{$APPLICATION_CONTENT}{/noparse}{/if}</textarea>
            <p class="help-block">The content has to contains <strong>{noparse}{$APPLICATION_CONTENT}{/noparse}</strong> variable to place the application's content.</p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary">Save</button>&nbsp;
            <a href="{$baseURL}administrator/layouts">Cancel and back to list</a>
        </div>
    </div>
</form>