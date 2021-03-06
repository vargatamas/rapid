<h2>
Library&nbsp;<small>Upload or remove files from your <em>{$filesDir}</em> directory.</small>
</h2>
<br>
{if="'' != $error"}
    <div class="alert alert-danger"><strong>Error!</strong> {$error}</div>
{/if}
{if="'' != $success"}
    <div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><strong>Success!</strong> {$success}</div>
{/if}
{if="isset($library)"}
    <form id="lib-form" action="{$baseURL}administrator/library/upload" method="post" enctype="multipart/form-data">
        <input type="file" name="library[]" id="upload-dialog" multiple="multiple" class="hidden" />
        <input type="hidden" name="library[path]" id="upload-path" class="hidden" />
    </form>
    <div class="btn-group pull-right">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            Action <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li>
                <a data-toggle="modal" href="#lib-mkfile-modal" class="lib-mkfile"><i class="fa fa-file"></i>&nbsp;New file</a>
            </li>
            <li>
                <a data-toggle="modal" href="#lib-mkdir-modal" class="lib-mkdir"><i class="fa fa-folder"></i>&nbsp;New directory</a>
            </li>
            <li>
                <a href="#" class="lib-upload"><i class="fa fa-cloud-upload"></i>&nbsp;Upload file(s)</a>
            </li>
        </ul>
    </div>
    <ol class="breadcrumb">
        {$path=""}
        {loop="library.path"}
            {$path.=$value.'/'}
            {if="$value != $library.current"}<li><a href="#" onclick="changeDir('{$path|substr:0,-1}');">{$value}</a></li>{/if}
        {/loop}
        <li class="active">{$library.current}</li>
    </ol>
    <div class="admin-files">
        <div class="row">
            {loop="library.tree.directories"}
                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 text-center item">
                    <a href="#" class="chdir moving" onclick="changeDir('{$value.path}');">
                        <div class="icon"><i class="fa fa-folder"></i></div>
                        <div class="title">{$value.filename}</div>
                        <div class="description">{$value.last_modified}</div>
                    </a>
                    <div class="actions">
                        <a href="#" class="rmdir text-danger" title="Delete Permanently" onclick="libraryRemoveDir('{$value.filename}');"><i class="fa fa-trash-o"></i></a>
                    </div>
                </div>
            {/loop}
            {loop="library.tree.files"}
                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 text-center item">
                    <a href="javascript:libraryViewFile('{$value.basename}');" class="moving">
                        <div class="icon"><i class="fa fa-file"></i></div>
                        <div class="title">{$value.basename}</div>
                        <div class="description">{$value.filesize}</div>
                    </a>
                    <div class="actions">
                        <a href="#" class="rmfile text-danger" title="Delete Permanently" onclick="libraryRemoveFile('{$value.basename}');"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;
                        <a href="#" class="vwfile" title="View / Edit file" onclick="libraryViewFile('{$value.basename}');"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;
                        <a href="#" class="usefile" title="Use file as Source" onclick="libraryUseFile('{$value.basename}');"><i class="fa fa-paperclip"></i></a>
                    </div>
                </div>
            {/loop}
        </div>
    </div>
{else}
    <div class="alert alert-info">
        <strong>Can not scan Library</strong>, probably some permission problem happened.
    </div>
{/if}

{if="isset($path)"}
<script> window.onload = function() { changeDir('{$path}'); } </script>
{/if}

{if="isset($filesDir)"}
<script> var filesDir = "{$filesDir}"; </script>
{/if}

<!-- Modal: New Directory -->
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
                            <p class="form-control-static" id="lib-mkdir-path">/{$filesDir}</p>
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
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal: New File -->
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
                            <p class="form-control-static" id="lib-mkfile-path">/{$filesDir}</p>
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
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal: Use file -->
<div class="modal fade" id="lib-usefile-modal" tabindex="-1" role="dialog" aria-labelledby="usefile-modal-title" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form class="form-horizontal" role="form" method="post" action="{$baseURL}administrator/library/use/save">
          <input type="hidden" name="usefile[path]" id="use-file-path-input" class="hided" />
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="usefile-modal-title">Use file as Source</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
                <label class="col-lg-4 control-label">File</label>
                <div class="col-lg-8">
                    <p class="form-control-static" id="use-file-path"></p>
                </div>
            </div>
            <div class="form-group">
                <label for="use-file-app" class="col-lg-4 control-label">Application</label>
                <div class="col-lg-8">
                    <select id="use-file-app" name="usefile[application]" class="form-control"></select>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
          </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->