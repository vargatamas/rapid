<h1>Rapid frissítés</h1>
<h5>Frissíteni lehet a Rapid-t a legújabb verzióra.</h5>
<br /><br />
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><strong>Success!</strong> {$success}</div>
<br />
<div class="jumbotron text-center">
    <a href="{$baseURL}administrator/update/install" class="btn btn-lg btn-success">Frissítés telepítése</a>
</div>
{/if}