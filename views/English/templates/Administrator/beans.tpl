{if="$beanName"}
    <h1>Bean <em>{$beanName}</em></h1>
    <br /><br />
{/if}
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Success!</strong> {$success}</div>
{/if}
{if="'' != $beanName && '' != $bean && '' != $beans"}
    <a href="{$baseURL}administrator/beans/bean:{$beanName}/add" class="btn btn-primary">Add Bean</a>&nbsp;
    <a href="javascript:linkConfirm('{$baseURL}administrator/beans/bean:{$beanName}/remove-all');" class="btn btn-danger">Remove all Beans</a>
    <div class="table-responsive">
		<table class="table table-striped table-condensed">
			<thead>
				<tr>
					<th>Column name</th>
					<th>Type</th>
				</tr>
			</thead>
			<tbody>
				{loop="bean"}
					<tr>
						<td>{$key}</td>
						<td>{$value}</td>
					</tr>
				{/loop}
			</tbody>
		</table>
	</div>
    {if="'' != $beans.0"}
        <br />
		<div class="table-responsive">
			<table class="table table-striped table-condensed">
				<thead>
					<tr>
						{loop="bean"}
							{if="6 > $counter"}<th>{$key}</th>{/if}
						{/loop}
						<th></th>
					</tr>
				</thead>
				<tbody>
					{loop="beans"}
						{$k=$key}
						<tr>
							{loop="beans.$k"}
								{if="'id' == $key"}{$id=$value}{/if}
								{if="6 > $counter"}<td>{$value|substr:0,80}{if="80 < strlen($value)"} ..{/if}</td>{/if}
							{/loop}
							<td>
								<a href="{$baseURL}administrator/beans/bean:{$beanName}/edit/id:{$id}" title="Edit Bean"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;
								<a href="javascript:linkConfirm('{$baseURL}administrator/beans/bean:{$beanName}/remove/id:{$id}');" title="Remove Bean"><span class="glyphicon glyphicon-trash"></span></a>
							</td>
						</tr>
					{/loop}
				</tbody>
			</table>
		</div>
    {else}
        <div class="alert alert-info">
            <strong>No Beans</strong> found here.
        </div>
    {/if}
{else}
    <div class="alert alert-info">
        <strong>Can not find this Bean</strong>, try again later.
    </div>
{/if}