<?php

use app\modules\developer\generators\jqplugin\Generator;

/* @var $this yii\web\View */
/* @var $generator Generator */

?>
!function ($) {
$(function () {

    $.fn.<?= $generator->pluginName ?> = function (method) {

        var methods = {

            init: function (options) {

                return this.each(function () {

                    options = $.extend({

                    }, options);

                });
            }
        };

        if (methods[method]) {

            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));

        } else if (typeof method === 'object' || !method) {

            return methods.init.apply(this, arguments);

        } else {

            $.error('Метод с именем ' + method + ' не существует для jQuery.<?= $generator->pluginName ?>');
        }
    };

})
}(window.jQuery);