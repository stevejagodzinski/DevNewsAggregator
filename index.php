<?php require 'php/service/remotehtml/ErrorReportingRemoteHTMLContentBuilder.php' ?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" type="text/css" media="all" href="css/DevNewsAggregator.css" />
    <title>Development News Aggregator</title>
</head>
<body>
    <div class="content">
        <?php echo ErrorReportingRemoteHTMLContentBuilder::getRemoteHTMLContent(); ?>
    </div>
</body>
</html>