<?php foreach ($data as $value): ?>
    <div class="row p404-item" data-item-id="<?php echo $value["id"] ?>">
        <div class="col-md-4 p404-break-w p404-td">
            <label class="hidden-md hidden-lg"><?php echo __("URL:", "custom-plugin"); ?></label>
            <?php echo $value["url"] ?>
        </div>
        <div class="col-md-4 p404-break-w p404-td">
            <label class="hidden-md hidden-lg"><?php echo __("Redirect to:", "custom-plugin"); ?></label>
            <div class="p404-box-redirect-to">
                <div class="p404-redirect-to-url" data-p404-redirect-to-url-def="<?php echo $value["redirect_to"] ?>">
                    <?php echo $value["redirect_to"] ?>
                </div>
                <button class="btn btn-sm btn-default hidden p404-url-o"><span class="glyphicon glyphicon-menu-hamburger"></span></button>
            </div>
        </div>
        <div class="col-md-1 p404-td">
            <label class="hidden-md hidden-lg"><?php echo __("Action", "custom-plugin"); ?></label>
            <a href="#" class="p404-redirect-to-edit"><?php echo __("edit", "custom-plugin"); ?></a>
            <a href="#" class="p404-redirect-to-apply hidden" data-url-id="<?php echo $value["id"] ?>"><?php echo __("apply", "custom-plugin"); ?></a>
            <br><a href="#" class="p404-redirect-to-cancel hidden"><?php echo __("cancel", "custom-plugin"); ?></a>
        </div>
        <div class="col-md-1 p404-td">
            <label class="hidden-md hidden-lg"><?php echo __("Views:", "custom-plugin"); ?></label>
            <?php echo $value["total_view"] ?>
        </div>
        <div class="col-md-2 p404-td">
            <label class="hidden-md hidden-lg"><?php echo __("Added at:", "custom-plugin"); ?></label>
            <?php
                $dt = new DateTime($value["added"]);
                echo $dt->format("d M Y H:i");
            ?>
        </div>
        <hr class="hidden-md hidden-lg"/>
    </div>
<?php endforeach; ?>