<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script type="text/javascript">
    function updateTranslatorPaneState() {
        var srcVal = getSrcTypeval();
        var destVal = getDestTypeVal();

        $("#pane-src-label").text(getSrcTypeText());

        if (srcVal === 'thai') {
            $("#hint-thai").show();
        } else {
            $("#hint-thai").hide();
        }

        if (srcVal === 'lao' || destVal === 'lao') {
            $("#hint-lao").show();
        } else {
            $("#hint-lao").hide();
        }

        $("#src-txt").toggleClass("lao", srcVal === 'lao');
        $("#src-txt").toggleClass("brahmi", srcVal === 'brahmi');
        $("#src-txt").toggleClass("siddham", srcVal === 'siddham');
    }

    $("#src-type, #dest-type, #lang").on('change', function () {
        updateTranslatorPaneState();
    });

    $("#swap-script-btn").on('click', function () {
        var srcVal = getSrcTypeval();
        var destVal = getDestTypeVal();
        // Swap only if both scripts exist in the opposite dropdown
        if ($("#src-type option[value='" + destVal + "']").length &&
            $("#dest-type option[value='" + srcVal + "']").length) {
            var convertedOut = transliteration();
            $("#src-type").val(destVal);
            $("#dest-type").val(srcVal);
            if (convertedOut && convertedOut.trim() !== "") {
                $("#src-txt").val(convertedOut);
            }
            $("#src-type").trigger('change');
        }
    });

    $("#clear-src-btn").on('click', function () {
        $("#src-txt").val('').focus();
    });

    $(document).ready(function () {
        updateTranslatorPaneState();
    });
</script>
