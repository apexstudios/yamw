<?php
use Yamw\Lib\Mongo\AdvMongo;
use Yamw\Lib\Exceptions\HttpError\Exception;

try {
    $vid = AdvMongo::gridFs('media')->find();
    if ($vid === null || !$vid->count()) {
        throw new HttpErrorException('We\'re sorry, but no videos had been found to display', 404);
    }
} catch (Exception $e) {
    throw $e;
}
