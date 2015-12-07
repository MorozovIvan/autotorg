/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

(function ($) {
    $(document).ready(function () {
        // add active to header main menu
        var menuClass = '.topmenu';
        var url = document.location.href;
        var base = location.protocol + '//' + document.location.host + '/';
        $.each($(menuClass + " a"),function(){
            if ( url.indexOf(this.href) >= 0 && this.href !== base ){
                $(this).addClass('active');
            } else if (url == base){
                $(menuClass + " a").first().addClass('active');
            }
        });
    });

    // Handling the modal confirmation message.
    $(document).on('submit', 'form[data-confirmation]', function (event) {
        var $form = $(this),
            $confirm = $('#confirmationModal');

        if ($confirm.data('result') !== 'yes') {
            //cancel submit event
            event.preventDefault();

            $confirm
                .off('click', '#btnYes')
                .on('click', '#btnYes', function () {
                    $confirm.data('result', 'yes');
                    $form.find('input[type="submit"]').attr('disabled', 'disabled');
                    $form.submit();
                })
                .modal('show');
        }
    });
})(window.jQuery);
