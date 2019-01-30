jQuery(document).ready(function() {
    jQuery('input:radio[name=payment_option]').click(function () {
        jQuery('input:radio[name=payment_option]').each(function () {
            if (jQuery(this).is(':checked')) {
                jQuery(this).addClass('active');
                jQuery(this).parent('li').children('label').css('font-weight', 'bold');

                var paymentMethod = jQuery('input:radio[name=payment_option]:checked').val();
                if(paymentMethod=='cc_cash'){
                    jQuery('._pay_later').show('slow');
                }else{
                    jQuery('._pay_later').hide('slow');
                    jQuery(this).parent('li').children('div.details').show();    
                }
                
            }
            else {
                jQuery(this).removeClass('active');
                jQuery(this).parent('li').children('label').css('font-weight', 'normal');
                jQuery(this).parent('li').children('div.details').hide();
            }
        });
    });
    jQuery('#btn_continue').click(function () {
        var paymentMethod = jQuery('input:radio[name=payment_option]:checked').val();
        // if(paymentMethod == '' || paymentMethod === undefined || paymentMethod === null) {
        //     alert('Pelase Select Payment Method!');
        //     return;
        // }
        // if(paymentMethod == 'cc_merchantpage' || paymentMethod == 'installments_merchantpage') {
        //     window.location.href = 'confirm-order.php?payment_method='+paymentMethod;
        // }
        if(paymentMethod == 'cc_merchantpage2') {
            // var isValid = payfortFortMerchantPage2.validateCcForm();
            // if(isValid) {
                getPaymentPage(paymentMethod);
            // }
        }
        else{
            // getPaymentPage(paymentMethod);
        }
    });
});    