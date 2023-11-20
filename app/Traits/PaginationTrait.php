<?php

namespace App\Traits;

trait PaginationTrait
{
    /**
     * Method getLimitPagination
     * 
     * @param mixed $limit
     * @return int
     */
    public function getLimit(mixed $limit = null): int
    {
        $limit = $limit ?? request()->query('limit');
        $limit = intval($limit) ?: 20;
    
        if ($limit > 100) {
            return 100;
        }
    
        if ($limit < 1) {
            return 1;
        }
    
        return $limit;
    }    

    /**
     * Method getOffsetPagination
     * 
     * @param mixed $offset
     * @return int
     */
    public function getOffset(mixed $offset = null): int
    {
        $offset = $offset ?? request()->input('offset', 1);
        $offset = intval($offset);

        if (1 > $offset) {
            return 1;
        }

        return $offset;
    }
}
