<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<style>
    .p404-urls-list td{
        word-break: break-all;
    }
    .p404-box-redirect-to{
        position: relative;
    }
    .p404-redirect-to-url{
        padding: 3px 35px 3px 5px;
        min-height: 26px;        
    }
    .p404-redirect-to-url[contenteditable="true"]{
        outline: 1px solid #5b9dd9 !important;
        background: #ffffff;
    }
    .p404-url-o{
        position: absolute;
        right: 1px;
        top: 1px;
        padding: 2px 5px;
    }
    .p404-break-w{
        /*word-wrap: break-word;*/
        word-break: break-all;
    }
    .p404-item:hover{
        background-color: #e8f0f7;
    }
</style>

<div>

    <div class="row">
        <?php // print_r($foundRows);  ?>
    </div>

    <div class="container-fluid">
        <div class="row hidden-xs hidden-sm">
            <div class="col-md-4"><label>URL:</label></div>
            <div class="col-md-4"><label>Redirect to:</label></div>
            <div class="col-md-1"><label>Action:</label></div>
            <div class="col-md-1"><label>Views:</label></div>
            <div class="col-md-2"><label>Added at:</label></div>
        </div>
        <div id="p404-data-table">
            <?php require_once "datatable.php"; ?>
        </div>
    </div>

    <?php if($foundRows > $limit): ?>
        <nav aria-label="Paging">
            <ul class="pagination p404-pagination">
               
                <?php for($i = 0; $i < ceil($foundRows / $limit); $i++): ?>
                <?php $active = ($i == 0) ? "active" : ""; ?>
                <li class="<?php echo $active; ?>"><a href="#" class="p404-page" data-page-id="<?php echo ($i + 1) ?>"><?php echo ($i + 1) ?></a></li>
                <?php endfor; ?>
               
            </ul>
        </nav>
    <?php endif; ?>

</div>

<div class="modal fade" tabindex="-1" role="dialog" id="p404-url-o-m">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Redirect to:</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="p404-url-o-m-types">Type:</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="p404-url-o-m-types" id="p404-url-o-m-types">
                                <?php foreach ($pageTypes as $value): ?>
                                    <option value="<?php echo $value["alias"]; ?>"><?php echo $value["name"]; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group p404-url-o-m-l p404-url-o-m-l-pages">
                        <label class="col-sm-2 control-label" for="p404-url-o-m-link">Link to:</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="p404-url-o-m-link" id="p404-url-o-m-link">
                                <?php foreach ($pages as $value): ?>
                                    <option value="<?php echo $value->guid; ?>"><?php echo $value->post_title; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group hidden p404-url-o-m-l p404-url-o-m-l-posts">
                        <label class="col-sm-2 control-label" for="p404-url-o-m-link">Link to:</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="p404-url-o-m-link" id="p404-url-o-m-link">
                                <?php foreach ($posts as $value): ?>
                                    <option value="<?php echo '/' . $value->post_name; ?>"><?php echo $value->post_title; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="p404-url-o-m-apply">Apply</button>
            </div>
        </div>
    </div>
</div>

<script>

    var p404_selected_rec = null;

    p404_init();

    jQuery(".p404-pagination .p404-page").off("click").click(function (e) {

        e.preventDefault();

        jQuery(".p404-pagination li").removeClass("active");
        jQuery(this).parents("li").addClass("active");

        var data = {
            action: "p404_show_page",
            id: jQuery(this).attr("data-page-id")

        };
        jQuery.post(ajaxurl, data, function (response) {

            var json = JSON.parse(response);
            
            if(json.html) {

                jQuery("#p404-data-table").html(json.html);
                p404_init();
            }
        });

    });

    function p404_init() {

        jQuery(".p404-redirect-to-edit").off("click").click(function (e) {

            e.preventDefault();

            jQuery(this).addClass("hidden");
            jQuery(this).parents(".p404-td").find(".p404-redirect-to-apply").removeClass("hidden");
            jQuery(this).parents(".p404-td").find(".p404-redirect-to-cancel").removeClass("hidden");
            jQuery(this).parents(".row").find(".p404-url-o").removeClass("hidden");

            jQuery(this).parents(".row").find(".p404-redirect-to-url").attr({
                contenteditable: true
            }).keyup(function () {

                if (p404_validURL(jQuery(this).text().trim())) {
                    jQuery(this).addClass("p404-danger");
                } else {
                    jQuery(this).removeClass("p404-danger");
                }

            }).click();

        });

        jQuery(".p404-redirect-to-apply").off("click").click(function (e) {

            e.preventDefault();

            jQuery(this).addClass("hidden");
            jQuery(this).parents(".p404-td").find(".p404-redirect-to-cancel").addClass("hidden");
            jQuery(this).parents(".row").find(".p404-url-o").addClass("hidden");
            jQuery(this).parents(".p404-td").find(".p404-redirect-to-edit").removeClass("hidden");

            jQuery(this).parents(".row").find(".p404-redirect-to-url").attr({
                contenteditable: false
            });

            var data = {
                action: "p404_redirect_to_save",
                id: jQuery(this).attr("data-url-id"),
                url: jQuery(this).parents(".row").find(".p404-redirect-to-url").text().trim()

            };
            jQuery.post(ajaxurl, data, function (response) {
                console.log(response);
            });

        });

        jQuery(".p404-redirect-to-cancel").off("click").click(function (e) {

            e.preventDefault();

            jQuery(this).addClass("hidden");
            jQuery(this).parents(".p404-td").find(".p404-redirect-to-apply").addClass("hidden");
            jQuery(this).parents(".row").find(".p404-url-o").addClass("hidden");
            jQuery(this).parents(".p404-td").find(".p404-redirect-to-edit").removeClass("hidden");

            jQuery(this).parents(".row").find(".p404-redirect-to-url").attr({
                contenteditable: false
            }).text(jQuery(this).parents(".row").find(".p404-redirect-to-url").attr("data-p404-redirect-to-url-def"));

        });

        jQuery(".p404-url-o").off("click").click(function (e) {

            e.preventDefault();

            jQuery("#p404-url-o-m").modal("show");
            jQuery("#p404-url-o-m-apply");

            p404_selected_rec = jQuery(this).parents(".p404-box-redirect-to").find(".p404-redirect-to-url");

        });

        jQuery("#p404-url-o-m-apply").off("click").click(function (e) {

            e.preventDefault();

            var typeSelected = jQuery("#p404-url-o-m-types").val();
            p404_selected_rec.html(jQuery(".p404-url-o-m-l-" + typeSelected + " #p404-url-o-m-link").val());

        });

        jQuery("#p404-url-o-m-types").off("change").change(function (e) {

            e.preventDefault();

            var typeSelected = jQuery(this).val();
            jQuery(".p404-url-o-m-l").addClass("hidden");
            jQuery(".p404-url-o-m-l-" + typeSelected).removeClass("hidden");

        });
    }

    function p404_validURL(str) {

        var pattern = new RegExp("^|[\s.:;?\-\]<\(])(https?://[-\w;/?:@&=+$\|\_.!~*\|'()\[\]%#,☺]+[\w/#](\(\))?)(?=$|[\s',\|\(\).:;?\-\[\]>\)]", "i");

        if (!pattern.test(str)) {
            return false;
        } else {
            return true;
        }
    }
</script>