<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/jquery-1.11.2.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script>
        var selected_file = {title:'', path:''};
        function select_file(a_obj){
            var a = $(a_obj).parent().parent().find('.media-heading:first');
            var title = $(a).text();
            var path = $(a).attr('path');
            var size = $(a_obj).parent().parent().find('.filesize:first').text();
            selected_file ={title: title, path:path, size: size};
        }


    </script>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <ol class="breadcrumb">
                <li><a href="?"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a></li>
                <?php $breads = tmsFileManager::getBreadcrumbParts(); ?>
                <?php if (is_array($breads) && count($breads)): ?>
                    <?php foreach ($breads as $path): ?>
                        <li><a href="?path=<?php echo $path['path']; ?>"><?php echo $path['title']; ?></a></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ol>
        </div>
    </div>
    <div class="row">

        <?php $folders = tmsFileManager::getFolders(); ?>
        <?php if (is_array($folders) && count($folders)): ?>
            <?php foreach ($folders as $folder): ?>

                <div class="col-xs-12 col-sm-12 col-md-3 entry">
                    <div class="media">
                        <div class="media-left">
                            <a href="#">
                                <img class="media-object"
                                     src="/images/<?php echo tmsFileManager::getIco($folder['title'], true); ?>"
                                     data-holder-rendered="true">
                            </a>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading" id="media-heading"><a
                                    href="?path=<?php echo $folder['path']; ?>"><?php echo $folder['title']; ?></a></h4>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php $files = tmsFileManager::getFiles(); ?>
        <?php if (is_array($files) && count($files)): ?>
            <?php foreach ($files as $file): ?>
                <div class="col-xs-12 col-sm-4 col-md-3 entry">
                    <div class="media">
                        <div class="media-left">
                            <a href="#">
                                <img class="media-object"
                                     src="/images/<?php echo tmsFileManager::getIco($file['title']); ?>"
                                     data-holder-rendered="true">
                            </a>
                        </div>
                        <div class="media-body" style="word-wrap: break-word">
                            <h4 class="media-heading" id="media-heading"
                                style="overflow:inherit; word-wrap: break-word" path="<?php echo $file['path'];?>"><?php echo $file['title']; ?></h4>
                            <span class="filesize"><?php echo $file['size']; ?></span>


                        </div>
                        <div class="media-right">
                            <a href="#" class="btn-link" onclick="select_file(this)" data-toggle="modal" data-target="#myModal">
                                <span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>
                            </a>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Operations</h4>
            </div>
            <div class="modal-body">
                <label>File:</label> <span style="font-size: 12px;" id="filename"></span><br/>
                <label class="filesize">size:</label> <span class="filesize" id="filenamesize"></span><br/>

                <div class="list-group">
<!--                    <a href=""  class="list-group-item"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Rename </a>-->
                    <a href="" id="a_download_file" class="list-group-item"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
                    <hr/>
                    <a  href="" id="a_remove_file" class="list-group-item " style="background-color: #f2dede;"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Remove</a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>

    $('#myModal').on('show.bs.modal', function (e) {
        $('#filename').text(selected_file.title);
        $('#filenamesize').text(selected_file.size);
        $('#a_download_file').attr('href','?act=download&path='+selected_file.path);
        $('#a_remove_file').attr('href','?act=remove&path='+selected_file.path);
    })
</script>
</body>
</html>