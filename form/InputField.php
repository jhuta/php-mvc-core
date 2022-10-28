<?php

namespace jhuta\phpmvccore\form;

use jhuta\phpmvccore\Model;

class InputField extends BaseField {
  public const TYPE_TEXT     = 'text';
  public const TYPE_PASSWORD = 'password';
  public const TYPE_DATE     = 'date';
  public const TYPE_NUMBER   = 'number';

  public string $type;

  public function __construct(
    public Model $model,
    public string $attribute
  ) {
    parent::__construct($model, $attribute);
    $this->type = self::TYPE_TEXT;
  }


  public function passwordField() {
    $this->type = self::TYPE_PASSWORD;
    return $this;
  }

  public function dateField() {
    $this->type = self::TYPE_DATE;
    return $this;
  }

  public function numberField() {
    $this->type = self::TYPE_NUMBER;
    return $this;
  }

  public function renderInput(): string {
    return sprintf(
      '<input type="%s" name="%s" value="%s" class="block w-full flex-1 rounded-none rounded-r-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm %s" />',
      $this->type,
      $this->attribute,
      $this->model->{$this->attribute},
      $this->model->hasError($this->attribute) ? 'border-red-500' : ''
    );
  }
}
  /* zmienic sprintf na... named replaced - str_replace
  function parse_template($filename, $data) {
    // example template variables {a} and {bc}
    // example $data array
    // $data = Array("a" => 'one', "bc" => 'two');
    $q = file_get_contents($filename);
    foreach ($data as $key => $value) {
      $q = str_replace('{' . $key . '}', $value, $q);
    }
    return $q;
  }
  */


  /*
<?= $model->hasError('firstName') ? 'border-red-500' : ''; ?>

<?php if ($model->hasError('firstName')) : ?>
<div class="text-red-500 text-sm">
  <?= $model->getFirstError('firstName') ?>
</div>
<?php endif; ?>

*/

// public static function begin($action, $method = 'post') {
// echo sprintf('<form action="%s" method="%s">', $action, $method);
  // return new Form();
  // }