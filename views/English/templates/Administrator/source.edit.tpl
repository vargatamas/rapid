<form method="post" action="{$baseURL}administrator/sources/edit/application:{$application}/save" class="form-horizontal" role="form">
    <h1>
        Edit Application Sources
        {if="$writable"}
        <button type="submit" class="btn btn-sm pull-right btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
        {/if}
    </h1>
    <div class="form-group">
        <label for="javascripts" class="col-lg-2 control-label">Javascripts</label>
        <div class="col-lg-10">
            <div>
                {if="'' != $sourceContent.javascripts.0"}
                    {loop="sourceContent.javascripts"}
                        <div class="input-group">
                            <input type="text" name="source[javascripts][]" value="{$value}" class="form-control" placeholder="/assets/js/script.js" />
                            <span class="input-group-btn"><button class="btn btn-danger remove-source" type="button"><i class="fa fa-trash-o"></i> Remove</button></span>
                        </div>
                        <br />
                    {/loop}
                {else}
                    <div class="input-group">
                        <input type="text" name="source[javascripts][]" id="javascripts" value="" class="form-control" placeholder="/assets/js/script.js" />
                        <span class="input-group-btn"><button class="btn btn-danger remove-source" type="button"><i class="fa fa-trash-o"></i> Remove</button></span>
                    </div>
                    <br />
                {/if}
            </div>
            <button type="button" class="btn btn-default add-source"><i class="fa fa-plus"></i>&nbsp;Add Javascript</button>
        </div>
    </div>
    <div class="form-group">
        <label for="stylesheets" class="col-lg-2 control-label">Stylesheets</label>
        <div class="col-lg-10">
            <div>
                {if="'' != $sourceContent.stylesheets.0"}
                    {loop="sourceContent.stylesheets"}
                        <div class="input-group">
                            <input type="text" name="source[stylesheets][]" value="{$value}" class="form-control" placeholder="/assets/css/style.css" />
                            <span class="input-group-btn"><button class="btn btn-danger remove-source" type="button"><i class="fa fa-trash-o"></i> Remove</button></span>
                        </div>
                        <br />
                    {/loop}
                {else}
                    <div class="input-group">
                        <input type="text" name="source[stylesheets][]" id="stylesheets" value="" class="form-control" placeholder="/assets/css/style.css" />
                        <span class="input-group-btn"><button class="btn btn-danger remove-source" type="button"><i class="fa fa-trash-o"></i> Remove</button></span>
                    </div>
                    <br />
                {/if}
            </div>
            <button type="button" class="btn btn-default add-source"><i class="fa fa-plus"></i>&nbsp;Add Stylesheet</button>
        </div>
    </div>
    <div class="form-group">
        <label for="less" class="col-lg-2 control-label">LESS CSS</label>
        <div class="col-lg-10">
            <div>
                {if="'' != $sourceContent.less.0"}
                    {loop="sourceContent.less"}
                        <div class="input-group">
                            <input type="text" name="source[less][]" value="{$value}" class="form-control" placeholder="/assets/less/style.less" />
                            <span class="input-group-btn"><button class="btn btn-danger remove-source" type="button"><i class="fa fa-trash-o"></i> Remove</button></span>
                        </div>
                        <br />
                    {/loop}
                {else}
                    <div class="input-group">
                        <input type="text" name="source[less][]" id="less" value="" class="form-control" placeholder="/assets/less/style.less" />
                        <span class="input-group-btn"><button class="btn btn-danger remove-source" type="button"><i class="fa fa-trash-o"></i> Remove</button></span>
                    </div>
                    <br />
                {/if}
            </div>
            <button type="button" class="btn btn-default add-source"><i class="fa fa-plus"></i>&nbsp;Add LESS CSS</button>
        </div>
    </div>
    {if="'' != $last_modified"}
        <div class="form-group">
            <label class="col-lg-2 control-label">Last modified</label>
            <div class="col-lg-10">
                <p class="form-control-static">{$last_modified}</p>
            </div>
        </div>
    {/if}
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            {if="$writable"}
                <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>&nbsp;
            {else}
                <div class="alert alert-warning"><strong>Warning!</strong> You can not Save this Source because it is non-writable.</div>
            {/if}
            <a href="{$baseURL}administrator/sources">Cancel and back to list</a>
        </div>
    </div>
</form>