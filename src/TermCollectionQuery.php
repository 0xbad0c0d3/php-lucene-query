<?php

namespace MakinaCorpus\Lucene;

/**
 * Represent a user term collection
 */
class TermCollectionQuery extends CollectionQuery
{
    /**
     * Add a term
     *
     * @param AbstractQuery|string $element
     *
     * @return $this
     */
    public function add($element): CollectionQuery
    {
        if ($element instanceof TermQuery) {
            parent::add($element);
        } else {
            parent::add(TermQuery::term((string)$element));
        }

        return $this;
    }

    /**
     * Add a list of terms
     *
     * @param string[]|TermQuery[] $terms
     *
     * @return $this
     */
    public function addAll(array $terms): TermCollectionQuery
    {
        foreach ($terms as $term) {
            $this->add($term);
        }

        return $this;
    }
}