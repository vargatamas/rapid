<form method="post" action="{$baseURL}administrator/beans/execute" class="form-horizontal" role="form">
    <h1>
        SQL futtatás
        <div class="btn-group btn-group-sm pull-right">
        	<button type="submit" class="btn btn-primary"><i class="fa fa-play"></i> Futtatás</button>
        	<button type="reset" class="btn btn-default"><i class="fa fa-ban"></i> Mégse</button>
    	</div>
    </h1>
    {if="'' != $success"}
    <div class="alert alert-success"><strong>Kész!</strong> {$success}</div>
    {/if}
    {if="'' != $error"}
    <div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
    {/if}
    <div class="form-group">
        <div class="col-lg-12">
            <input type="text" class="form-control" name="exec[sql]" id="sql" placeholder="SELECT * FROM users WHERE is_active = 1;" value="{$sql}">
            <p class="help-block"><strong>Légy óvatos.</strong> Bármilyen típusú SQL parancsot futtathatsz, csak akkor tedd ha biztos vagy benne mit csinál.</p>
        </div>
    </div>
</form>
{if="isset($result)"}
<h3>
	Eredmény(ek)
	{if="isset($bean)"}
	<a href="{$baseURL}administrator/beans/bean:{$bean}" class="btn btn-sm btn-default pull-right">{$bean|ucfirst} <i class="fa fa-angle-right"></i></a>
	{/if}
</h3>
<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				{loop="result.0"}
				<th>{$key}</th>
				{/loop}
			</tr>
		</thead>
		<tbody>
			<tr>
				{loop="result"}
				{loop="value"}
				<td>{$value}</td>
				{/loop}
				</tr><tr>
				{/loop}
			</tr>
		</tbody>
	</table>
</div>
{/if}