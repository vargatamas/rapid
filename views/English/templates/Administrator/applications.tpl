<h1>Applications</h1>
<a href="{$baseURL}administrator/applications/add" class="btn btn-primary">Add new Application</a>
<br /><br />
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Success!</strong> {$success}</div>
{/if}
{if="'' != $applications"}
    <div class="table-responsive">
		<table class="table table-striped table-condensed">
			<thead>
				<tr>
					<th>Application</th>
					<th>Languages</th>
					<th>Sources</th>
					<th>Default</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				{loop="applications"}
					<tr>
						<td>{$key}</td>
						<td>{$value.languages}</td>
						<td>{$value.sources}</td>
						<td><span class="glyphicon glyphicon-{if="$value.default"}ok{else}remove{/if}"></span></td>
						<td>
							<a href="{$baseURL}administrator/applications/edit/application:{$key}" title="Edit Application"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;
							<a href="javascript:linkConfirm('{$baseURL}administrator/applications/remove/application:{$key}');" title="Remove Application"><span class="glyphicon glyphicon-trash"></span></a>
						</td>
					</tr>
				{/loop}
			</tbody>
		</table>
	</div>
{else}
    <div class="alert alert-info">
        <strong>No Applications</strong> found. You can add new Applications, just click on <em>Add new Application</em> button above.
    </div>
{/if}