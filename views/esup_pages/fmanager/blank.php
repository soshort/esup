<!-- elFinder CSS (REQUIRED) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/elfinder/2.1.29/css/elfinder.full.min.css" integrity="sha256-6xgSwKeUTojt0JyIieijNPupZi96Qm78QinN4Ui5fbc=" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/elfinder/2.1.29/css/theme.min.css" integrity="sha256-EBf9xfjiYBGD7aZIDZak7QEiT4AhhQ5zVBnB7YMjDa8=" crossorigin="anonymous" />
<!-- elFinder JS (REQUIRED) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/elfinder/2.1.29/js/elfinder.full.min.js" integrity="sha256-m0g9zzaeCitJx8DKF5NjGII4nh2gi8ohsMndb6rrepg=" crossorigin="anonymous"></script>
<!-- elFinder translation (OPTIONAL) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/elfinder/2.1.29/js/i18n/elfinder.ru.min.js" integrity="sha256-CtjkVOUrvFsy3Ncx47wXC1zu6iD+TSy2VR/EMvSWviA=" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf-8">
    // Helper function to get parameters from the query string.
    function getUrlParam(paramName) {
        var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
        var match = window.location.search.match(reParam) ;

        return (match && match.length > 1) ? match[1] : '' ;
    }

    $(function(){
        var funcNum = getUrlParam('CKEditorFuncNum');

        var elf = $('#elfinder').elfinder({
            url : '/esup/fmanager/init',
            lang: 'ru',
            getFileCallback : function(file) {
                window.opener.CKEDITOR.tools.callFunction(funcNum, file);
                window.close();
            },
            resizable: false
        }).elfinder('instance');
    });
</script>
<div id="elfinder"></div>