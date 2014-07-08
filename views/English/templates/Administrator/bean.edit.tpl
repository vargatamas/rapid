<h1>Edit Item in <em>{$beanName}</em></h1>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
<div class="alert alert-warning">
    <strong>Be careful.</strong> If you do not know what this Bean is used exactly for, do not edit it.
</div>
<form method="post" action="{$baseURL}administrator/beans/bean:{$beanName}/edit/id:{$beanID}/save" class="form-horizontal" role="form">
    <input type="hidden" name="bean[id]" value="{$beanID}" class="hidden" />
    {loop="$bean"}
        {if="'id' != $key"}
            <div class="form-group">
                <label for="bean_{$key}" class="col-lg-2 control-label">{$key}</label>
                <div class="col-lg-10">
                    {if="100 > strlen($value)"}
                        <input type="text" name="bean[{$key}]" value="{$value}" class="form-control" id="bean_{$key}" />
                    {else}
                        <textarea class="summernote auto-codeview" rows="5" name="bean[{$key}]" id="bean_{$key}">{$value}</textarea>
                    {/if}
                </div>
            </div>
        {/if}
    {/loop}
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary">Save</button>&nbsp;
            <a href="{$baseURL}administrator/beans/bean:{$beanName}">Cancel and back to list</a>
        </div>
    </div>
</form>