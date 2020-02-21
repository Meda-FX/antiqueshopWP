<?php
// TODO: https://github.com/sebastianbergmann/php-timer/blob/master/src/Timer.php
class LZTimer {
    private $startTime;
    private $callsCounter;

    function __construct() {
        $this->startTime = microtime(true);
        $this->callsCounter = 0;
    }

    public function getTimer( $sec = 0 ) {

        $timeEnd = microtime(true);
        $sec = empty( $sec ) ? $timeEnd - $this->startTime : $sec;

        $this->callsCounter++;

        $h = floor($sec / 3600);
        $sec -= $h * 3600;
        $m = floor($sec / 60);
        $sec -= $m * 60;
        $total_time = sprintf('%02d', $h ).':'.sprintf('%02d', $m).':'.sprintf('%02d', $sec);

        return $total_time;
    }

    public function getCallsNumer(): int {
        return $this->callsCounter;
    }
}
/* $timer = new debugTimer();
sleep(5);
echo '<br />\n
'.$timer->getTimer(). ' seconds before call #'.$timer->getCallsNumer();

sleep(2);
echo '<br />\n
'.$timer->getTimer(). ' seconds before call #'.$timer->getCallsNumer(); */