<h1>Alkalmazás Források szerkesztése</h1>
<form method="post" action="{$baseURL}administrator/sources/edit/application:{$application}/save" class="form-horizontal" role="form">
    <div class="form-group">
        <label for="javascripts" class="col-lg-2 control-label">Javascript fájlok</label>
        <div class="col-lg-10">
            <div>
                {if="'' != $sourceContent.javascripts.0"}
                    {loop="sourceContent.javascripts"}
                        <div class="input-group">
                            <input type="text" name="source[javascripts][]" value="{$value}" class="form-control" placeholder="lib/js/script.js" />
                            <span class="input-group-btn"><button class="btn btn-danger remove-source" type="button">Eltávolít</button></span>
                        </div>
                        <br />
                    {/loop}
                {else}
                    <div class="input-group">
                        <input type="text" name="source[javascripts][]" id="javascripts" value="" class="form-control" placeholder="lib/js/script.js" />
                        <span class="input-group-btn"><button class="btn btn-danger remove-source" type="button">Eltávolít</button></span>
                    </div>
                    <br />
                {/if}
            </div>
            <button type="button" class="btn btn-default add-source"><i class="glyphicon glyphicon-plus"></i>&nbsp;Új Javascript fájl</button>
        </div>
    </div>
    <div class="form-group">
        <label for="stylesheets" class="col-lg-2 control-label">Stílusfájlok</label>
        <div class="col-lg-10">
            <div>
                {if="'' != $sourceContent.stylesheets.0"}
                    {loop="sourceContent.stylesheets"}
                        <div class="input-group">
                            <input type="text" name="source[stylesheets][]" value="{$value}" class="form-control" placeholder="lib/css/style.css" />
                            <span class="input-group-btn"><button class="btn btn-danger remove-source" type="button">Eltávolít</button></span>
                        </div>
                        <br />
                    {/loop}
                {else}
                    <div class="input-group">
                        <input type="text" name="source[stylesheets][]" id="stylesheets" value="" class="form-control" placeholder="lib/css/style.css" />
                        <span class="input-group-btn"><button class="btn btn-danger remove-source" type="button">Eltávolít</button></span>
                    </div>
                    <br />
                {/if}
            </div>
            <button type="button" class="btn btn-default add-source"><i class="glyphicon glyphicon-plus"></i>&nbsp;Új Stílusfájl</button>
        </div>
    </div>
    <div class="form-group">
        <label for="less" class="col-lg-2 control-label">LESS fájlok</label>
        <div class="col-lg-10">
            <div>
                {if="'' != $sourceContent.less.0"}
                    {loop="sourceContent.less"}
                        <div class="input-group">
                            <input type="text" name="source[less][]" value="{$value}" class="form-control" placeholder="lib/less/style.less" />
                            <span class="input-group-btn"><button class="btn btn-danger remove-source" type="button">Eltávolít</button></span>
                        </div>
                        <br />
                    {/loop}
                {else}
                    <div class="input-group">
                        <input type="text" name="source[less][]" id="less" value="" class="form-control" placeholder="lib/less/style.less" />
                        <span class="input-group-btn"><button class="btn btn-danger remove-source" type="button">Eltávolít</button></span>
                    </div>
                    <br />
                {/if}
            </div>
            <button type="button" class="btn btn-default add-source"><i class="glyphicon glyphicon-plus"></i>&nbsp;Új LESS fájlok</button>
        </div>
    </div>
    {if="'' != $last_modified"}
        <div class="form-group">
            <label class="col-lg-2 control-label">Utolsó módosítás</label>
            <div class="col-lg-10">
                <p class="form-control-static">{$last_modified}</p>
            </div>
        </div>
    {/if}
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            {if="$writable"}
                <button type="submit" class="btn btn-primary">Mentés</button>&nbsp;
            {else}
                <div class="alert alert-warning"><strong>Figyelem!</strong> Nem tudod elmenteni ezeket a Forrásokat, mivel nem írhatóak.</div>
            {/if}
            <a href="{$baseURL}administrator/sources">Mégse és vissza a listához</a>
        </div>
    </div>
</form>