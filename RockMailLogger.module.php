<?php namespace ProcessWire;
class WireMailPropGetter extends WireMail {
  public function getProps() {
    return array_keys($this->mail);
  }
}
class RockMailLogger extends WireData implements Module {
  public static function getModuleInfo() {
    return [
      'title' => 'WireMail Mail Logger',
      'version' => '0.0.1',
      'summary' => 'Logs all send() operations to the PW logs',
      'autoload' => true,
      'singular' => true,
      'icon' => 'stack-overflow',
    ];
  }

  public function init() {
    $this->addHookAfter("WireMail::send", $this, "LogMailSend");
  }

  /**
   * Log this send action to logs
   */
  public function LogMailSend($event) {
    $mail = $event->object;
    $this->log(print_r([
      'sent' => $event->return,
      'mail' => $this->getDump($mail),
    ], 1));
  }

  /**
   * Get mail data as JSON
   */
  public function getJSON($mail) {
    return json_encode($this->getArr($mail));
  }

  /**
   * Get mail data array
   */
  public function getArr($mail) {
    $props = new WireMailPropGetter();
    $props = $props->getProps();

    $arr = [];
    foreach($props as $prop) $arr[$prop] = $mail->get($prop);
    return $arr;
  }

  /**
   * Get log string for PW logs
   */
  public function getDump($mail) {
    return print_r($this->getArr($mail), 1);
  }
}