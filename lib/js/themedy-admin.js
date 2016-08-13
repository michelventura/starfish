// Toggle Portfolio Metabox on Template Change
jQuery(document).ready(function() {
    jQuery('#page_template').change(function() {
        if (jQuery(this).val() == 'page_portfolio.php') {
            jQuery('#themedy_portfolio_meta_box').slideDown(400);
            jQuery('body').addClass('themedy-portfolio');
        } else {
            jQuery('#themedy_portfolio_meta_box').slideUp(400, function() {
                jQuery('body').removeClass('themedy-portfolio');
            });
        }
    });
});