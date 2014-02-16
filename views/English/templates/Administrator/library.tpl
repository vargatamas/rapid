{if="'' == $recursive"}
    <h1>Library</h1>
    <h5>Upload or remove files from your lib directory.</h5>
    <br /><br />
    {if="'' != $error"}
        <div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
    {/if}
    {if="'' != $success"}
        <div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Success!</strong> {$success}</div>
    {/if}
    {if="'' != $tree"}
        <form id="lib-form" action="{$baseURL}administrator/library/upload" method="post" enctype="multipart/form-data">
            <input type="file" name="library[]" id="upload-dialog" multiple="multiple" class="hidden" />
            <input type="hidden" name="library[path]" id="upload-path" class="hidden" />
        </form>
        <div id="lib-tree">
            <a data-toggle="modal" href="#lib-mkfile-modal" class="btn btn-xs btn-primary lib-mkfile">New file</a>&nbsp;
            <a data-toggle="modal" href="#lib-mkdir-modal" class="btn btn-xs btn-primary lib-mkdir">New directory</a>&nbsp;
            <button class="btn btn-xs btn-primary lib-upload">Upload file(s)</button>
            {loop="$tree"}
                <div class="panel-group" id="accordion-{$counter}">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                {if="is_array($value)"}
                                    <i class="glyphicon glyphicon-folder-close"></i>&nbsp;
                                    <a class="accordion-toggle lib-dir" data-toggle="collapse" data-parent="#accordion-{$counter}" href="#collapse-{$counter}">
                                        {$key}
                                    </a>
                                    <div class="lib-controls pull-right hided">
                                        <a href="{$baseURL}administrator/library/rmdir" class="btn btn-xs btn-default lib-rmdir"><i class="glyphicon glyphicon-trash"></i></a>&nbsp;
                                        <a data-toggle="modal" href="#lib-mkfile-modal" class="btn btn-xs btn-primary lib-mkfile">New file</a>&nbsp;
                                        <a data-toggle="modal" href="#lib-mkdir-modal" class="btn btn-xs btn-primary lib-mkdir">New directory</a>&nbsp;
                                        <button class="btn btn-xs btn-primary lib-upload">Upload file(s)</button>
                                    </div>
                                {else}
                                    <i class="glyphicon glyphicon-file"></i>&nbsp;
                                    {$value}
                                    <div class="lib-controls pull-right hided">
                                        <a href="{$baseURL}administrator/library/rmfile" class="btn btn-xs btn-default lib-rmfile"><i class="glyphicon glyphicon-trash"></i></a>&nbsp;
                                        <a href="{$baseURL}administrator/library/view" class="btn btn-xs btn-default lib-vwfile"><i class="glyphicon glyphicon-pencil"></i></a>
                                    </div>
                                {/if}
                            </h4>
                        </div>
                        {if="is_array($value)"}
                            <div id="collapse-{$counter}" class="panel-collapse collapse">
                                <div class="panel-body">
                                    {$recursive=$value}
                                    {$collapse="_sub"}
                                    {$parent="_pre"}
                                    {include="$CULTURE/templates/Administrator/library"}
                                </div>
                            </div>
                        {/if}
                    </div>
                </div>
            {/loop}
        </div>
        <!-- Modal -->
        <div class="modal fade" id="lib-mkdir-modal" tabindex="-1" role="dialog" aria-labelledby="mkdir-modal-title" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-horizontal" role="form" method="post" action="{$baseURL}administrator/library/mkdir">
                        <input type="hidden" name="lib-mkdir[path]" id="lib-mkdir-path-input" class="hided" />
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="mkdir-modal-title">New Directory</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Parent directory</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static" id="lib-mkdir-path">lib/</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="mkdir-name" class="col-lg-4 control-label">Directory name</label>
                                <div class="col-lg-8">
                                    <input type="text" name="lib-mkdir[name]" class="form-control" id="mkdir-name" placeholder="Photo Gallery" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="lib-mkfile-modal" tabindex="-1" role="dialog" aria-labelledby="mkfile-modal-title" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-horizontal" role="form" method="post" action="{$baseURL}administrator/library/mkfile">
                        <input type="hidden" name="lib-mkfile[path]" id="lib-mkfile-path-input" class="hided" />
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="mkfile-modal-title">New File</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Parent directory</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static" id="lib-mkfile-path">lib/</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="mkfile-name" class="col-lg-4 control-label">Filename</label>
                                <div class="col-lg-8">
                                    <input type="text" name="lib-mkfile[name]" class="form-control" id="mkfile-name" placeholder="myscript.js" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    {else}
        <div class="alert alert-info">
            <strong>Can not scan Library</strong>, probably some permission problem happened.
        </div>
    {/if}
{else}
    {loop="$recursive"}
        <div class="panel-group" id="accordion-{$parent}-{$counter}">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        {if="is_array($value)"}
                            <i class="glyphicon glyphicon-folder-close"></i>&nbsp;
                            <a class="accordion-toggle lib-dir" data-toggle="collapse" data-parent="#accordion-{$parent}-{$counter}" href="#collapse{$collapse}-{$counter}">
                                {$key}
                            </a>
                            <div class="lib-controls pull-right hided">
                                <a href="{$baseURL}administrator/library/rmdir" class="btn btn-xs btn-default lib-rmdir"><i class="glyphicon glyphicon-trash"></i></a>&nbsp;
                                <a data-toggle="modal" href="#lib-mkfile-modal" class="btn btn-xs btn-primary lib-mkfile">New file</a>&nbsp;
                                <a data-toggle="modal" href="#lib-mkdir-modal" class="btn btn-xs btn-primary lib-mkdir">New directory</a>&nbsp;
                                <button class="btn btn-xs btn-primary lib-upload">Upload file(s)</button>
                            </div>
                        {else}
                            <i class="glyphicon glyphicon-file"></i>&nbsp;
                            {$value}
                            <div class="lib-controls pull-right hided">
                                <a href="{$baseURL}administrator/library/rmfile" class="btn btn-xs btn-default lib-rmfile"><i class="glyphicon glyphicon-trash"></i></a>&nbsp;
                                <a href="{$baseURL}administrator/library/view" class="btn btn-xs btn-default lib-vwfile"><i class="glyphicon glyphicon-pencil"></i></a>
                            </div>
                        {/if}
                    </h4>
                </div>
                {if="is_array($value)"}
                    <div id="collapse{$collapse}-{$counter}" class="panel-collapse collapse">
                        <div class="panel-body">
                            {$recursive=$value}
                            {$collapse.="_sub"}
                            {$parent.="_pre"}
                            {include="$CULTURE/templates/Administrator/library"}
                        </div>
                    </div>
                {/if}
            </div>
        </div>
    {/loop}
{/if}