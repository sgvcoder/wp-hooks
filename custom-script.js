jQuery(document).ready(function () {
    var txt = '<div class="update-message notice inline notice-warning notice-alt">';
    txt += '<p>New version!!!.';
    txt += '<a href="#" class="thickbox open-plugin-details-modal" aria-label="View A4 Barcode Generator version 0.2.7 details"> download </a>.</p>';
    txt += 'text text text text text text text text text text text';
    txt += '</div>';
    jQuery('a[href="#custom-plugin-item"]').parents(".column-description").append(txt);

    txt = '<a href="#" target="_blank">';
    txt += '<img src="/wp-content/plugins/custom-plugin/logo.png" width="125" style="margin: 10px 0;">';
    txt += '</a>';
    jQuery('a[href="#custom-plugin-item"]').parents("tr").find("td:first").append(txt);
});