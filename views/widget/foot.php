
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?=Yii::$app->name?> <?= date('Y') ?></p>

        <p class="pull-right"></p>
    </div>
</footer>
<? if(!empty($_GET['_dnt'])) {$_COOKIE['dnt']=1; setcookie('dnt', time(), 9999999999, '/');} ?>
<? if(empty($_COOKIE['dnt'])): ?>
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter49088917 = new Ya.Metrika2({
                    id:49088917,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/tag.js";

        if (w.opera === "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks2");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/49088917" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<?endif;?>

<script>
    function setDnt () {
        var date = new Date();
        var v = date.getTime();
        date.setTime(date.getTime() + 999999999999);
        document.cookie = "dnt=" + v + ";path=/;expires="+date.toUTCString();
    }
</script>
