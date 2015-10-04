<form method="post" action="{$baseURL}administrator/beans/new/save" class="form-horizontal" role="form">
    <h1>
        Új Bean
        <button type="submit" class="btn btn-sm pull-right btn-primary"><i class="fa fa-floppy-o"></i> Mentés</button>
    </h1>
    {if="'' != $error"}
    <div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
    {/if}
    <div class="alert alert-warning"><strong>Figyelem!</strong> Ne használj speciális karaktereket, csak kisbetűs alfabetikusokat. Továbbá ajánlott camelCase neveket használni.</div>
    <div class="form-group">
        <label for="beanName" class="col-lg-2 control-label">Bean neve</label>
        <div class="col-lg-5">
            <input type="text" name="bean[name]" class="form-control" value="{$beanName}" id="beanName" placeholder="hirek" />
            <p class="help-block">Az új Bean neve (tábla név).</p>
        </div>
    </div>
    <div class="form-group">
        <label for="beanField" class="col-lg-2 control-label">Mező(k)</label>
        <div class="col-lg-5">
            <input type="text" name="bean[field][]" class="form-control" placeholder="mezo" id="beanField" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-5">
            <button type="button" class="btn btn-default" id="add-bean-field"><i class="fa fa-plus"></i>&nbsp;Új Mező</button>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Mentés</button>
        </div>
    </div>
</form>