<?php

namespace App\Helpers;

class DatabaseHelper
{
    /**
     * Sanitize column name for use in queries
     * 
     * @param string $column
     * @return string
     */
    public static function sanitizeColumn($column)
    {
        $allowedColumns = [
            'id',
            'name',
            'email',
            'created_at',
            'updated_at',
            // Add all your legitimate column names here
        ];

        return in_array($column, $allowedColumns) ? $column : 'id';
    }

    /**
     * Sanitize sort direction
     * 
     * @param string $direction
     * @return string
     */
    public static function sanitizeDirection($direction)
    {
        return strtolower($direction) === 'desc' ? 'desc' : 'asc';
    }
}
