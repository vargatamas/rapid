<h1>New Translation</h1>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
<form method="post" action="{$baseURL}administrator/translations/add/save" class="form-horizontal" role="form">
    <div class="form-group">
        <label for="language" class="col-lg-2 control-label">Language</label>
        <div class="col-lg-3">
            <select id="language" name="translation[language]" class="form-control">
                <option value="" selected="selected"> :: Choose :: </option>
                {loop="languages"}<option value="{$value}">{$value}</option>{/loop}
            </select>
            <p class="help-block">Translated from <em>{$CULTURE}</em>.</p>
        </div>
    </div>
    <div class="form-group">
        <label for="from" class="col-lg-2 control-label">From</label>
        <div class="col-lg-10">
            <input type="text" name="translation[from]" value="{$translation.from}" class="form-control" id="from" placeholder="This is a message." />
            <p class="help-block">You can use variables, just write <strong>#text#</strong> on the correct place.</p>
        </div>
    </div>
    <div class="form-group">
        <label for="to" class="col-lg-2 control-label">To</label>
        <div class="col-lg-10">
            <input type="text" name="translation[to]" value="{$translation.to}" class="form-control" id="to" placeholder="Dies ist ein Nachricht." />
            <p class="help-block">If you have variable(s), it will replaced from left to right.</p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary">Save</button>&nbsp;
            <a href="{$baseURL}administrator/translations">Cancel and back to list</a>
        </div>
    </div>
</form>