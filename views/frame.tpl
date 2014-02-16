<!DOCTYPE html>
<html lang="hu">
    <head>
        <title>{$SITE.titlePrefix}{$APP.title}</title>
        {if="isset($APP.keywords)"}<meta name="keywords" content="{$APP.keywords}" />{/if}
        {if="isset($APP.description)"}<meta name="description" content="{$APP.description}" />{/if}
        {if="isset($SITE.author)"}<meta name="author" content="{$SITE.author}" />{/if}
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        {if="isset($SITE.favicon)"}<link rel="shortcut icon" type="image/x-icon" href="{$SITE.favicon}" />{/if}
        {loop="SOURCES.stylesheets"}<link rel="stylesheet" href="{$value}" />{/loop}
        {loop="SOURCES.less"}<link rel="stylesheet/less" href="{$value}" />{/loop}
    </head>
    <body>
        <!-- layout content start -->{$LAYOUT_CONTENT}<!-- layout content end -->

        {loop="SOURCES.javascripts"}<script type="text/javascript" src="{$value}"></script>{/loop}
        {if="isset($APP.analytics)"}
            <script>
              (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
              (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
              m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
              })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
              ga('create', '{$APP.analytics}', '{$SITE.url}');
              ga('send', 'pageview');
            </script>
        {/if}
    </body>
</html>