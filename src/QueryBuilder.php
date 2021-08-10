<?php

namespace Jdefez\Graphql;

class QueryBuilder
{
    public Query $query;

    public function __construct()
    {
        $this->query = new Query();
    }

    public static function query(): QueryBuilder
    {
        return new self();
    }

    public function __toString(): string
    {
        return $this->query->toString();
    }

    public function addField(Field $field): QueryBuilder
    {
        $this->query->addField($field);

        return $this;
    }
}
