<form method="post" action="{$baseURL}administrator/translations/edit/save/language:{$tLanguage}/index:{$tIndex}" class="form-horizontal" role="form">
    <h1>
        Fordítás szerkesztése
        <button type="submit" class="btn btn-sm pull-right btn-primary"><i class="fa fa-floppy-o"></i> Mentés</button>
    </h1>
    {if="'' != $error"}
    <div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
    {/if}
    <div class="form-group">
        <label for="language" class="col-lg-2 control-label">Fordítás célnyelve</label>
        <div class="col-lg-3">
            <p class="form-control-static">{$tLanguage}</p>
        </div>
    </div>
    <div class="form-group">
        <label for="from" class="col-lg-2 control-label">Szöveg amiről</label>
        <div class="col-lg-10">
            <input type="text" name="translation[from]" value="{$translation.from}" class="form-control" id="from" placeholder="This is a message." />
            <p class="help-block">Használhatsz változókat, ehhez írd a <strong>#text#</strong> szöveget a megfelelő helyre.</p>
        </div>
    </div>
    <div class="form-group">
        <label for="to" class="col-lg-2 control-label">Szöveg amire</label>
        <div class="col-lg-10">
            <input type="text" name="translation[to]" value="{$translation.to}" class="form-control" id="to" placeholder="Dies ist ein Nachricht." />
            <p class="help-block">Amennyiben vannak változóid, balról jobbra kerülnek lecserélésre, sorban.</p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Mentés</button>&nbsp;
            <a href="{$baseURL}administrator/translations">Mégse és vissza a listához</a>
        </div>
    </div>
</form>