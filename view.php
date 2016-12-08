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
    .p404-redirect-to-edit{
        position: absolute;
        right: 0;
        top: 0;
        background: #5b9dd9;
        color: #fff;
        border-radius: 2px;
        padding: 0 5px;
        text-decoration: none;
        display: none;
    }
    .p404-redirect-to-edit:hover{
        color: #fff;
        background: #337ab7;
        text-decoration: none;
    }
    .p404-redirect-to-apply{
        position: absolute;
        right: 0;
        top: 0;
        background: #3acc3b;
        color: #fff;
        border-radius: 2px;
        padding: 0 5px;
        text-decoration: none;
    }
    .p404-redirect-to-apply:hover{
        color: #fff;
        background: #12b113;
        text-decoration: none;
    }
    .p404-urls-list tr:hover .p404-redirect-to-edit{
        display: block;
    }
    .p404-redirect-to-url[contenteditable="true"]{
        outline: 1px solid #5b9dd9;
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
                            <th class="text-center" width="80">Views</th>
                            <th width="150">Added at</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $value): ?>
                            <tr>
                                <th scope="row"><?php echo $value["id"] ?></th>
                                <td><?php echo $value["url"] ?></td>
                                <td>
                                    <div class="p404-box-redirect-to">
                                        <div class="p404-redirect-to-url"><?php echo $value["redirect_to"] ?></div>
                                        <a href="#" class="p404-redirect-to-edit">edit</a>
                                        <a href="#" class="p404-redirect-to-apply hidden" data-url-id="<?php echo $value["id"] ?>">apply</a>
                                    </div>
                                </td>
                                <td class="text-center"><?php echo $value["total_view"] ?></td>
                                <td>
                                    <?php
                                    $dt = new DateTime($value["added"]);
                                    echo $dt->format("g:ia m/d/Y");
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

<script>
    jQuery(".p404-redirect-to-edit").off("click").click(function (e) {

        e.preventDefault();

        jQuery(this).addClass("hidden");
        jQuery(this).parents(".p404-box-redirect-to").find(".p404-redirect-to-apply").removeClass("hidden");

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
        jQuery(this).parents(".p404-box-redirect-to").find(".p404-redirect-to-edit").removeClass("hidden");

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

    function p404_validURL(str) {

        var pattern = new RegExp("^|[\s.:;?\-\]<\(])(https?://[-\w;/?:@&=+$\|\_.!~*\|'()\[\]%#,☺]+[\w/#](\(\))?)(?=$|[\s',\|\(\).:;?\-\[\]>\)]", "i");

        if (!pattern.test(str)) {
            return false;
        } else {
            return true;
        }
    }
</script>