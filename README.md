malam-bitly
===========

    $bitly = Bitly::instance();
    try {
        $bitly->shorten('http://domain/long.url')->short();
    } catch (Kohana_Exception $e) {
        // error
    }