<?php foreach ($data as $value): ?>
    <div class="row p404-item" data-item-id="<?php echo $value["id"] ?>">
        <div class="col-md-4 p404-break-w p404-td">
            <label class="hidden-md hidden-lg">URL:</label>
            <?php echo $value["url"] ?>
        </div>
        <div class="col-md-4 p404-break-w p404-td">
            <label class="hidden-md hidden-lg">Redirect to:</label>
            <div class="p404-box-redirect-to">
                <div class="p404-redirect-to-url" data-p404-redirect-to-url-def="<?php echo $value["redirect_to"] ?>">
                    <?php echo $value["redirect_to"] ?>
                </div>
                <button class="btn btn-sm btn-default hidden p404-url-o"><span class="glyphicon glyphicon-menu-hamburger"></span></button>
            </div>
        </div>
        <div class="col-md-1 p404-td">
            <label class="hidden-md hidden-lg">Action:</label>
            <a href="#" class="p404-redirect-to-edit">edit</a>
            <a href="#" class="p404-redirect-to-apply hidden" data-url-id="<?php echo $value["id"] ?>">apply</a>
            <br><a href="#" class="p404-redirect-to-cancel hidden">cancel</a>
        </div>
        <div class="col-md-1 p404-td">
            <label class="hidden-md hidden-lg">Views:</label>
            <?php echo $value["total_view"] ?>
        </div>
        <div class="col-md-2 p404-td">
            <label class="hidden-md hidden-lg">Added at:</label>
            <?php
                $dt = new DateTime($value["added"]);
                echo $dt->format("Y-m-d H:i");
            ?>
        </div>
        <hr class="hidden-md hidden-lg"/>
    </div>
<?php endforeach; ?>