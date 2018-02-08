<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\core\behaviors;

use falcon\core\base\JsonField;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class JsonBehavior extends Behavior
{
    /**
     * @var array
     */
    public $attributes = [];

    /**
     * @var null|string
     */
    public $emptyValue;

    /**
     * @var bool
     */
    public $encodeBeforeValidation = true;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_INIT => function () {
                $this->initialization();
            },
            ActiveRecord::EVENT_AFTER_FIND => function () {
                $this->decode();
            },
            ActiveRecord::EVENT_BEFORE_INSERT => function () {
                $this->encode();
            },
            ActiveRecord::EVENT_BEFORE_UPDATE => function () {
                $this->encode();
            },
            ActiveRecord::EVENT_AFTER_INSERT => function () {
                $this->decode();
            },
            ActiveRecord::EVENT_AFTER_UPDATE => function () {
                $this->decode();
            },
            ActiveRecord::EVENT_BEFORE_VALIDATE => function () {
                $this->beforeValidate();
            },
            ActiveRecord::EVENT_AFTER_VALIDATE => function () {
                $this->afterValidate();
            },
        ];
    }

    /**
     * Initialization
     *
     * @return void
     */
    protected function initialization()
    {
        foreach ($this->attributes as $attribute) {
            $this->owner->setAttribute($attribute, new JsonField());
        }
    }

    /**
     * Decode
     *
     * @return void
     */
    protected function decode()
    {
        foreach ($this->attributes as $attribute) {
            $value = $this->owner->getAttribute($attribute);
            if (!$value instanceof JsonField) {
                $value = new JsonField($value);
            }
            $this->owner->setAttribute($attribute, $value);
        }
    }


    /**
     * Encode
     *
     * @return void
     */
    protected function encode()
    {
        foreach ($this->attributes as $attribute) {
            $field = $this->owner->getAttribute($attribute);
            if (!$field instanceof JsonField) {
                $field = new JsonField($field);
            }
            $this->owner->setAttribute($attribute, (string)$field ?: $this->emptyValue);
        }
    }

    /**
     * Encode Validate
     *
     * @return void
     */
    protected function encodeValidate()
    {
        foreach ($this->attributes as $attribute) {
            $field = $this->owner->getAttribute($attribute);
            if ($field instanceof JsonField) {
                $this->owner->setAttribute($attribute, (string)$field ?: null);
            }
        }
    }

    /**
     * Before Validate
     *
     * @return void
     */
    protected function beforeValidate()
    {
        if ($this->encodeBeforeValidation) {
            $this->encodeValidate();
        }
    }

    /**
     * After Validate
     *
     * @return void
     */
    protected function afterValidate()
    {
        if ($this->encodeBeforeValidation) {
            $this->decode();
        }
    }
}