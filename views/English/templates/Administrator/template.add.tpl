<h1>Create new Template</h1>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
<form method="post" action="{$baseURL}administrator/templates/add/save" class="form-horizontal" role="form">
    <div class="form-group">
        <label for="application" class="col-lg-2 control-label">Application</label>
        <div class="col-lg-3">
            <select id="application" name="template[application]" class="form-control">
                <option value="" selected="selected"> :: Choose :: </option>
                {loop="applications"}<option value="{$value}"{if="$value == $template.application"} selected="selected"{/if}>{$value}</option>{/loop}
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="filename" class="col-lg-2 control-label">Filename</label>
        <div class="col-lg-3">
            <div class="input-group">
                <input type="text" name="template[filename]" value="{$template.filename}" class="form-control" id="filename" placeholder="newtemplate" />
                <span class="input-group-addon">.{$tpl_ext}</span>
            </div>
        </div>
    </div>
	<div class="form-group">
		<label for="allLanguage" class="col-lg-2 control-label">Create on every language</label>
		<div class="col-lg-10">
			<div class="checkbox">
				<input type="checkbox" name="template[allLanguage]" id="allLanguage" />
			</div>
		</div>
    </div>
    <div class="form-group">
        <label for="templateContent" class="col-lg-2 control-label">Template content</label>
        <div class="col-lg-10">
            <textarea class="form-control summernote" name="template[content]" id="templateContent" rows="12">{$template.content}</textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary">Save</button>&nbsp;
            <a href="{$baseURL}administrator/templates">Cancel and back to list</a>
        </div>
    </div>
</form>