<?php

namespace jhuta\phpmvccore\form;

use jhuta\phpmvccore\Model;

abstract class BaseField {
  abstract public function renderInput(): string;
  public const TYPE_TEXT     = 'text';

  public string $type;

  public function __construct(
    public Model $model,
    public string $attribute
  ) {
    $this->type = self::TYPE_TEXT;
  }

  public function __toString() {
    return sprintf(
      '
    <div class="col-span-3 sm:col-span-2">
      <label for="TODO" class="block text-sm font-medium text-gray-700">%s</label>
      <div class="mt-1 flex rounded-md shadow-sm">
        <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500">ICO</span>
        %s
      </div>
      <div class="text-red-500 text-sm">
        %s
      </div>
    </div>',
      $this->model->getLabel($this->attribute),
      $this->renderInput(),
      $this->model->getFirstError($this->attribute)
    );
  }
}