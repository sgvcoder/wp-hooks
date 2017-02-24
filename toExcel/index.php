<button id="create">create</button>
<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script>
    jQuery("#create").click(function(e){

        e.preventDefault();

        jQuery.ajax({
            url: "http://wordpress.local/wp-content/plugins/custom-plugin/toExcel/newPHPClass.php",
            method: "post",
            dataType: "json",
            data: {},
            success: function(r){

                if(!r.href){

                    fd(r);
                }

            }
        });
    });

    function fd(d){
        jQuery.ajax({
            url: "http://wordpress.local/wp-content/plugins/custom-plugin/toExcel/newPHPClass.php",
            method: "post",
            dataType: "json",
            data: d,
            success: function(r){

                if(!r.href){

                    fd(r);
                }
            }
        });
    }
</script>