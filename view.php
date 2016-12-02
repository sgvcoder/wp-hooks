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
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">

            <table class="table table-hover p404-urls-list">
                <thead>
                    <tr>
                        <th>#</th>
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
                            <td><?php echo $value["redirect_to"] ?></td>
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