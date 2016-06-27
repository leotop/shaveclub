<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
</div>
</main>

<footer class="footer">
    <div>
        <div class="footer-soc">
           <?include($_SERVER["DOCUMENT_ROOT"]."/include/footer_soc.php");?>
        </div>

        <?$APPLICATION->IncludeComponent(
            "bitrix:menu",
            "bottom_menu_overall",
            array(
                "ALLOW_MULTI_SELECT" => "N",
                "CHILD_MENU_TYPE" => "bottom",
                "DELAY" => "N",
                "MAX_LEVEL" => "1",
                "MENU_CACHE_GET_VARS" => array(
                ),
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "ROOT_MENU_TYPE" => "bottom",
                "USE_EXT" => "N",
                "COMPONENT_TEMPLATE" => "bottom_menu"
            ),
            false
        );?>
        <div class="clear"></div>
        <div class="link-container">
            <a href="#">� <?=date("Y")?> <?=GetMessage("SHAVECLUB");?></a>
            <a href="/about/privacy-policy/"><?=GetMessage("PRIVACY_POLICY");?></a>
            <a href="/about/delivery/"><?=GetMessage("DELIVERY");?></a></div>
    </div>
                </footer>
                <!-- .content -->
                <div class="clear"></div>


            </div>


            <!-- .footer -->
        </div>
        <!-- .wrapper -->
        <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter20899105 = new Ya.Metrika({id:20899105,
                        webvisor:true,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true});
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript><div><img src="//mc.yandex.ru/watch/20899105" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
    <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-39970778-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
    </script>
</body>
</html>