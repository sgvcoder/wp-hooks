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
    .wwww{
        /*position: fixed;*/
        top: 0;
        left: 0;
        z-index: 99999;
        background: #fff;
        width: 100%;
        height: 100%;
    }
</style>

<div class="wwww">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">

                <table class="table table-hover p404-urls-list">
                    <thead>
                        <tr>
                            <th width="30">#</th>
                            <th width="35%">URL</th>
                            <th width="35%">Redirect to</th>
                            <th width="30">Action</th>
                            <th class="text-center" width="80">Views</th>
                            <th width="150">Added at</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $value): ?>
                            <tr>
                                <th scope="row"><?php echo $value["id"] ?></th>
                                <td width="30%"><?php echo $value["url"] ?></td>
                                <td width="30%">
                                    <div class="p404-box-redirect-to">
                                        <div class="p404-redirect-to-url" data-p404-redirect-to-url-def="<?php echo $value["redirect_to"] ?>">
                                            <?php echo $value["redirect_to"] ?>
                                        </div>
                                        <button class="btn btn-sm btn-default hidden p404-url-o"><span class="glyphicon glyphicon-menu-hamburger"></span></button>
                                    </div>
                                </td>
                                <td>
                                    <a href="#" class="p404-redirect-to-edit">edit</a>
                                    <a href="#" class="p404-redirect-to-apply hidden" data-url-id="<?php echo $value["id"] ?>">apply</a>
                                    <br><a href="#" class="p404-redirect-to-cancel hidden">cancel</a>
                                </td>
                                <td class="text-center"><?php echo $value["total_view"] ?></td>
                                <td>
                                    <?php
                                    $dt = new DateTime($value["added"]);
                                    echo $dt->format("Y-m-d H:i");
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="p404-url-o-m">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Redirect to:</h4>
            </div>
            <div class="modal-body">
                <select class="form-control" name="p404-url-o-m-types" id="p404-url-o-m-types">
                    <?php foreach ($pageTypes as $value): ?>
                    <option value="<?php echo $value["alias"]; ?>"><?php echo $value["name"]; ?></option>
                    <?php endforeach; ?>
                </select>
                <select class="form-control p404-url-o-m-l p404-url-o-m-l-pages" name="p404-url-o-m-link" id="p404-url-o-m-link">
                    <?php foreach ($pages as $value): ?>
                    <option value="<?php echo $value->guid; ?>"><?php echo $value->post_title; ?></option>
                    <?php endforeach; ?>
                </select>
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

    jQuery(".p404-redirect-to-edit").off("click").click(function (e) {

        e.preventDefault();

        jQuery(this).addClass("hidden");
        jQuery(this).parents("td").find(".p404-redirect-to-apply").removeClass("hidden");
        jQuery(this).parents("td").find(".p404-redirect-to-cancel").removeClass("hidden");
        jQuery(this).parents("tr").find(".p404-url-o").removeClass("hidden");

        jQuery(this).parents("tr").find(".p404-redirect-to-url").attr({
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
        jQuery(this).parents("td").find(".p404-redirect-to-cancel").addClass("hidden");
        jQuery(this).parents("tr").find(".p404-url-o").addClass("hidden");
        jQuery(this).parents("td").find(".p404-redirect-to-edit").removeClass("hidden");

        jQuery(this).parents("tr").find(".p404-redirect-to-url").attr({
            contenteditable: false
        });

        var data = {
            action: "p404_redirect_to_save",
            id: jQuery(this).attr("data-url-id"),
            url: jQuery(this).parents("tr").find(".p404-redirect-to-url").text().trim()

        };
        jQuery.post(ajaxurl, data, function (response) {
            console.log(response);
        });

    });

    jQuery(".p404-redirect-to-cancel").off("click").click(function (e) {

        e.preventDefault();

        jQuery(this).addClass("hidden");
        jQuery(this).parents("td").find(".p404-redirect-to-apply").addClass("hidden");
        jQuery(this).parents("tr").find(".p404-url-o").addClass("hidden");
        jQuery(this).parents("td").find(".p404-redirect-to-edit").removeClass("hidden");

        jQuery(this).parents("tr").find(".p404-redirect-to-url").attr({
            contenteditable: false
        }).text(jQuery(this).parents("tr").find(".p404-redirect-to-url").attr("data-p404-redirect-to-url-def"));

    });

    jQuery(".p404-url-o").off("click").click(function (e) {

        e.preventDefault();

        jQuery("#p404-url-o-m").modal("show");
        jQuery("#p404-url-o-m-apply");

        p404_selected_rec = jQuery(this).parents(".p404-box-redirect-to").find(".p404-redirect-to-url");

    });

    jQuery("#p404-url-o-m-apply").off("click").click(function (e) {

        e.preventDefault();

        p404_selected_rec.html(jQuery("#p404-url-o-m-link").val());

    });

    jQuery("#p404-url-o-m-types").off("change").change(function (e) {

        e.preventDefault();

        var typeSelected = jQuery(this).val();
        jQuery("select.p404-url-o-m-l").addClass("hidden");
        jQuery("select.p404-url-o-m-l-" + typeSelected).removeClass("hidden");

    });

    function p404_validURL(str) {

        var pattern = new RegExp("^|[\s.:;?\-\]<\(])(https?://[-\w;/?:@&=+$\|\_.!~*\|'()\[\]%#,☺]+[\w/#](\(\))?)(?=$|[\s',\|\(\).:;?\-\[\]>\)]", "i");

        if (!pattern.test(str)) {
            return false;
        } else {
            return true;
        }
    }
</script>