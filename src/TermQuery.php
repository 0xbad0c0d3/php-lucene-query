<?php

namespace MakinaCorpus\Lucene;

/**
 * Represent a simple user term or phrase
 */
class TermQuery extends AbstractFuzzyQuery
{
    /**
     * Term
     */
    protected $term = null;

    public static function term(string $term): self
    {
        return (new static())->setValue($term);
    }

    /**
     * Set term
     *
     * @param string $term
     *
     * @return $this
     */
    public function setValue(string $term): TermQuery
    {
        $this->term = trim($term);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty(): bool
    {
        return null === $this->term;
    }

    /**
     * {@inheritdoc}
     */
    protected function toRawString(): string
    {
        return self::escapeToken($this->term);
    }
}
