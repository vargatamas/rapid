{if="$beanName"}
    <h2>
    	Bean <em>{$beanName}</em>
    	{if="'' != $beanName && '' != $bean"}
    	<div class="btn-group btn-group-sm pull-right" role="group">
	    	<a href="{$baseURL}administrator/beans/bean:{$beanName}/add" class="btn btn-primary"><i class="fa fa-plus"></i> Új elem</a>&nbsp;
	    	<a href="javascript:linkConfirm('{$baseURL}administrator/beans/bean:{$beanName}/remove-all');" class="btn btn-danger"><i class="fa fa-trash-o"></i> Minden elem eltávolítása</a>
    	</div>
    	{/if}
    </h2>
    <br>
{/if}
{if="'' != $error"}
<div class="alert alert-danger"><strong>Hiba!</strong> {$error}</div>
{/if}
{if="'' != $success"}
<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Kész!</strong> {$success}</div>
{/if}
{if="'' != $beanName && '' != $bean"}
    <div class="table-responsive">
		<table class="table table-striped table-condensed">
			<thead>
				<tr>
					<th>Oszlop neve</th>
					<th>Típus</th>
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
								<a href="{$baseURL}administrator/beans/bean:{$beanName}/edit/id:{$id}" title="Elem szerkesztése"><i class="fa fa-pencil"></i></a>&nbsp;
								<a href="javascript:linkConfirm('{$baseURL}administrator/beans/bean:{$beanName}/remove/id:{$id}');" title="Elem eltávolítása" class="text-danger"><i class="fa fa-trash-o"></i></a>
							</td>
						</tr>
					{/loop}
				</tbody>
			</table>
		</div>
        {if="isset($prevStart) || isset($nextStart) || isset($page)"}
            <div class="text-center">
                <strong>{$page}. oldal</strong><br /><br />
                <div class="btn-group">
                    {if="isset($prevStart)"}
                        <a href="{$baseURL}administrator/beans/bean:{$beanName}/start:{$prevStart}" class="btn btn-default"><i class="fa fa-angle-left"></i>&nbsp;előző</a>
                    {/if}
                    {if="isset($nextStart)"}
                        <a href="{$baseURL}administrator/beans/bean:{$beanName}/start:{$nextStart}" class="btn btn-default">következő&nbsp;<i class="fa fa-angle-right"></i></a>
                    {/if}
                </div>
            </div>
        {/if}
    {else}
        <div class="alert alert-info">
            <strong>Nincsenek elemek</strong> most itt.
        </div>
    {/if}
{else}
    <div class="alert alert-info">
        <strong>Nem található ez a Bean</strong>, próbáld újra később.
    </div>
{/if}