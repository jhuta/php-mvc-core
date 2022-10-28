<?php

namespace jhuta\phpmvccore\form;

class TextareaField extends BaseField {
  //  

  public function renderInput(): string {
    return sprintf(
      '<textarea name="%s" class="block w-full flex-1 rounded-none rounded-r-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm %s">%s</textarea>',
      $this->attribute,
      $this->model->hasError($this->attribute) ? 'border-red-500' : '',
      $this->model->{$this->attribute},
    );
  }
}