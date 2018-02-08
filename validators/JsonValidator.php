<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\validators;

use falcon\core\base\JsonField;
use yii\base\InvalidParamException;
use yii\db\BaseActiveRecord;
use yii\validators\Validator;

class JsonValidator extends Validator
{
    /**
     * @var bool
     */
    public $merge = false;

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if (!$value instanceof JsonField) {
            try {
                $new = new JsonField($value);
                if ($this->merge) {
                    /** @var BaseActiveRecord $model */
                    $old = new JsonField($model->getOldAttribute($attribute));
                    $new = new JsonField(array_merge($old->toArray(), $new->toArray()));
                }
                $model->$attribute = $new;
            } catch (InvalidParamException $e) {
                $this->addError($model, $attribute, $e->getMessage());
                $model->$attribute = new JsonField();
            }
        }
    }
}