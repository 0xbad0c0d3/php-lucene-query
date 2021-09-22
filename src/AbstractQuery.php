<?php

namespace MakinaCorpus\Lucene;

/**
 * Applies boost and prohibit/require operators over an expression
 */
abstract class AbstractQuery
{
    /**
     * Regex that matches all Lucene query syntax reserved chars
     */
    const RE_SPECIALS = '/\+|-|&|\||!|\(|\)|\{|\}|\[|\]|\^|"|~|\*|\?|\:|\\\/';

    /**
     * Add '"' chars if necessary to a token value, and escape Lucene query
     * syntax reserved chars
     *
     * @param string $token
     *   String to escape
     * @param boolean $force = FALSE
     *   Escape whatever happens
     *
     * @return string
     */
    public static function escapeToken(string $token, bool $force = false): string
    {
        $escaped = preg_replace(self::RE_SPECIALS, '\\\\\\0', $token);

        if ($force || preg_match('/ /', $escaped) || strlen($token) != strlen($escaped)) {
            return '"' . $escaped . '"';
        } else {
            return $escaped;
        }
    }

    /**
     * REQUIRE or PROHIBIT operator
     */
    protected $exclusion = null;

    /**
     * Boost float value, NULL for default
     */
    protected $boost = 0.0;

    /**
     * Field name
     *
     * @var string $field
     */
    protected $field = '';

    /**
     * Set exclusion mode
     *
     * @param $exclusion
     * @return $this
     */
    public function setExclusion($exclusion): AbstractQuery
    {
        if (!empty($exclusion) && $exclusion !== Query::OP_PROHIBIT && $exclusion !== Query::OP_REQUIRE) {
            throw new \InvalidArgumentException("Exclusion must be Query::OP_REQUIRE or Query::OP_PROHIBIT");
        }

        $this->exclusion = $exclusion;

        return $this;
    }

    /**
     * Set exclusion mode
     *
     * @param float $boost
     *   Float superior to 0 or null
     *
     * @return $this
     */
    public function setBoost(float $boost): AbstractQuery
    {
        if ($boost < 0) {
            throw new \InvalidArgumentException("Boost must be a absolute positive float");
        }

        $this->boost = $boost;

        return $this;
    }

    /**
     * Set field name
     *
     * @param string $field
     *
     * @return $this
     */
    public function setField(string $field): AbstractQuery
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Is this query empty
     *
     * @return boolean
     */
    abstract public function isEmpty(): bool;

    /**
     * Build this specific statement query string
     *
     * @return string
     */
    abstract protected function toRawString(): string;

    /**
     * Escape and apply boost and operators surrounding the given string
     *
     * @return string
     */
    public function __toString()
    {
        $raw = trim($this->toRawString());

        if (!isset($raw) || (''===$raw)) {
            return '';
        }

        if ($this->field) {
            $raw = self::escapeToken($this->field) . ':' . $raw;
        }

        if ($this->exclusion) {
            $raw = $this->exclusion . $raw;
        } elseif ($this->boost > 0) {
            $raw .= '^' . $this->boost;
        }

        return $raw;
    }
}
