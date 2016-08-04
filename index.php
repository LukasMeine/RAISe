<head>
    <link rel="stylesheet" href="systemtests/testResults.css">
    <meta charset="ISO-8859-1">
</head>
<body>

    <h1>RAISe System Tests</h1>
    <?php

    include "vendor/autoload.php";
    include "systemtests/TestManager.php";

    /**
     * Currently the address to which the tests will be sent are hard-coded in the TestManager class;
     */
    new TestManager();
    ?>
</body>