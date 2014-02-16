<h1>Új Fordítás</h1>
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
<form method="post" action="{$baseURL}administrator/translations/add/save" class="form-horizontal" role="form">
    <div class="form-group">
        <label for="language" class="col-lg-2 control-label">Cél nyelv</label>
        <div class="col-lg-3">
            <select id="language" name="translation[language]" class="form-control">
                <option value="" selected="selected"> :: Válassz :: </option>
                {loop="languages"}<option value="{$value}">{$value}</option>{/loop}
            </select>
            <p class="help-block">Fordítva <em>{$CULTURE}</em> nyelvről.</p>
        </div>
    </div>
    <div class="form-group">
        <label for="from" class="col-lg-2 control-label">Miről</label>
        <div class="col-lg-10">
            <input type="text" name="translation[from]" value="{$translation.from}" class="form-control" id="from" placeholder="This is a message." />
            <p class="help-block">Használhatsz változókat, ehhez írd a <strong>#text#</strong> szöveget a megfelelő helyre.</p>
        </div>
    </div>
    <div class="form-group">
        <label for="to" class="col-lg-2 control-label">Mire</label>
        <div class="col-lg-10">
            <input type="text" name="translation[to]" value="{$translation.to}" class="form-control" id="to" placeholder="Ez egy üzenet." />
            <p class="help-block">Amennyiben vannak változóid, balról jobbra kerülnek lecserélésre, sorban.</p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary">Mentés</button>&nbsp;
            <a href="{$baseURL}administrator/translations">Mégse és vissza a listához</a>
        </div>
    </div>
</form>