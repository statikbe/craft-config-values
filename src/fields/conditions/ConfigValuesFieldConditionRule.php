<?php

namespace statikbe\configvaluesfield\fields\conditions;

use statikbe\configvaluesfield\ConfigValuesField;
use craft\base\conditions\BaseMultiSelectConditionRule;
use craft\fields\conditions\FieldConditionRuleInterface;
use craft\fields\conditions\FieldConditionRuleTrait;
use statikbe\configvaluesfield\fields\ConfigValuesFieldField;
use yii\base\InvalidConfigException;

class ConfigValuesFieldConditionRule extends BaseMultiSelectConditionRule implements FieldConditionRuleInterface
{
    use FieldConditionRuleTrait;

    /**
     * @inheritdoc
     */
    protected function options(): array
    {
        if (!$this->field() instanceof ConfigValuesFieldField) {
            return [];
        }

        return ConfigValuesField::getInstance()->getSettings()->data[$this->field()->dataSet];
    }

    /**
     * @inheritdoc
     */
    protected function inputHtml(): string
    {
        if (!$this->field() instanceof ConfigValuesFieldField) {
            throw new InvalidConfigException();
        }

        return parent::inputHtml();
    }

    /**
     * @inheritdoc
     */
    protected function elementQueryParam(): ?array
    {
        if (!$this->field() instanceof ConfigValuesFieldField) {
            return null;
        }

        return $this->paramValue();
    }

    /**
     * @inheritdoc
     */
    protected function matchFieldValue($value): bool
    {
        if (!$this->field() instanceof ConfigValuesFieldField) {
            return true;
        }

        return $this->matchValue($value);
    }
}
