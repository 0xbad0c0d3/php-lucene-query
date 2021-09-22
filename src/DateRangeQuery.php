<?php

namespace MakinaCorpus\Lucene;

use DateTime;

class DateRangeQuery extends RangeQuery
{
    /**
     * {@inheritdoc}
     */
    protected function renderElement($value): string
    {
        if ($value instanceof DateTime) {
            return $value->format(DateTime::ISO8601);
        }

        if (is_string($value)) {
            if ($date = new DateTime($value)) {
                return $date->format(DateTime::ISO8601);
            }
        }

        if (is_int($value)) {
            if ($date = new DateTime('@' . $value)) {
                return $date->format(DateTime::ISO8601);
            }
        }

        return (string)$value;
    }
}
