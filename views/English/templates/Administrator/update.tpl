<h1>Update Rapid</h1>
<h5>You can update Rapid to the newest version.</h5>
<br /><br />
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><strong>Success!</strong> {$success}</div>
<br />
<div class="jumbotron text-center">
    <a href="{$baseURL}administrator/update/install" class="btn btn-lg btn-success">Install Update</a>
</div>
{/if}