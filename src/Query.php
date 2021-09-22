<?php

namespace MakinaCorpus\Lucene;

class Query extends CollectionQuery
{
    /**
     * Require operator
     */
    const OP_REQUIRE = '+';

    /**
     * Prohibit operator
     */
    const OP_PROHIBIT = '-';

    /**
     * Boost operator
     */
    const OP_BOOST = '^';

    /**
     * Fuzzyness / roaming operator
     */
    const OP_FUZZY_ROAMING = "~";

    /**
     * And operator
     */
    const OP_AND = 'AND';

    /**
     * Or operator
     */
    const OP_OR = 'OR';

    /**
     * '*' wildcard
     */
    const WILDCARD_ALL = '*';

    /**
     * Create new term collection statement
     *
     * @param string $operator
     * @return TermCollectionQuery
     */
    public function createTermCollection($operator = Query::OP_AND): TermCollectionQuery
    {
        $statement = new TermCollectionQuery();
        $statement->setOperator($operator);

        $this->add($statement);

        return $statement;
    }

    /**
     * Create new term collection statement
     *
     * @return CollectionQuery
     */
    public function createCollection($operator = Query::OP_AND): CollectionQuery
    {
        $statement = new CollectionQuery();
        $statement->setOperator($operator);

        $this->add($statement);

        return $statement;
    }

    /**
     * Create new term statement
     *
     * @return TermQuery
     */
    public function createTerm(): TermQuery
    {
        $statement = new TermQuery();

        $this->add($statement);

        return $statement;
    }

    /**
     * Create new arbitrary range statement
     *
     * @return RangeQuery
     */
    public function createRange(): RangeQuery
    {
        $statement = new RangeQuery();

        $this->add($statement);

        return $statement;
    }

    /**
     * Create new arbitrary range statement
     *
     * @param null $field
     * @return DateRangeQuery
     */
    public function createDateRange($field = null): DateRangeQuery
    {
        $statement = new DateRangeQuery();
        $statement->setField((string)$field);

        $this->add($statement);

        return $statement;
    }

    /**
     * Match single term to this query
     *
     * @param string|null $field
     * @param string|TermQuery $term
     * @param float|null $boost
     * @param float|null $fuzzyness
     *
     * @return $this
     */
    public function matchTerm(string $field = null, $term = null, ?float $boost = null, float $fuzzyness = null): Query
    {
        $this
            ->createTerm()
            ->setValue((string)$term)
            ->setFuzzyness($fuzzyness)
            ->setBoost((float)$boost)
            ->setField((string)$field)
        ;

        return $this;
    }

    /**
     * Require range
     *
     * @param string|null $field
     * @param mixed $start
     * @param mixed $stop
     * @param boolean $inclusive
     *
     * @return $this
     */
    public function requireRange(string $field = null, $start = null, $stop = null, bool $inclusive = true): Query
    {
        $this
            ->createRange()
            ->setField((string)$field)
            ->setInclusive($inclusive)
            ->setRange($start, $stop)
        ;

        return $this;
    }

    /**
     * Require date range
     *
     * @param string|null $field
     * @param int|string|\DateTime $start
     *   Timestamp, \DateTime parsable string or \DateTime object
     * @param int|string|\DateTime $stop
     *   Timestamp, \DateTime parsable string or \DateTime object
     * @param boolean $inclusive
     *
     * @return $this
     */
    public function requireDateRange(string $field = null, $start = null, $stop = null, bool $inclusive = true): Query
    {
        $this
            ->createDateRange()
            ->setInclusive($inclusive)
            ->setRange($start, $stop)
            ->setField((string)$field)
        ;

        return $this;
    }

    /**
     * Require single term to this query
     *
     * @param string|null $field
     * @param string|TermQuery $term
     *
     * @return $this
     */
    public function requireTerm(string $field = null, $term = null): Query
    {
        $this
            ->createTerm()
            ->setValue((string)$term)
            ->setExclusion(self::OP_REQUIRE)
            ->setField((string)$field)
        ;

        return $this;
    }

    /**
     * Prohibit single term to this query
     *
     * @param string|null $field
     * @param string|TermQuery $term
     *
     * @return $this
     */
    public function prohibitTerm(string $field = null, $term = null): Query
    {
        $this
            ->createTerm()
            ->setValue((string)$term)
            ->setField((string)$field)
            ->setExclusion(self::OP_PROHIBIT)
        ;

        return $this;
    }

    /**
     * Match term collection (OR by default)
     *
     * @param string|null $field
     * @param string[]|TermQuery[]|null $terms
     * @param float|null $boost
     * @param string $operator
     *
     * @return $this
     */
    public function matchTermCollection(string $field = null, array $terms = [], ?float $boost = null, string $operator = self::OP_OR): Query
    {
        $this
            ->createTermCollection()
            ->addAll($terms)
            ->setOperator($operator)
            ->setField((string)$field)
            ->setBoost((float)$boost)
        ;

        return $this;
    }

    /**
     * Require term collection (OR by default)
     *
     * @param string $field
     * @param string[]|TermQuery[]|null $terms
     * @param string $operator
     *
     * @return $this
     */
    public function requireTermCollection(string $field, array $terms, string $operator = self::OP_OR): Query
    {
        $this
            ->createTermCollection()
            ->addAll($terms)
            ->setOperator($operator)
            ->setField($field)
            ->setExclusion(self::OP_REQUIRE)
        ;

        return $this;
    }
}
