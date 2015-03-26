{if="$beanName"}
    <h2>
    	Bean&nbsp;<em>{$beanName}</em>
    	{if="'' != $beanName && '' != $bean"}
    	<div class="btn-group btn-group-sm pull-right" role="group">
			<a href="{$baseURL}administrator/beans/bean:{$beanName}/add" class="btn btn-primary">Add Item</a>&nbsp;
		    <a href="javascript:linkConfirm('{$baseURL}administrator/beans/bean:{$beanName}/remove-all');" class="btn btn-danger">Remove all items</a>
		</div>
		{/if}
    </h2>
    <br>
{/if}
{if="'' != $error"}
<div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Success!</strong> {$success}</div>
{/if}
{if="'' != $beanName && '' != $bean"}
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
    {if="isset($beans)"}
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
							<td class="text-right">
								<a href="{$baseURL}administrator/beans/bean:{$beanName}/edit/id:{$id}" title="Edit item"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;
								<a href="javascript:linkConfirm('{$baseURL}administrator/beans/bean:{$beanName}/remove/id:{$id}');" title="Remove item" class="text-danger"><span class="glyphicon glyphicon-trash"></span></a>
							</td>
						</tr>
					{/loop}
				</tbody>
			</table>
		</div>
        {if="isset($prevStart) || isset($nextStart) || isset($page)"}
            <div class="text-center">
                <strong>{$page}. page</strong><br /><br />
                <div class="btn-group">
                    {if="isset($prevStart)"}
                        <a href="{$baseURL}administrator/beans/bean:{$beanName}/start:{$prevStart}" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i>&nbsp;previous</a>
                    {/if}
                    {if="isset($nextStart)"}
                        <a href="{$baseURL}administrator/beans/bean:{$beanName}/start:{$nextStart}" class="btn btn-default">next&nbsp;<i class="glyphicon glyphicon-arrow-right"></i></a>
                    {/if}
                </div>
            </div>
        {/if}
    {else}
        <div class="alert alert-info">
            <strong>No Items</strong> found here.
        </div>
    {/if}
{else}
    <div class="alert alert-info">
        <strong>Can not find this Bean</strong>, try again later.
    </div>
{/if}